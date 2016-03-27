<?php 
/**
 * File for array-related services
 *
 * Basically just a wrapper to get array data into dest objects. URL is repurposed as data source.
 *
 * @package services
 */
 
namespace Service\SourceServices;

require_once('SourceServiceAbstract.php');

/**
 * Array source service.
 *
 * @package services\SourceServices
 */
class ArraySource extends SourceServiceAbstract {

	function __construct($dataTypeIn,
							$fieldNameIn=NULL,
							$groupByIn = NULL,
							$paramsIn=NULL,
							$dataIn,
							$credentialsIn=NULL,						
							$httpmethodIn=NULL) {
			$this->fieldName = $fieldNameIn;
			$this->groupBy = $groupByIn;
			$this->dataType = $dataTypeIn;
			$this->url = $dataIn;
			$this->credentials = $credentialsIn;
			$this->params = $paramsIn;			
			if (!is_null($paramsIn)) {
				foreach ($paramsIn as $key=>$value) {
					$this->{$key} = $value;
				}	
			}	
			$this->httpmethod = $httpmethodIn;			

			switch ($dataTypeIn) {
				case "all" :
					$this->dataResultImp = new ArraySourceAllImp();
					break;
				default:
					echo "Error. Type not defined";
					exit();								
			}
	}
	
	/**
	 * Overridden as it's filestreams, not HTTP requests. Though it could theoretically by on another server, if URL is passed in.
	 */
	public function httpexec() {		
		$result = $this->applyParameters($this->url);			
		return $result;	
	}
}





/**
 * Array - retrieves all data
 *
 * @package services\serviceImp\Sources\Array
 */
class ArraySourceAllImp extends \Service\ServiceImpAbstract {
	
	function showData($aarray,$fieldname,$groupby) {
		
		//$aarray = $this->applyParameters($aarray,$fieldname,$groupby);			
		return $aarray;
	}
}

?>