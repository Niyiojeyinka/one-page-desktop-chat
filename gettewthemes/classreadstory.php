<?php
session_start();
require 'classmeetupvalidate.php';


/**
 * class to handle reading of story starts here
 */
/*session to unset if not on page  starts here $_SESSION["storiesfollowed"] $_SESSION["readstorynumfollowers"] $_SESSION["followers"] $_SESSION["pid"] $_SESSION["storyid"] $_SESSION["storytitle"] $_SESSION["anonymous"]*/
class readstory extends meetupvalidate
{
	private $storyid;
	private $userid;
	private $writerid;
	function __construct()
	{
	 $this->createConnection();
	 if(isset($_SESSION["userid"])){
   $this->userid = $this->clean_input($_SESSION["userid"]);
	 }
   if(isset($_SESSION["pid"]) && isset($_SESSION["storyid"])){
   $this->setStoryId( $_SESSION["storyid"]);
   $this->setWriterId($_SESSION["pid"]);
   }
 	 }

    public function setStoryId($data)
	{
	  $this->storyid = $this->clean_input($data);
	}
    
    private function getStoryId()
    {
     return $this->storyid; 
    }

    public function setWriterId($data)
    { 
	  $this->writerid = $this->clean_input($data);
    }

    public function getWriterId()
    {
      return $this->writerid;
    }

