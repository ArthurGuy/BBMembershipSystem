<?php $payments = $user->payments()->paginate(10); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Payments</h3>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Reason</th>
            <th>Method</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td>{{ $payment->present()->reason }}</td>
            <td>{{ $payment->present()->method }}</td>
            <td>{{ $payment->present()->date }}</td>
            <td>{{ $payment->present()->amount }}</td>
            <td>{{ $payment->present()->status }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
    <?php echo $payments->links(); ?>
    </div>
</div>