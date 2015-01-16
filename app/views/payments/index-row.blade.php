<tr>
    <td>{{ $payment->present()->date }}</td>
    <td><a href="{{ route('account.show', $payment->user->id) }}">{{ $payment->user->name }}</a></td>
    <td>{{ $payment->present()->reason }}</td>
    <td>{{ $payment->present()->method }}</td>
    <td>{{ $payment->present()->amount }}</td>
    <td>{{ $payment->present()->status }}</td>
</tr>