    public function getStory()
    { 
       $data=$writerid=$storyid="";
       $conn = $this->conn;
       $storyid = $this->getStoryId();
       $uid = $this->userid;
       $sql = "select * from stories where storyid ='$storyid' limit 1";
       $result = $conn->query($sql);
       if($result->num_rows == 1){
       if($row = $result->fetch_assoc()){
       $_SESSION["pid"] = $writerid = $row["writerid"];
       $_SESSION["storyid"] =$storyid = $row["storyid"];
       $_SESSION["storytitle"] = $storytitle = $row["storytitle"];
       $storycontent = $row["storycontent"];
       $numviews = $row["numviews"];
       $viewerslist = $row["viewerslist"];
       $numcomment = $row["numcomment"];
       $commenterlist = $row["commenterslist"];
       $_SESSION["anonymous"] = $anonymous = $row["anonymous"];
       $expired = $row["expired"];
       $date = $row["date"];
       $storycontent = str_replace("_^_","\ud",$storycontent);
       $stry = json_decode($storycontent,true);
       $slidenum = count($stry);
       $stry = str_replace(array("_-_","_+_","_#_","#389"),array("\n","\r"," ","\\"),$stry);
       if (empty($viewerslist) || $viewerslist == ""){
       $viewerslist = array();
       }else{
       $viewerslist = json_decode($viewerslist,true);
       }
       $this->updateView($numviews,$storyid,$viewerslist);
       /*$colorstory = array_keys($stry);
       $readstory = array_values($stry);*/
       foreach ($stry as $keys => $values){
       $keys = $this->clean_input($keys);
       $values = strip_tags($values);
       $values = nl2br($values);
       $data .="<div class='$keys w3-animate-left storyctent w3-hide'style='width:100%;margin-right:auto;margin-left:auto;font-size:22px;height:100vh;word-wrap:break-word;padding:15px;'>
       <p class=''style='margin-top: 5px;height:100%;overflow: auto;vertical-align:middle;'>$values</p>
        </div>";
       }
       if($writerid == $uid){
        $data.="<button class='w3-round-large  w3-center w3-display-right deletebtn w3-text-red w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-red'style='margin-top:9vh;margin-right: 5px;'><span id='trashicon'class=''> <i class='fa fa-trash w3-xlarge'></i></span> <span id='delprg'class='w3-tiny w3-hide'><i class='fa fa-spinner w3-large w3-spin'></i><br>Deleting..</span></button>";
        }

        $data .="
        <button class='w3-round-large w3-display-topright w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-left: 30vw;margin-right:5px;margin-top:2vh;'><i class='fa fa-sticky-note w3-large'></i> <span id='slideindex' class='w3-medium'>1/$slidenum</span></button>

        <a href='oaumeetupstorycomments.php?storyid=$storyid'style='text-decoration:none;'><button class='w3-round-large commentbtn next w3-display-right w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-top:17vh;margin-right: 5px;'><i class='fa fa-comment w3-xlarge'></i></button></a>


        <button id='sharebtn' class='w3-round-large w3-hide w3-center w3-display-right w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-top:25vh;margin-right: 5px;'><span id='shareicon'class=''> <i class='fa fa-share-alt w3-xlarge'></i></span> <span id='shareprg'class='w3-tiny w3-hide'><i class='fa fa-spinner w3-large w3-spin'></i><br>sharing..</span></button>
         <a href='oaumeetupstorycomments.php?storyid=$storyid'style='text-decoration:none;'>
        <button class='w3-round-large  views w3-display-bottomleft w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-bottom:30px;padding: 7px;margin-left:20vw;'><i class='fa fa-eye w3-large'></i> <span class='w3-medium'>$numviews</span></button></a>
        
        <a href='oaumeetupstorycomments.php?storyid=$storyid'style='text-decoration:none;'><button class='w3-round-large comments next w3-display-bottommiddle w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-bottom:30px;padding: 7px;margin-left: 20vw;'><i class='fa fa-comments w3-large'></i> <span class='w3-medium'>$numcomment</span></button></a>";
        }
        if($writerid !=$uid){
        $this->storiesadd($date);
        }
        //if isset is storiesfollowed
        if(isset($_SESSION["storiesfollowed"]) && is_array($_SESSION["storiesfollowed"])){
        $revwriterid = "'$writerid'";
        if(array_key_exists($revwriterid,$_SESSION["storiesfollowed"]) && $writerid !=$uid){
        $data.="<button id='unfollowbtn' class='w3-round-large w3-center w3-display-left w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-top:9vh;margin-left: 5px;'>
        <span id='unfollowicon'class='w3-tiny'> <i class='fa fa-user-times w3-large'></i></span> 
        <span id='unfollowprg'class='w3-tiny w3-hide'><i class='fa fa-spinner w3-large w3-spin'></i><br>unfollowing..</span>
        </button>
        <button id='followbtn' class='w3-round-large w3-center w3-hide w3-display-left w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-top:9vh;margin-left: 5px;'>
        <span id='followicon'class='w3-tiny'> <i class='fa fa-user-plus w3-large'></i></span> 
        <span id='followprg'class='w3-tiny w3-hide'><i class='fa fa-spinner w3-large w3-spin'></i><br>following..</span>
        </button>
        ";
        }elseif(!array_key_exists($revwriterid,$_SESSION["storiesfollowed"]) && $writerid !=$uid){
        $data.="<button id='followbtn' class='w3-round-large w3-center w3-display-left w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-top:9vh;margin-left: 5px;'>
        <span id='followicon'class='w3-tiny'> <i class='fa fa-user-plus w3-large'></i></span> 
        <span id='followprg'class='w3-tiny w3-hide'><i class='fa fa-spinner w3-large w3-spin'></i><br>following..</span>
        </button>
        <button id='unfollowbtn' class='w3-round-large w3-hide w3-center w3-display-left w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-top:9vh;margin-left: 5px;'>
        <span id='unfollowicon'class='w3-tiny'> <i class='fa fa-user-times w3-large'></i></span> 
        <span id='unfollowprg'class='w3-tiny w3-hide'><i class='fa fa-spinner w3-large w3-spin'></i><br>unfollowing..</span>
        </button>";
        }
        }
        //if isset is storiesfollowed
        $writerdetails = $this->getPartnerDetails($writerid);
        $writername = $writerdetails[0];
        $writergender = $writerdetails[1];
        $followers = $writerdetails[2];
        //make sure the story doesnot belong t the user before setting session
        if($uid != $writerid && is_array($followers)){
        $_SESSION["followers"] = $followers; 
        }
        //make sure the story doesnot belong t the user before setting session

        //writername and writergender is set
        if($anonymous  == "1" && $writerid !=$uid){
        $writername = "<i class='fa fa-$writergender'></i> $writergender Anonymous";
        $data .="<button class='w3-round-large w3-display-topright w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-top:10vh;margin-right:5px;'><span id='writername' class='w3-tiny'>$writername</span></button>";
        }else if($anonymous == "0" && $writerid !=$uid){
        $writername = "<i class='fa fa-user'></i> $writername";
        $data .="<a href='oaumeetupprofile.php?uid=$writerid'style='text-decoration:none;'><button class='w3-round-large w3-display-topright w3-text-white w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue'style='margin-top:10vh;margin-right:5px;'><span id='writername' class='w3-tiny'>$writername</span></button></a>";
        }
        //writername and writergender is not set

        return $data;
       }else{
       	return "<div class='w3-center w3-text-red w3-large w3-animate-left'style='margin-top:40%;'>
       	<span><i class='fa fa-exclamation-triangle w3-xxlarge'></i> Story not found</span>
       	</div>";
       }
    }

    //function to update view on database starts here
    private function updateView($data,$storyid,$viewarray)
    {
      $view=$id=$arrayv="";
      $conn = $this->conn;
    	$view = $this->clean_input($data);
    	$id = $this->clean_input($storyid);
      $uid = $this->userid;
      $arrayv = $viewarray;
    	if (empty($view) || $view == "") {
      $view = 0; 
    	}
      if(empty($arrayv) || $arrayv == ""){
      $arrayv = array();
    	}
      if(!array_key_exists($uid, $arrayv)){
      $arrayv[$uid] = time();
       ++$view;
      $arrayv = json_encode($arrayv);
      if (!empty($id) || $id != "") {
      $sql = "update stories set numviews ='$view',viewerslist='$arrayv' where storyid = '$id' limit 1";
      $conn->query($sql);          
      }   
      }
      }
    
