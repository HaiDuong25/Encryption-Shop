@extends('admin.layouts.main')

@section('title', $product->name)

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3 gap-4">
                <div>
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" width="140" class="rounded border" style="object-fit:cover;">
                </div>
                <div>
                    <h3 class="mb-2">{{ $product->name }}</h3>
                    <div class="mb-1">Danh mục: <span class="badge bg-info ">{{ $product->category->name ?? '-' }}</span></div>
                    <div class="mb-1">Thương hiệu: <span class="badge btn-warning">{{ $product->brand->name ?? '-' }}</span></div>
                    <div class="mb-1 text-danger">Giá mặc định: <b>{{ number_format($product->price,0,',','.') }} đ</b></div>
                    <div class="mb-1 text-danger">Giá khuyến mãi: <b>{{ number_format($product->sale_price,0,',','.') }} đ</b></div>
                    <div class="mb-1">
                        Trạng thái:
                        <span class="badge bg-{{ $product->status=='active'?'primary':'danger' }}">
                            {{ $product->status=='active' ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="fw-semibold">Mô tả sản phẩm:</label>
                <span class="fw-bold">{!! nl2br(e($product->description)) !!}</span>
            </div>

            <hr>
            <h5 class="text-primary mt-3 mb-2">Danh sách biến thể sản phẩm</h5>
            <div class="table-responsive table-product">
                <table class="table theme-table table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th>SKU</th>
                            <th>Size</th>
                            <th>Màu</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Ảnh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                        <tr>
                            <td>{{ $variant->sku }}</td>
                            <td>
                                @foreach($variant->attributeValues as $av)
                                    @if($av->attribute->name=='Size') {{ $av->value }} @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($variant->attributeValues as $av)
                                    @if($av->attribute->name=='Màu') {{ $av->value }} @endif
                                @endforeach
                            </td>
                            <td class="text-danger fw-bold">
                                {{ number_format($variant->display_price ?? $variant->price,0,',','.') }} đ
                            </td>
                            <td>{{ $variant->stock }}</td>
                            <td>
                                @if($variant->image)
                                    <img src="{{ asset('storage/'.$variant->image) }}" width="60" class="rounded border" style="object-fit:cover;">
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if($product->variants->isEmpty())
                        <tr><td colspan="6" class="text-center text-warning">Chưa có biến thể cho sản phẩm này.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i data-feather="arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                    <i data-feather="edit"></i> Sửa sản phẩm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    if (window.feather) feather.replace();
</script>
@endpush
