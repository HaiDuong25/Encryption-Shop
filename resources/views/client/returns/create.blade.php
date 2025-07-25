@extends('client.layout.main')

@section('title', 'Yêu cầu trả hàng')

@section('content')
<div class="container py-4">
    <h4>Yêu cầu trả hàng</h4>

    <form action="{{ route('client.returns.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order_detail_id" value="{{ $orderDetail->id }}">

        <div class="mb-3">
            <label>Lý do trả hàng</label>
            <input type="text" name="reason" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Ảnh minh hoạ</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Gửi yêu cầu</button>
    </form>
</div>
@endsection
