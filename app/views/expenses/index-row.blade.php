<tr>
    <td>{{ $expense->present()->expense_date }}</td>
    <td><a href="{{ route('account.show', $expense->user->id) }}">{{ $expense->user->name }}</a></td>
    <td>{{ $expense->present()->category }}</td>
    <td>{{ $expense->present()->description }}</td>
    <td>{{ $expense->present()->amount }}</td>
    <td><a href="{{ $expense->present()->file }}">View / Download</a></td>
    <td>
        @if ($expense->approved)
            Approved by {{ $expense->approved_by->given_name }}
        @elseif ($expense->declined)
            Declined by {{ $expense->approved_by->given_name }}
        @elseif (Auth::user()->hasRole('admin'))
        <div class="btn-group">
            {{ Form::open(array('method' => 'PUT', 'route' => ['expenses.update', $expense->id], 'onSubmit' => 'return window.confirm("Are you sure??")')) }}
            {{ Form::submit('Approve', array('name' => 'approve', 'class'=>'btn btn-primary')) }}
            {{ Form::submit('Decline', array('name' => 'decline', 'class'=>'btn btn-primary')) }}
            {{ Form::close() }}
        </div>
        @endif
    </td>
</tr>