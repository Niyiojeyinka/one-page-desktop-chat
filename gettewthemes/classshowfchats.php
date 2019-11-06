<?php
session_start();
require 'classmeetupvalidate.php';
/**$_SESSION["showfchatlast"]
 * class to handle showing of normal chat to user
 */
class showfchats extends meetupvalidate
{
	private $uid;
	function __construct()
	{
    $this->createConnection();
    if(isset($_SESSION["userid"])){
	$this->setUid($_SESSION["userid"]);
	}
	}
	public function setUid($uid){
	$this->uid = $this->clean_input($uid);
	}
	public function getUid(){
	return $this->uid;
	}

	//public function to get different chat messages starts here
	public function getFchats(){
	$conn = $this->conn;
	$uid = $this->getUid();
	$data = "";
	$sql = "select receivermsg,numnewmsg,shortnewmsg,chatid,creatorid,recepientid,msgdetails,latestmsgtime from fchat where creatorid ='$uid' or recepientid='$uid' order by latestmsgtime desc limit 10";

	$result = $conn->query($sql);
	if($result->num_rows > 0){
    $n = 0;
    $num_rows = $result->num_rows;
	while($row = $result->fetch_assoc()){
    ++$n;
    
	$receivermsg = $row["receivermsg"];
	$numnewmsg = $row["numnewmsg"];
	$shortnewmsg = $row["shortnewmsg"];
	$chatid = $row["chatid"];
    $creatorid = $row["creatorid"];
    $recepientid = $row["recepientid"];
    $msgdetails = $row["msgdetails"];
    if($n == $num_rows){
    $_SESSION["showfchatlast"] = $row["latestmsgtime"];
    }
    if($creatorid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($recepientid);
    }elseif($recepientid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($creatorid);
    }else{
    continue;
    }
    $chatpartneruname = $chatpartnerdetails[0];
    $chatpartneravatar = $chatpartnerdetails[1];

    //to arrange chat starts here
    if(!empty($receivermsg) && !empty($shortnewmsg) && $receivermsg == $uid && $numnewmsg > 0){

    if(stripos($shortnewmsg,"photo") !== false){
    $shortnewmsg = "<i class='fa fa-camera-retro'></i> Photo";
    }else{
    if(strlen($shortnewmsg) > 25){
    $shortnewmsg = substr($shortnewmsg,0,25)."...";
    }
    }

    $data .="
    <a href='oaumeetupfchat.php?chatid=$chatid' style='text-decoration:none;'>
    <li class='w3-bar w3-display-container w3-animate-zoom w3-ripple w3-border-bottom'style='width:100%;margin:auto;'>
   <b class='w3-display-right w3-circle w3-green w3-padding w3-card'style='margin-right:50px;'>$numnewmsg</b>
   <img src='chatplaceholder1.jpg' data-src='$chatpartneravatar'class='lazyload w3-bar-item w3-circle'style='width:50px;height:50px;padding:0;margin-right:10px;'>
   <div class='w3-bar-item' style='width:70%;padding:0;margin-top:3px;''>
   <b class=''style='margin-left:5px'>$chatpartneruname</b><br>
   <span class='w3-small w3-text-grey w3-block'style='margin-left:5px;margin-bottom:8px;margin-top:3px;'>$shortnewmsg</span>
   </div>
   </li>
   </a>";

    /*closing braces for if there are new messages*/}else{

    $time = "";
    $msg = "";
    //check if msg is empty
    if(!empty($msgdetails)){
    $msgdetails = str_replace('®®©','\ud',$msgdetails);
    $msgdetails = json_decode($msgdetails,true);
    //check if msgdetails is an array or has no element starts here
    if(!is_array($msgdetails) || count($msgdetails) < 1){
    $msg = "";
    }else{
    $a = end($msgdetails);
    if($a[2] == $uid){
    //determine whether photo or text message
    if($a[0] != "" && $a[1] == "" && $a[5] != 1){
    $msg = strip_tags (stripslashes($a[0]));
    $time = strftime("%a %b %d %Y @ %I:%M%p",$a[4]);
    if(strlen($msg) > 25){
    $msg = substr($msg,0,25)."...";
    }
    }elseif($a[0] == "" && $a[1] != "" && $a[5] != 1){
    $msg = "You: <i class='fa fa-camera-retro'></i> Photo";
    $time = strftime("%a %b %d %Y @ %I:%M%p",$a[4]);
    }
    //determine whether photo or text message
    }elseif($a[3] == $uid){
    //determine whether photo or text message
    if($a[0] != "" && $a[1] == "" && $a[6] != 1){
    $msg = strip_tags (stripslashes($a[0]));
    if(strlen($msg) > 25){
    $msg = substr($msg,0,25)."...";
    }
    }elseif($a[0] == "" && $a[1] != "" && $a[6] != 1){
    $msg = "<i class='fa fa-camera-retro'></i> Photo";
    }
    //determine whether photo or text message
    }else{
    continue;
    }
    }
    //check if msgdetails is an array or has no element ends here
    }
    //checking if msgdetails is empty

    $data .="
    <a href='oaumeetupfchat.php?chatid=$chatid' style='text-decoration:none;'>
    <li class='w3-bar w3-display-container w3-animate-zoom w3-ripple w3-border-bottom'style='width:100%;margin:auto;'>
    <span class='w3-display-topright w3-tiny w3-text-grey'style='margin-right:5px;'>$time</span>
   <img src='chatplaceholder1.jpg' data-src='$chatpartneravatar'class='lazyload w3-bar-item w3-circle'style='width:50px;height:50px;padding:0;margin-right:10px;'>
   <div class='w3-bar-item' style='width:70%;padding:0;margin-top:3px;''>
   <b class=''style='margin-left:5px'>$chatpartneruname</b><br>
   <span class='w3-small w3-text-grey w3-block'style='margin-left:5px;margin-bottom:8px;margin-top:3px;'>$msg</span>
   </div>
   </li>
   </a>";

    }//stale messages
    //to arrange chat ends here


    }//while loop

    if($num_rows == 10){
    $data .="<button id='morechatbtn' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
      <span class='' id='morechatshow'><img src='chaticon.png' style='width:30px;height:30px;'> Click to load more chat</span>
      <span class='w3-hide' id='morechatprg'><i class='fa fa-spinner w3-spin'></i> getting more chat...</span>
      </button>";
    }else{
    unset($_SESSION["showfchatlast"]);
    }
	return $data;
	}else{
	/*echo  $conn->error;
	exit();*/
	return "<a href='search.php' style='text-decoration:none;'><div style='margin-top:30vh;' class='w3-center w3-text-blue w3-ripple'><img src='chaticon.png' style='width:60px;height:60px;'> Click to start  chat with  new people</div></a>";
	}
	}
    //public function to get different chat messages starts here

