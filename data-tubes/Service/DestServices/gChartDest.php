<?php 
/**
 * File for gChart-related services
 *
 * @package services
 */
namespace Service\DestServices;

use googlecharttools\model\Cell;
use googlecharttools\model\Column;
use googlecharttools\model\DataTable;
use googlecharttools\model\Row;
use googlecharttools\view\AreaChart;
use googlecharttools\view\BarChart;
use googlecharttools\view\BubbleChart;
use googlecharttools\view\CandlestickChart;
use googlecharttools\view\ColumnChart;
use googlecharttools\view\ComboChart;
use googlecharttools\view\Gauge;
use googlecharttools\view\GeoChart;
use googlecharttools\view\LineChart;
use googlecharttools\view\PieChart;
use googlecharttools\view\ScatterChart;
use googlecharttools\view\SteppedAreaChart;
use googlecharttools\view\Table;
use googlecharttools\view\TreeMap;
use googlecharttools\view\ChartManager;
use googlecharttools\view\options\Axis;
use googlecharttools\view\options\BackgroundColor;
use googlecharttools\view\options\Bubble;
use googlecharttools\view\options\ChartArea;
use googlecharttools\view\options\ColorAxis;
use googlecharttools\view\options\Legend;
use googlecharttools\view\options\Series;
use googlecharttools\view\options\TextStyle;
use googlecharttools\view\options\Tooltip;

/**
 * gChart destination service.
 *
 * @package services\DestServices
 */
class gChartDest extends DestServiceAbstract {
	
	
	function __construct($outputTypeIn,
						$dataSourceIn,
						$paramsIn=NULL,
						$urlIn=NULL,
						$credentialsIn=NULL,						
						$httpmethodIn=NULL) {
		$this->outputType = $outputTypeIn;
		$this->dataSource = $dataSourceIn;
		$this->url = $urlIn;
		$this->credentials = $credentialsIn;
		$this->params = $paramsIn;
		$this->httpmethod = $httpmethodIn;
		switch ($outputTypeIn) {
			case "pie" :
				$this->dataResultImp = new gChartDestPieImp();
				break;
			case "bar" :
				$this->dataResultImp = new gChartDestBarImp();
				break;
			case "line" :
				$this->dataResultImp = new gChartDestLineImp();
				break;	
			case "area" : 
				$this->dataResultImp = new gChartDestAreaImp();
				break;
			case "stepped" : 
				$this->dataResultImp = new gChartDestSteppedImp();
				break;
			case "gauge" :
				$this->dataResultImp = new gChartDestGaugeImp();
				break;
			case "table" :
				$this->dataResultImp = new gChartDestTableImp();
				break;
			case "geo" :
				$this->dataResultImp = new gChartDestGeoImp();
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
 * gChart - produce pie chart
 *
 * @package services\serviceImp\Destinations\gChart
 */
class gChartDestPieImp extends \Service\ServiceImpAbstract {
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		$sData = $sService->showData();

		if (is_null($sService->groupBy) == FALSE) {
			return "Error. Cannot use groupBy with pie chart.";
		}
		switch($sService->dataType) {
			case "date":
			case "type":				
				$gdata = new DataTable();
				$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));
				$gdata->addColumn(new Column(Column::TYPE_NUMBER,"n","Count of ".$sService->fieldName));

				foreach ($sData as $key=>$value) {
						$item = new Row();
						$item->addCell(new Cell($key))->addCell(new Cell($value));
						$gdata->addRow($item);
				}

				break;
			case "scores" :
				return "invalid type for scores thus far";
				break;
		}

		
		$chart = new PieChart(str_replace(array(" ","."),"_",$sService->fieldName),$gdata);
		$chart->setTitle(ucwords(str_replace("_"," ",$sService->fieldName))." from ".str_replace("Source","",get_class($sService)));
		

		$manager = new ChartManager();
		$manager->addChart($chart);

		return "<html><head>".$manager->getHtmlHeaderCode()."</head><body>".$chart->getHtmlContainer()."</body></html>";
	}
}


/**
 * gChart - produce bar and stacked bar chart
 *
 * @package services\serviceImp\Destinations\gChart
 */
