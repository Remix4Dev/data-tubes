<?php 
/**
 * File for csv-related services
 *
 * @package services
 */
 
namespace Service\SourceServices;

require_once('SourceServiceAbstract.php');

/**
 * CSV source service.
 *
 * @package services\SourceServices
 */
class CSVSource extends SourceServiceAbstract {

	function __construct($dataTypeIn,
							$fieldNameIn,
							$groupByIn = NULL,
							$paramsIn=NULL,
							$urlIn="FILENAMEHERE.csv",
							$credentialsIn=NULL,						
							$httpmethodIn=NULL) {
		$this->fieldName = $fieldNameIn;
		$this->groupBy = $groupByIn;
		$this->dataType = $dataTypeIn;

		$dirname = explode("/",realpath(dirname(__FILE__)));
		array_pop($dirname);
		array_pop($dirname);
		$dirname = implode("/",$dirname);
		$this->url = $dirname."/crondata/".$urlIn;
		$this->credentials = $credentialsIn;
		$this->params=$paramsIn;
		if (!is_null($paramsIn)) {
			foreach ($paramsIn as $key=>$value) {
				$this->{$key} = $value;
			}	
		}
		$this->httpmethod = $httpmethodIn;			

		switch ($dataTypeIn) {
			case "date" :
				$this->dataResultImp = new CSVSourceDateImp($this->params);
				break;
			case "scores" :
				$this->dataResultImp = new CSVSourceScoreImp($this->params);
				break;
			case "type" :
				$this->dataResultImp = new CSVSourceTypeImp($this->params);
				break;
			case "all" :
				$this->dataResultImp = new CSVSourceAllImp();
				break;
			default:
				echo "Error. Type not defined";
				exit();								
		}
	}
	
	/**
	 * Overridden as it's filestreams, not HTTP requests. Though it could theoretically by on another server, if URL is passed in.
	 * Still uses applyParameters, though.
	 */
	public function httpexec() {
		$handle = fopen($this->url,"r") or die ("file not found");		
		$headers = fgetcsv($handle);		
		while ($line=fgetcsv($handle)) {
			$result[] = array_combine($headers,$line);
		}
		$result = $this->applyParameters($result);			
		return $result;	
	}
}


/**
 * CSV - retrieves date data
 *
 * @package services\serviceImp\Sources\CSV
 */
class CSVSourceDateImp extends \Service\ServiceImpAbstract {
	
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
				$datetimelist[date("Y-m-d",strtotime($value[$fieldname]))]++;	
			}
		}
		else {
			foreach ($aarray as $value) {	//first, set up keys
				$datetimelist[$value[$groupby]][date("Y-m-d",strtotime($value[$fieldname]))]++;	
				$keylist[date("Y-m-d",strtotime($value[$fieldname]))] = 0;
			}								
			//then, merge in groupby data...
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
 * CSV - retrieves score data
 *
 * @package services\serviceImp\Sources\CSV
 */
class CSVSourceScoreImp extends \Service\ServiceImpAbstract {
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
 * CSV - retrieves categorical (type) data
 *
 * @package services\serviceImp\Sources
 */
class CSVSourceTypeImp extends \Service\ServiceImpAbstract {
	
	function __construct($paramsIn = NULL) {
		if (!is_null($paramsIn)) {
			foreach ($paramsIn as $key=>$value) {
				$this->{$key} = $value;
			}	
		}
	}
	
	function showData($aarray,$fieldname,$groupby) {		
		//print_r($aarray);
		if (is_null($groupby)) {
			foreach ($aarray as $value) {	
				$result[ucwords($value[$fieldname])]++;
			}			
		}
		else {		//groupby option
			foreach ($aarray as &$value) {
				if (strtotime($value[$groupby]) <> FALSE) {	//reformat groupby to a more simple datetime format. May have to update for other types too.
					$value[$groupby] = date("Y-m-d",strtotime($value[$groupby]));					
				}
				$result[$value[$groupby]][ucwords($value[$fieldname])]++;

			$keylist[ucwords($value[$fieldname])] = 0;				
			}			


			if (!isset($this->filterexact) && !isset($this->filtercontains)) {
				foreach ($result as &$value) {				
					$value = array_merge($keylist,$value);
				}
			}	
		}		
			
			
		//print_r($result);
		
		$result = $this->applyParameters($result,$fieldname,$groupby);
		
		return $result;
	}
}


/**
 * CSV - retrieves all data
 *
 * @package services\serviceImp\Sources\CSV
 */
class CSVSourceAllImp extends \Service\ServiceImpAbstract {
	
	function showData($aarray,$fieldname,$groupby) {
		return $aarray;
	}
}

?>
