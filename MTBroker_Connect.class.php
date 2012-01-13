<?php	
class MTConnect{
	/*MTBroker to MTConnect
	 * Public function allowing us to translate a piece of string.
	 *
	 * @returns: A string of translated text.
	 */
	 
	//Proxy switch for MicrosoftTraslator Naoto 2010-03-20	 
	//var $use_proxy = false;
	
	/* 
	 * Main machine translation methond. Must be called with the following parameters:
	 * $source: source language string, e.g. "english"
	 * $target: target language string, e.g. "spanish"
	 * $text: text string to be translated.
	 * Optional Parameters.
	 * $preferred_provided: set to "babelfish" (default).
	 * $proxy: set to "ul" (default), or "".
	 */
	public function translate($source, $target, $text, $preferred_provider = '', $proxy = ''){
		$ret = '';	
		if ($preferred_provider == "babelfish")
			$ret = $this->babelFish($source,$target,$text,$proxy);				
		elseif ($preferred_provider == "microsoft")	
			$ret = $this->msTranslator($source,$target,$text,$proxy);			
		else
			$ret = $this->msTranslator($source,$target,$text,$proxy);
			//$ret = $this->babelFish($source,$target,$text,$proxy);		
		return $ret;
	}
		
	private function babelFish($source,$target,$text,$proxy){       
	
	$config = parse_ini_file('config_mapper.ini');
	$httprequest = $config['http_request'];
	require_once $httprequest;
		
		$b_slang=strtoupper($source);
		$b_tlang=strtoupper($target);
		$arr= parse_ini_file("languages.ini");
		
		$b_slang=$arr[$b_slang]; 
		$b_tlang=$arr[$b_tlang];
		
		$lp=$b_slang.'_'.$b_tlang;
				
	$request = new HTTP_Request2('http://babelfish.yahoo.com/translate_txt', HTTP_Request2::METHOD_POST);
	$request->setMethod(HTTP_Request2::METHOD_POST)
		->setHeader('Accept-Charset', 'utf-8')
		->addPostParameter('ei', 'UTF-8')
		->addPostParameter('lp', $lp)
		->addPostParameter('trtext', $text);
		
		try{
			$response=$request->send();
				if (200 == $response->getStatus()){
					$data=$response->getBody();			
					$pattern="/\<div id\=\"result\"\>\<div style\=.*?\>(.*?)\<\/div\>\<\/div\>/";
			        preg_match($pattern, $data, $matches);
			        $translation=strip_tags($matches[1]);
				}
				else{
					echo 'UnexpectedHTTP Status: '.$response->getStatus().''.$response->getReasonPhrase();
				}
		}
		
		catch (HTTP_Request2_Exception $e){
			echo 'Error: '.$e->getMessage();
		}
		return trim($translation);
	}
	
	private function msTranslator($source,$target,$text,$proxy){
		
		$b_slang=strtoupper($source);
		$b_tlang=strtoupper($target);
		$arr= parse_ini_file("languages.ini");
	
		$b_slang=$arr[$b_slang]; 
		$b_tlang=$arr[$b_tlang];
		
		//Proxy switch Naoto 2010-03-20
		if ($proxy=='ul')
		{
			$aContext = array(
			'http' => array(
			   	'proxy' => 'tcp://staff-proxy.ul.ie:8080', // This needs to be the server and the port of the NTLM Authentication Proxy Server.
			    'request_fulluri' => True,
			    ),
			);
		}
		else
		{
			$aContext = array(
			'http' => array(
			   	//'proxy' => 'tcp://staff-proxy.ul.ie:8080', // This needs to be the server and the port of the NTLM Authentication Proxy Server.
			    'request_fulluri' => True,
			    ),
			);
		}
		//Proxy switch Naoto 2010-03-20
	    
		$cxContext = stream_context_create($aContext);	
		$query = urlencode($text).'&from='.urlencode($b_slang).'&to='.urlencode($b_tlang);
		$sFile = file_get_contents('http://api.microsofttranslator.com/V1/Http.svc/Translate?appId=B762C414CF08D83A6715EEB0171C4BF6E1AF0490&text='.$query, False, $cxContext);
		
		return $sFile;		
	}
	
} 