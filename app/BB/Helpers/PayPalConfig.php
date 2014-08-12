<?php namespace BB\Helpers;

class PayPalConfig {
    public static function getConfig()
    {
        $config = array(
            // values: 'sandbox' for testing
            //		   'live' for production
            "mode" => "live"

            // These values are defaulted in SDK. If you want to override default values, uncomment it and add your value.
            // "http.ConnectionTimeOut" => "5000",
            // "http.Retry" => "2",
        );
        return $config;
    }

    // Creates a configuration array containing credentials and other required configuration parameters.
    public static function getAcctAndConfig()
    {
        $config = array(
            // Signature Credential
            "acct1.UserName" => $_ENV['PAYPAL_USERNAME'],
            "acct1.Password" => $_ENV['PAYPAL_PASSWORD'],
            "acct1.Signature" => $_ENV['PAYPAL_SIGNATURE'],
        );

        return array_merge($config, self::getConfig());;
    }
} 