<?php 
/**
 * File for Zoho CRM-related services
 *
 * @package services
 */
namespace Service\SourceServices;


/**
 * ZohoCRM source service.
 *
 * @package services\SourceServices
 */
class ZohoCRMSource extends SourceServiceAbstract {
	function __construct($dataTypeIn="all",
							$fieldNameIn = "Drupal ID",
							$groupByIn = NULL,
							$paramsIn=array("queryparams"=>array('authtoken'=>'TOKENHERE',
								'scope'=>'crmapi',
								'selectColumns'=>'Accounts(Account Name,Drupal ID,ACCOUNTID)',
								'criteria'=>'(Industry:WHATEVER*)',
								'newformat'=>1,
								'fromIndex'=>1,
								'toIndex'=>200)),
							$urlIn="https://crm.zoho.com/crm/private/json/Accounts/searchRecords",
							$credentialsIn=NULL,						
							$httpmethodIn="POST") {
			$this->dataType = $dataTypeIn;
			$this->url = $urlIn;
			$this->fieldName = $fieldNameIn;
//			$this->params = $paramsIn;

			if (!is_null($paramsIn)) {
				foreach ($paramsIn as $key=>$value) {
					$this->{$key} = $value;
					
				}	

				if (isset($this->queryparams["selectColumns"]))
					$this->numFields = count(explode(",",$this->queryparams["selectColumns"]));
			}
			$this->httpmethod = $httpmethodIn;
			
			switch ($dataTypeIn) {
				case "single":
				case "all" :
					$this->dataResultImp = new ZohoCRMSourceAllImp();
					break;
				default:
					echo "Error. Type not defined";
					exit();								
			}
	}
	
	/**
	 * Overridden to get rid of ZZ-test accounts pre-emptively.
	 *
	 */
	public function httpexec() {	//should be a POST, submitting JSON, receiving JSON.
		
		$header[] = "Content-type: application/json";
		$ch = curl_init ();
		
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
		
		do {
			curl_setopt($ch, CURLOPT_URL, $this->url."?".http_build_query($this->queryparams,NULL,'&')); 
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
			
			//simplify data and reorder into reasonable fields
			unset($jsondata["response"]["uri"]);	
			while (count($jsondata) < 2) {
				$jsondata = reset($jsondata);
			}			
			
			//If from a single-item search, convert single item to happier array-style setup.				
			//NOTE - May be good to check later what happens if there's 200 + 1 items in the CRM being
			//pulled, as it's possible that this single item returned comes back incorrectly and then allows dupe orgs
			if ($this->dataType == "single") {				
				echo "is single item";
				$jsondata[0] = $jsondata;
				unset($jsondata["no"]);
				unset($jsondata["FL"]);				
			}

			if (!isset($jsondata["code"])) {
				foreach ($jsondata as $key=>&$value) {		
					if (count($value["FL"]) == $this->numFields) {
						foreach ($value["FL"] as $key2=>$value2) {
							$jsondata[$key][$value2["val"]]=$value2["content"];
						}
						unset($value["FL"]);
						unset($value["no"]);
						$finaljson[$jsondata[$key][$this->fieldName]]=$jsondata[$key];

					}
				}
				$this->queryparams["fromIndex"] += 200;
				$this->queryparams["toIndex"]+= 200;

			}
			
		} while (!isset($jsondata["code"]));

		$jsondata = $finaljson;

		curl_close($ch);
		
		$jsondata = $this->applyParameters($jsondata);							
		return $jsondata;
	}		
}



/**
 * ZohoCRM - retrieves all data
 *
 * @package services\serviceImp\Sources\ZohoCRM
 */
class ZohoCRMSourceAllImp extends \Service\ServiceImpAbstract {
	
	function showData($aarray,$fieldname,$groupby) {
		return $aarray;
	}
}


?>
