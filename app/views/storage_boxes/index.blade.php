@extends('layouts.main')

@section('page-title')
    Member Storage Boxes
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="well">
                @if ($boxesTaken > 0)
                    You have the following boxes, (please make sure your name is written on a masking tape label on each one)<br />
                    <ul>
                        @foreach($memberBoxes as $box)
                            <li>
                                {{ Form::open(array('method'=>'PUT', 'route' => ['storage_boxes.update', $box->id], 'class'=>'')) }}
                                {{ Form::hidden('user_id', '') }}
                                Box {{ $box->id }} - {{ $box->size }}L
                                {{ Form::submit('Return Box', array('class'=>'btn btn-default btn-link btn-sm')) }}
                                {{ Form::close() }}
                            </li>
                        @endforeach
                    </ul>
                    <p>
                    @if ($volumeAvailable >= 4)
                        You can claim a further {{ $volumeAvailable }} liters<br />
                    @else
                        You have claimed a total of {{ 19-$volumeAvailable }}L<br />
                    @endif
                        If your no longer using a box and want to return it or swap for a different combination please empty the
                        box (and clean if necessary) and return it to the member shelves, you can then use the return link above.
                    </p>
                @endif
                @if ($canPayMore)
                    <p>
                    If you wish to claim @if ($boxesTaken > 0) another @else a @endif box you will need pay its &pound;5 deposit
                    </p>
                    @include('partials/payment-form', ['reason'=>'storage-box', 'displayReason'=>'Storage Box Deposit', 'returnPath'=>route('storage_boxes.index', [], false), 'amount'=>5, 'buttonLabel'=>'Pay Now', 'methods'=>['gocardless', 'balance']])
                @endif
                @if ($moneyAvailable > 0)
                    To claim a box click claim next to the box you want below, you should probably make sure its on the shelf before you do this.
                @endif

            </div>
        </div>
        <div class="col-md-6">
            <div class="well">
                Each member can claim up to 19L of member storage, this can be one 19L box or a combination of 4L and 9L boxes.
                Each individual box requires a &pound;5 deposit.
            </div>
            <div class="well">
                Storage Box Payments
                <ul>
                @foreach ($boxPayments as $payment)
                    <li>{{ $payment->present()->date }} - {{ $payment->present()->amount }}</li>
                @endforeach
                </ul>
                Total Paid &pound{{ $paymentTotal }}
            </div>
        </div>

    </div>

@if (Auth::user()->hasRole('storage'))
<div class="well">
    When marking a box as returned please make sure the box has been cleared and is available in the shelf.
</div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Size</th>
            <th>Member</th>
            <th></th>
            @if (Auth::user()->hasRole('storage'))
            <th>Admin</th>
            @endif
        </tr>
    </thead>
@foreach ($storageBoxes as $box)
    <tbody>
        <tr @if($box->user && !$box->user->active)class="warning"@elseif(!$box->user)class="success"@endif>
            <td>{{ $box->id }}</td>
            <td>{{ $box->size }}L</td>
            <td>{{ $box->user->name or 'Available' }}</td>
            <td>
                @if($box->user && !$box->user->active)
                    Member left - box to be reclaimed
                @elseif (($volumeAvailable >= $box->size) && !$box->user)
                    {{ Form::open(array('method'=>'PUT', 'route' => ['storage_boxes.update', $box->id], 'class'=>'navbar-form navbar-left')) }}
                    {{ Form::hidden('user_id', Auth::user()->id) }}
                    {{ Form::submit('Claim', array('class'=>'btn btn-default')) }}
                    {{ Form::close() }}
                @endif
            </td>
            @if (Auth::user()->hasRole('storage'))
                <td>
                    @if($box->user)
                        {{ Form::open(array('method'=>'PUT', 'route' => ['storage_boxes.update', $box->id], 'class'=>'navbar-form navbar-left')) }}
                        {{ Form::hidden('user_id', '') }}
                        {{ Form::submit('Reclaim', array('class'=>'btn btn-default btn-sm')) }}
                        {{ Form::close() }}
                    @endif
                </td>
            @endif
        </tr>
    </tbody>
@endforeach
</table>


@stop