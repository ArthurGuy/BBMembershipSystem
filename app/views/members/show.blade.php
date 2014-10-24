@extends('layouts.main')

@section('meta-title')
{{ $user->name }}
@stop

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
        <div class="row page-header">
            <div class="col-xs-12 col-sm-12 col-lg-10">
                <h1>{{ $user->name }}</h1>
                <h3>{{ $profileData->present()->tagline }}</h3>
            </div>
            <div class="col-lg-2">
            @if (!@Auth::guest() && $user->id == Auth::user()->id)
                <a href="{{ route('account.profile.edit', $user->id) }}" class="btn btn-info btn-sm">Edit Your Profile</a>
            @endif
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4">
                @if ($profileData->profile_photo)
                    <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" width="250" height="250" class="img-circle" />
                @else
                    <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="250" height="250" class="img-circle" />
                @endif
            </div>
            <div class="col-xs-12 col-sm-6 col-md-8 pull-right">
                <p class="lead">
                    {{ $profileData->present()->description }}
                </p>
                <ul>
                    {{ HTML::profileSocialMediaListItem('GitHub', $profileData->present()->gitHubLink) }}
                    {{ HTML::profileSocialMediaListItem('Twitter', $profileData->present()->twitterLink) }}
                    {{ HTML::profileSocialMediaListItem('Google+', $profileData->present()->googlePlusLink) }}
                    {{ HTML::profileSocialMediaListItem('Facebook', $profileData->present()->facebookLink) }}
                    {{ HTML::profileSocialMediaListItem('Website', $profileData->present()->website) }}
                    @if ($profileData->irc)
                    <li>IRC - <a href="irc://irc.freenode.net/buildbrighton">irc://irc.freenode.net/buildbrighton</a> - {{ $profileData->irc }}</li>
                    @endif
                </ul>
            </div>
        </div>

        @if (count($userSkills) > 0)
            <div class="row">
                <div class="col-xs-12">
                    <h3>Skills</h3>
                    <div class="skill-list">
                        @foreach($userSkills as $skill)
                            <div class="col-sm-6 col-md-3">
                                <div class="thumbnail">
                                    <img src="/img/skills/{{  $skill['icon'] }}" width="100" height="100" />
                                    <div class="caption">
                                        <h3>{{  $skill['name'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        @endif
    </div>

</div>
@stop

@section('footer-js')
<!--
<script>
//$.fn.editable.defaults.mode = 'inline';
$.fn.editable.defaults.pk = '{{ $user->id }}';
$.fn.editable.defaults.url = '{{ route('account.profile.update', $user->id) }}';
$(document).ready(function() {
    $('.js-inline-edit').editable();
});
</script>
-->
@stop