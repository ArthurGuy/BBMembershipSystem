<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Inductions / Equipment Training</h3>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Cost</th>
            <th>Trained</th>
            <th>
                @if (Auth::user()->isAdmin())
                Payment
                <span class="label label-danger">Admin</span>
                @endif
            </th>
            <th>
                @if (Auth::user()->isAdmin())
                Trainer
                <span class="label label-danger">Admin</span>
                @endif
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($inductions as $itemKey=>$item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>&pound;{{ $item->cost }}</td>
            <td>
                @if ($item->userInduction && ($item->userInduction->is_trained))
                {{ $item->userInduction->trained->toFormattedDateString() }}
                @elseif ($item->userInduction && $item->userInduction->paid)
                Pending
                @else
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.create', $user->id])) }}
                {{ Form::hidden('induction_key', $itemKey) }}
                {{ Form::hidden('reason', 'induction') }}
                {{ Form::hidden('source', 'gocardless') }}
                {{ Form::submit('Pay Now (DD)', array('class'=>'btn btn-primary btn-xs')) }}
                {{ Form::close() }}
                @endif
            </td>
            <td>
                @if (Auth::user()->isAdmin() && (!$item->userInduction || ($item->userInduction && !$item->userInduction->paid)))
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.store', $user->id])) }}
                {{ Form::hidden('induction_key', $itemKey) }}
                {{ Form::hidden('reason', 'induction') }}
                {{ Form::hidden('source', 'manual') }}
                {{ Form::submit('Mark Paid', array('class'=>'btn btn-default btn-xs')) }}
                {{ Form::close() }}
                @endif
            </td>
            <td>
                @if (Auth::user()->isAdmin() && $item->userInduction && !$item->userInduction->is_trained)
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.induction.update', $user->id, $item->userInduction->id])) }}
                {{ Form::select('trainer_user_id', Induction::trainersForDropdown($itemKey)) }}
                {{ Form::hidden('mark_trained', '1') }}
                {{ Form::submit('Trained By', array('class'=>'btn btn-default btn-xs')) }}
                {{ Form::close() }}
                @elseif (Auth::user()->isAdmin() && $item->userInduction && $item->userInduction->is_trained)
                {{ $item->userInduction->trainer_user->name or '' }}
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>