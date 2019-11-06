<?php
session_start();
require "classmeetupvalidate.php";
/**
 * 
 */
class setupattr extends meetupvalidate
{
    private $userid;
    function __construct(){
	$this->createConnection();
    if(isset($_SESSION["userid"])){
    $this->userid = $this->clean_input($_SESSION["userid"]);
	}
 	}

 	//public function to set attribute starts here
 	public function setattrd($skincolor,$height,$size,$danceskills,$sings){
 	$conn = $this->conn;
 	$uid = $this->userid;
 	$skincolor = $this->clean_input($skincolor);
 	$height = $this->clean_input($height);
 	$size = $this->clean_input($size);
 	$danceskills = $this->clean_input($danceskills);
 	$sings = $this->clean_input($sings);
 	if(empty($skincolor) || empty($size) || empty($height) || empty($danceskills) || empty($uid) || empty($sings)){
    return "Failed missing values to continue";
    }
    $a = array("skincolor"=>$skincolor,"size"=>$size,"height"=>$height,"danceskills"=>$danceskills,"sings"=>$sings);
    $a = json_encode($a);
    $a = $conn->real_escape_string($a);
    $sql = "update oaumeetupusers set attributes = '$a' where userid = '$uid' limit 1";
    if($conn->query($sql) == "true"){
    return "success";
    }else{
    return "Failed".$conn->error;
    }
 	}
 	//public function to set attribute ends here

	
}

?>