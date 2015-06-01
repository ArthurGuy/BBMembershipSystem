<tr>
    <td>{{ $charge->present()->charge_date }}</td>
    <td>
        @if ($charge->user)
        <a href="{{ route('account.show', $charge->user->id) }}">{{ $charge->user->name }}</a>
        @else
        Unknown
        @endif
    </td>
    <td>{{ $charge->present()->payment_date }}</td>
    <td>{{ $charge->present()->status }}</td>
    <td>{{ $charge->present()->amount }}</td>
    <td>
        @if ($charge->user)
        {{ $charge->user->present()->paymentMethod() }}
        @endif
    </td>
</tr>