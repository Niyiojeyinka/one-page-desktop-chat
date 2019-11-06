<?php
session_start();
require 'classmeetupvalidate.php';
/**
 * class to handle database function for story comment and views starts here
 #sessions to clean later  $_SESSION["newlasttimecheck"] $_SESSION["oldlasttimecheck"] $_SESSION["moreviewerlist"] $_SESSION["commentwriterid"] $_SESSION["cmntstorytitle"]
 */
class storycomment extends meetupvalidate
{  
	private $storyid;
	private $storytitle;
  private $userid;
  private $changeid;//the changeid is meant for scrolling
  private $commentnum;
  private $viewerlist;
	
	function __construct()
	{
	$this->createConnection();
	if(isset($_SESSION["userid"])){
  $this->setUserid($_SESSION["userid"]);
	}
	}
	 public function setStoryId($data)
	{
	  $this->storyid = $this->clean_input($data);
	}
    
    public function getStoryId()
    {
     return $this->storyid; 
    }

    public function setStoryTitle($data)
	{
	  $this->storytitle = $this->clean_input($data);
	}
    
    public function getStoryTitle()
    {
     return $this->storytitle; 
    }
    public function setChangeId($data)
  {
    $this->changeid = $this->clean_input($data);
  }
    
   public function getChangeId()
    {
     return $this->changeid; 
    }
   public function setUserid($data)
  {
    $this->userid = $this->clean_input($data);
  }
    
  public function getUserid()
  {
   return $this->userid; 
  }
   public function setCommntNum($data)
  {
    $this->commentnum = $this->clean_input($data);
  }   
  public function getCommntNum()
  {
     return $this->commentnum; 
    }
   public function setViewer($data)
  {
    $this->viewerlist = $data;
  }   
  public function getViewer()
  {
     return $this->viewerlist; 
  }
  
