<?php

namespace App\Http\Controllers\Client;

use App\Helpers\BackendHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    public function userPoll()
    {
        $user = Auth::user();
        if ($user) {
            $cacheKey = "detected-recharge-user-{$user->id}";
            $detectMoney = \Cache::pull($cacheKey, 0);
            if ($detectMoney > 0) {
                return BackendHelper::resApi(
                    "success",
                    "Tài khoản của bạn vừa nạp thành công: " . formatMoney($detectMoney)
                );
            }
        }
        return BackendHelper::resApi("none", "");
    }
    public function search(\Illuminate\Http\Request $request)
    {
        $validatedData = $request->validate([
            'q' => 'nullable|string|max:255',
        ], [
            'q.string' => 'Nội dung tìm kiếm phải là một chuỗi ký tự hợp lệ.',
            'q.max' => 'Nội dung tìm kiếm không được vượt quá 255 ký tự.',
        ]);
        $keyword = trim($validatedData['q'] ?? '');
        if ($keyword === '') {
            return null;
        }
        $services = \App\Models\Service::with('social:id,name,image,slug')
            ->where('name', 'like', "%$keyword%")->where('status', true)
            ->get([
                'id',
                'name',
                'social_id',
                'slug'
            ]);

        if ($services->isEmpty()) {
            return BackendHelper::resApi('success', "Thành Công", ["data" => []]);
        }

        $data = $services->map(function ($item) {
            if (!isset($item->social))
                return;
            return [
                'name' => $item->social->name . ' - ' . $item->name,
                'img' => $item->social->image,
                'url' => route('service.view', [
                    'social' => $item->social->slug,
                    'service' => $item->slug,
                ]),
            ];
        })->filter()->sortBy('name')->values()->toArray();
        return BackendHelper::resApi('success', "Thành Công", ["data" => $data]);
    }
}
