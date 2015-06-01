<tr>
    <td>{{ $expense->present()->expense_date }}</td>
    <td><a href="{{ route('members.show', $expense->user->id) }}">{{ $expense->user->name }}</a></td>
    <td>{{ $expense->present()->category }}</td>
    <td>{{ $expense->present()->description }}</td>
    <td>{{ $expense->present()->amount }}</td>
    @if (Auth::user()->hasRole('admin'))
    <td><a href="{{ $expense->present()->file }}">View / Download</a></td>
    @endif
    <td>
        @if ($expense->approved)
            Approved by {{ $expense->approvedBy->given_name }}
        @elseif ($expense->declined)
            Declined by {{ $expense->approvedBy->given_name }}
        @elseif (Auth::user()->hasRole('admin'))
        <div class="btn-group">
            {!! Form::open(array('method' => 'PUT', 'route' => ['expenses.update', $expense->id], 'onSubmit' => 'return window.confirm("Are you sure??")')) !!}
            {!! Form::submit('Approve', array('name' => 'approve', 'class'=>'btn btn-primary')) !!}
            {!! Form::submit('Decline', array('name' => 'decline', 'class'=>'btn btn-primary')) !!}
            {!! Form::close() !!}
        </div>
        @endif
    </td>
</tr>