	public function getStory()
	{
	     $conn = $this->conn;
	     $data=$writerid=$storyid="";
       $storyid = $this->getStoryId();
       $sql = "select * from stories where storyid ='$storyid' limit 1";
       $result = $conn->query($sql);
       if($result->num_rows == 1){
       if ($row = $result->fetch_assoc()){
       $_SESSION["commentwriterid"] = $writerid = $row["writerid"];
       $storyid = $row["storyid"];
       $_SESSION["cmntstorytitle"] = $row["storytitle"];
       $_SESSION["cmntstorytitle"];
       $storytitle = $this->setStoryTitle($row["storytitle"]);
       $storycontent = $row["storycontent"];
       $numviews = $row["numviews"];
       $viewerslist = $row["viewerslist"];
       $numcomment = $row["numcomment"];
       $commenterlist = $row["commenterslist"];
       $anonymous = $row["anonymous"];
       $expired = $row["expired"];
       $date = $row["date"];
       $storycontent = str_replace("_^_","\ud",$storycontent);
       $stry = json_decode($storycontent,true);
       $slidenum = count($stry);
       $stry = str_replace(array("_-_","_+_","_#_","#389"),array("\n","\r"," ","\\"),$stry);
       if (empty($viewerslist) || $viewerslist == ""){
       $viewerslist = array();
       }else{
       $viewerslist = json_decode($viewerslist,true); $this->setViewer($viewerslist);}
       //begining of for each loop
       foreach ($stry as $color => $story) {
       $color = $this->clean_input($color);
       $story =strip_tags($story);
       $story = nl2br($story);
       $data .="<div class='$color w3-animate-left storycntent w3-hide'style='width:100%;margin-right:auto;margin-left:auto;font-size:22px;height:50vh;word-wrap:break-word;padding:15px;'>
       <p class=''style='margin-top: 5px;height:100%;overflow: auto;vertical-align:middle;'>$story</p>
        </div>";
       }
       //end of for each loop
       $data .="
      <button class='w3-round-large w3-display-topright w3-margin w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-bottom:10px;padding: 7px;margin-left: 30vw;'><i class='fa fa-sticky-note w3-large'></i> <span id='slideindex' class='w3-medium'>1/$slidenum</span> </button>
      <!--bar for sharing and the rest starts here-->
      <div class='w3-bar'style='margin-top:2px;margin-right:auto;margin-left:auto;width:95%;'>
      <div class='w3-bar-item  w3-center w3-border-top w3-border-bottom'style='width:100%;'>
      <button id='showcmnt'class='w3-text-blue w3-center w3-small w3-button w3-round-large w3-hover-blue w3-hover-text-white 'style='font-size:15px;width:40%;'><i class='fa fa-comments'></i> <span id='numcmmnttxt'> $numcomment comments</span>
      </button>
      <button id='showview'class='w3-text-blue w3-center w3-button w3-small w3-round-large w3-hover-blue w3-hover-text-white'style='font-size:15px;width:40%;'><i class='fa fa-eye'></i><span> $numviews views</span>
      </button>
      </div>
      <div class='w3-bar-item w3-border-bottom w3-hide'style='width:100%;padding:0;'>
      <button  onclick='sharE(\"$storyid\")' class='w3-button w3-bar-item  w3-small  w3-round-large w3-text-blue w3-hover-blue w3-hover-text-white'style='margin:5px;width:45%;'>
      <i class='fa fa-share-alt w3-xlarge'></i>
      </button>
      <button id='scbtn' class='w3-button w3-bar-item w3-small  w3-round-large w3-text-blue w3-hover-blue w3-hover-text-white'style='margin:5px;width:45%;'>
      <i class='fa fa-comment-o w3-xlarge'></i>
      </button>
      </div>
      </div>
      <!--bar for sharing and the rest ends here-->
      ";
      return $data;
       }//ending braces for if result== fetch_assoc()
       return $data;
       }else{ return "<div class='w3-center w3-text-red w3-large w3-animate-left'style='margin-top:40%;'>
       	<span><i class='fa fa-exclamation-triangle w3-xxlarge'></i> Story not found</span>
       	</div>";}
    }
      
      public function getComment()
      {
      $data = "";
      $conn = $this->conn;
      $uid = $this->getUserid();
      $storyid = $this->getStoryId();
      //$a = array();
      $n = 0;
      $sql = "select * from storycomment where storyid='$storyid' order by id desc limit 10";
      $result = $conn->query($sql);
      if($result->num_rows > 0){
      $num_rows = $result->num_rows;
      $data = "<button id='showmorenewcmntbtn' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
      <span class='' id='showmorenewcmntshow'><i class='fa fa-comments'></i> Click to load new comments</span>
      <span class='w3-hide' id='showmorenewcmntprg'><i class='fa fa-spinner w3-spin'></i> getting new comment...</span>
      </button>";
      while($row = $result->fetch_assoc()){
      ++$n;
      //code to set the last time check used when getting newer and older comments
      if($n == $num_rows && $num_rows == 10){
      $_SESSION["oldlasttimecheck"] = $row["id"];
      }
      if($n == 1){
      $_SESSION["newlasttimecheck"] = $row["id"];
      }
      $id = $row["id"];
      $storyid = $row["storyid"];
      $commenterid = $row["commenterid"];
      $commentid = $row["commentid"];
      $comment = nl2br($row["comment"]);
      $date = strftime("%b %d %Y @ %I:%M%p",$row["date"]);
      $numreply = $row["numreply"];
      $numshare = $row["numshare"];
      $userdetail = $this->getUserDetail($commenterid);
      $username = $userdetail[0];
      $avatar = $userdetail[1];
      $gender = $userdetail[2];
      if($uid == $commenterid){$username = "You";}
      if($gender == "female"){
      $gender = "<i class='fa fa-female'></i> Female";
      }elseif($gender == "male"){
      $gender = "<i class='fa fa-male'></i> Male";
      }
      $data .="<li id='comment$commentid' class='$id w3-bar w3-ripple w3-display-container w3-border-0'style='padding: 10px;'>
      <span class='w3-display-topright w3-small w3-text-blue'style='margin: 8px;'><i>$gender</i></span>
      <a href='oaumeetupprofile.php?uid=$commenterid'style='text-decoration:none;'>
      <img src='chatplaceholder1.jpg' data-src='$avatar'class='w3-circle lazyload w3-bar-item'style='margin-top:8px;width:70px;height:70px;padding:2px;'/></a>
      <div class='w3-bar-item w3-border-bottom w3-border-grey' style='width: 70%; padding: 0;margin:6px; margin-left:6px;'>
      <a href='oaumeetupprofile.php?uid=$commenterid'style='text-decoration:none;font-size:16px;'class='w3-text-black'><b>$username</b></a> <span class='w3-small w3-text-grey'>$date</span><br>
      <span class='w3-text-black'style='width:100%;word-wrap: break-word;'>$comment
      </span><br>
      <button class='w3-button w3-round-large w3-hide w3-text-blue w3-hover-blue w3-hover-text-white'style='margin:5px;'>
      <i class='fa fa-share-alt w3-large'></i> <span>$numshare</span>
     </button>
     <a href='replycomment.php?commentid=$commentid' style='text-decoration:none;'><button class='w3-button w3-round-large w3-text-blue w3-hover-blue w3-hover-text-white'style='margin:5px;''>
     <i class='fa fa-comment-o w3-large'></i> <span>$numreply</span>
     </button></a>
     </div>
     </li>";
      }
      if($num_rows == 10){
      $data.="<button id='showmoreoldcmntbtn' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
      <span class='' id='showmoreoldcmntshow'><i class='fa fa-comments'></i> Click to load more old comments</span>
      <span class='w3-hide' id='showmoreoldcmntprg'><i class='fa fa-spinner w3-spin'></i> getting more old comment...</span>
      </button>";
      }
      /* num rowss*/}else{
      $data = "<div id='nocmmntctn' class='w3-container w3-text-blue w3-center w3-margin'>
      <i class='fa fa-comments w3-xlarge'></i> Be the first to comment
      </div>";
      }
      return $data;
      }
      //function to get comment ends here

      //public function to get more comments starts here
      public function getmoreoldcomment(){
      if(!isset($_SESSION["oldlasttimecheck"]) || !is_numeric($_SESSION["oldlasttimecheck"])){
      return "nocommenta";
      }
      $data = "";
      $conn = $this->conn;
      $uid = $this->getUserid();
      $storyid = $this->getStoryId();
      $scrollid = "";
      //$a = array();
      $n = 0;
      $lasttimecheck = $this->clean_input($_SESSION["oldlasttimecheck"]);
      if(empty($lasttimecheck)){
      unset($_SESSION["oldlasttimecheck"]);
      return "nocommentb";
      }
      $sql = "select * from storycomment where id < '$lasttimecheck'  and storyid ='$storyid' order by date desc limit 10";
      $result = $conn->query($sql);
      if($result->num_rows > 0){
      $num_rows = $result->num_rows;
      while($row = $result->fetch_assoc()){
      ++$n;
      if($n == $num_rows){
      $_SESSION["oldlasttimecheck"] = $row["id"];
      }
      if(empty($scrollid)){
      $scrollid = $row["commentid"];
      }
      $id = $row["id"];
      $storyid = $row["storyid"];
      $commenterid = $row["commenterid"];
      $commentid = $row["commentid"];
      $comment = nl2br($row["comment"]);
      $date = strftime("%b %d %Y @ %I:%M%p",$row["date"]);
      $numreply = $row["numreply"];
      $numshare = $row["numshare"];
      $userdetail = $this->getUserDetail($commenterid);
      $username = $userdetail[0];
      $avatar = $userdetail[1];
      $gender = $userdetail[2];
      if($uid == $commenterid){$username = "You";}
      if($gender == "female"){
      $gender = "<i class='fa fa-female'></i> Female";
      }elseif($gender == "male"){
      $gender = "<i class='fa fa-male'></i> Male";
      }
      $data .="<li id='comment$commentid' class='$id w3-bar w3-ripple w3-display-container w3-border-0'style='padding: 10px;'>
      <span class='w3-display-topright w3-small w3-text-blue'style='margin: 8px;'><i>$gender</i></span>
      <a href='oaumeetupprofile.php?uid=$commenterid'style='text-decoration:none;'>
      <img src='chatplaceholder1.jpg'data-src='$avatar' class='w3-circle lazyload w3-bar-item'style='margin-top:8px;width:70px;height:70px;padding:2px;'/></a>
      <div class='w3-bar-item w3-border-bottom w3-border-grey' style='width: 70%; padding: 0;margin:6px; margin-left:6px;'>
      <a href='oaumeetupprofile.php?uid=$commenterid' class='w3-text-black'style='font-size:16px;text-decoration:none;'><b>$username</b></a> <span class='w3-small w3-text-grey'>$date</span><br>
      <span class='w3-text-black'style='width:100%;word-wrap: break-word;'>$comment
      </span><br>
      <button class='w3-button w3-round-large w3-hide w3-text-blue w3-hover-blue w3-hover-text-white'style='margin:5px;'>
      <i class='fa fa-share-alt w3-large'></i> <span>$numshare</span>
     </button>
     <a href='replycomment.php?commentid=$commentid' style='text-decoration:none;'><button class='w3-button w3-round-large w3-text-blue w3-hover-blue w3-hover-text-white'style='margin:5px;''>
     <i class='fa fa-comment-o w3-large'></i> <span>$numreply</span>
     </button></a> 
     </div>
     </li>";
      }
      if($num_rows != 10){
      unset($_SESSION["oldlasttimecheck"]);
      }
      //return $data."_*>._".$scrollid; 
      return $data;
      }else{
      return "nocommentc";
      }
      }
     //public function to get more old comments ends here

      //public function get more new comments starts here
      public function getmorenewcomment(){
      if(!isset($_SESSION["newlasttimecheck"]) || !is_numeric($_SESSION["newlasttimecheck"])){
      return "nocommenta";
      }
      $data = "";
      $conn = $this->conn;
      $uid = $this->getUserid();
      $storyid = $this->getStoryId();
      $scrollid = "";
      //$a = array();
      $n = 0;
      $lasttimecheck = $this->clean_input($_SESSION["newlasttimecheck"]);
      if(empty($lasttimecheck)){
      unset($_SESSION["newlasttimecheck"]);
      return "nocommentb";
      }
      //echo $_SESSION["newlasttimecheck"]."<br>";
      $sql = "select * from storycomment where id > '$lasttimecheck'  and storyid ='$storyid' order by date desc limit 10";
      $result = $conn->query($sql);
      if($result->num_rows > 0){
      $num_rows = $result->num_rows;
      while($row = $result->fetch_assoc()){
      ++$n;
      if($n == 1){
      $_SESSION["newlasttimecheck"] = $row["id"];
      }
      if(empty($scrollid)){
      $scrollid = $row["commentid"];
      }
      $id = $row["id"];
      $storyid = $row["storyid"];
      $commenterid = $row["commenterid"];
      $commentid = $row["commentid"];
      $comment = nl2br($row["comment"]);
      $date = strftime("%b %d %Y @ %I:%M%p",$row["date"]);
      $numreply = $row["numreply"];
      $numshare = $row["numshare"];
      $userdetail = $this->getUserDetail($commenterid);
      $username = $userdetail[0];
      $avatar = $userdetail[1];
      $gender = $userdetail[2];
      if($uid == $commenterid){$username = "You";}
      if($gender == "female"){
      $gender = "<i class='fa fa-female'></i> Female";
      }elseif($gender == "male"){
      $gender = "<i class='fa fa-male'></i> Male";
      }
      $data .="<li id='comment$commentid' class='$id w3-bar w3-ripple w3-display-container w3-border-0'style='padding: 10px;'>
      <span class='w3-display-topright w3-small w3-text-blue'style='margin: 8px;'><i>$gender</i></span>
      <a href='oaumeetupprofile.php?uid=$commenterid'style='text-decoration:none;'>
      <img src='chatplaceholder1.jpg' data-src='$avatar'class='w3-circle lazyload w3-bar-item'style='margin-top:8px;width:70px;height:70px;padding:2px;'/></a>
      <div class='w3-bar-item w3-border-bottom w3-border-grey' style='width: 70%; padding: 0;margin:6px; margin-left:6px;'>
      <a href='oaumeetupprofile.php?uid=$commenterid' class='w3-text-black'style='font-size:16px;text-decoration:none;'><b>$username</b></a> <span class='w3-small w3-text-grey'>$date</span><br>
      <span class='w3-text-black'style='width:100%;word-wrap: break-word;'>$comment
      </span><br>
      <button class='w3-button w3-round-large w3-hide w3-text-blue w3-hover-blue w3-hover-text-white'style='margin:5px;'>
      <i class='fa fa-share-alt w3-large'></i> <span>$numshare</span>
     </button>
     <a href='replycomment.php?commentid=$commentid' style='text-decoration:none;'><button class='w3-button w3-round-large w3-text-blue w3-hover-blue w3-hover-text-white'style='margin:5px;''>
     <i class='fa fa-comment-o w3-large'></i> <span>$numreply</span>
     </button></a> 
     </div>
     </li>";
      }
      //return $data."_*>._".$scrollid; 
      return $data;
      }else{
      return "nocommentc";
      }
      }
      //public function to get more new comments ends here

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
       
       if (empty($gender) || $gender == "") {
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

      //function to insert comment starts here
      public function insertComment($comment){
      $storyname = $_SESSION["cmntstorytitle"];
      $writerid = $this->clean_input($_SESSION["commentwriterid"]);
      $conn = $this->conn;
      $comment = $this->clean_input($comment);
      $uid = $this->getUserid();
      $storyid = $this->getStoryId();
      $numcomment = $this->getNumCmnt();
      $commentid = md5(rand(0,100000).rand(0,1000000).rand(0,100000));
      $date = time();
      ++$numcomment;
      $sql = "insert into storycomment (storyid,storyname,commenterid,commentid,comment,date) values('$storyid','$storyname','$uid','$commentid','$comment','$date')";
      $sql2 = "update stories set numcomment = '$numcomment' where storyid = '$storyid' limit 1";
      if (empty($comment) || $comment = ""){
      return "failed s";
      }

      //to handle the notification aspect starts here
      $sql25 = "select landpagelink  from notification where  creatornotifyid = '$writerid' and recepientnotifyid ='$storyid' and type ='d' limit 1";
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
      $sql25 = "update notification set landpagelink = '$landpagelink',date='$date' where creatornotifyid = '$writerid' and recepientnotifyid ='$storyid' and type ='d' limit 1";
      }else{
      $sql25 = "";
      }

      }else{
      $landpagelink = array();
      if(!in_array($uid,$landpagelink)){
      array_push($landpagelink,$uid);
      $landpagelink = $conn->real_escape_string(json_encode($landpagelink));
      $sql25 = "insert into notification(creatornotifyid,recepientnotifyid,type,landpagelink,date) values('$writerid','$storyid','d','$landpagelink','$date')";
      }else{
      $sql25 = "";
      }
      }
      //to handle the notification aspect ends here

      if ($conn->query($sql) == "true"){
      $this->setChangeId($commentid);
      if(!empty($sql25)){
      $conn->query($sql25);
      }
      $conn->query($sql2);
      $this->setCommntNum($numcomment);
      return $this->getComment();
      }else{
      return "failed".$conn->error;
      }
      }

