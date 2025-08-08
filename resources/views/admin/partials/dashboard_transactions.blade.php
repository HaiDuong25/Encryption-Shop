@foreach ($transactions as $transaction)
<tr>
    <td>
        <div class="transactions-icon">
            @if($transaction->paymentMethod->payment_type == 'momo')
            <i class="ri-bank-card-line"></i>
            @else
            <i class="ri-money-dollar-circle-line"></i>
            @endif
        </div>
        <div class="transactions-name">
            <h6>{{ strtoupper($transaction->paymentMethod->payment_type) }}</h6>
            <p>{{ $transaction->paymentMethod->description }}</p>
        </div>
    </td>
    <td class="success">+ {{ format_vnd($transaction->total_amount) }} đ</td>
</tr>
@endforeach
