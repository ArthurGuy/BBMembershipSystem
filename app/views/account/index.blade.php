<div class="page-header">
    <h1>Members</h1>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Active</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Induction Complete</th>
            <th>Subscription Expires</th>
        </tr>
    </thead>
@foreach ($users as $user)
    <tbody>
        <tr>
            <td>
                @if ($user->active)
                <span class="glyphicon glyphicon-ok"></span>
                @else
                <span class="glyphicon glyphicon-remove"></span>
                @endif
            </td>
            <td>
                <a href="{{ route('account.show', $user->id) }}">{{ $user->name }}</a>
                @if ($user->type == 'admin')
                <span class="label label-danger">Admin</span>
                @endif
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ User::statusLabel($user->status) }}</td>
            <td>
                @if ($user->induction_completed)
                <span class="glyphicon glyphicon-ok"></span>
                @else
                <span class="glyphicon glyphicon-remove"></span>
                @endif
            </td>
            <td>
                @if ($user->subscription_expires->year > 0)
                    {{ $user->subscription_expires->toFormattedDateString() }}
                @else
                    -
                @endif
            </td>
        </tr>
    </tbody>
@endforeach
</table>