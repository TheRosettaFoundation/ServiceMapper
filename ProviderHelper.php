<?php

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
        
        //$transUnits = $file->getElementsByTagName("trans-unit"); 
        if($altTransUnits = $file2->getElementsByTagName("alt-trans")) {
        
        //foreach($transUnits as $transUnit ){
            foreach($altTransUnits as $altTransUnit) {    
                
                $alt_trans = $file->createElement("alt-trans");
                $alt_trans->appendChild($file->createTextNode($altTransUnits->item(0)->nodeValue));
                //$alt_trans->appendChild($altTarget); 
                
            }
            
            if($data = $file->saveXML()) {
                 return $data;
            } else {
                echo "Failed to dump XML tree to string";
            }            
        //}
        } else {
            echo "No alt-trans elements found";
        }
        
    }    
}
