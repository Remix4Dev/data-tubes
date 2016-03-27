<?php 
/**
 * File for freshdesk-related services
 *
 * @package services
 */
namespace Service\SourceServices;

require_once('SourceServiceAbstract.php');

/**
 * Freshdesk source service.
 *
 * @package services\SourceServices
 */
class FreshdeskSource extends SourceServiceAbstract {
	function __construct($dataTypeIn,
							$fieldNameIn,
							$groupByIn = NULL,
							$paramsIn=array("queryparams"=>array("page"=>0,"filter_name"=>"all_tickets")),
							$urlIn="https://ACCOUNTHERE.freshdesk.com/helpdesk/tickets.json",
							$credentialsIn=array("uname"=>"APIKEYHERE","pwd"=>""),						
							$httpmethodIn="GET") {
			$this->fieldName = $fieldNameIn;
			$this->groupBy = $groupByIn;
			$this->dataType = $dataTypeIn;
			$this->url = $urlIn;
			$this->credentials = $credentialsIn;
			$this->params = $paramsIn;
			if (!is_null($paramsIn)) {
				foreach ($paramsIn as $key=>$value) {
					$this->{$key} = $value;
				}	
			}			
			$this->httpmethod = $httpmethodIn;
			switch ($dataTypeIn) {
				case "date" :
					$this->dataResultImp = new FreshdeskSourceDateImp($this->params);
					break;
				case "scores" :
					$this->dataResultImp = new FreshdeskSourceScoreImp($this->params);
					break;
				case "type" :
					$this->dataResultImp = new FreshdeskSourceTypeImp($this->params);
					break;
				case "all" :
					$this->dataResultImp = new FreshdeskSourceAllImp();
					break;
				default:
					echo "Error. Type not defined";
					exit();								
			}
	}
	
    /**
     * Array_map callback function used to convert codes from different FD ticket properties to readable text. 
     * 
	 * This function remaps status, source type, and priorities from codes to text. It also could drop unused fields, though that isn't set yet.
	 *
     * @param array $item a single FD ticket
     * @param int $key the ticket's key from the entire FD array. Not used for anything here.
     * 
     */
	protected function remap(&$item,$key) {
		$item["status"] = $item["status_name"];
		$item["source"] = $item["source_name"];
		$item["priority"] = $item["priority_name"];
	}
	/**
	 * Overridden to deal with FD throttling.
	 */
	public function httpexec() {	//should be a GET, submitting JSON, receiving JSON.
		$header[] = "Content-type: application/json";
		$ch = curl_init ();
		if  (empty($this->queryparams)) {
			curl_setopt($ch, CURLOPT_URL, $this->url);
		}
		else
			curl_setopt($ch, CURLOPT_URL, $this->url."?".http_build_query($this->queryparams,NULL,'&')); 
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
	
		/** @var mixed[] $finaldata array used as merge source when doing multiple API calls for all data */
		$finaldata = array();
		if ($this->dev) {		//just do one page so that we don't use up all the API calls for the hour.
			echo "!!!In dev state!!!";
			$returndata = "";			
			$this->queryparams["page"]++;
			curl_setopt($ch, CURLOPT_URL, $this->url."?".http_build_query($this->queryparams,NULL,'&')); 	
			$returndata = curl_exec($ch);
			$finaldata = json_decode($returndata,TRUE);
		}
		else {
			do {			
				$returndata = "";
				$this->queryparams["page"]++;
				curl_setopt($ch, CURLOPT_URL, $this->url."?".http_build_query($this->queryparams,NULL,'&')); 	
				$returndata = curl_exec ($ch);
				$finaldata = array_merge($finaldata,json_decode($returndata,TRUE));
			} while ($returndata <> "[]");
		}
		array_walk($finaldata,array($this, 'remap'));
		
		
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);	//warnings here.
		if( !preg_match( '/2\d\d/', $http_status ) ) {
			$err = "ERROR: HTTP Status Code == " . $http_status;
			echo $err;
	//		writeLog($err."\r\n. XML was: $returndata",TRUE);
	//		writeLog("Sync FAIL");
			exit();
		} //elseif (LOG_VERBOSE)
	//		writeLog("Data URL was OK for ".$logSource);


		$jsondata = $finaldata;
		//drop descriptions and third-level arrays, as no data can be really graphed on that.
		foreach ($jsondata as &$value) {
			unset($value["description"]);
			unset($value["description_html"]);
			unset($value["cc_email"]);
			unset($value["to_emails"]);
			//move custom fields into second level
			foreach ($value["custom_field"] as $key=>$custvalue) {
				$value[$key] = $custvalue;
			}
			unset($value["custom_field"]);
		}
		
		curl_close($ch);

		$jsondata = $this->applyParameters($jsondata);							
		return $jsondata;
	}		
}


/**
 * Freshdesk - retrieves date data
 *
 * @package services\serviceImp\Sources\Freshdesk
 */
class FreshdeskSourceDateImp extends \Service\ServiceImpAbstract {
	function __construct($paramsIn = NULL) {
		if (!is_null($paramsIn)) {
			foreach ($paramsIn as $key=>$value) {
				$this->{$key} = $value;
			}	
		}
	}
	
	function showData($aarray,$fieldname,$groupby) {
		date_default_timezone_set('America/Los_Angeles');
		if (is_null($groupby)) {
			foreach ($aarray as $value) {
				$datetimelist[date("y-m",strtotime($value[$fieldname]))]++;	
			}
		}
		else {
			foreach ($aarray as $value) {
					$datetimelist[$value[$groupby]][date("y-m",strtotime($value[$fieldname]))]++;	
					$keylist[date("y-m",strtotime($value[$fieldname]))] = 0;
			}
			//make arrays non-sparse
			foreach ($datetimelist as &$value) {
				$value = array_merge($keylist,$value);
			}
		}
		//apply cumulative, datefill, or other parameters.
		$datetimelist=$this->applyParameters($datetimelist,$fieldname,$groupby);
		return $datetimelist;
	}
}

/**
 * Freshdesk - retrieves score data
 *
 * @package services\serviceImp\Sources\Freshdesk
 */
class FreshdeskSourceScoreImp extends \Service\ServiceImpAbstract {
	function __construct($paramsIn = NULL) {
		if (!is_null($paramsIn)) {
			foreach ($paramsIn as $key=>$value) {
				$this->{$key} = $value;
			}	
		}
	}

	function showData($aarray,$fieldname,$groupby) {
		if (is_null($groupby)) {
			foreach ($aarray as $value) {
				$scores[] = $value[$fieldname];
			}
			$scorestats["max"] = max($scores);
			$scorestats["min"] = min($scores);
			$scorestats["avg"] = round(array_sum($scores) / count($scores));
		}
		else {
			foreach ($aarray as $value) {
				$scores[$value[$groupby]][] = $value[$fieldname];				
			}
			foreach ($scores as $key=>$value) {
				$scorestats[$key]["avg"] = round(array_sum($value) / count($value));
				$scorestats[$key]["max"] = max($value);
				$scorestats[$key]["min"] = min($value);
			}
		}
		
		$scorestats=$this->applyParameters($scorestats,$fieldname,$groupby);
		return $scorestats;	
	}
	
}

/**
 * Freshdesk - retrieves categorical (type) data
 *
 * @package services\serviceImp\Sources
 */
class FreshdeskSourceTypeImp extends \Service\ServiceImpAbstract {
	
	function __construct($paramsIn = NULL) {
		if (!is_null($paramsIn)) {
			foreach ($paramsIn as $key=>$value) {
				$this->{$key} = $value;
			}	
		}
	}

	function showData($aarray,$fieldname,$groupby) {
		if (is_null($groupby)) {
			foreach ($aarray as $value) {				
				$result[ucwords($value[$fieldname])]++;
			}			
		}
		else {
			foreach ($aarray as $value) {
				$result[$value[$groupby]][ucwords($value[$fieldname])]++;
				$keylist[ucwords($value[$fieldname])] = 0;
			}
			//make arrays non-sparse
			foreach ($result as &$value) {
				$value = array_merge($keylist,$value);
			}
		}
		$result = $this->applyParameters($result,$fieldname,$groupby);
		return $result;
	}
}

/**
 * Freshdesk - retrieves all data
 *
 * @package services\serviceImp\Sources\Freshdesk
 */
class FreshdeskSourceAllImp extends \Service\ServiceImpAbstract {
	
	function showData($aarray,$fieldname,$groupby) {
		return $aarray;
	}
}


?>
