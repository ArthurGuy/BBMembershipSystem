<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title">Cancel</h3>
    </div>
    <div class="panel-body">
        @if ($user->payment_method == 'gocardless')

        {{ Form::open(array('method'=>'DELETE', 'route' => ['account.subscription.destroy', $user->id, 1])) }}
        {{ Form::submit('Cancel Your Monthly Direct Debit', array('class'=>'btn btn-danger')) }}
        {{ Form::close() }}

        @else

        {{ Form::open(array('method'=>'DELETE', 'route' => ['account.destroy', $user->id])) }}
        {{ Form::submit('Leave Build Brighton :(', array('class'=>'btn btn-danger')) }}
        {{ Form::close() }}

        @endif
    </div>
</div>