class gChartDestBarImp extends \Service\ServiceImpAbstract {
	function showData($sService,$fieldname = NULL,$groupby=NULL) {

		$sData = $sService->showData();
		switch($sService->dataType) {
			case "date":
			case "type":				
				$gdata = new DataTable();								
				if (is_null($sService->groupBy)) {
					$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));
					$gdata->addColumn(new Column(Column::TYPE_NUMBER,"n","Count of ".$sService->fieldName));
				
					foreach ($sData as $key=>$value) {
							$item = new Row();
							$item->addCell(new Cell($key))->addCell(new Cell($value));
							$gdata->addRow($item);
					}
				
				}
				else {			
					$gdata->addColumn(new Column(Column::TYPE_STRING,"g",$sService->groupBy));
					$othercols = array_keys(reset($sData));
					foreach ($othercols as $value) {
						$gdata->addColumn(new Column(Column::TYPE_NUMBER,substr($value,0,1),$value));
					}
					foreach ($sData as $key=>$value) {							
							$item = new Row();
							$item->addCell(new Cell($key));
							foreach ($value as $key2=>$value2) {
								$item->addCell(new Cell($value2));								
							}
							$gdata->addRow($item);
					}
					$axisy = new Axis($sService->groupBy, new TextStyle("blue"));										
				}
				break;
			case "scores" :
				return "invalid type for scores thus far";
				break;
		}
		$axisx = new Axis("Count of ".$sService->fieldName, new TextStyle("blue"));			
		$chart = new BarChart($sService->fieldName,$gdata);
		if (is_null($sService->groupBy) == FALSE) {
			$chart->setIsStacked(true);
			$chart->setVAxis($axisy);
		}
		$chart->setHAxis($axisx);
		$chart->setTitle(ucwords(str_replace("_"," ",$sService->fieldName))." from ".str_replace("Source","",get_class($sService)));
		$manager = new ChartManager();
		$manager->addChart($chart);

		return "<html><head>".$manager->getHtmlHeaderCode()."</head><body>".$chart->getHtmlContainer()."</body></html>";
	}
}


/**
 * gChart - produce line chart
 *
 * @package services\serviceImp\Destinations\gChart
 */
class gChartDestLineImp extends \Service\ServiceImpAbstract {
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		$sData = $sService->showData();

		switch($sService->dataType) {
			case "date":				
				$gdata = new DataTable();								
				if (is_null($sService->groupBy)) {
					$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));
					$gdata->addColumn(new Column(Column::TYPE_NUMBER,"n","Count of ".$sService->fieldName));
					foreach ($sData as $key=>$value) {
							$item = new Row();
							$item->addCell(new Cell($key))->addCell(new Cell($value));
							$gdata->addRow($item);
					}					
				}
				else {		
					$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));					
					$othercols = array_keys($sData);
					foreach ($othercols as $value) {
						$gdata->addColumn(new Column(Column::TYPE_NUMBER,substr($value,0,1),$value));
					}
					$rowlist = array_keys(reset($sData));
					foreach ($rowlist as $value) {
						$item = new Row();
						$item->addCell(new Cell($value));
						foreach ($sData as $value2) {
							$item->addCell(new Cell ($value2[$value]));
						}
						$gdata->addRow($item);
					}
					$axisy = new Axis($sService->groupBy, new TextStyle("blue"));					
				}
				break;
			case "type":
			case "scores" :
				$result = "invalid type for area charts";
				break;
		}
		$axisx = new Axis("Count of ".$sService->fieldName, new TextStyle("blue"));			
		$chart = new LineChart($sService->fieldName,$gdata);

		
		$chart->setHAxis($axisx);
		$chart->setTitle(ucwords(str_replace("_"," ",$sService->fieldName))." from ".str_replace("Source","",get_class($sService)));
		
		$manager = new ChartManager();
		$manager->addChart($chart);
		
		return "<html><head>".$manager->getHtmlHeaderCode()."</head><body>".$chart->getHtmlContainer()."</body></html>";
	}
}
	
/**
 * gChart - produce area chart
 *
 * @package services\serviceImp\Destinations\gChart
 */
class gChartDestAreaImp extends \Service\ServiceImpAbstract {
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		$sData = $sService->showData();

