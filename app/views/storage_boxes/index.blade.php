<div class="page-header">
    <h1>Member Storage Boxes</h1>
</div>

@if ($memberBox)

    <p>
        You have a storage box
    </p>

@elseif($boxPayment)

    <p>
        We have your payment, a box will be assigned shortly.
    </p>
@else

    <div class="well">
        <p>
            Storage boxes require a £5 deposit, this can be paid in cash at the space or via Direct Debit now.
        </p>
        {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.create', Auth::user()->id])) }}
        {{ Form::hidden('reason', 'storage-box') }}
        {{ Form::hidden('source', 'gocardless') }}
        {{ Form::submit('Pay Now (DD)', array('class'=>'btn btn-primary btn-xs')) }}
        <small>You don't need to be paying via direct debit to use the option</small>
        {{ Form::close() }}
    </div>

@endif

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Size</th>
            <th>Member</th>
        </tr>
    </thead>
@foreach ($storageBoxes as $box)
    <tbody>
        <tr>
            <td>{{ $box->id }}</td>
            <td>{{ $box->size }}</td>
            <td>{{ $box->user->name or 'Available' }}</td>
        </tr>
    </tbody>
@endforeach
</table>