<?php namespace Bing;
header('Content-Type: text/xml; charset=utf-8');
mb_internal_encoding('UTF-8');

error_reporting(E_ALL^ E_WARNING);
require_once __DIR__.'/../IProvider.php';
//require_once '../IProvider.php';

class Bing extends \SoapClient implements \IProvider {
    
    public function __construct() {
        
    }

    public function isEnabled(){
        return false;
    }
    public function getSourceLanguages() {
        return array("ar","fi","it","ru","bg","fr","jp","sk","ca","de","ko","sl",
            "zh-CN","zh-TW","ht","lt","sv","cs","he","nn","th","da","hi","fa","tr"
            ,"nl","hmn","pl","uk","en","hu","pt","vi","et","id","ro","es");
    }

    public function getTargetLanguages() {
          return array("ar","fi","it","ru","bg","fr","jp","sk","ca","de","ko","sl",
            "zh-CN","zh-TW","ht","lt","sv","cs","he","nn","th","da","hi","fa","tr"
            ,"nl","hmn","pl","uk","en","hu","pt","vi","et","id","ro","es");
    }
    
    public function translateFile($fileText, $sourceLanguage, $targetLanguage) {
        $doc = new \DOMDocument();
        if($doc->loadXML($fileText)) {
            if($version = $doc->firstChild->getAttribute("version")) {
                if(strpos($version, "1.") === 0) {
                    return $this->translateXLIFFFile1x($doc, $fileText,$sourceLanguage, $targetLanguage);
                } else if(strcasecmp($version,"2.0") == 0) {
                    return $this->translateXLIFFFile2($doc, $fileText,$sourceLanguage, $targetLanguage);
                }
            }
        }
        return $fileText;
   }  
   
