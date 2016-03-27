<?php
/**
 * File for Service Implementation Abstract class.
 *
 */
 
namespace Service;

/**
 * Used to produce correctly formatted source data and correctly formatted destination data for APIs
 *
 * NOTE: All dataTypes need cases for DestServices at this point to control valid graph creation 
 * (for example, prevent users from creating a line graph for categorical data.
 *
 * Additionally, formats for source data are:
 * - type: [fieldname value] => count OR [groupby value][fieldname value]=>count
 * - date: [YY-MM] => count OR [groupby value][YY-MM]=>count
 * - score: array(["min"]=>value,["max"]=>value,["avg"]=>value) OR 
 * [groupby value]array(["min"]=>value,["max"]=>value,["avg"]=>value)
 * Sorting is typically alphanumeric, with exception of "status"-type results, which sort as 
 * "Not Started","Started","Completed".
 *
 * @package services\serviceImp
 */
abstract class ServiceImpAbstract {

/**
 * Shows data formatted for whatever purpose (e.g., source data, or data to pass to destinations)
 * 
 * @param SourceService|array $data For SourceService objects, an assoc array. For DestService, a SourceService object.
 * @param string $fieldname For SourceService, the field from which data is to be retrieved.
 * @param string $groupBy (optional) For SourceService, the field by which data should be grouped.
 * 
 * @return array|string If SourceService, an array; if DestService, JSON-formatted string.
 */
	abstract function showData($data,$fieldname,$groupBy);
	
	/**
	 * Module to filter and sort data before returning to requester.
	 *
	 * Should be applied to all inherited classes to ensure consistent support regarding parameter filters.
	 * It transforms data, e.g., filling in dates, cumulative totals, etc.
	 * It does NOT filter data; that should be done in the implementer classes.
	 *
	 * @param mixed[] $aarray The reshaped array that has only the needed data for the request.
	 * @param string $fieldname For SourceService, the field from which data is to be retrieved.
	 * @param string $groupBy (optional) For SourceService, the field by which data should be grouped.	 
	 *
	 * @return mixed[] An associative array of data, with specified transformations applied.
	 */
	public function applyParameters($aarray,$fieldname,$groupby) {

		//if need to fill in dates with no data
		if ($this->datefill) {		
			if (is_null($groupby))
				$firstdate = reset($aarray);
			else {				
				$firstdate = key(reset($aarray));				
				$checkdate = date_parse($firstdate);
				if ($checkdate["error_count"] > 0) {	//probably a groupby using a date. Need to check for blanks in case of assessment_complete_time, etc.
					ksort($aarray);
					$firstdate = key($aarray);
					if ($firstdate == "") {
						$finalitems = reset($aarray);
						array_shift($aarray);					
						$firstdate = key($aarray);
					}
					$fillarray = array_fill_keys(array_keys(reset($aarray)),0);
					$iskeyonly=TRUE;		
				}
			}
			$checkdate = date_parse($firstdate);
			if ($checkdate["error_count"] > 0) die("Issue with this field for date filling. Are you sure the field has dates in it?");
			$currdate = $firstdate;
			while ($currdate <= date("Y-m-d"))	{
				if ($iskeyonly)
					$fulldatetimelist[$currdate] = $fillarray;
				else
					$fulldatetimelist[$currdate] = 0;
				$currdate = date("Y-m-d",strtotime($currdate . " + 1 day"));
			}
			if (is_null($groupby) || $iskeyonly) {
				$aarray = array_merge($fulldatetimelist,$aarray);							
				end($aarray);
				foreach ($aarray[key($aarray)] as $key=>&$value) {
					$value += $finalitems[$key];
				}				
			}
			else {
				foreach ($aarray as &$value) {
					$value = array_merge($fulldatetimelist,$value);
				}
			}
		}
		
		//Replaces 1/0 fields with your choice of items
		if (isset($this->labelreplace)) {
			if (is_null($groupby) || $iskeyonly) {
				array_walk($aarray, function (&$value, $key) { //print_r($value); echo "***"; 
					foreach ($value as $key2=>&$value2) {
						$tmp[$this->labelreplace[$key2]] = $value2;
						unset($value[$key2]);
					}
					$value = $tmp;
					
				});
				//print_r($aarray);
			}
			else {
				foreach ($aarray as $key=>&$value) {
					$tmp[$this->labelreplace[$key]] = $value;
					unset($aarray[$key]);
				}
				$aarray=$tmp;			
			}
		}

		//if you want to show cumulative values
		if ($this->cumulative) {
			$actual_sum = 0;
			if (is_null($groupby)) 
				$aarray = array_map(function ($entry) use (&$actual_sum) { return $actual_sum += $entry; }, $aarray);
			else {
				if ($iskeyonly) {		//have to sum across all elements, not just the first level
					$actual_sums = array_fill_keys(array_keys(end($aarray)),0);					
					$aarray = array_map(function ($entry) use (&$actual_sums) { 
						foreach ($entry as $key=>$value) {
							$actual_sums[$key] += $value;
						}
						return $actual_sums; 
					}, $aarray);
				}
				else
					foreach ($aarray as &$value)
						$value = array_map(function ($entry) use (&$actual_sum) { return $actual_sum += $entry; }, $value);				
			}
		}

		
		
		//sorting; specialized sorting and non-sparse-making if fieldname is "Status"
		if ((strpos($fieldname,"status") !== false) && (!isset($this->filterexact) && !isset($this->filtercontains))) {
			$default = array("Completed"=>0,"Started"=>0,"Not Started"=>0);		
			if (is_null($groupby)) {
				$aarray = array_merge($default,$aarray);			
			}
			else {
				foreach ($aarray as &$value)
					$value = array_merge($default,$value);
				ksort($aarray);	//still need to sort outer level...
			}
		}
		else {
			if (is_null($groupby))
				ksort($aarray);
			else
				foreach ($aarray as &$value)
					ksort($value);
		}

		return $aarray;
	}
}
?>