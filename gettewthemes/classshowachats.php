<?php
session_start();
require 'classmeetupvalidate.php';
/**$_SESSION["oldanonylastmsg"] $_SESSION["newanonylastmsg"]
 * class to handle display of user different achat messages 
 */
class showachats extends meetupvalidate
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
    
    //public function to get aggregate anonymous chat of user starts here
    public function getAnonymousNewMsg(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $data = "";
    $sql = "select receivermsg,numnewmsg,shortnewmsg,achatid,creatorid,recepientid,latestmsgtime from achat where creatorid = '$uid' and receivermsg = '$uid' and shortnewmsg != '' or recepientid = '$uid' and receivermsg = '$uid' and shortnewmsg != '' order by latestmsgtime desc limit 10";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $n = 0;
    $num_rows = $result->num_rows;
    while($row = $result->fetch_assoc()){
    ++$n;
    $receivermsg = $row["receivermsg"];
    $numnewmsg = $row["numnewmsg"];
    $shortnewmsg = strip_tags(stripslashes($row["shortnewmsg"]));
    $achatid = $row["achatid"];
    $creatorid = $row["creatorid"];
    $recepientid = $row["recepientid"];
    if($n == $num_rows){
    $_SESSION["newanonylastmsg"] = $row["latestmsgtime"];
    }
    if(stripos($shortnewmsg,"photo") !== false){
    $shortnewmsg = "<i class='fa fa-camera-retro'></i> Photo";
    }else{
    if(strlen($shortnewmsg) > 25){
    $shortnewmsg = substr($shortnewmsg,0,25)."...";
    }
    }

    if($creatorid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($recepientid);
    if(empty($chatpartnerdetails) || !is_array($chatpartnerdetails)){
    continue;
    }
    $chatpartneruname = $chatpartnerdetails[0];
    $chatpartneravatar = $chatpartnerdetails[1];
    $chatpartnergender = $chatpartnerdetails[2];
    }elseif($recepientid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($creatorid);
    if(empty($chatpartnerdetails) || !is_array($chatpartnerdetails)){
    continue;
    }
    $chatpartnergender = $chatpartnerdetails[2];
    if($chatpartnergender == "male"){
    $chatpartneruname = "Male <i class='fa fa-male'></i>";
    }elseif($chatpartnergender == "female"){
    $chatpartneruname = "Female <i class='fa fa-female'></i>";
    }
    $chatpartneravatar ="chathide.jpg";
    }else{
    continue;
    }
    $data .="
    <a href='oaumeetupachat.php?achatid=$achatid' style='text-decoration:none;'>
    <li class='w3-bar w3-display-container w3-animate-zoom w3-ripple w3-border-bottom'style='width:100%;margin:auto;'>
   <b class='w3-display-right w3-circle w3-green w3-padding w3-card'style='margin-right:50px;'>$numnewmsg</b>
   <img src='chatplaceholder1.jpg' data-src='$chatpartneravatar'class='lazyload w3-bar-item w3-circle'style='width:50px;height:50px;padding:0;margin-right:10px;'>
   <div class='w3-bar-item' style='width:70%;padding:0;margin-top:3px;''>
   <b class=''style='margin-left:5px'>$chatpartneruname</b><br>
   <span class='w3-small w3-text-grey w3-block'style='margin-left:5px;margin-bottom:8px;margin-top:3px;'>$shortnewmsg</span>
   </div>
   </li>
   </a>";

    }//while loops
    if($num_rows == 10){
    $data .="<button id='morenachatbtn' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
    <span class='' id='morenachatshow'><img src='chathide.jpg' style='width:30px;height:30px;'> Click to load more chat</span>
    <span class='w3-hide' id='morenachatprg'><i class='fa fa-spinner w3-spin'></i> getting more chat...</span>
    </button>";
    }else{
    unset($_SESSION["oldanonylastmsg"]);
    }
    return $data;
    }else{
    return "<a href='search.php'style='text-decoration:none;'>
    <div style='margin-top:30vh;' class='w3-center w3-text-blue w3-ripple'><img src='chathide.jpg' style='width:60px;height:60px;'>Click to start anonymous chat with  new people</div>
    </a>";
    }
    }
    //public function to get aggregate anonymous new chat ends here

    //public function to get aggregate anonymous stale chat starts here
    public function getAnonymousOldMsg(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $data = "";
    $sql = "select achatid,creatorid,recepientid,msgdetails,latestmsgtime from achat where creatorid = '$uid' and receivermsg != '$uid' or recepientid = '$uid' and receivermsg != '$uid' order by latestmsgtime desc limit 10";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $n = 0;
    $num_rows = $result->num_rows;
    while($row = $result->fetch_assoc()){
    ++$n;
    $achatid = $row["achatid"];
    $creatorid = $row["creatorid"];
    $recepientid = $row["recepientid"];
    $msgdetails = $row["msgdetails"];
    if($n == $num_rows){
    $_SESSION["oldanonylastmsg"] = $row["latestmsgtime"];
    }
    $msgdetails = str_replace('®®©','\ud',$msgdetails);
    $time = "";
    $msg = "";

    if($creatorid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($recepientid);
    if(empty($chatpartnerdetails) || !is_array($chatpartnerdetails)){
    continue;
    }
    $chatpartneruname = $chatpartnerdetails[0];
    $chatpartneravatar = $chatpartnerdetails[1];
    $chatpartnergender = $chatpartnerdetails[2];
    }elseif($recepientid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($creatorid);
    if(empty($chatpartnerdetails) || !is_array($chatpartnerdetails)){
    continue;
    }
    $chatpartnergender = $chatpartnerdetails[2];
    if($chatpartnergender == "male"){
    $chatpartneruname = "Male <i class='fa fa-male'></i>";
    }elseif($chatpartnergender == "female"){
    $chatpartneruname = "Female <i class='fa fa-female'></i>";
    }
    $chatpartneravatar ="chathide.jpg";
    }else{
    continue;
    }
    //check if msg is empty
    if(!empty($msgdetails)){
    $msgdetails = json_decode($msgdetails,true);
    
    //check if msgdetails is an array or has no element starts here
    if(!is_array($msgdetails) || count($msgdetails) < 1){
    $msg = "";
    }else{
    $msg = "";
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

    }else{
    $msg = "";
    }
    //checking if msgdetails is empty
    $data .="
    <a href='oaumeetupachat.php?achatid=$achatid' style='text-decoration:none;'>
    <li class='w3-bar w3-display-container w3-animate-zoom w3-ripple w3-border-bottom'style='width:100%;margin:auto;'>
    <span class='w3-display-topright w3-tiny w3-text-grey'style='margin-right:5px;'>$time</span>
   <img src='chatplaceholder1.jpg' data-src='$chatpartneravatar'class='lazyload w3-bar-item w3-circle'style='width:50px;height:50px;padding:0;margin-right:10px;'>
   <div class='w3-bar-item' style='width:70%;padding:0;margin-top:3px;''>
   <b class=''style='margin-left:5px'>$chatpartneruname</b><br>
   <span class='w3-small w3-text-grey w3-block'style='margin-left:5px;margin-bottom:8px;margin-top:3px;'>$msg</span>
   </div>
   </li>
   </a>";
    }//while loop

    if($num_rows == 10){
    $data .="<button id='moreoachatbtn' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
    <span class='' id='moreoachatshow'><img src='chathide.jpg' style='width:30px;height:30px;'> Click to load more achat</span>
    <span class='w3-hide' id='moreoachatprg'><i class='fa fa-spinner w3-spin'></i> getting more achat...</span>
    </button>";
    }else{
    unset($_SESSION["oldanonylastmsg"]);
    }
    return $data;
    }else{
    return "<a href='search.php'style='text-decoration:none;'>
    <div style='margin-top:30vh;' class='w3-center w3-text-blue w3-ripple'><img src='chathide.jpg' style='width:60px;height:60px;'>Click to start anonymous chat with  new people</div>
    </a>";
    }	
    }
    //public function to get aggregate anonymous stale chat ends here

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

    //public function to get more old anonymous message starts here
    public function getmoreoachat(){
    if(!isset($_SESSION["oldanonylastmsg"]) || strlen($_SESSION["oldanonylastmsg"]) < 1){
    return "nomoreoachat a";
    }  
    $conn = $this->conn;
    $uid = $this->getUid();
    $data = "";
    $lastid = $this->clean_input($_SESSION["oldanonylastmsg"]);
    $sql = "select achatid,creatorid,recepientid,msgdetails,latestmsgtime from achat where creatorid = '$uid' and receivermsg != '$uid' and latestmsgtime < '$lastid' or recepientid = '$uid' and receivermsg != '$uid' and latestmsgtime < '$lastid' order by latestmsgtime desc limit 10";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $n = 0;
    $num_rows = $result->num_rows;
    while($row = $result->fetch_assoc()){
    ++$n;
    $achatid = $row["achatid"];
    $creatorid = $row["creatorid"];
    $recepientid = $row["recepientid"];
    $msgdetails = $row["msgdetails"];
    if($n == $num_rows){
    $_SESSION["oldanonylastmsg"] = $row["latestmsgtime"];
    }
    $msgdetails = str_replace('®®©','\ud',$msgdetails);
    $time = "";
    $msg = "";

    if($creatorid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($recepientid);
    if(empty($chatpartnerdetails) || !is_array($chatpartnerdetails)){
    continue;
    }
    $chatpartneruname = $chatpartnerdetails[0];
    $chatpartneravatar = $chatpartnerdetails[1];
    $chatpartnergender = $chatpartnerdetails[2];
    }elseif($recepientid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($creatorid);
    if(empty($chatpartnerdetails) || !is_array($chatpartnerdetails)){
    continue;
    }
    $chatpartnergender = $chatpartnerdetails[2];
    if($chatpartnergender == "male"){
    $chatpartneruname = "Male <i class='fa fa-male'></i>";
    }elseif($chatpartnergender == "female"){
    $chatpartneruname = "Female <i class='fa fa-female'></i>";
    }
    $chatpartneravatar ="chathide.jpg";
    }else{
    continue;
    }
    //check if msg is empty
    if(!empty($msgdetails)){
    $msgdetails = json_decode($msgdetails,true);
    
    //check if msgdetails is an array or has no element starts here
    if(!is_array($msgdetails) || count($msgdetails) < 1){
    $msg = "";
    }else{
    $msg = "";
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

    }else{
    $msg = "";
    }
    //checking if msgdetails is empty
    $data .="
    <a href='oaumeetupachat.php?achatid=$achatid' style='text-decoration:none;'>
    <li class='w3-bar w3-display-container w3-animate-zoom w3-ripple w3-border-bottom'style='width:100%;margin:auto;'>
    <span class='w3-display-topright w3-tiny w3-text-grey'style='margin-right:5px;'>$time</span>
   <img src='chatplaceholder1.jpg' data-src='$chatpartneravatar'class='lazyload w3-bar-item w3-circle'style='width:50px;height:50px;padding:0;margin-right:10px;'>
   <div class='w3-bar-item' style='width:70%;padding:0;margin-top:3px;''>
   <b class=''style='margin-left:5px'>$chatpartneruname</b><br>
   <span class='w3-small w3-text-grey w3-block'style='margin-left:5px;margin-bottom:8px;margin-top:3px;'>$msg</span>
   </div>
   </li>
   </a>";
    }//while loop

    if($num_rows != 10){
    unset($_SESSION["oldanonylastmsg"]);
    }
    return $data;
    }else{
    return "nomoreoachat c";
    }   

    }
    //public function to get more old anonymous message ends here

    //public function to get more new anonymous messasge starts here
    public function getmorenachat(){
    if(!isset($_SESSION["newanonylastmsg"]) || strlen($_SESSION["newanonylastmsg"]) < 1){
    return "nomorenachat a";
    }  
    $conn = $this->conn;
    $uid = $this->getUid();
    $data = "";
    $lastid = $this->clean_input($_SESSION["newanonylastmsg"]);
    $sql = "select receivermsg,numnewmsg,shortnewmsg,achatid,creatorid,recepientid,latestmsgtime from achat where creatorid = '$uid' and receivermsg = '$uid' and shortnewmsg != '' and latestmsgtime < '$lastid' or recepientid = '$uid' and receivermsg = '$uid' and shortnewmsg != '' and latestmsgtime < '$lastid' order by latestmsgtime desc limit 10";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $n = 0;
    $num_rows = $result->num_rows;
    while($row = $result->fetch_assoc()){
    ++$n;
    $receivermsg = $row["receivermsg"];
    $numnewmsg = $row["numnewmsg"];
    $shortnewmsg = strip_tags(stripslashes($row["shortnewmsg"]));
    $achatid = $row["achatid"];
    $creatorid = $row["creatorid"];
    $recepientid = $row["recepientid"];
    if($n == $num_rows){
    $_SESSION["newanonylastmsg"] = $row["latestmsgtime"];
    }
    if(stripos($shortnewmsg,"photo") !== false){
    $shortnewmsg = "<i class='fa fa-camera-retro'></i> Photo";
    }else{
    if(strlen($shortnewmsg) > 25){
    $shortnewmsg = substr($shortnewmsg,0,25)."...";
    }
    }

    if($creatorid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($recepientid);
    if(empty($chatpartnerdetails) || !is_array($chatpartnerdetails)){
    continue;
    }
    $chatpartneruname = $chatpartnerdetails[0];
    $chatpartneravatar = $chatpartnerdetails[1];
    $chatpartnergender = $chatpartnerdetails[2];
    }elseif($recepientid == $uid){
    $chatpartnerdetails = $this->getPartnerDetails($creatorid);
    if(empty($chatpartnerdetails) || !is_array($chatpartnerdetails)){
    continue;
    }
    $chatpartnergender = $chatpartnerdetails[2];
    if($chatpartnergender == "male"){
    $chatpartneruname = "Male <i class='fa fa-male'></i>";
    }elseif($chatpartnergender == "female"){
    $chatpartneruname = "Female <i class='fa fa-female'></i>";
    }
    $chatpartneravatar ="chathide.jpg";
    }else{
    continue;
    }
    $data .="
    <a href='oaumeetupachat.php?achatid=$achatid' style='text-decoration:none;'>
    <li class='w3-bar w3-display-container w3-animate-zoom w3-ripple w3-border-bottom'style='width:100%;margin:auto;'>
   <b class='w3-display-right w3-circle w3-green w3-padding w3-card'style='margin-right:50px;'>$numnewmsg</b>
   <img src='chatplaceholder1.jpg' data-src='$chatpartneravatar'class='lazyload w3-bar-item w3-circle'style='width:50px;height:50px;padding:0;margin-right:10px;'>
   <div class='w3-bar-item' style='width:70%;padding:0;margin-top:3px;''>
   <b class=''style='margin-left:5px'>$chatpartneruname</b><br>
   <span class='w3-small w3-text-grey w3-block'style='margin-left:5px;margin-bottom:8px;margin-top:3px;'>$shortnewmsg</span>
   </div>
   </li>
   </a>";

    }//while loops
    if($num_rows != 10){
    unset($_SESSION["newanonylastmsg"]);
    }
    return $data;
    }else{
    return "nomorenachat c";
    }
    }
    //public function to get more new anonymous messasge ends here



}

?>