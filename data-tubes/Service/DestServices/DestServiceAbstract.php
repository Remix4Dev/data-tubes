<?php
/**
 * File for Destination Service Abstract class.
 *
 */
 
 namespace Service\DestServices;
 
 
/**
 * Class defining services that are targets of data (e.g., poll to show data from a source on a dashboard, push data to a graphing API, etc.)
 *
 * This takes in a standardized set of data from an associative array and formats it to comply with the service's API and desired visualization format.
 * @package services\DestServices
 */
abstract class DestServiceAbstract extends \Service\ServiceAbstract {
	/** @var string $outputType designation used by the data service, if any, for chart type to display, etc. In non-abstract class needs a default option to capture invalid types passed in and halt execution. */
	protected $outputType;	
	/** @var object $dataSource A SourceService object that holds data that needs to be sent to the destination service. */
	protected $dataSource;
	
	/**
	 * currently unused. TBD if needed.
	 */
	abstract protected function pollexec();
	
	public function showData() {
		return $this->dataResultImp->showData($this->dataSource,NULL);
	}		

	/**
	 * Executes POST using cURL, submitting JSON data, and parses response that's formatted in XML.
	 */
	public function httpexec() {	//should be POST, submitting JSON, receiving XML.	
		if (isset($url)) {		
			/** @var resource $ch cURL handle */
			$ch = curl_init ($this->url);
			curl_setopt($ch, CURLOPT_VERBOSE, 1); 
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				
			/** @var string $query POST fields (if any) for this request */
			$query = http_build_query($this->params,NULL,'&');				
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $query);
			
			/** @var SimpleXMLElement $returnxml response XML from the API call */		
			$returnxml = simplexml_load_string(curl_exec ($ch));
			
			if (LOG_VERBOSE) {
			
			}

			if (isset($returnxml->error)) {		
				exit();
			}
			
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			if( !preg_match( '/2\d\d/', $http_status ) ) {
				$err = "ERROR: HTTP Status Code == " . $http_status;			
				exit();
			} elseif (LOG_VERBOSE)
				
			curl_close($ch);
		}
		
	}
}
?>