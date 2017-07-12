
Geting Started
--------------
Install the package from composer
```bash
$ composer require weezqyd/africastalking @dev
```
You then need to install **one** of the following: But we recomend GuzzleHttp
```bash
$ composer require guzzlehttp/guzzle
$ composer require kriswallsmith/buzz
$ composer require nategood/httpful
```
Configure the Adapter
---------------------
This package uses guzzlehttp as the default adapter, if you decide to use another http client good for you all you need is to configure your client and pass the adapter as the fourth parameter to the gateway's constructor. You can also create your own adapter as long as it implements `Http\Adapter\AdapterInterface`.


An example is worth a thousand words

```php
require_once 'vendor/autoload.php';

use AfricasTalking\Gateway;
use Http\Adapter\BuzzAdapter;

// These Headers are required
$headers = [
        'apiKey' => 'API-KEY',
        'Accept' => 'application/json',
        'Content-Type' => 'application/x-www-form-urlencoded',
    ];
$adapter = new BuzzAdapter($headers);
// Pass the adapter and your Africastalking Username to the gateway 
// The third parameter is a sandbox flag when true the Api wiil run in the sandbox, The defaults is false
// Because we are using a custom client you dont need provide the API KEY to the gateway
// Instead pass an empty string or null
$gateway = new Gateway('USERNAME', null, true, $adapter);
        
 ```
 
### Sending an SMS Message

Now let us send an SMS notification 

```php
use AfricasTalking\Gateway;
use Http\Exceptions\HttpException;

$gateway = new Gateway('API-TOKEN', 'USERNAME');
try {
	$response = $gateway->sms->sendMessage('+254700123XXX', 'My sample message');
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

 *Send with options*

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