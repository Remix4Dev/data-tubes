<?php
/** 
 * File for Service abstract class. 
 *
 * Service classes use a Bridge design pattern. You have a basic service class, and depending on the 
 * data passed in, you have different implementations of returning/receiving data. There is a 
 * two-dimensional variation, e.g., DataSource x Type of Data Requested = formatted array. The 
 * formatted arrays are the same structure for each data type requested, though the source can vary,
 * along with how the source data is retrieved and parsed. The same applies to 
 * DataDestination x Data Representation - the destination varies, as does the formatting to be 
 * represented for that source. * Basic process - DataSource x Type of Data Requested --> 
 * Formatted Array. DataDestination x Data Representation + Formatted Array = Correctly formatted 
 * data to be used in DataDestination for graphic, etc., representation. 
 * 
 * NOTE: To use this and other classes properly script calling them needs an autoload function like
 * what is spec'd in PSR-0.
 * 
 * @package services 
 */ 

namespace Service;
set_time_limit(0);
ini_set('max_input_time', -1);
 
/**
 * Service is a generic model for accessing a data resource. 
 *
 * @package services
 */
abstract class ServiceAbstract {
	/** @var string $url Base URL pointing to the service */
	protected $url;
    /** @var string[] $credentials uname/login info for the service. Used for cURL Basic Auth. */	
	protected $credentials;
	/** @var string[] $params used for optional data manipulation. If using HTTP request method, needs to be nested. key is field name, val is field val. */
	protected $params;
	/** @var string $httpmethod Options are "GET", "POST", etc. */	 
	protected $httpmethod;
	/** @var ServiceImp $dataResultImp Defines proper formatting for returned data. */
	protected $dataResultImp;
	
	public $colors = array("Color","#9BBB59","#4F81BD","#C0504D","#8064A2","#F79646","#4BACC6","#F79646","#9BBB59","#4F81BD","#C0504D","#8064A2","#F79646","#4BACC6","#F79646");		

    /**
     * Executes query on data service.
     */
	abstract protected function httpexec();
    /**
     * wrapper; Service->showData is really Service->ServiceImp->showData 
     */	
	abstract protected function showData();
}


?>