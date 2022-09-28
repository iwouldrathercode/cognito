<?php

// config for Iwouldrathercode/Cognito
return [

    'version' => '2016-04-18',

    'auth_flow' => 'ADMIN_NO_SRP_AUTH',

    'client_id' => env('AWS_COGNITO_CLIENT_ID'),
    
    'user_pool_id' => env('AWS_COGNITO_USER_POOL_ID'),
    
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1')
];
