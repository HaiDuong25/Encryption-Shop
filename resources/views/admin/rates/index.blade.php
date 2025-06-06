@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="container">
    <h2>Danh sách Đánh giá</h2>
    <a href="{{ route('rates.create') }}" class="btn btn-success mb-2">+ Thêm đánh giá mới</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rates as $rate)
                <tr>
                    <td>{{ $rate->id }}</td>
                    <td>{{ Str::limit($rate->content, 50) }}</td>
                    <td>
                        <span class="{{ $rate->status_class ?? '' }}">
                            {{ $rate->status_text ?? '' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('rates.show', $rate->id) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('rates.edit', $rate->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('rates.destroy', $rate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa đánh giá này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Chưa có đánh giá nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection