@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
        <div class="row page-header">
            <div class="col-xs-12 col-sm-12">
                <h1>{{ $user->name }}</h1>
                <h3 data-type="text" data-name="tagline" class="">Maker, developer, creator</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-4">
                @if ($user->profile_photo)
                <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" width="250" height="250" />
                @else
                <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="250" height="250" class="img-circle" />
                @endif
            </div>
            <div class="col-xs-12 col-sm-6 col-md-8 pull-right">
                <p class="lead" data-type="textarea" data-name="description">
                    I am a php and javascript programmer by day and an electronics engineer by night.<br />

                </p>
                <ul>
                    <li>GitHub - <a>github.com/ArthurGuy</a></li>
                    <li>Twitter - twitter.com/ArthurGuy</li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h3>Skills</h3>
                <div class="skill-list">
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <img src="/img/skills/coding.png" width="100" height="100" />
                            <div class="caption">
                                <h3>Coding</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <img src="/img/skills/3dprinting.png" width="100" height="100" />
                            <div class="caption">
                                <h3>3D Printing</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <img src="/img/skills/laser-cutter.png" width="100" height="100" />
                            <div class="caption">
                                <h3>Laser Cutter</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <img src="/img/skills/craft.png" width="100" height="100" />
                            <div class="caption">
                                <h3>Arts and Crafts</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <img src="/img/skills/pcb-design.png" width="100" height="100" />
                            <div class="caption">
                                <h3>PCB Design & Manufacture</h3>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@stop

@section('footer-js')
<!--
<script>
//$.fn.editable.defaults.mode = 'inline';
$.fn.editable.defaults.pk = '{{ $user->id }}';
$.fn.editable.defaults.url = '{{ route('account.profile', $user->id) }}';
$(document).ready(function() {
    $('.js-inline-edit').editable();
});
</script>
-->
@stop