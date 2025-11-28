<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RechargeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class WebsiteController extends Controller
{
    public function home()
    {
        $socials = \App\Models\SocialService::with('services')->where('status', true)->get();

        $userID = Auth::check() ? Auth::id() : null;

        $orderCounts = Order::select('status')
            ->selectRaw('count(id) as total_count')
            ->where('user_id', $userID)
            ->groupBy('status')
            ->pluck('total_count', 'status')
            ->toArray();
        $waitRefundOrders = $orderCounts['WaitingForRefund'] ?? 0;
        $warrantyOrders = $orderCounts['Warranty'] ?? 0;
        $activeOrders = $orderCounts['Active'] ?? 0;
        $completedOrders = $orderCounts['Completed'] ?? 0;
        $pendingOrders = $orderCounts['Pending'] ?? 0;
        $refundedOrders = $orderCounts['Refunded'] ?? 0;
        $cancelledOrders = $orderCounts['Cancelled'] ?? 0;
        $errorOrders = $orderCounts['Error'] ?? 0;

        $monthlyRecharge = RechargeLog::where('user_id', $userID)
            ->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->sum('amount_received');

        $balance = Auth::user()->balance ?? 0;
        $totalRecharge = Auth::user()->total_recharge ?? 0;

        $userLevels = json_decode(siteSetting('user_levels'), true);
        $maxMoney = collect($userLevels)->max('money') ?? 0;

        $balanceProgress = $monthlyRecharge > 0 ? min(100, ($balance / $monthlyRecharge) * 100) : 0;
        $monthlyRechargeProgress = $totalRecharge > 0 ? min(100, ($monthlyRecharge / $totalRecharge) * 100) : 0;
        $totalRechargeProgress = $maxMoney > 0 ? min(100, ($totalRecharge / $maxMoney) * 100) : 0;

        $level = Auth::user()->level ?? 1;
        $maxLevel = 4;
        $levelProgress = ($level / $maxLevel) * 100;

        $recentOrders = Order::with(['user:id,username', 'server:id,title'])->orderBy('id', 'desc')->limit(20)->get();
        $recentRecharges = RechargeLog::with(['user:id,username', 'recharge:id,name,method_type'])->where('status', 1)
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        return view('client.website.home', compact(
            'recentOrders',
            'recentRecharges',
            'monthlyRecharge',
            'socials',
            'waitRefundOrders',
            'warrantyOrders',
            'activeOrders',
            'completedOrders',
            'pendingOrders',
            'refundedOrders',
            'cancelledOrders',
            'errorOrders',
            'balanceProgress',
            'totalRechargeProgress',
            'monthlyRechargeProgress',
            'levelProgress'
        ));
    }
    public function maintaince()
    {
        $status = Cache::get('site-status', 1);
        if ($status == 1) {
            return redirect()->route('home');
        }
        return view('error.maintaince');
    }
    public function ipBlock()
    {
        $ipBlockList = Cache::get('blocked-ips-list', []);
        $ip = request()->ip();
        if (!array_key_exists($ip, $ipBlockList)) {
            return redirect()->route('guest.home');
        }
        $reason = $ipBlockList[$ip] ?? 'Bị chặn truy cập';
        return view('error.ip-block', compact('ip', 'reason'));
    }
    public function userBan(Request $request)
    {
        $reason = Cache::get('ban-reason-' . $request->ip());
        if (!$reason || Auth::check()) {
            return redirect()->route('guest.home');
        }
        return view('error.ban', compact('reason'));
    }
    public function terms()
    {
        return view("client.info.terms");
    }
    public function policy()
    {
        return view("client.info.policy");
    }
    public function guide()
    {
        return view("client.info.guide");
    }
    public function blog(Request $request, $name)
    {
        $request->merge(['slug' => $name]);
        $validator = \Validator::make($request->all(), [
            'slug' => 'required|string|max:255',
        ], [
            'slug.required' => 'Thiếu tên bài viết',
            'slug.string' => 'Tên bài viết không hợp lệ',
            'slug.max' => 'Tên bài viết không được vượt quá 255 ký tự',
        ]);
        if ($validator->fails()) {
            return redirect()->to(route('guest.home'))->withErrors($validator)->withInput();
        }
        $validatedData = $validator->validated();
        $blog = \App\Models\Blog::where('slug', $validatedData['slug'])->firstOrFail();
        $response = response()->view('client.website.blog', compact('blog'));
        if (!$request->hasCookie('utm_source')) {
            $response->cookie('utm_source', $blog->utm_source, 60 * 24 * 7);
        }
        return $response;
    }
}
