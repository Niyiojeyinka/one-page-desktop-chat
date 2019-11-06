<?php
session_start();
require "classmeetupvalidate.php";
/**
 * $_SESSION['findmatchnum']
 */
class findmatch extends meetupvalidate
{
	private $userid;
	function __construct(){
	$this->createConnection();
	if(isset($_SESSION["userid"])){
	$this->userid = $_SESSION["userid"];
	}
    }

    //public function to get love matches starts here
    public function getlovematch(){
    $conn = $this->conn;
    $uid = $this->userid;
    $gender = $this->gender;
    $mpref = $this->mpref;
    $num_rows = 0;
    $mpref = json_decode($mpref,true);
    $data = "";
    $a = array();
    $sql = "select userid,username,institution,gender,avatar,attributes from oaumeetupusers where ";
    $n = 0;
    foreach($mpref as $key => $value){
    ++$n;
    $value = $value;
    if($n == 1){
    $sql .=" gender != '$gender' and attributes like '%$value%'";
    }elseif($n > 1){
    $sql .=" or gender != '$gender' and attributes like '%$value%'";
    } 
    }//for each loop
    $sql .= " union select userid,username,institution,gender,avatar,attributes from oaumeetupusers where gender !='$gender' order by rand() limit 10";
    //queries starts here
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $num_rows = $result->num_rows;
    $data .="<div class='w3-card-4 w3-ripple  w3-panel w3-padding w3-center w3-text-blue w3-round-xlarge w3-display-container'style='width:50%;margin-left:auto;margin-right:auto;'>
   <img src ='love.png'class='w3-circle w3-spin w3-left'style='width:40px;height:40px;'/>
   <span class='w3-display-middle w3-small'style='width:70%;padding:0;margin-left:26px;'>$num_rows Matches Found</span>
   </div>";	
    while($row = $result->fetch_assoc()){
    $userid = $row["userid"];
    $dbuid = "'$userid'";
    array_push($a,$dbuid);
    $username = $row["username"];
    $institution = $row["institution"];
    $dbgender = $row["gender"];
    $avatar = $row["avatar"];
    $attr = $row["attributes"];
    if(empty($avatar) && $dbgender == "male"){
    $avatar= "maledefault.png";
    }else if(empty($avatar) && $dbgender == "female"){
    $avatar = "femaledefault.jpeg";
    }
    if(!empty($attr)){
    $attr = json_decode($attr,true);
    }else{
    $attr = array();
    }
    $compat = rand(60,100)."%";
    $attributes = "";

    $data .="<li class='w3-bar love_match_list w3-center w3-card-4 w3-ripple w3-panel w3-round-large w3-animate-zoom w3-display-container' style='margin-left:auto;margin-right:auto;'>
    <a href='oaumeetupprofile.php?uid=$userid' style='text-decoration:none;'><img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-circle lazyload w3-margin w3-card-4'style='width:85px;height:85px;padding:2px;border: 2px solid inset #2196F3;'></a>
    <div class ='w3-bar-item' style='width:100%; padding: 0;word-spacing: 8px;'>
    <span class='w3-text-blue'style=''><i class='fa fa-user w3-text-blue'></i> Username ~</span>
    <span class='w3-text-blue'style=''>$username</span><br>
    <span class='w3-text-blue'style=''><i class='fa fa-heart w3-spin w3-text-red'></i> Compatibility ~</span>
    <span class='w3-text-blue'style=''>$compat</span><br>
    <span class='w3-text-blue'style=''><i class='fa fa-$dbgender'></i> Gender ~</span>
    <span class='w3-text-blue'style=''>$dbgender</span><br>
    <span class='w3-text-blue'style=''><i class='fa fa-institution'></i> Institution ~</span>
    <span class='w3-text-blue'style=''>$institution</span><br>
    ";
   //for each loop starts here
   if(is_array($attr) && count($attr) > 0){

   foreach ($attr as $key => $value){
   if($key == "skincolor"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-heart w3-text-$value'></i> Complexion ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }elseif($key == "size"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-paw w3-text-$value'></i> Size ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }elseif($key == "height"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-sort-amount-asc'></i> Height ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }elseif($key == "danceskills"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-yelp'></i> Dancing skills ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }elseif($key == "sings"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-microphone'></i> Sings ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }
   }

   }
   //for each loop ends here
   $data .= $attributes;
   $data .= "</li>";
   }//while loop
   $data .="<button id='showmorematchbtn' class='w3-button w3-round-large w3-card w3-text-blue w3-hover-blue w3-hover-text-white w3-block'style='margin-left:auto;margin-right:auto;width:80%;'>
   <span class='' id='showmorematchshow'><i class='fa fa-heart w3-text-red'></i> click to find more matches</span>
   <span class='w3-hide' id='showmorematchprg'><i class='fa fa-spinner w3-spin'></i> getting new matches...</span>
   </button>";
   if(is_array($a) && count($a)  > 0){
   $_SESSION['findmatchnum'] = $a;
   }
   return $data;
   }else{
    return "<a href='oaumeetupprofile.php' style='text-decoration:none;'><div style='margin-top:30vh;' class='w3-center w3-text-blue w3-ripple'><img src='lovematch.png' style='width:60px;height:60px;'> Visit your profile while we find you a match</div></a>";
   }
   }
   //public function to get love matches ends here

