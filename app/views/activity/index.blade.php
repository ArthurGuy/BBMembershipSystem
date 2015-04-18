@extends('layouts.main')

@section('meta-title')
Activity Log
@stop

@section('page-title')
Activity Log
@stop

@section('content')

<div class="page-header">
    <h3>Door access to the main space - {{ $date->format('l jS \\of F'); }}</h3>
    Want to know if anyone's there now? Call the number at the space : 01273 603516

    {{ Form::open(['route'=> 'activity.index', 'method'=>'GET', 'id'=>'activityDatePicker', 'class'=>'form-inline']) }}
    <div class="input-group date">
        <input name="date" type="text" class="date-input form-control" value="{{ $date->format('Y-m-d') }}">
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>

    {{ Form::close() }}

    <ul class="pager">
    @if ($previousDate)
        <li class="previous">{{ link_to_route('activity.index', $previousDate->format('d/m/y'). ' &larr; Previous', ['date'=>$previousDate->format('Y-m-d')]) }}</li>
    @else
        <li class="previous disabled"><a href="#">Previous</a></li>
    @endif
    @if ($nextDate)
        <li class="next">{{ link_to_route('activity.index', 'Next &rarr; '.$nextDate->format('d/m/y'), ['date'=>$nextDate->format('Y-m-d')]) }}</li>
    @else
        <li class="next disabled"><a href="#">Next</a></li>
    @endif
    </ul>
</div>

<div class="memberActivityGrid">
    <div class="row">
        @foreach ($logEntries as $logEntry)
        <div class="col-sm-6 col-md-4 col-lg-2">
            <div class="activityBlock">
                @if ($logEntry->user->profile->profile_photo)
                    <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($logEntry->user->hash) }}" width="200" height="200" class="profilePhoto" />
                @else
                    <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="200" height="200" class="profilePhoto" />
                @endif
                <div class="activityDetails">
                    <strong><a href="{{ route('members.show', $logEntry->user->id) }}">{{{ $logEntry->user->name }}}</a></strong>
                    <!--
                    <span class="memberFlags">
                        @if ($logEntry->user->keyholderStatus())
                        <span class="glyphicon glyphicon-lock" data-toggle="tooltip" data-placement="top" title="Key Holder"></span>
                        @endif
                    </span>
                    -->
                    <br />
                    @if ($logEntry->delayed)
                        <span data-toggle="tooltip" data-placement="below" title="This record doesn't have an accurate time">(delayed)</span>
                    @else
                        {{ $logEntry->created_at->toTimeString() }}
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@stop

@section('footer-js')
    <script>
    $('#activityDatePicker .date').datepicker({
        format: "yyyy-mm-dd",
        endDate: "{{ date('Y-m-d') }}}",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
    })
    .on('hide', function(e){
        $('#activityDatePicker').submit();
    });
    </script>
@stop
