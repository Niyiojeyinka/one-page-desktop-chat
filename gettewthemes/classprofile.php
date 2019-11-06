<?php
session_start();
require "classmeetupvalidate.php";
/**
 *
 */
class profile extends meetupvalidate
{
	private $userid;
	private $profileownerid;
	private $achatid;
	private $chatid;
	public $chatexists = false;
	public $achatexists = false;

    function __construct(){
	$this->createConnection();
	if(isset($_SESSION["userid"])){
    $this->setUserid($_SESSION["userid"]);
	}
	}

    public function setUserId($data){
    $this->userid = $this->clean_input($data);
    }
    public function getUserId(){
    return $this->userid;
    }
    public function setOwnerId($data){
    $this->profileownerid = $this->clean_input($data);
    }
    public function getOwnerId(){
    return $this->profileownerid;
    }
    public function setChatId($data){
    $this->chatid = $this->clean_input($data);
    }
    public function getChatId(){
    return $this->chatid;
    }
    public function setAchatId($data){
    $this->achatid = $this->clean_input($data);
    }
    public function getAchatId(){
    return $this->achatid;
    }
    public function getstoryf(){
    return $this->storiesfollowed;
    }


    public function getProfileDetails(){
    $conn = $this->conn;
    $uid = $this->getUserId();
    $ownerid = $this->getOwnerId();
    $sql = "select * from oaumeetupusers where userid = '$ownerid' and activated = '1' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows  == 1){
    if($row = $result->fetch_assoc()){
    $userid = $row["userid"];
    $name = $row["name"];
    $username = $row["username"];
    $email = $row["email"];
    $phonenumber = $row["phonenumber"];
    $institution = $row["institution"];
    $gender = $row["gender"];
    $pass = $row["password"];
    $signupdate = $row["signupdate"];
    $avatar = $row["avatar"];
    $farray = json_decode($row["friend_array"],true);
    $mprefernce = json_decode($row["meetup_preference"],true);
    $bio = $row["bio"];
    $lastlog = $row["lastlogindate"];
    $attributes = json_decode($row["attributes"],true);
    }
    if(empty($avatar) && $gender == "female"){
    $avatar = "femaledefault.jpeg";
    }elseif(empty($avatar) && $gender == "male"){
    $avatar = "maledefault.png";
    }
    if(empty($bio) && $gender == "female"){
    $bio = "Don't doubt it,you are a beauty :)";
    }elseif(empty($bio) && $gender == "male"){
    $bio="Bro you are cool";
    }
    return array($name,$username,$institution,$gender,$avatar,$bio,$lastlog,$attributes,$mprefernce);
    }else{
    echo "Failed user doesnot exists";
    exit();
    }
    }
    //function to Handle Posting Of Status Starts Here
    public function updateStatus($data){
    $conn = $this->conn;
    $uid = $this->getUserId();
    $ownerid = $this->getOwnerId();
    $status = $this->clean_input($data);
    $sql= "update oaumeetupusers set bio ='$status' where userid='$ownerid' limit 1";
    if($uid != $ownerid){
    return "You are not the owner of this profile and cannot  upload status";   }
    if(empty($status) || $status == ""){
    return "sorry your status contains character that are not accepted";
    }
    
    if($conn->query($sql) == "true"){
    return "success";
    }else{
    return "sorry could not upload your status please try again";
    }
    }//ClosinG Braces For Function
    
    //function to handle uploading Of Profile Pic Starts Here
    public function updateProfilePic($data){
        $conn = $this->conn;
        $file = $this->clean_input($data);
        $uid = $this->getUserId();
        $ownerid = $this->getOwnerId();
        $date = time();
        $sql = "update oaumeetupusers set avatar = '$file' where userid='$ownerid' limit 1";
        if(!file_exists($file)){
         return "sorry  your file has not yet being uploaded";
        }else if($uid != $ownerid){
        return "You Are Not The Owner Of This Profile And Cannot Change ProFile Picture";
        }

        $sql10 = "select id from notification where creatornotifyid ='$uid' and type ='b' limit 1";
        $result10 = $conn->query($sql10);
        if($result10->num_rows == 1){
        $sql10 = "update notification set landpagelink ='$uid',date = '$date' where creatornotifyid = '$uid' and type='b' limit 1";
        }else{
        $sql10 = "insert into notification(creatornotifyid,type,landpagelink,date) value('$uid','b','$uid','$date')";
        }
        //opening tag for getting  dbpics starts here
        $sql1= "select avatar from oaumeetupusers where userid='$ownerid' limit 1";
        $result1 = $conn->query($sql1);
        if($result1->num_rows == 1){
        if($row1 = $result1->fetch_assoc()){
        $avatar = $row1["avatar"];
        }else{
        return "Sorry user does not";
        }
        }//closing braces for getting dbpic ends here

        //to insert the picture into The database
        if($conn->query($sql) == "true"){
        if(file_exists($avatar)){
        unlink($avatar);
        }
        //return $sql10;
        $conn->query($sql10);
        return "success";
        }else{
        if(file_exists($file)){
        unlink($file);
        }
        return "sorry could not  save image to server please try again";
        }
    }//closing braces for function
    
    //function to handle getting and set function starts here
    public function chatCreate(){
    $conn = $this->conn;
    $uid = $this->getUserId();
    $ownerid = $this->getOwnerId();
    if($uid == $ownerid){
    return;
    }else if(empty($uid) || empty($ownerid)){
    return;
    }
    //for Creating normal Chat
    $sql = "select chatid from chatcreate where creatorid='$uid' and recepientid='$ownerid' and type='0' or creatorid = '$ownerid' and recepientid='$uid' and type='0' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    if($row = $result->fetch_assoc()){
    $chatid  = $row["chatid"];
    $this->setChatId($chatid);
    $this->chatexists = "true";
    }
    }else{
    $time = time();
    $chatid = md5(rand(0,7000).rand(0,4000));
    $sql = "insert into chatcreate(creatorid,recepientid,type,chatid,date) values('$uid','$ownerid','0','$chatid','$time')";
    if($conn->query($sql) == "true"){
    $this->setChatId($chatid);
    $this->chatexists = "true";
    }else{
    echo $conn->error;
    exit();
    }
    }
    //for achat starts Here
    $sql = "select chatid from chatcreate where creatorid='$uid' and recepientid='$ownerid' and type='1' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    if($row = $result->fetch_assoc()){
    $chatid  = $row["chatid"];
    $this->setAchatId($chatid);
    $this->achatexists = "true";
    }
    }else{
    $time = time();
    $chatid = md5(rand(0,7000).rand(0,4000));
    $sql = "insert into chatcreate(creatorid,recepientid,type,chatid,date) values('$uid','$ownerid','1','$chatid','$time')";
    if($conn->query($sql) == "true"){
    $this->setAchatId($chatid);
    $this->achatexists = "true";
    }else{
    echo $conn->error;
    exit();
    }
    }
    }

    //public function to handle following of profile owner starts here
    public function followu(){
    $conn = $this->conn;
    $uid = $this->getUserId();
    $u = "'$uid'";
    $ownerid = $this->getOwnerId();
    $ow = "'$ownerid'";
    if($uid == $ownerid){
    return "failed sorry you cannot follow yourself";
    }
    $ownerdetails = $this->getdel($ownerid);
    if(is_array($ownerdetails) && count($ownerdetails) > 0){
    $ownerstoriesfollowers = $ownerdetails[1];
    }else{
    return "failed something went wrong could not follow userstory at this time please try again";
    }
    $storiesfollowed = $this->storiesfollowed;
    if(!array_key_exists($u,$storiesfollowed) && !array_key_exists($ow,$ownerstoriesfollowers)){
    $date = time();
    $storiesfollowed[$ow] = array(')*(','0');
    $storiesfollowed = json_encode($storiesfollowed);
    $ownerstoriesfollowers[$u] = array(")*(","0");
    $ownerstoriesfollowers = json_encode($ownerstoriesfollowers);
    $storiesfollowed = $conn->real_escape_string($storiesfollowed);
    $ownerstoriesfollowers = $conn->real_escape_string($ownerstoriesfollowers);
    $sql25 = "select id from notification where creatornotifyid = '$ownerid' and type = 'g' limit 1";
    $result25 = $conn->query($sql25);
    if($result25->num_rows == 1){
    $sql25 = "update notification set landpagelink = '$ownerstoriesfollowers',date = '$date' where creatornotifyid='$ownerid'and type = 'g' limit 1";
    }else{
    $sql25 = "insert into notification(creatornotifyid,type,landpagelink,date) values('$ownerid','g','$ownerstoriesfollowers','$date')";
    }
    $sql = "update oaumeetupusers set storiesfollowed = '$storiesfollowed' where userid = '$uid' limit 1";
    $sql1 = "update oaumeetupusers set followers = '$ownerstoriesfollowers' where userid = '$ownerid' limit 1";
    if($conn->query($sql) == "true"){
    $conn->query($sql1);
    $conn->query($sql25);
    return "success";
    }else{
    return "failed couldnot follow userstory at this time please try again".$conn->error;
    }
    /*if key does  not exit*/}else{
    return "failed you are already folllowing this userstory";
    }

    }
    //public function to handle following of profile owner ends here

    //PUBLIc function to get stories followed and follwers starts here
    public function getdel($data){
    $conn = $this->conn;
    $data = $this->clean_input($data);
    if(empty($data)){
    return array();
    }
    $sql = "select storiesfollowed,followers from oaumeetupusers where userid ='$data' and activated='1' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $storiesfollowed = $row["storiesfollowed"];
    $followers = $row["followers"];
    if(empty($storiesfollowed)){
    $storiesfollowed = array();
    }else{
    $storiesfollowed = json_decode($storiesfollowed,true);
    }
    if(empty($followers)){
    $followers = array();
    }else{
    $followers = json_decode($followers,true);
    }
    return array($storiesfollowed,$followers);
    }else{
    return array();
    }
    }
    //PUBLIc function to get stories followed and follwers starts here

    //public function to unfollow user starts here
    public function unfollowu(){
    $conn = $this->conn;
    $uid = $this->getUserId();
    $u = "'$uid'";
    $ownerid = $this->getOwnerId();
    $ow = "'$ownerid'";
    if($uid == $ownerid){
    return "failed sorry you cannot unfollow yourself";
    }
    $ownerdetails = $this->getdel($ownerid);
    if(is_array($ownerdetails) && count($ownerdetails) > 0){
    $ownerstoriesfollowers = $ownerdetails[1];
    }else{
    return "failed something went wrong could not unfollow userstory at this time please try again";
    }
    $storiesfollowed = $this->storiesfollowed;
    if(array_key_exists($ow,$storiesfollowed) && array_key_exists($u,$ownerstoriesfollowers)){
    unset($storiesfollowed[$ow]);
    unset($ownerstoriesfollowers[$u]);
    $storiesfollowed = json_encode($storiesfollowed);
    $storiesfollowed = $conn->real_escape_string($storiesfollowed);
    $ownerstoriesfollowers = json_encode($ownerstoriesfollowers);
    $ownerstoriesfollowers = $conn->real_escape_string($ownerstoriesfollowers);
    $sql = "update oaumeetupusers set storiesfollowed = '$storiesfollowed' where userid = '$uid' limit 1";
    $sql1 = "update oaumeetupusers set followers = '$ownerstoriesfollowers' where userid='$ownerid' limit 1";
    if($conn->query($sql) == "true"){
    $conn->query($sql1);
    return "success";
    }else{
    return "failed could not unfollow story at this time please try again later".$conn->error;
    }
    }else{
    return "failed you are not following this user story in the first place and cannot unfollow";
    }

    }
    //public function to unfollow user ends here


}?>