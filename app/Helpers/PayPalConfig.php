<?php namespace BB\Helpers;

class PayPalConfig
{
    public static function getConfig()
    {
        return [
            'mode' => 'live' //sandbox for testing
        ];
    }

    // Creates a configuration array containing credentials and other required configuration parameters.
    public static function getAcctAndConfig()
    {
        $config = array(
            // Signature Credential
            'acct1.UserName' => $_ENV['PAYPAL_USERNAME'],
            'acct1.Password' => $_ENV['PAYPAL_PASSWORD'],
            'acct1.Signature' => $_ENV['PAYPAL_SIGNATURE'],
        );

        return array_merge($config, self::getConfig());
    }
} 