		switch($sService->dataType) {
			case "date":				
				$gdata = new DataTable();								
				if (is_null($sService->groupBy)) {
					$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));
					$gdata->addColumn(new Column(Column::TYPE_NUMBER,"n","Count of ".$sService->fieldName));
					foreach ($sData as $key=>$value) {
							$item = new Row();
							$item->addCell(new Cell($key))->addCell(new Cell($value));
							$gdata->addRow($item);
					}					
				}
				else {		
					$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));					
					$othercols = array_keys($sData);
					foreach ($othercols as $value) {
						$gdata->addColumn(new Column(Column::TYPE_NUMBER,substr($value,0,1),$value));
					}
					$rowlist = array_keys(reset($sData));
					foreach ($rowlist as $value) {
						$item = new Row();
						$item->addCell(new Cell($value));
						foreach ($sData as $value2) {
							$item->addCell(new Cell ($value2[$value]));
						}
						$gdata->addRow($item);
					}
					$axisy = new Axis($sService->groupBy, new TextStyle("blue"));					
				}
				break;
			case "type":
			case "scores" :
				$result = "invalid type for area charts";
				break;
		}
		$axisx = new Axis("Count of ".$sService->fieldName, new TextStyle("blue"));			
		$chart = new AreaChart($sService->fieldName,$gdata);

		
		$chart->setHAxis($axisx);
		$chart->setTitle(ucwords(str_replace("_"," ",$sService->fieldName))." from ".str_replace("Source","",get_class($sService)));
		
		$manager = new ChartManager();
		$manager->addChart($chart);
		
		return "<html><head>".$manager->getHtmlHeaderCode()."</head><body>".$chart->getHtmlContainer()."</body></html>";
	}
}


/**
 * gChart - produce stepped (cumulative) chart
 *
 * @package services\serviceImp\Destinations\gChart
 */
class gChartDestSteppedImp extends \Service\ServiceImpAbstract {
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		$sData = $sService->showData();

		switch($sService->dataType) {
			case "date":				
				$gdata = new DataTable();								
				if (is_null($sService->groupBy)) {
					$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));
					$gdata->addColumn(new Column(Column::TYPE_NUMBER,"n","Count of ".$sService->fieldName));
					foreach ($sData as $key=>$value) {
							$item = new Row();
							$cumul += $value;
							$item->addCell(new Cell($key))->addCell(new Cell($cumul));
							$gdata->addRow($item);
					}					
				}
				else {		
					$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));					
					$othercols = array_keys($sData);
					foreach ($othercols as $value) {
						$gdata->addColumn(new Column(Column::TYPE_NUMBER,substr($value,0,1),$value));
					}
					$rowlist = array_keys(reset($sData));
					$cumul = array();
					foreach ($rowlist as $value) {
						$item = new Row();
						$item->addCell(new Cell($value));
						foreach ($sData as $key=>$value2) {
							$cumul[$key] += $value2[$value];
							$item->addCell(new Cell ($cumul[$key]));
						}
						$gdata->addRow($item);
					}
					$axisy = new Axis($sService->groupBy, new TextStyle("blue"));					
				}
				break;
			case "type":
			case "scores" :
				$result = "invalid type for area charts";
				break;
		}
		$axisx = new Axis("Count of ".$sService->fieldName, new TextStyle("blue"));			
		$chart = new SteppedAreaChart($sService->fieldName,$gdata);

		
		$chart->setHAxis($axisx);
		$chart->setTitle(ucwords(str_replace("_"," ",$sService->fieldName))." from ".str_replace("Source","",get_class($sService)));
		
		$manager = new ChartManager();
		$manager->addChart($chart);
		
		return "<html><head>".$manager->getHtmlHeaderCode()."</head><body>".$chart->getHtmlContainer()."</body></html>";
	}	
	
}

/**
 * gChart - produce gauge
 *
 * @package services\serviceImp\Destinations\gChart
 */
class gChartDestGaugeImp extends \Service\ServiceImpAbstract {
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		$sData = $sService->showData();
		if (is_null($sService->groupBy) == FALSE) {
			return "Error. Cannot use groupBy with gauge.";
		}
		switch($sService->dataType) {
			case "date":				
			case "type":
				$result = "invalid type for area charts";
				break;
			case "scores" :
				$gdata = new DataTable();								
				if (is_null($sService->groupBy)) {
					$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));
					$gdata->addColumn(new Column(Column::TYPE_NUMBER,"n","Count of ".$sService->fieldName));
					$item = new Row();
					$item->addCell(new Cell($sService->fieldName))->addCell(new Cell($sData["avg"]));
					$gdata->addRow($item);
				}
				else {		
					$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));					
					$othercols = array_keys($sData);
					foreach ($othercols as $value) {
						$gdata->addColumn(new Column(Column::TYPE_NUMBER,substr($value,0,1),$value));
					}
					$rowlist = array_keys(reset($sData));
					$cumul = array();
					foreach ($rowlist as $value) {
						$item = new Row();
						$item->addCell(new Cell($value));
						foreach ($sData as $key=>$value2) {
							$cumul[$key] += $value2[$value];
							$item->addCell(new Cell ($cumul[$key]));
						}
						$gdata->addRow($item);
					}
				}
				break;
				
				break;
		}
		$chart = new Gauge($sService->fieldName,$gdata);
		$chart->setMax($sData["max"]);
		$chart->setMin($sData["min"]);
		
		$manager = new ChartManager();
		$manager->addChart($chart);
		
		return "<html><head>".$manager->getHtmlHeaderCode()."</head><body>".$chart->getHtmlContainer()."</body></html>";
	}	
	
}

