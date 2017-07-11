# Africas talking Api gateway for php 
A php script for sending messages through Africa's talking API

Send a message
```php
require_once 'vendor/autoload.php';

use AfricasTalking\Gateway;
use Http\Adapter\GuzzleHttpAdapter;
use Http\Exceptions\HttpException;

$conf = parse_ini_file('env.ini');
$adapter = new GuzzleHttpAdapter($options);
try {
    $gateway = new Gateway($adapter, $conf['USERNAME'], true);
    $response = $gateway->sms->sendMessage('0700123456', 'My sample Message');
    var_dump($response);
} catch (HttpException $e) {
    throw $e;
}
        
 ```
 
