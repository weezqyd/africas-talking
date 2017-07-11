# Africas talking Api gateway for PHP

A composer driven package for sending  for sending messages through Africa's talking API

Geting Started
--------------
Install the package from composer
```bash
$ composer require weezqyd/africastalking
```
You then need to install **one** of the following: But we recoment GuzzleHttp
```bash
$ composer require kriswallsmith/buzz:~0.10
$ composer require guzzlehttp/guzzle:~5.0
$ composer require guzzlehttp/guzzle:~6.0
```
Configure the Adapter
---------------------
If you use Guzzle, just pass an array of options to the constructor of `Http\Adapter\GuzzleAdapter`.
Please refer to [Guzzle Documentation](http://docs.guzzlephp.org/en/stable/request-options.html). for a full list of possible options

You can add the adapter setup ro a bootstrap file or pass it as a service to your IOC Container

```php
require_once 'vendor/autoload.php';

use AfricasTalking\Gateway;
use Http\Adapter\GuzzleHttpAdapter;

// These Headers are required
$options = ['headers' => 
				[
                    'apiKey' => 'API-KEY',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ];
$adapter = new GuzzleHttpAdapter($options);
// Pass the adapter and your Africastalking Username to the gateway 
// The third parameter is a sandbox flag when true the Api wiil run in the sandbox, The defaults is false
$gateway = new Gateway($adapter, 'USERNAME', true);
        
 ```
 
### Sending an SMS Message

With everything set up now sending an SMS is as simple as

```php
use Http\Exceptions\HttpException;
// ..... Rest of adapter setup
try {
	$response = $gateway->sms->sendMessage('+254700123456', 'My sample message');
	var_dump($response);
} catch(HttpException $e) {
	print_r($e);
}

```
#### Passing additional options
The SMS API accept additional options to be passed allong with the request

| Parameter            	| Description                                                                                                                                                                                                                                                              	|
|----------------------	|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------	|
| from                 	| Shortcode or alphanumeric that is registered with your Africa's Talking account. If missing, the default value (20414) will be populated on the message                                                                                                                  	|
| enqueue              	| This parameter is used for Bulk SMS clients that would like deliver as many messages to the API before waiting for an Ack from the Telcos. If enabled, the API will store the messages in its databases and send them out asynchronously after responding to the request 	|
| keyword              	| This parameter is used for premium services. It is essential for subscription premium services.                                                                                                                                                                          	|
| linkId               	| This parameter is used for premium services to send OnDemand messages. We forward the linkId to your application when the user send a message to your service                                                                                                            	|
| retryDurationInHours 	| This parameter is used for premium messages. It specifies the number of hours your subscription message should be retried in case it's not delivered to the subscriber.                                                                                                  	|


** Send with options **

```php
use Http\Exceptions\HttpException;
// ..... Rest of adapter setup
$options = [
	'from' => '22123',
	'enqueque' => 1
];
try {
	$response = $gateway->sms->sendMessage('+254700123456', 'My sample message', $options);
	var_dump($response);
} catch(HttpException $e) {
	print_r($e);
}

```
#### Sending to multiple recipients 
At times you would want to do somthing with the API response. Well worry no more. To acheive this pass a callback funtion as the fourth parameter of the `sendMessage()` method. This callback will be run for each message that is sent

```php
use Http\Exceptions\HttpException;
// ..... Rest of adapter setup
$recipients = ['+254700123456', '+254700123123', '+254700123000'];
try {
	$gateway->sms->sendMessage($recipients, 'My sample message', ['from' => '22123'], funtion($recipient) {
		// $recipient->status;
		// $recipient->messageId;
		// $recipient->cost;
		// $recipient->number;
	});
} catch(HttpException $e) {
	print_r($e);
}

```