/**
 * gChart - produce table
 *
 * @package services\serviceImp\Destinations\gChart
 */
class gChartDestTableImp extends \Service\ServiceImpAbstract {
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		$sData = $sService->showData();
		$gdata = new DataTable();														
		if (is_null($sService->groupBy)) {
			$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));
			$gdata->addColumn(new Column(Column::TYPE_NUMBER,"n","Count of ".$sService->fieldName));
		
			foreach ($sData as $key=>$value) {
					$item = new Row();
					$item->addCell(new Cell($key))->addCell(new Cell($value));
					$gdata->addRow($item);
			}				
		}
		else {			
			$gdata->addColumn(new Column(Column::TYPE_STRING,"g",$sService->groupBy));
			$othercols = array_keys(reset($sData));
			foreach ($othercols as $value) {
				$gdata->addColumn(new Column(Column::TYPE_NUMBER,substr($value,0,1),$value));
			}
			foreach ($sData as $key=>$value) {							
					$item = new Row();
					$item->addCell(new Cell($key));
					foreach ($value as $key2=>$value2) {
						$item->addCell(new Cell($value2));								
					}
					$gdata->addRow($item);
			}
		}
				
		$chart = new Table($sService->fieldName,$gdata);
		$manager = new ChartManager();
		$manager->addChart($chart);
		
		return "<html><head>".$manager->getHtmlHeaderCode()."</head><body>".$chart->getHtmlContainer()."</body></html>";
	}	
	
}

/**
 * gChart - produce map
 *
 * @package services\serviceImp\Destinations\gChart
 */
class gChartDestGeoImp extends \Service\ServiceImpAbstract {
	function showData($sService,$fieldname = NULL,$groupby=NULL) {
		$sData = $sService->showData();

		switch($sService->dataType) {
			case "date":
			case "type":				
				$gdata = new DataTable();
				$gdata->addColumn(new Column(Column::TYPE_STRING,"t",$sService->fieldName));
				$gdata->addColumn(new Column(Column::TYPE_NUMBER,"n","%age of ".$sService->fieldName));
				//to make the scale start at zero...and end at 100.
				$item = new Row();
				$item->addCell(new Cell("ZZ"))->addCell(new Cell(0));
				$gdata->addRow($item);
				
				$item = new Row();
				$item->addCell(new Cell("ZA"))->addCell(new Cell(100));
				$gdata->addRow($item);
				
				foreach ($sData as $key=>$value) {
						$item = new Row();
						if (strpos(get_class($sService),"DrupalStateSource") !== FALSE) {
							$item->addCell(new Cell("US-".$key))
								 ->addCell(new Cell($value));
						}
						else {
							$item->addCell(new Cell("US-".$key))
								 ->addCell(new Cell(round((reset($value)/array_sum($value))*100,1)));
						}
						$gdata->addRow($item);
				}
				break;
			case "scores" :
				return "invalid type for scores thus far";
				break;
		}

		
		$chart = new GeoChart(str_replace(array(" ","."),"_",$sService->fieldName),$gdata);
		$chart->setRegion('US');
		$cAxis = new ColorAxis();
		$colorbar = array("#d7191c","#fdae61","#ffffbf","#a6d96a","#1a9641");
		if ($sService->fieldName=="Unregistered") 
			$cAxis->setColors(array_reverse($colorbar));		
		else
			$cAxis->setColors($colorbar);
			
		$chart->setColorAxis($cAxis);
	
		$chart->setResolution('provinces');
		//$chart->setTitle(ucwords(str_replace("_"," ",$sService->fieldName))." from ".str_replace("Source","",get_class($sService)));
		

		$manager = new ChartManager();
		$manager->addChart($chart);

		return "<html><head>".$manager->getHtmlHeaderCode()."</head><body>".$chart->getHtmlContainer()."</body></html>";
	}
}
?>