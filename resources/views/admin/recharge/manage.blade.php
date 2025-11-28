@extends('admin.layout.app')
@section('title', 'Cấu hình nạp tiền')

@section('content')
    <div class="col-md-12">
        <div class="card border shadow-sm rounded-4">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="ti ti-credit-card text-success me-2"></i> Cấu hình nạp tiền cho hệ thống
                </h4>

                <form action="{{ route('admin.recharge.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- ======== NGÂN HÀNG ======== -->
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 shadow-sm">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="ti ti-building-bank me-2"></i> Ngân hàng Việt Nam
                                </h5>

                                <div class="mb-3">
                                    <label class="form-label">Tên ngân hàng</label>
                                    <input type="text" name="bank_name" class="form-control" value="{{ $bank->name ?? '' }}"
                                        readonly placeholder="VD: MB Bank">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tên chủ tài khoản</label>
                                    <input type="text" name="bank_account_name" class="form-control"
                                        value="{{ old('bank_account_name', $bank->account_name ?? '') }}"
                                        placeholder="VD: NGUYEN VAN A">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Số tài khoản</label>
                                    <input type="text" name="bank_account_index" class="form-control"
                                        value="{{ old('bank_account_index', $bank->account_index ?? '') }}"
                                        placeholder="VD: 123456789">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">API Token</label>
                                    <input type="text" name="bank_api_key" class="form-control"
                                        value="{{ old('bank_api_key', $bank->api_key ?? '') }}" placeholder="Nhập API Key">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Số tiền nạp tối thiểu</label>
                                    <input type="number" name="bank_recharge_min" class="form-control"
                                        value="{{ old('bank_recharge_min', $bank->recharge_min ?? 0) }}" min="0"
                                        placeholder="VD: 1000">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nội Dung Nạp</label>
                                    <input type="text" name="bank_code" class="form-control"
                                        value="{{ old('bank_code', $siteSettings['bank_code'] ?? 0) }}"
                                        placeholder="VD: NAPTIEN">
                                </div>

                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0 fw-semibold">Kích hoạt ngân hàng</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="bank_status" value="1" {{ old('bank_status', isset($bank) && $bank->status == 1 ? 'checked' : '') }}>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ghi chú ngân hàng</label>
                                    <textarea name="bank_note" rows="4"
                                        class="form-control tinymce">{{ old('bank_note', $bank->note ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- ======== CRYPTO ======== -->
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 shadow-sm">
                                <h5 class="fw-bold text-warning mb-3">
                                    <i class="ti ti-coin me-2"></i> Cấu hình Crypto (USDT, BTC...)
                                </h5>

                                <div class="mb-3">
                                    <label class="form-label">Loại coin</label>
                                    <input type="text" name="crypto_name" class="form-control" readonly
                                        value="{{ old('crypto_name', $crypto->name ?? '') }}" placeholder="VD: USDT">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mạng lưới (Network)</label>
                                    <input type="text" name="crypto_network" class="form-control"
                                        value="{{ old('crypto_network', $crypto->network ?? '') }}"
                                        placeholder="VD: TRC20, ERC20">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Địa chỉ ví</label>
                                    <input type="text" name="crypto_account_index" class="form-control"
                                        value="{{ old('crypto_account_index', $crypto->account_index ?? '') }}"
                                        placeholder="VD: TYswEcnYLJhn...">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tỷ giá quy đổi</label>
                                    <input type="number" step="0.01" name="crypto_exchange_rate" class="form-control"
                                        value="{{ old('crypto_exchange_rate', $crypto->exchange_rate ?? '') }}"
                                        placeholder="VD: 25,000">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Số tiền nạp tối thiểu</label>
                                    <input type="number" name="crypto_recharge_min" class="form-control"
                                        value="{{ old('crypto_recharge_min', $crypto->recharge_min ?? 0) }}" min="0"
                                        placeholder="VD: 10">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Logo chuyển tiền</label>
                                    <input type="file" name="crypto_wallet_qr" class="form-control">
                                    @if (!empty($crypto->wallet_qr))
                                        <div class="mt-2">
                                            <img src="{{ asset($crypto->wallet_qr) }}" alt="Logo" class="rounded shadow-sm"
                                                style="height: 80px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3 d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0 fw-semibold">Kích hoạt Crypto</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="crypto_status" value="1" {{ old('crypto_status', isset($crypto) && $crypto->status == 1 ? 'checked' : '') }}>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ghi chú Crypto</label>
                                    <textarea name="crypto_note" rows="4"
                                        class="form-control tinymce">{{ old('crypto_note', $crypto->note ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======== KHUYẾN MÃI ======== -->
                    <div class="border rounded-4 p-3 shadow-sm mt-4">
                        <h5 class="fw-bold text-success mb-3">
                            <i class="ti ti-gift me-2"></i> Cấu hình khuyến mãi theo mốc nạp
                        </h5>
                        <div class="form-check form-switch mb-3">
                            <label class="form-label mb-0 fw-semibold me-2">Bật khuyến mãi</label>
                            <input type="checkbox" class="form-check-input" name="promotion_show" value="1" {{ ($siteSettings['promotion_show'] ?? 0) == 1 ? 'checked' : '' }}>
                        </div>
                        <div id="promotion-levels">
                            @foreach ($promotionLevels as $i => $level)
                                <div class="row align-items-center mb-2 promotion-row">
                                    <div class="col-md-5">
                                        <input type="number" name="promotion_money[]" class="form-control"
                                            value="{{ $level['money'] }}" placeholder="Mức nạp (VD: 10000)">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="number" name="promotion_value[]" class="form-control"
                                            value="{{ $level['promotion'] }}" placeholder="Khuyến mãi (%)">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-center">
                            <button type="button" id="add-row" class="btn btn-outline-primary btn-sm">
                                + Thêm mốc
                            </button>
                        </div>
                    </div>


                    <!-- ======== NÚT LƯU ======== -->
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-semibold shadow">
                            <i class="ti ti-device-floppy me-2"></i> Lưu toàn bộ cấu hình
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="/assets/libs/flatpickr/flatpickr.min.css">
@endsection

@section('script')
    <script src="/assets/libs/flatpickr/flatpickr.js"></script>
    <script src="/assets/libs/flatpickr/dist/vn.js"></script>
    <script src="/assets/libs/tinymce/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            license_key: 'gpl',
            selector: 'textarea.tinymce',
            height: 500,
            menubar: true,
            toolbar_mode: 'wrap',
            toolbar_sticky: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount',
                'nonbreaking', 'pagebreak', 'autosave', 'quickbars', 'emoticons',
            ],

            toolbar: [
                'undo redo | styles blocks | fontfamily fontsize forecolor backcolor | bold italic underline strikethrough | removeformat',
                'align | bullist numlist outdent indent | link image media table charmap emoticons anchor | nonbreaking pagebreak',
                'preview fullscreen | searchreplace | insertdatetime | code help wordcount | visualblocks',
            ],
            font_size_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
            font_family_formats: "Arial=arial,helvetica,sans-serif; Times New Roman='Times New Roman',serif; Tahoma=tahoma,sans-serif; Verdana=verdana,sans-serif;",
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
        });
    </script>
    <script>
        // Gọi lại initFlatpickr có sẵn để chỉ chọn ngày (date-only)
        initFlatpickr("#promotion_start", {
            mode: "single",
            enableTime: false,
            dateFormat: "Y-m-d",
            locale: "vn",
        });

        initFlatpickr("#promotion_end", {
            mode: "single",
            enableTime: false,
            dateFormat: "Y-m-d",
            locale: "vn",
        });
    </script>
    <script>
        $('#add-row').on('click', function () {
            const newRow = `<div class="row align-items-center mb-2 promotion-row"><div class="col-md-5"><input type="number" name="promotion_money[]" class="form-control" placeholder="Mức nạp (VD: 10000)"></div><div class="col-md-5"><input type="number" name="promotion_value[]" class="form-control" placeholder="Khuyến mãi (%)"></div><div class="col-md-2 text-center"><button type="button" class="btn btn-danger btn-sm remove-row">X</button></div></div>`;
            $('#promotion-levels').append(newRow);
        });

        // Xóa mốc
        $(document).on('click', '.remove-row', function () {
            $(this).closest('.promotion-row').remove();
        });
    </script>
@endsection