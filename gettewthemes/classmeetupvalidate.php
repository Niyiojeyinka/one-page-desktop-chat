<?php
require_once "conn.php";
/**
 * this class is to validate user on every page
 */
class meetupvalidate extends conn
{ 
	protected $fonttype;
    protected $readstorylist;
    protected $storiesfollowed;
    protected $followers;
    protected $gender;
    protected $mpref;
    protected $lastnotes;


    public function cleanuseless(){
    }
    
    public function cleanSession(){
    //for fchat starts here
    /*if(isset($_SESSION["chatid"]) && isset($_SESSION["partnerid"])){
	unset($_SESSION["partnerid"]);
	unset($_SESSION["chatid"]);
	}
    if(isset($_SESSION["iscreatorchat"])){
    unset($_SESSION["iscreatorchat"]);
    }
    if(isset($_SESSION["iscreatorblocked"])){
    unset($_SESSION["iscreatorblocked"]);
    }
    if(isset($_SESSION["isrecepientblocked"])){
    unset($_SESSION["isrecepientblocked"]);
    }
    if(isset($_SESSION["m"])){
	unset($_SESSION["m"]);
    }
    //for fchat ends here
    //for achat starts here
     if(isset($_SESSION["toseeprofilepicid"]) && isset($_SESSION["tonotseeprofilepicid"]) && isset($_SESSION["achatid"])){
     unset($_SESSION["toseeprofilepicid"]);
     unset($_SESSION["tonotseeprofilepicid"]);
     unset($_SESSION["achatid"]);
     }
     if(isset($_SESSION["am"])){
     unset($_SESSION["am"]);
     }
     if(isset($_SESSION["iscreatorachat"])){
     unset($_SESSION["iscreatorachat"]);
     }
     if(isset($_SESSION["isacreatorblocked"])){
     unset($_SESSION["isacreatorblocked"]);
     }
     if(isset($_SESSION["isarecepientblocked"])){
     unset($_SESSION["isarecepientblocked"]);
     }*/
    //for achat ends here

    }


	public function validateUser(){
	//to Unset Useless Session
	if(isset($_SESSION["userid"])){
	$conn = $this->conn;
	$uid = $this->clean_input($_SESSION["userid"]);
	$uname = $this->clean_input($_SESSION["uname"]);
	$password = $this->clean_input($_SESSION["pass"]);
	$sql = "select gender,meetup_preference,notescheckdate,fontpref,readstorylist,storiesfollowed,followers,attributes from oaumeetupusers where userid = '$uid' and username='$uname' and password = '$password' and activated = '1' limit 1";
    $date = time();
    $sql2 = "update oaumeetupusers set lastlogindate ='$date' where userid ='$uid' limit 1";
	$result = $conn->query($sql);
	if ($result->num_rows == 1) {
    $conn->query($sql2);
    $this->updatestat();
    $this->updatexiststory();
	if($row = $result->fetch_assoc()){
    $this->gender = $row["gender"];
    $this->mpref = $meetup_preference = $row["meetup_preference"];
    $this->lastnotes = $lastnotes = $row["notescheckdate"];
	$this->fonttype = $row["fontpref"];
    $this->readstorylist = $row["readstorylist"];
    $this->storiesfollowed = $row["storiesfollowed"];
    $this->followers = $row["followers"];
    $attributes = $row["attributes"];

    if(empty($this->readstorylist)){
    $this->readstorylist = array();
    }else{
    $this->readstorylist = json_decode($this->readstorylist,true);
    }
    if(empty($this->lastnotes)){
    $this->lastnotes = 0;
    }
    
    if(empty($this->storiesfollowed)){
    $this->storiesfollowed = array();
    }else{
    $this->storiesfollowed = json_decode($this->storiesfollowed,true);
    }
    
    if(empty($this->followers)){
    $this->followers = array();
    }else{
    $this->followers = json_decode($this->followers,true);
    }

	if(empty($this->fonttype)){
    if(empty($this->fonttype) &&  basename($_SERVER["REQUEST_URI"]) != "selectfont.php"){
    header("location:selectfont.php");
    exit();    
    }
    }elseif(empty($meetup_preference)){
    if(empty($meetup_preference) &&  basename($_SERVER["REQUEST_URI"]) != "meetupprefrence.php"){
    header("location:meetupprefrence.php");
    exit();    
    }
    }elseif(empty($attributes)){
    if(empty($attributes) && basename($_SERVER["REQUEST_URI"]) != "setattributes.php"){
    header("location:setattributes.php");
    exit();
    }
    }



    /*if){
   
    }else{
    $meetup_preference = json_decode($meetup_preference,true);
    if((!is_array($meetup_preference) || count($meetup_preference) < 1) && basename($_SERVER["REQUEST_URI"]) != "meetupprefrence.php"){
    header("location:meetupprefrence.php");
    exit();
    }
    }

    if(empty($attributes) && basename($_SERVER["REQUEST_URI"]) != "setattributes.php"){
    header("location:setattributes.php");
    }else{
    $attributes = json_decode($attributes,true);
    if((!is_array($attributes) || count($attributes) < 1) && basename($_SERVER["REQUEST_URI"]) != "setattributes.php"){
    header("location:setattributes.php");
    }
    }*/


	}//fetch_assoc()
    $this->createCookie($this->fonttype);
	return 'true';
	}else{
    unset($_SESSION["userid"]);
	unset($_SESSION["uname"]);
	unset($_SESSION["pass"]);
	session_destroy();
   return 'false';
	}
	}else{
    return 'false';
	}
	
	/*closing braces for function*/}
	//public function to set font if user is verified
	public function getFont(){
	return $this->fonttype;
    }
    public function getReadStoryList(){
    return $this->readstorylist;
    }

    public function createCookie($data){
	setcookie("fontpref", "$data",time()+(86400*30),"/");
    }

    //public function to insert web stats here
    public function updatestat(){
    $conn = $this->conn;
    $date = time();
    $num = 0;
    $page = basename($_SERVER["REQUEST_URI"]);
    $sql = "select * from websitestats where $date - date < '86400' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $id = $row["id"];
    $webstat = $row["webstat"];
    if(empty($webstat)){
    $webstat = array();
    }else{
    $webstat = json_decode($webstat,true);
    }
    if(array_key_exists($page, $webstat)){
    $num = $webstat[$page];
    ++$num;
    $webstat[$page] = $num;
    }else{
    ++$num;
    $webstat[$page] = $num;
    }
    $webstat = $conn->real_escape_string(json_encode($webstat));
    $sql = "update websitestats set webstat = '$webstat' where id ='$id' limit 1";
    $conn->query($sql);
    }else{
    $webstat = array();
    $webstat[$page] = 1;
    $webstat = $conn->real_escape_string(json_encode($webstat));
    $sql = "insert into websitestats(webstat,date) values('$webstat','$date')";
    $conn->query($sql);
    }
    
    }
    //public function to insert web ends here

    //public function to get update expired story starts here
    public function updatexiststory(){
    $conn = $this->conn;
    $date = time();
    $sql = "update stories set expired = '1' where $date - date > '86400'";
    $conn->query($sql);
    }
    //public function to get update expired story starts here

	/*function for cleaning data input**/
	public function clean_input($data){
	 $conn = $this->conn;
     $data = trim($data); 
     $data = strip_tags($data); 
     $data = stripslashes($data);  
     $data = htmlentities($data); 
     $data = $conn->real_escape_string($data);
     return $data;   
	}
    /*function for cleaning data input**/



    


}
?>