<?php
		 error_reporting(E_ALL);
		 ini_set("display_errors", 1);
		 
		require_once 'Process_XLIFF.class.php';
		
		$option=$_POST['select'];
		$allowed_filetypes = array('.xlf'); // These will be the types of file that will pass the validation.	  
		$max_filesize = 524288; // Maximum filesize in BYTES
		
		$config = parse_ini_file('config_mapper.ini');
		$upload_path =$config['upload_path'];
		
		$filename = $_FILES['userfile']['name']; // Get the name of the file (including file extension).
		$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
		
			if(!in_array($ext,$allowed_filetypes))// Check if the filetype is allowed, if not DIE and inform the user.
			    die('The file you attempted to upload is not allowed.');
			 
			if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)  // Now check the filesize, if it is too large then DIE and inform the user.
			    die('The file you attempted to upload is too large.');
			 
			if(!is_writable($upload_path))// Check if we can upload to the specified path, if not DIE and inform the user.
			    die('You cannot upload to the specified directory, please CHMOD it to 777.');
			
		$tmpName=$_FILES['userfile']['tmp_name']; // Upload the file to the specified path. $content will have the content of the file!
		$fp = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
			fclose($fp);
		
		$pattern = '/<\?xml version.*?>/i';
		$replacement = '';
		$content=preg_replace($pattern, $replacement, $content); // strip out xml declaration.
		
		function view($out){
		$doc = new DOMDocument();
		$doc->loadXML($out);
		print '<center> <a href="#" id="trans"><h3 style="font-weight:bold; font-size:20px;"> Transalations </h3> </a></center>';
		print '<div id="translate">';
		print '<table class="trans" border="1" cellpadding="3" cellspacing="0" align="center"><tbody>';
		$nodes = $doc->getElementsByTagName("trans-unit");//print '<tr class="header"><td colspan="2" rowspan="1">XLIFF Count Group Metadata</td></tr>';
		foreach ($nodes as $node)
		{
			$name =$node->getAttribute("id");
			if ($node->hasChildNodes())
			{
				$sourceNode=$node->getElementsByTagName("source");
				if ($sourceNode->length>0){
					$source=$sourceNode->item(0)->nodeValue ;
				}
				else{
					$source="(source string not found)";
				}
				if (trim($source)==""){
					$source="(source string not found)";
				}
				$targetNode=$node->getElementsByTagName("target");//print $node->hasElement("target");
				if ($targetNode->length>0){
					$target=$targetNode->item(0)->nodeValue;
				}
				else{
					$target="(target string not found)";
				}
				if (trim($target)==""){
					$target="(target string not found)";
				}
				$altTrans=$node->getElementsByTagName("alt-trans");
				print '<tr class="header"><td colspan="4" rowspan="1">'.$name.'</td></tr>';
				print '<tr class="row" id="src"><td>source</td><td colspan="3" rowspan="1">'.htmlentities($source, ENT_QUOTES, 'UTF-8').'</td></tr><tr id="tgt"><td>target</td><td colspan="3" rowspan="1" class="dblclick" id="'.$name.'">'.$target.'</td></tr>';
				if ($altTrans->length>0){
					$k=0;
					foreach ($altTrans as $alt){
						$k++;
						$altval=$alt->nodeValue;
						if ($k==1){
							print '<tr class="row"><td colspan="1" rowspan="'.(string)$altTrans->length.'">alternative translations</td>';
						}
						else{
							print "<tr class='row'>";
						}
						if ($altval!=""){
							print '<td class="alt">'.$altval.'</td>';
						}
						else{
							print '<td> <em> no alternative translations found </em> </td>';
						}
						$temp="";
						$length = $alt->attributes->length;
						for ($i = 0; $i < $length; ++$i){
							$att =$alt->attributes->item($i)->name;
							$val=$alt->attributes->item($i)->value;
							if ($att!="match-quality"){
								$temp=$temp.$att. " : ".$val. "<br/>";
							}
						}
						$mq =$alt->getAttribute("match-quality");
						if ($mq=="" or $mq==NULL){
							$mq="N/A";
						}
						print '<td class="red" id="altb">'.$mq.'</td><td class="txt" id="altb">'.$temp.'</td></tr>';
					}
				}
				else{
					print '<tr class="row"><td colspan="4" rowspan="1" class="alt"> <em> no alternative translations found </em> </td></tr>';
				}
			}
		}
		print '</tbody></table>';
		print '<center><p class="txt"><a id="closet" href="#"> <em> hide Translations </em> </a></p></center></div>' ;
		print '<br/><center> <a href="#" id="meta"><h3 style="font-weight:bold; font-size:20px;"> Metadata </h3> </a></center>';
		print '<div id="metadata">';
		print '<table class="meta" border="1" cellpadding="2" cellspacing="0" align="center">';// project meta data
		$nodes = $doc->getElementsByTagName("pmui-data");
		print '<tr class="header"><td colspan="2" rowspan="1">Project Metadata</td></tr>';
		foreach ($nodes as $node){
			$length = $node->attributes->length;
			for ($i = 0; $i < $length; ++$i){
				$name =$node->attributes->item($i)->name;
				$val =$node->attributes->item($i)->value;
				if ($val!=""){
					print "<tr class='row'><td class='bold'>".$name."</td><td>".$val."</td></tr>";
				}
			}
			print '<tr class="blankrow"><td colspan="2" rowspan="1">&nbsp;</td></tr>';
		}// file meta data
		$nodes = $doc->getElementsByTagName("file");
		print '<tr class="header"><td colspan="2" rowspan="1">XLIFF File Metadata</td></tr>';
		foreach ($nodes as $node){
			$length = $node->attributes->length;
			for ($i = 0; $i < $length; ++$i){
				$name =$node->attributes->item($i)->name;
				$val =$node->attributes->item($i)->value;
				if ($val!=""){
					print "<tr class='row'><td  class='bold'>".$name."</td><td>".$val."</td></tr>";
				}
			}
			print '<tr class="blankrow"><td colspan="2" rowspan="1">&nbsp;</td></tr>';
		}// workflow meta data
		$nodes = $doc->getElementsByTagName("task");
		print '<tr class="header"><td colspan="2" rowspan="1">XLIFF Workflow Metadata</td></tr>';
		foreach ($nodes as $node){
			$length = $node->attributes->length;
			for ($i = 0; $i < $length; ++$i){
				$name =$node->attributes->item($i)->name;
				$val =$node->attributes->item($i)->value;
				if ($val!=""){
					print "<tr class='row'><td class='bold'>".$name."</td><td>".$val."</td></tr>";
				}
			}//print '<tr class="blankrow"><td colspan="2" rowspan="1">&nbsp;</td></tr>';
		}
		print '<tr class="blankrow"><td colspan="2" rowspan="1">&nbsp;</td></tr>';
		// phase data
		$nodes = $doc->getElementsByTagName("phase");
		print '<tr class="header"><td colspan="2" rowspan="1">XLIFF Phase Metadata</td></tr>';
		foreach ($nodes as $node){
			$length = $node->attributes->length;
			for ($i = 0; $i < $length; ++$i){
				$name =$node->attributes->item($i)->name;
				$val =$node->attributes->item($i)->value;
				if ($val!=""){
					print "<tr class='row'><td  class='bold'>".$name."</td><td>".$val."</td></tr>";
				}
			}
			print '<tr class="blankrow"><td colspan="2" rowspan="1">&nbsp;</td></tr>';
		}
		// tools metadata
		$nodes = $doc->getElementsByTagName("tool");
		print '<tr class="header"><td colspan="2" rowspan="1">XLIFF Tool Metadata</td></tr>';
		foreach ($nodes as $node){
			$length = $node->attributes->length;
			for ($i = 0; $i < $length; ++$i){
				$name =$node->attributes->item($i)->name;
				$val =$node->attributes->item($i)->value;
				if ($val!=""){
					print "<tr class='row'><td  class='bold'>".$name."</td><td>".$val."</td></tr>";
				}
			}
			print '<tr class="blankrow"><td colspan="2" rowspan="1">&nbsp;</td></tr>';
		}
		$nodes = $doc->getElementsByTagName("count-group");
		print '<tr class="header"><td colspan="2" rowspan="1">XLIFF Count Group Metadata</td></tr>';
		foreach ($nodes as $node){
			$length = $node->attributes->length;
			for ($i = 0; $i < $length; ++$i){//$jname =$node->attributes->item($i)->name;
				$name =$node->attributes->item($i)->value;
				if ($node->hasChildNodes()){
					$countNode=$node->getElementsByTagName("count");
					$func=$countNode->item(0)->attributes->item(0)->value;
					$type=$countNode->item(0)->attributes->item(1)->value;
					$val=$countNode->item(0)->nodeValue;
					if ($val!=""){
						print "<tr class='row'><td  class='bold'>".$func." ".$type." count(".$name.")</td><td>".$val."</td></tr>";
					}//print $val;
				}
			}//print '<tr class="blankrow"><td colspan="2" rowspan="1">&nbsp;</td></tr>';
		}
		print '<tr class="blankrow"><td colspan="2" rowspan="1">&nbsp;</td></tr>';
		$nodes = $doc->getElementsByTagName("note");
		print '<tr class="header"><td colspan="2" rowspan="1">XLIFF Note Metadata</td></tr>';
		foreach ($nodes as $node){
			$val=$node->nodeValue;
			if ($val!=""){
				print '<tr class="header"><td colspan="2" rowspan="1">'.$val.'</td></tr>';//print $val;
			}//print '<tr class="blankrow"><td colspan="2" rowspan="1">&nbsp;</td></tr>';
		}
		print '</tbody></table>';
		print '<center><p class="txt"><a id="close" href="#"> <em> hide Metadata </em> </a></p></center></div>' ;
}   
   
   
   
   if(move_uploaded_file($tmpName,$upload_path . $filename)){
   
	$name=date('m-d-Y-His');
		$xlif = new XLIFF();
		$processedFile=trim($xlif->processXLIFF($content));
		$processedFile = str_replace("&#xFEFF;", "", $processedFile);//Spanish characters (diacritics)
   
		if ($option=='download'){
				header('Content-Disposition: attachment; filename='.$name.'-xliff.xlf');	
				print html_entity_decode($processedFile, ENT_NOQUOTES, 'UTF-8');
				
		}

		else if ($option=='browser'){
		header("Content-type: text/xml");
		$xlif = new XLIFF();
				$processedFile=trim($xlif->processXLIFF($content));
				print $processedFile = str_replace("&#xFEFF;", "", $processedFile);//Spanish characters (diacritics)

		}
	
		else{
			header('Content-Type: text/html; charset=utf-8');		
?>
			
						<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml"><head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>ALT-Trans v1.0 - Bringing MT translations together</title>
						<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
						</head>
						<body>
						<div id="wrapper">
						<div id="header">
						<h1></h1>
						</div>
						<div id="menu">
						<ul>
						<li><a href="index.html">Home</a></li>
						
						</ul>
						</div>
						<div id="content">
						<div class="entry">
						<div class="entry-title">MT-Mapper - ALT-TRANS</div>
						<div class="date">CNGL - Autumn 2011</div>
						<br />
						<br />
								<?php			
											view(html_entity_decode($processedFile, ENT_NOQUOTES, 'UTF-8'));
								?>
						</div>
						</div>
						<div id="footer">
						<div id="footer-valid"> <a href="http://validator.w3.org/check/referer">xhtml</a>
						/ <a href="http://www.ginger-ninja.net/">ginger ninja!</a>
						</div>
						</div>
						</div>
						</body></html>
<?php			
			}
		} 
      else
         echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
?>