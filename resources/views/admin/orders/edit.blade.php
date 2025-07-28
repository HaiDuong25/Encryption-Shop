@extends('admin.layouts.main')

@section('title', 'Chỉnh sửa đơn hàng')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Chỉnh sửa đơn hàng #{{ $order->id }}</h5>
        </div>
        <div class="card-body">
            <form id="orderEditForm" action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Alert container for AJAX responses --}}



                <!-- Thông tin người nhận -->


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
                                'returning' => 'Đang trả hàng',
                                'approved' => 'Đã trả hàng',
                                'rejected' => 'Từ chối trả',
                            ];

                            // Convert numeric status to string for backward compatibility
                            $currentStatus = $order->status;
                            $statusMap = [
                                '0' => 'pending',
                                '1' => 'confirmed',
                                '2' => 'shipping',
                                '3' => 'delivering',
                                '4' => 'received',
                                '5' => 'completed',
                                '6' => 'cancelled',
                                '7' => 'returning',
                                '8' => 'approved',
                                '9' => 'rejected',
                            ];
                            if (is_numeric($currentStatus)) {
                                $currentStatus = $statusMap[$currentStatus] ?? 'pending';
                            }

                        @endphp
                  @php
    $statusKeys = array_keys($statuses);
    $currentIndex = array_search($currentStatus, $statusKeys);
@endphp

@foreach ($statuses as $value => $label)
    @php
        $optionIndex = array_search($value, $statusKeys);
        $disabled = false;

        // Trạng thái không được chọn nếu đã đi qua (tức là optionIndex < currentIndex)
        if ($optionIndex < $currentIndex) {
            $disabled = true;
        }

        // Trạng thái cuối không được chọn từ đây
        $finalStatuses = ['returning', 'approved', 'rejected'];
        if (in_array($value, $finalStatuses)) {
            $disabled = true;
        }

        // Nếu hiện tại đang ở trạng thái cuối, thì không cho chọn bất cứ trạng thái nào nữa
        if (in_array($currentStatus, $finalStatuses)) {
            $disabled = true;
        }

        // Không cho hủy nếu đơn hàng đã giao
        $forbidCancel =
            in_array($currentStatus, ['shipping', 'delivering', 'received', 'completed']) &&
            $value === 'cancelled';
    @endphp

    <option value="{{ $value }}"
        {{ old('status', $currentStatus) == $value ? 'selected' : '' }}
        @if ($disabled || $forbidCancel) disabled @endif>
        {{ $label }}
    </option>
@endforeach


                    </select>

                </div>

                @if ($currentStatus === 'cancelled')
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">Lý do hủy</label>
                        <input type="text" class="form-control" id="cancel_reason" name="cancel_reason"
                            maxlength="255" value="{{ old('cancel_reason', $order->cancel_reason) }}">
                    </div>

                    <div class="mb-3">
                        <label for="cancel_note" class="form-label">Ghi chú hủy đơn</label>
                        <textarea class="form-control" id="cancel_note" name="cancel_note" rows="3">{{ old('cancel_note', $order->cancel_note) }}</textarea>
                    </div>
                @endif


                <button type="submit" class="btn btn-primary" id="submitBtn">Cập nhật</button>
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

        // AJAX form submission
        document.getElementById('orderEditForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alert-container');

            // Show loading state
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i data-feather="loader" class="rotating"></i> Đang cập nhật...';
            submitBtn.disabled = true;

            // Clear previous alerts
            alertContainer.innerHTML = '';

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alertContainer.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show">
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;

                        // Redirect after success
                        setTimeout(() => {
                            window.location.href = data.redirect || '{{ route('orders.index') }}';
                        }, 1500);
                    } else {
                        // Show error messages
                        if (data.errors) {
                            let errorHtml =
                                '<div class="alert alert-danger alert-dismissible fade show"><strong>Đã có lỗi xảy ra:</strong><ul class="mb-0 mt-2">';
                            Object.values(data.errors).flat().forEach(error => {
                                errorHtml += `<li>${error}</li>`;
                            });
                            errorHtml +=
                                '</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                            alertContainer.innerHTML = errorHtml;
                        } else {
                            alertContainer.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show">
                                ${data.message || 'Có lỗi xảy ra khi cập nhật đơn hàng!'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        }

                        // Restore button state
                        submitBtn.innerHTML = originalContent;
                        submitBtn.disabled = false;
                        feather.replace();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show">
                        Có lỗi xảy ra khi cập nhật đơn hàng!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                    // Restore button state
                    submitBtn.innerHTML = originalContent;
                    submitBtn.disabled = false;
                    feather.replace();
                });
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
    <style>
        .rotating {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
