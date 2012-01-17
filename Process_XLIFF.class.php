<?php

include_once "MTBroker_Connect.class.php";
/**
 * Class XLIFF
 *
 * Description: [missing]
 */
class XLIFF {
	var $proxy = 'false';
	public function processXLIFF($xliff) {
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
	  
	  	$arr= parse_ini_file("demolangs.ini");
		$source=$arr[$source_xx]; 
		$target=$arr[$target_xx];
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
		
 		$transUnits = $doc->getElementsByTagName( 'trans-unit' );

		foreach($transUnits as $transUnit ){
			$transID = $transUnit->getAttribute('id');
			$sources = $transUnit->getElementsByTagName( "source" );
			$text = $sources->item(0)->nodeValue;
			$string1= new MTConnect();
			$provider[0] ='Microsoft Bing Translator'; 					
			$provider[1] ='Yahoo! Babelfish';
			$config = parse_ini_file('config.ini');
			$proxy =$config['proxy'];
			
			for ($i=0; $i < count($provider); $i++){			
				$preferred_provider=$provider[$i];		
				if (trim($text)!=""){								
					$translation=$string1->translate($source, $target, trim($text), $preferred_provider,$proxy);
					
					$alt_trans=$doc->createElement('alt-trans');
					$transUnit->appendChild($alt_trans);
					
					$root_attr1 = $doc->createAttribute('tool-id');
					$alt_trans->appendChild($root_attr1);
					$root_text = $doc->createTextNode($toolid);
					$root_attr1->appendChild($root_text); 
				
					$root_attr2 = $doc->createAttribute('origin');
					$alt_trans->appendChild($root_attr2);
				
					$root_text = $doc->createTextNode($preferred_provider);
					$root_attr2->appendChild($root_text); 
					
					$root_attr1 = $doc->createAttribute('phase-name');
					$alt_trans->appendChild($root_attr1);
					$root_text = $doc->createTextNode($phasename);
					$root_attr1->appendChild($root_text); 
				 
					$source_element=$doc->createElement('source', $text);
					$alt_trans->appendChild($source_element);
					
					$attr1 = $doc->createAttribute('xml:lang');
					$source_element->appendChild($attr1);
					$root_text = $doc->createTextNode($source_xx);
					$attr1->appendChild($root_text); 
					
					$target_element=$doc->createElement('target', $translation);
					$alt_trans->appendChild($target_element);
					
					$attr2 = $doc->createAttribute('xml:lang');
					$target_element->appendChild($attr2);
					$root_text = $doc->createTextNode($target_xx);
					$attr2->appendChild($root_text); 
					
					$translation = "";
				}
			}
		}
	$doc->formatOutput = true;//this line format output when browsing HTML source. 
	$data = $doc->saveXML();
	return $data;
	}
}
