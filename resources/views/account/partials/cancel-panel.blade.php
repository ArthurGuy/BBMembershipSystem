<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Thinking of leaving?</h3>
    </div>
    <div class="panel-body">
        <p>
        If you're thinking of leaving we would love to hear from you first, please send us a message and we will see if we can help.<br />
        <a href="mailto:trustees@buildbrighton.com">Email the trustees</a>
        </p>
        @if ($user->payment_method == 'gocardless')

        {!! Form::open(array('method'=>'DELETE', 'route' => ['account.subscription.destroy', $user->id, 1])) !!}
        {!! Form::submit('Cancel Your Monthly Direct Debit', array('class'=>'btn btn-link')) !!}
        {!! Form::close() !!}

        @elseif ($user->payment_method == 'gocardless-variable')

            {!! Form::open(array('method'=>'DELETE', 'route' => ['account.subscription.destroy', $user->id, 1])) !!}
            {!! Form::submit('Cancel Your Direct Debit and Leave', array('class'=>'btn btn-link')) !!}
            {!! Form::close() !!}

        @else

        {!! Form::open(array('method'=>'DELETE', 'route' => ['account.destroy', $user->id])) !!}
        {!! Form::submit('Leave Build Brighton :(', array('class'=>'btn btn-link')) !!}
        {!! Form::close() !!}

        @endif
    </div>
</div>