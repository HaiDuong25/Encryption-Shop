@extends('admin.layouts.main')

@section('title', 'Quản lý Danh mục')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách danh mục</h5>
                        <div class="right-options d-flex gap-2 align-items-center">
                            <a class="btn btn-solid btn-sm" href="{{ route('categories.create') }}">Thêm danh mục</a>
                        </div>
                    </div>

                    <form action="{{ route('categories.index') }}" method="GET" class="mb-3 d-flex flex-wrap gap-2 align-items-end">
                        <select name="parent_id" class="form-select" style="width:200px;">
                            <option value="">-- Danh mục cha --</option>
                            @foreach ($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="status" class="form-select" style="width:150px;">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                        </select>
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="ri-search-line"></i> Tìm
                        </button>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start">Danh mục cha</th>
                                    <th>Ngày tạo</th>
                                    <th>Ảnh</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grouped = $categories->groupBy('parent_id');
                                    $parents = $grouped[null] ?? collect();
                                @endphp

                                @forelse ($parents as $parent)
                                    <tr class="parent-row" data-id="{{ $parent->id }}">
                                        <td class="text-start">
                                            <a href="javascript:void(0);" class="toggle-children fw-bold text-dark text-decoration-none">
                                                <i class="ri-arrow-down-s-line me-1"></i> {{ $parent->name }}
                                            </a>
                                        </td>
                                        <td>{{ $parent->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                        <td>
                                            @if ($parent->image)
                                                <img src="{{ asset('storage/' . $parent->image) }}" width="60" alt="{{ $parent->name }}">
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="{{ $parent->status ? 'status-close' : 'status-danger' }}">
                                            <span>{{ $parent->status ? 'Hiển thị' : 'Ẩn' }}</span>
                                        </td>
                                        <td>
                                            <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                <li><a href="{{ route('categories.edit', $parent) }}"><i class="ri-pencil-line"></i></a></li>
                                                <li>
                                                    <form action="{{ route('categories.destroy', $parent) }}" method="POST" onsubmit="return confirm('Xác nhận xoá?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-link p-0 text-danger"><i class="ri-delete-bin-line"></i></button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>

                                    @foreach ($grouped[$parent->id] ?? [] as $child)
                                        <tr class="child-row d-none" data-parent-id="{{ $parent->id }}">
                                            <td class="text-start">└── {{ $child->name }}</td>
                                            <td>{{ $child->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                            <td>
                                                @if ($child->image)
                                                    <img src="{{ asset('storage/' . $child->image) }}" width="60" alt="{{ $child->name }}">
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="{{ $child->status ? 'status-close' : 'status-danger' }}">
                                                <span>{{ $child->status ? 'Hiển thị' : 'Ẩn' }}</span>
                                            </td>
                                            <td>
                                                <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                                    <li><a href="{{ route('categories.edit', $child) }}"><i class="ri-pencil-line"></i></a></li>
                                                    <li>
                                                        <form action="{{ route('categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Xác nhận xoá?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-link p-0 text-danger"><i class="ri-delete-bin-line"></i></button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr><td colspan="5" class="text-center">Không có danh mục.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-children').forEach(button => {
            button.addEventListener('click', function () {
                const parentRow = button.closest('tr');
                const parentId = parentRow.dataset.id;
                const icon = button.querySelector('i');

                document.querySelectorAll(`tr[data-parent-id='${parentId}']`).forEach(row => {
                    row.classList.toggle('d-none');
                });

                if (icon) {
                    icon.classList.toggle('ri-arrow-down-s-line');
                    icon.classList.toggle('ri-arrow-up-s-line');
                }
            });
        });

        const selectedParentId = '{{ request("parent_id") }}';
        if (selectedParentId) {
            const parentRow = document.querySelector(`tr[data-id='${selectedParentId}']`);
            if (parentRow) {
                document.querySelectorAll(`tr[data-parent-id='${selectedParentId}']`).forEach(row => {
                    row.classList.remove('d-none');
                });

                const icon = parentRow.querySelector('i');
                if (icon) {
                    icon.classList.remove('ri-arrow-down-s-line');
                    icon.classList.add('ri-arrow-up-s-line');
                }
            }
        }
    });
</script>
@endpush
