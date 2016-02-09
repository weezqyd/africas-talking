<?php
namespace Weezqyd\Api;

use Httpful\Request;
use Httpful\Mime;
use Httpful\Http;
use Weezqyd\Api\AfricasTalking;

Class HttpClient extends AfricasTalking // implements HttpClientInterface
{
	public $init;

	function __construct()
	{
		parent::__construct();
		$this->init();
	}

	public function init()
	{
		$this->init = Request::init()
	    ->withoutStrictSsl()        // Ease up on some of the SSL checks
		->addHeaders(['content-type'=>Mime::FORM,'apikey'=>$this->_apiKey,'Accept'=>'']);
 		return Request::ini($this->init);
	}
	public function sendMessage($to, $message, $options='')
	{
		$params=[
						'username' => $this->_username,
						'message'    => $message,
		            	'to' => implode(',',$to),
					   ];
		$this->_responseBody=http_build_query($params, '', '&');
		$this->_requestUrl = $this->SMS_URL;
		$this->response=$this->init->post($this->_requestUrl )->body($this->_responseBody)->send();
		return $this->response ;
		//var_dump($this);

	}
	public function jsonEncode($data)
	{
		return json_encode($data);
	}
	public function addContact($contacts)
	{
		
	}

}