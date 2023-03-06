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
            <div class="panel-heading"><h3 class="panel-title">Our Online Community</h3></div>
            <div class="panel-body">
                  <b>1. Join Discord say hello and Arrange an Induction</b><br /><br />
    The first thing is to Discord - a collaborative network for group and individual messaging.<br /><br />
    For an invite to <a href="https://discord.gg">Discord</a> please install the client on desktop or mobile, and click <a href="https://discord.gg/tzgfE2Q5qh">this link</a><br /><br />
    Once you’re signed in, please update your nickname to your 'real name' by right clicking yourself in sidebar and selecting "edit server profile". You can join any other channels that take your interest such as 3D printing, electronics and photography.
                <p>If you're using the space at all you are required to be signed up to the discord so you hear about announcements or other issues.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
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
                    <li><a href="{{ route('resources.policy.view', 'permaloan') }}">Permaloan</a></li>
                </ul>

                <strong>Policies</strong>
                <ul>
                    <li><a href="{{ route('resources.policy.view', '3-week-bins') }}">3 Week Bins</a></li>
                    <li><a href="{{ route('resources.policy.view', 'member-storage') }}">Member Storage</a></li>
                </ul>
            </div>
        </div>
    </div>




@stop
