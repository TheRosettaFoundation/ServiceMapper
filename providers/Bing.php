<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');


require_once 'IProvider.php';

class Bing extends SoapClient implements IProvider {
    
    public function __construct() {
        
    }

    
    public function getSourceLanguages() {
        return array();
    }

    public function getTargetLanguages() {
        return array();
    }
    
    public function translateFile($fileText, $sourceLanguage, $targetLanguage) {

         $doc = new DOMDocument();
         if($doc->loadXML($fileText)) {
             if($transUnits = $doc->getElementsByTagName("trans-unit")) {
                 foreach($transUnits as $transUnit ){                        
                     $source = $transUnit->getElementsByTagName("source");
                     $text = $source->item(0)->nodeValue;

                     $translation = $this->translate($sourceLanguage,$targetLanguage,$text);
                     $translation = strip_tags($translation);
                     $alt_trans = $doc->createElement("alt-trans");
                     $transUnit->appendChild($alt_trans);  
                     $clone=$source->item(0)->cloneNode(true);
                     $alt_trans->appendChild($clone);
                     $altTarget = $doc->createElement("target");
                     $altTarget->setAttribute("xml:lang", $targetLanguage);
                     $altTarget->appendChild($doc->createTextNode($translation));
                     $alt_trans->appendChild($altTarget);                    
                 }  
             }                     
         } else {
             echo "Failed to load file. Check path/permissions.";
         }
         $doc->formatOutput = true;
         if($data = $doc->saveXML()) {
              return $data;
         } else {
             echo "Failed to dump XML tree to string";
         }
   }  
   

    public  function translate($source ,$target,$text) {        
        //Source and target languages must be in language code form
        $b_slang=strtolower($source);
        $b_tlang=strtolower($target);
        $language_codes = parse_ini_file("../demolangs.ini");
        $language_names = parse_ini_file("../languages.ini");

        if(!isset($language_codes[$b_slang])) {
            $b_slang = $language_names[strtoupper($b_slang)];
        }

        if(!isset($language_codes[$b_tlang])) {
            $b_tlang = $language_names[strtoupper($b_tlang)];
        }
        
        //Proxy switch Naoto 2010-03-20
        $proxy = false; // test
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
                    'request_fulluri' => True
                    ,'method'=>"GET"
                    ,'header'=>"Content-Type: text/xml; charset=utf-8" 
                    ),
                );
        }        

        $cxContext = stream_context_create($aContext);	
        $query = urlencode($text).'&from='.$b_slang.'&to='.$b_tlang;
        $sFile = file_get_contents('http://api.microsofttranslator.com/V2/Http.svc/Translate?appId=B762C414CF08D83A6715EEB0171C4BF6E1AF0490&text='.$query, FILE_TEXT, $cxContext);
//        return mb_convert_encoding($sFile, 'unicode');
        
        return $sFile;		
    } 
}
//$bing= new Bing();
//
//echo $bing->translateFile(file_get_contents("/home/manuel/Desktop/ExampleDocs/lucia/Symposium3.xlf"), "en","es");

?>