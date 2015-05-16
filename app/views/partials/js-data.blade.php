@if (!Auth::guest())
    <script>
        var paymentRoutes = {
            stripe: '{{ route('account.payment.stripe.store', Auth::user()->id) }}',
            gocardless: '{{ route('account.payment.gocardless.create', Auth::user()->id) }}',
            balance: '{{ route('account.payment.balance.create', Auth::user()->id) }}'
        };
        var stripePublicKey = '@stripeKey()';
        var memberEmail = '{{ Auth::user()->email }}';
        var userId = '{{ Auth::user()->id }}';
    </script>
    <input type="hidden" id="stripePublicKey" value="@stripeKey()" />
    <input type="hidden" id="memberEmail" value="{{ Auth::user()->email }}" />
@else
    <script>
        var stripePublicKey = '';
        var memberEmail = '';
        var userId = '';
    </script>
@endif

<input type="hidden" id="csrfToken" value="<?php echo csrf_token(); ?>">