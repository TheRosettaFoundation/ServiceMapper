<?php	
class MTConnect{
	/*MTBroker to MTConnect
	 * Public function allowing us to translate a piece of string.
	 * @returns: A string of translated text.
     * Proxy switch for MicrosoftTraslator Naoto 2010-03-20	 
	 * Main machine translation method. Must be called with the following parameters:
	 * $source: source language string, e.g. "english"
	 * $target: target language string, e.g. "spanish"
	 * $text: text string to be translated.
	 * Optional Parameters.
	 * $preferred_provided: set to "Bing translator" (default).
	 * $proxy: set to "ul" (default), or "".
	 */
	public function translate($source, $target, $text, $preferred_provider, $proxy){
		$ret = '';
        
		if ($preferred_provider == 'Yahoo! Babelfish')
			$ret = $this->babelFish($source,$target,$text,$proxy);				
		elseif ($preferred_provider == 'Microsoft Bing Translator')	
			$ret = $this->msTranslator($source,$target,$text,$proxy);			
		else
			$ret = $this->msTranslator($source,$target,$text,$proxy);
		return $ret;
	}

	private function babelFish($source,$target,$text,$proxy){       
		require_once 'HTTP'.DIRECTORY_SEPARATOR.'Request2.php'; 
		$b_slang=strtolower($source);
		$b_tlang=strtolower($target);
		$arr= parse_ini_file("demolangs.ini");
		
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
		//Source and target languages must be in language code form
        $b_slang=strtolower($source);
		$b_tlang=strtolower($target);
		$language_codes = parse_ini_file("demolangs.ini");
        $language_names = parse_ini_file("languages.ini");

        if(!isset($language_codes[$b_slang])) {
            $b_slang = $language_names[strtoupper($b_slang)];
        }

        if(!isset($language_codes[$b_tlang])) {
            $b_tlang = $language_names[strtoupper($b_tlang)];
        }

		//Proxy switch Naoto 2010-03-20
		if ($proxy){
			$config = parse_ini_file('config.ini');
			$proxy ='tcp://'.$config['proxy_address'];	

			$aContext = array(
			'http' => array(
					'proxy' =>$proxy,
			    'request_fulluri' => True,
			    ),
			);
		}
		else
		{
			$aContext = array(
			'http' => array(
			    'request_fulluri' => True,
			    ),
			);
		}
	    
		$cxContext = stream_context_create($aContext);	
		$query = urlencode($text).'&from='.$b_slang.'&to='.$b_tlang;
		$sFile = file_get_contents('http://api.microsofttranslator.com/V2/Http.svc/Translate?appId=B762C414CF08D83A6715EEB0171C4BF6E1AF0490&text='.$query, False, $cxContext);
		
		return $sFile;		
	}	
}
