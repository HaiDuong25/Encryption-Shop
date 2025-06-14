@extends('admin.layouts.main')

@section('title', 'Quản lý kho hàng')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Quản lý kho hàng</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng còn lại</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/images/product/1.png') }}" alt="image" width="60">
                    </td>
                    <td>{{ $product->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection