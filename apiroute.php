<?php
$url_array = explode("/", $_SERVER['REQUEST_URI']);
//I'm getting the link details here and I split with "/"

$indexOfIndexPHP = array_search("apiroute.php", $url_array);
//Get position of apiroute.php in the link in case the tester tests using deep folder

//Routing
if (array_key_exists($indexOfIndexPHP + 1, $url_array) && $url_array[$indexOfIndexPHP + 1] != "") {
	//If url as first parameter and the parameter is not /
	
	require_once "dashboard.php";
			$dashboard = new Dashboard;
			$method = $url_array[$indexOfIndexPHP + 2];
			$parameter = isset($url_array[$indexOfIndexPHP + 3]) ? $url_array[$indexOfIndexPHP + 3]: NULL;
			$dashboard->$method($parameter);
			/*modified so that /apiroute.php/dashboard/totalExpenses/4
				will call the totalExpenses function and insert 4 as parameter 
			*/
}else {
  //home
	echo "This Home, Direct Access Not Allowed";
}
?>