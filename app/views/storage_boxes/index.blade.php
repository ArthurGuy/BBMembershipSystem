@extends('layouts.main')

@section('page-title')
    Member Storage Boxes
@stop

@section('content')

@if ($memberBox)

    <p>
        You have a storage box
    </p>

@elseif($boxPayment)

    <p>
        We have your payment, please email <a href="mailto::arthur@arthurguy.co.uk">Arthur</a> to arrange to collect a box.
    </p>
@else

    <div class="well">

        @if ($availableBoxes > 0)

        <p>
            Storage boxes require a £5 deposit, this can be paid in cash at the space or via the form below.
        </p>

        @include('partials/payment-form', ['reason'=>'storage-box', 'displayReason'=>'Storage Box Deposit', 'returnPath'=>route('storage_boxes.index', [], false), 'amount'=>10, 'buttonLabel'=>'Pay Now', 'methods'=>['gocardless']])

        @else

            We don't currently have any available boxes, please email us and we will try and sort something out.<br />
            <a href="mailto:trustees@buildbrighton.com">trustees@buildbrighton.com</a>

        @endif
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


@stop