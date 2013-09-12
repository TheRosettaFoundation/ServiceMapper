<?php namespace moses_en_es_europarlService;
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
//require_once '../IProvider.php';
require_once __DIR__.'/../IProvider.php';
error_reporting(E_ALL^ E_WARNING);


class SequenceFormat {
}

class sequenceInput {
  public $direct_data; // string
  public $usa; // string
  public $sformat; // SequenceFormat
  public $sbegin; // int
  public $send; // int
  public $sprotein; // boolean
  public $snucleotide; // boolean
  public $sreverse; // boolean
  public $slower; // boolean
  public $supper; // boolean
}

class appInputs {
  public $input_direct_data; // string
  public $input_url; // string
  public $language; // language
  public $notokenize; // boolean
  public $nolowercase; // boolean
  public $nodetokenize; // boolean
  public $norecase; // boolean
}



class appResults {
  public $report; // string
  public $detailed_status; // long
  public $output; // string
  public $output_url; // string
}


class RunAndWaitForResponse {
}

class runResponse {
  public $jobId; // string
}

class jobId {
  public $jobId; // string
}

class describeResponse {
  public $description; // string
}

class getLastEventResponse {
  public $event; // string
}

class getResultsInfoResponse {
  public $resultsInfoList; // resultInfo
}

class resultInfo {
  public $name; // string
  public $type; // string
  public $length; // long
  public $size; // long
}

class jobStatus {
  public $jobStatus; // jobStatus
}


class getStatusRequest {
  public $jobId; // jobId
}

class getResultsRequest {
  public $jobId; // jobId
}

class getSomeResultsRequest {
  public $jobId; // jobId
  public $resultName; // string
}

class waitforRequest {
  public $jobId; // jobId
}

class terminateRequest {
  public $jobId; // jobId
}

class clearRequest {
  public $jobId; // jobId
}

class describeRequest {
}

class getLastEventRequest {
  public $jobId; // jobId
}

class getResultsInfoRequest {
  public $jobId; // jobId
}

