<?php namespace translate_main_devService;
//require_once '../IProvider.php';
require_once 'IProvider.php';
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
  public $sourceLanguage; // sourceLanguage
  public $targetLanguage; // targetLanguage
}

class appResults {
  public $report; // string
  public $detailed_status; // long
  public $output; // string
  public $output_url; // string
}

class RunAndWaitFor {
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
 * translate_main_devService class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class translate_main_devService extends \SoapClient implements \IProvider {
     public function isEnabled(){
        return false;
    }

  private static $classmap = array(
                                    'SequenceFormat' => 'translate_main_devService\SequenceFormat',
                                    'sequenceInput' => 'translate_main_devService\sequenceInput',
                                    'appInputs' => 'translate_main_devService\appInputs',
                                    'sourceLanguage' => 'translate_main_devService\sourceLanguage',
                                    'targetLanguage' => 'translate_main_devService\targetLanguage',
                                    'appResults' => 'translate_main_devService\appResults',
                                    'RunAndWaitFor' => 'translate_main_devService\RunAndWaitFor',
                                    'RunAndWaitForResponse' => 'translate_main_devService\RunAndWaitForResponse',
                                    'runResponse' => 'translate_main_devService\runResponse',
                                    'jobId' => 'translate_main_devService\jobId',
                                    'describeResponse' => 'translate_main_devService\describeResponse',
                                    'getLastEventResponse' => 'translate_main_devService\getLastEventResponse',
                                    'getResultsInfoResponse' => 'translate_main_devService\getResultsInfoResponse',
                                    'resultInfo' => 'translate_main_devService\resultInfo',
                                    'jobStatus' => 'translate_main_devService\jobStatus',
                                    'getStatusRequest' => 'translate_main_devService\getStatusRequest',
                                    'getResultsRequest' => 'translate_main_devService\getResultsRequest',
                                    'getSomeResultsRequest' => 'translate_main_devService\getSomeResultsRequest',
                                    'waitforRequest' => 'translate_main_devService\waitforRequest',
                                    'terminateRequest' => 'translate_main_devService\terminateRequest',
                                    'clearRequest' => 'translate_main_devService\clearRequest',
                                    'describeRequest' => 'translate_main_devService\describeRequest',
                                    'getLastEventRequest' => 'translate_main_devService\getLastEventRequest',
                                    'getResultsInfoRequest' => 'translate_main_devService\getResultsInfoRequest',
                                   );

  public function __construct($wsdl = "http://srv-cngl.computing.dcu.ie/mlwlt/typed/services/mlwlt.translate_main_dev?wsdl", $options = array()) {
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
  public function runAndWaitFor(RunAndWaitFor $parameters) {
    return $this->__soapCall('runAndWaitFor', array($parameters),       array(
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
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
            'uri' => 'http://soaplab.org/mlwlt/translate_main_dev',
            'soapaction' => ''
           )
      );
  }
 
  public function translateFile($Filetext,$source=null,$target=null){
    $data = new appInputs();
    $data->input_direct_data =$Filetext;
    if(strpos($source, "-")!==false){
        $source=explode("-", $source);
        $source=$source[0];
    }
    $data->sourceLanguage=$source;
    if(strpos($target, "-")!==false){
        $target=explode("-", $target);
        $target=$target[0];
    }
    $data->targetLanguage=$target;
    $result =$Filetext;
    try{
    $jobid = self::run($data);
    $waitFor =new waitforRequest();
    $waitFor->jobId=$jobid;
    self::waitfor($waitFor);
    $resultRequest= new getResultsRequest();
    $resultRequest->jobId=$jobid;
    $result = self::getResults($resultRequest);
    $infoRequest = new getResultsInfoRequest();
    $infoRequest->jobId=$jobid;
    $info = self::getResultsInfo($infoRequest);
    }  catch (Exception $e){
    print_r($e);
}
    return $result->output;
  }
  
  public function getSourceLanguages() {
        return array("en");
    }

    public function getTargetLanguages() {
        return array("fr","es");
    }

}

//$temp = new translate_main_devService();
//echo $temp->translateFile(file_get_contents("/home/sean/Desktop/Example1_HTML5.html.xlf"),"en","fr");
