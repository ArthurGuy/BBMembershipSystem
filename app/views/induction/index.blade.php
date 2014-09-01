<div class="page-header">
    <h1>Inductions / Equipment</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Equipment</th>
            <th>User</th>
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
            <td>{{ $induction->paid }}</td>
            <td>{{ $induction->is_trained }}</td>
            <td>{{ $induction->is_trainer }}</td>
            <td>{{ $induction->trainer_user->name or '' }}</td>
        </tr>
    </tbody>
@endforeach
</table>