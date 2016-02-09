<?php
namespace Weezqyd;

require __DIR__.'/vendor/autoload.php';
        use Weezqyd\Api\HttpClient;
        
        
        // An Array of recipients
        $recipients[] = '0000000000000';
        // Initialize The Africas Talking Api
        $gateway = new HttpClient();
        $message='nice one a message send through the awesome sematime api';
            $results = $gateway->sendMessage($recipients, $message);
            isset($results) ? $response = $results : $response = 'An Error Was Encoutered';
            
            echo $response ;
        /*
        $dotenv = new Dotenv(__DIR__);
        $dotenv->load();
        echo getenv('API_KEY');
        var_dump($dotenv);
        ?>*/
