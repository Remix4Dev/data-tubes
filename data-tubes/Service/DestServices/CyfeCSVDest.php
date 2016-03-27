<?php 
/**
 * File for dumping Cyfe CSV items. 
 *
 * Some slightly differently formatted items to use Cyfe's CSV conventions.
 *
 * @package services
 */
namespace Service\DestServices;

/**
 * CyfeCSV destination service.
 *
 * @package services\DestServices
 */
class CyfeCSVDest extends DestServiceAbstract {
	
	
	function __construct($outputTypeIn,
						$dataSourceIn,
						$paramsIn=NULL,
						$urlIn="test1.csv",
						$credentialsIn=NULL,						
						$httpmethodIn=NULL) {
		$this->outputType = $outputTypeIn;
		$this->dataSource = $dataSourceIn;	
		$this->credentials = $credentialsIn;
		if (!is_null($paramsIn)) {
			foreach ($paramsIn as $key=>$value) {
				$this->{$key} = $value;
			}	
		}	

		if ($this->complexdata) {	//use datetime for filename
			$withoutExt = preg_replace("/\\.[^.\\s]{3,4}$/", "", $urlIn);
			$dirname = explode("/",realpath(dirname(__FILE__)));
			array_pop($dirname);
			array_pop($dirname);
			$dirname[]="crondata";
			$dirname[]=$withoutExt;
			$dirname = implode("/",$dirname);
			if (!is_dir($dirname)) {
				mkdir($dirname,0755,true);			
			}
			$this->url = $withoutExt."/".$withoutExt.date("Ymd").".csv";
		}
		else		
			$this->url = $urlIn;
		$this->httpmethod = $httpmethodIn;
		switch ($outputTypeIn) {
			case "all" :
				$this->dataResultImp = new CyfeCSVDestAllImp($this->url);
				break;
			case "single" :				
				$this->dataResultImp = new CyfeCSVDestSingleImp($this->url);
				break;
			case "pie" :				
				$this->dataResultImp = new CyfeCSVDestPieImp($this->url);
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
 * CyfeCSV - dump entire response set into a csv.
 *
 * @package services\serviceImp\Destinations\CyfeCSV
 */
class CyfeCSVDestAllImp extends \Service\ServiceImpAbstract {
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
		fputcsv($handle,$headers,","," ");
		foreach ($sData as $row) {
			fputcsv($handle,$row,","," ");
		}
		fclose($handle);
		touch($this->filename);
		return "File successfully dumped to $this->filename.";
	}
}


/**
 * CyfeCSV - dump a single field into a csv (in summary form)
 *
 * @package services\serviceImp\Destinations\CyfeCSV
 */
class CyfeCSVDestSingleImp extends \Service\ServiceImpAbstract {
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
		foreach ($sData as $key=>$value) {
			//if (!is_array($value)) { print_r($value);exit;}
		}
		$keysearch = reset($sData);
		if (!is_int($keysearch)) {
			$keys = array_keys(reset($sData));
			$n=array_search("Started",$keys);
			if ($n != NULL) {
				$keys[$n]="In Progress";
			}
		}
		$handle = fopen($this->filename,"w") or die("unable to access file");
		if (is_null($sService->groupBy)) {			//no groupBy
			if ($sService->dataType == "all") {
				$headers = $keys;
				fputcsv($handle,$headers,","," ");

				foreach ($sData as $key=>$value) {
					$row = $value;
					fputcsv($handle,$value,","," ");
				}
			}
			else {
				$headers = array($sService->fieldName,"count");			
				fputcsv($handle,$headers,","," ");
				foreach ($sData as $key=>$value) {
					$row = array($key,$value);
					fputcsv($handle,$row,","," ");
				}
			}
			fputcsv($handle,$sService->colors,","," ");
			if (!$sService->cumulative) {
				$cumul = array_fill_keys(array_keys($row),1);
				array_unshift($cumul,"LabelShow");
				fputcsv($handle,$cumul,","," ");
			}
		}

		else {										//uses groupBy
			switch($sService->dataType) {
				case "type":
					if (strtotime(end(array_keys($sData))) != FALSE) {
						$headers = array_merge(array("Date"),$keys);						
					}
					else
						$headers = array_merge(array($sService->groupBy),$keys);
					fputcsv($handle,$headers,","," ");
					$oldkey = date("Ymd");
					foreach ($sData as $key=>&$value) {						
							$newkey = $key;
							$newvalue = $value;
							array_unshift($newvalue,$newkey);
							fputcsv($handle,$newvalue,","," ");
							array_shift($newvalue);
					}
					$cumul = array_fill_keys(array_keys($newvalue),1);
					if ($sService->cumulative) { //label for cumulative values...		
						array_unshift($cumul,"Cumulative");
						fputcsv($handle,$cumul,","," ");
						array_shift($cumul);
					}
					else {
						//various Cyfe labellings
						array_unshift($cumul,"LabelShow");
						fputcsv($handle,$cumul,","," ");
					}
					fputcsv($handle,$sService->colors,","," ");
					break;
				case "date" :	
					$keys = array_keys($sData);
					$n=array_search("Started",$keys);
					if ($n != NULL) {
						$keys[$n]="In Progress";
					}				
					$headers = array_merge(array("Date"),$keys);						
					fputcsv($handle,$headers,","," ");
					foreach ($sData as $key=>$value) {
						foreach ($value as $key2 => $value2) {							
							$row[$key2][] = $value2;
						}
					}	
					foreach ($row as $key=>&$value) {
						$newkey = date("Ymd",strtotime($key));
						array_unshift($value,$newkey);
						fputcsv($handle,$value,","," ");
					}
					$cumul = array_fill_keys(array_keys($keys),1);
					if ($sService->cumulative) { //label for cumulative values...		
						array_unshift($cumul,"Cumulative");
						fputcsv($handle,$cumul,","," ");
						array_shift($cumul);
					}
					break;			
				default:
					return "Error. can't handle non-type or non-date data sources";
					break;
			}
		}
		fclose($handle);
		touch($this->filename);
		echo "File successfully dumped to $this->filename.". PHP_EOL;
	}
}


/**
 * CyfeCSV - dump a single field into a csv (in summary form)
 *
 * Basically the same as single, but with rows/cols flipped for non-grouped data.
 *
 * @package services\serviceImp\Destinations\CyfeCSV
 */
class CyfeCSVDestPieImp extends \Service\ServiceImpAbstract {
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
		$keys = array_keys($sData);
		$n=array_search("Started",$keys);
		if ($n != NULL) {
			$keys[$n]="In Progress";	//clean this up later - shouldn't need to use both $keys and $sdata
			$sData["In Progress"] = $sData["Started"];
			unset($sData["Started"]);
			sort($keys);
			ksort($sData);
		}
		$handle = fopen($this->filename,"w") or die("unable to access file");
		if (is_null($sService->groupBy)) {
			if ($sService->dataType == "all") {
				$headers = $keys;
				fputcsv($handle,$headers,","," ");
				foreach ($sData as $key=>$value) {
					$row = $value;
					fputcsv($handle,$value,","," ");
				}
			}
			else {				
				$headers = $keys;			
				fputcsv($handle,$headers,","," ");
				foreach ($sData as $key=>$value) {
					$row[] = $value;					
				}
				fputcsv($handle,$row,","," ");
			}
			fputcsv($handle,$sService->colors);
			if (!$sService->cumulative) {
				$cumul = array_fill_keys(array_keys($row),1);
				array_unshift($cumul,"LabelShow");
				fputcsv($handle,$cumul,","," ");
			}
		}
		else {
			exit("Cannot use GroupBy with pie charts...");
		}
		fclose($handle);
		touch($this->filename);
		echo "File successfully dumped to $this->filename.". PHP_EOL;
	}
}

?>