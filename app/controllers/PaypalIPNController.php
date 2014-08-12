<?php

use BB\Helpers\PayPalConfig;

class PaypalIPNController {

    public function receiveNotification()
    {
        $ipnMessage = new \PayPal\IPN\PPIPNMessage(Input::all(), PayPalConfig::getConfig());

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