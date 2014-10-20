@extends('layouts.main')

@section('title')
Proposals
@stop

@section('content')

<div class="page-header">
    <h1>Proposals</h1>
    <h4>Have your say in what happens at Build Brighton</h4>
</div>


<table class="table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>End Date</th>
            <th>Outcome</th>
            <th>Proposal Created</th>
        </tr>
    </thead>
@foreach ($proposals as $proposal)
    <tbody>
        <tr>
            <td>{{ link_to_route('proposals.show', $proposal->title, $proposal->id) }}</td>
            <td>{{ $proposal->present()->status }}</td>
            <td>{{ $proposal->present()->end_date }}</td>
            <td>{{ $proposal->present()->outcome }}</td>
            <td>{{ $proposal->present()->created_at }}</td>
        </tr>
    </tbody>
@endforeach
</table>

<div class="well well-sm">
Proposals will be carried forward if there are more people in favour of a proposal than against.
If a proposal doesn't pass it may be reopened for a second round of voting
</div>
@stop