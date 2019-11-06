<?php
/**
 * 
 */
class countsys{
	private $userid;
	private $followers;
	private $conn;
	private $storiesfollowed;
	private $lastnotes;
	function __construct(){
	if(isset($_SESSION["userid"])){
	$this->userid = $_SESSION["userid"];
	}
	$this->connect();
	}
    
    private function connect(){
    $this->conn = new mysqli("www.oaumeetup.com","u0820137_u082013","babalolaolamide","u0820137_oaumeetupdb");
    if(mysqli_connect_error()){
    echo mysqli_connect_error();
    }else{
    $this->setudetails();
    }
    }

    //public function to set neccessary user details starts here
    public function setudetails(){
    $conn = $this->conn;
    $uid = $this->userid;
    $_SESSION["userid"];
    $sql = "select notescheckdate,storiesfollowed,followers from oaumeetupusers where userid ='$uid' limit 1";
    $result = $conn->query($sql);
     if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $this->lastnotes = $row["notescheckdate"];
    $this->storiesfollowed = $row["storiesfollowed"];
    $this->followers = $row["followers"];
    if(empty($this->lastnotes)){
    $this->lastnotes = 0;
    }
    if(empty($this->storiesfollowed)){
    $this->storiesfollowed = array();
    }else{
    $this->storiesfollowed = json_decode($this->storiesfollowed,true);
    }
    if(empty($this->followers)){
    $this->followers = array();
    }else{
    $this->followers = json_decode($this->followers,true);
    }
    }else{
    exit();
    }
    }
    //public function to set neccessary user details ends here


	//public function to check if user has new notification starts here
	public function checknewnotes(){
	$conn = $this->conn;
    $uid = $this->userid;
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
    $sql = "select * from notification where creatornotifyid ='$uid' and type in('a','d','g','e','h') and date > '$lastnotes' or creatornotifyid ='oaumeetup' and  type in('f') and date > '$lastnotes' union select * from notification where creatornotifyid in($fw) and type in ('b','c') and date > '$lastnotes' or creatornotifyid in($sfw) and type in('b','c') and date > '$lastnotes' order by id desc limit 2";
    }elseif(count($followers) > 0  && count($storiesfollowed) < 1) {
    $sql = "select * from notification where creatornotifyid ='$uid' and type in('a','d','g','e','h') and date > '$lastnotes' or creatornotifyid ='oaumeetup' and  type in('f') and date > '$lastnotes' union select * from notification where creatornotifyid in($fw) and type in ('b','c') and date > '$lastnotes' order by id desc limit 3";
    }elseif(count($followers) < 1  && count($storiesfollowed) > 0){
    $sql = "select * from notification where creatornotifyid ='$uid' and type in('a','d','g','e','h') and date > '$lastnotes' or creatornotifyid ='oaumeetup' and  type in('f') and date > '$lastnotes' union select * from notification where creatornotifyid in($sfw) and type in('b','c') and date > '$lastnotes' order by id desc limit 2";
    }else{
    $sql = "select * from notification where creatornotifyid ='$uid' and type in('a','d','g','e','h') and date > '$lastnotes' or creatornotifyid ='oaumeetup' and  type in('f') and date > '$lastnotes' order by id desc limit 2";
    }
    $result = $conn->query($sql);
    if($result->num_rows > 0){
    return "yes";
    }else{
    return "no";}

	}
	//public function to check if user has new notification ends here

	//public function to check new if user has unread chats starts here
	public function checknewchat(){
	$conn = $this->conn;
	$uid = $this->userid;
	$sql = "select id from fchat where receivermsg ='$uid' and numnewmsg > '0' and shortnewmsg !='' limit 2";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	return "yes";
	}else{
	return "no";
	}
	}
	//public function to check new if user has unread chats ends here

	//public function to get if user has new achat starts here
	public function checknewachat(){
	$conn = $this->conn;
	$uid = $this->userid;
	$sql = "select id from achat where receivermsg ='$uid' and numnewmsg > '0' and shortnewmsg !='' limit 2";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	return "yes";
	}else{
	return "no";
	}
	}
    //public function to get if user has new achat ends here

    //public function to check for new stories starts here
    public function checknewstory(){
    $conn = $this->conn;
	$uid = $this->userid;
	$sql = "select id from stories where writerid !='$uid' and expired = '0' limit 2";
	$result = $conn->query($sql);
	if($result->num_rows > 0){
	return "yes";
	}else{
	return "no";
	}
    }
    //public function to check for new stories ends here



}
?>