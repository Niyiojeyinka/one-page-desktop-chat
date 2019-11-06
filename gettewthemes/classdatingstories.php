<?php
session_start();
require "classmeetupvalidate.php";
/**
 * class for handling display of stories start here
 */
//session to unset starts here $_SESSION["datingstoriesfollowers"] $_SESSION["datingstoriesfollowed"] $_SESSION["lasttrendnum"] $_SESSION["lastnewnum"] $_SESSION["userstorynum"] $_SESSION["leftreadget"] 
class meetupdatingstories extends meetupvalidate
{ 
	private $userid;
  private $readedstories = array();
	function __construct()
	{
	$this->createConnection();
  if(isset($_SESSION["userid"])){
  $this->setUserData();
	}
 	}
 	public function setUserData(){
 	$this->userid = $_SESSION["userid"];
 	}
  
  //public function to
  public function clearexpired(){
  $readstorylist = $this->getReadStoryList();
  if(empty($readstorylist) || !is_array($readstorylist) || count($readstorylist) < 1 ){
  return;
  }
  foreach ($readstorylist as $key => $value){
  $r = time() - $value;
  if($r >= 86400){
  unset($readstorylist[$key]);
  }
  }//for each loop
  $this->readedstories = $readstorylist;
  }
 	//function to get users own story from database if exists
 	public function getUserStories(){
 	$conn = $this->conn;
 	$userid = $this->userid;
 	$sql = "select * from stories where writerid ='$userid' and expired = '0' order by date desc limit 5";
 	$result = $conn->query($sql);
 	$data =$writerid=$writername=$storyid=$storytitle=$storycontent=$numviews=$viewerslist=$numcomment=$commenterlist=$anonymous=$expired=$date=$stry=$colorstory=$color_first=$shrtstry= "";
  $lastaffected = array();
 	if ($result->num_rows > 0) {
 	while ($row = $result->fetch_assoc()) {
    $id = $row["id"];
    $id = "'$id'";
  array_push($lastaffected,$id);
    $writerid = $row["writerid"];
    $writername=$gender="";
    $writerdetails = $this->getPartnerDetails($writerid);
    if(is_array($writerdetails)){
    $writername= $writerdetails[0];
    $gender = $writerdetails[1];
    }
    $storyid = $row["storyid"];
    $storytitle = $row["storytitle"];
    $storycontent = $row["storycontent"];
    $numviews = $row["numviews"];
    $viewerslist = $row["viewerslist"];
    $numcomment = $row["numcomment"];
    $commenterlist = $row["commenterslist"];
    $anonymous = $row["anonymous"];
    $expired = $row["expired"];
    $date = $row["date"];   $storycontent = str_replace("_^_","\ud",$storycontent);
    $stry = json_decode($storycontent,true);
    $stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
    $count = count($stry);
    $colorstory = array_keys($stry);
    $color_first = $colorstory[0];
    $shrtstry = substr($stry[$color_first], 0,18);
    $shrtstry = str_replace("#389","\\",$shrtstry);
    $writername ="<i class='fa fa-user'> </i> $writername"; 
    if ($anonymous == "1") {
    $writername =  "<i class='fa fa-eye-slash'> </i> Anonymous";
    }
    $data .="
    <a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container 'style='padding-left:4px; padding-bottom:4px;'>
   <!--<span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>-->
   <span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
   <div class='w3-bar-item w3-circle'style='border: 3px dashed #2196F3;padding: 5px;margin-top:15px;'>
   <div class='w3-circle w3-bar-item  $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow: ellipsis;letter-spacing:2px;'>$shrtstry</div>	
       </div>
   <div class='w3-bar-item ' style='width:70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
   <span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
   <span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
   <span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp 
   <span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
   </li></a>

   ";
 	}
  if(count($lastaffected) > 0){
  $_SESSION["userstorynum"] = $lastaffected;
  $data .="
  </div>
  <button id='loadmustories'class='w3-padding w3-ripple w3-btn  w3-text-blue w3-center' style='width:100%;margin-left:auto;margin-right:auto;outline:none;'>
  <b id='loadmustoriesshow' class=''>Click to load more of your stories <img class='w3-circle' src='storylove.jpg'style='width:30px;height:30px;'></b>
  <b id='loadmoreustoriesprg' class='w3-hide'><i class='fa fa-spinner w3-spin w3-xlarge'></i> Loading more of your stories..</b>
  </button>";
  }
  }
  return $data; 
 	}//closing braces for function


  //public function to get trending stories starts here
 	public function getTrendStories()
 	{
 	$conn = $this->conn;
 	$userid = $this->userid;
 	$sql = "select * from stories where writerid !='$userid' and expired = '0' order by numviews desc limit 5";
 	$result = $conn->query($sql);
 	$data = "";
  $lastaffected = array();
 	if ($result->num_rows > 0) {
 	while ($row = $result->fetch_assoc()) {
  $id = $row["id"];
  $id = "'$id'";
  array_push($lastaffected,$id);
  $writerid = $row["writerid"];
  $writerdetails = $this->getPartnerDetails($writerid);
  if(is_array($writerdetails)){
  $writername= $writerdetails[0];
  $gender = $writerdetails[1];
  }  
  $storyid = $row["storyid"];
  $tokeepstoryid = "'$storyid'";
  $storytitle = $row["storytitle"];
  $storycontent = $row["storycontent"];
  $mood = $row["mood"];
  $numviews = $row["numviews"];
  $viewerslist = $row["viewerslist"];
  $numcomment = $row["numcomment"];
  $commenterlist = $row["commenterslist"];
  $anonymous = $row["anonymous"];
  $expired = $row["expired"];
  $date = $row["date"];
  $storycontent = str_replace("_^_","\ud",$storycontent);
  $stry = json_decode($storycontent,true);
  $stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
  $count = count($stry);
  $colorstory = array_keys($stry);
  $color_first = $colorstory[0];
  $shrtstry = substr($stry[$color_first], 0,18);
  $shrtstry = str_replace("#389","\\",$shrtstry);
  $writername ="<span class='w3-small'><i class='fa fa-user'> </i> $writername</span>"; 
  if($anonymous == "1") {
  $writername =  "<span class='w3-small'><i class='fa fa-eye-slash'> </i> <i class='fa fa-$gender'></i> $gender Anonymous</span>";
  }
  if($mood == ""){
  $mood = "Happy";
  }
    $data .="
    <a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container'style='padding-left:4px;'>
   <span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>
   <span class='w3-text-grey w3-display-topmiddle  w3-small'style='margin-left:30vw;'>Mood : $mood</span>
   <span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
   <div class='w3-bar-item w3-circle'style='border: 3px dashed #2196F3;padding: 5px;margin-top:15px;'>
   <div class='w3-circle w3-bar-item  $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow: ellipsis;letter-spacing:2px;'>$shrtstry</div> 
       </div>
   <div class='w3-bar-item' style='width: 70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
   <span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
   <span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
   <span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp
   <span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
   </li></a>";
  }//while  loop
  //greater than 0
  if(count($lastaffected) > 0){
  $_SESSION["lasttrendnum"] = $lastaffected;
  $data.= $this->getNewStories($_SESSION["lasttrendnum"]);
  $data .="
  </div>
  <button id='loadmostories'class='w3-padding w3-ripple w3-button w3-hover-light-grey w3-hover-text-blue w3-text-blue w3-center' style='width:100%;margin-left:auto;margin-right:auto;'>
  <b id='loadmostoriesshow' class=''>Click to load more of dating stories <img class='w3-circle' src='storylove.jpg'style='width:30px;height:30px;'></b>
  <b id='loadmoreostoriesprg' class='w3-hide'><i class='fa fa-spinner w3-spin w3-xlarge'></i> Loading more dating stories...</b>
  </button>";
  }
  //greaster than
  }//num_rows
  
  return $data;
 	}
  //public function to get trend stories ends here


