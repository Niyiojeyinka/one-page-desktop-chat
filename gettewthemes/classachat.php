<?php
session_start();
require 'classmeetupvalidate.php';
/**
 * class for anonymous chatting among users starts here
 */
class achat extends meetupvalidate
{
	 private $uid;
	 private $toseeprofilepicid;
	 private $tonotseeprofilepicid;
	 private $achatid;
	 public $iscreatorachat = false;
	 private $msgdetails = array();
   public $isacreatorblocked = 0;
   public $isarecepientblocked = 0;
	
	 function __construct(){
	 $this->createConnection();
	 if(isset($_SESSION["userid"])){
	 $this->setUid($_SESSION["userid"]);
	 }
     if(isset($_SESSION["toseeprofilepicid"]) && isset($_SESSION["tonotseeprofilepicid"]) && isset($_SESSION["achatid"])){
     $this->setToSeeProfilePicId($_SESSION["toseeprofilepicid"]);
     $this->setToNotSeeProfilePicId($_SESSION["tonotseeprofilepicid"]);
     $this->setAchatId($_SESSION["achatid"]);
     }
     if(isset($_SESSION["am"])){
     $this->msgdetails = $_SESSION["am"];
     }
     if(isset($_SESSION["iscreatorachat"])){
     $this->iscreatorachat = $_SESSION["iscreatorachat"];
     }
     if(isset($_SESSION["isacreatorblocked"])){
     $this->isacreatorblocked = $_SESSION["isacreatorblocked"];
     }
     if(isset($_SESSION["isarecepientblocked"])){
     $this->isarecepientblocked = $_SESSION["isarecepientblocked"];
     }

	 }

	public function setUid($uid){
	$this->uid = $this->clean_input($uid);
	}
	public function getUid(){
	return $this->uid;
	}

	public function setToSeeProfilePicId($data){
	$this->toseeprofilepicid = $this->clean_input($data);
	}
	public function getToSeeProfilePicId(){
	return $this->toseeprofilepicid;
	}
     
    public function setToNotSeeProfilePicId($data){
	$this->tonotseeprofilepicid = $this->clean_input($data);
	}
	public function getToNotSeeProfilePicId(){
	return $this->tonotseeprofilepicid;
	}

	public function setAchatId($data){
	$this->achatid = $this->clean_input($data);
	}
	public function getAchatId(){
	return $this->achatid;
    }

   //public function to validat anonymous chat starts here
    public function confirmAchat($achatid){
    $conn = $this->conn;
    $uid = $this->getUid();
    $achatid = $this->clean_input($achatid);
    if(empty($achatid)){
    echo "Failed Missing values to continue";
    exit();
    }
    $sql = "select creatorid,recepientid,chatid,creatorblocked,recepientblocked from chatcreate where creatorid = '$uid' and type='1' and chatid = '$achatid' or recepientid = '$uid' and type='1' and chatid ='$achatid' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $creatorid = $this->clean_input($row["creatorid"]);
    $recepientid = $this->clean_input($row["recepientid"]);
    $chatid = $this->clean_input($row["chatid"]);
    $creatorblocked = $this->clean_input($row["creatorblocked"]);
    $recepientblocked = $this->clean_input($row["recepientblocked"]);
    if($chatid != $achatid){
    echo "chatid do not match !!!";
    exit();
    }
    //checking whether userid is the creatorid
    if($uid == $creatorid){
    $this->iscreatorachat= $_SESSION["iscreatorachat"] = true;
    }else if($recepientid == $uid){
    $this->iscreatorachat= $_SESSION["iscreatorachat"] = false;
    }else{
    echo "Could not acertain chat creator";
    exit();
    }
    //checking whether userid is the creatorid ends here

    //to check if chat has being blocked starts here
    if($creatorblocked == "1"){
    $this->isacreatorblocked = $_SESSION["isacreatorblocked"] = "1";
    }else{
    $this->isacreatorblocked = $_SESSION["isacreatorblocked"] = "0";
    }

    if($recepientblocked == "1"){
    $this->isarecepientblocked = $_SESSION["isarecepientblocked"] = "1";
    }else{
    $this->isarecepientblocked = $_SESSION["isarecepientblocked"] = "0";
    }
    //to check if chat has being blocked ends here

    //code to open achat for the two users 
    $sql2 = "select id from achat where achatid ='$achatid' and creatorid = '$creatorid' and recepientid = '$recepientid'  limit 1";
    $result2 = $conn->query($sql2);
    //code to handle creating of achat starts here
    if($result2->num_rows == 1){
    $this->setAchatId($achatid);
    $_SESSION["achatid"] = $achatid;
    if($uid == $creatorid && $this->iscreatorachat == true){
    $this->setToSeeProfilePicId($uid);
    $this->setToNotSeeProfilePicId($recepientid);
    $_SESSION["toseeprofilepicid"] = $uid;
    $_SESSION["tonotseeprofilepicid"] = $recepientid;
    }else if($recepientid == $uid && $this->iscreatorachat == false){
    $this->setToNotSeeProfilePicId($uid);
    $this->setToSeeProfilePicId($creatorid);
    $_SESSION["toseeprofilepicid"] = $creatorid;
    $_SESSION["tonotseeprofilepicid"] = $uid;
    }	
    
    /*closing braces for if achat for both users already exists*/}else{
    $sql2 = "insert into achat(achatid,creatorid,recepientid) values('$achatid','$creatorid','$recepientid')";
    if($conn->query($sql2) == 'true'){
    $this->setAchatId($achatid);
    $_SESSION["achatid"] = $achatid;
    if($uid == $creatorid && $this->iscreatorachat == true){
    $this->setToSeeProfilePicId($uid);
    $this->setToNotSeeProfilePicId($recepientid);
    $_SESSION["toseeprofilepicid"] = $uid;
    $_SESSION["tonotseeprofilepicid"] = $recepientid;
    }else if($recepientid == $uid && $this->iscreatorachat == false){
    $this->setToNotSeeProfilePicId($uid);
    $this->setToSeeProfilePicId($creatorid);
    $_SESSION["toseeprofilepicid"] = $creatorid;
    $_SESSION["tonotseeprofilepicid"] = $uid;
    }		
    }else{
    echo "Could not initialize anonymous chat please refresh your browser".$conn->error;
    exit();
    }
}
    //code to handle creating of achat ends here
    /*closing braces for if num_rows == 1*/}else{
    echo "Anonymous chat doesnot exist or something went wrong please refresh your browser".$conn->error;
    exit();
    }
    }
    //public function to validat anonymous chat ends here

    //public function to update chat
    public function updateArrayOpen($data){
    global $uid;
    $uid = $this->getUid();
    function myfunction($v){
    $a = $v;
    global $uid;
    if($uid == $a[3]){
    $a[7] = 1;
    }
    return $a;
    }
    if(!empty($data) && is_array($data)){
    return array_map("myfunction",$data);
    }//if statement
    return array();
    }//closing Braces For Funxtion

    //public function to get anonymous chat starts here
    public function getTheAnonymousChat(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $achatid = $this->getAchatId();
    $toseeid = $this->getToSeeProfilePicId();
    $scrollid = '';
    $data = '';
    $numc = 0;
    $time = "";
    $nottoseeid = $this->getToNotSeeProfilePicId();
    if(empty($uid) || empty($toseeid) || empty($nottoseeid) || empty($achatid)){
    echo "Something went wrong important values missing to continue please refresh browser";
    exit();
    }
    $conn->begin_transaction();
    $sql = "select receivermsg,numnewmsg,msgdetails from achat where achatid ='$achatid' limit 1 for update";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    if($row = $result->fetch_assoc()){
	$receivermsg = $row["receivermsg"];
	$numnewmsg = $row["numnewmsg"];
	$msgdetails =  $row['msgdetails'];
	} //fwct_assoc()
	if($msgdetails == "" || empty($msgdetails)){
	$this->msgdetails = $_SESSION["am"]= array();
	return "";
	}
	$msgdetails = str_replace('®®©','\ud',$msgdetails);
	$msgdetails = json_decode($msgdetails,true);
    $this->msgdetails = $_SESSION["am"]=$msgdetails = $this->updateArrayOpen($msgdetails);
    $msgdetails = json_encode($msgdetails);
    $msgdetails = str_replace('\ud','®®©',$msgdetails);
    $msgdetails= $conn->real_escape_string($msgdetails);
    if($receivermsg == $uid){
    $sql2 = "update achat set receivermsg = '',numnewmsg='0',shortnewmsg ='',msgdetails = '$msgdetails' where achatid= '$achatid' limit 1";
    }else{
    $sql2 = "update achat set msgdetails = '$msgdetails' where achatid= '$achatid' limit 1";
    }
    if($conn->query($sql2) != "true"){
    $this->msgdetails = $_SESSION["am"]= array();
    echo "Could not anonymous chat messages between chat users please try again".$conn->error;
    $conn->rollback();
    exit();
    }else{
    $conn->commit();
    }
     $msgdetails = $this->msgdetails;
     $msgdetails = str_replace(array('®®©','<','>'),array('\ud','',''),$msgdetails);
	  $num = count($msgdetails);
	  $index = $num -1;
	  foreach($msgdetails as $key => $a){
	  $numc++;
	  $msgid = $key;
	  $ar = $a;
	  $msg  = nl2br(strip_tags (stripslashes($ar[0])));
	  $avatarmsg = $ar[1];
	  $senderid = $ar[2];
	  $receiverid = $ar[3];
	  $time = strftime("%a %b %d %Y @ %I:%M%p",$ar[4]);
	  $senderdelete = $ar[5];
	  $receiverdelete = $ar[6];
	  $opened = $ar[7];
	  //code to get scroll to currrent scrollid
	  if(empty($scrollid) && $numc < $index){
	   if($receiverid  == $uid && $opened == "0" ){
	   $scrollid = $msgid;  
	   }else if($senderid == $uid && $opened == "0"){
	   $scrollid = $msgid;
	   }
	  }else if(empty($scrollid) && $numc >= $index){
     if($receiverid  == $uid && $opened == "0" ){
     $scrollid = $msgid;  
     }else if($senderid == $uid && $opened == "0"){
     $scrollid = $msgid;
     }else{
	   $scrollid = $msgid;
    }
	  }

	  //code to get Scroll ID Ends Here

	  if($msg != '' && $avatarmsg == ''){
	  if($senderid == $uid && $senderdelete == 0){
    $receivertime = $this->getdatet($receiverid);
	  $data .="<div id='$msgid' class='mymsg w3-panel'style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-right w3-card msg_txt w3-purple w3-padding w3-animate w3-ripple  w3-animate-zoom w3-medium 'style='max-width:250px;word-wrap: break-word;border-radius: 15px 50px 30px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
     <p class='w3-tiny w3-left'style='margin:0;margin-left:5px;margin-right:10px;'>$time</p>
     <img src='chathide.jpg' class='w3-circle  w3-right' style='width:20px;height:20px;margin-left:8px;'>";
	  if($opened == 1){
    $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>seen</span>
    </div></div>";
    }else if($opened == 0){
    if($receivertime - $ar[4] >= 1){
    $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>Delivered</span>
    </div></div>";
    }else{
    $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>Sent</span>
    </div></div>";
    }
	  }

	  }else if($receiverid == $uid && $receiverdelete == 0){
	  $data .="<div id='$msgid' class='w3-panel partnermsg' style='padding:0;margin:0;'>
     <div  onmousedown='deleteMsg(\"$msgid\")' class='w3-left msg_txt w3-text-purple w3-white w3-card w3-padding w3-ripple w3-animate-zoom'style='max-width:250px;word-wrap: break-word;border-radius:10px 0px 15px 15px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
      <img src='chathide.jpg' class='w3-circle  w3-right' style='width:20px;height:20px;margin-left:8px;'>
      <p class='w3-tiny w3-left'style='margin:0;'>$time</p>
      </div></div>";
	  }
	  }else if($msg == '' && $avatarmsg != ''){
    $receivertime = $this->getdatet($receiverid);
	  if($senderid == $uid && $senderdelete == 0){
	  $data .="<div id='$msgid' class='w3-panel mymsg'>
    <div class='w3-animate-zoom w3-right'>
    <a style='text-decoration:none;' href='$avatarmsg'>
    <img src='chatplaceholder1.jpg'data-src='$avatarmsg'class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-round-xxlarge w3-image w3-block w3-ripple'style='width:150px;max-height:200px;'>
    </a>
    </div>
    <div class='w3-right' style='width:100%;'>  
    <b onmousedown ='deleteMsg(\"$msgid\")'class='w3-right w3-tiny w3-text-purple'style='margin-top:3px;'>Delete</b>
    <img src='chathide.jpg' class='w3-circle w3-right' style='width:15px;height:15px;margin-top:0;margin-left:10px;margin-top:3px;'>
    ";
	 if($opened == 1){
    $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-purple'style='margin-left:10px;margin-right:10px;'>seen</b>
    <b class='w3-right w3-tiny w3-text-purple'>$time</b>
    </div>
    </div>";
     }else if($opened == 0){
    if($receivertime - $ar[4] >= 1){
    $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-purple'style='margin-left:10px;margin-right:10px;'>Delivered</b>
    <b class='w3-right w3-tiny w3-text-purple'>$time</b>
    </div>        
    </div>";
    }else{
	 $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-purple'style='margin-left:10px;margin-right:10px;'>sent</b>
    <b class='w3-right w3-tiny w3-text-purple'>$time</b>
    </div>        
    </div>";
    }
	  }
	  }else if($receiverid == $uid && $receiverdelete == 0){
      $data .="<div id='$msgid' class='w3-panel'>
      <div class='w3-animate-zoom  w3-left'>
      <a style='text-decoration:none;' href='$avatarmsg'>
      <img src='chatplaceholder1.jpg' data-src='$avatarmsg' class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-image w3-block w3-ripple' style='width:150px;max-height:200px;'>
      </a>
      </div>

      <div class='w3-left' style='width:100%;'>
      <b class='w3-left w3-tiny w3-text-purple'>$time</b>
      <img src='chathide.jpg' class='w3-circle w3-left' style='width:15px;height:15px;margin-top:0;margin-left:10px;'>
      <span onmousedown ='deleteMsg(\"$msgid\")'class='w3-left w3-tiny w3-text-purple'style=''>Delete</span>
      <a href='$avatarmsg'download='oaumeetup/$avatarmsg' class='w3-tiny w3-text-purple w3-hover-text-green'>
      <i class='fa fa-download w3-tiny w3-text-purple w3-large'style='margin-left:10px;'></i>
      </a>
      </div>
      </div>";
	  }
	  }	   
	  }//for each loop
	  return array($data,$scrollid);
    }else{
    echo "Sorry could not get chat between you and user please refresh browser".$conn->error;
    $conn->rollback();
    exit();
    }
    }
    //public function to get anonymous chat ends here


   //public function to insert achat starts here
    public function insertachat($achat){
    $conn = $this->conn;
    $achat = $this->clean_input($achat);
    $frmsgdetails = $this->msgdetails;
    $uid = $this->getUid();
    $toseeid = $this->getToSeeProfilePicId();
    $nottoseeid = $this->getToNotSeeProfilePicId();
    $achatid = $this->getAchatId();
    $this->isacreatorblocked = $_SESSION["isacreatorblocked"];
    $this->isarecepientblocked = $_SESSION["isarecepientblocked"];
    $partnerid ='';
    $data = "";
	$isimagechat = 'false';
	$numc = 0;
    if(empty($achat) || $achat == ""){
    return "Failed your chat is empty or contains characters that are not accepted";
    }
    if(empty($uid) || empty($toseeid) || empty($nottoseeid) || empty($achatid)){
    return "Something went  wrong important values missing to continue please try again";
    }
    if($this->isacreatorblocked == "1" || $this->isarecepientblocked == "1"){
    return "Blocked";
    }else{
    $sql = "select id from chatcreate where chatid='$achatid' and creatorblocked ='1' or chatid='$achatid' and  recepientblocked = '1' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    return "Blocked";
    }
    }
    if($toseeid == $uid){
    $partnerid = $nottoseeid;
    }else if($nottoseeid == $uid){
    $partnerid = $toseeid;
    }else{
    echo "Something went wrong could not get anonymous chat partner details due to missing values";
    exit();
    }
    $conn->begin_transaction();
    $sql = "select receivermsg,numnewmsg,shortnewmsg,msgdetails from achat where achatid = '$achatid' limit 1  for update";
	$result =$conn->query($sql);
    if($result->num_rows == 1){
	$row = $result->fetch_assoc();
	$receivermsg = $row["receivermsg"];
	$numnewmsg = $row["numnewmsg"];
	$shortnewmsg = $row["shortnewmsg"];
	$msgdetails =  $row['msgdetails'];
	$msgdetails = str_replace('®®©','\ud',$msgdetails);
	if(empty($msgdetails)){
	$msgdetails = array();
	}else{
  $msgdetails = json_decode($msgdetails,true);
	}
  $time = time();
	//checking to see what type of msg it is starts here
	if(stripos($achat,"fchatphotomsg/") !== false && file_exists($achat)){
	$isimagechat = 'true';
	$array = array('',$achat,$uid,$partnerid,time(),'0','0','0',time());
	}else{
  $array = array($achat,'',$uid,$partnerid,time(),'0','0','0',time());
	}
  //checking to see what type of msg it is ends here
    $dbmsgid = md5(rand(0,7000).rand(0,4000));
    $msgdetails[$dbmsgid] = $array;
    $this->msgdetails=$_SESSION["am"]=$msgdetails = $this->updateArrayOpen($msgdetails);
    $dbmsgdetails = json_encode($msgdetails);
    $dbmsgdetails = str_replace(array('\ud','<','>'),array('®®©','',''),$dbmsgdetails);
    //insert Into Database Starts Here
    $dbmsgdetails= $conn->real_escape_string($dbmsgdetails);
    if($receivermsg == $uid){
    $numnewmsg = 0;
    }
    ++$numnewmsg;
    $achat = $conn->real_escape_string($achat);
    if(empty($shortnewmsg)){
    if($isimagechat == 'true'){
    $achat = "photo";
    }
    $sql2 = "update achat set receivermsg='$partnerid',numnewmsg='$numnewmsg',shortnewmsg='$achat',msgdetails='$dbmsgdetails',latestmsgtime ='$time' where achatid='$achatid' limit 1";
    }else{
    $sql2 = "update achat set receivermsg='$partnerid',numnewmsg='$numnewmsg',msgdetails='$dbmsgdetails',latestmsgtime='$time' where achatid='$achatid' limit 1";
    }
    if($conn->query($sql2) != "true"){
    $this->msgdetails =$_SESSION["am"]=$frmsgdetails;
    $error = $conn->error;
    $conn->rollback();
    return "Failed".$error;
    }else{
    $conn->commit();
    }
    $newmsgs= array_diff_key($msgdetails,$frmsgdetails);
    $oldmsgs = array_intersect_key($msgdetails,$frmsgdetails);
   //code to Get Update For Stale Messages Starts Here
    $oldarraytosend = array();
    foreach($oldmsgs as $value => $a){
    $oldchatid = $value;
    $oldarr = $a;
    $oldsenderid = $oldarr[2];
    $oldopened = $oldarr[7];
    if($oldsenderid == $uid && $oldopened == 1){
    array_push($oldarraytosend,$oldchatid);
    }
    }
    //code to Get Update For Stale Messages ends Here
    //to get new messages starts here
    $num = count($newmsgs);
    $index = $num -1;
    foreach($newmsgs as $key => $a){
	++$numc;
	$msgid = $key;
	$ar = $a;
	$msg  = nl2br(strip_tags (stripslashes($ar[0])));
	$avatarmsg = $ar[1];
    $senderid = $ar[2];
	$receiverid = $ar[3];
	$time = strftime("%a %b %d %Y @ %I:%M%p",$ar[4]);
	$senderdelete = $ar[5];
	$receiverdelete = $ar[6];
	$opened = $ar[7];
	  
    //code to get scroll to currrent scrollid
	if(empty($scrollid) && $numc < $index){
	if($receiverid  == $uid && $opened == "0" ){
	$scrollid = $msgid;  
	}else if($senderid == $uid && $opened == "0"){
	$scrollid = $msgid;
	}
	}else if(empty($scrollid) && $numc >= $index){
	$scrollid = $msgid;
	}
	//code to get Scroll ID Ends Here

	  
	  if($msg != '' && $avatarmsg == ''){
	  if($senderid == $uid && $senderdelete == 0){
	  $receivertime = $this->getdatet($receiverid);
    $data .="<div id='$msgid' class='mymsg w3-panel'style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-right w3-card msg_txt w3-purple w3-padding w3-animate w3-ripple  w3-animate-zoom w3-medium 'style='max-width:250px;word-wrap: break-word;border-radius: 15px 50px 30px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
     <p class='w3-tiny w3-left'style='margin:0;margin-left:5px;margin-right:10px;'>$time</p>
     <img src='chathide.jpg' class='w3-circle  w3-right' style='width:20px;height:20px;margin-left:8px;'>";
    if($opened == 1){
    $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>seen</span>
    </div></div>";
    }else if($opened == 0){
    if($receivertime - $ar[4] >= 1){
    $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>Delivered</span>
    </div></div>";
    }else{
    $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>Sent</span>
    </div></div>";
    }
    }
	  }else if($receiverid == $uid && $receiverdelete == 0){
	  $data .="<div id='$msgid' class='w3-panel partnermsg' style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-left msg_txt w3-text-purple w3-white w3-card w3-padding w3-ripple w3-animate-zoom'style='max-width:250px;word-wrap: break-word;border-radius:10px 0px 15px 15px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
     <img src='chathide.jpg' class='w3-circle  w3-right' style='width:20px;height:20px;margin-left:8px;'>
     <p class='w3-tiny w3-left'style='margin:0;'>$time</p>
      </div></div>";
	  }
	  }else if($msg == '' && $avatarmsg != ''){
    if($senderid == $uid && $senderdelete == 0){
    $receivertime = $this->getdatet($receiverid);
    $data .="<div id='$msgid' class='w3-panel mymsg'>
    <div class='w3-animate-zoom w3-right'>
    <a style='text-decoration:none;' href='$avatarmsg'>
    <img src='chatplaceholder1.jpg'data-src='$avatarmsg'class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-round-xxlarge w3-image w3-block w3-ripple'style='width:150px;max-height:200px;'>
    </a>
    </div>
    <div class='w3-right' style='width:100%;'>  
    <b onmousedown ='deleteMsg(\"$msgid\")'class='w3-right w3-tiny w3-text-purple'style='margin-top:3px;'>Delete</b>
    <img src='chathide.jpg' class='w3-circle w3-right' style='width:15px;height:15px;margin-top:0;margin-left:10px;margin-top:3px;'>
    ";
   if($opened == 1){
    $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-purple'style='margin-left:10px;margin-right:10px;'>seen</b>
    <b class='w3-right w3-tiny w3-text-purple'>$time</b>
    </div>
    </div>";
     }else if($opened == 0){
    if($receivertime - $ar[4] >= 1){
    $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-purple'style='margin-left:10px;margin-right:10px;'>Delivered</b>
    <b class='w3-right w3-tiny w3-text-purple'>$time</b>
    </div>        
    </div>";
    }else{
   $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-purple'style='margin-left:10px;margin-right:10px;'>sent</b>
    <b class='w3-right w3-tiny w3-text-purple'>$time</b>
    </div>        
    </div>";
    }
    }
    }else if($receiverid == $uid && $receiverdelete == 0){
      $data .="<div id='$msgid' class='w3-panel'>
      <div class='w3-animate-zoom  w3-left'>
      <a style='text-decoration:none;' href='$avatarmsg'>
      <img src='chatplaceholder1.jpg' data-src='$avatarmsg' class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-image w3-block w3-ripple' style='width:150px;max-height:200px;'>
      </a>
      </div>

      <div class='w3-left' style='width:100%;'>
      <b class='w3-left w3-tiny w3-text-purple'>$time</b>
      <img src='chathide.jpg' class='w3-circle w3-left' style='width:15px;height:15px;margin-top:0;margin-left:10px;'>
      <span onmousedown ='deleteMsg(\"$msgid\")'class='w3-left w3-tiny w3-text-purple'style=''>Delete</span>
      <a href='$avatarmsg'download='oaumeetup/$avatarmsg' class='w3-tiny w3-text-purple w3-hover-text-green'>
      <i class='fa fa-download w3-tiny w3-text-purple w3-large'style='margin-left:10px;'></i>
      </a>
      </div>
      </div>";
    }
	  }	   
	  }//for each loop
     //to get new Messages EndS here
    $oldmsgtosend = implode("©©©",$oldarraytosend);
    return $oldmsgtosend."®®®".$data."®®®".$scrollid;
    }else{
    $error = $conn->error;
    $conn->rollback();
    return "Failed could not access and insert anonymous chat between users please try again".$error;
    }
    }
    //public function to insert achat ends here

    //function to Get User Details starts Here
    public function getPartnerDetails(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $toseeid = $this->getToSeeProfilePicId();
    $nottoseeid = $this->getToNotSeeProfilePicId();
    if($toseeid == $uid){
    $partnerid = $nottoseeid;
    }else if($nottoseeid == $uid){
    $partnerid = $toseeid;
    } 
    $sql = "select username,gender,avatar,lastlogindate from oaumeetupusers where userid = '$partnerid' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $uname = $row["username"];
    $gender = $row["gender"];
    $avatar = $row["avatar"];
    $lastlogindate = $row["lastlogindate"];
    $timdiff =time() - $lastlogindate;
    if($timdiff <= 60){
    $lastlogindate = "Online";
    }else{
    $lastlogindate = strftime("%b %d %Y @ %I:%M%p",$lastlogindate);
    }
    if(empty($avatar) && $gender == "male"){
    $avatar= "maledefault.png";
    }else if(empty($avatar) && $gender == "female"){
    $avatar = "femaledefault.jpeg";
    }
    return array($uname,$avatar,$lastlogindate,$gender);
    }else{
    return "";
    }    
    }
    //function to get User DetaIls Ends Here

    //public function to update achat starts here
    public function updateachat(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $frmsgdetails = $this->msgdetails;
    $achatid = $this->getAchatId();
    $toseeid = $this->getToSeeProfilePicId();
    $this->isacreatorblocked = $_SESSION["isacreatorblocked"];
    $this->isarecepientblocked = $_SESSION["isarecepientblocked"];
    $scrollid = '';
    $data = "";
    $numc = 0;
    $nottoseeid = $this->getToNotSeeProfilePicId();
    if(empty($uid) || empty($toseeid) || empty($nottoseeid) || empty($achatid)){
    return "";
    }
    if($this->isacreatorblocked == "1" || $this->isarecepientblocked == "1"){
    return "";
    }
    $conn->begin_transaction();
    $sql = "select receivermsg,numnewmsg,shortnewmsg,msgdetails from achat where achatid = '$achatid' limit 1 for update";
    $result =$conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $receivermsg = $row["receivermsg"];
    $numnewmsg = $row["numnewmsg"];
    $shortnewmsg = $row["shortnewmsg"];
    $msgdetails =  $row['msgdetails'];

    if(empty($msgdetails)){
    return "";
    }else{
    $msgdetails = str_replace('®®©','\ud',$msgdetails);
    $msgdetails = json_decode($msgdetails,true);
    if(!is_array($msgdetails)){
    return "";
    }
    }
    $this->msgdetails=$_SESSION["am"]=$msgdetails = $this->updateArrayOpen($msgdetails);
    $dbmsgdetails = json_encode($msgdetails);
    $dbmsgdetails = str_replace(array('\ud','<','>'),array('®®©','',''),$dbmsgdetails);
    $time = time();
    //insert Into Database Starts Here
    $dbmsgdetails= $conn->real_escape_string($dbmsgdetails);
    if($receivermsg == $uid){
    $numnewmsg = 0;
    }
    ++$numnewmsg;
    if($receivermsg == $uid){
    $sql2 = "update achat set   receivermsg = '',numnewmsg='0',shortnewmsg ='',msgdetails = '$dbmsgdetails' where achatid= '$achatid' limit 1";
    }else{
    $sql2 = "update achat set msgdetails = '$dbmsgdetails' where achatid= '$achatid' limit 1";
    }
    if($conn->query($sql2) != "true"){
    $this->msgdetails =$_SESSION["am"]=$frmsgdetails;
    $error = $conn->error;
    $conn->rollback();
    return "Failed".$error;
    }else{
    $conn->commit();
    }
    /*echo count($msgdetails)."<br>";
    echo count($frmsgdetails);*/
    $newmsgs = array_diff_key($msgdetails,$frmsgdetails);
    $oldmsgs = array_intersect_key($msgdetails,$frmsgdetails);
    //print_r($newmsgs);

   //code to Get Update For Stale Messages Starts Here
    $oldarraytosend = array();
    foreach($oldmsgs as $value => $a){
    $oldchatid = $value;
    $oldarr = $a;
    $oldsenderid = $oldarr[2];
    $oldopened = $oldarr[7];
    if($oldsenderid == $uid && $oldopened == 1){
    array_push($oldarraytosend,$oldchatid);
    }
    }
    //code to Get Update For Stale Messages ends Here
    //to get new messages starts here
    $num = count($newmsgs);
    $index = $num -1;
    foreach($newmsgs as $key => $a){
    ++$numc;
    $msgid = $key;
    $ar = $a;
    $msg  = nl2br(strip_tags (stripslashes($ar[0])));
    $avatarmsg = $ar[1];
    $senderid = $ar[2];
    $receiverid = $ar[3];
    $time = strftime("%a %b %d %Y @ %I:%M%p",$ar[4]);
    $senderdelete = $ar[5];
    $receiverdelete = $ar[6];
    $opened = $ar[7];
      
    //code to get scroll to currrent scrollid
    if(empty($scrollid) && $numc < $index){
    if($receiverid  == $uid && $opened == "0" ){
    $scrollid = $msgid;  
    }else if($senderid == $uid && $opened == "0"){
    $scrollid = $msgid;
    }
    }else if(empty($scrollid) && $numc >= $index){
    $scrollid = $msgid;
    }
    //code to get Scroll ID Ends Here

      
      if($msg != '' && $avatarmsg == ''){
      if($senderid == $uid && $senderdelete == 0){
      $receivertime = $this->getdatet($receiverid);
      $data .="<div id='$msgid' class='mymsg w3-panel'style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-right w3-card msg_txt w3-purple w3-padding w3-animate w3-ripple  w3-animate-zoom w3-medium 'style='max-width:250px;word-wrap: break-word;border-radius: 15px 50px 30px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
     <p class='w3-tiny w3-left'style='margin:0;margin-left:5px;margin-right:10px;'>$time</p>
     <img src='chathide.jpg' class='w3-circle  w3-right' style='width:20px;height:20px;margin-left:8px;'>";
    if($opened == 1){
    $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>seen</span>
    </div></div>";
    }else if($opened == 0){
    if($receivertime - $ar[4] >= 1){
    $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>Delivered</span>
    </div></div>";
    }else{
    $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>Sent</span>
    </div></div>";
      }
      }
      }else if($receiverid == $uid && $receiverdelete == 0){
      $data .="<div id='$msgid' class='w3-panel partnermsg' style='padding:0;margin:0;'>
     <div  onmousedown='deleteMsg(\"$msgid\")' class='w3-left msg_txt w3-text-purple w3-white w3-card w3-padding w3-ripple w3-animate-zoom'style='max-width:250px;word-wrap: break-word;border-radius:10px 0px 15px 15px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
      <img src='chathide.jpg' class='w3-circle  w3-right' style='width:20px;height:20px;margin-left:8px;'>
      <p class='w3-tiny w3-left'style='margin:0;'>$time</p>
      </div></div>";
      }
      }else if($msg == '' && $avatarmsg != ''){
     if($senderid == $uid && $senderdelete == 0){
    $receivertime = $this->getdatet($receiverid);
    $data .="<div id='$msgid' class='w3-panel mymsg'>
    <div class='w3-animate-zoom w3-right'>
    <a style='text-decoration:none;' href='$avatarmsg'>
    <img src='chatplaceholder1.jpg'data-src='$avatarmsg'class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-round-xxlarge w3-image w3-block w3-ripple'style='width:150px;max-height:200px;'>
    </a>
    </div>
    <div class='w3-right' style='width:100%;'>  
    <b onmousedown ='deleteMsg(\"$msgid\")'class='w3-right w3-tiny w3-text-purple'style='margin-top:3px;'>Delete</b>
    <img src='chathide.jpg' class='w3-circle w3-right' style='width:15px;height:15px;margin-top:0;margin-left:10px;margin-top:3px;'>
    ";
   if($opened == 1){
    $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-purple'style='margin-left:10px;margin-right:10px;'>seen</b>
    <b class='w3-right w3-tiny w3-text-purple'>$time</b>
    </div>
    </div>";
     }else if($opened == 0){
   if($receivertime - $ar[4] >= 1){
    $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-purple'style='margin-left:10px;margin-right:10px;'>Delivered</b>
    <b class='w3-right w3-tiny w3-text-purple'>$time</b>
    </div>        
    </div>";
    }else{
   $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-purple'style='margin-left:10px;margin-right:10px;'>sent</b>
    <b class='w3-right w3-tiny w3-text-purple'>$time</b>
    </div>        
    </div>";
    }
    }
    }else if($receiverid == $uid && $receiverdelete == 0){
      $data .="<div id='$msgid' class='w3-panel'>
      <div class='w3-animate-zoom  w3-left'>
      <a style='text-decoration:none;' href='$avatarmsg'>
      <img src='chatplaceholder1.jpg' data-src='$avatarmsg' class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-image w3-block w3-ripple' style='width:150px;max-height:200px;'>
      </a>
      </div>

      <div class='w3-left' style='width:100%;'>
      <b class='w3-left w3-tiny w3-text-purple'>$time</b>
      <img src='chathide.jpg' class='w3-circle w3-left' style='width:15px;height:15px;margin-top:0;margin-left:10px;'>
      <span onmousedown ='deleteMsg(\"$msgid\")'class='w3-left w3-tiny w3-text-purple'style=''>Delete</span>
      <a href='$avatarmsg'download='oaumeetup/$avatarmsg' class='w3-tiny w3-text-purple w3-hover-text-green'>
      <i class='fa fa-download w3-tiny w3-text-purple w3-large'style='margin-left:10px;'></i>
      </a>
      </div>
      </div>";
      }
      }    
      }//for each loop
     //to get new Messages EndS here
    $oldmsgtosend = implode("©©©",$oldarraytosend);
    return $oldmsgtosend."®®®".$data."®®®".$scrollid;
    }else{
    $error = $conn->error;
    $conn->rollback();
    return "";
    }
    }
    //public function to update achat starts here

    //public function to delete a message starts here
    public function deleteMsg($msgid){
    $conn = $this->conn;
    $fallbackmsgdetails = $this->msgdetails;
    $uid = $this->getUid();
    $frmsgdetails = $this->msgdetails;
    $achatid = $this->getAchatId();
    $toseeid = $this->getToSeeProfilePicId();
    $nottoseeid = $this->getToNotSeeProfilePicId();
    if(empty($uid) || empty($toseeid) || empty($nottoseeid) || empty($achatid) || empty($msgid)){
    return "Failed could not delete message missing values to continue";
    }
    
    if(!array_key_exists($msgid,$frmsgdetails)){
    return "Failed sorry message to be deleted not found";
    }
    //remove message for user side starts here
    $senderid = $frmsgdetails[$msgid][2];
    $receiverid = $frmsgdetails[$msgid][3];
    if($uid == $senderid){
    $frmsgdetails[$msgid][5] = 1;
    }else if($uid == $receiverid){
    $frmsgdetails[$msgid][6] = 2;
    }else{
    return "Failed you are not involved in this achat and cannot modify messages";
    }
    //remove message for user side starts here
    $conn->begin_transaction();
    $sql = "select msgdetails from achat where achatid ='$achatid' limit 1 for update";
    $result = $conn->query($sql);
    if($result->num_rows  == 1){
    $row = $result->fetch_assoc();
    $msgdetails = $row["msgdetails"];
    if(empty($msgdetails)){
    return "Failed no message to delete";
    }
    $msgdetails = str_replace('®®©','\ud',$msgdetails);
    $msgdetails = json_decode($msgdetails,true);
    if(!is_array($msgdetails)){
    return "Failed sorry could not delete message please try again";
    }
    $halfnewmsgdetails = array_intersect_key($frmsgdetails,$msgdetails);
    $otherhalfnewmsgdetails =  array_diff_key($msgdetails,$frmsgdetails);
    if(!is_array($halfnewmsgdetails) || count($halfnewmsgdetails) < 0){
    return "Failed could not find message to delete";
    }
    $completenewmsgdetails = array_merge($halfnewmsgdetails,$otherhalfnewmsgdetails);
    $dbcompletenewmsgdetails = json_encode($completenewmsgdetails);
    $dbcompletenewmsgdetails = str_replace(array('\ud','<','>'),array('®®©','',''),$dbcompletenewmsgdetails);
    $dbcompletenewmsgdetails = $conn->real_escape_string($dbcompletenewmsgdetails);
    $time = time();
    $sql2 = "update achat set msgdetails = '$dbcompletenewmsgdetails',latestmsgtime ='$time' where achatid ='$achatid' limit 1";
    if($conn->query($sql2) == "true"){
    $this->msgdetails=$_SESSION["am"]=$completenewmsgdetails;
    $conn->commit();
    return "success";
    }else{
    $this->msgdetails=$_SESSION["am"]=$fallbackmsgdetails;
    $conn->rollback();
    $error = $conn->error;  
    return "Failed could not delete message at this time please try again".$error;
    }
    }else{
    $conn->rollback();
    $error = $conn->error; 
    return "Failed couldnot delete message missing values to continue d".$error;
    }
    }
    //public function to delete a message ends here

    //public function to block userchat starts here
    public function blockUser(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $achatid = $this->getAchatId();
    $toseeid = $this->getToSeeProfilePicId();
    $nottoseeid = $this->getToNotSeeProfilePicId();
    if(empty($uid) || empty($toseeid) || empty($nottoseeid) || empty($achatid)){
    return "Failed could not block chat missing values to continue";
    }
    
    if($toseeid == $uid){
    $sql = "update chatcreate set creatorblocked = '1' where chatid = '$achatid' limit 1";
    }else if($nottoseeid == $uid){
    $sql = "update chatcreate set recepientblocked = '1' where chatid = '$achatid' limit 1";
    }else{
    echo "Failed you are not invoved in this chat and do not have the authorization to modify its information";
    exit();
    }
    if($conn->query($sql) == "true"){
    $this->isacreatorblocked = $_SESSION["isacreatorblocked"] = "1";
    $this->isarecepientblocked = $_SESSION["isarecepientblocked"] = "1";    
    return "success";
    }else{
    return "Failed could not block chat at this time please try again".$conn->error;
    }
    }
    //public function to block user ends here

    //public function to unblock chat starts here
    public function unblockachat(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $achatid = $this->getAchatId();
    $toseeid = $this->getToSeeProfilePicId();
    $formerisacreatorblocked = $_SESSION["isacreatorblocked"];
    $formerisarecepientblocked  = $_SESSION["isarecepientblocked"];
    $nottoseeid = $this->getToNotSeeProfilePicId();
    if(empty($uid) || empty($toseeid) || empty($nottoseeid) || empty($achatid)){
    return "Failed could not unblock chat missing values to continue";
    }
    if($toseeid == $uid){
    $sql = "update chatcreate set creatorblocked = '0' where chatid = '$achatid' limit 1";
    }else if($nottoseeid == $uid){
    $sql = "update chatcreate set recepientblocked = '0' where chatid = '$achatid' limit 1";
    }else{
    echo "Failed you are not involved in this chat and do not have the authorization to modify its information";
    exit();
    }
    if($conn->query($sql) == "true"){
    $this->isacreatorblocked = $_SESSION["isacreatorblocked"] = "0";
    $this->isarecepientblocked = $_SESSION["isarecepientblocked"] = "0";    
    return "success";
    }else{
    return "Failed could not unblock chat at this time please try again".$conn->error;
    }
    }
    //public function to unblock chat ends here




     
    //public function  clear all messages currently in chat starts here
    public function clearchat(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $achatid = $this->getAchatId();
    $toseeid = $this->getToSeeProfilePicId();
    $nottoseeid = $this->getToNotSeeProfilePicId();
    $frmsgdetails = $this->msgdetails;
    if(empty($uid) || empty($toseeid) || empty($nottoseeid) || empty($achatid)){
    return "Failed could not clear chat messages missing values to continue";
    }
    if(empty($frmsgdetails) || count($frmsgdetails) == 0){
    return "Failed no new chat to clear";
    }
    
    $this->msgdetails = $_SESSION["am"] = $this->setAllDelete($frmsgdetails); 
    $conn->begin_transaction();
    $sql = "select msgdetails from achat where achatid = '$achatid' limit 1 for update";
    $result =$conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $msgdetails =  $row['msgdetails'];
    $msgdetails = str_replace('®®©','\ud',$msgdetails);
    $msgdetails = json_decode($msgdetails,true);
    if(empty($msgdetails) || count($msgdetails) == 0 || !is_array($msgdetails)){
    return "Failed could not find chat to clear";
    }
    $halfnewmsgdetails = array_intersect_key($this->msgdetails,$msgdetails);
    $otherhalfnewmsgdetails =  array_diff_key($msgdetails,$this->msgdetails);
    if(!is_array($halfnewmsgdetails) || count($halfnewmsgdetails) < 0){
    return "Failed could not find messages to delete";
    }
    $completenewmsgdetails = array_merge($halfnewmsgdetails,$otherhalfnewmsgdetails);
    $completenewmsgdetails = json_encode($completenewmsgdetails);
    $completenewmsgdetails = str_replace(array('\ud','<','>'),array('®®©','',''),$completenewmsgdetails);
    $completenewmsgdetails = $conn->real_escape_string($completenewmsgdetails);
    $sql2 = "update achat set msgdetails = '$completenewmsgdetails' where achatid ='$achatid' limit 1";
    if($conn->query($sql2) == "true"){
    $conn->commit();
    return "success";
    }else{
    $this->msgdetails = $_SESSION["am"] = $frmsgdetails;
    $conn->rollback();
    $error = $conn->error;  
    return "Failed could not delete messages at this time please try again".$error;
    }

    }else{
    $err = $conn->error;
    return "Failed could not clear messages at this time please try again".$err;
    $conn->rollback();
    }
     
    }
   //public function  clear all messages currently in chat ends here

    //public function to update all messages to all messages to delete starts delete
    public function setAllDelete($data){
    global $uid;
    $uid = $this->getUid();
    function myfunction($v){
    $a = $v;
    global $uid;
    if($a["2"] == $uid){
    $a["5"] = 1;
    }else if($a["3"] == $uid){
    $a["6"] = 1;
    }
    return $a;
    }
    if(!empty($data) && is_array($data)){
    return array_map("myfunction",$data);
    }//if statement
    return array();
    }
   //public function to update all messages to all messages to delete ends deletes

   //public function to get last seen of current user starts here
    public function getdatet($uid){
    $uid = $this->clean_input($uid);
    $conn = $this->conn;
    if(empty($uid)){
    return "";
    }
    $sql = "select lastlogindate from oaumeetupusers where userid = '$uid' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $lastlogindate = $row["lastlogindate"];
    return $lastlogindate;
    }else{
    return "";
    }
    }
   //public function to get last seen of user ends here

}
?>