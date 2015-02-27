<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Your Payment Method</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <p class="lead">
                    Will you switch to a Direct Debit payment?
                </p>
                <div>
                    Processing the standing order bank transfers each month takes time and introduces delays.<br />
                    A monthly Direct Debit payment is quick and fully automated and if you need you have the ability to cancel the payment from here or from your bank.<br />
                    Switching only takes a minute, just follow the link below to the <a href="https://gocardless.com/security" target="_blank">GoCardless</a> website (our payment processor) and complete the form.<br />
                    <br />
                    <a href="{{ route('account.subscription.create', $user->id) }}" class="btn btn-primary">Setup a Direct Debit for &pound;{{ round($user->monthly_subscription) }}</a>
                    <small><a href="#" class="js-show-alter-subscription-amount">Change your monthly direct debit amount</a></small>
                    {{ Form::open(array('method'=>'POST', 'class'=>'form-inline hidden js-alter-subscription-amount-form', 'style'=>'display:inline-block', 'route' => ['account.update-sub-payment', $user->id])) }}
                    <div class="input-group">
                        <div class="input-group-addon">&pound;</div>
                        {{ Form::text('monthly_subscription', round($user->monthly_subscription), ['class'=>'form-control']) }}
                    </div>
                    {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
                    {{ Form::close() }}
                    <br />
                    <p>
                        Don't forget to cancel your current subscription payment.<br />
                        <small>By switching you will also protected by the <a href="https://gocardless.com/direct-debit/guarantee/">direct debit guarantee.</a></small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

