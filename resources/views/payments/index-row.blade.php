<tr>
    <td>{{ $payment->present()->date }}</td>
    <td>
        @if ($payment->user)
        <a href="{{ route('account.show', $payment->user->id) }}">{{ $payment->user->name }}</a>
        @else
            Unknown
        @endif
    </td>
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
                    {!! Form::open(array('method'=>'DELETE', 'route' => ['payments.destroy', $payment->id], 'class'=>'navbar-form navbar-left')) !!}
                    {!! Form::submit('Delete', array('class'=>'btn btn-link')) !!}
                    {!! Form::close() !!}
                    </li>
                @endif
                @if ($payment->user && $payment->reason != 'balance')
                    <li>
                        {!! Form::open(array('method'=>'PUT', 'route' => ['payments.update', $payment->id], 'class'=>'navbar-form navbar-left')) !!}
                        {!! Form::hidden('change', 'refund-to-balance') !!}
                        {!! Form::submit('Refund to Balance', array('class'=>'btn btn-link')) !!}
                        {!! Form::close() !!}
                    </li>
                @endif
                @if (!$payment->user)
                    <li>
                        {!! Form::open(array('method'=>'PUT', 'route' => ['payments.update', $payment->id], 'class'=>'navbar-form navbar-left')) !!}
                        {!! Form::hidden('change', 'assign-unknown-to-user') !!}
                        {!! Form::input('number', 'user_id', null, ['placeholder' => 'User ID', 'class' => 'form-control']) !!}
                        {!! Form::submit('Assign to user', array('class'=>'btn')) !!}
                        {!! Form::close() !!}
                    </li>
                @endif
            </ul>
        </div>
    </td>
</tr>