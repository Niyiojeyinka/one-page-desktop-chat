<?php
session_start();
require "classmeetupvalidate.php";
/**
 * $_SESSION["lastnotifynum"]
 */
class notification extends meetupvalidate
{
	private $userid;
	function __construct(){
	$this->createConnection();
	if(isset($_SESSION["userid"])){
	$this->userid = $this->clean_input($_SESSION["userid"]);
	}
    }

    //public function to starts getting notification starts here
    public function getnotes(){
    $conn = $this->conn;
    $uid = $this->userid;
    $uname = $this->clean_input($_SESSION["uname"]);
    $followers = $this->followers;
    $fw = array_keys($this->followers);
    $fw = implode(",",$fw);
    $storiesfollowed = $this->storiesfollowed;
    $sfw = array_keys($storiesfollowed);
    $sfw = implode(",",$sfw);
    $lastnotes = $this->lastnotes;
    $sql = '';
    if(empty($lastnotes)){
    $lastnotes = 0;
    }
    if(count($followers) > 0 && count($storiesfollowed) > 0){
    $sql = "select * from notification where creatornotifyid ='$uid' and type in('a','d','g','e','h') or creatornotifyid ='oaumeetup' and  type in('f') union select * from notification where creatornotifyid in($fw) and type in ('b','c') or creatornotifyid in($sfw) and type in('b','c') order by id desc limit 10";
    }elseif(count($followers) > 0  && count($storiesfollowed) < 1) {
    $sql = "select * from notification where creatornotifyid ='$uid' and type in('a','d','g','e','h') or creatornotifyid ='oaumeetup' and  type in('f') union select * from notification where creatornotifyid in($fw) and type in ('b','c') order by id desc limit 10";
    }elseif(count($followers) < 1  && count($storiesfollowed) > 0){
    $sql = "select * from notification where creatornotifyid ='$uid' and type in('a','d','g','e','h') or creatornotifyid ='oaumeetup' and  type in('f') union select * from notification where creatornotifyid in($sfw) and type in('b','c') order by id desc limit 10";
    }else{
    $sql = "select * from notification where creatornotifyid ='$uid' and type in('a','d','g','e','h') or creatornotifyid ='oaumeetup' and  type in('f') order by id desc limit 10";
    }

    $result = $conn->query($sql);

    if($result->num_rows > 0){
    $n = 0;
    $num_rows = $result->num_rows;
    $data = "";
    while($row = $result->fetch_assoc()){
    ++$n;
    if($n == $num_rows && $num_rows == 10){
    $_SESSION["lastnotifynum"] = $row["id"];
    }
    $id = $row["id"];
    $creatornotifyid = $row["creatornotifyid"];
    $recepientnotifyid = $row["recepientnotifyid"];
    $type = $row["type"];
    $landpagelink = $row["landpagelink"];
    if(!empty($landpagelink)){
    $landpagelink = json_decode($landpagelink,true);
    }else{
    $landpagelink = array();
    }
    $date = strftime("%a %b %d %Y @ %I:%M%p",$row['date']);
    //conditions to display notifications in the right way
    if($type == "a"){
    $data .="<li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
    <img src='bakground.jpeg'class='w3-bar-item'style='width:70px;height:70px;padding:5px;'>	
    <div class='w3-bar-item' style='width:70%;padding:0;margin:3px; margin-left:6px;'>
    <span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>Hello $uname welcome to oaumeetup :) we are happy to have you here please upload your profile picture on time so people can find you,click <a href='oaumeetupprofile.php'style='text-decoration:none;'><b class='w3-text-blue'>here</b></a> to get started</span>
    </div>
    </li>";
    }elseif($type =="f" && count($landpagelink) > 0 && $creatornotifyid == $uid){
    $cont = count($landpagelink);
    $pid = array_pop($landpagelink);
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .= "<a href='oaumeetupprofile.php?uid=$pid'style='text-decoration:none;'><li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
        <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
    <img src='chatplaceholder1.jpg' data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>	
    <div class='w3-bar-item' style='width:70%;padding:0;margin:3px;margin-left:6px;'>";
    if($cont > 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others just joined oaumeetup!:) be the first to connect</span>
    </div>
    </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname just joined oaumeetup! :) be the first to connect</span>
    </div>
    </li></a>";
    }
    }elseif($type == "b" && !empty($creatornotifyid)){
    $uname=$avatar=$wrds="";
    $details = $this->getPartnerDetails($creatornotifyid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='oaumeetupprofile.php?uid=$creatornotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
    <img src='chatplaceholder1.jpg' data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>	
    <div class='w3-bar-item' style='width:70%;padding:0;margin:3px; margin-left: 6px;'>
    <span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname just updated  profile picture</span>
    </div>
    </li>
    </a>";
    }elseif($type == "g" && count($landpagelink) > 0){
    $cont = count($landpagelink) -1;
    $pid = array_keys($landpagelink);
    $pid = str_replace("'","",array_pop($pid));
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='oaumeetupprofile.php?uid=$creatornotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
    <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>	
    <div class='w3-bar-item' style='width:70%;padding:0;margin:3px; margin-left: 6px;'>";
    if($cont >= 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others are following you</span>
    </div>
    </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname is following you</span>
    </div>
    </li></a>";
    }
    }elseif($type == "d" && $creatornotifyid == $uid && !empty($recepientnotifyid) && count($landpagelink) > 0){
    $cont = count($landpagelink) -1;
    $pid = array_pop($landpagelink);
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='oaumeetupstorycomments.php?storyid=$recepientnotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
     <!--<a href='oaumeetupprofile.php?uid=$pid'style='text-decoration:none;'>-->
     <img src='chatplaceholder1.jpg'data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>
     <!--</a>-->	
     <div class='w3-bar-item' style='width:70%;padding:0;margin:3px;margin-left:6px;'>";
    if($cont >= 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others commented on your story</span>
     </div>
     </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname commented on your story</span>
     </div>
     </li></a>";
    }
    }elseif($type == "h" && $creatornotifyid == $uid && !empty($recepientnotifyid) && count($landpagelink) > 0){
    $cont = count($landpagelink) -1;
    $pid = array_pop($landpagelink);
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='oaumeetupreadstory.php?storyid=$recepientnotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
     <!--<a href='oaumeetupprofile.php?uid=$pid'style='text-decoration:none;'>-->
     <img src='chatplaceholder1.jpg'data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>
     <!--</a>-->	
     <div class='w3-bar-item' style='width:70%;padding:0;margin:3px;margin-left:6px;'>";
    if($cont >= 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others viewed your story</span>
     </div>
     </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname viewed your story</span>
     </div>
     </li></a>";
    }
    }elseif($type == "e" && $creatornotifyid == $uid && !empty($recepientnotifyid) && count($landpagelink) > 0){
    $cont = count($landpagelink) -1;
    $pid = array_pop($landpagelink);
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='replycomment.php?commentid=$recepientnotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
     <!--<a href='oaumeetupprofile.php?uid=$pid'style='text-decoration:none;'>-->
     <img src='chatplaceholder1.jpg'data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>
     <!--</a>-->	
     <div class='w3-bar-item' style='width:70%;padding:0;margin:3px;margin-left:6px;'>";
    if($cont >= 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others replied to your comment</span>
     </div>
     </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname replied to your comment</span>
     </div>
     </li></a>";
    }
    }
    //conditions to display notifications in the right way
    }//while loop 
    if($num_rows == 10 && isset($_SESSION["lastnotifynum"])){
    $data .="<button id='morenotebtn' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
      <span class='' id='morenoteshow'><i class='fa fa-bell'></i> Click to load more notifications</span>
      <span class='w3-hide' id='morenoteprg'><i class='fa fa-spinner w3-spin'></i> getting more notifications...</span>
      </button>";
    }
    return $data;
    /*num_rows > 0*/}else{
    return "<div style='margin-top:30vh;' class='w3-center w3-text-blue w3-ripple w3-xlarge'><i class='fa fa-bell'></i> You have no notification yet</div></a>";
    }
    }
    //public function to starts getting notification ends here
    //function to Get User Details starts Here
    public function getPartnerDetails($data){
    $conn = $this->conn;
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

    //public function to fget more notifications starts here
    public function getmorenotes(){
    if(!isset($_SESSION["lastnotifynum"]) || strlen($_SESSION["lastnotifynum"]) < 1){
    return "nomorenotes a";
    }
    $lastid = $this->clean_input($_SESSION["lastnotifynum"]);
    $conn = $this->conn;
    $uid = $this->userid;
    $uname = $this->clean_input($_SESSION["uname"]);
    $followers = $this->followers;
    $fw = array_keys($this->followers);
    $fw = implode(",",$fw);
    $storiesfollowed = $this->storiesfollowed;
    $sfw = array_keys($storiesfollowed);
    $sfw = implode(",",$sfw);
    $lastnotes = $this->lastnotes;
    $sql = '';
    if(count($followers) > 0 && count($storiesfollowed) > 0){
    $sql = "select * from notification where id < '$lastid' and creatornotifyid ='$uid' and type in('a','d','g','e','h') or id < '$lastid' and  creatornotifyid ='oaumeetup' and  type in('f') union select * from notification where id < '$lastid' and  creatornotifyid in($fw) and type in ('b','c') or id < '$lastid' and  creatornotifyid in($sfw) and type in('b','c') order by id desc limit 10";
    }elseif(count($followers) > 0  && count($storiesfollowed) < 1) {
    $sql = "select * from notification where id < '$lastid' and creatornotifyid ='$uid' and type in('a','d','g','e','h') or id < '$lastid' and creatornotifyid ='oaumeetup' and  type in('f') union select * from notification where id < '$lastid' and creatornotifyid in($fw) and type in ('b','c') order by id desc limit 10";
    }elseif(count($followers) < 1  && count($storiesfollowed) > 0){
    $sql = "select * from notification where id < '$lastid' and creatornotifyid ='$uid' and type in('a','d','g','e','h') or id < '$lastid' and  creatornotifyid ='oaumeetup' and  type in('f') union select * from notification where id < '$lastid' and creatornotifyid in($sfw) and type in('b','c') order by id desc limit 10";
    }else{
    $sql = "select * from notification where id < '$lastid' and creatornotifyid ='$uid' and type in('a','d','g','e','h') or creatornotifyid ='oaumeetup' and  type in('f') order by id desc limit 10";
    }
   
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $n = 0;
    $num_rows = $result->num_rows;
    $data = "";
    while($row = $result->fetch_assoc()){
    ++$n;
    if($n == $num_rows){
    $_SESSION["lastnotifynum"] = $row["id"];
    }
    $id = $row["id"];
    $creatornotifyid = $row["creatornotifyid"];
    $recepientnotifyid = $row["recepientnotifyid"];
    $type = $row["type"];
    $landpagelink = $row["landpagelink"];
    if(!empty($landpagelink)){
    $landpagelink = json_decode($landpagelink,true);
    }else{
    $landpagelink = array();
    }
    $date = strftime("%a %b %d %Y @ %I:%M%p",$row['date']);
    //conditions to display notifications in the right way
    if($type == "a"){
    $data .="<li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
    <img src='bakground.jpeg'class='w3-bar-item'style='width:70px;height:70px;padding:5px;'>	
    <div class='w3-bar-item' style='width:70%;padding:0;margin:3px; margin-left:6px;'>
    <span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>Hello $uname welcome to oaumeetup :) we are happy to have you here please upload your profile picture on time so people can find you,click <a href='oaumeetupprofile.php'style='text-decoration:none;'><b class='w3-text-blue'>here</b></a> to get started</span>
    </div>
    </li>";
    }elseif($type =="f" && count($landpagelink) > 0 && $creatornotifyid == $uid){
    $cont = count($landpagelink);
    $pid = array_pop($landpagelink);
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .= "<a href='oaumeetupprofile.php?uid=$pid'style='text-decoration:none;'><li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
        <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
    <img src='chatplaceholder1.jpg' data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>	
    <div class='w3-bar-item' style='width:70%;padding:0;margin:3px;margin-left:6px;'>";
    if($cont > 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others just joined oaumeetup!:) be the first to connect</span>
    </div>
    </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname just joined oaumeetup! :) be the first to connect</span>
    </div>
    </li></a>";
    }
    }elseif($type == "b" && !empty($creatornotifyid)){
    $uname=$avatar=$wrds="";
    $details = $this->getPartnerDetails($creatornotifyid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='oaumeetupprofile.php?uid=$creatornotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
    <img src='chatplaceholder1.jpg' data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>	
    <div class='w3-bar-item' style='width:70%;padding:0;margin:3px; margin-left: 6px;'>
    <span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname just updated  profile picture</span>
    </div>
    </li>
    </a>";
    }elseif($type == "g" && count($landpagelink) > 0){
    $cont = count($landpagelink) -1;
    $pid = array_keys($landpagelink);
    $pid = str_replace("'","",array_pop($pid));
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='oaumeetupprofile.php?uid=$creatornotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
    <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>	
    <div class='w3-bar-item' style='width:70%;padding:0;margin:3px; margin-left: 6px;'>";
    if($cont >= 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others are following you</span>
    </div>
    </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname is following you</span>
    </div>
    </li></a>";
    }
    }elseif($type == "d" && $creatornotifyid == $uid && !empty($recepientnotifyid) && count($landpagelink) > 0){
    $cont = count($landpagelink) -1;
    $pid = array_pop($landpagelink);
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='oaumeetupstorycomments.php?storyid=$recepientnotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
     <!--<a href='oaumeetupprofile.php?uid=$pid'style='text-decoration:none;'>-->
     <img src='chatplaceholder1.jpg'data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>
     <!--</a>-->	
     <div class='w3-bar-item' style=width:70%;padding:0;margin:3px;margin-left:6px;'>";
    if($cont >= 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others commented on your story</span>
     </div>
     </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname commented on your story</span>
     </div>
     </li></a>";
    }
    }elseif($type == "h" && $creatornotifyid == $uid && !empty($recepientnotifyid) && count($landpagelink) > 0){
    $cont = count($landpagelink) -1;
    $pid = array_pop($landpagelink);
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='oaumeetupreadstory.php?storyid=$recepientnotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
     <!--<a href='oaumeetupprofile.php?uid=$pid'style='text-decoration:none;'>-->
     <img src='chatplaceholder1.jpg'data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>
     <!--</a>-->	
     <div class='w3-bar-item' style='width:70%;padding:0;margin:3px;margin-left:6px;'>";
    if($cont >= 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others viewed your story</span>
     </div>
     </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname viewed your story</span>
     </div>
     </li></a>";
    }
    }elseif($type == "e" && $creatornotifyid == $uid && !empty($recepientnotifyid) && count($landpagelink) > 0){
    $cont = count($landpagelink) -1;
    $pid = array_pop($landpagelink);
    $uname=$avatar="";
    $details = $this->getPartnerDetails($pid);
    if(is_array($details)){
    $uname = $details[0];
    $avatar = $details[1];
    }
    $data .="
    <a href='replycomment.php?commentid=$recepientnotifyid'style='text-decoration:none;'>
    <li class='w3-bar w3-ripple w3-display-container w3-border-bottom'>
    <span class='w3-text-grey w3-display-topright w3-small'>$date</span>
     <!--<a href='oaumeetupprofile.php?uid=$pid'style='text-decoration:none;'>-->
     <img src='chatplaceholder1.jpg'data-src='$avatar'class='w3-bar-item lazyload'style='width:70px;height:70px;padding:5px;'>
     <!--</a>-->	
     <div class='w3-bar-item' style='width:70%;padding:0;margin:3px;margin-left:6px;'>";
    if($cont >= 1){
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname and $cont others replied to your comment</span>
     </div>
     </li></a>";
    }else{
    $data .="<span class='w3-bold w3-block w3-text-grey'style='width:100%;font-size: 16px;word-wrap:break-word;'>$uname replied to your comment</span>
     </div>
     </li></a>";
    }
    }
    //conditions to display notifications in the right way
    }//while loop 
    if($num_rows != 10){
    unset($_SESSION["lastnotifynum"]);
    }
    return $data;
    /*num_rows > 0*/}else{
    return "nomorenotesc";
    }
    }
    //public function to get more notifications ends here

    //public function to update notescheck of user starts here
    public function updatenotes(){
    $conn = $this->conn;
    $uid = $this->userid;
    $date = time();
    $sql = "update oaumeetupusers set notescheckdate ='$date' where userid='$uid' limit 1";
    $conn->query($sql);
    }
    //public function to update notescheck of user starts here


}
?>