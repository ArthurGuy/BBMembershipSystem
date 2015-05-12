
@if (Notification::hasMessage())
<div class="snackBar snackBar-{{ Notification::getLevel() }}">
    {{ Notification::getMessage() }}
    @if (Notification::hasDetails())
    <ul>
        @foreach(Notification::getDetails()->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
</div>
@endif


<div id="js-message-holder"></div>