    private function translateXLIFFFile1x(&$doc, $Filetext,$sourceLanguage=null,$targetLanguage=null)
    {

             if($transUnits = $doc->getElementsByTagName("trans-unit")) {
                 $pRecs = $doc->getElementsByTagName("provenanceRecords");
                 $map = array();
                 foreach($pRecs as $pRec){
                     $current =$pRec->getAttribute("xml:id");
                     $map[$current]=$pRec;
                 }
                 $i = 1;
                    while(isset ($map["pr$i"])) $i++;
//                 unset ($map);
                 $pRecs=$doc->createElement("its:provenanceRecords");
                 $pRecs->setAttribute("xml:id", "pr$i");
                 $pRec=$doc->createElement("its:provenanceRecord");
                 $pRec->setAttribute("its:tool", "bingtranslate");
                 $pRec->setAttribute("its:orgRef", "http://www.microsoft.com");
                 $pRec->setAttribute("its:provRef", "http://api.microsofttranslator.com/V2/Http.svc/Translate");
                 $pRecs->appendChild($pRec);
                 $head = $doc->getElementsByTagName("header")->item(0);
                 $head->appendChild($pRecs);
                 foreach($transUnits as $transUnit ){                        
                     if($transUnit->hasAttribute("translate") && $transUnit->getAttribute("translate")=="no") continue;
                     $segsource = $transUnit->getElementsByTagName("seg-source");
                     $source = $transUnit->getElementsByTagName("source");
                     $segs = array();
                     if($segsource->length==0||!($current=simplexml_import_dom ($segsource->item(0)))) {
                        $current = simplexml_import_dom ($source->item(0));
                     }
   
                     $mrks = $current->xpath("*[@mtype='seg']");

                     if(is_array($mrks)&&!empty($mrks)){

                     $segs=$mrks;
                    }
                    else $segs[]= $current;

                    foreach ($segs as $seg){
                        $text = $seg->asXML();
                        if(strcasecmp($seg->getName(),"mrk")==0){ 
                            
                            $pos = strpos($text, "<mrk");
                            if($pos ===0){
                                $pos = strpos($text, ">")+1;
                                if(strpos($text, "mtype='seg'")<$pos ){
                                    $text=  substr($text, $pos);
                                    $pos = strrpos($text, "</mrk>");
                                    $text= substr_replace($text,"",$pos);
                                }
                            }
                        
                        } else {
                            
                            $pos = strpos($text, "<".$seg->getName());
                            if($pos ===0){
                                $pos = strpos($text, ">")+1;
                                $text=  substr($text, $pos);
                                $pos = strrpos($text, "</".$seg->getName().">");
                                $text= substr_replace($text,"",$pos);
                            }
                        }

     
                        //$translation = $this->translate($sourceLanguage,$targetLanguage,$text);
                        $translation = $text; // un comment for fake translation
                        
                        
                        $alt_trans = $doc->createElement("alt-trans");
                        $alt_trans->setAttribute("origin", "bingtranslate");
                        $alt_trans->setAttribute("its:annotatorsRef", "mtconfidence|http://api.microsofttranslator.com/V2/Http.svc/Translate");
                        $alt_trans->setAttribute("its:provenanceRecordsRef", "#pr$i");
                        if(isset($seg['mid']) )  $alt_trans->setAttribute("mid", $seg['mid']);
                          
                        $altSource= $doc->createElement("source");
                        $altSource->setAttribute("xml:lang", $sourceLanguage);
                        $altSource->appendChild(new \DOMText($text));
                        $alt_trans->appendChild($altSource);
                        $altTarget = $doc->createElement("target");
                        $altTarget->setAttribute("xml:lang", $targetLanguage);
                        $altTarget->appendChild($doc->createTextNode($translation));
                        $alt_trans->appendChild($altTarget);
                        $transUnit->appendChild($alt_trans);
//                        $finalTarget = $doc->createElement("target");
//                        $finalTarget->setAttribute("xml:lang", $targetLanguage);
                        $currentid = $transUnit->getAttribute("id");

                        $temp = new \DOMDocument();
                        $saved = htmlspecialchars_decode($doc->saveXML());
                        
                        $temp->loadXML($saved);
                         $domXPath = new \DOMXPath($temp);
                        $sourceMrks = $domXPath->query("//*[local-name()='trans-unit' and @id='$currentid']/*[local-name()='alt-trans' and @mid={$seg['mid']}]/*[local-name()='source']/*[local-name()='mrk' and (@mtype='term' or @mtype='protected' or @mtype='phrase')]");
                        $targetMrks = $domXPath->query("//*[local-name()='trans-unit' and @id='$currentid']/*[local-name()='alt-trans'and @mid={$seg['mid']}]/*[local-name()='target']/*[local-name()='mrk' and (@mtype='term' or @mtype='protected' or @mtype='phrase')]");
                        for($i=0; $i < $sourceMrks->length; $i++) {
//                       
                          $translation= str_replace($temp->saveXML($targetMrks->item($i)), $temp->saveXML($sourceMrks->item($i)),$translation);
                         
                        }
                        $sourceMrks = $domXPath->query("//*[local-name()='trans-unit' and @id='$currentid']/*[local-name()='alt-trans' and @mid={$seg['mid']}]/*[local-name()='source']/*[local-name()='mrk' and not(@mtype='term' or @mtype='protected' or @mtype='phrase')]");
                        $targetMrks = $domXPath->query("//*[local-name()='trans-unit' and @id='$currentid']/*[local-name()='alt-trans'and @mid={$seg['mid']}]/*[local-name()='target']/*[local-name()='mrk' and not(@mtype='term' or @mtype='protected' or @mtype='phrase')]");
                        for($i=0; $i < $sourceMrks->length; $i++) {
//                       
                          $translation= str_replace($temp->saveXML($sourceMrks->item($i)), $temp->saveXML($targetMrks->item($i)),$translation);
                         
                        }
                        
                        $finalTarget = $doc->createElement("target");
                        $finalTarget->setAttribute("xml:lang", $targetLanguage);
                        $finalTarget->appendChild($doc->createTextNode($translation));
                        $alt_trans->appendChild($doc->importNode($finalTarget));
                        $alt_trans->removeChild($altTarget);
                     }
                 }  
             }                     

         $doc->formatOutput = true;
         if($data = htmlspecialchars_decode($doc->saveXML())) {
              return $data;
         } else {
             echo "Failed to dump XML tree to string";
         }
        
    }
            
