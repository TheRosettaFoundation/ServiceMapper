<?PHP
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

include("MTBroker_Connect.class.php");
include("Process_XLIFF.class.php");
$locConnect='http://193.1.97.50/locconnect/';

$config = parse_ini_file('config.ini');
$locConnect =$config['locConnect_address'];

require_once 'HTTP'.DIRECTORY_SEPARATOR.'Request2.php'; // uses Pear

// This will get a list of pending jobs from the CNLF server and store them $jobs variable;
$request = new HTTP_Request2($locConnect.'fetch_job.php', HTTP_Request2::METHOD_GET);
$request->setHeader('Accept-Charset', 'utf-8');
$url = $request->getUrl();
$url->setQueryVariable('com', 'MT');         // set your component name here

function get_rid_of_content($jobs){
	$output=$jobs;
	$output = str_replace("<content>", "", $output);
	$output = str_replace("</content>", "", $output);
	return $output;
}

$jobs = $request->send()->getBody();
$doc = new DOMDocument();
$doc->loadXML($jobs );	
$xpath = new DOMXPath($doc);
$files = $doc->getElementsByTagName("error");
$files1 = $doc->getElementsByTagName("jobs");
	 
foreach ($files as $file) {
	$content =$file->getElementsByTagName( "msg" );
	$xmlUserName=$content->item(0)->nodeValue; 					
	srand(time());
	$random = (rand()%9);
}	
$files2 = $doc->getElementsByTagName("jobs");
		
foreach ($files2 as $file) {
	$content =$file->getElementsByTagName( "job" );
	$jobId=$content->item(0)->nodeValue; 
	//print("now processing:".$jobId);
	$request = new HTTP_Request2($locConnect.'set_status.php', HTTP_Request2::METHOD_GET);
	$request->setHeader('Accept-Charset', 'utf-8');
	$url = $request->getUrl();
	$url->setQueryVariable('com', 'MT');         // set your component name here
	$url->setQueryVariable('id', $jobId);         // set job id here
	$url->setQueryVariable('msg', 'processing'); 
	// This will get the server response 
	$response=$request->send()->getBody();
}	
				
if (isset($jobId)) {
	print('Now processing:'.$jobId.'<br/>');	
	$files = $doc->getElementsByTagName( "job" );
	//do{
  	foreach ($files as $file ) {
        require_once 'HTTP'.DIRECTORY_SEPARATOR.'Request2.php'; // uses Pear
		$request = new HTTP_Request2($locConnect.'get_job.php', HTTP_Request2::METHOD_GET);
		$request->setHeader('Accept-Charset', 'utf-8');
		$url = $request->getUrl();
		$url->setQueryVariable('id', $jobId);
		$url->setQueryVariable('com', 'MT');         // set your component name here
		$content=$request->send()->getBody();
		$content=get_rid_of_content($content);
		//print $content;
		
		$toolid='MT';
		$phasename='MT-Leverage';
		$request = new HTTP_Request2($locConnect.'send_output.php', HTTP_Request2::METHOD_POST);
		$data = new XLIFF(); //2011-11-05
		//$data->processXLIFF($xliff); //2011-11-05
		$xliff_string = $data->processXLIFF($content);//2011-11-05
		//$data =processXLIFF($content);//2010-10-04
		//$data= html_entity_decode(trim($data), ENT_NOQUOTES, 'UTF-8')
		$xliff_string = html_entity_decode(trim($xliff_string), ENT_NOQUOTES, 'UTF-8');// what is this for? 2010-10-20
		print $xliff_string;
		
		$request->setMethod(HTTP_Request2::METHOD_POST)
		->addPostParameter('id', $jobId)
		->addPostParameter('com', 'MT')
		->addPostParameter('data', $xliff_string);
		print $xliff_string;
		try{
			$response=$request->send();
			if (200 == $response->getStatus()){
					echo "<br>Job: ".$jobId." was successfully processed.</br>";
				}
				else{
					echo 'UnexpectedHTTP Status: '.$response->getStatus().''.$response->getReasonPhrase();
				}
		}
		catch (HTTP_Request2_Exception $e){
			echo 'Error: '.$e->getMessage();
		}
		
		//feedback
		$request = new HTTP_Request2($locConnect.'send_feedback.php', HTTP_Request2::METHOD_GET);
		$request->setHeader('Accept-Charset', 'utf-8');
		$url = $request->getUrl();
		$url->setQueryVariable('com', 'MT');         // set your component name here
		$url->setQueryVariable('id', $jobId);         // set job id here
		$url->setQueryVariable('msg', 'Enriched the xliff with alternative translations obtained via Google, Bing and Babelfish');         // set your component's feedback here

		// This will get the server response 
		$response=$request->send()->getBody();
		echo $response;

		//2010-08-31
		$request = new HTTP_Request2($locConnect.'set_status.php', HTTP_Request2::METHOD_GET);
		$request->setHeader('Accept-Charset', 'utf-8');
		$url = $request->getUrl();
		$url->setQueryVariable('com', 'MT');         // set your component name here
		$url->setQueryVariable('id', $jobId);         // set job id here
		$url->setQueryVariable('msg', 'complete');         // set status id here
		$response=$request->send()->getBody();
	}     
} 
else{
	$time_start = microtime(true);
	print('Waiting for a job : ('.$time_start.') <br/>'); 	
}
