<?php
session_start();
require 'classmeetupvalidate.php';
/**
 * $_SESSION["rstoryid"] $_SESSION["rcommenterid"] $_SESSION["rcommentid"] $_SESSION["rnumreply"] $_SESSION["replyoldlasttimecheck"] $_SESSION["replynewlasttimecheck"]
 */
class  replystorycomment extends meetupvalidate
{ 
	private $userid;
	private $storyid;
	private $commentid;
	private $commenterid;
	
	function __construct()
	{
	$this->createConnection();
	if(isset($_SESSION["userid"])){
    $this->userid = $_SESSION["userid"];
	}
	}
	public function setstoryid($data){
    $this->storyid = $this->clean_input($data);
	}
  public function getstoryid(){
  return  $this->storyid;
  }
	public function setcommentid($data){
	$this->commentid = $this->clean_input($data);
	}
	public function setcommenterid($data){
	$this->commenterid = $this->clean_input($data);
	}

	//public function to get the comment starts here
    public function getthecomment(){
    $conn = $this->conn;
    $uid = $this->userid;
    $cmmntid = $this->commentid;
    $data = "";
    if(empty($cmmntid) || empty($uid)){
    echo "comment does exists";
    exit();
    }
    $sql = "select * from storycomment where  commentid='$cmmntid' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $id = $row["id"];
    $_SESSION["rstoryid"] = $storyid = $row["storyid"];
    $this->setstoryid($row["storyid"]);
    $storyname = $row["storyname"];
    $_SESSION["rcommenterid"] = $commenterid = $row["commenterid"];
    $this->setcommenterid($row["commenterid"]);
    $_SESSION["rcommentid"] = $commentid = $row["commentid"];
    $this->setcommentid($row["commentid"]);
    $comment = nl2br($row["comment"]);
    $date = strftime("%I:%M%p",$row["date"])." . ".strftime("%b %d %Y",$row["date"]);
    $numreply = $row["numreply"];
    if(empty($numreply)){
    $numreply = "0";
    }
    $_SESSION["rnumreply"] = $numreply;
    $numshare = $row["numshare"];
    $userdetail = $this->getUserDetail($commenterid);
    $username = $userdetail[0];
    $avatar = $userdetail[1];
    $gender = $userdetail[2];

    $data .="<li id=''class='w3-bar w3-display-container' style=''>
    <i class='fa fa-$gender w3-display-topright w3-margin w3-text-blue'></i>
    <a href='oaumeetupprofile.php?uid=$commenterid' style='text-decoration:none;'><img src='chatplaceholder1.jpg' data-src='$avatar'class='w3-circle w3-bar-item lazyload'style='margin-top:2px;width:50px;height:50px;padding:2px;'/></a>
    <div class='w3-bar-item'style='margin-top:4px;padding:0;'>
    <a href='oaumeetupprofile.php?uid=$commenterid' style='text-decoration:none;'><b class=''style='padding:0px;margin-left:5px;margin-top:px;'>$username</b></a><br>
    <b class='w3-text-grey'style='padding:0;margin-left:5px;'><i class='fa fa-$gender'></i> $gender</b>
    </div>
    <!--for comment text ends here-->
    <div class='w3-bar-item' style='width:100%;padding:0;'>
    <span class='w3-text-grey'>Commented on <a href='oaumeetupstorycomments.php?storyid=$storyid'style='text-decoration:none;'><b class='w3-text-blue'>$storyname</b></a></span><br>
    <span class='w3-text-black w3-large'style='width:100%;word-wrap:break-word;'>
    $comment
    </span><br>
    <span class='w3-text-grey'>$date</span>
    </div>
    <!--for comment text ends here-->
    <!--for number of comment starts here-->
    <div class='w3-bar-item'style='border-bottom:1px solid #ddd; width:100%;border-top:1px solid #ddd; width:100%;'>
    <span class='w3-text-grey'><b id='numreplytxt'class='w3-text-black w3-animate-bottom'>$numreply</b> replies</span>	
    </div>
    <!--for number of comment ends here-->
    <!--for comment and share button starts here-->
    <div class='w3-bar-item w3-hide'style='border-bottom:1px solid #ddd;width:100%;padding:0;'>
    <button id='replybtn' class='w3-button w3-hover-white w3-hover-text-green w3-white w3-text-blue w3-center w3-large'style='width:45%;outline:none;'><i class='fa fa-comment-o'></i></button>
    <button id='sharebtn' class='w3-button w3-hover-white w3-hover-text-green w3-white w3-text-blue w3-center w3-large' style='width:45%;outline:none;'><i class='fa fa-share-alt'></i></button>
    </div>
    <!--for comment and share button ends here-->
    </li>";
    return $data;
    }else{
    echo "comment does exists";
    exit();
    }
    }
   //public function to get the comment ends  here

    //public function to get comment replies starts here
    public function getcommentreplies(){
    $conn = $this->conn;
    $storyid = $this->storyid;
    $commentid = $this->commentid;
    $commenterid = $this->commenterid;
    $uid = $this->userid;
    $data = "";
    if(empty($storyid) || empty($commentid) || empty($commenterid)){
    return "<div id='noreplyctn' class='w3-container w3-text-blue w3-center w3-margin'>
      <i class='fa fa-meh-o w3-xlarge'></i> Could not get replies missing values to continue
      </div>";
    }
    $sql = "select * from replystorycomment where commentid = '$commentid' order by id desc  limit 10";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $data .="<button id='showmorenewreplycmntbtn' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
      <span class='' id='showmorenewreplycmntshow'><i class='fa fa-comment-o'></i> Click to load new replies</span>
      <span class='w3-hide' id='showmorenewreplycmntprg'><i class='fa fa-spinner w3-spin'></i> getting new replies...</span>
      </button>";
    $n = 0;
    $num_rows = $result->num_rows;
    while($row = $result->fetch_assoc()){
    ++$n;
    if($n == $num_rows && $num_rows == 10){
    $_SESSION["replyoldlasttimecheck"] = $row["id"];
    }
    if($n == 1){
    $_SESSION["replynewlasttimecheck"] = $row["id"];
    }
    $id = $row["id"];
    $storyid = $row["storyid"];
    $replyercommentid = $row["replyercommentid"];
    $commenterid = $row["commenterid"];
    $commentid = $row["commentid"];
    $replycommentid = $row["replycommentid"];
    $replycomment = $row["replycomment"];
    $date = strftime("%b %d %Y @ %I:%M%p",$row["date"]);
    $username=$avatar=$gender="";
    $userdetail = $this->getUserDetail($replyercommentid);
    if(is_array($userdetail)){
    $username = $userdetail[0];
    $avatar = $userdetail[1];
    $gender = $userdetail[2];
    }
    if($uid == $replyercommentid){
    $username = "You";
    }
    
    $data .="<li id='replycomment$replycommentid' class='$id w3-bar w3-ripple w3-display-container'style='padding:10px;border-bottom:1px solid #ddd;'>
    <span class='w3-display-topright w3-small w3-text-blue'style='margin: 8px;'><i class='fa fa-$gender'> $gender</i></span>
    <a href='oaumeetupprofile.php?uid=$commenterid'style='text-decoration:none;'>
    <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-circle lazyload w3-bar-item'style='margin-top:8px;width:40px;height:40px;padding:2px;'/></a>
   <div class='w3-bar-item' style='width: 70%; padding: 0;margin:6px; margin-left:6px;'>
   <a href='oaumeetupprofile.php?uid=$commenterid' class='w3-text-black'style='font-size:11px;text-decoration:none;'><b>$username</b></a> <span class='w3-small w3-text-grey'>$date</span><br>
   <span class='w3-text-black'style='width:100%;word-wrap: break-word;'>
   $replycomment
   </span><br>
   </div>
   </li>";

    }//while loop
    if($num_rows == 10 && isset($_SESSION["replyoldlasttimecheck"])){
    $data .="<button id='showmoreoldreplycmntbtn' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
    <span class='' id='showmoreoldreplycmntshow'><i class='fa fa-comment-o'></i> Click to load old replies</span>
    <span class='w3-hide' id='showmoreoldreplycmntprg'><i class='fa fa-spinner w3-spin'></i> getting old replies...</span>
    </button>";
    }else{
    unset($_SESSION["replyoldlasttimecheck"]);
    }
    return $data;
    /*if result num rows*/}else{
    return "<div id='noreplyctn' class='w3-container w3-text-blue w3-center w3-margin'>
      <i class='fa fa-comment-o w3-xlarge'></i> Be the first to reply 
      <i class='fa fa-smile-o w3-xlarge'></i>
      </div>";
    }

    }
    //public function to get comment replies ends here

    //public function to get more old replies starts here
    public function getmoreoldreplies(){
    if(!isset($_SESSION["replyoldlasttimecheck"]) || empty($_SESSION["replyoldlasttimecheck"]) || !isset($_SESSION["rcommentid"]) || empty($_SESSION["rcommentid"])){
    return "noreplies a";
    }
    $lasttimecheck = $this->clean_input($_SESSION["replyoldlasttimecheck"]);
    $commentid = $this->clean_input($_SESSION["rcommentid"]);
    if(empty($lasttimecheck) || empty($commentid)){
    unset($_SESSION["replyoldlasttimecheck"]);
    unset($_SESSION["rcommentid"]);
    return "noreplies b";
    }
    $conn = $this->conn;
    $uid = $this->userid;
    $lasttimecheck = $_SESSION["replyoldlasttimecheck"];
    $sql = "select * from replystorycomment where id < '$lasttimecheck' and commentid = '$commentid' order by id desc limit 10";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $n = 0;
    $data = "";
    $num_rows = $result->num_rows;
    while($row = $result->fetch_assoc()){
    ++$n;
    if($n == $num_rows){
    $_SESSION["replyoldlasttimecheck"] = $row["id"];
    }
    $id = $row["id"];
    $storyid = $row["storyid"];
    $replyercommentid = $row["replyercommentid"];
    $commenterid = $row["commenterid"];
    $commentid = $row["commentid"];
    $replycommentid = $row["replycommentid"];
    $replycomment = $row["replycomment"];
    $date = strftime("%b %d %Y @ %I:%M%p",$row["date"]);
    $username=$avatar=$gender="";
    $userdetail = $this->getUserDetail($replyercommentid);
    if(is_array($userdetail)){
    $username = $userdetail[0];
    $avatar = $userdetail[1];
    $gender = $userdetail[2];
    }
    if($uid == $replyercommentid){
    $username = "You";
    }
    $data .="<li id='replycomment$replycommentid' class='$id w3-bar w3-ripple w3-display-container'style='padding:10px;border-bottom:1px solid #ddd;'>
    <span class='w3-display-topright w3-small w3-text-blue'style='margin: 8px;'><i class='fa fa-$gender'> $gender</i></span>
    <a href='oaumeetupprofile.php?uid=$commenterid'style='text-decoration:none;'>
    <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-circle lazyload w3-bar-item'style='margin-top:8px;width:40px;height:40px;padding:2px;'/></a>
   <div class='w3-bar-item' style='width: 70%; padding: 0;margin:6px; margin-left:6px;'>
   <a href='oaumeetupprofile.php?uid=$commenterid' class='w3-text-black'style='font-size:11px;text-decoration:none;'><b>$username</b></a> <span class='w3-small w3-text-grey'>$date</span><br>
   <span class='w3-text-black'style='width:100%;word-wrap: break-word;'>
   $replycomment
   </span><br>
   </div>
   </li>";
    }//while loop
    if($num_rows != 10){
    unset($_SESSION["replyoldlasttimecheck"]);
    }
    return $data;
    }else{
    return "noreplies c";
    }
    }
    //public function to get more old replies ends here

    //public function to get new replies starts here
    public function getmorenewreplies(){
    if(!isset($_SESSION["replynewlasttimecheck"]) || empty($_SESSION["replynewlasttimecheck"]) || !isset($_SESSION["rcommentid"]) || empty($_SESSION["rcommentid"])){
    return "noreplies a";
    }
    $lasttimecheck = $this->clean_input($_SESSION["replynewlasttimecheck"]);
    $commentid = $this->clean_input($_SESSION["rcommentid"]);
    if(empty($lasttimecheck) || empty($commentid)){
    unset($_SESSION["replynewlasttimecheck"]);
    unset($_SESSION["rcommentid"]);
    return "noreplies b";
    }
    $conn = $this->conn;
    $uid = $this->userid;
    $lasttimecheck = $_SESSION["replynewlasttimecheck"];
    $sql = "select * from replystorycomment where id > '$lasttimecheck' and commentid = '$commentid' order by id desc limit 10";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    $n = 0;
    $data = "";
    $num_rows = $result->num_rows;
    while($row = $result->fetch_assoc()){
    ++$n;
    if($n == 1){
    $_SESSION["replynewlasttimecheck"] = $row["id"];
    }
    $id = $row["id"];
    $storyid = $row["storyid"];
    $replyercommentid = $row["replyercommentid"];
    $commenterid = $row["commenterid"];
    $commentid = $row["commentid"];
    $replycommentid = $row["replycommentid"];
    $replycomment = $row["replycomment"];
    $date = strftime("%b %d %Y @ %I:%M%p",$row["date"]);
    $username=$avatar=$gender="";
    $userdetail = $this->getUserDetail($replyercommentid);
    if(is_array($userdetail)){
    $username = $userdetail[0];
    $avatar = $userdetail[1];
    $gender = $userdetail[2];
    }
    if($uid == $replyercommentid){
    $username = "You";
    }
    $data .="<li id='replycomment$replycommentid' class='$id w3-bar w3-ripple w3-display-container'style='padding:10px;border-bottom:1px solid #ddd;'>
    <span class='w3-display-topright w3-small w3-text-blue'style='margin: 8px;'><i class='fa fa-$gender'> $gender</i></span>
    <a href='oaumeetupprofile.php?uid=$commenterid'style='text-decoration:none;'>
    <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-circle lazyload w3-bar-item'style='margin-top:8px;width:40px;height:40px;padding:2px;'/></a>
   <div class='w3-bar-item' style='width: 70%; padding: 0;margin:6px; margin-left:6px;'>
   <a href='oaumeetupprofile.php?uid=$commenterid' class='w3-text-black'style='font-size:11px;text-decoration:none;'><b>$username</b></a> <span class='w3-small w3-text-grey'>$date</span><br>
   <span class='w3-text-black'style='width:100%;word-wrap: break-word;'>
   $replycomment
   </span><br>
   </div>
   </li>";
    }//while loop
    return $data."_[]_".$num_rows;
    }else{
    return "noreplies c";
    }
    }
    //public function to get new replies ends here

   

   //public function to insert reply comment starts here
   public function insertreply($reply){
   if(!isset($_SESSION["rstoryid"]) || !isset($_SESSION["rcommenterid"]) || !isset($_SESSION["rcommentid"]) || !isset($_SESSION["rnumreply"]) || !is_numeric($_SESSION["rnumreply"])){
   return "failed required arguments are not set";
   }elseif(empty($_SESSION["rstoryid"]) || empty($_SESSION["rcommenterid"]) || empty($_SESSION["rcommentid"])){
   	return "failed missing values to continue";
   }
   $conn = $this->conn;
   $reply = $this->clean_input($reply);
   $uid = $this->userid;
   $storyid = $_SESSION["rstoryid"];
   $commenterid = $_SESSION["rcommenterid"];
   $commentid = $_SESSION["rcommentid"];
   $numreply = $_SESSION["rnumreply"];
   ++$numreply; 
   if(empty($reply)){
   return "failed could not post reply,reply is empty or contains characters that are not accepted";
   }elseif(empty($uid)){
   return "failed could not post reply,reply is empty or contains characters that are not accepted";
   }
   $date = time();
   $replycommentid = md5(rand(0,100000).rand(0,1000000).rand(0,100000));
    //to handle the notification aspect starts here
      $sql25 = "select landpagelink  from notification where  creatornotifyid = '$commenterid' and recepientnotifyid ='$commentid' and type ='e' limit 1";
      $result25 = $conn->query($sql25);
      if($result25->num_rows == 1){
      $row25 = $result25->fetch_assoc();
      $landpagelink = $row25["landpagelink"];

      if(empty($landpagelink)){
      $landpagelink = array();
      }else{
      $landpagelink = json_decode($landpagelink,true);
      }
      if(!in_array($uid,$landpagelink)){
      array_push($landpagelink,$uid);
      $landpagelink = $conn->real_escape_string(json_encode($landpagelink));
      $sql25 = "update notification set landpagelink = '$landpagelink',date='$date' where creatornotifyid = '$commenterid' and recepientnotifyid ='$commentid' and type ='e' limit 1";
      }else{
      $sql25 = "";
      }

      }else{
      $landpagelink = array();
      if(!in_array($uid,$landpagelink)){
      array_push($landpagelink,$uid);
      $landpagelink = $conn->real_escape_string(json_encode($landpagelink));
      $sql25 = "insert into notification(creatornotifyid,recepientnotifyid,type,landpagelink,date) values('$commenterid','$commentid','e','$landpagelink','$date')";
      }else{
      $sql25 = "";
      }
      }
      //to handle the notification aspect ends here

   $sql = "insert into replystorycomment(storyid,replyercommentid,commenterid,commentid,replycommentid,replycomment,date) values('$storyid','$uid','$commenterid','$commentid','$replycommentid','$reply','$date')";
   $sql2 = "update storycomment set numreply ='$numreply' where commentid='$commentid' limit 1";
   if($conn->query($sql) == "true"){
   $_SESSION["replynewlasttimecheck"] = $conn->insert_id;
   $conn->query($sql2);
   if(!empty($sql25)){
   $conn->query($sql25);
   }
   $userdetail = $this->getUserDetail($uid);
   if(is_array($userdetail)){
   $username = $userdetail[0];
   $avatar = $userdetail[1];
   $gender = $userdetail[2];
   }
   $_SESSION["rnumreply"] = $numreply;


   $date = strftime("%b %d %Y @ %I:%M%p",$date);
   return "<li id='replycomment$replycommentid' class='w3-bar w3-ripple w3-display-container'style='padding:10px;border-bottom:1px solid #ddd;'>
    <span class='w3-display-topright w3-small w3-text-blue'style='margin: 8px;'><i class='fa fa-$gender'> $gender</i></span>
    <a href='oaumeetupprofile.php?uid=$commenterid'style='text-decoration:none;'>
    <img src='chatplaceholder1.jpg' data-src='$avatar' class='w3-circle lazyload w3-bar-item'style='margin-top:8px;width:40px;height:40px;padding:2px;'/></a>
   <div class='w3-bar-item' style='width: 70%; padding: 0;margin:6px; margin-left:6px;'>
   <a href='oaumeetupprofile.php?uid=$commenterid' class='w3-text-black'style='font-size:11px;text-decoration:none;'><b>You</b></a> <span class='w3-small w3-text-grey'>$date</span><br>
   <span class='w3-text-black'style='width:100%;word-wrap: break-word;'>
   $reply
   </span><br>
   </div>
   </li>"."_[]_".$numreply."_[]_"."replycomment$replycommentid";
   }else{
   return "failed could not post reply at this time please try again".$conn->error;
   }
   }
   //public function to insert reply comment ends here

   //function to get username and profile pics starts here
   public function getUserDetail($uid)
   {
   $conn = $this->conn;
   $uid = $this->clean_input($uid);
   $ar = array();
   $sql = "select username,gender,avatar from oaumeetupusers where userid ='$uid' limit 1";
   $result = $conn->query($sql);
   if($result->num_rows == 1){
   if ($row = $result->fetch_assoc()){
   $username = $row["username"];
   $gender = $row["gender"];
   $avatar = $row["avatar"];
   if(empty($username) || $username == ""){
   $username = "Unknown";
   }
   if((empty($avatar) || $avatar == "") && $gender == "female"){
   $avatar = "femaledefault.jpeg";
   }else if((empty($avatar) || $avatar == "") && $gender == "male"){
   $avatar = "maledefault.png";
   }else if(!file_exists($avatar)){
   $avatar = "maledefault.png";
   }
   if(empty($gender) || $gender == ""){
   $gender = "Unknown";
   }
   $ar = array($username,$avatar,$gender);
   }//closing braces for if result->fetch_assoc()
   return $ar;
   }else{
   return array("Unknown","maledefault.png","Unknown");
   }
   }
   //function to get username and profile pics ends here

}
?>