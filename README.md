# Africas talking Api gateway for php 
This is a php package that you can easily intergarate into your project to send SMS Messages usint the awesome Africas talking API, to start using this package require it in your project using composer a php Dependency management tool.
if you dont have composer installed head over to 

Send a message
```php
require __DIR__.'/vendor/autoload.php';
        use Weezqyd\Api\HttpClient;
        // An Array of recipients
        $recipients[] = '123456789';
        // Initialize The Sematime Api
        $gateway = new SematimeAPI();
        $message='A nice message send using Sematime';
       
        $results = $gateway->sendMessage($recipients, $message);
        echo $results;
        
 ```
 
