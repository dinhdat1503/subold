<?php

namespace App\Http\Controllers\Api\Suppliers;

use App\Helpers\BackendHelper;
use App\Http\Controllers\Controller;
use App\Models\Supplier;

class SmmController extends Controller
{
    private string $apiName = 'SMM';
    public $suppliers;

    public function __construct()
    {
        $this->suppliers = Supplier::where('api', $this->apiName)
            ->where('status', true)
            ->get();

        if ($this->suppliers->isEmpty()) {
            $this->suppliers = false;
        }
    }

    private function buildRequests(string $action, array $data = []): array
    {
        if (!$this->suppliers) {
            return [];
        }

        return $this->suppliers->map(function ($supplier) use ($action, $data) {
            return [
                'name' => $supplier->id,
                'url' => rtrim($supplier->base_url, '/') . '/api/v2',
                'method' => 'POST',
                'data' => [
                    'key' => $supplier->api_key,
                    'action' => $action,
                    ...$data,
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'proxy' => $supplier->proxy,
            ];
        })->toArray();
    }

    public function getBalance()
    {
        if (!$this->suppliers) {
            return BackendHelper::resApi(
                'error',
                'Code 0 - Không tìm thấy supplier nào khớp với API ' . $this->apiName,
                [],
                false
            );
        }

        $responses = BackendHelper::multiRequest(
            $this->buildRequests('balance'),
            30,
            5
        );

        $results = [];

        foreach ($this->suppliers as $supplier) {
            $response = $responses[$supplier->id] ?? null;

            if (!method_exists($response, 'successful')) {
                // \Log::error("Code 1 - Supplier {$supplier->name} gặp lỗi Exception/Error: " . (is_object($response) ? $response->getMessage() : 'Phản hồi không hợp lệ'));
                continue;
            }
            if (!$response || !$response->successful()) {
                continue;
            }

            $dataJson = $response->json();
            if (!is_array($dataJson) || empty($dataJson) || array_keys($dataJson)[0] === 'error') {
                continue;
            }
            $requiredKeys = ['balance', 'currency'];
            foreach ($requiredKeys as $key) {
                if (!isset($dataJson[$key])) {
                    continue 2;
                }
            }
            $balance = BackendHelper::sanitize($dataJson['balance'] ?? 0, 'float', ['precision' => 4]);

            $results[] = [
                'id' => $supplier->id,
                'money' => $balance,
            ];
        }
        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', ['data' => $results], false);
    }

    public function getServices()
    {
        if (!$this->suppliers) {
            return BackendHelper::resApi(
                'error',
                'Code 0 - Không tìm thấy supplier nào khớp với API ' . $this->apiName,
                [],
                false
            );
        }
        $responses = BackendHelper::multiRequest(
            $this->buildRequests('services'),
            30,
            5
        );
        $results = [];
        foreach ($this->suppliers as $supplier) {
            $response = $responses[$supplier->id] ?? null;

            if (!method_exists($response, 'successful')) {
                // \Log::error("Code 1 - Supplier {$supplier->name} gặp lỗi Exception/Error: " . (is_object($response) ? $response->getMessage() : 'Phản hồi không hợp lệ'));
                continue;
            }
            if (!$response || !$response->successful()) {
                continue;
            }
            $dataJson = $response->json();
            if (!is_array($dataJson) || empty($dataJson) || array_keys($dataJson)[0] === 'error') {
                continue;
            }
            foreach ($dataJson as $data) {
                $requiredKeys = ['service', 'name'];
                foreach ($requiredKeys as $key) {
                    if (!isset($data[$key])) {
                        continue 3;
                    }
                }
                $rate = BackendHelper::sanitize($data['rate'] ?? 0, 'float', ['precision' => 6]);
                $cost = round(($rate / $supplier->price_unit_value) * (float) $supplier->exchange_rate, 2);

                $results[] = [
                    'id' => $supplier->id,
                    'server_code' => BackendHelper::sanitize($data['service'] ?? 0, 'int'),
                    'service' => BackendHelper::sanitize($data['category'] ?? ''),
                    'title' => BackendHelper::sanitize($data['name'] ?? ''),
                    'description' => BackendHelper::sanitize($data['desc'] ?? ''),
                    'cost' => $cost,
                    'min' => BackendHelper::sanitize($data['min'] ?? 0, 'int'),
                    'max' => BackendHelper::sanitize($data['max'] ?? 0, 'int'),
                    'type' => BackendHelper::sanitize($data['type'] ?? ''),
                ];
            }
        }

        return BackendHelper::resApi('success', 'Lấy dữ liệu thành công', ['data' => $results], false);
    }

    public function placeOrder($data)
    {
        if (!$this->suppliers) {
            return BackendHelper::resApi(
                'error',
                'Code 0 - Không tìm thấy nguồn',
                [],
                false
            );
        }
        $responses = BackendHelper::multiRequest(
            $this->buildRequests('services'),
            30,
            5
        );

    }
}
