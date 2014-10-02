<?php 
$I = new ApiTester($scenario);
$I->am('gocardless');
$I->wantTo('confirm a gocardless webhook is received');

$payloadData = '{
  "payload": {
    "bills": [
      {
        "id": "0QG9YGF008",
        "status": "pending",
        "uri": "https://sandbox.gocardless.com/api/v1/bills/0QG9YGF008",
        "amount": "20.0",
        "amount_minus_fees": "19.8",
        "source_type": "subscription",
        "source_id": "0Q0XG97SBN"
      }
    ],
    "resource_type": "bill",
    "action": "created",
    "signature": "8c50eac00ebd59465f6d634925b7c1291d56a55cb125a5076af7d9e8ac28a5cf"
  }
}';
$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST('/gocardless/webhook', $payloadData);
$I->seeResponseCodeIs(200);