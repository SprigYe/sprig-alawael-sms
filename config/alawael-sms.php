<?php

return [
    'org_name' => env('ALAWAEL_ORG_NAME'),
    'username' => env('ALAWAEL_USERNAME'),
    'password' => env('ALAWAEL_PASSWORD'),
    'send_url' => env('ALAWAEL_URL', "https://sms.alawaeltec.com/MainServlet"),
    'balance_url' => env('ALAWAEL_BALANCE_URL', "http://185.216.203.97:8070/AlawaelEstalam"),
    'coding' => env('ALAWAEL_CODING', 2),
];
