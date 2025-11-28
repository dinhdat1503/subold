@extends('admin.layout.App')
@section('title', 'Chỉnh sửa dịch vụ máy chủ')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-tilte">Chỉnh sửa máy chủ</h4>
                    <form action="{{ route('admin.service.server.update', $server->id) }}" method="POST">
                        @csrf
                        @method("PUT")
                        {{-- Tabs --}}
                        <ul class="nav nav-tabs nav-fill mb-4 rounded-pill bg-light p-1 shadow-sm" id="serverTabs"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active rounded-pill fw-semibold text-primary" data-bs-toggle="tab"
                                    href="#info" role="tab">
                                    <i class="fa fa-info-circle me-1"></i> Thông tin chung
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link rounded-pill fw-semibold text-primary" data-bs-toggle="tab"
                                    href="#pricing" role="tab">
                                    <i class="fa fa-money-bill-wave me-1"></i> Giá & Giới hạn
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link rounded-pill fw-semibold text-primary" data-bs-toggle="tab"
                                    href="#actions" role="tab">
                                    <i class="fa fa-bolt me-1"></i> Hành động
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="serverTabContent">
                            <div class="tab-pane fade show active" id="info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-primary">
                                            <i class="fa fa-font me-1"></i> Tiêu đề
                                        </label>
                                        <input type="text" name="title" class="form-control border border-primary"
                                            value="{{ old('title', $server->title) }}"
                                            placeholder="Nhập tiêu đề máy chủ...">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">
                                            <i class="fa fa-link me-1"></i> Tiêu đề API
                                        </label>
                                        <input type="text" class="form-control border border-secondary bg-light"
                                            value="{{ $server->serverSupplier->title }}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label fw-semibold text-primary">
                                            Mã máy chủ
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fa fa-hashtag me-1 text-primary"></i>
                                            </span>
                                            <select name="server" class="form-control border selectpicker"
                                                data-live-search="true" title="Chọn máy chủ...">
                                                <option value="">-- Không --</option>
                                                @for ($i = 1; $i <= 100; $i++)
                                                    <option value="{{ $i }}" {{ (old('server', $server->server) == $i) ? 'selected' : '' }}>MC {{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label fw-semibold text-primary">
                                            Quốc gia
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fa fa-flag me-1 text-primary"></i>
                                            </span>
                                            <select name="flag" class="form-control border selectpicker"
                                                data-live-search="true" title="Chọn quốc gia...">
                                                <option value="">-- Không --</option>
                                                @foreach($flags as $flag)
                                                    <option
                                                        data-content='<span class="fi fi-{{ $flag['value'] }} me-2"></span> {{ $flag['name'] }}'
                                                        value="{{ $flag['value'] }}" {{ old('flag', $server->flag) == $flag['value'] ? 'selected' : '' }}>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label fw-semibold text-primary">
                                            Dịch vụ
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fa fa-diagram-next me-1 text-primary"></i>
                                            </span>
                                            <select name="service_id" class="form-control border selectpicker"
                                                data-live-search="true" title="Chọn máy chủ...">
                                                <option value="">-- Không --</option>
                                                @foreach($socials as $social)
                                                    <optgroup label="{{ strtoupper($social->name) }}">
                                                        @foreach($social->services as $service)
                                                            <option value="{{ $service->id }}" {{ old('service_id', $server->service_id) == $service->id ? 'selected' : '' }}>
                                                                {{ $service->name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">
                                            <i class="fa fa-plug me-1 text-secondary"></i> Dịch vụ API
                                        </label>
                                        <input type="text" class="form-control border border-primary bg-light"
                                            value="{{ $server->serverSupplier->service }}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-primary">
                                            <i class="fa fa-paragraph me-1 text-primary"></i> Mô tả
                                        </label>
                                        <textarea name="description" rows="4" class="form-control border border-primary"
                                            placeholder="Nhập mô tả chi tiết cho máy chủ...">{{ old('description', $server->description) }}</textarea>

                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">
                                            <i class="fa fa-file-alt me-1"></i> Mô tả API
                                        </label>
                                        <textarea rows="4" class="form-control border border-secondary bg-light"
                                            disabled>{{ $server->serverSupplier->description}}</textarea>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-primary">
                                            Trạng thái
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fa-solid fa-display text-primary"></i> </span>
                                            <select name="status" class="form-control border selectpicker"
                                                data-live-search="true" title="Chọn trạng thái...">
                                                <option value="0" {{ old('status', $server->status) == 0 ? 'selected' : '' }}>
                                                    Tắt</option>
                                                <option value="1" {{ old('status', $server->status) == 1 ? 'selected' : '' }}>
                                                    Bật</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">
                                            Tự Động Tắt Khi
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fa-solid fa-signal text-secondary"></i> </span>
                                            <select name="status_off" class="form-control border selectpicker"
                                                data-live-search="true" title="Chọn tự động tắt...">
                                                <option value="no" @if ($server->serverSupplier->status_off == "no") selected
                                                @endif>Không </option>
                                                <option value="title" @if ($server->serverSupplier->status_off == "title")
                                                selected @endif>
                                                    Tiêu Đề Thay Đổi (API)</option>
                                                <option value="desc" @if ($server->serverSupplier->status_off == "desc")
                                                selected @endif>
                                                    Mô
                                                    Tả Thay Đổi (API)</option>
                                                <option value="all" @if ($server->serverSupplier->status_off == "all")
                                                selected @endif>
                                                    Tiêu
                                                    Đề + Mô Tả Thay Đổi (API)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pricing" role="tabpanel">
                                <div class="row">
                                    {{-- Giá bán --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-primary">
                                            <i class="fa fa-cash-register me-1"></i> Giá dịch vụ
                                        </label>
                                        <input type="number" name="price" step="0.01" value="{{ $server->price }}"
                                            class="form-control border border-primary" placeholder="Nhập giá bán...">
                                    </div>

                                    {{-- Giá gốc (hoặc chi phí thực) --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">
                                            <i class="fa fa-money-bill-wave me-1 text-secondary"></i> Giá gốc
                                        </label>
                                        <input type="number" class="form-control border border-secondary bg-light"
                                            value="{{ $server->serverSupplier->cost }}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- Min --}}
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-primary">
                                            <i class="fa fa-chevron-down me-1"></i> Số lượng tối thiểu
                                        </label>
                                        <input type="number" name="min" value="{{ $server->min }}"
                                            class="form-control border border-primary" placeholder="Nhập min...">
                                    </div>

                                    {{-- Max --}}
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold text-primary">
                                            <i class="fa fa-chevron-up me-1"></i> Số lượng tối đa
                                        </label>
                                        <input type="number" name="max" value="{{ $server->max }}"
                                            class="form-control border border-primary" placeholder="Nhập max...">
                                    </div>
                                    {{-- Cập nhật tự động giá --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">
                                            Cập Nhật Min Max
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fa-solid fa-arrow-rotate-right me-1 text-secondary"></i>
                                            </span>
                                            <select name="update_minmax" class="form-control border selectpicker"
                                                data-live-search="true" title="Cập Nhật Min Max...">
                                                <option value="0" @if (!$server->serverSupplier->update_minmax) selected
                                                @endif>
                                                    Tắt
                                                </option>
                                                <option value="1" @if ($server->serverSupplier->update_minmax) selected
                                                @endif>
                                                    Bật
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- TAB 3: Hành động --}}
                            <div class="tab-pane fade" id="actions" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <h5 class="alert-heading">
                                                <i class="bi bi-exclamation-triangle-fill"></i> CHÚ Ý QUAN TRỌNG: Quy tắc
                                                Cấu hình Server
                                            </h5>
                                            <hr>
                                            <p>Nếu chọn cấu hình **Server (Tùy chỉnh/Khác)**</p>

                                            <p class="mb-1 fw-bold text-danger">
                                                <span class="text-decoration-underline">CHỈ ĐƯỢC PHÉP</span> kích hoạt
                                                và cấu hình <span class="badge bg-danger">MỘT (1)</span> trong ba tùy chọn
                                                sau:
                                            </p>

                                            <ul class="list-unstyled ms-3 mt-2">
                                                <li><i class="bi bi-check-circle-fill text-success"></i>
                                                    <strong>Reaction</strong>
                                                </li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i>
                                                    <strong>Time</strong>
                                                </li>
                                                <li><i class="bi bi-check-circle-fill text-success"></i>
                                                    <strong>Amount</strong>
                                                </li>
                                            </ul>

                                            <hr class="my-3">

                                            <p class="fw-bold text-dark">
                                                **KHÔNG ĐƯỢC PHÉP** cấu hình nhiều loại khác Server cùng một lúc! ( Nhiều dữ
                                                liệu cùng lúc thì được )
                                            </p>

                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                    {{-- REACTION --}}
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm">
                                            <div
                                                class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                                                <span><i class="fa fa-face-smile me-1 text-warning"></i> Reaction</span>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="action_reaction[status]" value="true"
                                                        @checked($server->action_reaction['status'] ?? false)>
                                                    <label class="form-check-label small">Trạng Thái</label>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <label class="form-label fw-semibold">Chọn cảm xúc</label>
                                                <select name="action_reaction_form" multiple
                                                    class="form-select choices-input">
                                                    @foreach ($reaction as $key => $label)
                                                        <option value="{{ $key }}" @selected(in_array($key, array_keys(json_decode($server->action_reaction['server'] ?? '{}', true))))>{{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="action_reaction[server]"
                                                    class="form-control mt-2 choice-insert"
                                                    value="{{  $server->action_reaction['server'] ?? "{}" }}" hidden>
                                                <button type="button"
                                                    class="btn btn-outline-primary w-100 mt-2 open-config-modal"
                                                    data-type="reaction" data-bs-toggle="modal"
                                                    data-bs-target="#configServerModal">
                                                    <i class="fa fa-gears me-1"></i> Cấu hình server theo cảm
                                                    xúc
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- COMMENT --}}
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm">
                                            <div
                                                class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                                                <span><i class="fa fa-comment-dots me-1 text-primary"></i> Comment</span>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="action_comment[status]" value="true"
                                                        @checked($server->action_comment['status'] ?? false)>
                                                    <label class="form-check-label small">Trạng Thái</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- TIME --}}
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm">
                                            <div
                                                class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                                                <span><i class="fa fa-clock me-1 text-success"></i> Thời gian
                                                    (Time)</span>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="action_time[status]" value="true"
                                                        @checked($server->action_time['status'] ?? false)>
                                                    <label class="form-check-label small">Trạng Thái</label>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <label class="form-label fw-semibold">Kiểu</label>
                                                <input type="text" name="action_time[type]" class="form-control mb-2"
                                                    value="{{ $server->action_time['type'] ?? 'second' }}"
                                                    placeholder="Kiểu (second)">
                                                <label class="form-label fw-semibold">Dữ liệu (data)</label>
                                                <input multiple type="text" name="action_time_form"
                                                    value="{{ implode(',', array_keys(json_decode($server->action_time['server'] ?? '{}', true))) }}"
                                                    class="form-control mb-2 choices-input" placeholder="Nhập dữ liệu...">
                                                <input type="text" name="action_time[server]"
                                                    class="form-control mt-2 choice-insert"
                                                    value="{{  $server->action_time['server'] ?? "{}" }}" hidden>
                                                <button type="button"
                                                    class="btn btn-outline-success w-100 mt-2 open-config-modal"
                                                    data-type="time" data-bs-toggle="modal"
                                                    data-bs-target="#configServerModal">
                                                    <i class="fa fa-clock me-1"></i> Cấu hình server theo thời gian
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- AMOUNT --}}
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm">
                                            <div
                                                class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                                                <span><i class="fa fa-chart-line me-1 text-danger"></i> Số lượng
                                                    (Amount)</span>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="action_amount[status]" value="true"
                                                        @checked($server->action_amount['status'] ?? false)>
                                                    <label class="form-check-label small">Trạng Thái</label>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Kiểu</label>
                                                        <input type="text" name="action_amount[type]" class="form-control"
                                                            value="{{ $server->action_amount['type'] ?? 'second' }}"
                                                            placeholder="Kiểu (second)">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Đơn vị</label>
                                                        <input type="text" name="action_amount[unit]" class="form-control"
                                                            value="{{ $server->action_amount['unit'] ?? 'bài' }}"
                                                            placeholder="Đơn vị (bài)">
                                                    </div>
                                                </div>
                                                <label class="form-label mt-2 fw-semibold">Dữ liệu (data)</label>
                                                <input type="text" name="action_amount_form"
                                                    value="{{ implode(',', array_keys(json_decode($server->action_amount['server'] ?? '{}', true))) }}"
                                                    class="form-control mb-2 choices-input" placeholder="Nhập dữ liệu...">
                                                <input type="text" name="action_amount[server]"
                                                    class="form-control mt-2 choice-insert"
                                                    value="{{ $server->action_amount['server'] ?? "{}" }}" hidden>
                                                <button type="button"
                                                    class="btn btn-outline-danger w-100 mt-2 open-config-modal"
                                                    data-type="amount" data-bs-toggle="modal"
                                                    data-bs-target="#configServerModal">
                                                    <i class="fa fa-chart-line me-1"></i> Cấu hình server theo số lượng
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ORDER CONFIG --}}
                                    <div class="col-md-12">
                                        <div class="card border-0 shadow-sm">
                                            <div
                                                class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
                                                <span><i class="fa fa-gear me-1 text-info"></i> Cấu hình Đơn
                                                    hàng</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">

                                                    <div class="col-md-3">
                                                        <label for="switchMultiLink"
                                                            class="form-check-label fw-semibold mb-1">
                                                            <i class="fa-solid fa-list-ol me-1 text-primary"></i>
                                                            Đặt Nhiều Link
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch"
                                                                id="switchMultiLink" name="action_order[multi_link]"
                                                                value="true" @checked($server->action_order['multi_link'] ?? false)>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="switchRefund" class="form-check-label fw-semibold mb-1">
                                                            <i
                                                                class="fa-solid fa-money-bill-transfer me-1 text-success"></i>
                                                            Hoàn Tiền
                                                        </label>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch"
                                                                id="switchRefund" name="action_order[refund]" value="true"
                                                                @checked($server->action_order['refund'] ?? false)>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="switchWarranty"
                                                            class="form-check-label fw-semibold mb-1">
                                                            <i class="fa-solid fa-shield-halved me-1 text-info"></i>
                                                            Bảo Hành
                                                        </label>
                                                        <div class="form-check form-switch"> <input class="form-check-input"
                                                                type="checkbox" role="switch" id="switchWarranty"
                                                                name="action_order[warranty]" value="true"
                                                                @checked($server->action_order['warranty'] ?? false)>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary col-12">Chỉnh sửa </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="configServerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-light">Cấu hình server</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap gap-1 mb-3" id="btnValueConfigModal">
                    </div>
                    <div class="card shadow-sm" id="formConfigModal" style="display: none;">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-circle-notch text-primary me-2"></i> Cấu hình:
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="vstack gap-3">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Tên Nhà Cung Cấp</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-server text-primary"></i></span>
                                            <select class="form-control selectpicker fetch-supplier" data-live-search="true"
                                                title="Chọn nhà cung cấp" data-container="body" data-type="SupplierName">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Gói Dịch Vụ Gốc</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i
                                                    class="fa fa-tachometer-alt text-success"></i></span>
                                            <select class="form-control selectpicker fetch-supplier" data-live-search="true"
                                                title="Chọn gói dịch vụ" data-type="SupplierService">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Server</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-flag text-info"></i></span>
                                            <select name="" class="form-control selectpicker fetch-supplier"
                                                data-live-search="true" title="Chọn dịch vụ" data-container="body"
                                                data-type="SupplierServer">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="" target="_blank" id="toServer">
                                            <button class="btn btn-secondary">
                                                <i class="fa-regular fa-share-from-square"></i>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                <div class="row" id="detailsSupplier">
                                    <div class="col-12">
                                        <hr class="mt-0">
                                        <h6 class="mb-3 text-secondary"><i class="fas fa-info-circle me-1"></i> Chi tiết Gói
                                            Dịch Vụ</h6>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Giá</label>
                                        <div class="input-group">
                                            <span class="input-group-text text-secondary"><i
                                                    class="fas fa-dollar-sign"></i></span>
                                            <input type="text" class="form-control price-detail" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Min/Max</label>
                                        <div class="input-group">
                                            <span class="input-group-text text-secondary"><i
                                                    class="fas fa-sort-numeric-up-alt"></i></span>
                                            <input type="text" class="form-control minmax-detail" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Trạng thái Hoạt động</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input status-detail" type="checkbox" disabled>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Mô tả Dịch vụ</label>
                                        <textarea class="form-control bg-light-danger des-detail" disabled></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="/assets/libs/choices/choices.min.css">
    <link rel="stylesheet" href="/assets/libs/bootstrap-select-1.13.14/bootstrap-select.min.css">
    <link rel="stylesheet" href="/assets/css/client/flag-css/css/flag-icons.min.css">
    <style>
        .nav-tabs {
            border: none !important;
        }

        .nav-tabs .nav-link {
            color: #0d6efd;
            border: none;
            background: transparent;
            transition: all 0.25s ease;
        }

        .nav-tabs .nav-link:hover {
            background-color: #e9f3ff;
            color: #0a58ca;
        }

        .nav-tabs .nav-link.active {
            background: #0d6efd;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
        }

        .bootstrap-select .dropdown-menu .dropdown-header {
            font-weight: bold !important;
        }
    </style>
@endsection
@section("script")
    <script src="/assets/libs/choices/choices.min.js"></script>
    <script src="/assets/libs/bootstrap-select-1.13.14/bootstrap-select.min.js"></script>
    <script>
        const BTN_CONFIG = (data) => `<button class="btn btn-outline-secondary flex-fill btn-config-select" data-value="${data}">${data}</button>`;
        let selectedValues;
        let selectedType;
        let selectedAction;
        let selectedActionKey;
        const supplierName = @json($suppliers);
        const $supplierSelect = $('select[data-type="SupplierName"]');
        const $serviceSelect = $('select[data-type="SupplierService"]');
        const $serverSelect = $('select[data-type="SupplierServer"]');
        const resetDetails = () => {
            $("#detailsSupplier .price-detail").val('');
            $("#detailsSupplier .minmax-detail").val('');
            $("#detailsSupplier .des-detail").html('');
            $("#detailsSupplier .status-detail").prop('checked', false);
            $("#toServer").attr("href", "");
            $serverSelect.selectpicker('destroy').empty().selectpicker();
        };
        const optionNone = `<option value="0">-- Không -- </option>`;
        const actionReaction = $('input[name="action_reaction[server]"]');
        const actionTime = $('input[name="action_time[server]"]');
        const actionAmount = $('input[name="action_amount[server]"]');
        const toServerLink = "{{ route('admin.service.server.edit', '__ID__') }}";
    </script>
    <script>
        $(function () {
            $('.choices-input').each(function () {
                const $el = $(this);
                const value = $.trim($el.val());
                const items = value ? value.split(',').map(i => $.trim(i)) : [];
                $el.val('');
                const choice = new Choices(this, {
                    removeItemButton: true,
                    duplicateItemsAllowed: false,
                    delimiter: ',',
                    editItems: true,
                    addItems: true,
                    placeholderValue: 'Nhập dữ liệu...',
                    items: items
                });
                this.choicesjs = choice;
                $el.on('change', function () {
                    const values = choice.getValue(true);
                    $el.val(values);
                    const $outputElement = $el.closest('.card-body').find('.choice-insert');
                    const jsonObject = {};
                    values.forEach(value => {
                        if (value) {
                            jsonObject[value] = 0;
                        }
                    });
                    let oldObject = JSON.parse($outputElement.val());
                    Object.keys(oldObject).forEach(key => {
                        if (jsonObject.hasOwnProperty(key)) {
                            jsonObject[key] = oldObject[key];
                        }
                    });
                    $outputElement.attr('value', JSON.stringify(jsonObject));
                });
            });
            $('.selectpicker').selectpicker();
            $(".open-config-modal").on('click', function (e) {
                $("#btnValueConfigModal").html("");
                const type = $(this).data("type");
                if (type == "reaction") {
                    selectedValues = $('select[name="action_reaction_form"]').get(0).choicesjs.getValue(true);
                    selectedType = "reaction";
                } else if (type == "time") {
                    selectedValues = $('input[name="action_time_form"]').get(0).choicesjs.getValue(true);
                    selectedType = "time";
                } else if (type == "amount") {
                    selectedValues = $('input[name="action_amount_form"]').get(0).choicesjs.getValue(true);
                    selectedType = "amount";
                }
                $("#formConfigModal").hide();
                if (selectedValues && selectedValues.length > 0) {
                    selectedValues.forEach(element => {
                        $("#btnValueConfigModal").append(BTN_CONFIG(element));
                    });
                }
            });
            $("#btnValueConfigModal").on('click', ".btn-config-select", function (e) {
                swal({
                    title: "Loading...",
                    text: "Vui lòng chờ trong giây lát!",
                    showLoading: true
                })

                $(".btn-config-select").removeClass("active");
                $(this).addClass("active");
                selectedActionKey = $(this).data("value");

                let action;
                if (selectedType == "reaction") {
                    action = JSON.parse(actionReaction.val() || "{}");
                } else if (selectedType == "time") {
                    action = JSON.parse(actionTime.val() || "{}");
                }
                else if (selectedType == "amount") {
                    action = JSON.parse(actionAmount.val() || "{}");
                }
                selectedAction = action;

                $supplierSelect.selectpicker('destroy').empty();
                $supplierSelect.append(optionNone);
                $.each(supplierName, function (index, supplier) {
                    const option = `<option value="${supplier.id}">${supplier.name}</option>`;
                    $supplierSelect.append(option);
                });
                $supplierSelect.selectpicker();
                $serviceSelect.selectpicker('destroy').empty().selectpicker();
                resetDetails();

                ajaxRequest("{{route("admin.service.server.edit.fetch")}}", "GET", { type: "SupplierFull", value: action[selectedActionKey] ?? 0 }, function (res) {
                    if (res.status != "success") {
                        Swal.close();
                        return;
                    }
                    $supplierSelect.val(res.data.supplier_id).selectpicker('destroy').selectpicker();
                    fetchAllData("SupplierName", res.data.supplier_id, { service: res.data.server_supplier.service, server: res.data.id });
                });
                $("#formConfigModal").show();
            });
            $(".fetch-supplier").on("change", function (e) {
                let type = $(this).data('type');
                let val = $(this).val();
                if (val && val.length > 0) {
                    fetchAllData(type, val);
                }
            });
        });
    </script>
    <script>
        function fetchAllData(type, val, addMore = {}) {
            const setAction = (id) => {
                selectedAction[selectedActionKey] = id;
                if (selectedType == "reaction") {
                    actionReaction.attr("value", JSON.stringify(selectedAction));
                } else if (selectedType == "time") {
                    actionTime.attr("value", JSON.stringify(selectedAction));
                }
                else if (selectedType == "amount") {
                    actionAmount.attr("value", JSON.stringify(selectedAction));
                }
            }
            ajaxRequest("{{route("admin.service.server.edit.fetch")}}", "GET", { type: type, value: val }, function (res) {
                if (res.status != "success") {
                    return;
                }
                if (type == "SupplierName") {
                    resetDetails();
                    setAction(0);
                    $serviceSelect.selectpicker('destroy');
                    $serviceSelect.empty();
                    if (res.data) {
                        $serviceSelect.append(optionNone);
                        $.each(res.data, function (index, service) {
                            const option = `<option value="${service.service}">${service.service}</option>`;
                            $serviceSelect.append(option);
                        });
                    }
                    $serviceSelect.selectpicker();
                    if (addMore.service) {
                        $serviceSelect.val(addMore.service).selectpicker('destroy').selectpicker();
                        fetchAllData("SupplierService", addMore.service, { server: addMore.server });
                    }
                }
                else if (type == "SupplierService") {
                    resetDetails();
                    setAction(0);
                    $serverSelect.selectpicker('destroy');
                    if (res.data) {
                        $serverSelect.append(optionNone);
                        $.each(res.data, function (index, service) {
                            const option = `<option value="${service.server_id}">${service.server.title}</option>`;
                            $serverSelect.append(option);
                        });
                    }
                    $serverSelect.selectpicker();
                    if (addMore.server) {
                        $serverSelect.val(addMore.server).selectpicker('destroy').selectpicker();
                        $("#toServer").attr("href", toServerLink.replace('__ID__', addMore.server));
                        fetchAllData("SupplierServer", addMore.server);
                    }
                }
                else if (type == "SupplierServer") {
                    if (res.data) {
                        $("#toServer").attr("href", toServerLink.replace('__ID__', res.data.id));
                        setAction(res.data.id);
                        $("#detailsSupplier .price-detail").val(formatMoney(res.data.price, "đ", 2));
                        $("#detailsSupplier .minmax-detail").val(`${formatNumber(res.data.min)} ~ ${formatNumber(res.data.max)}`);
                        $("#detailsSupplier .status-detail").prop('checked', res.data.status);
                        $("#detailsSupplier .des-detail").html(res.data.description);
                    }
                    Swal.close();
                }
            });
        }
    </script>
@endsection