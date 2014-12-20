@extends('layouts.main')

@section('page-title')
    Member Storage Boxes
@stop

@section('content')

@if ($memberBox)

    <p>
        You have box {{ $memberBox->id }}.
        Please make sure you name is written on the masking tape label, this allows others to return items to you.
    </p>

@elseif($boxPayment)

    <div class="well">
        @if ($availableBoxes > 0)
            <p>
                It looks like we have a box available, you can use the claim button below to select a storage box.<br />
                You should probably check to make sure its on the shelf first.
            </p>
        @else
            <p>We have your payment but don't currently have any spare boxes, we hope to get more soon.</p>
        @endif
    </div>

@else

    <div class="well">

        @if ($availableBoxes > 0)

        <p>
            Storage boxes require a &pound;5 deposit, this can be paid in cash at the space or via the form below.
        </p>

        @include('partials/payment-form', ['reason'=>'storage-box', 'displayReason'=>'Storage Box Deposit', 'returnPath'=>route('storage_boxes.index', [], false), 'amount'=>5, 'buttonLabel'=>'Pay Now', 'methods'=>['gocardless']])

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
            <th></th>
        </tr>
    </thead>
@foreach ($storageBoxes as $box)
    <tbody>
        <tr @if($box->user && !$box->user->active)class="warning"@elseif(!$box->user)class="success"@endif>
            <td>{{ $box->id }}</td>
            <td>{{ $box->size }}</td>
            <td>{{ $box->user->name or 'Available' }}</td>
            <td>
                @if($box->user && !$box->user->active)
                    @if (Auth::user()->isAdmin())
                        {{ Form::open(array('method'=>'PUT', 'route' => ['storage_boxes.update', $box->id], 'class'=>'navbar-form navbar-left')) }}
                        {{ Form::hidden('user_id', '') }}
                        Member left:
                        {{ Form::submit('Reclaim', array('class'=>'btn btn-default btn-sm')) }}
                        {{ Form::close() }}
                    @else
                        Member left - box to be reclaimed
                    @endif
                @elseif ($canClaimBox && !$box->user)
                    {{ Form::open(array('method'=>'PUT', 'route' => ['storage_boxes.update', $box->id], 'class'=>'navbar-form navbar-left')) }}
                    {{ Form::hidden('user_id', Auth::user()->id) }}
                    {{ Form::submit('Claim', array('class'=>'btn btn-default')) }}
                    {{ Form::close() }}
                @endif
            </td>
        </tr>
    </tbody>
@endforeach
</table>


@stop