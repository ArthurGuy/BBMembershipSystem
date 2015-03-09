@extends('layouts.main')

@section('meta-title')
Resources
@stop

@section('page-title')
Resources
@stop

@section('content')



    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">General mailing lists</h3></div>
            <div class="panel-body">
                <p>
                    There are two main google groups, one public for general discussion of the space that anyone might find interesting, and one private for members only. <br />If you're using the space at all you should be signed up to the members list so you hear about announcements or other issues.<br />
                </p>
                <ul class="list-unstyled">
                    <li><a href="https://groups.google.com/d/forum/brightonhackerspace">Public mailing list</a></li>
                    <li><a href="https://groups.google.com/d/forum/brighton-hackspace-members">Members mailing list</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Special interest groups</h3></div>
            <div class="panel-body">
                <ul>
                    <li><a href="https://groups.google.com/d/forum/bb-audio">Audio group</a></li>
                    <li><a href="https://groups.google.com/d/forum/bb-machinists">Machinists</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Rules and Policies</h3></div>
            <div class="panel-body">
                <p>We have started to improve some of the various polices that govern Build Brighton, as they are clarified and confirmed they will appear here.</p>

                <strong>Core Rules</strong>
                <ul>
                    <li><a href="{{ route('resources.policy.view', 'rules') }}">Rules</a></li>
                    <li><a href="{{ route('resources.policy.view', 'code-of-conduct') }}">Code of Conduct</a></li>
                    <li><a href="{{ route('resources.policy.view', 'grievance-procedure') }}">Grievance Procedure</a></li>
                    <li><a href="{{ route('resources.policy.view', 'trusted-member') }}">Trusted Member</a></li>
                </ul>

                <strong>Policies</strong>
                <ul>
                    <li><a href="{{ route('resources.policy.view', '3-week-bins') }}">3 Week Bins</a></li>
                </ul>
            </div>
        </div>
    </div>




@stop
