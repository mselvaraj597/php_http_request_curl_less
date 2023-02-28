<?php
ini_set("allow_url_fopen", true);
/* Define the errors */
define('BAD_REQUEST', 'Error: Bad HTTP Request');
define('AUTH_ERROR', 'Error: Authentication Failed');

/* Call the class */
$sendRequest = new sendHttpRequest();
$sendRequest->getPost();

/* Define the errors */
class sendHttpRequest {

    protected $name;
    protected $email;
    protected $gitHub;
	protected $url;
	
	/**
     * Constructor
     */

    public function __construct()
    {
        $this->name = "Meena";
        $this->email = "meenuselvaraj@gmail.com";
		$this->gitHub = "";
        $this->url = "https://corednacom.corewebdna.com/assessment-endpoint.php";
		
    }
	
	/**
     * Function to get authentication token
     */

	protected function getAuthorisation()
	{
		try {
			$data = array (
			  "name"	=>	$this->name,
			  "email"	=>	$this->email,
			  "url"	=>	$this->gitHub,
			);
			
			$auth  = "Authorization: header('Access-Control-Allow-Methods: OPTIONS,GET,POST')";
			
			return $this->callRequest('OPTIONS',$auth,$data);
		}
		catch(Exception $e){
			throw new Exception(BAD_REQUEST,0,$e);
		}
	}
	/**
     * Function to get send http request using post
     */
	public function getPost()
	{
		try {
			$data = array (
			  "name"	=>	$this->name,
			  "email"	=>	$this->email,
			  "url"	=>	$this->gitHub,
			);
			
			$bearerToken=$this->getAuthorisation();
			if (!$bearerToken) echo AUTH_ERROR; 
			
			$auth = "Authorization: Bearer ".$bearerToken." header('Access-Control-Allow-Methods: OPTIONS,GET,POST')";
			
			$response = $this->callRequest('POST',$auth,$data);
			return json_decode($response,true);
			
			
		}
		catch(Exception $e){
			throw new Exception(BAD_REQUEST,0,$e);
		}
	}
	
	/**
     * Function to call request
     */
	protected function callRequest($method,$authorization,$data)
	{
		try {
			
			$opts = array('http' =>
				array(
					'method'  => $method,
					'header'  => "Accept: application/json\r\n" . "Content-Type: application/json\r\n".$authorization,
					'content' => json_encode($data)
				)
			);
			$context  = stream_context_create($opts);
			$response = file_get_contents($this->url, false, $context);
			return $response;
		}
		catch(Exception $e){
			throw new Exception(BAD_REQUEST,0,$e);
		}
	}

   
}







