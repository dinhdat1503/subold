@extends('admin.layout.app')
@section('title', 'Chỉnh sửa Supplier')
@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient bg-warning text-white fw-bold">
                    <i class="fa fa-edit me-1"></i> Chỉnh sửa Supplier: {{ $supplier->name }}
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.supplier.update', $supplier->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Tên Supplier --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name" id="name"
                                value="{{ old('name', $supplier->name) }}" required>
                            <label for="name">Tên Supplier</label>
                        </div>

                        {{-- Base URL --}}
                        <div class="form-floating mb-3">
                            <input type="url" class="form-control" name="base_url" id="base_url"
                                value="{{ old('base_url', $supplier->base_url) }}" required>
                            <label for="base_url">Base URL</label>
                        </div>

                        {{-- API Key --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="api_key" id="api_key"
                                value="{{ old('api_key', $supplier->api_key) }}" required>
                            <label for="api_key">API Key</label>
                        </div>

                        {{-- Proxy --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="proxy" id="proxy"
                                value="{{ old('proxy', $supplier->proxy) }}" placeholder="user:pass:host:port">
                            <label for="proxy">Proxy (tuỳ chọn)</label>
                        </div>

                        {{-- Username 🆕 --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="username" id="username"
                                value="{{ old('username', $supplier->username) }}" placeholder="Tên tài khoản (nếu có)">
                            <label for="username">Tên đăng nhập API</label>
                        </div>

                        {{-- Loại API --}}
                        <div class="form-floating mb-3">
                            <select class="form-select" id="api" name="api" required>
                                <option value="SMM" {{ old('api', 'SMM') === 'SMM' ? 'selected' : '' }}> SMM
                                </option>
                                <option value="2MXH" {{ old('api') === '2MXH' ? 'selected' : '' }}>2MXH</option>
                                <option value="TRUMVIP" {{ old('api') === 'TRUMVIP' ? 'selected' : '' }}>TRUMVIP
                                </option>
                            </select>
                            <label for="api">Loại API</label>
                        </div>

                        {{-- % Giá --}}
                        <div class="form-floating mb-3">
                            <input type="number" step="0.01" min="0" max="1000" class="form-control" name="price_percent"
                                id="price_percent" value="{{ old('price_percent', $supplier->price_percent) }}" required>
                            <label for="price_percent">% Giá (markup)</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" min="1" class="form-control" name="price_unit_value" id="price_unit_value"
                                value="{{ old('price_unit_value', $supplier->price_unit_value) }}" required>
                            <label for="price_unit_value">Giá Theo Số Lượng</label>
                        </div>

                        {{-- 🆕 Đơn vị tiền tệ --}}
                        <div class="form-floating mb-3">
                            <select name="currency" id="currency" class="form-select" required>
                                <option value="VND" {{ old('currency', $supplier->currency) == 'VND' ? 'selected' : '' }}>VND
                                </option>
                                <option value="USD" {{ old('currency', $supplier->currency) == 'USD' ? 'selected' : '' }}>USD
                                </option>
                                <option value="Xu" {{ old('currency', $supplier->currency) == 'Xu' ? 'selected' : '' }}>Xu
                                </option>
                                <option value="INR" {{ old('currency', $supplier->currency) == 'INR' ? 'selected' : '' }}>INR
                                </option>
                                <option value="THB" {{ old('currency', $supplier->currency) == 'THB' ? 'selected' : '' }}>THB
                                </option>
                                <option value="CNY" {{ old('currency', $supplier->currency) == 'CNY' ? 'selected' : '' }}>CNY
                                </option>
                            </select>
                            <label for="currency">Đơn vị tiền tệ</label>
                        </div>

                        {{-- 🆕 Tỷ giá quy đổi --}}
                        <div class="form-floating mb-3">
                            <input type="number" step="0.0001" min="0" class="form-control" name="exchange_rate"
                                id="exchange_rate" value="{{ old('exchange_rate', $supplier->exchange_rate) }}" required>
                            <label for="exchange_rate">Tỷ giá quy đổi sang VND</label>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $supplier->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Bật (hoạt động)</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.supplier.manage') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-1"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fa fa-save me-1"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection