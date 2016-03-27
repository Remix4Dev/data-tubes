<?PHP
//This example was used to move data from a specific drupal data source to Zoho CRM. As such, it won't work "out of the box" but may be used as a guide as to how this code could be used.
/**This section needed for all uses of this code**/
function autoload($className)
{

	$subdir = "data-tubes";
	$className = ltrim($className, '\\');
	$fileName  = '';
	$namespace = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	$fileName = $subdir.DIRECTORY_SEPARATOR.$fileName;
	if (!file_exists($fileName)) $fileName = str_replace('.php','.class.php',$fileName);
	require $fileName;
	
}

spl_autoload_register("autoload");

function errHandle($errNo, $errStr, $errFile, $errLine) {
    $msg = "$errStr in $errFile on line $errLine";
    if ($errNo == E_WARNING) {
        $msg = "WARNING: Error triggered! May need to check logs to see what's going on.\r\nMessage was: $msg\r\n";	
		echo $msg;
		if (ERR_EMAIL == "TRUE")
		{	
			echo "Emailing...";
			mail('cbarina@uw.edu','Sync script issue',$msg);
		}
		if (BAIL == "TRUE")
			exit(1);
    } 
}
/**END this section needed for all uses of this code**/

set_error_handler('errHandle');

function split_data($source,$contactmapfile) {		//splits into accounts and contacts
	$dirname = realpath(dirname(__FILE__));
	$mapfilename = $dirname."/mapfiles/".$contactmapfile;
	$mapfilehandle = fopen($mapfilename,"r") or die("unable to access file $mapfilename");
	
	$headers = fgetcsv($mapfilehandle);
	
	while (($data = fgetcsv($mapfilehandle)) !== FALSE) {
		$data = array_combine($headers,$data);
		$mapfile[] = $data["drupal"];
	}
	//remove n/a's for our purposes here
	$mapfile = array_filter($mapfile,function ($element) { return ($element != "n/a"); });
	$mapfile = array_flip($mapfile);
	
	$result = array();
	foreach ($source as $key=>$value) {
		$result["Contacts"][$key] = array_merge(array_intersect_key($value,$mapfile),array("id"=>$key));
		$result["Accounts"][$key] = array_diff_key($value,$mapfile);		
	}
	
	fclose($mapfilehandle);
	
	return $result;
}

function compare_data($source,$dest=array()) {
	//add in related account info for contacts...SEMODULE is there by default, but need linking Account id...
	if (empty($dest) == TRUE) {	
		$results["insert"] = $source;
		return $results;
	}
	foreach ($source as $key=>$value) {
		if (array_key_exists($key,$dest)) {
			$results["update"][$key]["ACCOUNTID"] = $dest[$key]["ACCOUNTID"];
			$results["update"][$key] += $value;
		}
		else
			$results["insert"][$key] = $value;
	}

	return $results;	
}
  
function writelog($xmlreturns,$drupaldata,$zohodata) {
	define("LOG_FILE_DIRECTORY", "./logs");
	define("LOG_FILE", LOG_FILE_DIRECTORY.'/'.'dzSync-' . date("d-m-Y") . ".log");
	
	$message = "";
	foreach ($drupaldata as $key=>$value) {
		foreach ($value as $key2=>$value2) {
			$message .= date("d-m-Y\tH:i\tT\t")."Drupal - number of ".strtolower($key." ".$key2)." pulled: \t".count($value2)."\n";
		}
	}
	$message .= date("d-m-Y\tH:i\tT\t")."Zoho - total number of accounts (lib + state) pulled: \t".count($zohodata)."\n";
	
	$xmlsummary = array();
	foreach ($xmlreturns as $key=>$value) {
		foreach ($value as $key2=>$value2) {
			$xmlsummary[$key][$value2["status"]." = status"]++;
			$xmlsummary[$key][$value2["code"]." = code"]++;
		}
	}
	ksort($xmlsummary);

	foreach ($xmlsummary as $key=>$value) {
		$message .= date("d-m-Y\tH:i\tT\t")."Zoho update results for $key: \t";
		foreach ($value as $key2=>$value2) {
			$message .= "$value2 \t$key2 \t";
		}
		$message .= "\n";
	}

	if (!(file_exists(LOG_FILE)))
		$message = "Date \t Time \t Timezone \t Desc \t Count \t Count-label \t Add'l-Counts \t Add'l-Counts-Labels\n".$message;
	
	file_put_contents(LOG_FILE,$message,FILE_APPEND);

}

date_default_timezone_set('America/Los_Angeles');

if (isset($argv)) {
	foreach ($argv as $arg) {	//Used to pull in CLI args if needed to get parameters.
		$e=explode("=",$arg);
		if(count($e)==2)
			$_GET[$e[0]]=$e[1];
		else    
			$_GET[$e[0]]=0;
	}
}

if (isset($_GET["email"]))
	define('ERR_EMAIL',$_GET["email"]);

if (isset($_GET["bail"]))
	define('BAIL',$_GET["bail"]);

if (isset($_GET["single"])) {

	$keypattern = "/^[A-Z]{2}[0-9]{4}$/";
	if (preg_match($keypattern,$_GET["fscs_key"])) {
		$dlibrequest = new \Service\SourceServices\DrupalSource("single",NULL,NULL,array("queryparams"=>array('fscs_key' => $_GET["fscs_key"],"api-key"=>"APIKEY")),"REST-URL");
		$dresult = split_data($dlibrequest->showData(),"libContactsmap.csv");

		$zohorequest = new \Service\SourceServices\ZohoCRMSource("single","Drupal ID",NULL,array("queryparams"=>array('authtoken'=>'YOURAUTHTOKEN',
								'scope'=>'crmapi',
								'selectColumns'=>'Accounts(Account Name,Drupal ID,ACCOUNTID)',
								'criteria'=>'(FSCS Number:'.$_GET["fscs_key"].')',
								'newformat'=>1,
								'fromIndex'=>1,
								'toIndex'=>1)),
								"https://crm.zoho.com/crm/private/json/Accounts/searchRecords");
		
		$zresult = $zohorequest->showData();
		
		if (count($zresult) == 0)
			die( "Error! Account not found in zoho! exiting.");
		
		//needed solely to get the ACCOUNTID from Zoho for a proper update rather than insert. Ends up in "update".
		$zresult = compare_data($dresult["Accounts"],$zresult);
				
		$zsingleupdate = new \Service\SourceServices\ArraySource("all",NULL,NULL,NULL,$zresult["update"]);
		
		$paramlist = array('authtoken'=>'AUTHKEY',
				'scope'=>'crmapi',
				'version'=>4,
				'newFormat'=>2,
				'mapfile'=>"libAccountsmap.csv");	

		$zohoSyncSingle = new \Service\DestServices\ZohoCRMDest("update",$zsingleupdate,$paramlist,"https://crm.zoho.com/crm/private/xml/Accounts/updateRecords");	
				
		$xmlreturns = $zohoSyncSingle->showData();
		$xmlfirst = reset($xmlreturns);
		$SEID = $xmlfirst["SEID"];

		$libcontactsfixed = new \Service\SourceServices\ArrayDrupalContactSource("all",NULL,NULL,NULL,$dresult["Contacts"]);
		$libcontacts = $libcontactsfixed->showData();

		foreach ($libcontacts as &$value) {
			$value["SEID"] = $SEID;
		}
		
		$zsingleupdate = new \Service\SourceServices\ArraySource("all",NULL,NULL,NULL,$libcontacts);
	
		//queue up contact inserts
		$paramlist = array('authtoken'=>'ZOHOTOKEN',
								'scope'=>'crmapi',
								'version'=>4,
								'newFormat'=>2,
								'mapfile'=>"libContactsmap.csv",
								'duplicateCheck'=>2);

		$zohoSyncSingle = new \Service\DestServices\ZohoCRMDest("insert",$zsingleupdate,$paramlist,"https://crm.zoho.com/crm/private/xml/Contacts/insertRecords");	
	
	
		$xmlreturns = $zohoSyncSingle->showData();	
		exit( "Update for ".$_GET["fscs_key"]." successful. Please close this tab and refresh your CRM page.");
	}
	else
		die("Doesn't match FSCS key pattern, so can't look up.");	
}


$sapi = php_sapi_name();
if ($sapi <> "cli") die("Bulk syncs can only be run in command-line mode! Mode currently is: ".$sapi.". Exiting.");

//first, grab drupal data. NOTE: If there are no libraries that have been updated, the 
//$drupal["lib"] option will return a null array and throw warnings all over the place. 
//This doesn't affect the state lib updates; it just looks bad if you run it on a console.
if (isset($_GET["timestamp"])) {
	echo "***Timestamp-based sync in progress for data since ".date("r",$_GET["timestamp"]).". This may take a while...***\r\n";
	$queryparams["timestamp"]=$_GET["timestamp"];
	$queryparams["api-key"]="wD2uE5CmnY8XTVWKqWDWHBxJ";
	$librequest = new \Service\SourceServices\DrupalSource("all",NULL,NULL,array("queryparams"=>$queryparams));
}
else
	$librequest = new \Service\SourceServices\DrupalSource("all");
$staterequest = new \Service\SourceServices\DrupalStateSource("all",NULL,NULL);
//then, break into account data and contact data, to match how data gets put into Zoho.
//have to keep lib and state data separate due to different map files for fields; data is updated as orgs first, then contacts.

//print_r($staterequest->showData());
$drupal["state"] = split_data($staterequest->showData(),"stateContactsmap.csv");
$drupal["lib"] = split_data($librequest->showData(),"libContactsmap.csv");

//then, grab Zoho data
$zohorequest["Accounts"] = new \Service\SourceServices\ZohoCRMSource();

//cache otherwise it does httpexec every time...											
$zoho["all"]["Accounts"] = $zohorequest["Accounts"]->showData();

															
//Set up Zoho Contacts (empty array, as we don't do comparisons, just inserts)
//doing this because while you could retrieve and compare, it would exceed the API call limit
$zohorequest["Contacts"] = new \Service\SourceServices\ArraySource("all",NULL,NULL,NULL,array());
$zoho["all"]["Contacts"] = $zohorequest["Contacts"]->showData();
			
foreach ($drupal as $key=>$value) {
	//split data into insert/update items, starting with Accounts
	if (array_key_exists("Accounts",$value))
		$value["Accounts"] = compare_data($value["Accounts"],$zoho["all"]["Accounts"]);
	if (isset($value["Accounts"]["update"])) {
		$value["update"]["Accounts"] = new \Service\SourceServices\ArraySource("all",NULL,NULL,NULL,$value["Accounts"]["update"]);
		//queue up org updates
		$paramlist = array('authtoken'=>'ZOHOTOKEN',
						'scope'=>'crmapi',
						'version'=>4,
						'newFormat'=>2,
						'mapfile'=>$key."Accountsmap.csv");	
		
		$zohoSync[$key."-account-update"] = new \Service\DestServices\ZohoCRMDest("update",$value["update"]["Accounts"],$paramlist,"https://crm.zoho.com/crm/private/xml/Accounts/updateRecords");			
	}
	if (isset($value["Accounts"]["insert"])) {
		$value["insert"]["Accounts"] = new \Service\SourceServices\ArraySource("all",NULL,NULL,NULL,$value["Accounts"]["insert"]);		
		//queue up org inserts
		$paramlist = array('authtoken'=>'ZOHOTOKEN',
						'scope'=>'crmapi',
						'version'=>4,
						'newFormat'=>2,
						'mapfile'=>$key."Accountsmap.csv");	
						
		$zohoSync[$key."-account-insert"] = new \Service\DestServices\ZohoCRMDest("insert",$value["insert"]["Accounts"],$paramlist,"https://crm.zoho.com/crm/private/xml/Accounts/insertRecords");	
	}	
}

//run all account updates and inserts, grabbing seids - add log-writing later.
$seids = array();
foreach ($zohoSync as $key=>$value) {
	$xmlreturns[$key] = $value->showData();
	$seids = array_merge($seids,$xmlreturns[$key]);
}

//filter out bad seids to prevent related contacts from being synced
$seids = array_filter($seids, function ($element) { return ($element["status"] == "success"); });

foreach ($drupal as $key=>$value) {
	//dummy compare here - should result in all inserts, as zohoContacts is (for now) null
	$value["Contacts"] = compare_data($value["Contacts"],$zohoContacts=NULL);
	
	//if it's library updates, and there are actual contacts to insert...
	if (($key == "lib") and (!empty($value["Contacts"]["insert"])))  {
		//first fix up data, as it's all weird.
		$libcontacts = new \Service\SourceServices\ArrayDrupalContactSource("all",NULL,NULL,NULL,$value["Contacts"]["insert"]);
		$value["Contacts"]["insert"] = $libcontacts->showData();
		foreach ($value["Contacts"]["insert"] as &$value2) {
			if (empty($value2["id"])) unset($value2);
			else {
				if (array_key_exists($value2["id"],$seids)) {
					$value2["SEID"] = $seids[$value2["id"]]["SEID"];
				}
				else {	//unset - some issue with the org
					unset($value2);
				}
			}
		}
		//done to make stats right in log
		$drupal["lib"]["Contacts"] = $value["Contacts"]["insert"];
	}
	else if ($key != "lib") {	//otherwise, if state library contacts (always exist to insert)
		//merge new ids into contacts so they get associated properly...
		$value["Contacts"]["insert"] = array_merge_recursive($value["Contacts"]["insert"],$seids);
	}
	
	//then, only do the following if you actually has contacts to insert...	
	if (isset($value["Contacts"]["insert"])) {
		//filter out ones with no SEIDs, or no IDs, as there's an account problem with them...
		$value["Contacts"]["insert"] = array_filter($value["Contacts"]["insert"], 
							function ($element) {return ((empty($element["SEID"]) !== TRUE) && (empty($element["id"]) !== TRUE) ); });
			
		$value["insert"]["Contacts"] = new \Service\SourceServices\ArraySource("all",NULL,NULL,NULL,$value["Contacts"]["insert"]);
		
		//queue up contact inserts
		$paramlist = array('authtoken'=>'ZOHOTOKEN',
								'scope'=>'crmapi',
								'version'=>4,
								'newFormat'=>2,
								'mapfile'=>$key."Contactsmap.csv",
								'duplicateCheck'=>2);

		$zohoSync[$key."-insert"] = new \Service\DestServices\ZohoCRMDest("insert",$value["insert"]["Contacts"],$paramlist,"https://crm.zoho.com/crm/private/xml/Contacts/insertRecords");	
		
		//go ahead and execute.
		$xmlreturns[$key."-contact-insert"] = $zohoSync[$key."-insert"]->showData();	
	}
}

writelog($xmlreturns,$drupal,$zoho["all"]["Accounts"]);


?>
