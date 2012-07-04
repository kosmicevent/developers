<?php

class SMSApiClient{
	private $apiKey;
	private $apiSecret;
	private $apiUrl;
	private $acceptType;

	/* Sets object's data parameters
	 @param: api's url, your apikey, your apisecret
	*/
	public function __construct ($url, $apiKey, $apiSecret){
		$this->apiKey = $apiKey;
		$this->apiSecret = $apiSecret;
		$this->apiUrl = $url;
		$this->acceptType = 'application/json';
	}

	/* Sets the Authentication Information to be passed during curl exec
	 @param: curl object
	*/
	private function setAuth (&$curlHandle) {
		if ($this->apiKey !== null && $this->apiSecret !== null)
		{
			curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curlHandle, CURLOPT_USERPWD, "".$this->apiKey.":".$this->apiSecret);
		}
	}

	/* Sets basic curl options
	 @param: curl handle
	*/
	private function setCurlOpts (&$curlHandle){
		curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
		curl_setopt($curlHandle, CURLOPT_URL, $this->apiUrl);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array ('Accept: ' . $this->acceptType));
	}

	/* helper function for converting arrays to html strings
	 @param: data array
	*/
	private function htmlReady ($data){
		if (!is_array($data)){
			throw new InvalidArgumentException('Invalid data input for postBody. Array expected');
		}
		$data = http_build_query($data, '', '&');
		return $data;
	}

	/* Helper function for sending messages
	 @param: receiver, sender, message, option(1 -blind, 0 -Wait)
	@return: returns curl exec results
	*/
	private function messageData($to,$from,$message,$option){
		$data = array("to" => $to, "from" => $from, "message" => $message, "option" => $option);
		$fields = count($data);
		$data = http_build_query($data, '', '&');

		//open connection
		$ch = curl_init();

		$this->setAuth($ch);

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$this->apiUrl );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Accept: ' . $this->acceptType));
		curl_setopt($ch,CURLOPT_POST,$fields);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

		////execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		return $result;
	}

	/* Sends message without confirmation from server
	 @param: receiver, sender, message
	@return: returns curl exec results
	*/
	public function sendBlindMessage($to,$from,$message){
		return $this->messageData($to,$from,$message,1);
	}

	/* Sends message and wait for confirmation from server
	   @param: receiver, sender, message
	   @return: returns curl exec results
	*/
	public function sendMessage($to,$from,$message){
		return $this->messageData($to,$from,$message,0);
	}
}

?>