    //function to Get User Details starts Here
    public function getPartnerDetails($data){
    $conn = $this->conn;
    $uid = $this->getUid();
    $data = $this->clean_input($data);
    $sql = "select username,gender,avatar from oaumeetupusers where userid = '$data' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $uname = $row["username"];
    $gender = $row["gender"];
    $avatar = $row["avatar"];
    if(empty($avatar) && $gender == "male"){
    $avatar= "maledefault.png";
    }else if(empty($avatar) && $gender == "female"){
    $avatar = "femaledefault.jpeg";
    }
    return array($uname,$avatar,$gender);
    }else{
    return "";
    }    
    }
    //function to get User DetaIls Ends Here

    //public function to get mroe chat starts here
    public function getmorechat(){
    if(!isset($_SESSION["showfchatlast"]) || strlen($_SESSION["showfchatlast"]) < 1){
    return "nomorechat a";
    }
    $lastid = $this->clean_input($_SESSION["showfchatlast"]);
    $conn = $this->conn;
    $uid = $this->getUid();
    $data = "";
    $sql = "select id,receivermsg,numnewmsg,shortnewmsg,chatid,creatorid,recepientid,msgdetails,latestmsgtime from fchat where creatorid ='$uid' and latestmsgtime < '$lastid' or recepientid='$uid' and latestmsgtime < '$lastid' order by latestmsgtime desc limit 10";

    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $n = 0;
    $num_rows = $result->num_rows;
    while($row = $result->fetch_assoc()){
    ++$n;
    $receivermsg = $row["receivermsg"];
    $numnewmsg = $row["numnewmsg"];
    $shortnewmsg = $row["shortnewmsg"];
    $chatid = $row["chatid"];
    $creatorid = $row["creatorid"];
    $recepientid = $row["recepientid"];
    $msgdetails = $row["msgdetails"];
    if($n == $num_rows){
    $_SESSION["showfchatlast"] = $row["latestmsgtime"];
    }
    if($creatorid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($recepientid);
    }elseif($recepientid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($creatorid);
    }else{
    continue;
    }
    $chatpartneruname = $chatpartnerdetails[0];
    $chatpartneravatar = $chatpartnerdetails[1];

    //to arrange chat starts here
    if(!empty($receivermsg) && !empty($shortnewmsg) && $receivermsg == $uid && $numnewmsg > 0){

    if(stripos($shortnewmsg,"photo") !== false){
    $shortnewmsg = "<i class='fa fa-camera-retro'></i> Photo";
    }else{
    if(strlen($shortnewmsg) > 25){
    $shortnewmsg = substr($shortnewmsg,0,25)."...";
    }
    }

    $data .="
    <a href='oaumeetupfchat.php?chatid=$chatid' style='text-decoration:none;'>
    <li class='w3-bar w3-display-container w3-animate-zoom w3-ripple w3-border-bottom'style='width:100%;margin:auto;'>
   <b class='w3-display-right w3-circle w3-green w3-padding w3-card'style='margin-right:50px;'>$numnewmsg</b>
   <img src='chatplaceholder1.jpg' data-src='$chatpartneravatar'class='lazyload w3-bar-item w3-circle'style='width:50px;height:50px;padding:0;margin-right:10px;'>
   <div class='w3-bar-item' style='width:70%;padding:0;margin-top:3px;''>
   <b class=''style='margin-left:5px'>$chatpartneruname</b><br>
   <span class='w3-small w3-text-grey w3-block'style='margin-left:5px;margin-bottom:8px;margin-top:3px;'>$shortnewmsg</span>
   </div>
   </li>
   </a>";

    /*closing braces for if there are new messages*/}else{

    $time = "";
    $msg = "";
    //check if msg is empty
    if(!empty($msgdetails)){
    $msgdetails = str_replace('®®©','\ud',$msgdetails);
    $msgdetails = json_decode($msgdetails,true);
    //check if msgdetails is an array or has no element starts here
    if(!is_array($msgdetails) || count($msgdetails) < 1){
    $msg = "";
    }else{
    $a = end($msgdetails);
    if($a[2] == $uid){
    //determine whether photo or text message
    if($a[0] != "" && $a[1] == "" && $a[5] != 1){
    $msg = strip_tags (stripslashes($a[0]));
    $time = strftime("%a %b %d %Y @ %I:%M%p",$a[4]);
    if(strlen($msg) > 25){
    $msg = substr($msg,0,25)."...";
    }
    }elseif($a[0] == "" && $a[1] != "" && $a[5] != 1){
    $msg = "You: <i class='fa fa-camera-retro'></i> Photo";
    $time = strftime("%a %b %d %Y @ %I:%M%p",$a[4]);
    }
    //determine whether photo or text message
    }elseif($a[3] == $uid){
    //determine whether photo or text message
    if($a[0] != "" && $a[1] == "" && $a[6] != 1){
    $msg = strip_tags (stripslashes($a[0]));
    if(strlen($msg) > 25){
    $msg = substr($msg,0,25)."...";
    }
    }elseif($a[0] == "" && $a[1] != "" && $a[6] != 1){
    $msg = "<i class='fa fa-camera-retro'></i> Photo";
    }
    //determine whether photo or text message
    }else{
    continue;
    }
    }
    //check if msgdetails is an array or has no element ends here
    }
    //checking if msgdetails is empty

    $data .="
    <a href='oaumeetupfchat.php?chatid=$chatid' style='text-decoration:none;'>
    <li class='w3-bar w3-display-container w3-animate-zoom w3-ripple w3-border-bottom'style='width:100%;margin:auto;'>
    <span class='w3-display-topright w3-tiny w3-text-grey'style='margin-right:5px;'>$time</span>
   <img src='chatplaceholder1.jpg' data-src='$chatpartneravatar'class='lazyload w3-bar-item w3-circle'style='width:50px;height:50px;padding:0;margin-right:10px;'>
   <div class='w3-bar-item' style='width:70%;padding:0;margin-top:3px;''>
   <b class=''style='margin-left:5px'>$chatpartneruname</b><br>
   <span class='w3-small w3-text-grey w3-block'style='margin-left:5px;margin-bottom:8px;margin-top:3px;'>$msg</span>
   </div>
   </li>
   </a>";

    }//stale messages
    //to arrange chat ends here
    }//while loop

    if($num_rows != 10){
    unset($_SESSION["showfchatlast"]);
    }
    return $data;
    }else{
    /*echo  $conn->error;
    exit();*/
    return "nomorechat c";
    }

    }
    //public function to get more chat ends here


}
?>