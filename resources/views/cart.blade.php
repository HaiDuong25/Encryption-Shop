<form action="{{ route('apply.coupon') }}" method="POST">
    @csrf
    <label>Nhập mã giảm giá:</label>
    <input type="text" name="code" required>
    <button type="submit">Áp dụng</button>
</form>

@if(session('coupon'))
    <div>Đã áp dụng mã: {{ session('coupon.code') }} ({{ session('coupon.discount') }}%)</div>
@endif
@if(session('error'))
    <div style="color:red;">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div style="color:green;">{{ session('success') }}</div>
@endif