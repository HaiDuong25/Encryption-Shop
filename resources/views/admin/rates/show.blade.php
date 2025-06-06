@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chi tiết Đánh giá #{{ $rate->id }}</h2>

    <div class="mb-3">
        <strong>Nội dung:</strong>
        <div class="border p-2">{!! nl2br(e($rate->content)) !!}</div>
    </div>

    <div class="mb-3">
        <strong>Trạng thái:</strong>
        <span class="{{ $rate->status_class ?? '' }}">
            {{ $rate->status_text ?? '' }}
        </span>
    </div>

    @if($rate->replies && $rate->replies->isNotEmpty())
        <div class="mb-3">
            <strong>Phản hồi:</strong>
            <ul>
                @foreach($rate->replies as $reply)
                    <li>
                        @if($reply->admin)
                            <span class="badge bg-primary">Admin</span>
                        @else
                            <span class="badge bg-secondary">Khách</span>
                        @endif
                        {!! nl2br(e($reply->content)) !!}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('rates.index') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection