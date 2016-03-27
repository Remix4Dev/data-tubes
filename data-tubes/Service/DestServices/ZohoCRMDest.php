<?php 
/**
 * File for dumping Zoho CRM items. 
 *
 * Currently only accepts Drupal data.
 *
 * @package services
 */
namespace Service\DestServices;



/**
 * Zoho CRM destination service.
 *
 * @package services\DestServices
 */
class ZohoCRMDest extends DestServiceAbstract {	
	
	function __construct($outputTypeIn="insert",
						$dataSourceIn,
						$paramsIn=array('authtoken'=>'TOKENHERE',
								'scope'=>'crmapi',
								'version'=>4,
								'mapfile'=>'mapfile.txt'),
						$urlIn="https://crm.zoho.com/crm/private/xml/Accounts/insertRecords",
						$credentialsIn=NULL,						
						$httpmethodIn="POST") {
		$this->outputType = $outputTypeIn;
		$this->url = $urlIn;
		$this->credentials = $credentialsIn;
		$this->params = $paramsIn;
		$this->httpmethod = $httpmethodIn;
		$this->dataSource = $dataSourceIn;
		switch ($outputTypeIn) {
			case "insert" :
				$this->dataResultImp = new ZohoCRMDestImp($this->url,$this->params,$this->outputType);
				break;
			case "update" :
				$this->dataResultImp = new ZohoCRMDestImp($this->url,$this->params,$this->outputType);
				break;
			default:
				echo "Error. Data object type not defined";
				exit();
		}
	}
	
	
	public function pollexec() {

	}
	

}



/**
 * Zoho CRM - dump drupal data into Zoho.
 *
 * @package services\serviceImp\Destinations\Zoho CRM
 */
class ZohoCRMDestImp extends \Service\ServiceImpAbstract {
	protected $params;
	protected $url;
	protected $rootNode;
	protected $outputType;

	
	function __construct($sUrlIn,$sParams,$sOutputType) {
		$this->url = $sUrlIn;
		$xmlroot = explode("/",$sUrlIn);
		end($xmlroot);
		$this->outputType = $sOutputType;
		$this->rootNode = "<".prev($xmlroot)." />";
		$this->params = $sParams;
	}

	function mapData($sData) {
		if (is_null($sData)) return null;
		$dirname = explode("/",realpath(dirname(__FILE__)));
		array_pop($dirname);
		array_pop($dirname);
		array_pop($dirname);
		$dirname = implode("/",$dirname);
		$mapfilename = $dirname."/mapfiles/".$this->params["mapfile"];
		$mapfilehandle = fopen($mapfilename,"r") or die("unable to access file $mapfilename");
		
		//set up mapfile; use numeric options in case other mappings are used in the future.
		//also set up n/a's as completely separate, as they'll be bulk applied to every item.
		$headers = fgetcsv($mapfilehandle);
				
		while (($data = fgetcsv($mapfilehandle)) !== FALSE) {
			if ($data[0] != "n/a") {
				$mapitems[] = $data;
			}
			else {
				$defaultitems[$data[1]]=$data[3];
			}
		}		
		fclose($mapfilehandle);

		
		foreach ($sData as $value) {	//last item always the id (I hope!)
			$key = end($value);
			foreach ($mapitems as $value2) {
				if (array_key_exists($value2[0],$value)) {
					switch ($value2[2]) {
						case "string2":
							$result[$key][$value2[1]] .= " ".$value[$value2[0]];
							break;
						case "multipicklist":
							$result[$key][$value2[1]] = str_replace(",",";",$value[$value2[0]]);
							break;
						case "percentage":
							$result[$key]["Num. " . $value2[1]] = $value[$value2[0]];
							$result[$key]["Pct. " . $value2[1]] = number_format(($value[$value2[0]] / $value["Total"])*100,1);
							break;
						case "datetime":
							if (empty($value[$value2[0]]) != TRUE) 
								$result[$key][$value2[1]] = date("Y-m-d H:i:s",strtotime($value[$value2[0]]));
							else 
								$result[$key][$value2[1]] = $value[$value2[0]];
							break;
						case "picklist":
							$result[$key][$value2[1]] = ucwords(strtolower($value[$value2[0]]));
							break;
						case "picklistbool":
							$trans = array(0=>"Not Started",1=>"Completed");
							$result[$key][$value2[1]] = strtr($value[$value2[0]],$trans);
							break;
						case "dummy" :
							break;
						default:
							$result[$key][$value2[1]] = $value[$value2[0]];
					}			
				}
			}
			//merge in the default items
			$result[$key] = array_merge($result[$key],$defaultitems);
			//add in accountid if an update
			if ($this->outputType == "update") {
				$result[$key]["Id"] = $value["ACCOUNTID"];
			}
		}
		

		return $result;
	}
	
	function makeXMLChildren($value,$key,$xml) {
		$row = $xml->addChild('row');
		$row->addAttribute('no',$key+1);
		foreach ($value as $key2=>$value2) {
			if(strpbrk($value2,"\"&'<>") !== FALSE) {
				$child = $row->addChildWithCDATA('FL',(string) $value2);
			}
			else 
				$child = $row->addChild('FL',(string) $value2);		
				
			$child->addAttribute('val',$key2);
		}
	}
	
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		if (is_null($sService->showData())) return array();
		$sData = $this->mapData($sService->showData());
		
		$chunks = array_chunk($sData,100);

		//set up ridiculous Zoho XML format, and throttle properly so that there's only 100/post.				
		foreach ($chunks as $key=>$value) {
			$xml[$key] = new SimpleXMLElementExtended($this->rootNode);
			array_walk($value,array($this,"makeXMLChildren"),$xml[$key]);
		}
		
		//iterate and post all chunks to the CRM
		$ch = curl_init ($this->url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POST, true);
		
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$finalcodes = array();
		foreach ($xml as $key=>$value) {
			if (is_null($value) != TRUE) {
				$this->params["xmlData"] = $value->asXML();
				$query = http_build_query($this->params,NULL,'&');			
				curl_setopt ($ch, CURLOPT_POSTFIELDS, $query);
				$returnxml = simplexml_load_string(curl_exec ($ch));
				$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);	//warnings here.				

				if( !preg_match( '/2\d\d/', $http_status ) ) {
					$err = "ERROR: HTTP Status Code == " . $http_status;				
				}
				
				foreach ($returnxml->result->row as $value2) {
					$drupalkey = (string) $value->row[$value2["no"]-1]->FL[0];	//assumes drupal ID/email address is the first item in each mapfile
					if (empty($value->error) != TRUE) {
						$returncodes[$drupalkey]["status"] = "error";
						$returncodes[$drupalkey]["code"] = (int) $value2->error->code;
						$returncodes[$drupalkey]["Id"] = $value;
					}
					else {
						$returncodes[$drupalkey]["status"] = "success";					
						$returncodes[$drupalkey]["code"] = (int) $value2->success->code;
						$returncodes[$drupalkey]["SEID"] = (int) $value2->success->details->FL[0];
					}
				}
			}
			$finalcodes = array_merge($finalcodes,$returncodes);	//to keep track of all the chunks			
		}
		curl_close($ch);

		return $finalcodes;		
	}
}



?>
