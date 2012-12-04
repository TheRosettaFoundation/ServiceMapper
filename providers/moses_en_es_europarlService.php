<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
require_once 'IProvider.php';


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
class moses_en_es_europarlService extends SoapClient implements IProvider{

  private static $classmap = array(
                                    'SequenceFormat' => 'SequenceFormat',
                                    'sequenceInput' => 'sequenceInput',
                                    'appInputs' => 'appInputs',
                                    'language' => 'language',
                                    'appResults' => 'appResults',
                                    'RunAndWaitFor' => 'RunAndWaitFor',
                                    'RunAndWaitForResponse' => 'RunAndWaitForResponse',
                                    'runResponse' => 'runResponse',
                                    'jobId' => 'jobId',
                                    'describeResponse' => 'describeResponse',
                                    'getLastEventResponse' => 'getLastEventResponse',
                                    'getResultsInfoResponse' => 'getResultsInfoResponse',
                                    'resultInfo' => 'resultInfo',
                                    'jobStatus' => 'jobStatus',
                                    'jobStatus' => 'jobStatus',
                                    'getStatusRequest' => 'getStatusRequest',
                                    'getResultsRequest' => 'getResultsRequest',
                                    'getSomeResultsRequest' => 'getSomeResultsRequest',
                                    'waitforRequest' => 'waitforRequest',
                                    'terminateRequest' => 'terminateRequest',
                                    'clearRequest' => 'clearRequest',
                                    'describeRequest' => 'describeRequest',
                                    'getLastEventRequest' => 'getLastEventRequest',
                                    'getResultsInfoRequest' => 'getResultsInfoRequest',
                                   );

  public function moses_en_es_europarlService($wsdl = "http://www.cngl.ie/panacea-soaplab2-axis/typed/services/panacea.moses_en_es_europarl?wsdl", $options = array()) {
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

        $doc = new DOMDocument();
        
        if($doc->loadXML($fileText)) {
            if($transUnits = $doc->getElementsByTagName("trans-unit")) {
                foreach($transUnits as $transUnit ){  
                    if($transUnit->hasAttribute("translate") && $transUnit->getAttribute("translate")=="no") continue;
                    $source = $transUnit->getElementsByTagName("source");
                    $text = $source->item(0)->nodeValue;
                    $translation = $this->translate($sourceLanguage,$targetLanguage,$text);
                    $translation = strip_tags($translation);
                    $alt_trans = $doc->createElement("alt-trans");
                    $alt_trans->setAttribute("origin", "Panacea");
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
  
  
  public function translate($source,$target,$text){
    $parameters = new appInputs();
    $parameters->input_direct_data=$text;
    $parameters->language = $source;
    $parameters->nodetokenize = false;
    $parameters->nolowercase = false;
    $parameters->norecase = true;
    $parameters->notokenize=false;
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
//echo $test->translateFile("/home/manuel/Desktop/ExampleDocs/lucia/Symposium3.xlf","en","es");
////echo $test->translate("en", "es",  "I am happy. Are you happy too?");
