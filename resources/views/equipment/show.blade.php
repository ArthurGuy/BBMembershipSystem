@extends('layouts.main')

@section('page-title')
<a href="{{ route('equipment.index') }}">Tools &amp; Equipment</a> > {{ $device->name() }}
@stop

@section('meta-title')
Tools and Equipment
@stop

@section('page-action-buttons')
    @if (!Auth::guest() && Auth::user()->hasRole('equipment'))
        <a class="btn btn-secondary" href="{{ route('equipment.edit', $device->slug()) }}">Edit</a>
    @endif
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

                            @if ($device->present()->manufacturerModel()) Make: {{ $device->present()->manufacturerModel }}<br />@endif
                            <!-- @if ($device->properties()->colour()) Colour: {{ $device->properties()->colour() }}<br />@endif -->
                            @if ($device->present()->livesIn()) Lives in: {{ $device->present()->livesIn() }}<br />@endif
                            @if ($device->present()->purchaseDate) Purchased: {{ $device->present()->purchaseDate }}<br />@endif
                            @if ($device->cost()->requiresInduction()) Induction required<br /> @endif
                            @if ($device->cost()->hasUsageCharge())
                            Usage Cost: {!! $device->present()->usageCost() !!}<br />
                            @endif
                            @if ($device->isManagedByGroup())
                                Managed By: <a href="{{ route('groups.show', $device->role()->name()) }}">{{ $device->role()->title() }}</a>
                            @endif

                            @if (!$device->isWorking())<span class="label label-danger">Out of action</span>@endif
                            @if ($device->owner()->isPermaloan())<span class="label label-warning">Permaloan</span>@endif

                        </div>
                        <div class="col-md-12 col-lg-6">

                        </div>
                    </div>

                    <br />

                    {!! $device->present()->description() !!}
                    <br />

                    @if ($device->helpText())
                        <a data-toggle="modal" data-target="#helpModal" href="#" class="btn btn-info">Help</a>
                        <br /><br />
                    @endif

                    {!! $device->present()->ppe() !!}


                    @if ($device->cost()->requiresInduction())

                        @if (!$userInduction)

                            <p>
                            To use this piece of equipment an access fee and an induction is required. The access fee goes towards equipment maintenance.<br />
                            <strong>Equipment access fee: &pound{{ $device->cost()->accessFee() }}</strong><br />
                            </p>

                            <div class="paymentModule" data-reason="induction" data-display-reason="Equipment Access Fee" data-button-label="Pay Now" data-methods="gocardless,stripe,balance" data-amount="{{ $device->cost()->accessFee() }}" data-ref="{{ $device->cost()->inductionCategory() }}"></div>

                        @elseif ($userInduction->is_trained)

                            <span class="label label-success">You have been inducted and can use this equipment</span>

                        @elseif ($userInduction)

                            <span class="label label-info">Access fee paid, induction to be completed</span>

                        @endif

                    @endif

                </div>
            </div>


            <div class="col-sm-12 col-lg-6">

                @if ($device->hasPhoto())
                    @foreach($device->photos() as $photo)
                        <img src="{{ $photo->photoUrl() }}" width="170" data-toggle="modal" data-target="#image{{ $photo->id() }}" style="margin:3px 1px; padding:0;" />

                        <div class="modal fade" id="image{{ $photo->id() }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <img src="{{ $photo->photoUrl() }}" width="100%" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>

            <div class="col-sm-12 col-lg-6">

                @if ($device->cost()->requiresInduction())
                    <div class="row">
                    <h4>Trainers</h4>
                    <div class="list-group">
                        @foreach($trainers as $trainer)
                            <a href="{{ route('members.show', $trainer->user->id) }}" class="list-group-item">
                                {!! HTML::memberPhoto($trainer->user->profile, $trainer->user->hash, 25, '') !!}
                                {{ $trainer->user->name }}
                            </a>
                        @endforeach
                    </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    @if ($device->hasActivity())
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
        <tfoot>
            <tr>
                <td colspan="5">
                    <strong>Total times in minutes:</strong>
                    Billed: {{ number_format($usageTimes['billed']) }} |
                    Unbilled: {{ number_format($usageTimes['unbilled']) }} |
                    Training: {{ number_format($usageTimes['training']) }} |
                    Testing: {{ number_format($usageTimes['testing']) }}
                </td>
            </tr>
        </tfoot>
        <tbody>
        @foreach($equipmentLog as $log)
            <tr>
                <td>{{ $log->present()->started }}</td>
                <td>{{ $log->present()->timeUsed }}</td>
                <td><a href="{{ route('members.show', $log->user->id) }}">{{ $log->user->name }}</a></td>
                <td>{{ $log->present()->reason }}</td>
                @if (Auth::user()->isAdmin() || Auth::user()->hasRole($equipmentId))
                <td>
                    @if (empty($log->reason))
                    {!! Form::open(['method'=>'POST', 'route'=>['equipment_log.update', $log->id], 'name'=>'equipmentLog']) !!}
                    {!! Form::select('reason', ['testing'=>'Testing', 'training'=>'Training'], $log->reason, ['class'=>'']) !!}
                    {!! Form::submit('Update', ['class'=>'btn btn-primary btn-xs']) !!}
                    {!! Form::close() !!}
                    @endif
                </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
        <?php echo $equipmentLog->render(); ?>
    </div>
    @endif


    @if ($device->cost()->requiresInduction())
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h4>Trained Users</h4>
                <ul>
                    @foreach($trainedUsers as $trainedUser)
                        <li>
                            <a href="{{ route('members.show', $trainedUser->user->id) }}">
                                {{ $trainedUser->user->name }}
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
                                {{ $trainedUser->user->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    <div class="modal fade" id="helpModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Help</h4>
                </div>
                <div class="modal-body">
                    {!! $device->helpText() !!}
                </div>
            </div>
        </div>
    </div>

</div>

@stop