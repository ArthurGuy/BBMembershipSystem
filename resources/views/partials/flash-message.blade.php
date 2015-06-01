
<div id="js-message-holder"></div>

@if (Notification::hasMessage())
    <input type="hidden" id="snackbarMessage" value="{{ Notification::getMessage() }}" />
    <input type="hidden" id="snackbarLevel" value="{{ Notification::getLevel() }}" />
    @if (Notification::hasDetails())
        <input type="hidden" id="snackbarMessages" value="{!! htmlentities(json_encode(Notification::getDetails()->all())) !!}" />
    @endif
@endif