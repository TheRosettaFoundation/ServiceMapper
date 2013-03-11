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
            if($provider!="."&&$provider!=".." && strncmp($provider, ".", 1)) $ret[]=substr($provider, 0, sizeof($provider)-5);
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
            $name = "$name\\$name";
            $providers[]=new $name;
        }
        return $providers;
    }
    
    public static function enrichFile( &$file, array $array) {
        foreach ($array as $xliff){
            if($xliff=="" || is_null($xliff)) continue;
            ProviderHelper::mergeFile($file, $xliff);
        }
//        $file = $array[0];
    }
    public static function updateProvenance(&$fileText, &$fileText2){
        
        $file = new DOMDocument();
        $file->loadXML($fileText);
        $file2 = new DOMDocument();
        $file2->loadXML($fileText2);
        $head = $file->getElementsByTagName("header")->item(0);
        $pRecs = $file->getElementsByTagName("provenanceRecords"); 
        $pRecs2 = $file2->getElementsByTagName("provenanceRecords"); 
        $map = array();
        foreach($pRecs as $pRec){
            $current =$pRec->getAttribute("xml:id");
            $map[$current]=$pRec;
        }
        foreach($pRecs2 as $pRec){
            $currentID =$pRec->getAttribute("xml:id");
            if(isset($map[$currentID])){
                $current = $file2->saveXML($pRec);
                $old =$file->saveXML($map[$currentID]);                
                if($current==$old) continue;
                else{
                    $i = 1;
                    while(isset ($map["pr$i"])) $i++;
                    $fileText2=str_replace("#$currentID","#pr$i", $fileText2);
                    $pRec->setAttribute("xml:id","pr$i");
                    $map["pr$i"]=$pRec;
                    $head->appendChild($file->importNode($pRec,true));
                    
                }
                
            }else{
                $head->appendChild($file->importNode($pRec,true));    
            }
        }
        $fileText =$file->saveXML(); 
        echo $fileText;
    }


    public static function mergeFile(&$fileText, &$fileText2) { //private
        //merge altrans from file2  into file per transunit
        self::updateProvenance($fileText, $fileText2);
        $file = new DOMDocument();
        $file->loadXML($fileText);
        $file2 = new DOMDocument();
        $file2->loadXML($fileText2);
        $transUnits = $file->getElementsByTagName("trans-unit"); 
        $transUnits2 = $file2->getElementsByTagName("trans-unit"); 
        
        
        
        for($i=0; $i < $transUnits->length; $i++) {
            
            $transUnit = $transUnits->item($i);
            $transUnit2 = $transUnits2->item($i);
            $altTransUnits = $transUnit2->getElementsByTagName("alt-trans");
            foreach($altTransUnits as $alt){
            $copyAltTrans =$file->importNode($alt,true);
            $transUnit->appendChild($copyAltTrans);
            }
        }
        $fileText=$file->saveXML();
    }
}

//$file = "uploads/test1.xlf";
//$file2 = "uploads/test2.xlf";
//$doc = file_get_contents($file);
//$doc2 = file_get_contents($file2);
//ProviderHelper::mergeFile($doc, $doc2);
//echo $doc;
//$data = new DOMDocument();
//$data->loadXML($doc);
//$data->formatOutput = true;
//echo $data->saveXML();


