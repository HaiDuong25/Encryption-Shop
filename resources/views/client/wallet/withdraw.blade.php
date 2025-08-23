@extends('client.layout.main')

@section('title', 'Rút tiền')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Yêu cầu rút tiền</h4>

    <form action="{{ route('wallet.withdraw.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="amount" class="form-label">Số tiền muốn rút</label>
            <input type="number" name="amount" id="amount" class="form-control" required min="10000" placeholder="Nhập số tiền">
            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Nếu user đã lưu nhiều tài khoản thì hiển thị --}}
@if(isset($bankAccounts) && $bankAccounts->count() > 0)
    <div class="mb-3 d-flex align-items-center">
        <div class="flex-grow-1">
            <label for="bank_account_id" class="form-label">Chọn tài khoản ngân hàng đã lưu</label>
            <select name="bank_account_id" id="bank_account_id" class="form-select">
                <option value="">-- Chọn tài khoản đã lưu --</option>
                @foreach($bankAccounts as $account)
                    <option value="{{ $account->id }}">
                        {{ $account->bank_name }} - {{ $account->account_number }} ({{ $account->account_holder }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mb-2">
        <label class="form-label">Tài khoản đã lưu:</label>
        <ul class="list-group">
            @foreach($bankAccounts as $account)
                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                    <span>
                        {{ $account->bank_name }} - {{ $account->account_number }} ({{ $account->account_holder }})
                    </span>
                    <button type="button" class="btn btn-sm btn-danger delete-bank-btn" data-id="{{ $account->id }}">
                        Xóa
                    </button>
                </li>
            @endforeach
        </ul>
    </div>
    <p class="text-muted">Hoặc nhập tài khoản mới:</p>
@endif


        {{-- Nhập tài khoản mới --}}
        <div id="new-bank-fields">
            <div class="mb-3">
                <label class="form-label">Tên ngân hàng</label>
                <input type="text" name="bank_name" class="form-control" placeholder="VD: Vietcombank">
            </div>

            <div class="mb-3">
                <label class="form-label">Số tài khoản</label>
                <input type="text" name="account_number" class="form-control" placeholder="Nhập số tài khoản">
            </div>

            <div class="mb-3">
                <label class="form-label">Tên chủ tài khoản</label>
                <input type="text" name="account_holder" class="form-control" placeholder="Nhập tên chủ tài khoản">
            </div>
        </div>

        {{-- Lý do rút tiền --}}
        <div class="mb-3">
            <label for="note" class="form-label">Lý do rút tiền</label>
            <textarea name="note" id="note" class="form-control" rows="3" placeholder="Nhập lý do rút tiền (nếu có)"></textarea>
            @error('note') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
        <a href="{{ route('wallet.history') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>

{{-- JS toggle --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const select = document.getElementById('bank_account_id');
    const newBankFields = document.getElementById('new-bank-fields');
    const savedAccountsContainer = document.querySelector('.list-group').closest('div');
    // hoặc bao div chứa label "Tài khoản đã lưu" và <ul>

    function toggleFields() {
        const hasSelection = select && select.value;
        // Ẩn hiện form nhập mới
        newBankFields.style.display = hasSelection ? 'none' : 'block';

        // Ẩn hiện danh sách tài khoản đã lưu
        if (savedAccountsContainer) {
            savedAccountsContainer.style.display = hasSelection ? 'none' : 'block';
        }
    }

    toggleFields();
    if (select) {
        select.addEventListener('change', toggleFields);
    }

    // Xóa tài khoản bằng AJAX
    document.querySelectorAll('.delete-bank-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const accountId = this.getAttribute('data-id');
            const listItem = this.closest('li');
            if (confirm('Bạn có chắc muốn xóa tài khoản này?')) {
                fetch(`/wallet/bank/${accountId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Xóa li trong danh sách
                        listItem.remove();

                        // Xóa option trong select
                        if (select) {
                            const option = select.querySelector(`option[value="${accountId}"]`);
                            if (option) option.remove();
                        }

                        // Nếu option bị xóa đang được chọn -> reset
                        if (select && select.value === accountId) {
                            select.value = '';
                            toggleFields();
                        }

                        alert('Đã xóa tài khoản thành công!');
                    } else {
                        alert(data.message || 'Có lỗi xảy ra!');
                    }
                })
                .catch(() => alert('Có lỗi xảy ra!'));
            }
        });
    });
});


</script>


@endsection
