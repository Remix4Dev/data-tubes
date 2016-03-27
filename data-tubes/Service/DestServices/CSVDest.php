<?php 
/**
 * File for dumping CSV items. 
 *
 * Doesn't do anything but dump an array into a file, and only the first two levels currently.
 * url should be the filename to use. 
 *
 * @package services
 */
namespace Service\DestServices;

/**
 * CSV destination service.
 *
 * @package services\DestServices
 */
class CSVDest extends DestServiceAbstract {
	
	
	function __construct($outputTypeIn,
						$dataSourceIn,
						$paramsIn=NULL,
						$urlIn="test1.csv",
						$credentialsIn=NULL,						
						$httpmethodIn=NULL) {
		$this->outputType = $outputTypeIn;
		$this->dataSource = $dataSourceIn;
		$this->url = $urlIn;
		$this->credentials = $credentialsIn;
		$this->params = $paramsIn;
		$this->httpmethod = $httpmethodIn;
		switch ($outputTypeIn) {
			case "all" :
				$this->dataResultImp = new CSVDestAllImp($this->url);
				break;
			case "single" :
				$this->dataResultImp = new CSVDestSingleImp($this->url);
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
 * CSV - dump entire response set into a csv.
 *
 * @package services\serviceImp\Destinations\CSV
 */
class CSVDestAllImp extends \Service\ServiceImpAbstract {
	protected $filename;
	function __construct($filenamein) {	
		$dirname = explode("/",realpath(dirname(__FILE__)));
		array_pop($dirname);
		array_pop($dirname);
		$dirname = implode("/",$dirname);
		$this->filename = $dirname."/crondata/".$filenamein;
	}
	
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		if (is_null($sService->groupBy) == FALSE) {
			return "Error. Cannot use groupBy with full data dump.";
		}
		$sData = $sService->showData();
		$handle = fopen($this->filename,"w") or die("unable to access file");
		$headers = array_keys(reset($sData));
		fputcsv($handle,$headers);
		foreach ($sData as $row) {
			fputcsv($handle,$row);
		}
		fclose($handle);
		touch($this->filename);
		echo "File successfully dumped to $this->filename.". PHP_EOL;
	}
}


/**
 * CSV - dump a single field into a csv (in summary form)
 *
 * @package services\serviceImp\Destinations\CSV
 */
class CSVDestSingleImp extends \Service\ServiceImpAbstract {
protected $filename;
	function __construct($filenamein) {	
		$dirname = explode("/",realpath(dirname(__FILE__)));
		array_pop($dirname);
		array_pop($dirname);
		$dirname = implode("/",$dirname);
		$this->filename = $dirname."/crondata/".$filenamein;
	}
	
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		$sData = $sService->showData();
		$handle = fopen($this->filename,"w") or die("unable to access file");
		if (is_null($sService->groupBy)) {
			$headers = array($sService->fieldName,"count");			
			fputcsv($handle,$headers);
			foreach ($sData as $key=>$value) {
				$row = array($key,$value);
				fputcsv($handle,$row);
			}
		}
		else {
			switch($sService->dataType) {
				case "type":
					$headers = array_merge(array($sService->groupBy),array_keys(reset($sData)));
					fputcsv($handle,$headers);
					foreach ($sData as $key=>&$value) {
						array_unshift($value,$key);
						fputcsv($handle,$value);
					}
					break;
				case "date" :			
					$headers = array_merge(array($sService->fieldName),array_keys($sData));						
					fputcsv($handle,$headers);
					foreach ($sData as $key=>$value) {
						foreach ($value as $key2 => $value2) {
							$row[$key2][] = $value2;
						}
					}	
					foreach ($row as $key=>&$value) {
						array_unshift($value,$key);
						fputcsv($handle,$value);
					}
					break;			
				default:
					echo "Error. can't handle non-type or non-date data sources";
					break;
			}
		}
		fclose($handle);
		touch($this->filename);
		echo "File successfully dumped to $this->filename.". PHP_EOL;
	}
}


?>