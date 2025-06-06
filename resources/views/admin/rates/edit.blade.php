@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Sửa Đánh giá</h2>
    <form action="{{ route('rates.update', $rate->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung</label>
            <textarea class="form-control" id="content" name="content" rows="3">{{ old('content', $rate->content) }}</textarea>
            @error('content')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select name="status" id="status" class="form-select">
                @foreach ($statuses as $value => $text)
                    <option value="{{ $value }}" {{ old('status', $rate->status) == $value ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('rates.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection