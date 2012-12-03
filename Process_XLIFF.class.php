<?php

include_once "MTBroker_Connect.class.php";
require_once 'ProviderHelper.php';
/**
 * Class XLIFF
 *
 * Description: [missing]
 */
class XLIFF {
	var $proxy = false;
	public function processXLIFF($xliff) {
        $proxy = false;
        $toolid='MT';
        $phasename='MT-Leverage';
        $doc = new DOMDocument();
        $doc->loadXML($xliff);		
        $xpath = new DOMXPath($doc);
        $files = $doc->getElementsByTagName( "file" );

        foreach( $files as $file ){
                $source_xx = $file->getAttribute('source-language');
                $target_xx =  $file->getAttribute('target-language');
        }
        $source=$source_xx;
        $target=$target_xx;
//        $arr= parse_ini_file("languages.ini");
//        $source=$arr[strtoupper($source_xx)]; 
//        $target=$arr[strtoupper($target_xx)];
        $phasegroups = $doc->getElementsByTagName('phase-group');

        foreach($phasegroups as $phasegroup){
                $root_child=$doc->createElement('phase');
                $phasegroup ->appendChild($root_child);

                $root_attr1 = $doc->createAttribute('phase-name');
                $root_child->appendChild($root_attr1);
                $root_text = $doc->createTextNode($phasename);
                $root_attr1 ->appendChild($root_text); 

                $root_attr2 = $doc->createAttribute('company-name');
                $root_child->appendChild($root_attr2);
                $root_text = $doc->createTextNode('Naoto-Company');
                $root_attr2 ->appendChild($root_text); 

                $root_attr3 = $doc->createAttribute('process-name');
                $root_child->appendChild($root_attr3);
                $root_text = $doc->createTextNode('Translation-MT');
                $root_attr3 ->appendChild($root_text); 

                $root_attr4 = $doc->createAttribute('contact-name');
                $root_child->appendChild($root_attr4);
                $root_text = $doc->createTextNode('Naoto');
                $root_attr4 ->appendChild($root_text); 

                $root_attr5 = $doc->createAttribute('contact-email');
                $root_child->appendChild($root_attr5);
                $root_text = $doc->createTextNode('naoto.nishio@ul.ie');
                $root_attr5 ->appendChild($root_text); 

                $root_attr6 = $doc->createAttribute('tool-id');
                $root_child->appendChild($root_attr6);
                $root_text = $doc->createTextNode($toolid);
                $root_attr6 ->appendChild($root_text); 

                $date=date("r", time());
                $root_attr7 = $doc->createAttribute('date');
                $root_child->appendChild($root_attr7);
                $root_text = $doc->createTextNode($date);
                $root_attr7 ->appendChild($root_text); 
        }

        $headers = $doc->getElementsByTagName('header');

        foreach($headers as $header){
                $root_child=$doc->createElement('tool');
                $header ->appendChild($root_child);

                $root_attr1 = $doc->createAttribute('tool-id');
                $root_child->appendChild($root_attr1);
                $root_text = $doc->createTextNode($toolid);
                $root_attr1 ->appendChild($root_text); 

                $root_attr2 = $doc->createAttribute('tool-name');
                $root_child->appendChild($root_attr2);
                $root_text = $doc->createTextNode('MT-Broker/Mapper');
                $root_attr2 ->appendChild($root_text); 
        }

       
        $provider = array();
        $providers = ProviderHelper::loadAllProviders();
        foreach($providers as $p){
            $tlangs=array();
            foreach ($p->getTargetLanguages() as $tlang){
                if(strpos($tlang, "-")!=false){
                    $tlang=explode("-",$tlang);
                    $tlang=$tlang[0];
                }$tlangs[]=$tlang;
            }
            $slangs=array();
            foreach ($p->getSourceLanguages() as $slang){
                if(strpos($slang, "-")!=false){
                    $slang=explode("-",$slang);
                    $slang=$slang[0];
                }$slangs[]=$slang;
            }
            $tempSource =$source;
            if(strpos($tempSource, "-")!=false){
                $tempSource=explode("-",$tempSource);
                $tempSource=$tempSource[0];
            }
           $tempTarget =$target;
            if(strpos($tempTarget, "-")!=false){
                $tempTarget=explode("-",$tempTarget);
                $tempTarget=$tempTarget[0];
            }
            if(in_array($tempTarget,$tlangs)&&in_array($tempSource,$slangs))$provider[]=$p;
        }
        $translatedFiles = array();
        foreach ($provider as $p){
            $translatedFiles[]=$p->translateFile($xliff, $source, $target);
            
        }
        $result= $doc->saveXML();
        ProviderHelper::enrichFile($result,$translatedFiles);
        
//      just return $result ?
        $doc->loadXML($result);
	$doc->formatOutput = true;//this line format output when browsing HTML source. 
	$data = $doc->saveXML();
	return $data;
	}
}
