<?php

use BB\Helpers\PayPalConfig;

class PaypalIPNController extends \BaseController {

    public function receiveNotification()
    {
        $ipnMessage = new \PayPal\IPN\PPIPNMessage('', PayPalConfig::getConfig());

        foreach($ipnMessage->getRawData() as $key => $value) {
            \Log::debug("IPN: $key => $value");
        }

        if($ipnMessage->validate()) {
            \Log::debug("IPN Success");
        } else {
            \Log::error("Invalid IPN");
        }
    }
} 