  //public function to get newly posted stories starts here
  public function getNewStories($data)
  {
  if(!is_array($data) || count($data) < 1){
  return "";
  }
  $conn = $this->conn;
  $userid = $this->userid;
  $str = implode($data,",");
  $sql = "select * from stories where id not in($str) and writerid !='$userid'  and expired = '0' order by numviews  limit 5";
  $result = $conn->query($sql);
  $data = "";
  $lastaffected = array();
  if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
  $id = $row["id"];
  $id = "'$id'";  
  array_push($lastaffected,$id);
  $writerid = $row["writerid"];
  $writerdetails = $this->getPartnerDetails($writerid);
  if(is_array($writerdetails)){
  $writername = $writerdetails[0];
  $gender = $writerdetails[1];
  }  
  $storyid = $row["storyid"];
  $storytitle = $row["storytitle"];
  $storycontent = $row["storycontent"];
  $mood = $row["mood"];
  $numviews = $row["numviews"];
  $viewerslist = $row["viewerslist"];
  $numcomment = $row["numcomment"];
  $commenterlist = $row["commenterslist"];
  $anonymous = $row["anonymous"];
  $expired = $row["expired"];
  $date = $row["date"];
  $storycontent = str_replace("_^_","\ud",$storycontent);
  $stry = json_decode($storycontent,true);
  $stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
  $count = count($stry);
  $colorstory = array_keys($stry);
  $color_first = $colorstory[0];
  $shrtstry = substr($stry[$color_first], 0,18);
  $shrtstry = str_replace("#389","\\",$shrtstry);
  $writername ="<span class='w3-small'><i class='fa fa-user'> </i> $writername</span>"; 
  if ($anonymous == "1") {
  $writername =  "<span class='w3-small'><i class='fa fa-eye-slash'> </i> <i class='fa fa-$gender'></i> $gender Anonymous</span>";
  }
  if($mood == ""){
  $mood = "Happy";
  }
  $data .="
  <a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
  <li class='w3-bar w3-ripple w3-display-container'style='padding-left:4px;'>
  <span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>
  <span class='w3-text-grey w3-display-topmiddle  w3-small'style='margin-left:30vw;'>Mood : $mood </span>
  <span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
  <div class='w3-bar-item w3-circle'style='border: 3px dashed #2196F3;padding: 5px;margin-top:15px;'>
  <div class='w3-circle w3-bar-item  $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow: ellipsis;letter-spacing:2px;'>$shrtstry</div> 
       </div>
  <div class='w3-bar-item' style='width: 70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
  <span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
  <span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
  <span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp
  <span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
  </li></a>";
  }
  if(count($lastaffected) > 0){
  $_SESSION["lasttrendnum"] = array_merge($_SESSION["lasttrendnum"],$lastaffected);
  }
  }
  return $data;
  }
  //public function to get newly posted stories ends here

  //public function to load more user stories starts here
  public function getMoreUserStories(){
  $conn = $this->conn;
  $userid = $this->userid;
  if(!isset($_SESSION["userstorynum"]) || empty($_SESSION["userstorynum"]) || !is_array($_SESSION["userstorynum"])){
  return "";
  }
  $arg = $_SESSION["userstorynum"];
  $arg = implode(",", $arg);
  $sql = "select * from stories  where id not in($arg) and writerid ='$userid' and expired = '0' order by date desc limit 5";
  $result = $conn->query($sql);
  $data =$writerid=$writername=$storyid=$storytitle=$storycontent=$numviews=$viewerslist=$numcomment=$commenterlist=$anonymous=$expired=$date=$stry=$colorstory=$color_first=$shrtstry= "";
  if ($result->num_rows > 0) {
  $lastaffected = array();
  while ($row = $result->fetch_assoc()) {
    $id = $row["id"];
    $id = "'$id'";
    array_push($lastaffected,$id);
    $writerid = $row["writerid"];
    $writername=$gender="";
    $writerdetails = $this->getPartnerDetails($writerid);
    if(is_array($writerdetails)){
    $writername= $writerdetails[0];
    $gender = $writerdetails[1];
    }
    $storyid = $row["storyid"];
    $storytitle = $row["storytitle"];
    $storycontent = $row["storycontent"];
    $numviews = $row["numviews"];
    $viewerslist = $row["viewerslist"];
    $numcomment = $row["numcomment"];
    $commenterlist = $row["commenterslist"];
    $anonymous = $row["anonymous"];
    $expired = $row["expired"];
    $date = $row["date"];   $storycontent = str_replace("_^_","\ud",$storycontent);
    $stry = json_decode($storycontent,true);
    $stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
    $count = count($stry);
    $colorstory = array_keys($stry);
    $color_first = $colorstory[0];
    $shrtstry = substr($stry[$color_first], 0,18);
    $shrtstry = str_replace("#389","\\",$shrtstry);
    $writername ="<span class='w3-small'><i class='fa fa-user'> </i> $writername</span>"; 
    if ($anonymous == "1") {
    $writername =  "<span class='w3-small'><i class='fa fa-eye-slash'> </i> Anonymous</span>";
    }
     $data .="
    <a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container 'style='padding-left:4px; padding-bottom:4px;'>
   <!--<span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>-->
   <span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
   <div class='w3-bar-item w3-circle'style='border:3px dashed #2196F3;padding: 5px;margin-top:15px;'>
   <div class='w3-circle w3-bar-item   $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow:ellipsis;letter-spacing:2px;'>
   $shrtstry
   </div> 
  </div>
   <div class='w3-bar-item ' style='width:70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
   <span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
   <span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
   <span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp 
   <span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
   </li></a>";
  }
  if(count($lastaffected) > 0){
  $_SESSION["userstorynum"] = array_merge($lastaffected,$_SESSION["userstorynum"]);
  }
  }
  return $data; 
  }//closing braces for function
  //public function to load more user stories ends here

  //public function to load more trending stories starts here
  public function getMoreTrendStories()
  {
  if(!isset($_SESSION["lasttrendnum"]) || empty($_SESSION["lasttrendnum"]) || !is_array($_SESSION["lasttrendnum"])){
  return "";
  } 
  $arg = $_SESSION["lasttrendnum"];
  $arg = implode(",", $arg);
  $conn = $this->conn;
  $userid = $this->userid;
  $sql = "select * from stories where id not in($arg) and writerid !='$userid' and expired = '0' order by numviews desc limit 5";
  $result = $conn->query($sql);
  $data = "";
  $lastaffected = array();
  if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
  $id = $row["id"];
  $id = "'$id'";
  array_push($lastaffected,$id);
  $writerid = $row["writerid"];
  $writerdetails = $this->getPartnerDetails($writerid);
  if(is_array($writerdetails)){
  $writername= $writerdetails[0];
  $gender = $writerdetails[1];
  }  
  $storyid = $row["storyid"];
  $tokeepstoryid = "'$storyid'";
  $storytitle = $row["storytitle"];
  $storycontent = $row["storycontent"];
  $mood = $row["mood"];
  $numviews = $row["numviews"];
  $viewerslist = $row["viewerslist"];
  $numcomment = $row["numcomment"];
  $commenterlist = $row["commenterslist"];
  $anonymous = $row["anonymous"];
  $expired = $row["expired"];
  $date = $row["date"];
  $storycontent = str_replace("_^_","\ud",$storycontent);
  $stry = json_decode($storycontent,true);
  $stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
  $count = count($stry);
  $colorstory = array_keys($stry);
  $color_first = $colorstory[0];
  $shrtstry = substr($stry[$color_first], 0,18);
  $shrtstry = str_replace("#389","\\",$shrtstry);
  $writername ="<span class='w3-small'><i class='fa fa-user'> </i> $writername</span>"; 
  if($anonymous == "1") {
  $writername =  "<span class='w3-small'><i class='fa fa-eye-slash'> </i> <i class='fa fa-$gender'></i> $gender Anonymous</span>";
  }
  if($mood == ""){
  $mood = "Happy";
  }
    $data .="
    <a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container'style='padding-left:4px;'>
   <span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>
   <span class='w3-text-grey w3-display-topmiddle  w3-small'style='margin-left:30vw;'>Mood : $mood</span>
   <span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
   <div class='w3-bar-item w3-circle'style='border: 3px dashed #2196F3;padding: 5px;margin-top:15px;'>
   <div class='w3-circle w3-bar-item  $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow: ellipsis;letter-spacing:2px;'>$shrtstry</div> 
       </div>
   <div class='w3-bar-item' style='width: 70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
   <span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
   <span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
   <span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp
   <span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
   </li></a>";
  }//while  loop
  
  //greater than
  if(count($lastaffected) > 0){
  $_SESSION["lasttrendnum"] = array_merge($_SESSION["lasttrendnum"],$lastaffected);
  $data .= $this->getNewStories($_SESSION["lasttrendnum"]);
  }
  //greaster than
  }//num_rows
  return $data;
  }
  //public function to load more trending stories ends here
  //public to get more new stories starts  here
  public function getMoreNewStories($data)
  {
  if(!is_array($data) || count($data) < 1){
  return "";
  }
  if(!isset($_SESSION["lasttrendnum"]) || empty($_SESSION["lasttrendnum"]) || !is_array($_SESSION["lasttrendnum"])){
  return "";
  } 
  $conn = $this->conn;
  $userid = $this->userid;
  $str = implode($data,",");
  $sql = "select * from stories where id not in($str) and writerid !='$userid' and expired = '0' order by numviews  limit 5";
  $result = $conn->query($sql);
  $data = "";
  $lastaffected = array();
  if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
  $id = $row["id"];
  $id = "'$id'";
  array_push($lastaffected,$id);
  $writerid = $row["writerid"];
  $writerdetails = $this->getPartnerDetails($writerid);
  if(is_array($writerdetails)){
  $writername= $writerdetails[0];
  $gender = $writerdetails[1];
  }  
  $storyid = $row["storyid"];
  $storytitle = $row["storytitle"];
  $storycontent = $row["storycontent"];
  $mood = $row["mood"];
  $numviews = $row["numviews"];
  $viewerslist = $row["viewerslist"];
  $numcomment = $row["numcomment"];
  $commenterlist = $row["commenterslist"];
  $anonymous = $row["anonymous"];
  $expired = $row["expired"];
  $date = $row["date"];
  $storycontent = str_replace("_^_","\ud",$storycontent);
  $stry = json_decode($storycontent,true);
  $stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
  $count = count($stry);
  $colorstory = array_keys($stry);
  $color_first = $colorstory[0];
  $shrtstry = substr($stry[$color_first], 0,18);
  $shrtstry = str_replace("#389","\\",$shrtstry);
  $writername ="<span class='w3-small'><i class='fa fa-user'> </i> $writername </span>"; 
  if ($anonymous == "1") {
  $writername =  "<span class='w3-small'><i class='fa fa-eye-slash'> </i> <i class='fa fa-$gender'></i> $gender Anonymous </span>";
  }
  if($mood == ""){
  $mood = "Happy";
  }
  $data .="
  <a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
  <li class='w3-bar w3-ripple w3-display-container'style='padding-left:4px;'>
  <span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>
  <span class='w3-text-grey w3-display-topmiddle  w3-small'style='margin-left:30vw;'>Mood : $mood </span>
  <span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
  <div class='w3-bar-item w3-circle'style='border: 3px dashed #2196F3;padding: 5px;margin-top:15px;'>
  <div class='w3-circle w3-bar-item  $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow: ellipsis;letter-spacing:2px;'>$shrtstry</div> 
       </div>
  <div class='w3-bar-item' style='width: 70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
  <span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
  <span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
  <span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp
  <span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
  </li></a>";
  }
  if(count($lastaffected) > 0){
  $_SESSION["lasttrendnum"] = array_merge($_SESSION["lasttrendnum"],$lastaffected);
  $data .= $this->getNewStories($_SESSION["lasttrendnum"]);
  }
  }
  return $data;
  }
  //public function to get more new stories ends here
  
  //public function to get read stories starts here
  public function getReadStory(){
  $readstorylist = $this->readedstories;
  $uid = $this->userid;
  $conn = $this->conn;
  $readstorylist = array_keys($readstorylist);
  if(empty($readstorylist) || !is_array($readstorylist) || count($readstorylist) < 1){
  return "no read";
  }
  $str = implode(",",$readstorylist);
  $sql = "select * from stories where storyid in($str) and writerid !='$uid' and expired = '0' limit 10";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
  $leftreadget = array();
  $data = "";
  while($row = $result->fetch_assoc()){
  $writerid = $row["writerid"];
  $writerdetails = $this->getPartnerDetails($writerid);
  if(is_array($writerdetails)){
  $writername= $writerdetails[0];
  $gender = $writerdetails[1];
  }  
  $storyid = $row["storyid"];
  $tokeepstoryid = "'$storyid'";
  array_push($leftreadget,$tokeepstoryid);
  $storytitle = $row["storytitle"];
  $storycontent = $row["storycontent"];
  $mood = $row["mood"];
  $numviews = $row["numviews"];
  $viewerslist = $row["viewerslist"];
  $numcomment = $row["numcomment"];
  $commenterlist = $row["commenterslist"];
  $anonymous = $row["anonymous"];
  $expired = $row["expired"];
  $date = $row["date"];
  $storycontent = str_replace("_^_","\ud",$storycontent);
  $stry = json_decode($storycontent,true);
  $stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
  $count = count($stry);
  $colorstory = array_keys($stry);
  $color_first = $colorstory[0];
  $shrtstry = substr($stry[$color_first], 0,18);
  $shrtstry = str_replace("#389","\\",$shrtstry);
  $writername ="<span class='w3-small'><i class='fa fa-user'> </i> $writername</span>"; 
  if($anonymous == "1") {
  $writername =  "<span class='w3-small'><i class='fa fa-eye-slash'> </i> <i class='fa fa-$gender'></i> $gender Anonymous</span>";
  }
  if($mood == ""){
  $mood = "Happy";
  }
  $data .="
    <a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container'style='padding-left:4px;'>
   <span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>
   <span class='w3-text-grey w3-display-topmiddle  w3-small'style='margin-left:30vw;'>Mood : $mood</span>
   <span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
   <div class='w3-bar-item w3-circle'style='border: 3px dashed #2196F3;padding: 5px;margin-top:15px;'>
   <div class='w3-circle w3-bar-item  $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow: ellipsis;letter-spacing:2px;'>$shrtstry</div> 
       </div>
   <div class='w3-bar-item' style='width: 70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
   <span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
   <span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
   <span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp
   <span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
   </li></a>";
  }//while  loop
  $leftreadget = array_diff($readstorylist,$leftreadget);
  if(count($leftreadget) > 0){
  $_SESSION["leftreadget"] = $leftreadget;
  $data .="
  <button id='loadmorereadstories'onclick='moreread()' class='w3-padding w3-ripple w3-button w3-hover-text-white w3-hover-blue w3-text-blue w3-center' style = 'width:100%;margin-left:auto;margin-right:auto;'>
  <b id='loadmorereadstoriesshow' class=''>Click to load more of read stories <i class='fa fa-envelope-open'></i></b>
  <b id='loadmorereadstoriesprg' class='w3-hide'><i class='fa fa-spinner w3-spin w3-xlarge'></i> Loading more Read stories...</b>
  </button>";
  }
  return $data;
  }else{
  return "no read".$conn->error;
  }

  }
  //public function to get read stories ends here


  //public functon to get more already read stories starts here
  public function getMoreReadStories(){
  if(!isset($_SESSION["leftreadget"]) || !is_array($_SESSION["leftreadget"]) || count($_SESSION["leftreadget"]) < 1){
  return "no read";
  }
  $conn = $this->conn;
  $uid = $this->userid;
  $readstorylist = $_SESSION["leftreadget"];
  $str = implode(",",$readstorylist);
  $sql = "select * from stories where storyid in($str) and writerid !='$uid' and expired = '0' limit 10";
  $result = $conn->query($sql);
  if($result->num_rows > 0){
  $leftreadget = array();
  $data = "";
  while($row = $result->fetch_assoc()){
  $writerid = $row["writerid"];
  $writerdetails = $this->getPartnerDetails($writerid);
  if(is_array($writerdetails)){
  $writername= $writerdetails[0];
  $gender = $writerdetails[1];
  }  
  $storyid = $row["storyid"];
  $tokeepstoryid = "'$storyid'";
  array_push($leftreadget,$tokeepstoryid);
  $storytitle = $row["storytitle"];
  $storycontent = $row["storycontent"];
  $mood = $row["mood"];
  $numviews = $row["numviews"];
  $viewerslist = $row["viewerslist"];
  $numcomment = $row["numcomment"];
  $commenterlist = $row["commenterslist"];
  $anonymous = $row["anonymous"];
  $expired = $row["expired"];
  $date = $row["date"];
  $storycontent = str_replace("_^_","\ud",$storycontent);
  $stry = json_decode($storycontent,true);
  $stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
  $count = count($stry);
  $colorstory = array_keys($stry);
  $color_first = $colorstory[0];
  $shrtstry = substr($stry[$color_first], 0,18);
  $shrtstry = str_replace("#389","\\",$shrtstry);
  $writername ="<span class='w3-small'><i class='fa fa-user'> </i> $writername</span>"; 
  if($anonymous == "1") {
  $writername =  "<span class='w3-small'><i class='fa fa-eye-slash'> </i> <i class='fa fa-$gender'></i> $gender Anonymous</span>";
  }
  if($mood == ""){
  $mood = "Happy";
  }
  $data .="<a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
  <li class='w3-bar w3-ripple w3-display-container'style='padding-left:4px;'>
   <span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>
   <span class='w3-text-grey w3-display-topmiddle  w3-small'style='margin-left:30vw;'>Mood : $mood</span>
   <span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
   <div class='w3-bar-item w3-circle'style='border: 3px dashed #2196F3;padding: 5px;margin-top:15px;'>
   <div class='w3-circle w3-bar-item  $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow: ellipsis;letter-spacing:2px;'>$shrtstry</div> 
       </div>
   <div class='w3-bar-item' style='width: 70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
   <span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
   <span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
   <span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp
   <span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
   </li></a>";
  }//while  loop
  $leftreadget = array_diff($readstorylist,$leftreadget);
  if(count($leftreadget) > 0){
  $_SESSION["leftreadget"] = $leftreadget;
  }else{
  unset($_SESSION["leftreadget"]);
  }
  return $data;
  }else{
  return "no read".$conn->error;
  }
  }
  //public functon to get more already read stories ends here
  
  //public function  to get followed stories starts here
  public function getFollowedStories(){
  if(is_array($this->storiesfollowed) && count($this->storiesfollowed) > 0){
  $storiesfollowed = $this->storiesfollowed;
  $conn = $this->conn;
  $uid = $this->userid;
  $data = "";
  $arr = array();
  foreach ($storiesfollowed as $writerid => $a) {
  if(count($arr) >= 10){
  break;
  }
  $meid = $writerid;
  $writerid = $this->clean_input(str_replace("'","",$writerid));
  $a = $a;
  $storyf = stripslashes(strip_tags($a[0]));
  if(stripos($storyf, ")*(") !== false){
  $storyf = "from your profile";
  }
  $anonymous = $this->clean_input($a[1]);

  //check whether all is well  starts here
  if(!is_array($a) || count($a) != 2){
  continue;
  }
  if(empty($storyf) || strlen($anonymous) != 1){
  continue;  
  }
  //check whether all is well ends here

  //to determine what is displayed to user starts here
  if($anonymous == 0){
  $details = $this->getPartnerDetails($writerid);
  if(!is_array($details) || count($details) < 1){
  continue;
  }
  $name = $details[0];
  $avatar = $details[2];
  $data .= "
  <li  id='follower$writerid' class='w3-panel w3-card w3-round-large w3-ripple w3-padding-large w3-bar w3-display-container w3-animate-zoom' style='width:90%;margin-left:auto;margin-right:auto;'>
  <a href='oaumeetupprofile.php?uid=$writerid'style='text-decoration:none;'>
  <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-image lazyload w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
  </a>
  <div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
 <b class='w3-text-blue w3-small'>$name</b><br>
 <span class='w3-tiny w3-text-blue'>You started following on story:<b class='w3-small'> $storyf</b></span>  
 </div>
  <button id='btn$writerid' onclick='unfollow(\"$writerid\")' class='w3-display-topright w3-small w3-button w3-white w3-text-red w3-hover-red w3-hover-text-white'>
 <span id='showunfollow$writerid' class=''>unfollow</span>
 <span id='showunfollowprg$writerid'class='w3-hide'><i class='fa fa-spinner w3-spin'></i> unfollowing</span>
 </button>
 </li>
 </a>";
 $arr[$meid] = $a;
  }else if ($anonymous == 1){
  $details = $this->getPartnerDetails($writerid);
  if(!is_array($details) || count($details) < 1){
  continue;
  }
  $gender = $details[1]; 
  $data .="<li id='follower$writerid' class='w3-panel w3-card w3-round-large w3-ripple w3-bar w3-display-container w3-animate-zoom' style='width:90%;margin-left:auto;margin-right:auto;'>
    <a href='oaumeetupprofile.php?uid=$writerid'style='text-decoration:none;'>
  <img src='chathide.jpg' class='w3-image w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
  </a>
  <div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
  <b class='w3-text-blue w3-small'><i class='fa fa-$gender'></i> $gender anonymous</b><br>
  <span class='w3-tiny w3-text-blue'>You started following on story:<b class='w3-small'> $storyf</b></span>
  </div>
 <button id='btn$writerid' onclick='unfollow(\"$writerid\")' class='w3-display-topright w3-small w3-button w3-white w3-text-red w3-hover-red w3-hover-text-white'>
 <span id='showunfollow$writerid' class=''>unfollow</span>
 <span id='showunfollowprg$writerid'class='w3-hide'><i class='fa fa-spinner w3-spin'></i> unfollowing</span>
 </button>
  </li>";
  $arr[$meid] = $a;
  }
  //to determine what is displayed to user ends here
  }//for each loop
  $check = array_diff_key($this->storiesfollowed, $arr);

  if(count($check) > 0){
  $_SESSION["datingstoriesfollowed"] = $check;
  $data .="
  <button id='loadmorefollowing'onclick='morefollowing()' class='w3-padding w3-ripple w3-button w3-hover-text-white w3-hover-blue w3-text-blue w3-center' style = 'width:100%;margin-left:auto;margin-right:auto;'>
  <b id='loadmorefollowingshow' class=''>Click to load more of stories followed <i class='fa fa-book'></i></b>
  <b id='loadmorefollowingprg' class='w3-hide'><i class='fa fa-spinner w3-spin w3-xlarge'></i> Loading more stories followed...</b>
  </button>";
  }
 
  return $data;
  }else{
  return "no followed";
  }

  }
  //public function to get followed stories ends here

  //public function to get more followed stories starts here
  public function getmorefollowedstories(){
  if(!isset($_SESSION["datingstoriesfollowed"]) || !is_array($_SESSION["datingstoriesfollowed"]) || count($_SESSION["datingstoriesfollowed"]) < 1){
  return "no followed";
  }
  $storiesfollowed = $_SESSION["datingstoriesfollowed"];
  $conn = $this->conn;
  $uid = $this->userid;
  $data = "";
  $arr = array();
  foreach ($storiesfollowed as $writerid => $a) {
  if(count($arr) >= 10){
  break;
  }
  $meid = $writerid;
  $writerid = $this->clean_input(str_replace("'","",$writerid));
  $a = $a;
  $storyf = stripslashes(strip_tag($a[0]));
  if(stripos($storyf, ")*(") !== false){
  $storyf = "from your profile";
  }
  $anonymous = $this->clean_input($a[1]);

  //check whether all is well  starts here
  if(!is_array($a) || count($a) != 2){
  continue;
  }
  if(empty($storyf) || strlen($anonymous) != 1){
  continue;  
  }
  //check whether all is well ends here

  //to determine what is displayed to user starts here
  if($anonymous == 0){
  $details = $this->getPartnerDetails($writerid);
  if(!is_array($details) || count($details) < 1){
  continue;
  }
  $name = $details[0];
  $avatar = $details[2];
  $data .= "
  <li id='follower$writerid' class='w3-panel w3-display-container w3-card w3-round-large w3-ripple w3-padding-large w3-bar w3-animate-zoom' style='width:90%;margin-left:auto;margin-right:auto;'>
  <a href='oaumeetupprofile.php?uid=$writerid'style='text-decoration:none;'>
  <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-image lazyload w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
  </a>
  <div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
 <b class='w3-text-blue w3-small'>$name</b><br>
 <span class='w3-tiny w3-text-blue'>Started following on story:<b class='w3-small'> $storyf</b></span>  
 </div>
  <button id='btn$writerid' onclick='unfollow(\"$writerid\")' class='w3-display-topright w3-small w3-button w3-white w3-text-red w3-hover-red w3-hover-text-white'>
 <span id='showunfollow$writerid' class=''>unfollow</span>
 <span id='showunfollowprg$writerid'class='w3-hide'><i class='fa fa-spinner w3-spin'></i> unfollowing</span>
 </button>
 </li>
 </a>";
 $arr[$meid] = $a;
  }else if ($anonymous == 1){
  $details = $this->getPartnerDetails($writerid);
  if(!is_array($details) || count($details) < 1){
  continue;
  }
  $gender = $details[1]; 
  $data .="<li id='follower$writerid'class='w3-panel w3-card  w3-display-container w3-round-large w3-ripple  w3-bar w3-animate-zoom' style='width:90%;margin-left:auto;margin-right:auto;'>
  <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-image lazyload w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
  <div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
 <b class='w3-text-blue w3-small'><i class='fa fa-$gender'></i> $gender anonymous</b><br>
 <span class='w3-tiny w3-text-blue'>Started following on story:<b class='w3-small'> $storyf</b></span>  
 </div>
  <button id='btn$writerid' onclick='unfollow(\"$writerid\")' class='w3-display-topright w3-small w3-button w3-white w3-text-red w3-hover-red w3-hover-text-white'>
 <span id='showunfollow$writerid' class=''>unfollow</span>
 <span id='showunfollowprg$writerid'class='w3-hide'><i class='fa fa-spinner w3-spin'></i> unfollowing</span>
 </button>
 </li>";
  $arr[$meid] = $a;
  }
  //to determine what is displayed to user ends here
  }//for each loop
  $check = array_diff_key($_SESSION["datingstoriesfollowed"], $arr);
  if(count($check) > 0){
  $_SESSION["datingstoriesfollowed"] = $check;
  }else{
  unset($_SESSION["datingstoriesfollowed"]);
  }
  return $data;
  }
  //public function to get more followed stories ends here


  //public function to get people following starts here
  public function getfollowers(){
  if(is_array($this->followers) && count($this->followers) > 0){
  $followers = $this->followers;
  $conn = $this->conn;
  $uid = $this->userid;
  $data = "";
  $arr = array();
  foreach ($followers as $userid => $a) {
  if(count($arr) >= 10){
  break;
  }
  $meid = $userid;
  $userid = $this->clean_input(str_replace("'","",$userid));
  $a = $a;
  $storyf = stripslashes(strip_tags($a[0]));
  if(stripos($storyf, ")*(") !== false){
  $storyf = "from your profile";
  }
  $anonymous = $this->clean_input($a[1]);
  //check whether all is well  starts here
  if(!is_array($a) || count($a) != 2){
  continue;
  }
  if(empty($storyf) || strlen($anonymous) != 1){
  continue;  
  }
  //check whether all is well ends here

  //to determine what is displayed to user starts here
  $details = $this->getPartnerDetails($userid);
  if(!is_array($details) || count($details) < 1){
  continue;
  }
  $name = $details[0];
  $avatar = $details[2];
  $arr[$meid] = $a;
  $data .= "
  <li id='followers$userid' class='w3-panel w3-card w3-round-large w3-ripple w3-bar w3-animate-zoom w3-display-container' style='width:90%;margin-left:auto;margin-right:auto;'>
  <a href='oaumeetupprofile.php?uid=$userid'style='text-decoration:none;'>
  <img src='chatplaceholder1.jpg'data-src='$avatar' class='w3-image lazyload w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
  </a>
  <div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
 <b class='w3-text-blue w3-small'>$name</b><br>
 <span class='w3-tiny w3-text-blue'>$name started following you on your story:<b class='w3-small'> $storyf</b></span>  
 </div>
 <button id='btn$userid' onclick='remove(\"$userid\")'class='w3-display-topright w3-button w3-small w3-white w3-text-red w3-hover-red w3-hover-text-white'>
  <span id='showfollower$userid' class=''>Remove</span>
 <span id='showunfollowprg$userid'class='w3-hide'><i class='fa fa-spinner w3-spin'></i> removing</span>
 </button>
 </li>";
  //to determine what is displayed to user ends here
  }//for each loop
  $check = array_diff_key($this->followers,$arr);
  if(count($check) > 0){
  $_SESSION["datingstoriesfollowers"] = $check;
  $data .="
  <button id='loadmorefollowers' onclick='morefollowers()' class='w3-padding w3-ripple w3-button w3-hover-text-white w3-hover-blue w3-text-blue w3-center' style = 'width:100%;margin-left:auto;margin-right:auto;'>
  <b id='loadmorefollowersshow' class=''>Click to load more followers <i class='fa fa-users'></i></b>
  <b id='loadmorefollowersprg' class='w3-hide'><i class='fa fa-spinner w3-spin w3-xlarge'></i> Loading more followers...</b>
  </button>";
  }
  return $data;
  }else{
  return "no followers";
  }

  }
  //public function to get people following ends here

  //public function to get more people following you starts here
  public function getmorefollowers(){

  if(isset($_SESSION["datingstoriesfollowers"]) && is_array($_SESSION["datingstoriesfollowers"]) && count($_SESSION["datingstoriesfollowers"]) > 0){
  $followers = $_SESSION["datingstoriesfollowers"];
  $conn = $this->conn;
  $uid = $this->userid;
  $data = "";
  $arr = array();
  foreach ($followers as $userid => $a) {
  if(count($arr) >= 10){
  break;
  }
  $meid = $userid;
  $userid = $this->clean_input(str_replace("'","",$userid));
  $a = $a;
  $storyf = stripslashes(strip_tags($a[0]));
  if(stripos($storyf, ")*(") !== false){
  $storyf = "from your profile";
  }
  $anonymous = $this->clean_input($a[1]);
  //check whether all is well  starts here
  if(!is_array($a) || count($a) != 2){
  continue;
  }
  if(empty($storyf) || strlen($anonymous) != 1){
  continue;  
  }
  //check whether all is well ends here

  //to determine what is displayed to user starts here
  $details = $this->getPartnerDetails($userid);
  if(!is_array($details) || count($details) < 1){
  continue;
  }
  $name = $details[0];
  $avatar = $details[2];
  $arr[$meid] = $a;
  $data .= "
  <li id='followers$userid' class='w3-panel w3-card w3-display-container w3-round-large w3-ripple w3-padding-large w3-bar w3-animate-zoom' style='width:90%;margin-left:auto;margin-right:auto;'>
  <a href='oaumeetupprofile.php?uid=$userid'style='text-decoration:none;'>
  <img src='chatplaceholder1.jpg'data-src='$avatar' class='w3-image lazyload w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
  </a>
  <div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
 <b class='w3-text-blue w3-small'>$name</b><br>
 <span class='w3-tiny w3-text-blue'>$name started following you on your story:<b class='w3-small'> $storyf</b></span>  
 </div>
 <button id='btn$userid' onclick='remove(\"$userid\")'class='w3-display-topright w3-button w3-small w3-white w3-text-red w3-hover-red w3-hover-text-white'>
  <span id='showfollower$userid' class=''>Remove</span>
 <span id='showunfollowprg$userid'class='w3-hide'><i class='fa fa-spinner w3-spin'></i> removing</span>
 </button>
 </li>";
  //to determine what is displayed to user ends here
  }//for each loop
  $check = array_diff_key($_SESSION["datingstoriesfollowers"],$arr);
  if(count($check) > 0){
  $_SESSION["datingstoriesfollowers"] = $check;
  }else{
  unset($_SESSION["datingstoriesfollowers"]);
  }
  return $data;
  }else{
  return "no followers";
  }
  }
  //public function to get more people following ends here
  
  //public function to unfollow a story starts here
  public function unfollow($writerid){
  $storiesfollowed = $this->storiesfollowed;
  $writerid = $this->clean_input($writerid);
  $wrid = $writerid;
  $uid = $this->userid;
  $idu = $uid;
  $conn = $this->conn;
  if(!is_array($storiesfollowed) || count($storiesfollowed) < 1 || empty($writerid)){
  return "Failed";
  }
  $writerdetails = $this->getPartnerDetails($writerid);
  if(!is_array($writerdetails) || !is_array($writerdetails[3])){
  return "Failed";
  }
  $writerfollowers = $writerdetails[3];
  $writerid = "'$writerid'";
  $uid = "'$uid'";
  if(array_key_exists($writerid,$storiesfollowed) && array_key_exists($uid,$writerfollowers)){
  unset($storiesfollowed[$writerid]);
  unset($writerfollowers[$uid]);
  $storiesfollowed = json_encode($storiesfollowed);
  $storiesfollowed = $conn->real_escape_string($storiesfollowed);
  $writerfollowers = json_encode($writerfollowers);
  $writerfollowers = $conn->real_escape_string($writerfollowers);
  $sql = "update oaumeetupusers set storiesfollowed = '$storiesfollowed' where userid = '$idu' limit 1";
  $sql1 = "update oaumeetupusers set followers = '$writerfollowers' where userid='$wrid' limit 1";
  if($conn->query($sql) == "true"){
  $conn->query($sql1);
  return "success";
  }else{
  return "Failed".$conn->error;
  }
  }else{
  return "Failed";
  }

  }
  //public function to unfollow a story ends here
   
  //public function to remove follwers starts here
  public function removefollower($followerid){
  $followerid = $this->clean_input($followerid);
  $uid = $this->userid;
  $fid = $followerid;
  $idu = $uid;
  $conn = $this->conn;
  $followers = $this->followers;
  if(!is_array($followers) || count($followers) < 1 || empty($followerid)){
  return "Failed";
  }
  $followerdetails = $this->getPartnerDetails($followerid);
  if(!is_array($followerdetails) || !is_array($followerdetails[4])){
  return "Failed";
  }
  $storiesfollowed = $followerdetails[4];
  $followerid = "'$followerid'";
  $uid = "'$uid'";
  if(array_key_exists($uid,$storiesfollowed) && array_key_exists($followerid,$followers)){
  unset($storiesfollowed[$uid]);
  unset($followers[$followerid]);
  $storiesfollowed = json_encode($storiesfollowed);
  $storiesfollowed = $conn->real_escape_string($storiesfollowed);
  $followers = json_encode($followers);
  $followers = $conn->real_escape_string($followers);
  $sql = "update oaumeetupusers set storiesfollowed = '$storiesfollowed' where userid = '$fid' limit 1";
  $sql1 = "update oaumeetupusers set followers = '$followers' where userid='$idu' limit 1";
  if($conn->query($sql1) == "true"){
  $conn->query($sql);
  return "success";
  }else{
  return "Failed".$conn->error;
  }
  }else{
  return "Failed";
  }

  }
  //public function to remove follwers ends here

  //function to Get User Details starts Here
    public function getPartnerDetails($data){
    $id = $this->clean_input($data);
    if(empty($id)){
    return array();
    }
    $conn = $this->conn;
    $sql = "select username,gender,avatar,storiesfollowed,followers from oaumeetupusers where userid ='$id' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $uname = $row["username"];
    $gender = $row["gender"];
    $avatar = $row["avatar"];
    $storiesfollowed = $row["storiesfollowed"];
    $followers = $row["followers"];
    if(empty($followers)){
    $followers = array();
    }else{
    $followers = json_decode($followers,true);
    }
    if(empty($storiesfollowed)){
    $storiesfollowed = array();
    }else{
    $storiesfollowed = json_decode($storiesfollowed,true);
    }
    if(empty($avatar) && $gender == "male"){
    $avatar= "maledefault.png";
    }else if(empty($avatar) && $gender == "female"){
    $avatar = "femaledefault.jpeg";
    }
    return array($uname,$gender,$avatar,$followers,$storiesfollowed);
    }else{
    return array();
    }    
    }
    //function to get User DetaIls Ends Here




 }
?>