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
        <div class="well">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    @if ($equipment->requires_training)
                        This piece of equipment requires that an induction fee is paid, this goes towards maintaining the equipment.<br />
                        Induction fee: &pound{{ $equipment->cost }}<br />
                    @else
                        No induction required
                    @endif
                    @if (!$equipment->working)
                        <span class="label label-danger">Out of action</span>
                    @endif
                </div>

                @if ($equipment->requires_training)
                    <div class="col-sm-12 col-md-6">
                        <h4>Trainers</h4>
                        <div class="list-group">
                            @foreach($trainers as $trainer)
                                <a href="{{ route('members.show', $trainer->user->id) }}" class="list-group-item">
                                    {{ HTML::memberPhoto($trainer->user->profile, $trainer->user->hash, 50, '') }}
                                    {{ $trainer->user->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Used for</th>
                <th>Member</th>
            </tr>
        </thead>
        <tbody>
        @foreach($equipmentLog as $log)
            <tr>
                <td>{{ $log->present()->started }}</td>
                <td>{{ $log->present()->timeUsed }}</td>
                <td><a href="{{ route('members.show', $log->user->id) }}">{{ $log->user->name }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
        <?php echo $equipmentLog->links(); ?>
    </div>
</div>

@stop

@section('footer-js')

@stop