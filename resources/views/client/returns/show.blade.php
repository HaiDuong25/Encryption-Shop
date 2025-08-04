@extends('client.layout.main')

@section('title', 'Chi tiết yêu cầu trả hàng')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Chi tiết yêu cầu trả hàng #{{ $return->id }}</h4>
            <a href="{{ route('client.returns.index') }}" class="btn btn-outline-secondary">
                ← Quay lại danh sách
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Thông tin yêu cầu</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Đơn hàng:</strong> #{{ $return->order_id }}
                                </div>
                                <div class="mb-3">
                                    <strong>Trạng thái:</strong>
                                    @switch($return->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">Đã duyệt</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Từ chối</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($return->status) }}</span>
                                    @endswitch
                                </div>
                                <div class="mb-3">
                                    <strong>Ngày tạo:</strong> {{ $return->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Lý do:</strong> {{ $return->reason }}
                                </div>
                                @if($return->description)
                                    <div class="mb-3">
                                        <strong>Mô tả:</strong> {{ $return->description }}
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <strong>Phương thức hoàn tiền:</strong> 
                                    {{ optional($return->paymentMethod)->payment_type ?? 'Chưa chọn' }}
                                </div>
                            </div>
                        </div>

                        @if($return->bank_account_name || $return->bank_account_number)
                            <hr>
                            <h6><i class="fas fa-credit-card me-2"></i>Thông tin hoàn tiền</h6>
                            @if($return->bank_account_name)
                                <div class="mb-2">
                                    <strong>Tên người nhận:</strong> {{ $return->bank_account_name }}
                                </div>
                            @endif
                            @if($return->bank_account_number)
                                <div class="mb-2">
                                    <strong>Số tài khoản/ví:</strong> {{ $return->bank_account_number }}
                                </div>
                            @endif
                        @endif

                        @if($return->image)
                            <hr>
                            <h6><i class="fas fa-image me-2"></i>Hình ảnh đính kèm</h6>
                            <img src="{{ asset('storage/' . $return->image) }}" class="img-thumbnail" style="max-width: 300px;">
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-box me-2"></i>Sản phẩm trả hàng</h5>
                        
                        @if($return->orderDetail && $return->orderDetail->product)
                            @php
                                $product = $return->orderDetail->variant->product ?? $return->orderDetail->product;
                                $image = $product->image ?? null;
                                $imageUrl = $image 
                                    ? (Str::startsWith($image, ['http://', 'https://']) 
                                        ? $image 
                                        : asset('storage/' . $image))
                                    : 'https://via.placeholder.com/150?text=No+Image';
                            @endphp
                            
                            <div class="text-center mb-3">
                                <img src="{{ $imageUrl }}" class="img-thumbnail" style="max-width: 150px;">
                            </div>
                            
                            <div class="mb-2">
                                <strong>Tên sản phẩm:</strong>
                                <div>{{ $product->name }}</div>
                            </div>
                            
                            @if($return->orderDetail->variant && $return->orderDetail->variant->attributeValues && $return->orderDetail->variant->attributeValues->count())
                                <div class="mb-2">
                                    <strong>Thuộc tính:</strong>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($return->orderDetail->variant->attributeValues as $attrValue)
                                            <span class="badge bg-light text-dark border">
                                                {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mb-2">
                                <strong>Số lượng:</strong> {{ $return->orderDetail->quantity }}
                            </div>
                            
                            <div class="mb-2">
                                <strong>Đơn giá:</strong> {{ format_vnd($return->orderDetail->price) }}₫
                            </div>
                            
                            <div class="alert alert-warning">
                                <strong>Thành tiền:</strong> {{ format_vnd($return->orderDetail->total_price) }}₫
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <p>Sản phẩm đã bị xóa</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
