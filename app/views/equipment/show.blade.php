@extends('layouts.main')

@section('page-title')
Tools &amp; Equipment > {{ $equipment->name }}
@stop

@section('meta-title')
Tools and Equipment
@stop

@section('main-tab-bar')

@stop


@section('content')

<div class="row">

    <div class="col-md-12 col-lg-12">
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="well">

                    <div class="row">
                        <div class="col-md-12 col-lg-6">

                            Make: {{ $equipment->present()->manufacturerModel }}<br />
                            Colour: {{ $equipment->colour }}<br />
                            Lives in: {{ $equipment->present()->livesIn }}<br />
                            Purchased: {{ $equipment->present()->purchaseDate }}<br />

                        </div>
                        <div class="col-md-12 col-lg-6">

                            @if ($equipment->hasPhoto())
                                <img src="{{ $equipment->getPhotoUrl(1) }}" class="img-thumbnail pull-right" width="200" />
                            @endif

                        </div>
                    </div>



                    <br />

                    {{ $equipment->present()->description }}<br />

                    @if ($equipment->isPermaloan())
                        <span class="label label-warning">Permaloan</span>
                    @endif





                    @if ($equipment->requiresInduction())
                        To use this piece of equipment an access fee and an induction is required. The access fee goes towards equipment maintenance.<br />
                        Equipment access fee: &pound{{ $equipment->access_fee }}<br />
                        <br />
                        @if ($userInduction)
                            Induction to be completed
                        @else
                            @include('partials/payment-form', ['reason'=>'induction', 'displayReason'=>'Equipment Access Fee', 'returnPath'=>route('equipment.show', [$equipmentId], false), 'amount'=>$equipment->access_fee, 'buttonLabel'=>'Pay Now', 'methods'=>['balance'], 'ref'=>$equipmentId])
                        @endif

                    @else
                        No fee required
                    @endif
                    @if (!$equipment->isWorking())
                        <span class="label label-danger">Out of action</span>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-lg-6">

                @if ($equipment->requiresInduction())
                    <div class="row">
                    <h4>Trainers</h4>
                    <div class="list-group">
                        @foreach($trainers as $trainer)
                            <a href="{{ route('members.show', $trainer->user->id) }}" class="list-group-item">
                                {{ HTML::memberPhoto($trainer->user->profile, $trainer->user->hash, 25, '') }}
                                {{{ $trainer->user->name }}}
                            </a>
                        @endforeach
                    </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    @if ($equipment->hasActivity())
    <h3>Activity Log</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Used for</th>
                <th>Member</th>
                <th>Reason</th>
                @if (Auth::user()->isAdmin() || Auth::user()->hasRole($equipmentId))
                <th></th>
                @endif
            </tr>
        </thead>
        <tbody>
        @foreach($equipmentLog as $log)
            <tr>
                <td>{{ $log->present()->started }}</td>
                <td>{{ $log->present()->timeUsed }}</td>
                <td><a href="{{ route('members.show', $log->user->id) }}">{{{ $log->user->name }}}</a></td>
                <td>{{ $log->present()->reason }}</td>
                @if (Auth::user()->isAdmin() || Auth::user()->hasRole($equipmentId))
                <td>
                    @if (empty($log->reason))
                    {{ Form::open(['method'=>'POST', 'route'=>['equipment_log.update', $log->id], 'name'=>'equipmentLog']) }}
                    {{ Form::select('reason', ['testing'=>'Testing', 'training'=>'Training'], $log->reason, ['class'=>'']) }}
                    {{ Form::submit('Update', ['class'=>'btn btn-primary btn-xs']) }}
                    {{ Form::close() }}
                    @endif
                </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
        <?php echo $equipmentLog->links(); ?>
    </div>
    @endif


    @if ($equipment->requiresInduction())
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h4>Trained Users</h4>
                <ul>
                    @foreach($trainedUsers as $trainedUser)
                        <li>
                            <a href="{{ route('members.show', $trainedUser->user->id) }}">
                                {{{ $trainedUser->user->name }}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-12 col-md-6">
                <h4>Members waiting for an Induction</h4>
                <ul>
                    @foreach($usersPendingInduction as $trainedUser)
                        <li>
                            <a href="{{ route('members.show', $trainedUser->user->id) }}">
                                {{{ $trainedUser->user->name }}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

</div>

@stop

@section('footer-js')

@stop