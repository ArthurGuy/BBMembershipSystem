@extends('layouts.main')

@section('meta-title')
Build Brighton Members
@stop

@section('page-title')
Members
@stop


@section('content')

<div class="memberGrid">
    <div class="row">
        @foreach ($users as $user)
        <div class="col-sm-6 col-md-4 col-lg-2">
            <div class="memberBlock">
                <a href="{{ route('members.show', $user->id) }}">
                    {!! HTML::memberPhoto($user->profile, $user->hash, 200) !!}
                    <div class="memberDetails">
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <span class="memberFlags">
                    @if ($user->keyholderStatus())
                        <i class="material-icons" data-toggle="tooltip" data-placement="top" title="Key Holder">vpn_key</i>
                    @endif
                    @if ($user->director)
                        <i class="material-icons" data-toggle="tooltip" data-placement="top" title="Trustee">star</i>
                    @endif
                    </span>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

@stop