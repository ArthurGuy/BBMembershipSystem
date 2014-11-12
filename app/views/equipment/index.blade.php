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
            <div class="col-md-12">
                <div class="well">
                    @if ($tool->requires_training)
                        Induction fee: &pound{{ $tool->cost }}<br />
                    @else
                        No induction required
                    @endif
                    @if (!$tool->working)
                        <span class="label label-danger">Out of action</span>
                    @endif
                </div>
            </div>
            @if ($tool->requires_training)
                @if(isset($trainers[$toolId]))
                    <div class="col-sm-12 col-md-6">
                        <h4>Trainers</h4>
                        <div class="list-group">
                            @foreach($trainers[$toolId] as $trainer)
                                <a href="{{ route('members.show', $trainer->id) }}" class="list-group-item">
                                    {{ HTML::memberPhoto($trainer->profile, $trainer->hash, 50, '') }}
                                    {{ $trainer->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(isset($usersPendingInduction[$toolId]))
                    <div class="col-sm-12 col-md-6">
                        <h4>Members waiting for an Induction</h4>
                        <div class="list-group">
                        @foreach($usersPendingInduction[$toolId] as $user)
                            <a href="{{ route('members.show', $user->id) }}" class="list-group-item">
                                {{ HTML::memberPhoto($user->profile, $user->hash, 25, '') }}
                                {{ $user->name }}
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
                            {{ $user->name }}
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