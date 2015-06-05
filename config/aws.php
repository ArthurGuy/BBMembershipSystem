<?php return [

    /*
    |--------------------------------------------------------------------------
    | Your AWS Credentials
    |--------------------------------------------------------------------------
    |
    | In order to communicate with an AWS service, you must provide your AWS
    | credentials including your AWS Access Key ID and AWS Secret Access Key.
    |
    | To use credentials from your credentials file or environment or to use
    | IAM Instance Profile credentials, please remove these config settings from
    | your config or make sure they are null. For more information, see:
    | http://docs.aws.amazon.com/aws-sdk-php-2/guide/latest/configuration.html
    |
    */
    'key'    => env('AWS_KEY'),
    'secret' => env('AWS_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | AWS Region
    |--------------------------------------------------------------------------
    |
    | Many AWS services are available in multiple regions. You should specify
    | the AWS region you would like to use, but please remember that not every
    | service is available in every region. To see what regions are available,
    | see: http://docs.aws.amazon.com/general/latest/gr/rande.html
    |
    */
    'region' => env('AWS_REGION', 'eu-west-1'),

    'version' => 'latest',

];
