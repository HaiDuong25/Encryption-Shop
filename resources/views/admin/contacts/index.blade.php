@extends('admin.layouts.main')

@section('title', 'Quản lý Liên hệ Khách hàng')

@section('content')
<div class="col-12">
    <h3 class="mt-3 mb-3">Danh sách Liên hệ từ Khách hàng</h3>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-envelope-open-text me-1"></i> Tất cả Liên hệ</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người gửi</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th style="min-width: 250px;">Nội dung (tóm tắt)</th>
                            <th>Ngày gửi</th>
                            <th style="min-width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                        <tr>
                            <td>{{ $contact->id }}</td>
                            <td>
                                {{ $contact->name }}
                                @if($contact->user_id && $contact->user) {{-- Thêm kiểm tra $contact->user tồn tại --}}
                                <br><small class="text-muted">(User: {{ $contact->user->name }} - ID: {{ $contact->user_id }})</small>
                                @else
                                <br><small class="text-muted">(Khách)</small>
                                @endif
                            </td>
                            <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                            <td>{{ $contact->phone ?: 'N/A' }}</td>
                            <td>{{ Str::limit($contact->content, 100) }}</td>
                            <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex flex-wrap gap-1">
                                    {{-- Nút Xem --}}
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-primary btn-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>

                                    {{-- Nút Xóa --}}
                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa liên hệ">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Chưa có liên hệ nào.</td> {{-- Giảm colspan vì bỏ cột status --}}
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($contacts->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $contacts->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
