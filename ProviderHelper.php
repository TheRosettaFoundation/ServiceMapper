<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProviderHelper
 *
 * @author sean
 */
class ProviderHelper {
    private static function autoRequire(array $providers,$root="providers") {
        foreach($providers as $provider){
            require_once ProviderHelper::getSetting($root).$provider.".php";
        }
    }
    
    private static function readProviders(){
        $temp= scandir(ProviderHelper::getSetting("providers"));
        $ret=array();
        foreach ($temp as $provider) {
            if($provider!="."&&$provider!="..") $ret[]=substr($provider, 0, sizeof($provider)-5);
        }
        return $ret;
    }
    
    public static function getSetting($setting,$file="config.ini"){
        $config = parse_ini_file($file);
        return $config[$setting];	
    }
    
    /**
     *
     * @return IProvider 
     */
    public static function loadAllProviders(){
        $providerNames = ProviderHelper::readProviders();
        ProviderHelper::autoRequire($providerNames);
        $providers = array();
        foreach($providerNames as $name){
            $providers[]=new $name();
        }
        return $providers;
    }
    
    public static function enrichFile( &$file, array $array) {
        foreach ($array as $xliff){
            ProviderHelper::mergeFile($file, $xliff);
        }
//        $file = $array[0];
    }
    
    public static function mergeFile(&$file, &$file2) { //private
        //merge altrans from file2  into file per transunit 
        
        $transUnits = $file->getElementsByTagName("trans-unit"); 
        $altTransUnits = $file2->getElementsByTagName("alt-trans");   
        
        for($i=0; $i < $altTransUnits->length; $i++) {
            
            $transUnit = $transUnits->item($i);
            $altTransUnit = $altTransUnits->item($i);
                
            if($altTransUnit->hasChildNodes()) {

                $altTrans = $file->createElement("alt-trans");
                $altTransSource = $file->createElement("source");
                $altTransTarget = $file->createElement("target");

                $children = $altTransUnit->childNodes;

                foreach($children as $child) {
                    if($child->nodeName == "source") {
                        //$sourceAltTransValue = $child->nodeValue;
                        $altTransSource->appendChild($file->createTextNode($child->nodeValue));
                        //$x = $child->getAttribute("xml:lang");
                        if($child->hasAttributeNS("xml","lang")) {
                            //$attributeValue = $child->getAttributeNS("xml","lang");
                            $altTransSource->setAttributeNS("xml","lang", $child->getAttributeNS("xml","lang"));
                        }                            

                    }elseif($child->nodeName == "target") {
                        //$targetAltTransValue = $child->nodeValue;
                        $altTransTarget->appendChild($file->createTextNode($child->nodeValue));

                        if($child->hasAttributeNS("xml","lang")) {
                            //$attributeValue = $child->getAttributeNS("xml","lang");
                            $altTransTarget->setAttributeNS("xml","lang", $child->getAttributeNS("xml","lang"));
                        }                            
                    }
                }

                $altTrans->appendChild($altTransSource);
                $altTrans->appendChild($altTransTarget);
                $transUnit->appendChild($altTrans);

                echo "has child nodes <br>";
            }
        }
        
        if($data = $file->saveXML()) {
            return $data;
        } else {
            echo "Failed to dump XML tree to string";
        }            
    }
}

$file = "uploads/test1.xlf";
$file2 = "uploads/test2.xlf";
$doc = new DOMDocument();
$doc2 = new DOMDocument();
if($doc->load($file)) {
    if($doc2->load($file2)) {
        echo "<br>xml tree dump: ".ProviderHelper::mergeFile($doc, $doc2);        
    }
} 