      //function to get latest num comment
      public function getNumCmnt(){
      $conn = $this->conn;
      $storyid = $this->getStoryId();
      $sql = "select numcomment from stories where storyid = '$storyid' limit 1";
      $result = $conn->query($sql);
      if($result->num_rows == 1){
      if($row = $result->fetch_assoc()){
      $numcomment = $row["numcomment"];
      if($numcomment == "" || empty($numcomment)){
      $numcomment = 0;
      }
      return $numcomment;
      }
      }else{
      return 0;
      }
      }

      //function to get details of viewers starts here 
      public function getViewers()
      {
      $conn = $this->conn;
      $uid = $this->userid;
      $dat = "";
      $a = $this->getViewer();
      $a = array_reverse($a);
      $num = count($a);
      if($a == "" || empty($a) || $num < 1 || !is_array($a)){
      return "";
      }
      $n = 0;
      foreach($a as $key => $values){
      ++$n;
      if($n == 10){
      break;
      }
      $values = $this->clean_input($values);
      $key = $this->clean_input($key);
      $sql = "select userid,username,gender,avatar from oaumeetupusers where userid ='$key' limit 1";
      $result = $conn->query($sql);
      $values = strftime("%b %d %Y @ %I:%M%p",$values);
      if($row = $result->fetch_assoc()){
      $userid = $row["userid"];
      $username = $row["username"];
      $gender = $row["gender"];
      $avatar = $row["avatar"];
      if ($avatar == "" || !file_exists($avatar)){
      if($gender == "female"){
      $avatar = "femaledefault.jpeg";
      }elseif($gender == "male"){
      $avatar = "maledefault.png";
      }
      }
      //to set gender View
      if($gender == "female"){
      $gender = "<i class='fa fa-female'></i> Female";
      }elseif($gender == "male"){
      $gender = "<i class='fa fa-male'></i> Male";
      }
      if($userid == $uid){$username = "You";}
      $dat .="<a href='oaumeetupprofile.php?uid=$userid'style='text-decoration:none;'><li class='w3-bar w3-ripple w3-display-container w3-border-0' style='padding: 10px;'>
      <span class='w3-display-topright w3-small w3-text-blue w3-margin 'style=''><i>$gender</i></span>
      <img src='chatplaceholder1.jpg' data-src='$avatar'class='w3-circle lazyload w3-bar-item'style='margin-top:8px;width:70px;height:70px;padding:2px;'/>
      <div class='w3-bar-item' style='width: 70%; padding: 0;margin:6px; margin-left: 6px;'>
      <span class='w3-text-black'style='font-size:16px;''><b>$username</b></span><br>
      <span class='w3-text-grey w3-small'style='width:100%;word-wrap: break-word;'>
      Viewed $values
      </span></div>
      </li></a>";
      }//result->num_rows
      }//for each loop
      //to determine whether button to load more messgaes are available
      if($num > 10 && $n == 10){
      $_SESSION["moreviewerlist"] = array_slice($a,10);
      if(count($_SESSION["moreviewerlist"]) < 1){
      unset($_SESSION["moreviewerlist"]);
      }
      }
      $dat.="<button id='showmoreviewersbtn' class='w3-button w3-text-blue w3-hover-blue w3-hover-text-white'class='w3-block'style='width:100%;'>
      <span class='' id='showmoreviewersshow'><i class='fa fa-comments'></i> Click to show more viewers</span>
      <span class='w3-hide' id='showmoreviewersprg'><i class='fa fa-spinner w3-spin'></i> getting more viewers...</span>
      </button>";
      //to determine whether button to load more messgaes are available ends here
      return $dat;
      }
      //function to get details of viewers starts here

