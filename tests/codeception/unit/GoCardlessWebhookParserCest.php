<?php

class GoCardlessWebhookParserCest
{

    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function parsePaymentPaid(UnitTester $I)
    {
        $parser = new \BB\Services\Payment\GoCardlessWebhookParser();
        $parser->parseResponse($this->paymentPaidPayload());

        $I->assertEquals('paid', $parser->getAction());
        $I->assertEquals('bill', $parser->getResourceType());
        $I->assertEquals(1, count($parser->getBills()));
        $I->assertEquals(0, count($parser->getSubscriptions()));

        $bills = $parser->getBills();
        $this->assertBillFormat($I, $bills);
    }

    public function parsePaymentPaidNoSource(UnitTester $I)
    {
        $parser = new \BB\Services\Payment\GoCardlessWebhookParser();
        $parser->parseResponse($this->paymentPaidNoSourcePayload());

        $I->assertEquals('paid', $parser->getAction());
        $I->assertEquals('bill', $parser->getResourceType());
        $I->assertEquals(1, count($parser->getBills()));

        $bills = $parser->getBills();
        $this->assertBillFormat($I, $bills);
    }

    public function parsePaymentCreated(UnitTester $I)
    {
        $parser = new \BB\Services\Payment\GoCardlessWebhookParser();
        $parser->parseResponse($this->paymentCreatedPayload());

        $I->assertEquals('created', $parser->getAction());
        $I->assertEquals('bill', $parser->getResourceType());
        $I->assertEquals(1, count($parser->getBills()));

        $bills = $parser->getBills();
        $this->assertBillFormat($I, $bills);
    }

    public function parsePaymentRetried(UnitTester $I)
    {
        $parser = new \BB\Services\Payment\GoCardlessWebhookParser();
        $parser->parseResponse($this->paymentRetriedPayload());

        $I->assertEquals('retried', $parser->getAction());
        $I->assertEquals('bill', $parser->getResourceType());
        $I->assertEquals(1, count($parser->getBills()));

        $bills = $parser->getBills();
        $this->assertBillFormat($I, $bills);
    }

    public function parsePaymentWithdrawn(UnitTester $I)
    {
        $parser = new \BB\Services\Payment\GoCardlessWebhookParser();
        $parser->parseResponse($this->paymentWithdrawnPayload());

        $I->assertEquals('withdrawn', $parser->getAction());
        $I->assertEquals('bill', $parser->getResourceType());
        $I->assertEquals(3, count($parser->getBills()));

        $bills = $parser->getBills();
        $this->assertBillFormat($I, $bills);
    }

    public function parsePaymentCancelled(UnitTester $I)
    {
        $parser = new \BB\Services\Payment\GoCardlessWebhookParser();
        $parser->parseResponse($this->paymentCancelledPayload());

        $I->assertEquals('cancelled', $parser->getAction());
        $I->assertEquals('bill', $parser->getResourceType());
        $I->assertEquals(1, count($parser->getBills()));

        $bills = $parser->getBills();
        $this->assertBillFormat($I, $bills);
    }

    public function parseSubscriptionCancelled(UnitTester $I)
    {
        $parser = new \BB\Services\Payment\GoCardlessWebhookParser();
        $parser->parseResponse($this->subscriptionCancelledPayload());

        $I->assertEquals('cancelled', $parser->getAction());
        $I->assertEquals('subscription', $parser->getResourceType());
        $I->assertEquals(1, count($parser->getSubscriptions()));
        $I->assertEquals(0, count($parser->getBills()));

        $this->assertSubscriptionFormat($I, $parser->getSubscriptions());
    }


    private function assertBillFormat(UnitTester $I, $bills)
    {
        foreach ($bills as $bill) {
            $I->assertTrue(isset($bill['id']), "Bill ID");
            $I->assertTrue(isset($bill['status']), "Bill status");
            $I->assertTrue(isset($bill['uri']), "Bill URI");
            $I->assertTrue(isset($bill['amount']), "Bill amount");
            $I->assertTrue(isset($bill['amount_minus_fees']), "Bill amount minus fee");
            $I->assertTrue(isset($bill['source_type']), "Bill source type");
            $I->assertTrue(isset($bill['source_id']), "Bill source ID");
            $I->assertTrue(isset($bill['paid_at']), "Bill date - paid at");
        }
    }


    private function assertSubscriptionFormat(UnitTester $I, $subscriptions)
    {
        foreach ($subscriptions as $subscription) {
            $I->assertTrue(isset($subscription['id']), "Bill ID");
            $I->assertTrue(isset($subscription['status']), "Bill status");
            $I->assertTrue(isset($subscription['uri']), "Bill URI");
        }
    }



