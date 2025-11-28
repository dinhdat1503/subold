<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BackendHelper;
use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;

use App\Models\LogRecharge;
use App\Models\Order;
use App\Models\RechargeLog;
use App\Models\SiteSetting;
use App\Models\User;

use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function dashboard()
    {
        return view('admin.website.dashboard');
    }
    public function dashboardPoll(Request $request)
    {
        $validated = $request->validate(
            [
                'last_update' => 'required|integer|min:1',
            ]
        );
        $since = $validated['last_update'] > 0 ? \Carbon\Carbon::createFromTimestamp($validated['last_update']) : now()->subYear();

        // ====== CHECK CÓ DỮ LIỆU MỚI KHÔNG (query nhẹ, chỉ exists) ======
        $hasNewUsers = User::where('created_at', '>', $since)->exists();
        $hasNewOrders = Order::where('created_at', '>', $since)->exists();
        $hasNewRecharges = RechargeLog::where('created_at', '>', $since)->exists();
        $hasNewlogs = \App\Models\Log::where('created_at', '>', $since)->exists();

        $hasNewData = $hasNewUsers || $hasNewOrders || $hasNewRecharges || $hasNewlogs;

        if (!$hasNewData) {
            return BackendHelper::resApi("success", "No updates available", [
                'has_new_data' => false,
            ]);
        }

        // Có data mới: Tính full aggregate (nhanh vì index)
        $year = now()->year;
        $months = collect(range(1, 12));

        // ====== TỔNG QUAN ======
        $totalUser = User::count();

        $orderStats = Order::selectRaw("
        COUNT(id) as total_orders,
        SUM(CASE WHEN status NOT IN ('WaitingForRefund','Error','Warranty','Refunded','Cancelled')
            THEN profit ELSE 0 END
        ) as total_profit")->first();

        $rechargeStats = RechargeLog::selectRaw('SUM(amount) as total_recharge')->first();

        $totalOrder = (int) $orderStats->total_orders;
        $totalProfit = (int) $orderStats->total_profit;
        $totalRecharge = (int) $rechargeStats->total_recharge;

        // ====== THEO THÁNG ======
        $usersByMonth = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month')
            ->union($months->mapWithKeys(fn($m) => [$m => 0]))
            ->sortKeys()
            ->toArray();

        $ordersMonthly = Order::selectRaw('
        MONTH(created_at) as month,
        COUNT(*) as total_orders,
        SUM(CASE WHEN status NOT IN ("WaitingForRefund","Error","Warranty","Refunded","Cancelled")
            THEN profit ELSE 0 END) as total_profit')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->keyBy('month');

        $ordersByMonth = $months->map(fn($m) => (int) ($ordersMonthly[$m]->total_orders ?? 0))->toArray();
        $profitsByMonth = $months->map(fn($m) => (int) ($ordersMonthly[$m]->total_profit ?? 0))->toArray();

        $rechargesByMonth = RechargeLog::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month')
            ->union($months->mapWithKeys(fn($m) => [$m => 0]))
            ->sortKeys()
            ->toArray();

        // ====== THEO NGÀY ======
        $yesterdayStart = now()->subDay()->startOfDay();
        $yesterdayEnd = now()->subDay()->endOfDay();
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $getStats = fn($yesterday, $today) => [
            $yesterday,
            $today >= $yesterday ? 'fa-arrow-up' : 'fa-arrow-down',
            $yesterday > 0 ? round((($today - $yesterday) / $yesterday) * 100, 2) : 0,
            $today
        ];

        $yesterdayUsers = User::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->count();
        $todayUsers = User::whereBetween('created_at', [$todayStart, $todayEnd])->count();
        $usersDay = $getStats($yesterdayUsers, $todayUsers);

        $yesterdayOrders = Order::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->count();
        $todayOrders = Order::whereBetween('created_at', [$todayStart, $todayEnd])->count();

        $yesterdayProfit = Order::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->whereNotIn('status', ['WaitingForRefund', 'Error', 'Warranty', 'Refunded', 'Cancelled'])
            ->sum('profit');

        $todayProfit = Order::whereBetween('created_at', [$todayStart, $todayEnd])
            ->whereNotIn('status', ['WaitingForRefund', 'Error', 'Warranty', 'Refunded', 'Cancelled'])
            ->sum('profit');

        $orderDay = $getStats($yesterdayOrders, $todayOrders);
        $profitDay = $getStats($yesterdayProfit, $todayProfit);

        $yesterdayRecharge = RechargeLog::whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])->sum('amount');
        $todayRecharge = RechargeLog::whereBetween('created_at', [$todayStart, $todayEnd])->sum('amount');
        $rechargeDay = $getStats($yesterdayRecharge, $todayRecharge);

        // ====== TRẢ VỀ DATA THỰC (FULL) KHI CÓ THAY ĐỔI ======
        return BackendHelper::resApi("success", "New Data", [
            'has_new_data' => true,
            'timestamp' => now()->timestamp,
            'data' => [
                'totalUser' => $totalUser,
                'totalOrder' => $totalOrder,
                'totalProfit' => $totalProfit,
                'totalRecharge' => $totalRecharge,
                'usersByMonth' => $usersByMonth,
                'ordersByMonth' => $ordersByMonth,
                'profitsByMonth' => $profitsByMonth,
                'rechargesByMonth' => $rechargesByMonth,
                'usersDay' => $usersDay,
                'orderDay' => $orderDay,
                'profitDay' => $profitDay,
                'rechargeDay' => $rechargeDay,
            ],
        ]);
    }
    public function config()
    {
        $userLevels = json_decode(siteSetting("user_levels"), true);
        return view('admin.website.config', compact(
            "userLevels"
        ));
    }
    public function configUpdate(Request $request)
    {
        $checkboxFields = ['status', 'order_allow', 'google_recaptcha', 'cloudflare_mode'];
        $uploadFields = ['logo', 'favicon', 'image_seo'];
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'keywords' => 'required|string|max:500',
            'admin_name' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'zalo' => 'nullable|string|max:255',
            'telegram' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,ico',
            'image_seo' => 'nullable|image|mimes:jpg,jpeg,png',
            'script_header' => 'nullable|string',
            'script_footer' => 'nullable|string',
            'status' => 'nullable|boolean',
            'order_allow' => 'nullable|boolean',
            'google_recaptcha' => 'nullable|boolean',
            'cloudflare_mode' => 'nullable|boolean',
            'user_levels_discount_2' => 'nullable|numeric|min:0',
            'user_levels_money_2' => 'nullable|numeric|min:0',
            'user_levels_discount_3' => 'nullable|numeric|min:0',
            'user_levels_money_3' => 'nullable|numeric|min:0',
            'user_levels_discount_4' => 'nullable|numeric|min:0',
            'user_levels_money_4' => 'nullable|numeric|min:0',
        ]);
        $userLevels = [
            "1" => ["discount" => 0, "money" => 0],
            "2" => [
                "discount" => $validated['user_levels_discount_2'] ?? 0,
                "money" => $validated['user_levels_money_2'] ?? 0
            ],
            "3" => [
                "discount" => $validated['user_levels_discount_3'] ?? 0,
                "money" => $validated['user_levels_money_3'] ?? 0
            ],
            "4" => [
                "discount" => $validated['user_levels_discount_4'] ?? 0,
                "money" => $validated['user_levels_money_4'] ?? 0
            ],
        ];
        SiteSetting::setValue('user_levels', $userLevels);
        foreach ($validated as $key => $value) {
            if (!in_array($key, $checkboxFields)) {
                SiteSetting::setValue($key, $value);
                if ($key === 'status') {
                    \Cache::forget('site-status');
                }
            }
        }
        foreach ($checkboxFields as $field) {
            SiteSetting::setValue($field, $validated[$field] ?? false);
        }
        foreach ($uploadFields as $field) {
            if (isset($validated[$field])) {
                $oldFile = SiteSetting::getValue($field);
                $filePath = BackendHelper::uploadPublicFile($validated[$field], "assets/images/theme", $oldFile, $field);
                SiteSetting::setValue($field, $filePath);
            }
        }
        UserHelper::logWrite("AdminSettings", "Cập Nhật Cài Đặt Cho Website");
        return redirect()->back()->with('success', 'Cập nhật thành công');
    }
}
