@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa đơn hàng')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Chỉnh sửa đơn hàng #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="user_id" class="form-label">Khách hàng</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="">-- Chọn khách hàng --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" data-name="{{ $user->name }}"
                                data-phone="{{ $user->phone }}" data-address="{{ $user->address }}"
                                {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} (ID: {{ $user->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sản phẩm trong đơn hàng</label>
                    <div id="products-wrapper">
                        @foreach ($order->orderDetails as $detail)
                            <div class="row mb-2 product-row">
                                <div class="col-7">
                                    @php
                                        $productName = 'Sản phẩm đã xóa';
                                        if ($detail->variant && $detail->variant->product) {
                                            $productName = $detail->variant->product->name;
                                            if ($detail->variant->attribute_values) {
                                                $productName .= ' (' . $detail->variant->attribute_values . ')';
                                            }
                                        } elseif ($detail->product_id > 0 && $detail->product) {
                                            $productName = $detail->product->name;
                                        }
                                    @endphp
                                    <input type="text" class="form-control" value="{{ $productName }}" readonly>
                                </div>
                                <div class="col-3">
                                    <input type="number" name="quantities[]" class="form-control" min="1"
                                        value="{{ $detail->quantity }}" required>
                                </div>
                                <div class="col-2 d-flex align-items-center">
                                    <!-- Không cho xóa sản phẩm ở đây -->
                                </div>
                                <input type="hidden" name="product_ids[]" value="{{ $detail->product_id }}">
                                <input type="hidden" name="variant_ids[]" value="{{ $detail->variant_id }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Thông tin người đặt hàng -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">Thông tin người đặt hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="orderer_name" class="form-label">Tên người đặt</label>
                                <input type="text" class="form-control" id="orderer_name" name="orderer_name" 
                                    value="{{ old('orderer_name', $order->orderer_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="orderer_phone" class="form-label">SĐT người đặt</label>
                                <input type="text" class="form-control" id="orderer_phone" name="orderer_phone" 
                                    value="{{ old('orderer_phone', $order->orderer_phone) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="orderer_email" class="form-label">Email người đặt</label>
                                <input type="email" class="form-control" id="orderer_email" name="orderer_email" 
                                    value="{{ old('orderer_email', $order->orderer_email) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin người nhận hàng -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">Thông tin người nhận hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="recipient_name" class="form-label">Tên người nhận</label>
                                <input type="text" class="form-control" id="recipient_name" name="recipient_name" required
                                    value="{{ old('recipient_name', $order->recipient_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="recipient_phone" class="form-label">SĐT người nhận</label>
                                <input type="text" class="form-control" id="recipient_phone" name="recipient_phone" required
                                    value="{{ old('recipient_phone', $order->recipient_phone) }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="recipient_email" class="form-label">Email người nhận</label>
                                <input type="email" class="form-control" id="recipient_email" name="recipient_email" 
                                    value="{{ old('recipient_email', $order->recipient_email) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="recipient_address" class="form-label">Địa chỉ nhận hàng</label>
                                <textarea class="form-control" id="recipient_address" name="recipient_address" required>{{ old('recipient_address', $order->recipient_address) }}</textarea>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="order_notes" class="form-label">Ghi chú đơn hàng</label>
                            <textarea class="form-control" id="order_notes" name="order_notes">{{ old('order_notes', $order->order_notes) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status" required>
                        @php
                            $statuses = [
                                'pending' => 'Chờ xử lý',
                                'confirmed' => 'Đã xác nhận',
                                'shipping' => 'Đã giao cho ĐVVC',
                                'delivering' => 'Đang giao',
                                'received' => 'Đã nhận',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy',
                            ];
                            
                            // Convert numeric status to string for backward compatibility
                            $currentStatus = $order->status;
                            if (is_numeric($currentStatus)) {
                                $statusMap = [
                                    '0' => 'pending',
                                    '1' => 'confirmed',
                                    '2' => 'shipping',
                                    '3' => 'delivering',
                                    '4' => 'received',
                                    '5' => 'completed'
                                ];
                                $currentStatus = $statusMap[$currentStatus] ?? 'pending';
                            }
                        @endphp
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('status', $currentStatus) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="discount_id" class="form-label">Mã giảm giá</label>
                    <select class="form-select" id="discount_id" name="discount_id">
                        <option value="">-- Không áp dụng --</option>
                        @foreach ($coupons as $coupon)
                            <option value="{{ $coupon->id }}"
                                {{ old('discount_id', $order->discount_id) == $coupon->id ? 'selected' : '' }}>
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
                            <option value="{{ $method->id }}"
                                {{ old('payment_method_id', $order->payment_method_id) == $method->id ? 'selected' : '' }}>
                                {{ $method->payment_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('user_id').addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            document.getElementById('name').value = selected.getAttribute('data-name') || '';
            document.getElementById('phone').value = selected.getAttribute('data-phone') || '';
            document.getElementById('address').value = selected.getAttribute('data-address') || '';
        });
    </script>
@endsection
