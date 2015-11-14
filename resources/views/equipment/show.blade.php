@extends('layouts.main')

@section('page-title')
<a href="{{ route('equipment.index') }}">Tools &amp; Equipment</a> > {{ $equipment->name }}
@stop

@section('meta-title')
Tools and Equipment
@stop

@section('page-action-buttons')
    @if (!Auth::guest() && Auth::user()->hasRole('equipment'))
        <a class="btn btn-secondary" href="{{ route('equipment.edit', $equipment->slug) }}">Edit</a>
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

                            @if ($equipment->present()->manufacturerModel) Make: {{ $equipment->present()->manufacturerModel }}<br />@endif
                            <!-- @if ($equipment->colour) Colour: {{ $equipment->colour }}<br />@endif -->
                            @if ($equipment->present()->livesIn) Lives in: {{ $equipment->present()->livesIn }}<br />@endif
                            @if ($equipment->present()->purchaseDate) Purchased: {{ $equipment->present()->purchaseDate }}<br />@endif
                            @if ($equipment->requiresInduction()) Induction required<br /> @endif
                            @if ($equipment->hasUsageCharge())
                            Usage Cost: {!! $equipment->present()->usageCost() !!}<br />
                            @endif
                            @if ($equipment->isManagedByGroup())
                                Managed By: <a href="{{ route('groups.show', $equipment->role->name) }}">{{ $equipment->role->title }}</a>
                            @endif

                            @if (!$equipment->isWorking())<span class="label label-danger">Out of action</span>@endif
                            @if ($equipment->isPermaloan())<span class="label label-warning">Permaloan</span>@endif

                        </div>
                        <div class="col-md-12 col-lg-6">

                        </div>
                    </div>

                    <br />

                    {!! $equipment->present()->description !!}
                    <br />

                    @if ($equipment->help_text)
                        <a data-toggle="modal" data-target="#helpModal" href="#" class="btn btn-info">Help</a>
                        <br /><br />
                    @endif

                    {!! $equipment->present()->ppe !!}


                    @if ($equipment->requiresInduction())

                        @if (!$userInduction)

                            <p>
                            To use this piece of equipment an access fee and an induction is required. The access fee goes towards equipment maintenance.<br />
                            <strong>Equipment access fee: &pound{{ $equipment->access_fee }}</strong><br />
                            </p>

                            <div class="paymentModule" data-reason="induction" data-display-reason="Equipment Access Fee" data-button-label="Pay Now" data-methods="gocardless,stripe,balance" data-amount="{{ $equipment->access_fee }}" data-ref="{{ $equipment->induction_category }}"></div>

                        @elseif ($userInduction->is_trained)

                            @if ($equipment->slug == 'laser')
                            <p>
                                While the laser access control system is unavailable you can make a payment for your usage of the equipment below.
                            </p>

                            <div class="paymentModule" data-reason="equipment-fee" data-display-reason="Usage Fee" data-button-label="Pay Now" data-methods="balance" data-ref="laser"></div>
                            @endif

                            <span class="label label-success">You have been inducted and can use this equipment</span>

                        @elseif ($userInduction)

                            <span class="label label-info">Access fee paid, induction to be completed</span>

                        @endif

                    @endif

                </div>
            </div>


            <div class="col-sm-12 col-lg-6">

                @if ($equipment->hasPhoto())
                    @for($i=0; $i < $equipment->getNumPhotos(); $i++)
                        <img src="{{ $equipment->getPhotoUrl($i) }}" width="170" data-toggle="modal" data-target="#image{{ $i }}" style="margin:3px 1px; padding:0;" />

                        <div class="modal fade" id="image{{ $i }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <img src="{{ $equipment->getPhotoUrl($i) }}" width="100%" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif

            </div>

            <div class="col-sm-12 col-lg-6">

                @if ($equipment->requiresInduction())
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


    @if ($equipment->requiresInduction())
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
                    {!! $equipment->present()->help_text !!}
                </div>
            </div>
        </div>
    </div>

</div>

@stop