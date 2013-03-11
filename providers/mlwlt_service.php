<?php

namespace mlwlt_service;

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

require_once 'IProvider.php';
//require_once '../IProvider.php';

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
        $data = new mlwlt_xliff_mt_prepare();
        $data->xliff_input = base64_encode($Filetext);
        $result = self::mlwlt_xliff_mt_prepare($data);
        return base64_decode($result->mlwlt_xliff_mt_prepareResult);
    }

    public function getSourceLanguages() {
        return array("en");
    }

    public function getTargetLanguages() {
        return array("fr", "es");
    }

}

//$temp = new mlwlt_service();
//echo $temp->translateFile(file_get_contents("/home/sean/Desktop/EXc-xliff-prov-rt-1-post-seg.xlf"));
//$content = file_get_contents("/home/sean/Desktop/lucia/simple_short.xlf");
////$content = mb_convert_encoding($content, 'UTF-16', 'UTF-8');
//echo $content;
//

