<?php
namespace Weezqyd\Api;

use Weezqyd\Api\Exception\AfricasTalkingAPIException;
use Dotenv\Dotenv;
/**
* 
*/
class AfricasTalking
{
	
	public $_apiKey ;
    public $_username ;
    public $_requestBody;
    public $_requestUrl;
    public $_responseBody;
    public $_responseInfo;

    public $SMS_URL = "https://api.africastalking.com/version1/messaging";
    public $URL = "https://api.sematime.com/v1/{userId}";

    public $OK = 200;
    public $CREATED = 201;
    public $UNAUTHORIZED =401;
    public $FORBIDDEN =403;
    public $BAD_REQUEST =400;
    public $NOT_FOUND =404;
    public $SERVER_ERROR =500;


    const Debug = false;
    function __construct()
    {
    	
    	$this->boot();
    }
    function boot()
    {
        $dotenv = new Dotenv(realpath(__DIR__.'/../../'));
        $dotenv->load();
        $this->_apiKey=getenv('API_KEY');
        $this->_username=getenv('USERNAME');
    	if(strlen($this->_apiKey) == 0 || strlen($this->_username)==0)
    	{
    		print AfricasTalkingAPIException::noCredentials();
            exit;
    		//var_dump($this);
    		
    	}
    }

}
