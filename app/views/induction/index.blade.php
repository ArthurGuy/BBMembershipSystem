<div class="page-header">
    <h1>Equipment Training</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Equipment</th>
            <th>Member</th>
            <th>Paid</th>
            <th>Trained</th>
            <th>Trainer</th>
            <th>Trained By</th>
        </tr>
    </thead>
@foreach ($inductions as $induction)
    <tbody>
        <tr>
            <td>{{ $induction->key }}</td>
            <td>{{ $induction->user->name or 'Unknown' }}</td>
            <td>
                @if($induction->paid)
                <span class="glyphicon glyphicon-ok"></span>
                @endif
            </td>
            <td>
                @if($induction->is_trained)
                <span class="glyphicon glyphicon-ok"></span>
                @endif
            </td>
            <td>
                @if($induction->is_trainer)
                <span class="glyphicon glyphicon-ok"></span>
                @endif
            </td>
            <td>{{ $induction->trainer_user->name or '' }}</td>
        </tr>
    </tbody>
@endforeach
</table>