    private function paymentPaidPayload()
    {
        return '{
              "payload": {
                "bills": [
                  {
                    "id": "ABCDEF1234",
                    "status": "paid",
                    "uri": "https://gocardless.com/api/v1/bills/ABCDEF1234",
                    "amount": "5.0",
                    "amount_minus_fees": "4.95",
                    "source_type": "subscription",
                    "source_id": "0VEJM4EW74",
                    "paid_at": "2015-04-07T11:15:33Z"
                  }
                ],
                "resource_type": "bill",
                "action": "paid",
                "signature": "41b5984494a1b896a138ff15fb59d6a0e7aa08b21d897d329956343a68aab50d"
              }
            }';
    }

    private function paymentPaidNoSourcePayload()
    {
        return '{
              "payload": {
                "bills": [
                  {
                    "id": "ABCDEF1234",
                    "status": "paid",
                    "uri": "https://gocardless.com/api/v1/bills/ABCDEF1234",
                    "amount": "5.0",
                    "amount_minus_fees": "4.95",
                    "paid_at": "2015-04-10T11:14:38Z"
                  }
                ],
                "resource_type": "bill",
                "action": "paid",
                "signature": "62fa8f36f649c883aeaa3552eecd0957c81000a8cc65ac8d56fbee52fef8e886"
              }
            }';
    }


    private function paymentCreatedPayload()
    {
        return '{
              "payload": {
                "bills": [
                  {
                    "id": "ABCDEF1234",
                    "status": "pending",
                    "uri": "https://gocardless.com/api/v1/bills/ABCDEF1234",
                    "amount": "30.0",
                    "amount_minus_fees": "29.7",
                    "source_type": "subscription",
                    "source_id": "0VEJM4EW74"
                  }
                ],
                "resource_type": "bill",
                "action": "created",
                "signature": "f11f70d02b0328190c2518bb98881d2e203a8d563ff82567d22b01b05d1a088b"
              }
            }';
    }


    private function paymentRetriedPayload()
    {
        return '{
              "payload": {
                "bills": [
                  {
                    "id": "ABCDEF1234",
                    "status": "pending",
                    "uri": "https://gocardless.com/api/v1/bills/ABCDEF1234",
                    "amount": "25.0",
                    "amount_minus_fees": "24.75",
                    "source_type": "subscription",
                    "source_id": "1234ABCDEF"
                  }
                ],
                "resource_type": "bill",
                "action": "retried",
                "signature": "7f31f91966fe6180b64ae8a604227c0bfedc6d7b4e85129fc00a71fde68ad7c3"
              }
            }';
    }


    private function paymentWithdrawnPayload()
    {
        return '{
              "payload": {
                "bills": [
                  {
                    "id": "ABCDEF1234",
                    "status": "withdrawn",
                    "uri": "https://gocardless.com/api/v1/bills/ABCDEF1234",
                    "amount": "5.0",
                    "amount_minus_fees": "4.95",
                    "source_type": "subscription",
                    "source_id": "1234ABCDEF",
                    "payout_id": "PO0000ABCDEFG"
                  },
                  {
                    "id": "ABCDEF1234",
                    "status": "withdrawn",
                    "uri": "https://gocardless.com/api/v1/bills/ABCDEF1234",
                    "amount": "25.0",
                    "amount_minus_fees": "24.75",
                    "source_type": "subscription",
                    "source_id": "1234ABCDEF",
                    "payout_id": "PO0000ABCDEFG"
                  },
                  {
                    "id": "ABCDEF1234",
                    "status": "withdrawn",
                    "uri": "https://gocardless.com/api/v1/bills/ABCDEF1234",
                    "amount": "5.0",
                    "amount_minus_fees": "4.95",
                    "source_type": "subscription",
                    "source_id": "1234ABCDEF",
                    "payout_id": "PO0000ABCDEFG"
                  }
                ],
                "resource_type": "bill",
                "action": "withdrawn",
                "signature": "2225331228d6778d7bbc4df50e724a88535761e7fe7dcb5da84106071ff83bdd"
              }
            }';
    }

    private function paymentCancelledPayload()
    {
        return '{
              "payload": {
                "bills": [
                  {
                    "id": "ABCDEF1234",
                    "status": "cancelled",
                    "uri": "https://gocardless.com/api/v1/bills/ABCDEF1234",
                    "amount": "10.0",
                    "amount_minus_fees": "9.9",
                    "source_type": "subscription",
                    "source_id": "1234ABCDEF"
                  }
                ],
                "resource_type": "bill",
                "action": "cancelled",
                "signature": "a91eab1e804a8369d93d8025047230a04629bc0730638b89ecde569e5607a343"
              }
            }';
    }

    private function subscriptionCancelledPayload()
    {
        return '{
              "payload": {
                "subscriptions": [
                  {
                    "id": "ABCDEF1234",
                    "status": "cancelled",
                    "uri": "https://gocardless.com/api/v1/subscriptions/ABCDEF1234"
                  }
                ],
                "resource_type": "subscription",
                "action": "cancelled",
                "signature": "3a2c7d108a19a592f626b804580666c8ff003eef3702395015f0dcced9249e17"
              }
            }';
    }
}