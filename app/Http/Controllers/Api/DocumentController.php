<?php

namespace App\Http\Controllers\Api;

use App\Helpers\BackendHelper;
use App\Http\Controllers\Controller;
use App\Models\ServerService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function orders(Request $request)
    {
        $user = null;
        $server = null;
        $priceServer = null;
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:100',
            'server_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('server_services', 'id')->where(function ($query) {
                    return $query->where('status', true);
                }),
            ],
            'links' => 'required|min:1',
            'reaction' => 'nullable|string|in:like,love,haha,angry,sad,wow,care',
            'comments' => 'nullable|string',
            'time' => 'nullable|integer|min:1',
            'amount' => 'nullable|integer|min:1',
            'quantity' => 'nullable|integer|min:1',
        ], [
            'required' => ':attribute không được để trống',
            'token.string' => 'Token phải là một chuỗi ký tự hợp lệ',
            'token.max' => 'Token không được vượt quá :max ký tự',
            'server_id.integer' => 'Server ID phải là số nguyên',
            'server_id.min' => 'Server ID phải lớn hơn hoặc bằng :min',
            'server_id.exists' => 'Server ID không tồn tại hoặc đang bảo trì',
            'links.min' => 'Bạn phải cung cấp ít nhất :min liên kết (link)',
            'reaction.in' => 'Giá trị reaction không hợp lệ. Vui lòng chọn một trong các giá trị: :values',
            'comments.string' => 'Comments phải là chuỗi ký tự',
            'time.integer' => 'Thời gian (Time) phải là số nguyên',
            'time.min' => 'Thời gian (Time) phải lớn hơn hoặc bằng :min',
            'amount.integer' => 'Số lượng đơn hàng (Amount) phải là số nguyên',
            'amount.min' => 'Số lượng đơn hàng (Amount) phải lớn hơn hoặc bằng :min',
            'quantity.integer' => 'Số lượng (Quantity) phải là số nguyên',
            'quantity.min' => 'Số lượng (Quantity) phải lớn hơn hoặc bằng :min',
        ]);
        $validator->after(function ($validator) use ($request, &$user, &$server, &$priceServer) {
            $token = $request->input('token');
            $serverID = (int) $request->input('server_id');
            $quantity = $request->input('quantity', 0);

            $user = User::where('status', true)->with('security')->whereHas('security', function ($query) use ($token) {
                $query->where('api_token', $token);
            })->first();
            if (!$user) {
                $validator->errors()->add('token', 'Token này không tồn tại hoặc đã bị khóa');
            }
            $server = ServerService::where('id', $serverID)->where('status', true)->first();
            if ($server->action_orders['multi_link'] != true && count($request->input('links', [])) > 1) {
                $validator->errors()->add('links', 'Server này không hỗ trợ đặt nhiều link trong một đơn hàng');
            } else if ($server->action_orders['multi_link'] == true) {
                $links = $request->input('links', []);
                if (is_array($links)) {
                    foreach ($links as $link) {
                        if (!filter_var($link, FILTER_VALIDATE_URL)) {
                            $validator->errors()->add('links', "Link '{$link}' không hợp lệ.");
                        }
                    }
                } elseif (is_string($links)) {
                    if (!filter_var($links, FILTER_VALIDATE_URL)) {
                        $validator->errors()->add('links', "Link '{$links}' không hợp lệ.");
                    }
                }
            }
            if ($server->action_orders['reaction']['status'] == true) {
                $reaction = $request->input('reaction');
                if (!$reaction) {
                    $validator->errors()->add('reaction', 'Bạn phải chọn loại cảm xúc khi đặt đơn hàng này');
                }
                $serverActions = json_decode($server->action_orders['server'], true);
                if (!in_array($reaction, array_keys($serverActions))) {
                    $validator->errors()->add('reaction', 'Loại cảm xúc không hợp lệ. Vui lòng chọn một trong các loại sau: ' . implode(', ', array_keys($serverActions)));
                }
                $serverNew = ServerService::where('id', $serverActions[$reaction])->where('status', true)->first();
                if ($serverActions[$reaction] != 0 && !$serverNew) {
                    $validator->errors()->add('reaction', 'Server cảm xúc bạn chọn hiện đang bảo trì hoặc không khả dụng');
                } else {
                    $priceServer = $serverNew->price;
                }
            }
            if ($server->action_comment['status'] == true) {
                $comments = $request->input('comments', '');
                if (empty(trim($comments))) {
                    $validator->errors()->add('comments', 'Bạn phải nhập nội dung bình luận khi đặt đơn hàng này');
                } else {
                    $quantity = count(explode("\n", trim($comments)));
                    $validator->setValue('quantity', $quantity);
                }
            }
            if ($server->action_time['status'] == true) {
                $time = $request->input('time');
                $type = $server->action_time['type'];
                if (!$time) {
                    $validator->errors()->add('time', 'Bạn phải chọn số ' . $type . ' khi đặt đơn hàng này');
                }
                $serverActions = json_decode($server->action_time['server'], true);
                if (!in_array($time, array_keys($serverActions))) {
                    $validator->errors()->add('time', 'Số ' . $type . ' không hợp lệ. Vui lòng chọn một trong các loại sau: ' . implode(', ', array_keys($serverActions)));
                }
                $serverNew = ServerService::where('id', $serverActions[$time])->where('status', true)->first();
                if ($serverActions[$time] != 0 && !$serverNew) {
                    $validator->errors()->add('time', 'Server số ' . $type . ' bạn chọn hiện đang bảo trì hoặc không khả dụng');
                } else {
                    $priceServer = $serverNew->price;
                }
            }
            if ($server->action_amount['status'] == true) {
                $amount = $request->input('amount');
                $type = $server->action_amount['type'];
                if (!$amount) {
                    $validator->errors()->add('amount', 'Bạn phải chọn số ' . $type . ' khi đặt đơn hàng này');
                }
                $serverActions = json_decode($server->action_amount['server'], true);
                if (!in_array($amount, array_keys($serverActions))) {
                    $validator->errors()->add('amount', 'Số ' . $type . ' không hợp lệ. Vui lòng chọn một trong các loại sau: ' . implode(', ', array_keys($serverActions)));
                }
                $serverNew = ServerService::where('id', $serverActions[$amount])->where('status', true)->first();
                if ($serverActions[$amount] != 0 && !$serverNew) {
                    $validator->errors()->add('amount', 'Server số ' . $type . ' bạn chọn hiện đang bảo trì hoặc không khả dụng');
                } else {
                    $priceServer = $serverNew->price;
                }
            }
            if ($quantity === 0) {
                $validator->errors()->add('quantity', 'Số lượng không được để trống');
            } else if ($quantity < $server->min || $quantity > $server->max) {
                if ($quantity < $server->min || $quantity > $server->max) {
                    $validator->errors()->add('quantity', 'Số lượng phải từ ' . $server->min . ' đến ' . $server->max);
                }
            }
            if ($priceServer === null) {
                $priceServer = $server->price;
            }
        });
        if ($validator->fails()) {
            return BackendHelper::resApi(
                'error',
                $validator->errors()->first()
            );
        }
        $validated = $validator->validated();
        $status = "success";
        $message = "Đặt đơn hàng thành công";
        $data = [];
        if ($user->balance < ($priceServer * $validated['quantity'])) {
            $status = "error";
            $message = "Số dư tài khoản không đủ để thực hiện đơn hàng này";
        }
        return BackendHelper::resApi(
            $status,
            $message,
            $data
        );

    }
}
