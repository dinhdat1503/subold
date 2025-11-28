<?php
function siteSetting($key, $default = null)
{
    return \App\Models\SiteSetting::getValue($key, $default);
}
function getDomain()
{
    return request()->getHost();
}
function timeAgo($date)
{
    $timestamp = strtotime($date);

    if (!$timestamp) {
        return "Không Xác Định";
    }

    $units = [
        'giây' => 60,
        'phút' => 60,
        'giờ' => 24,
        'ngày' => 30,
        'tháng' => 12,
        'năm' => 10, // số 10 để dừng vòng lặp, không dùng thực tế
    ];

    $currentTime = time();
    $diff = $currentTime - $timestamp;

    if ($diff < 1) {
        return "Vừa xong";
    }

    foreach ($units as $unit => $step) {
        if ($diff < $step) {
            return $diff . " " . $unit . " trước";
        }
        $diff = floor($diff / $step);
    }

    return $diff . " năm trước";
}
function formatMoney($number, $unit = 'VNĐ', $decimals = 0)
{
    return formatNumber($number, $decimals) . ' ' . $unit;
}
function formatNumber($number, $decimals = null, $decPoint = ',', $thousandsSep = '.')
{
    $number = (float) $number;

    if ($decimals === null) {
        // Giữ nguyên số thập phân từ DB
        $decimals = strlen(substr(strrchr($number, "."), 1));
    }

    return number_format($number, $decimals, $decPoint, $thousandsSep);
}
function priceServer($price, $level, $unit = 'đ')
{
    $userDiscounts = json_decode(siteSetting("user_levels"), true);

    $discountPercent = isset($userDiscounts[$level]['discount'])
        ? (float) $userDiscounts[$level]['discount']
        : 0;

    $finalPrice = $price * (1 - $discountPercent / 100);

    // Nếu $unit = null → trả về số dạng decimal:2 (float, để lưu DB)
    if ($unit === null) {
        return (float) number_format($finalPrice, 2, '.', '');
    }

    // Nếu có $unit → format hiển thị cho user
    return formatMoney($finalPrice, $unit, 2);
}
function userLevel($html = true)
{
    if ($html) {
        switch (\Auth::user()->level ?? 0) {
            case 1:
                return '<span class="badge bg-primary badge-primary">Thành viên</span>';
            case 2:
                return '<span class="badge bg-success badge-success">Cộng tác viên</span>';
            case 3:
                return '<span class="badge bg-warning badge-warning">Đại lý</span>';
            case 4:
                return '<span class="badge bg-danger badge-danger">Nhà phân phối</span>';
            default:
                return '<span class="badge bg-secondary badge-secondary">Khách</span>';
        }
    } else {
        switch (\Auth::user()->level ?? 0) {
            case 1:
                return 'Thành viên';
            case 2:
                return 'Cộng tác viên';
            case 3:
                return 'Đại lý';
            case 4:
                return 'Nhà phân phối';
            default:
                return 'Khách';
        }
    }
}
function statusService($status, $html = true)
{
    if ($html) {
        switch ($status) {
            case 'Active':
                return '<span class="badge bg-success badge-success">Hoạt động</span>';
            default:
                return '<span class="badge bg-secondary badge-secondary">Bảo trì</span>';
        }
    } else {
        switch ($status) {
            case 'Active':
                return 'Hoạt động';
            default:
                return 'Bảo trì';
        }
    }
}
