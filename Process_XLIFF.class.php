<?php

include_once "MTBroker_Connect.class.php";
require_once 'ProviderHelper.php';
include_once "XLIFFVersionEnum.php";

/**
 * Class XLIFF
 *
 * Description: [missing]
 */
class XLIFF {

    var $proxy = false;

    public function processXLIFF($xliff) {
        $proxy = false;
        $toolid = 'MT';
        $phasename = 'MT-Leverage';
        $doc = new DOMDocument();
        $doc->loadXML($xliff);
        

        $segCount= 0;
        $mrkId=0;
        $xpath = new DOMXPath($doc);
        foreach($doc->getElementsByTagName("segment") as $segment ){  
            if(!$segment->hasAttribute("id")){ 
                $idVal="segID_".$segCount++;
                $segment->setAttribute("id", $idVal);
            }
            if(!$segment->hasAttribute("canResegment") || strtolower($segment->getAttribute("canResegment"))=="yes")
            {
                $source = $segment->getElementsByTagName("source")->item(0);
                $mrk=$doc->createElement("mrk");
               
                $children =$source->childNodes;
                $placeholder = null;
                for($i=$children->length-1;$i>=0;$i-- ) {
                    
                    $current=$children->item($i);
                    $mrk->insertBefore($current,$placeholder);
                    $placeholder=$current;
                }
                
                $source->appendChild($mrk);        
                $id=null;
               
                $unitID = $segment->parentNode->getAttribute("id");
                do{
                    $id="mrkID_".$mrkId++;      
                }
                while ($xpath->query("//unit[@id='$unitID' and ./segment[@id='$idVal']//mrk[@id='$id']]")->length>0);
                $mrk->setAttribute("id", $id);
                $source->appendChild($mrk);
            }
        }
        $xliff = htmlspecialchars_decode($doc->saveXML());
        
        $xliffVersion = $doc->firstChild->getAttribute('version');
        
        if($xliffVersion == XLIFFVersionEnum::XLIFF_2_0) {
            $xliffElement = $doc->getElementsByTagName("xliff")->item(0);
            $source = $xliffElement->getAttribute("srcLang");
            if($target = $xliffElement->getAttribute("tgtLang")) {                
                return $this->processFile($xliffVersion, $doc, $xliff, $source, $target);
            } else {
                return $xliff;
            }
            
        } else {

            $files = $doc->getElementsByTagName("file");
            $source_xx=null;
            $target_xx=null;
            foreach ($files as $file) {
                $source_xx = $file->getAttribute('source-language');
                $target_xx = $file->getAttribute('target-language');
            }
            $source = $source_xx;
            $target = $target_xx;
//            $arr= parse_ini_file("languages.ini");
//            $source=$arr[strtoupper($source_xx)]; 
//            $target=$arr[strtoupper($target_xx)];
            $phasegroups = $doc->getElementsByTagName('phase-group');
    
            foreach ($phasegroups as $phasegroup) {
                $root_child = $doc->createElement('phase');
                $phasegroup->appendChild($root_child);
    
                $root_attr1 = $doc->createAttribute('phase-name');
                $root_child->appendChild($root_attr1);
                $root_text = $doc->createTextNode($phasename);
                $root_attr1->appendChild($root_text);
    
                $root_attr2 = $doc->createAttribute('company-name');
                $root_child->appendChild($root_attr2);
                $root_text = $doc->createTextNode('Naoto-Company');
                $root_attr2->appendChild($root_text);
    
                $root_attr3 = $doc->createAttribute('process-name');
                $root_child->appendChild($root_attr3);
                $root_text = $doc->createTextNode('Translation-MT');
                $root_attr3->appendChild($root_text);
    
                $root_attr4 = $doc->createAttribute('contact-name');
                $root_child->appendChild($root_attr4);
                $root_text = $doc->createTextNode('Naoto');
                $root_attr4->appendChild($root_text);
    
                $root_attr5 = $doc->createAttribute('contact-email');
                $root_child->appendChild($root_attr5);
                $root_text = $doc->createTextNode('naoto.nishio@ul.ie');
                $root_attr5->appendChild($root_text);
    
                $root_attr6 = $doc->createAttribute('tool-id');
                $root_child->appendChild($root_attr6);
                $root_text = $doc->createTextNode($toolid);
                $root_attr6->appendChild($root_text);
    
                $date = date("r", time());
                $root_attr7 = $doc->createAttribute('date');
                $root_child->appendChild($root_attr7);
                $root_text = $doc->createTextNode($date);
                $root_attr7->appendChild($root_text);
            }

            $headers = $doc->getElementsByTagName('header');

            foreach ($headers as $header) {
                $root_child = $doc->createElement('tool');
                $header->appendChild($root_child);
    
                $root_attr1 = $doc->createAttribute('tool-id');
                $root_child->appendChild($root_attr1);
                $root_text = $doc->createTextNode($toolid);
                $root_attr1->appendChild($root_text);
    
                $root_attr2 = $doc->createAttribute('tool-name');
                $root_child->appendChild($root_attr2);
                $root_text = $doc->createTextNode('MT-Broker/Mapper');
                $root_attr2->appendChild($root_text);
            }

            return $this->processFile($xliffVersion, $doc, $xliff, $source, $target);
            

        }
    }
    
    public function translateFiles($xliff, $source, $target)
    {
            $provider = array();
            $providers = ProviderHelper::loadAllProviders();
            foreach ($providers as $p) {
                $tlangs = array();
                foreach ($p->getTargetLanguages() as $tlang) {
                    if (strpos($tlang, "-") != false) {
                        $tlang = explode("-", $tlang);
                        $tlang = $tlang[0];
                    }$tlangs[] = $tlang;
                }
                $slangs = array();
                foreach ($p->getSourceLanguages() as $slang) {
                    if (strpos($slang, "-") != false) {
                        $slang = explode("-", $slang);
                        $slang = $slang[0];
                    }$slangs[] = $slang;
                }
                $tempSource = $source;
                if (strpos($tempSource, "-") != false) {
                    $tempSource = explode("-", $tempSource);
                    $tempSource = $tempSource[0];
                }
                $tempTarget = $target;
                if (strpos($tempTarget, "-") != false) {
                    $tempTarget = explode("-", $tempTarget);
                    $tempTarget = $tempTarget[0];
                }
                if (in_array($tempTarget, $tlangs) && in_array($tempSource, $slangs))
                    $provider[] = $p;
            }
            $translatedFiles = array();
            if (strpos($source, "-") !== false) {
                $source = explode("-", $source);
                $source = $source[0];
            }

            if (strpos($target, "-") !== false) {
                $target = explode("-", $target);
                $target = $target[0];
            }

            foreach ($provider as $p) {
                try {
                    $translatedFiles[] = $p->translateFile($xliff, $source, $target);
                } catch (Exception $e) {
    //            print_r($e);
                }
            }
            return $translatedFiles;
    }
    
    public function processFile($xliffVersion, &$doc, $xliff, $source, $target)
    {
            $translatedFiles = $this->translateFiles($xliff, $source, $target);
            $result = $doc->saveXML();
            ProviderHelper::enrichFile($xliffVersion, $result, $translatedFiles);
            $doc->formatOutput = true; 
            $doc->preserveWhiteSpace=false;
            $doc->loadXML($result);
            
            $doc->normalizeDocument();
            $data = $doc->saveXML();
            
          
            return $data;
    }
            

   
    
}



//$xliff = new XLIFF();
//$data = file_get_contents("uploads/EXc-xliff-prov-rt-1-post-seg.xlf");
//$xliff = new XLIFF();
//echo $xliff->processXLIFF($data);

