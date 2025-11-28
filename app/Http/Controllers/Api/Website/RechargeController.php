<?php

namespace App\Http\Controllers\Api\Website;

use App\Helpers\BackendHelper;
use App\Http\Controllers\Controller;
use App\Models\RechargeLog;
use App\Models\RechargeMethod;
use Illuminate\Support\Carbon;

class RechargeController extends Controller
{
    public $recharge;

    public function __construct()
    {
        $this->recharge = RechargeMethod::where('status', true)->get();
    }
    public function getTransactionBank()
    {
        $bank = $this->recharge->where("method_type", "bank")->first();
        if (!$bank) {
            return BackendHelper::resApi('error', 'Code 0 - Không tìm thấy phương thức nạp tiền qua ngân hàng', [], false);
        }
        $headers = [
            'Content-Type' => 'application/json',
            'pay2s-token' => base64_encode($bank->api_key),
        ];
        $dataPost = [
            'bankAccounts' => $bank->account_index,
            'begin' => now()->subDay()->format('d/m/Y'),
            'end' => now()->addDay()->format('d/m/Y'),
        ];
        try {
            $response = \Http::timeout(30)
                ->connectTimeout(5)
                ->withHeaders($headers)
                ->post("https://my.pay2s.vn/userapi/transactions", $dataPost);
        } catch (\Throwable $e) {
            // \Log::error("Lỗi lấy giao dịch ngân hàng: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return BackendHelper::resApi('error', 'Code 5 - Lỗi kết nối đến API ngân hàng: ' . $e->getMessage(), [], false);
        }
        if (!method_exists($response, 'successful')) {
            return BackendHelper::resApi('error', "Code 1 - Ngân hàng gặp lỗi Exception/Error: " . (is_object($response) ? $response->getMessage() : 'Phản hồi không hợp lệ'), [], false);
        }
        if (!$response || !$response->successful()) {
            return BackendHelper::resApi('error', 'Code 2 - Có lỗi xảy ra khi lấy dữ liệu giao dịch', [], false);
        }
        $data = $response->json();
        if (empty($data)) {
            return BackendHelper::resApi('error', 'Code 3 - Có lỗi xảy ra khi đọc dữ liệu giao dịch', [], false);
        }
        if (empty($data['transactions']) || !is_array($data['transactions']) || (isset($data['status']) && $data['status'] != true)) {
            return BackendHelper::resApi('error', 'Code 4 - Không có dữ liệu giao dịch hoặc lỗi', [], false);
        }

        $apiTransactionIds = collect($data['transactions'])
            ->filter(fn($tx) => $tx['type'] === 'IN')
            ->map(fn($tx) => BackendHelper::sanitize($tx['transaction_id']))
            ->unique()
            ->toArray();
        $existingTransIds = RechargeLog::whereIn("trans_id", $apiTransactionIds)
            ->pluck('trans_id')
            ->all();
        $existingTransIdsMap = array_flip($existingTransIds);

        $dataReturn = [];
        foreach ($data['transactions'] as $tx) {
            if ($tx['type'] !== 'IN') {
                continue;
            }
            $transID = BackendHelper::sanitize($tx['transaction_id']);
            if (isset($existingTransIdsMap[$transID])) {
                continue;
            }
            $dataReturn[] = [
                'id' => $bank->id,
                'trans_id' => $transID,
                'amount' => BackendHelper::sanitize($tx['amount'], "int"),
                'note' => BackendHelper::sanitize($tx['description']),
            ];
        }
        return BackendHelper::resApi('success', 'Lấy dữ liệu giao dịch thành công', ['data' => $dataReturn], false);
    }
    public function checkTransactionCrypto()
    {
        $crypto = $this->recharge->where("method_type", "crypto")->first();
        if (!$crypto) {
            return BackendHelper::resApi('error', 'Code 0 - Không tìm thấy phương thức nạp tiền qua crypto', [], false);
        }
        $invoiceCrypto = RechargeLog::where("recharge_id", $crypto->id)->where("status", 0)->get();
        foreach ($invoiceCrypto as $invCry) {
            if ($invCry->created_at < Carbon::now()->subHour()) {
                $invCry->update([
                    'status' => 2,
                    'note' => 'Tự động huỷ vì quá hạn 1 tiếng chưa nhận USDT',
                ]);
            }
        }
        $activeInvoices = $invoiceCrypto->where('status', 0)->values();
        if ($activeInvoices->isEmpty()) {
            return BackendHelper::resApi('success', 'Code 4 - Không có giao dịch crypto đang hoạt động để kiểm tra', [], false);
        }
        $requests = [];
        foreach ($activeInvoices as $invCry) {
            $requests[] = [
                'name' => $invCry->id,
                'url' => 'https://apilist.tronscanapi.com/api/transaction-info',
                'method' => 'GET',
                'data' => [
                    'hash' => $invCry->trans_id,
                ],
            ];
        }
        $responses = BackendHelper::multiRequest($requests, 30, 5);
        $dataReturn = [];
        foreach ($activeInvoices as $invCry) {
            $response = $responses[$invCry->id];
            if (!method_exists($response, 'successful')) {
                return BackendHelper::resApi('error', "Code 1 - $invCry->id gặp lỗi Exception/Error: " . (is_object($response) ? $response->getMessage() : 'Phản hồi không hợp lệ'), [], false);
            }
            if (!$response || !$response->successful()) {
                continue;
            }
            $data = $response->json();
            if (!is_array($data) || empty($data['confirmed'])) {
                continue;
            }
            if (isset($data['confirmed']) && $data['confirmed'] === true && isset($data['contractRet']) && $data['contractRet'] === 'SUCCESS') {
                if (empty($data['trc20TransferInfo']) || !is_array($data['trc20TransferInfo']) || count($data['trc20TransferInfo']) === 0) {
                    continue;
                }
                if (Carbon::createFromTimestampMs($data['timestamp']) < Carbon::now()->subHour()) {
                    $invCry->update([
                        'status' => 2,
                        'note' => 'Tự động huỷ vì quá hạn 1 tiếng chưa nhận USDT',
                    ]);
                    continue;
                }
                $txInfo = $data['trc20TransferInfo'][0];
                $amount = BackendHelper::sanitize(($txInfo['amount_str'] ?? 0) / 1_000_000, 'float', ['precision' => 2]);
                $toAddress = $txInfo['to_address'] ?? '';
                $symbol = strtoupper($txInfo['symbol'] ?? '');
                // if ($symbol === $crypto->name && $amount > 0) {
                if ($toAddress === $crypto->account_index && $symbol === $crypto->name && $amount > 0) {
                    $dataReturn[] = [
                        'id' => $crypto->id,
                        'trans_id' => $invCry->trans_id,
                        'amount' => $amount,
                    ];
                }
            }
        }
        return BackendHelper::resApi('success', 'Lấy dữ liệu giao dịch crypto thành công', ['data' => $dataReturn], false);
    }
}
