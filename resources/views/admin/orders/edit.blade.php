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
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
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

                <!-- Thông tin người đặt -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Thông tin người đặt</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="orderer_name" class="form-label">Tên người đặt</label>
                            <input type="text" class="form-control" id="orderer_name" name="orderer_name" maxlength="255" required
                                value="{{ old('orderer_name', $order->orderer_name) }}">
                        </div>

                        <div class="mb-3">
                            <label for="orderer_phone" class="form-label">Số điện thoại người đặt</label>
                            <input type="text" class="form-control" id="orderer_phone" name="orderer_phone" 
                                pattern="[0-9]{10,11}" maxlength="11" required
                                value="{{ old('orderer_phone', $order->orderer_phone) }}">
                        </div>

                        <div class="mb-3">
                            <label for="orderer_address" class="form-label">Địa chỉ người đặt</label>
                            <input type="text" class="form-control" id="orderer_address" name="orderer_address" maxlength="255" required
                                value="{{ old('orderer_address', $order->orderer_address) }}">
                        </div>
                    </div>
                </div>

                <!-- Thông tin người nhận -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Thông tin người nhận</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="recipient_name" class="form-label">Tên người nhận</label>
                            <input type="text" class="form-control" id="recipient_name" name="recipient_name" maxlength="255" required
                                value="{{ old('recipient_name', $order->recipient_name) }}">
                        </div>

                        <div class="mb-3">
                            <label for="recipient_phone" class="form-label">Số điện thoại người nhận</label>
                            <input type="text" class="form-control" id="recipient_phone" name="recipient_phone" 
                                pattern="[0-9]{10,11}" maxlength="11" required
                                value="{{ old('recipient_phone', $order->recipient_phone) }}">
                        </div>

                        <div class="mb-3">
                            <label for="recipient_address" class="form-label">Địa chỉ người nhận</label>
                            <input type="text" class="form-control" id="recipient_address" name="recipient_address" maxlength="255" required
                                value="{{ old('recipient_address', $order->recipient_address) }}">
                        </div>
                    </div>
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
                                        value="{{ old('quantities.' . $loop->index, $detail->quantity) }}" required>
                                </div>
                                <div class="col-2 d-flex align-items-center">
                                    <!-- Không cho xóa sản phẩm ở đây -->
                                </div>
                                <input type="hidden" name="order_detail_ids[]" value="{{ $detail->id }}">
                                <input type="hidden" name="product_ids[]" value="{{ $detail->product_id }}">
                                <input type="hidden" name="variant_ids[]" value="{{ $detail->variant_id }}">
                                <input type="hidden" name="prices[]" value="{{ $detail->price }}">
                            </div>
                        @endforeach
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
                                    '5' => 'completed',
                                    '6' => 'cancelled'
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

                @if($currentStatus === 'cancelled')
                <div class="mb-3">
                    <label for="cancel_reason" class="form-label">Lý do hủy</label>
                    <input type="text" class="form-control" id="cancel_reason" name="cancel_reason" maxlength="255"
                        value="{{ old('cancel_reason', $order->cancel_reason) }}">
                </div>

                <div class="mb-3">
                    <label for="cancel_note" class="form-label">Ghi chú hủy đơn</label>
                    <textarea class="form-control" id="cancel_note" name="cancel_note" rows="3">{{ old('cancel_note', $order->cancel_note) }}</textarea>
                </div>
                @endif

                <div class="mb-3">
                    <label for="discount_id" class="form-label">Mã giảm giá</label>
                    <select class="form-select" id="discount_id" name="discount_id">
                        <option value="">-- Không áp dụng --</option>
                        @foreach ($coupons as $coupon)
                            @php
                                $isExpired = $coupon->end_date && $coupon->end_date->isPast();
                                $isNotStarted = $coupon->start_date && $coupon->start_date->isFuture();
                                $isValid = !$isExpired && !$isNotStarted && $coupon->is_active;
                                $isSelected = $order->discount_id == $coupon->id || $order->coupon_code == $coupon->code;
                                
                                $statusText = '';
                                if ($isExpired) {
                                    $statusText = ' (Hết hạn)';
                                } elseif ($isNotStarted) {
                                    $statusText = ' (Chưa bắt đầu)';
                                } elseif (!$coupon->is_active) {
                                    $statusText = ' (Không khả dụng)';
                                }
                                
                                $dateRange = '';
                                if ($coupon->start_date && $coupon->end_date) {
                                    $dateRange = ' (' . $coupon->start_date->format('d/m/Y') . ' - ' . $coupon->end_date->format('d/m/Y') . ')';
                                }
                            @endphp
                            <option value="{{ $coupon->id }}"
                                {{ $isSelected ? 'selected' : '' }}
                                {{ !$isValid && !$isSelected ? 'disabled' : '' }}>
                                {{ $coupon->code }} - Giảm {{ $coupon->discount }}% {{ $dateRange }} {{ $statusText }}
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
        // Debug form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('Form submit triggered');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            // Kiểm tra các field required
            var requiredFields = this.querySelectorAll('[required]');
            var missingFields = [];
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    missingFields.push(field.name || field.id);
                }
            });
            
            if (missingFields.length > 0) {
                console.log('Missing required fields:', missingFields);
                alert('Vui lòng điền đầy đủ các trường bắt buộc: ' + missingFields.join(', '));
                e.preventDefault();
                return false;
            }
            
            console.log('Form validation passed, submitting...');
        });

        document.getElementById('user_id').addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            // Cập nhật thông tin người đặt
            document.getElementById('orderer_name').value = selected.getAttribute('data-name') || '';
            document.getElementById('orderer_phone').value = selected.getAttribute('data-phone') || '';
            document.getElementById('orderer_address').value = selected.getAttribute('data-address') || '';
            
            // Cập nhật thông tin người nhận (mặc định giống người đặt)
            document.getElementById('recipient_name').value = selected.getAttribute('data-name') || '';
            document.getElementById('recipient_phone').value = selected.getAttribute('data-phone') || '';
            document.getElementById('recipient_address').value = selected.getAttribute('data-address') || '';
        });

        document.getElementById('status').addEventListener('change', function() {
            var cancelFields = document.querySelectorAll('[id^="cancel_"]');
            var showCancelFields = this.value === 'cancelled';
            
            cancelFields.forEach(function(field) {
                field.closest('.mb-3').style.display = showCancelFields ? 'block' : 'none';
                field.required = showCancelFields;
            });
        });

        // Initialize cancel fields visibility
        document.getElementById('status').dispatchEvent(new Event('change'));
    </script>
@endsection
