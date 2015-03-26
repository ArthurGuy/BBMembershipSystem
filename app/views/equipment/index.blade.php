@extends('layouts.main')

@section('page-title')
Tools &amp; Equipment
@stop

@section('meta-title')
Tools and Equipment
@stop

@section('main-tab-bar')

@stop


@section('content')

<nav id="">
    <ul class="equipmentTabs nav nav-pills" role="tablist">
        @foreach($equipment as $toolId => $tool)
            <li class=""><a href="#{{ $toolId }}" data-toggle="tab" role="tab">{{ $tool->name }}</a></li>
        @endforeach
    </ul>
</nav>

<div class="tab-content">
@foreach($equipment as $toolId => $tool)
    <div class="tab-pane fade" id="{{ $toolId }}">

        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="well">

                    @if ($tool->requires_training)
                        <p>Requires training</p>
                    @endif
                    @if ($tool->cost > 0)
                        <p>
                            Equipment access fee: &pound{{ $tool->cost }}<br />
                            The fee goes towards maintenance and repair.<br />
                        </p>
                    @else
                        <p>No fee required</p>
                    @endif

                    @if (!$tool->working)
                        <p><span class="label label-danger">Out of action</span></p>
                    @endif

                    <a href="{{ route('equipment.show', $toolId) }}" class="btn btn-info">Activity Log</a>

                </div>
            </div>

            @if ($tool->requires_training)
                <div class="col-sm-12 col-md-6">
                    <h4>Trainers</h4>
                    @if(isset($trainers[$toolId]))
                    <div class="list-group">
                        @foreach($trainers[$toolId] as $trainer)
                            <a href="{{ route('members.show', $trainer->id) }}" class="list-group-item">
                                {{ HTML::memberPhoto($trainer->profile, $trainer->hash, 25, '') }}
                                {{{ $trainer->name }}}
                            </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endif
        </div>


        <div class="row">
            @if ($tool->requires_training)
                @if(isset($usersPendingInduction[$toolId]))
                    <div class="col-sm-12 col-md-6">
                        <h4>Members waiting for an Induction</h4>
                        <div class="list-group">
                        @foreach($usersPendingInduction[$toolId] as $user)
                            <a href="{{ route('members.show', $user->id) }}" class="list-group-item">
                                {{ HTML::memberPhoto($user->profile, $user->hash, 25, '') }}
                                {{{ $user->name }}}
                            </a>
                        @endforeach
                        </div>
                    </div>
                @endif
                @if(isset($trainedUsers[$toolId]))
                    <div class="col-sm-12 col-md-6">
                        <h4>Trained Users</h4>
                        <div class="list-group">
                        @foreach($trainedUsers[$toolId] as $user)
                            <a href="{{ route('members.show', $user->id) }}" class="list-group-item">
                            {{ HTML::memberPhoto($user->profile, $user->hash, 25, '') }}
                            {{{ $user->name }}}
                            </a>
                        @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endforeach
</div>

@stop

@section('footer-js')
<script>
$('.equipmentTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
});
$('.equipmentTabs a:first').tab('show')
</script>
@stop