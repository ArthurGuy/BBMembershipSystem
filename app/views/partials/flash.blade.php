@if (Notification::hasMessage())
<div class="top-alerts">
    <div class="alert alert-{{ Notification::getLevel() }} alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ Notification::getMessage() }}
        @if (Notification::hasDetails())
        <ul>
            @foreach(Notification::getDetails()->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </div>
</div>
@endif