    //public function to get and update users stories read starts here
    public function storiesadd($data){
    $uid = $this->userid;
    $conn = $this->conn;
    $writerid = "";
    $date = time();
    if(isset($_SESSION["pid"])){
    $writerid = $_SESSION["pid"];
    }
    $storyid = $this->getStoryId();
    $stryid = $storyid;
    if(empty($uid) || empty($storyid) || empty($data) || !is_numeric($data)){
    return "";
    }
    $sql = "select readstorylist,storiesfollowed from oaumeetupusers where userid = '$uid' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $readstorylist = $row["readstorylist"];
    $storiesfollowed = $row["storiesfollowed"];
    if(empty($readstorylist)){
    $readstorylist = array();
    }else{
    $readstorylist = json_decode($readstorylist,true);
    }
    
    //to set storiesfollowed session starts here
    if(empty($storiesfollowed)){
    $_SESSION["storiesfollowed"] = array();
    }else{
    $_SESSION["storiesfollowed"] = json_decode($storiesfollowed,true);
    }
    //to set storiesfollowed session ends here
    $storyid = "'$storyid'";
    //to handle notification starts here
    $sql25 = "select landpagelink from notification where creatornotifyid = '$writerid' and recepientnotifyid ='$stryid' and type ='h' limit 1";
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
      $sql25 = "update notification set landpagelink = '$landpagelink',date='$date' where creatornotifyid = '$writerid' and recepientnotifyid ='$stryid' and type ='h' limit 1";
      }else{
      $sql25 = "";
      }

      }else{
      $landpagelink = array();
      if(!in_array($uid,$landpagelink)){
      array_push($landpagelink,$uid);
      $landpagelink = $conn->real_escape_string(json_encode($landpagelink));
      $sql25 = "insert into notification(creatornotifyid,recepientnotifyid,type,landpagelink,date) values('$writerid','$stryid','h','$landpagelink','$date')";
      }else{
      $sql25 = "";
      }
      }
    //to handle notification ends here
    if(!array_key_exists($storyid,$readstorylist)){
    $readstorylist[$storyid] = $data;
    $readstorylist = json_encode($readstorylist);
    $readstorylist = $conn->real_escape_string($readstorylist);
    $sql = "update oaumeetupusers set readstorylist ='$readstorylist' where userid ='$uid' limit 1";
    $conn->query($sql);
    if(!empty($sql25)){
    $conn->query($sql25);
    }
    }

    }//if num_rows == 1
    }
    //public function to get and update stories read ends here

    //public function to follow a story starts here
    public function followStory(){
    $conn = $this->conn;
    $uid = $this->userid;
    $writerid = $this->getWriterId();
    $wrid = $writerid;
    $idu = $uid;
    
    if(!isset($_SESSION["storiesfollowed"]) || !isset($_SESSION["anonymous"]) || !isset($_SESSION["followers"]) || !isset($_SESSION["storytitle"]) || empty($uid) || empty($writerid)){
    return "Failed could not follow story at this time kindly refresh your browser and try again";
    }
    $storiesfollowed = $_SESSION["storiesfollowed"];
    $writerfollowers = $_SESSION["followers"];
    $anonymous = $this->clean_input($_SESSION["anonymous"]);
    $storytitle = $this->clean_input($_SESSION["storytitle"]);
    if(!is_array($storiesfollowed) || !is_array($writerfollowers) || strlen($anonymous) > 1 || empty($storytitle)){
    return "Failed could not follow story at this time kindly refresh your browser and try again";
    }
    $writerid = "'$writerid'";
    $uid = "'$uid'";
    if(!array_key_exists($writerid,$storiesfollowed) && !array_key_exists($writerid,$writerfollowers)){
    $date = time();
    $storiesfollowed[$writerid] = array($storytitle,$anonymous);
    $dbstoriesfollowed = json_encode($storiesfollowed);
    $writerfollowers[$uid] = array($storytitle,$anonymous);
    $dbwriterfollowers = json_encode($writerfollowers);
    $dbstoriesfollowed = $conn->real_escape_string($dbstoriesfollowed);
    $dbwriterfollowers = $conn->real_escape_string($dbwriterfollowers);
    //query to update into notification
    $sql25 = "select id from notification where creatornotifyid = '$wrid' and type = 'g' limit 1";
    $result25 = $conn->query($sql25);
    if($result25->num_rows == 1){
    $sql25 = "update notification set landpagelink = '$dbwriterfollowers' ,date = '$date' where creatornotifyid='$wrid'and type = 'g' limit 1";
    }else{
    $sql25 = "insert into notification(creatornotifyid,type,landpagelink,date) values('$wrid','g','$dbwriterfollowers','$date')";
    }
    //query to update into notification ends here
    $sql = "update oaumeetupusers set storiesfollowed = '$dbstoriesfollowed' where userid = '$idu' limit 1";
    $sql1 = "update oaumeetupusers set followers = '$dbwriterfollowers' where userid = '$wrid' limit 1";
    if($conn->query($sql) == "true"){
    $_SESSION["storiesfollowed"] = $storiesfollowed;
    $conn->query($sql25);
    $conn->query($sql1);
    $_SESSION["followers"] = $writerfollowers;
    return "success";
    }else{
    return "Failed couldnot follow story at this time please try again".$conn->error;
    }
    /*if key does  not exit*/}else{
    return "Failed you are already folllowing this story";
    }
    }
    //public function to unfollow a story ends here
    
    //public function to unfollow story starts here
    public function unfollowstory(){
    $conn = $this->conn;
    $uid = $this->userid;
    $writerid = $this->getWriterId();
    $wrid = $writerid;
    $idu = $uid;
    if(!isset($_SESSION["storiesfollowed"]) || !isset($_SESSION["followers"]) || empty($uid) || empty($writerid)){
    return "Failed could not unfollow story please kindly refresh your browser and try again";
    }
    $storiesfollowed = $_SESSION["storiesfollowed"];
    $dbstoriesfollowed = $_SESSION["storiesfollowed"];
    $writerfollowers = $_SESSION["followers"];
    $dbwriterfollowers = $_SESSION["followers"]; 
    if(!is_array($dbstoriesfollowed) && !is_array($dbwriterfollowers)){
    return "Failed could not unfollow story please kindly refresh your browser and try again";
    }
    $writerid = "'$writerid'";
    $uid = "'$uid'";
    if(array_key_exists($writerid,$dbstoriesfollowed) && array_key_exists($uid,$dbwriterfollowers)){
    unset($dbstoriesfollowed[$writerid]);
    unset($dbwriterfollowers[$uid]);
    $_SESSION["storiesfollowed"] = $dbstoriesfollowed;
    $_SESSION["followers"] = $dbwriterfollowers;
    $dbstoriesfollowed = json_encode($dbstoriesfollowed);
    $dbstoriesfollowed = $conn->real_escape_string($dbstoriesfollowed);
    $dbwriterfollowers = json_encode($dbwriterfollowers);
    $dbwriterfollowers = $conn->real_escape_string($dbwriterfollowers);
    $sql = "update oaumeetupusers set storiesfollowed = '$dbstoriesfollowed' where userid = '$idu' limit 1";
    $sql1 = "update oaumeetupusers set followers = '$dbwriterfollowers' where userid='$wrid' limit 1";

    if($conn->query($sql) == "true"){
    $conn->query($sql1);
    return "success";
    }else{
    $_SESSION["storiesfollowed"] = $storiesfollowed; 
    $_SESSION["followers"] = $writerfollowers;   
    return "Failed could not unfollow story at this time please try again later".$conn->error;
    }

    }else{
    return "Failed you cannot unfollow this story because you arent following the story in the first place";
    }


    }
    //public function to unfollow story ends here


    //block of code to delete a story 
    public function deleteStory(){
      $conn = $this->conn;
      $uid = $this->userid;
      $storyid = $this->getStoryId();
      $writerid = $this->getWriterId();
      $sql = "delete from stories where storyid='$storyid' limit 1";
      if ($uid == $writerid) {
      if ($conn->query($sql) == "true") {
      unset($_SESSION["storyid"]);
      unset($_SESSION["pid"]);
      return "delete successful";
      }else{return "failed".$conn->error;}
      }else{
       return "You didnt write this story you can't delete it :(";
      }
      }

    //function to Get User Details starts Here
    public function getPartnerDetails($data){
    $uid = $this->userid;
    $id = $this->clean_input($data);
    if(empty($id)){
    return array();
    }
    $conn = $this->conn;
    $sql = "select username,gender,followers from oaumeetupusers where userid = '$id' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $uname = $row["username"];
    $gender = $row["gender"];
    $followers = $row["followers"];
    if(empty($followers)){
    $followers = array();
    }else{
    $followers = json_decode($followers,true);
    }
    return array($uname,$gender,$followers);
    }else{
    return array();
    }    
    }
    //function to get User DetaIls Ends Here

    //public function to share story starts here
    public function sharestory(){
    $conn = $this->conn;
    $uid = $this->userid;
    $storyid = $this->getStoryId();
    }
    //public function to share story ends here

}

?>