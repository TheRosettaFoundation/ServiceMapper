<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');
require_once __DIR__.'/IProvider.php';
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

    static $mrkId = 0;
    const INSERT_END = 0;
    const INSERT_BEFORE = 1;
    const INSERT_AFTER = 2;
    
    public static function insertNode($newNode, $refNode, $insertMode=self::INSERT_END) {
        
        if(is_null($insertMode) || $insertMode == self::INSERT_END) {
            
            $refNode->appendChild($newNode);
            
        } else if($insertMode == self::INSERT_BEFORE) {
            
            $refNode->parentNode->insertBefore($newNode, $refNode);
            
        } else if($insertMode == self::INSERT_AFTER) {
            
            if($refNode->nextSibling) {
                $refNode->parentNode->insertBefore($newNode, $refNode->nextSibling);
            } else {
                $refNode->parentNode->appendChild($newNode);
            }      
                  
        }
        
    }
    
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
            $provider=new $name;
            if($provider->isEnabled())$providers[]=$provider;
        }
        return $providers;
    }
    
    public static function enrichFile($xliffVersion, &$file, array $array) {
        foreach ($array as $xliff){
            if($xliff=="" || is_null($xliff)) continue;
            if($xliffVersion == XLIFFVersionEnum::XLIFF_2_0) {
                ProviderHelper::mergeFileXLIFFFile2($file, $xliff);
            } else {
                ProviderHelper::mergeFileXLIFFFile1x($file, $xliff);
            }
        }
//        $file = $array[0];
    }

    
    public static function updateProvenanceXLIFF1x(&$fileText, &$fileText2){
        
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
                $old =$file->saveXML($file->importNode($map[$currentID],true));                
                if($pRec==$map[$currentID]&&$current==$old) continue;
//                if($pRec==$map[$currentID])continue;
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
        
    }

    public static function updateProvenanceXLIFF2(&$fileText, &$fileText2){
        
        $file = new DOMDocument();
        $file->loadXML($fileText);
        $file2 = new DOMDocument();
        $file2->loadXML($fileText2);
        $fileElement = $file->getElementsByTagName("file")->item(0);
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
                $old =$file->saveXML($file->importNode($map[$currentID],true));                
                if($pRec==$map[$currentID]&&$current==$old) continue;
//                if($pRec==$map[$currentID])continue;
                else{
                    $i = 1;
                    while(isset ($map["pr$i"])) $i++;
                    $fileText2=str_replace("#$currentID","#pr$i", $fileText2);
//                    $fileText2=str_replace("xml:id=\"$currentID\"","xml:id=\"pr$i\"", $fileText2);
//                    $fileText2=str_replace("xml:id='$currentID'","xml:id='pr$i'", $fileText2);
                    $pRec->setAttribute("xml:id","pr$i");
                    $map["pr$i"]=$pRec;
                    $placeholder = $file->getElementsByTagName("group")->item(0);
                    if(is_null($placeholder)) {
                        $placeholder = $file->getElementsByTagName("unit")->item(0);
                    }
                    //$fileElement->insertBefore($file->importNode($pRec,true), $placeholder);
                    $fileElement->appendChild($file->importNode($pRec,true));
                }
                
            }else{
                 $placeholder = $file->getElementsByTagName("group")->item(0);
                    if(is_null($placeholder)) {
                        $placeholder = $file->getElementsByTagName("unit")->item(0);
                    }
                    //$fileElement->insertBefore($file->importNode($pRec,true), $placeholder);
                    $fileElement->appendChild($file->importNode($pRec,true));
            }
        }
        $fileText =$file->saveXML(); 
        
    }

    public static function mergeFileXLIFFFile1x(&$fileText, &$fileText2) { //private
        //merge altrans from file2  into file per transunit
        self::updateProvenanceXLIFF1x($fileText, $fileText2);
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
    
    public static function mergeFileXLIFFFile2(&$fileText, &$fileText2) { //private
        //merge altrans from file2  into file per transunit
        self::updateProvenanceXLIFF2($fileText, $fileText2);
        $file = new DOMDocument();
        $file->loadXML($fileText);
        $xPath = new \DOMXPath($file);
        $file2 = new DOMDocument();
        $file2->loadXML($fileText2);
        $xPath2 = new \DOMXPath($file2);
        $units = $file->getElementsByTagName("unit"); 
        $units2 = $file2->getElementsByTagName("unit");
        
//        if($matches2 = $xPath2->query("//*[local-name()='matches']")){
            //$mrkId = 0;
            for($i=0; $i < $units2->length; $i++) {

                $unit = $units->item($i);
                $unit2 = $units2->item($i);
                
//                $actualSegments = $unit->getElementsByTagName("segment");
//                $actualSegments2 = $unit2->getElementsByTagName("segment");
//                
//                for($i=0; $i < $actualSegments2->length; $i++) {
//                    $actualSegments->item($i)->getElementsByTagName("source")->item(0)->setAttribute("ref", "#".$actualSegments2->item($i)->getElementsByTagName("source")->item(0)->getAttribute("ref"));
//                }
                
                //$newSegSource = $file->importNode($segmentSource2, true);
                //$segment->replaceChild($newSegSource, $segmentSource);                

//                $matches = $xPath->query("//*[local-name()='matches']");
//                $matchPrefix = $file->lookupPrefix("urn:oasis:names:tc:xliff:matches:2.0");
                
               
                
                $prefix = $file->lookupPrefix(\IProvider::XMLNS_MTC);
                $matches = $unit->getElementsByTagName("matches")->item(0);
                
                
                
                //$matches = $segment->getElementsByTagName("matches")->item(0);
//                if($matches===false||$matches->length===0){
////                    if(is_null($matchPrefix) || $matchPrefix="") {
////                        $doc->firstChild->setAttributeNS('http://www.w3.org/2000/xmlns/',"xmlns:mtc","urn:oasis:names:tc:xliff:matches:2.0");
////                        $matchPrefix = "mtc";
////                    }
////                    $matches = $file->createElement("{$matchPrefix}:matches");
//                    $matches = $file->createElement("matches");
//                    $segment->appendChild($matches);
//                } else $matches= $matches->item(0);
                if(is_null($matches)){
                    $prefix = $file->lookupPrefix(\IProvider::XMLNS_MTC) ;
                    $matches = $file->createElementNS(\IProvider::XMLNS_MTC, "$prefix:matches");
                    $placesholder = $unit->getElementsByTagName("segment");
                    $placesholder= $placesholder->item($placesholder->length-1);
                    ProviderHelper::insertNode($matches, $placesholder, ProviderHelper::INSERT_AFTER);
                }

                $matchElements = $unit2->getElementsByTagName("match");
                //$matchElements = $segment2->getElementsByTagName("match");
//                $matchElements = $xPath2->query("//*[local-name()='match']",$match2);
//                if($matchElements && $matchElements->length>0){
                    $unitId = $unit->getAttribute("id");
                        
                    foreach($matchElements as $matchElement){                        
                        $matchID = $matchElement->getAttribute("id");
                        
                        
                        $matchSeg=$xPath2->query("//unit[@id='$unitId']/segment[.//mrk[@ref='#$matchID']]")->item(0);
                        $originalSeg= $xPath->query($matchSeg->getNodePath())->item(0);
                     
                        $temp= 0;
                        while($xPath->query("//unit[@id='$unitId']/*[local-name()='matches']/*[local-name()='match' and @id='$matchID']")->length>0){
                            $matchID.=$temp++;  
                        }
                        $matchElement->setAttribute("id",$matchID);
                
                        
                        $segmentSource = $originalSeg->getElementsByTagName("source")->item(0);
                            
                        $mrk=$file->createElement("mrk");

                        $children =$segmentSource->childNodes;
                        $placeholder = null;
                        for($j=$children->length-1;$j>=0;$j-- ) {

                            $current=$children->item($j);
                            $mrk->insertBefore($current,$placeholder);
                            $placeholder=$current;
                        }

       
                        $id=null;
                    

                        
                        do{
                            $id="mrkID_".self::$mrkId++;      
                        }
                        while ($xPath->query($originalSeg->getNodePath()."//mrk[@id='$id']]")->length > 0);
                        $mrk->setAttribute("id", $id);
                        $mrk->setAttribute("ref","#".$matchID);
                        $segmentSource->appendChild($mrk);
                        
                        
                        
                        $copyMatchElement =$file->importNode($matchElement,true);
                        $matches->appendChild($copyMatchElement);
                    }
//                }
//            }
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


