<div class="row">
    <div class="col-xs-12 col-md-10">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>Want to change your subscription amount?</h4>
                <ul>
                    <li>Click the button below and fill in Direct Debit form</li>
                    <li>Return to the BBMS</li>
                    <li>Use the "change" link which will be next to your subscription amount (top right)</li>
                </ul>
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.gocardless-migrate'])) }}
                {{ Form::submit('Setup a variable Direct Debit', array('class'=>'btn btn-primary')) }}
                {{ Form::close() }}
                <p>
                    <br />
                    <strong>What does this do?</strong>
                    This will move you over to the new direct debit process where you authorise
                    Build Brighton once and it applies to all the other payments.<br />
                    You can still cancel at any point and the same protections as before will apply.
                </p>
            </div>
        </div>
    </div>
</div>