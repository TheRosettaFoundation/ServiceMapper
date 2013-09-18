<?php

namespace mlwlt_service;

require_once __DIR__."/../XLIFFVersionEnum.php";
error_reporting(E_ALL^ E_WARNING);

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
        $xliff2x = new \DOMDocument();
        $xliff2x->loadXML($Filetext);
        $xliffVersion = $xliff2x->firstChild->getAttribute('version');    
        
        // if XLIFF 2.0, create a 1.2 file from it
        if($xliffVersion == \XLIFFVersionEnum::XLIFF_2_0) {
          
            $sourceLang = $xliff2x->firstChild->getAttribute("srcLang");
            $targetLang = $xliff2x->firstChild->getAttribute("tgtLang");            
         
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
            $units = $xliff2x->getElementsByTagName("unit");
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
                    
                    $segmentSourceValue =  strip_tags($xliff2x->saveXML($segment->getElementsByTagName("source")->item(0)));
//                    $segmentSourceValue =  $doc->saveXML($segment->getElementsByTagName("source")->item(0));
//                   
//                    $pos = strpos($segmentSourceValue, "<source");
//                    if($pos ===0){
//                        $pos = strpos($segmentSourceValue, ">")+1;
//                        $segmentSourceValue=  substr($segmentSourceValue, $pos);
//                        $pos = strrpos($segmentSourceValue, "</source>");
//                        $segmentSourceValue= substr_replace($segmentSourceValue,"",$pos);
//                    }
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
//            $data = $doc->saveXML();
            $xliff1xText = htmlspecialchars_decode($xliff1x->saveXML());   
            
//            $mrkDoc = new \DOMDocument();
//            $mrkDoc->loadXML($xliff1xText);
//            
//            $xpath = new \DOMXPath($mrkDoc);
//            $markers = $xpath->query("//*[local-name() = 'mrk']");
//            $invalid =array("id","ref","value","fs","subFs","storageRestriction","sizeRestriction" );
//            foreach($markers as $marker) {
//                $attribs = $marker->attributes;
//                
//                foreach($attribs as $attrib) {
//                    if (in_array($attrib->name, $invalid))$marker->removeAttribute($attrib->name);
//                    else{
//                        if(strcasecmp($attrib->name,"translate")===0 && strcasecmp($attrib->value,"no")===0){
//                        $marker->setAttribute("mtype", "protected");
//                        $marker->removeAttribute($attrib->name);
//                        }
//                        if((strcasecmp($attrib->name,"type")===0 && strcasecmp($attrib->value,"term")===0)||(strcasecmp($attrib->name,"terminology")===0 && strcasecmp($attrib->value,"yes")===0)){
//                            $marker->setAttribute("mtype", "term");
//                            $marker->removeAttribute($attrib->name);
//                        } else {
//                            $marker->removeAttribute($attrib->name);
//                        }
//                        if(strpos($attrib->name, "taConf")===0 || strpos($attrib->name, "taClass")===0 ||strpos($attrib->name, "taIdent")===0){
//                            if(strcasecmp("term",$marker->getAttribute("mtype"))!==0 ||is_null($marker->getAttribute("mtype")))$marker->setAttribute("mtype", "phrase");
//                        }
//                    }
//                }
//            }
//             $xliff1xText=  htmlspecialchars_decode($mrkDoc->saveXML());


            $data = new mlwlt_xliff_mt_prepare();
            $data->xliff_input = base64_encode($xliff1xText);
            $result = $this->mlwlt_xliff_mt_prepare($data);
            $xliff1xText =  base64_decode($result->mlwlt_xliff_mt_prepareResult);            
            //$xliff1xText = file_get_contents(__DIR__."/../test/xliff12.xlf"); // fake translation
           
            $doc2 = new \DOMDocument();
            $doc2->loadXML($xliff1xText);
            $xPath = new \DOMXPath($doc2);
            $fileNodes =$xliff2x->getElementsByTagName("file");
            $pRecs = $xPath->query("//*[local-name()='provenanceRecords']");
            
            foreach($pRecs as $pRec) {
                if(is_numeric($xid=$pRec->getAttribute("xml:id"))){
                            $pRec->setAttribute("xml:id","pr$xid");
                            $xliff1xText = str_replace("#$xid", "#pr$xid", $xliff1xText);
                }
                foreach($fileNodes as $f) {
                     $placeholder = $f->getElementsByTagName("group")->item(0);
                    if(is_null($placeholder)) {
                        $placeholder = $f->getElementsByTagName("unit")->item(0);
                    }
                    //$f->insertBefore($xliff2x->importNode($pRec,true), $placeholder);
                    //\ProviderHelper::insertNode($xliff2x->importNode($pRec,true), $placeholder, \ProviderHelper::INSERT_END);
                    $f->appendChild($xliff2x->importNode($pRec,true));
                    
                }
            }
            
            $xPathX2 = new \DOMXPath($xliff2x);
            $mlwltProvRec = $xPathX2->query("//*[local-name()='provenanceRecords']/*[local-name()='provenanceRecord' and @its:tool='mosesmt']");
            $mlwltProvRecId = $mlwltProvRec->item(0)->parentNode->getAttribute("xml:id");
            
            $units = $xliff2x->getElementsByTagName("unit");
            foreach($units as $unit) {
                $count=0;
                if($segments = $unit->getElementsByTagName("segment")) {               
                    $segCount= 0;
                    $matchId = 0;
                    foreach($segments as $segment ){  
                        $idVal = $segment->getAttribute("id");
//                        if(!$segment->hasAttribute("id")){ 
//                            $idVal=$unit->getAttribute("id")."|".$segCount++;
//                            $segment->setAttribute("id", $idVal);
//                        }
                        $sourceElement = simplexml_import_dom($segment->getElementsByTagName("source")->item(0));
                        $segmentText = $sourceElement->asXML();
                         $pos = strpos($segmentText, "<".$sourceElement->getName());
                            if($pos ===0){
                                $pos = strpos($segmentText, ">")+1;
                                $segmentText=  substr($segmentText, $pos);
                                $pos = strrpos($segmentText, "</".$sourceElement->getName().">");
                                $segmentText= substr_replace($segmentText,"",$pos);
                            }
                        $translateSegment = ($segment->hasAttribute("translate") && $segment->getAttribute("translate")=="yes") || (!$segment->hasAttribute("translate")||$translateUnit);
                        if($translateSegment) {
//                            $matches = $segment->parentNode->getElementsByTagName("matches")->item(0);
//                            $prefix = $xliff2x->lookupPrefix(\IProvider::XMLNS_MTC) ;
//                            
//                            if(is_null($matches)){
//                              
//                                $matches = $xliff2x->createElementNS(\IProvider::XMLNS_MTC, "$prefix:matches");
//                                $placesholder= $segments->item($segments->length-1);
//                                \ProviderHelper::insertNode($matches, $placesholder, \ProviderHelper::INSERT_AFTER);
//                            }
//                            
//                            
//
                            $matchelementID = "mlwlt_".$matchId++;
//                            $match = $xliff2x->createElementNS(\IProvider::XMLNS_MTC, "$prefix:match");
//                            $match->setAttribute("id",$matchelementID );
//                            $match->setAttribute("its:provenanceRecordsRef", "#$mlwltProvRecId");
//                            $matchSource= $xliff2x->createElement("source");
//                            $matchSource->setAttribute("xml:lang", $source);
////                            $idMRK = $doc->createElement("mrk");
////                            $idMRK->appendChild(new \DOMText($segmentText));
////                            $idMRK->setAttribute("ref", "#".$idVal);
////                            $idMRK->setAttribute("type", "match");
//                            $matchSource->appendChild(new \DOMText($segmentText));
//                            
//                            //$segmentSource->setAttribute("ref", $match->getAttribute("id"));
//                            $match->appendChild($matchSource);
//                            
//                            
//                            
//                            
//                            
//                            //$translation = $this->translate($source, $target, $segmentText);
//                            $translation = $segmentText; // fake translation
//                            
//                            
//                            $matchTarget= $xliff2x->createElement("target");
//                            $matchTarget->setAttribute("xml:lang", $target);
//                            $matchTarget->appendChild(new \DOMText($translation));
//                            $match->appendChild($matchTarget);
//                            
//                            $matches->appendChild($match);
//                            //$segment->appendChild($matches); 
//                         
//                            
                            $segmentSource = $segment->getElementsByTagName("source")->item(0);
                            
                            $mrk=$xliff2x->createElement("mrk");

                            $children =$segmentSource->childNodes;
                            $placeholder = null;
                            for($i=$children->length-1;$i>=0;$i-- ) {

                                $current=$children->item($i);
                                $mrk->insertBefore($current,$placeholder);
                                $placeholder=$current;
                            }

                            $segmentSource->appendChild($mrk);        
                            $id=null;
                            $mrkId =0;
                            $unitID = $segment->parentNode->getAttribute("id");
                            $xpath = new \DOMXPath($xliff2x);
                            do{
                                $id="mrkID_".$mrkId++;      
                            }
                            while ($xpath->query("//unit[@id='$unitID' and ./segment[@id='$idVal']//mrk[@id='$id']]")->length>0);
                            $mrk->setAttribute("id", $id);
                            $mrk->setAttribute("ref","#".$matchelementID);
                            $segmentSource->appendChild($mrk);
//
//                            
//                            $temp = new \DOMDocument();
//                            $saved = htmlspecialchars_decode($xliff2x->saveXML());
//
//                            $currentid = $unit->getAttribute("id");
//                            $temp->loadXML($saved);
//                            $domXPath = new \DOMXPath($temp);
//                            $sourceMrks = $domXPath->query("//*[local-name()='unit' and @id='$currentid']/*[local-name()='segment']//*[local-name()='match' and @id='Solas_bing{$count}' ]/*[local-name()='source']//*[local-name()='mrk' and (@its:terminology='yes' or @translate='no')]");
//                            $targetMrks = $domXPath->query("//*[local-name()='unit' and @id='$currentid']/*[local-name()='segment']//*[local-name()='match' and @id='Solas_bing{$count}' ]/*[local-name()='target']//*[local-name()='mrk' and (@its:terminology='yes' or @translate='no')]");
//                            for($i=0; $i < $sourceMrks->length; $i++) {
//    //                       
//                              $translation= str_replace($temp->saveXML($targetMrks->item($i)), $temp->saveXML($sourceMrks->item($i)),$translation);
//
//                            }
//                            $sourceMrks = $domXPath->query("//*[local-name()='unit' and @id='$currentid']/*[local-name()='segment']//*[local-name()='match' and @id='Solas_bing{$count}' ]/*[local-name()='source']//*[local-name()='mrk' and not(@its:terminology='yes' or @translate='no')]");
//                            $targetMrks = $domXPath->query("//*[local-name()='unit' and @id='$currentid']/*[local-name()='segment']//*[local-name()='match' and @id='Solas_bing{$count}' ]/*[local-name()='target']//*[local-name()='mrk' and not(@its:terminology='yes' or @translate='no')]");
//                            for($i=0; $i < $sourceMrks->length; $i++) {
//    //                       
//                              $translation= str_replace($temp->saveXML($sourceMrks->item($i)), $temp->saveXML($targetMrks->item($i)),$translation);
//
//                            }
//                            $count++;
//                            $finalTarget= $xliff2x->createElement("target");
//                            $finalTarget->setAttribute("xml:lang", $target);
////                            $idMRK = $doc->createElement("mrk");
////                            $idMRK->appendChild(new \DOMText($translation));
////                            $idMRK->setAttribute("ref", "#".$idVal);
////                            $idMRK->setAttribute("type", "match");
//                            $finalTarget->appendChild(new \DOMText($translation));
//                            $match->appendChild($finalTarget);
//                            $match->removeChild($matchTarget);
                        }
                    }
                } 
            }
            

            
            $doc2->loadXML($xliff1xText);
            $xPath = new \DOMXPath($doc2);
            $altTrans = $xPath->query("//*[local-name()='alt-trans']");
//            $matches = $doc->getElementsByTagName("match");
            $prefix = $xliff2x->lookupPrefix(\IProvider::XMLNS_MTC);    
            $matchId = 0;
            foreach($altTrans as $altTran) {
                $currentSeg = $map[$altTran->getAttribute("mid")];
                $idVal = $currentSeg->getAttribute("id");
//                $matches = $xPath->query("//*[local-name()='matches']",$currentSeg);
//                $matchPrefix = $doc->lookupPrefix("urn:oasis:names:tc:xliff:matches:2.0");
                
                $matches=null;
                if($xliff2x->getElementsByTagName("matches")->length == 0) {
                    $matches = $xliff2x->createElementNS(\IProvider::XMLNS_MTC, "$prefix:matches");
                } else {
                    $matches = $currentSeg->parentNode->getElementsByTagName("matches")->item(0);
                }
                
                //$currentSeg->parentNode->appendChild($matches);
                \ProviderHelper::insertNode($matches, $currentSeg, \ProviderHelper::INSERT_AFTER);
                
                $altSource = $altTran->getElementsByTagName("source")->item(0);
                $altTarget = $altTran->getElementsByTagName("target")->item(0);
                
                  $segmentSourceValue =  strip_tags($doc2->saveXML($altSource));
//                  $segmentSourceValue =  $doc2->saveXML($altSource);
//                   
//                    $pos = strpos($segmentSourceValue, "<source");
//                    if($pos ===0){
//                        $pos = strpos($segmentSourceValue, ">")+1;
//                        $segmentSourceValue=  substr($segmentSourceValue, $pos);
//                        $pos = strrpos($segmentSourceValue, "</source>");
//                        $segmentSourceValue= substr_replace($segmentSourceValue,"",$pos);
//                    }
//                    
                    
                
                $match = $xliff2x->createElementNS(\IProvider::XMLNS_MTC, "$prefix:match");
                $match->setAttribute("id", "mlwlt_".$matchId++);
//                $sourceMrk = $xliff2x->createElement("mrk", $segmentSourceValue);
//                $sourceMrk->setAttribute("ref", "#".$idVal);
//                $sourceMrk->setAttribute("type", "match");
                
                $matchSource = $xliff2x->createElement("source",$segmentSourceValue);
                $matchSource->setAttribute("xml:lang", $sourceLang); 
//                $matchSource->appendChild($sourceMrk);
                $segmentTargetValue =  strip_tags($doc2->saveXML($altTarget));
//                  $segmentSourceValue =  $doc2->saveXML($altTarget);
//                    $pos = strpos($segmentSourceValue, "<target");
//                    if($pos ===0){
//                        $pos = strpos($segmentSourceValue, ">")+1;
//                        $segmentSourceValue=  substr($segmentSourceValue, $pos);
//                        $pos = strrpos($segmentSourceValue, "</target>");
//                        $segmentSourceValue= substr_replace($segmentSourceValue,"",$pos);
//                    }
//                $targetMrk = $xliff2x->createElement("mrk", $segmentSourceValue);
//                $targetMrk->setAttribute("ref", "#".$idVal);
//                $targetMrk->setAttribute("type", "match");
                $matchTarget = $xliff2x->createElement("target",$segmentTargetValue);
//                $matchTarget->appendChild($targetMrk);
                
                $match->appendChild($matchSource);
                $match->appendChild($matchTarget);
                $matches->appendChild($match);

                    
                $attribs = $altTran->attributes;
                foreach($attribs as $attrib) {
                    if(strcasecmp($attrib->name,"mid")==0) continue;
                    if(strcasecmp($attrib->name,"match-quality")==0){
                        $match->setAttribute("matchSuitability", $attrib->value);
                    }
                    else $match->setAttribute(empty ($attrib->prefix)?$attrib->name:"{$attrib->prefix}:{$attrib->name}", $attrib->value );
                }
               
            }
            
        }

        return $xliff2x->saveXML(); 
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


