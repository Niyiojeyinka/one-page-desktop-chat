<?php
session_start();
require 'classmeetupvalidate.php';
/**
 * $_SESSION["adminusernum"] $_SESSION["webstatsnum"]
 */
class adminin extends meetupvalidate
{
	private $admin;
	
	function __construct()
	{
	$this->createConnection();
	}
	//public function to get users starts here
	public function getuser(){
	$conn = $this->conn;
	$sql = "select id,name,username,email,phonenumber,institution,gender,signupdate,avatar,lastlogindate from oaumeetupusers order by id desc limit 10";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	$n = 0;
	$data = "";
	$num_rows = $result->num_rows;
	while($row = $result->fetch_assoc()){
	++$n;
	if($n == $num_rows){
	$_SESSION["adminusernum"] = $row["id"];
	}
	$id = $row["id"];
    $name = $row["name"];
    $uname = $row["username"];
    $email = $row["email"];
    $number = $row["phonenumber"];
    $institutio = $row["institution"];
    $gender = $row["gender"];
    $signupdate = strftime("%a %b %d %Y @ %I:%M%p",$row["signupdate"]);
    $avatar = $row["avatar"];
    if(empty($avatar) && $gender == "male"){
    $avatar= "maledefault.png";
    }else if(empty($avatar) && $gender == "female"){
    $avatar = "femaledefault.jpeg";
    }
    $lastlogindate = strftime("%a %b %d %Y @ %I:%M%p",$row["lastlogindate"]);
    $data .="<li class='w3-bar w3-center w3-card-4 w3-ripple w3-panel w3-round-large w3-animate-zoom w3-display-container' style='margin-left:auto;margin-right:auto;'>
    <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-circle lazyload w3-margin w3-card-4'style='width:85px;height:85px;padding:2px;border: 2px solid #2196F3;'/>
   <div class ='w3-bar-item' style='font-family:cursive;width:100%; padding: 0;word-spacing: 8px;'>
    <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> Username ~</span>
   <span class='w3-text-blue'style='font-family: cursive;'>$uname</span><br>
   <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> name ~</span>
   <span class='w3-text-blue'style='font-family: cursive;'>$name</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> email ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$email</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> number ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$number</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> institution ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$institutio</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> gender ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$gender</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> Signupdate ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$signupdate</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> lastlogindate ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$lastlogindate</span><br>
   </li>";
	}//while loop
	if($num_rows == 10){
	$data .="<button id='moreuserbtn'onclick='getmuser()' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
      <span class='' id='moreusershow'><i class='fa fa-bell'></i> Click to load more users</span>
      <span class='w3-hide' id='moreuserprg'><i class='fa fa-spinner w3-spin'></i> getting more users...</span>
      </button>";
	}else{
	unset($_SESSION["adminusernum"]);
	}
	return $data;
	}else{
	return "failed sorry no users yet".$conn->error;
	}
	}
	//public function to get user ends here

	//public function to get more user starts here
	public function getmoreuser(){
	if(!isset($_SESSION["adminusernum"]) || strlen($_SESSION["adminusernum"]) < 1 || empty($_SESSION["adminusernum"])){
	return "nomoreuser a";
	}
	$conn = $this->conn;
	$lastid = $_SESSION["adminusernum"];
	$sql = "select id,name,username,email,phonenumber,institution,gender,signupdate,avatar,lastlogindate from oaumeetupusers where id < '$lastid' order by id desc limit 10";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	$n = 0;
	$data = "";
	$num_rows = $result->num_rows;
	while($row = $result->fetch_assoc()){
	++$n;
	if($n == $num_rows){
	$_SESSION["adminusernum"] = $row["id"];
	}
	$id = $row["id"];
    $name = $row["name"];
    $uname = $row["username"];
    $email = $row["email"];
    $number = $row["phonenumber"];
    $institutio = $row["institution"];
    $gender = $row["gender"];
    $signupdate = strftime("%a %b %d %Y @ %I:%M%p",$row["signupdate"]);
    $avatar = $row["avatar"];
    if(empty($avatar) && $gender == "male"){
    $avatar= "maledefault.png";
    }else if(empty($avatar) && $gender == "female"){
    $avatar = "femaledefault.jpeg";
    }
    $lastlogindate = strftime("%a %b %d %Y @ %I:%M%p",$row["lastlogindate"]);
    $data .="<li class='w3-bar w3-center w3-card-4 w3-ripple w3-panel w3-round-large w3-animate-zoom w3-display-container' style='margin-left:auto;margin-right:auto;'>
    <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-circle lazyload w3-margin w3-card-4'style='width:85px;height:85px;padding:2px;border: 2px solid #2196F3;'/>
   <div class ='w3-bar-item' style='font-family:cursive;width:100%; padding: 0;word-spacing: 8px;'>
    <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> Username ~</span>
   <span class='w3-text-blue'style='font-family: cursive;'>$uname</span><br>
   <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> name ~</span>
   <span class='w3-text-blue'style='font-family: cursive;'>$name</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> email ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$email</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> number ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$number</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> institution ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$institutio</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> gender ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$gender</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> Signupdate ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$signupdate</span><br>
  <span class='w3-text-blue'style='font-family: cursive;'><i class='fa fa-user w3-text-blue'></i> lastlogindate ~</span>
  <span class='w3-text-blue'style='font-family: cursive;'>$lastlogindate</span><br>
   </li>";
	}//while loop
	if($num_rows != 10){
	unset($_SESSION["adminusernum"]);
	}
	return $data;
	}else{
	return "nomoreuser c";
	}
	}
   //public function to get more user ends here

	//public function to get num chat startrs here
	public function getnumchat(){
	$conn = $this->conn;
	$sql = "select count(id) as total from fchat limit 25";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$total = $row["total"];
	return "<li class='w3-card-4 w3-ripple  w3-panel w3-padding w3-center w3-text-blue w3-round-xlarge w3-display-container'style='width:50%;margin-left:auto;margin-right:auto;'>
    <img src ='chaticon.png' class='w3-circle w3-left'style='width:40px;height:40px;'/>
    <span class='w3-display-middle w3-small'style='width:70%;padding:0;margin-left:26px;'>$total chat</span>
    </li>";
	}
	//public function to get nuwm chat ends here

	//public function to get num chat startrs here
	public function getnumachat(){
	$conn = $this->conn;
	$sql = "select count(id) as total from achat limit 25";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$total = $row["total"];
	return "<li class='w3-card-4 w3-ripple  w3-panel w3-padding w3-center w3-text-blue w3-round-xlarge w3-display-container'style='width:50%;margin-left:auto;margin-right:auto;'>
    <img src ='chathide.jpg' class='w3-circle w3-left'style='width:40px;height:40px;'/>
    <span class='w3-display-middle w3-small'style='width:70%;padding:0;margin-left:26px;'>$total achat</span>
    </li>";
	}
	//public function to get nuwm chat ends here

	//public function to get website stats starts here
	public function getwebstats(){
	$conn = $this->conn;
	$sql = "select * from websitestats  order by id desc limit 10";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	$data = "";
	$n = 0;
	$num_rows = $result->num_rows;
	while($row = $result->fetch_assoc()){
    $data .="<li class='w3-bar w3-center w3-card-4 w3-ripple w3-panel w3-round-large w3-animate-zoom w3-display-container' style='margin-left:auto;margin-right:auto;'>";
	++$n;
	if($n == $num_rows){
	$_SESSION["webstatsnum"] = $row["id"];
	}
	$id = $row["id"];
	$webstats = $row["webstat"];
	$date = $row["date"];
	if(empty($webstats)){
	$webstats =array();
	}else{
	$webstats = json_decode($webstats,true);
	}

	if(is_array($webstats) && count($webstats) > 0){
	foreach ($webstats as $key => $value) {
     $data .="<div class ='w3-bar-item' style='font-family:cursive;width:100%; padding: 0;word-spacing: 8px;'>
    <span class='w3-text-blue'style=''> $key ~</span>
   <span class='w3-text-blue'style=''>$value visits</span><br>";
	}//for each loop
	}
	$data .="</li>";
	}//while loop
    if($num_rows == 10){
	$data.="<button id='morestatbtn'onclick='getmorestat()' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
      <span class='' id='morestatshow'><i class='fa fa-bell'></i> Click to load more stats</span>
      <span class='w3-hide' id='morestatprg'><i class='fa fa-spinner w3-spin'></i> getting more stats...</span>
      </button>";
	}else{
	unset($_SESSION["webstatsnum"]);
	}
	
     return $data;
	}else{
	return "failed no webstats yet".$conn->error;
	}
	}
	//public function to get websute starts ends hete

	//public function to get website stats starts here
	public function getmorewebstats(){
	if(!isset($_SESSION["webstatsnum"]) || strlen($_SESSION["webstatsnum"]) < 1 || empty($_SESSION["webstatsnum"])){
	return "nomorestat a";
	}
	$conn = $this->conn;
	$lastid = $_SESSION["webstatsnum"];
	$sql = "select * from websitestats  where id < '$lastid' order by id  desc limit 10";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	$data = "";
	$n = 0;
	$num_rows = $result->num_rows;
	while($row = $result->fetch_assoc()){
    $data .="<li class='w3-bar w3-center w3-card-4 w3-ripple w3-panel w3-round-large w3-animate-zoom w3-display-container' style='margin-left:auto;margin-right:auto;'>";
	++$n;
	if($n == $num_rows){
	$_SESSION["webstatsnum"] = $row["id"];
	}
	$id = $row["id"];
	$webstats = $row["webstat"];
	$date = $row["date"];
	if(empty($webstats)){
	$webstats =array();
	}else{
	$webstats = json_decode($webstats,true);
	}

	if(is_array($webstats) && count($webstats) > 0){
	foreach ($webstats as $key => $value) {
     $data .="<div class ='w3-bar-item' style='font-family:cursive;width:100%; padding: 0;word-spacing: 8px;'>
    <span class='w3-text-blue'style=''> $key ~</span>
   <span class='w3-text-blue'style=''>$value visits</span><br>";
	}//for each loop
	}
	$data .="</li>";
	}//while loop
    if($num_rows != 10){
	unset($_SESSION["webstatsnum"]);
	}
    return $data;
	}else{
	return "nomorestat c";
	}
	}
	//public function to get websute starts ends hete

}
?>