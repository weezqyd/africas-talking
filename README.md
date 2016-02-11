# Africas talking Api gateway for php 
A php script for sending messages through Africa's talking API

Send a message
```php
require __DIR__.'/vendor/autoload.php';
        use Weezqyd\Api\HttpClient;
        // An Array of recipients
        $recipients[] = '123456789';
        // Initialize The Api
        $gateway = new HttpClient();
        $message='A nice message send using Africas Talking';
       
        $results = $gateway->sendMessage($recipients, $message);
        echo $results;
        
 ```
 
