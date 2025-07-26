@extends('client.layout.main')

@section('title', 'Yêu cầu trả hàng')

@section('content')
    <div class="container py-4">
        <h4>Yêu cầu trả hàng</h4>

        <form action="{{ route('client.returns.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="order_detail_id" value="{{ $orderDetail->id }}">

            {{-- Phương thức thanh toán --}}
            <div class="mb-3">
                <label for="payment_method" class="form-label">Phương thức thanh toán:</label>
                <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                    <option value="">-- Chọn phương thức thanh toán --</option>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method->id }}"
                            {{ isset($selectedPaymentMethodId) && $selectedPaymentMethodId == $method->id ? 'selected' : '' }}>
                            {{ $method->payment_type }}
                        </option>
                    @endforeach
                </select>


            </div>

            {{-- Tên người nhận tiền hoàn --}}
            {{-- Tên người nhận tiền hoàn --}}
            <div class="mb-3 refund-info">
                <label for="bank_account_name" class="form-label">Tên người nhận</label>
                <input type="text" name="bank_account_name" id="bank_account_name" class="form-control"
                    placeholder="VD: Nguyễn Văn A">
            </div>

            {{-- Số tài khoản hoặc số ví --}}
            <div class="mb-3 refund-info">
                <label for="bank_account_number" class="form-label">Số tài khoản của ví điện từ</label>
                <input type="text" name="bank_account_number" id="bank_account_number" class="form-control"
                    placeholder="VD: 1234567890">
            </div>

            {{-- Lý do --}}
            <div class="mb-3">
                <label for="reason" class="form-label">Lý do trả hàng</label>
                <input type="text" name="reason" id="reason" class="form-control" required>
            </div>

            {{-- Mô tả --}}
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
            </div>

            {{-- Ảnh minh hoạ --}}
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh minh hoạ</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <button class="btn btn-primary">Gửi yêu cầu</button>
        </form>
    </div>

    {{-- Script ẩn/hiện trường nhập khi chọn COD --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const methodSelect = document.getElementById('payment_method_id');
                const refundFields = document.querySelectorAll('.refund-info');

                function toggleRefundFields() {
                    const selectedOption = methodSelect.options[methodSelect.selectedIndex];
                    const method = selectedOption.textContent.trim().toLowerCase();
                    refundFields.forEach(field => {
                        field.style.display = (method === 'cod') ? 'none' : 'block';
                    });
                }

                methodSelect.addEventListener('change', toggleRefundFields);
                toggleRefundFields(); // chạy lần đầu khi trang load
            });
        </script>
    @endpush
@endsection
