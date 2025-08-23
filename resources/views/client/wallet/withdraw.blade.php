@extends('client.layout.main')

@section('title', 'Rút tiền')

@section('content')
    <div class="container py-4">
        <h4 class="mb-3">Yêu cầu rút tiền</h4>

        <form action="{{ route('wallet.withdraw.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="amount" class="form-label">Số tiền muốn rút</label>
                <input type="number" name="amount" id="amount" class="form-control" required min="10000"
                    placeholder="Nhập số tiền">
                @error('amount')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Nếu user đã lưu nhiều tài khoản thì hiển thị --}}
            @if (isset($bankAccounts) && $bankAccounts->count() > 0)
                <div class="mb-3 d-flex align-items-center">
                    <div class="flex-grow-1">
                        <label for="bank_account_id" class="form-label">Chọn tài khoản ngân hàng đã lưu</label>
                        <select name="bank_account_id" id="bank_account_id" class="form-select">
                            <option value="">-- Chọn tài khoản đã lưu --</option>
                            @foreach ($bankAccounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->bank_name }} - {{ $account->account_number }}
                                    ({{ $account->account_holder }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Tài khoản đã lưu:</label>
                    <ul class="list-group">
                        @foreach ($bankAccounts as $account)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span>
                                    {{ $account->bank_name }} - {{ $account->account_number }}
                                    ({{ $account->account_holder }})
                                </span>
                                <button type="button" class="btn btn-sm btn-danger delete-bank-btn"
                                    data-id="{{ $account->id }}">
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
                    <label class="form-label">Chọn ngân hàng</label>
                    <div id="bank-list" class="d-flex flex-wrap gap-2">
                        @php
                            $banks = [
                                ['name' => 'Vietcombank', 'logo' => '/storage/banks/vietcombank.png'],
                                ['name' => 'Techcombank', 'logo' => '/storage/banks/techcombank.png'],
                                ['name' => 'BIDV', 'logo' => '/storage/banks/bidv.png'],
                                ['name' => 'MB Bank', 'logo' => '/storage/banks/mbbank.png'],
                                ['name' => 'ACB', 'logo' => '/storage/banks/acb.png'],
                                ['name' => 'Sacombank', 'logo' => '/storage/banks/sacombank.png'],
                                ['name' => 'VPBank', 'logo' => '/storage/banks/vpbank.png'],
                                ['name' => 'Agribank', 'logo' => '/storage/banks/agribank.png'],
                                ['name' => 'TPBank', 'logo' => '/storage/banks/tpbank.png'],
                                ['name' => 'HDBank', 'logo' => '/storage/banks/hdbank.png'],
                                ['name' => 'LienVietPostBank', 'logo' => '/storage/banks/lienvietpostbank.png'],
                                ['name' => 'VietinBank', 'logo' => '/storage/banks/vietinbank.png'],
                                ['name' => 'OceanBank', 'logo' => '/storage/banks/oceanbank.png'],
                                ['name' => 'Eximbank', 'logo' => '/storage/banks/eximbank.png'],
                                ['name' => 'SHB', 'logo' => '/storage/banks/shb.png'],
                                ['name' => 'SCB', 'logo' => '/storage/banks/scb.png'],
                                ['name' => 'Nam A Bank', 'logo' => '/storage/banks/namabank.png'],
                                ['name' => 'VIB', 'logo' => '/storage/banks/vib.png'],
                                ['name' => 'ABBANK', 'logo' => '/storage/banks/abbank.png'],
                                ['name' => 'PG Bank', 'logo' => '/storage/banks/pgbank.png'],
                            ];

                        @endphp

                        @foreach ($banks as $bank)
                            <div class="bank-item text-center" data-name="{{ $bank['name'] }}" style="cursor:pointer;">
                                <img src="{{ $bank['logo'] }}" alt="{{ $bank['name'] }}" width="60">
                                <div style="font-size: 12px;">{{ $bank['name'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tên ngân hàng</label>
                    <input type="text" name="bank_name" id="bank_name" class="form-control"
                        placeholder="VD: Vietcombank">
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
                @error('note')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
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
            const savedAccountsContainer = document.querySelector('.list-group') ?
                document.querySelector('.list-group').closest('div') :
                null;

            function toggleFields() {
                const hasSelection = select && select.value;
                newBankFields.style.display = hasSelection ? 'none' : 'block';
                if (savedAccountsContainer) {
                    savedAccountsContainer.style.display = hasSelection ? 'none' : 'block';
                }
            }

            toggleFields();
            if (select) {
                select.addEventListener('change', toggleFields);
            }

            // --- Gán sự kiện click cho bank-item ---
            document.querySelectorAll('.bank-item').forEach(item => {
                item.addEventListener('click', function() {
                    const bankName = this.getAttribute('data-name');
                    const bankInput = document.getElementById('bank_name');
                    if (bankInput) {
                        bankInput.value = bankName;
                    }
                    // Highlight bank đã chọn (thêm class selected)
                    document.querySelectorAll('.bank-item').forEach(el => {
                        el.classList.remove('selected');
                    });
                    this.classList.add('selected');
                });
            });

            // --- Xóa tài khoản bằng AJAX ---
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
                                    listItem.remove();
                                    if (select) {
                                        const option = select.querySelector(
                                            `option[value="${accountId}"]`);
                                        if (option) option.remove();
                                    }
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

@push('style')
    <style>
        #bank-list {
            gap: 16px !important;
        }

        .bank-item {
            width: 90px;
            padding: 10px 0 4px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #fff;
            transition: border 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        .bank-item img {
            display: block;
            margin-bottom: 6px;
            max-width: 60px;
            max-height: 40px;
            object-fit: contain;
        }

        .bank-item.selected,
        .bank-item:focus,
        .bank-item:active {
            border: 2px solid #007bff;
            box-shadow: 0 0 4px #007bff33;
        }

        .bank-item div {
            font-size: 13px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }
    </style>
@endpush
