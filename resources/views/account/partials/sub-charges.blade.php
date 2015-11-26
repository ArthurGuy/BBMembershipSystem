
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Monthly Subscription Charges</h3>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Charge Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Payment Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($subscriptionCharges as $charge)
        <tr class="{{ $charge->present()->rowClass() }}">
            <td>{{ $charge->present()->charge_date }}</td>
            <td>{{ $charge->present()->amount }}</td>
            <td>{{ $charge->present()->status }}</td>
            <td>{{ $charge->present()->payment_date }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
    {!! $subscriptionCharges->render() !!}
    </div>
</div>