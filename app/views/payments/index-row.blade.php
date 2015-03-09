<tr>
    <td>{{ $payment->present()->date }}</td>
    <td><a href="{{ route('account.show', $payment->user->id) }}">{{ $payment->user->name }}</a></td>
    <td>{{ $payment->present()->reason }}</td>
    <td>{{ $payment->present()->method }}</td>
    <td>{{ $payment->present()->amount }}</td>
    <td>{{ $payment->present()->reference }}</td>
    <td>{{ $payment->present()->status }}</td>
    <td>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Action <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                @if (($payment->source == 'cash') && ($payment->reason == 'balance'))
                    <li>
                    {{ Form::open(array('method'=>'DELETE', 'route' => ['payments.destroy', $payment->id], 'class'=>'navbar-form navbar-left')) }}
                    {{ Form::submit('Delete', array('class'=>'btn btn-link')) }}
                    {{ Form::close() }}
                    </li>
                @endif
                @if ($payment->reason != 'balance')
                    <li>
                        {{ Form::open(array('method'=>'PUT', 'route' => ['payments.update', $payment->id], 'class'=>'navbar-form navbar-left')) }}
                        {{ Form::hidden('reason', 'balance') }}
                        {{ Form::submit('Refund to Balance', array('class'=>'btn btn-link')) }}
                        {{ Form::close() }}
                    </li>
                @endif
            </ul>
        </div>
    </td>
</tr>