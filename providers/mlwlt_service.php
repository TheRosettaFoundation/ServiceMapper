<?php

namespace mlwlt_service;

require_once __DIR__."/../XLIFFVersionEnum.php";


header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');

class mlwlt_xliff_mt_echo {

    public $xliff_input; // base64Binary
    public $fileName; // string

}

class mlwlt_xliff_mt_echoResponse {

    public $mlwlt_xliff_mt_echoResult; // base64Binary

}

class mlwlt_xliff_mt_prepare {

    public $xliff_input; // base64Binary
    public $fileName; // string

}

class mlwlt_xliff_mt_prepareResponse {

    public $mlwlt_xliff_mt_prepareResult; // base64Binary

}

//require_once 'IProvider.php';
require_once __DIR__.'/../IProvider.php';

/**
 * mlwlt_service class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class mlwlt_service extends \SoapClient implements \IProvider {
     public function isEnabled(){
        return true;
    }

    private static $classmap = array(
        'mlwlt_xliff_mt_echo' => 'mlwlt_service\mlwlt_xliff_mt_echo',
        'mlwlt_xliff_mt_echoResponse' => 'mlwlt_service\mlwlt_xliff_mt_echoResponse',
        'mlwlt_xliff_mt_prepare' => 'mlwlt_service\mlwlt_xliff_mt_prepare',
        'mlwlt_xliff_mt_prepareResponse' => 'mlwlt_service\mlwlt_xliff_mt_prepareResponse',
    );

    public function __construct($wsdl = "http://mlwlt.moravia.com/mlwlt-service-xliff-mt/mlwlt-service.asmx?WSDL", $options = array()) {
        foreach (self::$classmap as $key => $value) {
            if (!isset($options['classmap'][$key])) {
                $options['classmap'][$key] = $value;
            }
        }
        parent::__construct($wsdl, $options);
    }

    /**
     *  
     *
     * @param mlwlt_xliff_mt_echo $parameters
     * @return mlwlt_xliff_mt_echoResponse
     */
    public function mlwlt_xliff_mt_echo(mlwlt_xliff_mt_echo $parameters) {
        return $this->__soapCall('mlwlt_xliff_mt_echo', array($parameters), array(
                    'uri' => 'http://mlwlt.moravia.com/',
                    'soapaction' => ''
                        )
        );
    }

    /**
     *  
     *
     * @param mlwlt_xliff_mt_prepare $parameters
     * @return mlwlt_xliff_mt_prepareResponse
     */
    public function mlwlt_xliff_mt_prepare(mlwlt_xliff_mt_prepare $parameters) {
        return $this->__soapCall('mlwlt_xliff_mt_prepare', array($parameters), array(
                    'uri' => 'http://mlwlt.moravia.com/',
                    'soapaction' => ''
                        )
        );
    }

    public function translateFile($Filetext, $source=null, $target=null) {
        $doc = new \DOMDocument();
        $doc->loadXML($Filetext);
        $xliffVersion = $doc->firstChild->getAttribute('version');    
        if($xliffVersion == \XLIFFVersionEnum::XLIFF_2_0) {
          
            $sourceLang = $doc->firstChild->getAttribute("srcLang");
            $targetLang = $doc->firstChild->getAttribute("tgtLang");            
         
            $xliff1x = new \DOMDocument();
            $xliff1x->loadXML(file_get_contents(__DIR__."/../template.xlf"));
            $body = $xliff1x->getElementsByTagName("body")->item(0);
            
            $files = $xliff1x->getElementsByTagName("file");            
            foreach($files as $file) {
                $file->setAttribute("original", "test.xlf");
                $file->setAttribute("source-language", $sourceLang);
                $file->setAttribute("target-language", $targetLang);
            }
            
            $map = array();
            $units = $doc->getElementsByTagName("unit");
            $i = 0;
            foreach($units as $unit) {
                $transUnit = $xliff1x->createElement("trans-unit");
                $transUnit->setAttribute("id", "TU$i");
                
                $sourceElement = $xliff1x->createElement("source");
                $sourceElement->setAttribute("xml:lang", $sourceLang);
                
                $segSource = $xliff1x->createElement("seg-source");
                $segSource->setAttribute("xml:lang", $sourceLang);
                
                $segments = $unit->getElementsByTagName("segment");
                foreach($segments as $segment) { 
                    
                    $segmentSourceValue =$segment->getElementsByTagName("source")->item(0)->nodeValue;
//                    $seg=  simplexml_import_dom($segment);
//                    $pos = strpos($segmentSourceValue, "<".$seg->getName());
//                    if($pos ===0){
//                        $pos = strpos($segmentSourceValue, ">")+1;
//                        $segmentSourceValue=  substr($segmentSourceValue, $pos);
//                        $pos = strrpos($text, "</".$seg->getName().">");
//                        $segmentSourceValue= substr_replace($segmentSourceValue,"",$pos);
                    //}
                    $mrk = $xliff1x->createElement("mrk", $segmentSourceValue);
                    $mrk->setAttribute("mid", "MRK$i");
                    $mrk->setAttribute("mtype", "seg");                    
                    
                    $sourceElement->appendChild($mrk);
                    $cloneSourceMrk = $mrk->cloneNode(true);
                    $cloneSourceMrk->setAttribute("mid", "SM$i");
                    $segSource->appendChild($cloneSourceMrk);
                    
                   
                    $map["SM$i"] = $segment;
                    $map["MRK$i"] = $segment;
                    
                    $i++;
                }
                
                $body->appendChild($transUnit);
                //$transUnit->insertBefore($sourceElement, $transUnit->firstChild);
                $transUnit->appendChild($sourceElement);
                $transUnit->appendChild($segSource);
                
                
            }
            $data = $doc->saveXML();
            $xliff1xText = $xliff1x->saveXML();   
            


            $data = new mlwlt_xliff_mt_prepare();
            $data->xliff_input = base64_encode($xliff1xText);
            $result = $this->mlwlt_xliff_mt_prepare($data);
            $xliff1xText =  base64_decode($result->mlwlt_xliff_mt_prepareResult);            
//            $xliff1xText = file_get_contents(__DIR__."/../test/xliff12.xlf"); // fake translation
           
            $doc2 = new \DOMDocument();
            $doc2->loadXML($xliff1xText);
            $xPath = new \DOMXPath($doc2);

            $altTrans = $xPath->query("//*[local-name()='alt-trans']");
//            $matches = $doc->getElementsByTagName("match");

            
            foreach($altTrans as $altTran) {
                $currentSeg = $map[$altTran->getAttribute("mid")];
                
                $matches = $currentSeg->getElementsByTagName("matches")->item(0);
                if(is_null($matches)){
                    $matches = $doc->createElement("matches");
                    $currentSeg->appendChild($matches);
                }
                
                $altSource = $altTran->getElementsByTagName("source")->item(0);
                $altTarget = $altTran->getElementsByTagName("target")->item(0);
                
                $match = $doc->createElement("match");
                $matchSource = $doc->createElement("source", $altSource->nodeValue);
                $matchSource->setAttribute("xml:lang", $sourceLang);
                $matchTarget = $doc->createElement("target", $altTarget->nodeValue);

                $match->appendChild($matchSource);
                $match->appendChild($matchTarget);
                $matches->appendChild($match);

                    
                $attribs = $altTran->attributes;
                foreach($attribs as $attrib) {
                    if(strcasecmp($attrib->name,"mid")==0) continue;
                    if(strcasecmp($attrib->name,"match-quality")==0){
                        $match->setAttribute("match-suitability", $attrib->value);
                    }
                    else $match->setAttribute($attrib->name, $attrib->value);
                }
               
            }

        }

        return $doc->saveXML(); 
    }

    public function getSourceLanguages() {
        return array("en");
    }

    public function getTargetLanguages() {
        return array("fr", "es");
    }

}
//
//$temp = new mlwlt_service();
//echo $temp->translateFile(file_get_contents(__DIR__."/../test/xliff3Test.xlf"));
//$content = file_get_contents("/home/sean/Desktop/lucia/simple_short.xlf");
//$content = mb_convert_encoding($content, 'UTF-16', 'UTF-8');
//echo $content;