   //public function to load more stories starts here
   public function loadmorematches(){
   if(!isset($_SESSION['findmatchnum']) || !is_array($_SESSION['findmatchnum']) || count($_SESSION['findmatchnum']) < 1){
   return "nomatches a";
   }
   $conn = $this->conn;
   $uid = $this->userid;
   $gender = $this->gender;
   $mpref = $this->mpref;
   $param = $_SESSION['findmatchnum'];
   $param = implode(",", $param);
   $num_rows = 0;
   $mpref = json_decode($mpref,true);
   $data = "";
   $a = array();
   $sql = "select userid,username,institution,gender,avatar,attributes from oaumeetupusers where ";
   $n = 0;
   foreach($mpref as $key => $value){
   ++$n;
   $value = $value;
   if($n == 1){
   $sql .="userid not in ($param) and gender != '$gender' and attributes like '%$value%'";
   }elseif($n > 1){
   $sql .=" or userid not in ($param) and gender != '$gender' and attributes like '%$value%'";
   } 
   }//for each loop
   $sql .= " union select userid,username,institution,gender,avatar,attributes from oaumeetupusers where userid not in ($param) and gender !='$gender' order by rand() limit 10";
   $result = $conn->query($sql);
   if($result->num_rows > 0){
   $num_rows = $result->num_rows;
   while($row = $result->fetch_assoc()){
    $userid = $row["userid"];
    $dbuid = "'$userid'";
    array_push($a,$dbuid);
    $username = $row["username"];
    $institution = $row["institution"];
    $dbgender = $row["gender"];
    $avatar = $row["avatar"];
    if(empty($avatar) && $dbgender == "male"){
    $avatar= "maledefault.png";
    }else if(empty($avatar) && $dbgender == "female"){
    $avatar = "femaledefault.jpeg";
    }
    $attr = $row["attributes"];
    if(!empty($attr)){
    $attr = json_decode($attr,true);
    }else{
    $attr = array();
    }
    $compat = rand(60,100)."%";
    $attributes = "";

    $data .="<li class='w3-bar love_match_list w3-center w3-card-4 w3-ripple w3-panel w3-round-large w3-animate-zoom w3-display-container' style='margin-left:auto;margin-right:auto;'>
    <a href='oaumeetupprofile.php?uid=$userid' style='text-decoration:none;'><img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-circle lazyload w3-margin w3-card-4'style='width:85px;height:85px;padding:2px;border: 2px solid inset #2196F3;'></a>
    <div class ='w3-bar-item' style='width:100%; padding: 0;word-spacing: 8px;'>
    <span class='w3-text-blue'style=''><i class='fa fa-user w3-text-blue'></i> Username ~</span>
    <span class='w3-text-blue'style=''>$username</span><br>
    <span class='w3-text-blue'style=''><i class='fa fa-heart w3-spin w3-text-red'></i> Compatibility ~</span>
    <span class='w3-text-blue'style=''>$compat</span><br>
    <span class='w3-text-blue'style=''><i class='fa fa-$dbgender'></i> Gender ~</span>
    <span class='w3-text-blue'style=''>$dbgender</span><br>
    <span class='w3-text-blue'style=''><i class='fa fa-institution'></i> Institution ~</span>
    <span class='w3-text-blue'style=''>$institution</span><br>
    ";
   //for each loop starts here
   if(is_array($attr) && count($attr) > 0){
   foreach ($attr as $key => $value){
   if($key == "skincolor"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-heart w3-text-$value'></i> Complexion ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }elseif($key == "size"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-paw w3-text-$value'></i> Size ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }elseif($key == "height"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-sort-amount-asc'></i> Height ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }elseif($key == "danceskills"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-yelp'></i> Dancing skills ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }elseif($key == "sings"){
   $attributes .="<span class='w3-text-blue'style=''><i class='fa fa-microphone'></i> Sings ~</span>
   <span class='w3-text-blue'style=''>$value</span><br>";
   }
   }
   }
   //for each loop ends here
   $data .= $attributes;
   $data .= "</li>";
   }//while loop
   if(is_array($a) && count($a)  > 0){
   $_SESSION['findmatchnum'] = array_merge($_SESSION['findmatchnum'],$a);
   }
   return $data;
   }else{
   return "nomatches b";
   }

   }
   //public function to load more stories ends here


}
?>