    private function translateXLIFFFile2(&$doc, $Filetext,$source=null,$target=null)
    {
        
        if($units = $doc->getElementsByTagName("unit")) {

            foreach($units as $unit) {
                $count=0;
                $translateUnit = ($unit->hasAttribute("translate") && $unit->getAttribute("translate")=="yes") || !$unit->hasAttribute("translate");
//                if($segment->hasAttribute("translate") && $segment->getAttribute("translate")=="no") continue;

                $pRecs = $doc->getElementsByTagName("provenanceRecords");
                $map = array();
                foreach($pRecs as $pRec){
                    $current =$pRec->getAttribute("xml:id");
                    $map[$current]=$pRec;
                }
                $i = 1;
                   while(isset ($map["pr$i"])) $i++;
            //                 unset ($map);
                $pRecs=$doc->createElement("its:provenanceRecords");
                $pRecs->setAttribute("xml:id", "pr$i");
                $pRec=$doc->createElement("its:provenanceRecord");
                $pRec->setAttribute("its:tool", "bingtranslate");
                $pRec->setAttribute("its:orgRef", "http://www.microsoft.com");
                $pRec->setAttribute("its:provRef", "http://api.microsofttranslator.com/V2/Http.svc/Translate");
                $pRecs->appendChild($pRec);
                $fileElements = $doc->getElementsByTagName("file");
                 foreach ($fileElements as $fileElement){
                    $placeholder = $doc->getElementsByTagName("group")->item(0);
                    if(is_null($placeholder)) {
                        $placeholder = $doc->getElementsByTagName("unit")->item(0);
                    }
                    $fileElement->insertBefore($doc->importNode($pRecs,true), $placeholder);
                 }

                if($segments = $doc->getElementsByTagName("segment")) {
                    $segCount= 0;
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
                            $matches = null;
                            $unitElement = $doc->getElementsByTagName("unit")->item(0);
                            
                            $prefix = $doc->lookupPrefix(\IProvider::XMLNS_MTC);     
                            if($doc->getElementsByTagName("matches")->length == 0) {
                                $matches = $doc->createElementNS(\IProvider::XMLNS_MTC, "$prefix:matches");
                            } else {
                                $matches = $unit->getElementsByTagName("matches")->item(0);
                            }
                 
                            $match = $doc->createElementNS(\IProvider::XMLNS_MTC, "$prefix:match");
                            $match->setAttribute("its:provenanceRecordsRef", "#pr$i");
                            $matchSource= $doc->createElement("source");
                            $matchSource->setAttribute("xml:lang", $source);
                            $idMRK = $doc->createElement("mrk");
                            $idMRK->appendChild(new \DOMText($segmentText));
                            $idMRK->setAttribute("ref", "#".$idVal);
                            $idMRK->setAttribute("type", "match");
                            $matchSource->appendChild($idMRK);
                            $match->appendChild($matchSource);
                            
                            
                            //$translation = $this->translate($source, $target, $segmentText);
                            $translation = $segmentText; // fake translation
                            
                            
                            $matchTarget= $doc->createElement("target");
                            $matchTarget->setAttribute("xml:lang", $target);
                            $matchTarget->appendChild(new \DOMText($translation));
                            $match->appendChild($matchTarget);
                            
                            $matches->appendChild($match);
                            //$segment->appendChild($matches); 
                            $unitElement->appendChild($matches);

                            
                            $temp = new \DOMDocument();
                            $saved = htmlspecialchars_decode($doc->saveXML());

                            $currentid = $unit->getAttribute("id");
                            $temp->loadXML($saved);
                            $domXPath = new \DOMXPath($temp);
                            $sourceMrks = $domXPath->query("//*[local-name()='unit' and @id='$currentid']/*[local-name()='segment']//*[local-name()='match' and @id='Solas_bing{$count}' ]/*[local-name()='source']//*[local-name()='mrk' and (@its:terminology='yes' or @translate='no')]");
                            $targetMrks = $domXPath->query("//*[local-name()='unit' and @id='$currentid']/*[local-name()='segment']//*[local-name()='match' and @id='Solas_bing{$count}' ]/*[local-name()='target']//*[local-name()='mrk' and (@its:terminology='yes' or @translate='no')]");
                            for($i=0; $i < $sourceMrks->length; $i++) {
    //                       
                              $translation= str_replace($temp->saveXML($targetMrks->item($i)), $temp->saveXML($sourceMrks->item($i)),$translation);

                            }
                            $sourceMrks = $domXPath->query("//*[local-name()='unit' and @id='$currentid']/*[local-name()='segment']//*[local-name()='match' and @id='Solas_bing{$count}' ]/*[local-name()='source']//*[local-name()='mrk' and not(@its:terminology='yes' or @translate='no')]");
                            $targetMrks = $domXPath->query("//*[local-name()='unit' and @id='$currentid']/*[local-name()='segment']//*[local-name()='match' and @id='Solas_bing{$count}' ]/*[local-name()='target']//*[local-name()='mrk' and not(@its:terminology='yes' or @translate='no')]");
                            for($i=0; $i < $sourceMrks->length; $i++) {
    //                       
                              $translation= str_replace($temp->saveXML($sourceMrks->item($i)), $temp->saveXML($targetMrks->item($i)),$translation);

                            }
                            $count++;
                            $finalTarget= $doc->createElement("target");
                            $finalTarget->setAttribute("xml:lang", $target);
                            $idMRK = $doc->createElement("mrk");
                            $idMRK->appendChild(new \DOMText($translation));
                            $idMRK->setAttribute("ref", "#".$idVal);
                            $idMRK->setAttribute("type", "match");
                            $finalTarget->appendChild($idMRK);
                            $match->appendChild($finalTarget);
                            $match->removeChild($matchTarget);
                        }
                    }
                }
            }
            $doc->formatOutput = true;
            if($data = htmlspecialchars_decode($doc->saveXML())) {
                 return $data;
            } else {
                echo "Failed to dump XML tree to string";
            }
        }
        
        return $Filetext;
    }
   

    public  function translate($source ,$target,$text) {        
        //Source and target languages must be in language code form
        $b_slang=strtolower($source);
        $b_tlang=strtolower($target);
        $language_codes = parse_ini_file(__DIR__."/../demolangs.ini");
        $language_names = parse_ini_file(__DIR__."/../languages.ini");
        
        if(!isset($language_codes[$b_slang])) {
            $b_slang = $language_names[strtoupper($b_slang)];
        }

        if(!isset($language_codes[$b_tlang])) {
            $b_tlang = $language_names[strtoupper($b_tlang)];
        }
//        echo "$source ,$target,$b_slang,$b_tlang";
        //Proxy switch Naoto 2010-03-20
        $proxy = false; // test
        if ($proxy){
                $config = parse_ini_file(__DIR__."/../config.ini");
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
   
//        echo 'http://api.microsofttranslator.com/V2/Http.svc/Translate?appId=B762C414CF08D83A6715EEB0171C4BF6E1AF0490&text='.$query;
        $sFile = file_get_contents('http://api.microsofttranslator.com/V2/Http.svc/Translate?appId=B762C414CF08D83A6715EEB0171C4BF6E1AF0490&text='.$query, FILE_TEXT, $cxContext);
//        return mb_convert_encoding($sFile, 'unicode');
        return htmlspecialchars_decode(strip_tags($sFile));		
    } 
}
//$bing= new Bing();
//
//echo $bing->translateFile(file_get_contents(__DIR__."/../test/newtest.xlf"), "en","es");
//"__DIR__."/../uploads/EXe-xliff-prov-rt-1-post-term.xlf""

//echo $bing->translateFile(file_get_contents("/home/manuel/Downloads/xliff2Test.xlf"), "en","es");