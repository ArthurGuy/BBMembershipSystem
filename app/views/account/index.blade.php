<div class="row page-header">
    <div class="col-xs-12 col-sm-10">
        <h1>Members</h1>
    </div>
    <div class="col-xs-12 col-sm-2">
        <p><a href="{{ route('account.create') }}" class="btn btn-info btn-sm">Create a new member</a></p>
    </div>
</div>

<div>
    Active Members: {{ $numActiveUsers }}
</div>

<?php echo $users->links(); ?>
<table class="table">
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Email</th>
            <th>Active</th>
            <th>Status</th>
            <th>Key Holder</th>
            <th>Induction Complete</th>
            <th>Payment Method</th>
            <th>Subscription Expires</th>
            <th>Payment</th>
        </tr>
    </thead>
    <tbody>
        @each('account.index-row', $users, 'user')
    </tbody>
</table>
<?php echo $users->links(); ?>