/**
 * moses_en_es_europarlService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class moses_en_es_europarlService extends \SoapClient implements \IProvider{

     public function isEnabled(){
        return false;
    }
    
    
  private static $classmap = array(
                                    'SequenceFormat' => 'moses_en_es_europarlService\SequenceFormat',
                                    'sequenceInput' => 'moses_en_es_europarlService\sequenceInput',
                                    'appInputs' => 'moses_en_es_europarlService\appInputs',
                                    'language' => 'moses_en_es_europarlService\language',
                                    'appResults' => 'moses_en_es_europarlService\appResults',
                                    'RunAndWaitFor' => 'moses_en_es_europarlService\RunAndWaitFor',
                                    'RunAndWaitForResponse' => 'moses_en_es_europarlService\RunAndWaitForResponse',
                                    'runResponse' => 'moses_en_es_europarlService\runResponse',
                                    'jobId' => 'moses_en_es_europarlService\jobId',
                                    'describeResponse' => 'moses_en_es_europarlService\describeResponse',
                                    'getLastEventResponse' => 'moses_en_es_europarlService\getLastEventResponse',
                                    'getResultsInfoResponse' => 'moses_en_es_europarlService\getResultsInfoResponse',
                                    'resultInfo' => 'moses_en_es_europarlService\resultInfo',
                                    'jobStatus' => 'moses_en_es_europarlService\jobStatus',
                                    'jobStatus' => 'moses_en_es_europarlService\jobStatus',
                                    'getStatusRequest' => 'moses_en_es_europarlService\getStatusRequest',
                                    'getResultsRequest' => 'moses_en_es_europarlService\getResultsRequest',
                                    'getSomeResultsRequest' => 'moses_en_es_europarlService\getSomeResultsRequest',
                                    'waitforRequest' => 'moses_en_es_europarlService\waitforRequest',
                                    'terminateRequest' => 'moses_en_es_europarlService\terminateRequest',
                                    'clearRequest' => 'moses_en_es_europarlService\clearRequest',
                                    'describeRequest' => 'moses_en_es_europarlService\describeRequest',
                                    'getLastEventRequest' => 'moses_en_es_europarlService\getLastEventRequest',
                                    'getResultsInfoRequest' => 'moses_en_es_europarlService\getResultsInfoRequest',
                                   );

  public function __construct($wsdl = "http://www.cngl.ie/panacea-soaplab2-axis/typed/services/panacea.moses_en_es_europarl?wsdl", $options = array()) {
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    parent::__construct($wsdl, $options);
  }

  /**
   * starts a job and returns its job identifier 
   *
   * @param appInputs $parameters
   * @return runResponse
   */
  public function run(appInputs $parameters) {
    return $this->__soapCall('run', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   * starts a job, waits until it is completed and
				returns the job results 
   *
   * @param RunAndWaitFor $parameters
   * @return RunAndWaitForResponse
   */
  public function runAndWaitFor(appInputs $parameters) {
    return $this->__soapCall('runAndWaitFor', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   * returns status of a given job 
   *
   * @param getStatusRequest $parameters
   * @return jobStatus
   */
  public function getStatus(getStatusRequest $parameters) {
    return $this->__soapCall('getStatus', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   * terminates the specified job 
   *
   * @param terminateRequest $parameters
   * @return void
   */
  public function terminate(terminateRequest $parameters) {
    return $this->__soapCall('terminate', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   * informs server that resources for the specified job can be cleared 
   *
   * @param clearRequest $parameters
   * @return void
   */
  public function clear(clearRequest $parameters) {
    return $this->__soapCall('clear', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   * waits until the specified job terminates 
   *
   * @param waitforRequest $parameters
   * @return void
   */
  public function waitfor(waitforRequest $parameters) {
    return $this->__soapCall('waitfor', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   * returns all results of a given job 
   *
   * @param getResultsRequest $parameters
   * @return appResults
   */
  public function getResults(getResultsRequest $parameters) {
    return $this->__soapCall('getResults', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   * returns all results of a given job 
   *
   * @param getSomeResultsRequest $parameters
   * @return appResults
   */
  public function getSomeResults(getSomeResultsRequest $parameters) {
    return $this->__soapCall('getSomeResults', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   * returns a structured description of the service, in xml 
   *
   * @param describeRequest $parameters
   * @return describeResponse
   */
  public function describe(describeRequest $parameters) {
    return $this->__soapCall('describe', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param getLastEventRequest $parameters
   * @return getLastEventResponse
   */
  public function getLastEvent(getLastEventRequest $parameters) {
    return $this->__soapCall('getLastEvent', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param getResultsInfoRequest $parameters
   * @return getResultsInfoResponse
   */
  public function getResultsInfo(getResultsInfoRequest $parameters) {
    return $this->__soapCall('getResultsInfo', array($parameters),       array(
            'uri' => 'http://soaplab.org/panacea/moses_en_es_europarl',
            'soapaction' => ''
           )
      );
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
                 $pRec->setAttribute("its:tool", "Panacea");
                 $pRec->setAttribute("its:orgRef", "http://www.cngl.ie/");
                 $pRec->setAttribute("its:provRef", "http://www.cngl.ie/panacea-soaplab2-axis/typed/services/panacea.moses_en_es_europarl"); 
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

     
                        $translation = $this->translate($sourceLanguage,$targetLanguage,$text);
//                        $translation = $text; // un commont for fake translation
                        
                        $alt_trans = $doc->createElement("alt-trans");
                        $alt_trans->setAttribute("origin", "Panacea");
                        //$alt_trans->setAttribute("its:annotatorsRef", "mtconfidence|http://api.microsofttranslator.com/V2/Http.svc/Translate");
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
                 $pRec->setAttribute("its:tool", "Panacea");
                 $pRec->setAttribute("its:orgRef", "http://www.cngl.ie/");
                 $pRec->setAttribute("its:provRef", "http://www.cngl.ie/panacea-soaplab2-axis/typed/services/panacea.moses_en_es_europarl"); 
                 $pRecs->appendChild($pRec);
                 $fileElements = $doc->getElementsByTagName("file");
                 foreach ($fileElements as $fileElement){
                    $placeholder = $doc->getElementsByTagName("group")->item(0);
                    if(is_null($placeholder)) {
                        $placeholder = $doc->getElementsByTagName("unit")->item(0);
                    }
                    $fileElement->insertBefore($doc->importNode($pRecs,true), $placeholder);
                 }
//                $fileElement->appendChild($pRecs);

                if($segments = $doc->getElementsByTagName("segment")) {
                    foreach($segments as $segment ){  
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
                            
                            $matches = $doc->createElement("matches");
                            $match = $doc->createElement("match");
                            $match->setAttribute("its:provenanceRecordsRef", "#pr$i");
                            $matchSource= $doc->createElement("source");
                            $matchSource->setAttribute("xml:lang", $source);
                            $matchSource->appendChild(new \DOMText($segmentText));
                            $match->appendChild($matchSource);
                            
                                                        
                            $translation = $this->translate($source, $target, $segmentText);
//                            $translation = $segmentText; //fake translation
                                                        
                            
                            $matchTarget= $doc->createElement("target");
                            $matchTarget->setAttribute("xml:lang", $target);
                            $matchTarget->appendChild(new \DOMText($translation));
                            $match->appendChild($matchTarget);
                            
                            $matches->appendChild($match);
                            $segment->appendChild($matches);                            
                            
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
                            $finalTarget->appendChild(new \DOMText($translation));
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
  
  public function translate($source,$target,$text){
    $parameters = new appInputs();
    $parameters->input_direct_data=$text;
    $parameters->language = $source;
    $parameters->nodetokenize = false;
    $parameters->nolowercase = false;
    $parameters->norecase = true;
    $parameters->notokenize=true;
    $result = $this->runAndWaitFor($parameters);
    
    return $result->output;
  }
  
  public function getTargetLanguages(){
      return array("es");
  }
  public function getSourceLanguages(){
      return array("en");
  }

}
//$test = new moses_en_es_europarlService();
//echo $test->translateFile(file_get_contents("/home/manuel/dev/ITS-2.0-Testsuite/its2.0/xliffsamples/roundtrip-example/EXe-xliff-prov-rt-1-post-term.xlf"),"en","es");
//echo $test->translate("en", "es",  "I am happy. Are you happy too?");
