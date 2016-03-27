<?php
/**
 * File for Source Service Abstract class.
 *
 */

namespace Service\SourceServices;

/**
 * Retrieves data using API interfaces  (e.g., a list of dates, or assessment scores, etc.)
 *
 * Data retrieved is preferably JSON data, and returned in summarized form in an associative array.
 *
 * @package services\SourceServices
 */
abstract class SourceServiceAbstract extends \Service\ServiceAbstract {	

	/**
	 * Options are:
	 * - type - something that's categorical, e.g., status
	 * - date - datetimes
	 * - scores - something that's interval, e.g., scores, mileage, etc.
	 * In non-abstract use in SourceServices, needs a default to catch undefined data types and 
	 * thus halt execution.
	 *
     * @var string $dataType Kind of data being requested. 
	 */
	public $dataType;	
	
	/** @var string $fieldName Name of field that contains data to be retrieved from the source. */
	public $fieldName;	
	/** @var string $groupBy (Optional) Name of field that data should be grouped by */
	public $groupBy;
	
	/**
	 * Module to filter and sort data before returning to requester.
	 *
	 * Should be applied to all inherited classes to ensure consistent support regarding parameter filters.
	 * It only filters data; it does NOT transform data, e.g., filling in dates, cumulative totals, etc.
	 * That should be done in the implementer classes. It does NOT apply filters to all or single data items
	 * (as it doesn't make sense to do so).
	 *
	 * @param mixed[] $aarray The reshaped array that has only the needed data for the request.
  	 *
	 * @return mixed[] An associative array of data, with null values removed and filters and other parameters applied, sorted by field of interest.
	 */
	public function applyParameters($aarray) {	
		if (($this->dataType <> "all") && ($this->dataType <> "single"))  {
			/** @var mixed[] $element first element of assoc array - used to check for valid fields/groupby requests before continuing. */
			$element = reset($aarray);
			if (array_key_exists($this->fieldName,$element) == FALSE) die("Error. Field doesn't exist.");
			if ((is_null($this->groupBy) == FALSE) && (array_key_exists($this->groupBy,$element) == FALSE)) die("Error. GroupBy field doesn't exist.");

			//filter out null/empty values in field of interest
			$aarray = array_filter($aarray, function($el) { return !is_null($el[$this->fieldName]);});
			
			//apply other filters if needed here. Can be a single or multiple filters...
			//exact data match
			if (isset($this->filterexact))
				foreach ($this->filterexact as $key=>$value) 
					$aarray = array_filter($aarray, function($el) use ($key, $value) { return strcmp($el[$key],$value) == 0;});

			
			if (isset($this->filtercontains)) 
				foreach ($this->filtercontains as $key=>$value) 
					$aarray = array_filter($aarray, function($el) use ($key, $value) { return strpos($el[$key],$value) !== FALSE;});					
			//opposite of filterexact - if filter does NOT have an item...use NULL for returning only non-blank items
			if (isset($this->filterexactnot)) 
				foreach ($this->filterexactnot as $key=>$value)
					$aarray = array_filter($aarray,function($el) use ($key,$value) { return strcmp($el[$key],$value) !== 0;});
			
			//Exit if filters return no records
			if (count($aarray) == 0) die("Your parameters resulted in no records being returned. Did you correctly format the parameters array as multi-dim associative arrays? Exiting.");
				
			//go ahead and sort by the field requested...unlikely you'll not want this.			
			uasort($aarray, function($a, $b) { return strnatcasecmp( $a[$this->fieldName], $b[$this->fieldName] );});
			

		}
		
		return $aarray;
	}
	
	
	public function showData() {
			return $this->dataResultImp->showData($this->httpexec(),$this->fieldName,$this->groupBy);
	}		
	
	/**
	 * Default implementation executes GET using cURL, receives JSON data, returns as assoc array.
	 *
	 * @return mixed[] An associative array created from decoding the string JSON data.
	 */
	public function httpexec() {	//should be a GET, submitting JSON, receiving JSON.
		$header[] = "Content-type: application/json";
		$ch = curl_init ();
		if  (empty($this->params)) {
			curl_setopt($ch, CURLOPT_URL, $this->url);
		}
		else
			{ 
				curl_setopt($ch, CURLOPT_URL, $this->url."?".http_build_query($this->params,NULL,'&')); 
				}
		if ($this->credentials['uname'] != NULL)  {
			curl_setopt($ch, CURLOPT_USERPWD, $this->credentials['uname'].":".$this->credentials['pwd']);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		/** @var string $returndata The JSON-formatted data from the source */
		$returndata = curl_exec ($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);	//warnings here.
		if( !preg_match( '/2\d\d/', $http_status ) ) {
			$err = "ERROR: HTTP Status Code == " . $http_status;
			echo $err;
	//		writeLog($err."\r\n. XML was: $returndata",TRUE);
	//		writeLog("Sync FAIL");
			exit();
		} //elseif (LOG_VERBOSE)
	//		writeLog("Data URL was OK for ".$logSource);

		$jsondata = json_decode($returndata,TRUE);
		$jsondata = $this->applyParameters($jsondata);
		curl_close($ch);
		return $jsondata;
	}		
}

?>
