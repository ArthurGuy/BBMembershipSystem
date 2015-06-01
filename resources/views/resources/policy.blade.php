@extends('layouts.main')

@section('meta-title')
Resources > Policy
@stop

@section('page-title')
Resources > Policy
@stop

@section('content')

    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                {!! $document !!}
            </div>
        </div>
    </div>


@stop
