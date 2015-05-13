@if (!Auth::guest())
    <script>
        var paymentRoutes = {
            stripe: '{{ route('account.payment.stripe.store', Auth::user()->id) }}',
            gocardless: '{{ route('account.payment.gocardless.create', Auth::user()->id) }}',
            balance: '{{ route('account.payment.balance.create', Auth::user()->id) }}'
        };
        var stripePublicKey = '@stripeKey()';
        var memberEmail = '{{ Auth::user()->email }}';
    </script>
@else
    <script>
        var stripePublicKey = '';
        var memberEmail = '';
    </script>
@endif