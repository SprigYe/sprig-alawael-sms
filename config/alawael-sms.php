<?php

return [
    'org_name' => env('ALAWAEL_ORG_NAME'),
    'username' => env('ALAWAEL_USERNAME'),
    'password' => env('ALAWAEL_PASSWORD'),
    'send_url' => 'http://sms.alawaeltec.com/SMSGetDelivery',
    'balance_url' => 'http://185.216.203.97:8070/AlawaelEstalam',
    'coding' => env('ALAWAEL_CODEING', 2),
];
