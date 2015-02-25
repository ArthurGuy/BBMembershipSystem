@extends('layouts.main')

@section('meta-title')
Proposals
@stop
@section('page-title')
Proposals
@stop

@section('page-action-buttons')
    @if (!Auth::guest() && Auth::user()->isAdmin())
    <a class="btn btn-secondary" href="{{ route('proposals.create') }}">Create a proposal</a>
    @endif
@stop

@section('content')

<div class="page-header">
    <h4>Have your say in what happens at Build Brighton</h4>
</div>


<table class="table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>End Date</th>
            <th>Outcome</th>
        </tr>
    </thead>
@foreach ($proposals as $proposal)
    <tbody>
        <tr>
            <td>{{ link_to_route('proposals.show', $proposal->title, $proposal->id) }}</td>
            <td>{{ $proposal->present()->status }}</td>
            <td>{{ $proposal->present()->end_date }}</td>
            <td>{{ $proposal->present()->outcome }}</td>
        </tr>
    </tbody>
@endforeach
</table>

<div class="well well-sm">
Proposals will be carried forward if there are more people in favour of a proposal than against.
If a proposal doesn't pass it may be reopened for a second round of voting providing there is a valid reason.<br />
Non voters will be counted as having placed a 0 or neutral vote.
</div>
@stop