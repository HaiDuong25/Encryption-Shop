@extends('admin.layouts.main')

@section('title', 'Tạo đơn hàng')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tạo đơn hàng mới</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">Khách hàng</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    <option value="">-- Chọn khách hàng --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-phone="{{ $user->phone }}"
                            data-address="{{ $user->address }}">
                            {{ $user->name }} (ID: {{ $user->id }})
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Chọn sản phẩm và số lượng --}}
            <div class="mb-3">
                <label class="form-label">Sản phẩm</label>
                <div id="products-wrapper">
                    <div class="row mb-2 product-row">
                        <div class="col-7">
                            <select name="product_ids[]" class="form-select" required>
                                <option value="">-- Chọn sản phẩm --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} ({{ number_format($product->price) }}đ)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="number" name="quantities[]" class="form-control" min="1" value="1" required placeholder="Số lượng">
                        </div>
                        <div class="col-2 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-product" style="display:none;">X</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-info btn-sm mt-2" id="add-product">+ Thêm sản phẩm</button>
            </div>
            {{-- Thông tin người đặt hàng --}}
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Thông tin người đặt hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="orderer_name" class="form-label">Tên người đặt</label>
                            <input type="text" class="form-control" id="orderer_name" name="orderer_name" value="{{ old('orderer_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="orderer_phone" class="form-label">SĐT người đặt</label>
                            <input type="text" class="form-control" id="orderer_phone" name="orderer_phone" value="{{ old('orderer_phone') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="orderer_email" class="form-label">Email người đặt</label>
                            <input type="email" class="form-control" id="orderer_email" name="orderer_email" value="{{ old('orderer_email') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thông tin người nhận hàng --}}
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">Thông tin người nhận hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="recipient_name" class="form-label">Tên người nhận</label>
                            <input type="text" class="form-control" id="recipient_name" name="recipient_name" required value="{{ old('recipient_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="recipient_phone" class="form-label">SĐT người nhận</label>
                            <input type="text" class="form-control" id="recipient_phone" name="recipient_phone" required value="{{ old('recipient_phone') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="recipient_email" class="form-label">Email người nhận</label>
                            <input type="email" class="form-control" id="recipient_email" name="recipient_email" value="{{ old('recipient_email') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="recipient_address" class="form-label">Địa chỉ nhận hàng</label>
                            <textarea class="form-control" id="recipient_address" name="recipient_address" required>{{ old('recipient_address') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="order_notes" class="form-label">Ghi chú đơn hàng</label>
                        <textarea class="form-control" id="order_notes" name="order_notes">{{ old('order_notes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="total_price" class="form-label">Tổng tiền</label>
                <input type="number" class="form-control" id="total_price" name="total_price" required value="{{ old('total_price') }}">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Đã đặt</option>
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Xác nhận</option>
                    <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Giao cho ĐVVC</option>
                    <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Đang giao</option>
                    <option value="4" {{ old('status') == 4 ? 'selected' : '' }}>Đã nhận</option>
                    <option value="5" {{ old('status') == 5 ? 'selected' : '' }}>Hoàn thành</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="discount_id" class="form-label">Mã giảm giá</label>
                <select class="form-select" id="discount_id" name="discount_id">
                    <option value="">-- Không áp dụng --</option>
                    @foreach ($coupons as $coupon)
                        <option value="{{ $coupon->id }}" {{ old('discount_id') == $coupon->id ? 'selected' : '' }}>
                            {{ $coupon->code }} - {{ $coupon->discount }}%
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="payment_method_id" class="form-label">Phương thức thanh toán</label>
                <select class="form-select" id="payment_method_id" name="payment_method_id" required>
                    <option value="">-- Chọn phương thức --</option>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                            {{ $method->payment_type }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái đơn hàng</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Đang giao</option>
                    <option value="3" {{ old('status') == 3 ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="4" {{ old('status') == 4 ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Tổng tiền</label>
                <input type="text" id="total_price_display" class="form-control" readonly value="0">
                <input type="hidden" id="total_price" name="total_price" value="0">
            </div>
            {{-- Nút --}}
            <hr class="my-4">
<h5>Danh sách sản phẩm</h5>
@foreach ($products as $index => $product)
    <div class="row align-items-end mb-3 border-bottom pb-2">
        <div class="col-md-4">
            <label class="form-label">Sản phẩm</label>
            <input type="text" class="form-control" value="{{ $product->name }}" disabled>
            <input type="hidden" name="products[{{ $index }}][product_id]" value="{{ $product->id }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Số lượng</label>
            <input type="number" name="products[{{ $index }}][quantity]" class="form-control" value="1" min="1">
        </div>
        <div class="col-md-3">
            <label class="form-label">Giá</label>
            <input type="number" name="products[{{ $index }}][price]" class="form-control" value="{{ $product->price }}">
        </div>
        <div class="col-md-2 text-muted">
            <small><i>Mặc định: {{ number_format($product->price, 0, ',', '.') }} đ</i></small>
        </div>
    </div>
@endforeach

            <div class="row mt-4">
                <div class="col-4 d-flex justify-content-start">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-md px-4 fw-bold rounded-2 shadow-sm">
                        <i class="fa fa-arrow-left"></i> Quay lại
                    </a>
                </div>
                <div class="col-4 d-flex justify-content-center">
                    <button type="submit" class="btn btn-success btn-md px-4">Tạo đơn hàng</button>
                </div>
                <div class="col-4"></div>
            </div>
        </form>
    </div>
</div>
{{-- Script thêm/xóa sản phẩm --}}
<script>
    document.getElementById('add-product').onclick = function() {
        let wrapper = document.getElementById('products-wrapper');
        let firstRow = wrapper.querySelector('.product-row');
        let newRow = firstRow.cloneNode(true);
        newRow.querySelector('select').selectedIndex = 0;
        newRow.querySelector('input').value = 1;
        newRow.querySelector('.remove-product').style.display = 'inline-block';
        wrapper.appendChild(newRow);
    };
    document.getElementById('products-wrapper').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            e.target.closest('.product-row').remove();
        }
    });
</script>
<script>
    // Lấy giá sản phẩm từ PHP sang JS
    const productPrices = {
        @foreach ($products as $product)
            "{{ $product->id }}": {{ $product->price }},
        @endforeach
    };

    function formatCurrency(n) {
        return n.toLocaleString('vi-VN') + 'đ';
    }

    function calcTotal() {
        let total = 0;
        document.querySelectorAll('#products-wrapper .product-row').forEach(function(row) {
            let select = row.querySelector('select[name="product_ids[]"]');
            let qtyInput = row.querySelector('input[name="quantities[]"]');
            let qty = parseInt(qtyInput.value) || 1;
            let price = 0;
            if (select && select.value && productPrices[select.value]) {
                price = productPrices[select.value];
                total += price * qty;
            }
        });
        document.getElementById('total_price_display').value = formatCurrency(total);
        document.getElementById('total_price').value = total;
    }

    // Event delegation cho mọi select/input trong products-wrapper
    document.getElementById('products-wrapper').addEventListener('change', function(e) {
        if (e.target.matches('select[name="product_ids[]"], input[name="quantities[]"]')) {
            calcTotal();
        }
    });
    document.getElementById('products-wrapper').addEventListener('input', function(e) {
        if (e.target.matches('select[name="product_ids[]"], input[name="quantities[]"]')) {
            calcTotal();
        }
    });

    // Tính tổng tiền khi load trang
    window.onload = calcTotal;
</script>
@endsection
