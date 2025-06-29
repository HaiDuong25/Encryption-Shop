@extends('admin.layouts.main')

@section('title', 'Quản lý Liên hệ Khách hàng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="title-header option-title d-sm-flex d-block justify-content-between align-items-center">
                        <h5>Danh sách Liên hệ Khách hàng</h5>
                    </div>

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
                        <table class="table all-package theme-table table-product text-center align-middle" style="border-collapse: separate; border-spacing: 0 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Người gửi</th>
                                    <th>Email</th>
                                    <th>Điện thoại</th>
                                    <th style="min-width: 250px;">Nội dung (tóm tắt)</th>
                                    <th>Ngày gửi</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contacts as $contact)
                                <tr style="border-bottom: none !important;">
                                    <td>{{ $contact->id }}</td>
                                    <td>
                                        {{ $contact->name }}
                                        @if($contact->user_id && $contact->user)
                                        <br><small class="text-muted">(User: {{ $contact->user->name }} - ID: {{ $contact->user_id }})</small>
                                        @else
                                        <br><small class="text-muted">(Khách)</small>
                                        @endif
                                    </td>
                                    <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                    <td>{{ $contact->phone ?: 'N/A' }}</td>
                                    <td>{{ Str::limit($contact->content, 100) }}</td>
                                    <td>
                                        {{ $contact->created_at ? $contact->created_at->format('d/m/Y H:i') : 'Không rõ' }}
                                    </td>
                                    <td>
                                        <ul class="d-flex justify-content-center gap-2 list-unstyled mb-0">
                                            <li>
                                                <a href="{{ route('contacts.show', $contact->id) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link p-0 text-danger">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Chưa có liên hệ nào.</td>
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
    </div>
</div>
@endsection
