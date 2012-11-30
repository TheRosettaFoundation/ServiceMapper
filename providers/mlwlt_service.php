<?php
header('Content-Type: text/html; charset=utf-8');
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

require_once '../IProvider.php';
/**
 * mlwlt_service class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class mlwlt_service extends SoapClient implements IProvider {

  private static $classmap = array(
                                    'mlwlt_xliff_mt_echo' => 'mlwlt_xliff_mt_echo',
                                    'mlwlt_xliff_mt_echoResponse' => 'mlwlt_xliff_mt_echoResponse',
                                    'mlwlt_xliff_mt_prepare' => 'mlwlt_xliff_mt_prepare',
                                    'mlwlt_xliff_mt_prepareResponse' => 'mlwlt_xliff_mt_prepareResponse',
                                   );

  public function mlwlt_service($wsdl = "http://mlwlt.moravia.com/mlwlt-service-xliff-mt/mlwlt-service.asmx?WSDL", $options = array()) {
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
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
    return $this->__soapCall('mlwlt_xliff_mt_echo', array($parameters),       array(
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
    return $this->__soapCall('mlwlt_xliff_mt_prepare', array($parameters),       array(
            'uri' => 'http://mlwlt.moravia.com/',
            'soapaction' => ''
           )
      );
  }

  
   public function translateFile($Filetext,$source=null,$target=null){
    $provider = new mlwlt_service();
    $data = new mlwlt_xliff_mt_echo();
    $data->xliff_input = base64_encode($Filetext);
    $result = $provider->mlwlt_xliff_mt_echo($data);
    return base64_decode($result->mlwlt_xliff_mt_echoResult);
  }

    public function getSourceLanguages() {
        return array();
    }

    public function getTargetLanguages() {
        return array();
    }
}
$temp = new mlwlt_service();
echo $temp->translateFile(file_get_contents("/home/manuel/Desktop/ExampleDocs/XLIFF/9769d8e842-WFR.xlf"));

?>
