<b>⚠️ CẢNH BÁO SỐ DƯ KHÔNG ĐỦ</b>
━━━━━━━━━━━━━━━━━━

<b>📌 Tiêu đề:</b> Balance Insufficient

<i>📄 Nội dung:</i>
Số dư của bạn hiện <b>không đủ để thực hiện giao dịch!</b>

━━━━━━━━━━━━━━━━━━
<b>💰 Số dư hiện tại:</b> <code>{{ formatMoney($balance, $unit, $decimals) }}</code>

<b>👤 Username:</b> {{ $username ?? 'N/A' }}
<b>🛠️ Dịch vụ:</b> {{ $service ?? 'N/A' }}
<b>📦 Số lượng:</b> {{ $quantity ?? 'N/A' }}
<b>💵 Thành tiền:</b> <code>{{ formatMoney($total ?? 0, $unitPrice, $decimalsPrice) }}</code>
<b>💸 Tiền gốc:</b> <code>{{ formatMoney($cost ?? 0, $unitPrice, $decimalsPrice) }}</code>

━━━━━━━━━━━━━━━━━━
<b>🔗 Liên kết:</b>
<a href="{{ $link }}">{{ $link }}</a>

<b>🕓 Thời gian:</b> {{ now()->format('d/m/Y H:i') }}