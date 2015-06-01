<input type="hidden" id="stripePublicKey" value="{{ $stripeKey }}" />
@if (!Auth::guest())
    <input type="hidden" id="memberEmail" value="{{ Auth::user()->email }}" />
    <input type="hidden" id="userId" value="{{ Auth::user()->id }}" />
@endif

<input type="hidden" id="csrfToken" value="<?php echo csrf_token(); ?>">