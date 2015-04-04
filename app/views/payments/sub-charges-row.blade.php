<tr>
    <td>{{ $charge->present()->charge_date }}</td>
    <td><a href="{{ route('account.show', $charge->user->id) }}">{{ $charge->user->name }}</a></td>
    <td>{{ $charge->present()->payment_date }}</td>
    <td>{{ $charge->present()->status }}</td>
    <td>{{ $charge->present()->amount }}</td>
    <td>{{ $charge->user->present()->paymentMethod() }}</td>
</tr>