      //function to  load more viewers starts here
      public function getmoreviewers(){
      if(!isset($_SESSION["moreviewerlist"]) || !is_array($_SESSION["moreviewerlist"]) || count($_SESSION["moreviewerlist"]) < 1){
      return "noviewers";
      }
      $viewers = $_SESSION["moreviewerlist"];
      $conn = $this->conn;
      $uid = $this->userid;
      $data = "";
      $num = count($viewers);
      $n = 0;
      foreach($viewers as $key => $values){
      ++$n;
      if($n == 10){
      break;
      }
      $values = $this->clean_input($values);
      $key = $this->clean_input($key);
      $sql = "select userid,username,gender,avatar from oaumeetupusers where userid ='$key' limit 1";
      $result = $conn->query($sql);
      $values = strftime("%b %d %Y @ %I:%M%p",$values);
      if($row = $result->fetch_assoc()){
      $userid = $row["userid"];
      $username = $row["username"];
      $gender = $row["gender"];
      $avatar = $row["avatar"];
      if ($avatar == "" || !file_exists($avatar)){
      if($gender == "female"){
      $avatar = "femaledefault.jpeg";
      }elseif($gender == "male"){
      $avatar = "maledefault.png";
      }
      }
      //to set gender View
      if($gender == "female"){
      $gender = "<i class='fa fa-female'></i> Female";
      }elseif($gender == "male"){
      $gender = "<i class='fa fa-male'></i> Male";
      }
      if($userid == $uid){$username = "You";}
      $data .="<a href='oaumeetupprofile.php?uid=$userid'style='text-decoration:none;'><li class='w3-bar w3-ripple w3-display-container w3-border-0' style='padding: 10px;'>
      <span class='w3-display-topright w3-small w3-text-blue w3-margin 'style=''><i>$gender</i></span>
      <img src='chatplaceholder1.jpg' data-src='$avatar'class='w3-circle lazyload w3-bar-item'style='margin-top:8px;width:70px;height:70px;padding:2px;'/>
      <div class='w3-bar-item' style='width: 70%; padding: 0;margin:6px; margin-left: 6px;'>
      <span class='w3-text-black'style='font-size:16px;''><b>$username</b></span><br>
      <span class='w3-text-grey w3-small'style='width:100%;word-wrap: break-word;'>
      Viewed $values
      </span></div>
      </li></a>";
      }//result->num_rows
      }//for each loop
      if($num > 10 &&  $n == 10){
      $_SESSION["moreviewerlist"] = array_slice($viewers,10);
      if(count($_SESSION["moreviewerlist"]) < 1){
      unset($_SESSION["moreviewerlist"]);
      }
      }else{
      unset($_SESSION["moreviewerlist"]);
      }
      return $data;
      }
      //function to  load more viewers ends here


}
?>
