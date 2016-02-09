<?php
namespace Weezqyd\Api\Exception;

use Exception;

class AfricasTalkingAPIException extends Exception
{
    function noConnection()
    {
        return 'No Internet connection please check your connection and try again';
    }
    function noCredentials()
    {
        return "Error Processing Request, You must provide both Api key and userID";
    }
    function toOrMessage()
    {
    	return "Please supply both to and message parameters";
    }
}