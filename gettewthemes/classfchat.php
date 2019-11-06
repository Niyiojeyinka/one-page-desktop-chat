<?php
session_start();
require_once "classmeetupvalidate.php";
class fchat extends meetupvalidate{
	private $chatid;
	private $uid;
	private $chatpartnerid;
	private $msgdetails = array();
    public $iscreatorchat = false;
    public $iscreatorblocked = 0;
    public $isrecepientblocked = 0;

	
   function __construct(){
	$this->createConnection();
	if(isset($_SESSION["userid"])){
	$this->setUid($_SESSION["userid"]);
	}
	if(isset($_SESSION["chatid"]) && isset($_SESSION["partnerid"])){
	 $this->setPatnerId($_SESSION["partnerid"]);
	$this->setChatId($_SESSION["chatid"]);
	}
    if(isset($_SESSION["iscreatorchat"])){
    $this->iscreatorchat = $_SESSION["iscreatorchat"];
    }
    
    if(isset($_SESSION["iscreatorblocked"])){
    $this->iscreatorblocked = $_SESSION["iscreatorblocked"];
    }
    if(isset($_SESSION["isrecepientblocked"])){
    $this->isrecepientblocked = $_SESSION["isrecepientblocked"];
    }

    if(isset($_SESSION["m"])){
	$this->msgdetails=$_SESSION["m"];
    }
   
   }//closing Function Construct 

    //settters and getters
    public function setChatId($data){
	 $this->chatid = $this->clean_input($data);
    }
    public function getChatId(){
	 return $this->chatid;
    }
    public function setUid($data){
	 $this->uid = $this->clean_input($data);
    }
    public function getUid(){
	 return $this->uid;
   }
   
    public function setPatnerId($data){
	  $this->chatpartnerid = $this->clean_input($data);
    }
    public function getPatnerId(){
	 return $this->chatpartnerid;
    }
    
     //public function for confirm  chat
    public function confirmChat($chatid){
	 $conn = $this->conn;
	 $uid = $this->getUid();
	 $chatid = $this->clean_input($chatid);
	 if(empty($chatid) || $chatid == ""){
	 echo "chat doesnot exists";
	 exit();
	 }
	$sql = "select creatorid,recepientid,creatorblocked,recepientblocked from chatcreate where creatorid='$uid' and type='0' and chatid ='$chatid' or recepientid = '$uid' and type='0' and chatid ='$chatid' limit 1";

	$sql1 = "select id  from fchat where chatid='$chatid' limit 1";
	$result = $conn->query($sql);
	if($result->num_rows != 1){
	echo "chat doesnot exists";
	exit();
	}else{
	$row = $result->fetch_assoc();
	$creatorid = $row["creatorid"];
	$recepientid = $row["recepientid"];
	$creatorblocked = $row["creatorblocked"];
	$recepientblocked = $row["recepientblocked"];
	$result1 = $conn->query($sql1);
	if($result1->num_rows  != 1){
	$sql2= "insert into fchat (chatid,creatorid,recepientid) 
	values ('$chatid','$creatorid','$recepientid')";

	if($conn->query($sql2) == "true"){
	$this->setChatId($chatid);
	if($creatorid == $uid){
	$this->setPatnerId($recepientid);
	$this->iscreatorchat = $_SESSION["iscreatorchat"] = "true";
	$_SESSION['chatid'] = $chatid;
	$_SESSION['partnerid'] = $recepientid;
	}else if($recepientid == $uid){
	$this->setPatnerId($creatorid);
	$_SESSION['chatid'] = $chatid;
	$_SESSION['partnerid'] = $creatorid;
	$this->iscreatorchat = $_SESSION["iscreatorchat"] = false;
	}
	}	
	//to check if chat has being blocked starts here
    if($creatorblocked == "1"){
    $this->iscreatorblocked = $_SESSION["iscreatorblocked"] = "1";
    }else{
    $this->iscreatorblocked = $_SESSION["iscreatorblocked"] = "0";
    }
    if($recepientblocked == "1"){
    $this->isrecepientblocked = $_SESSION["isrecepientblocked"] = "1";
    }else{
    $this->isrecepientblocked = $_SESSION["isrecepientblocked"] = "0";
    }
    //to check if chat has being blocked ends here	
			
	}else{
	$this->setChatId($chatid);
	if($creatorid == $uid){
	$this->setPatnerId($recepientid);
	$this->iscreatorchat = $_SESSION["iscreatorchat"] = "true";
	$_SESSION['chatid'] = $chatid;
	$_SESSION['partnerid'] = $recepientid;
	}else if($recepientid == $uid){
	$this->setPatnerId($creatorid);
	$_SESSION['chatid'] = $chatid;
	$_SESSION['partnerid'] = $creatorid;
	$this->iscreatorchat = $_SESSION["iscreatorchat"] = false;
	}
    //to check if chat has being blocked starts here
    if($creatorblocked == "1"){
    $this->iscreatorblocked = $_SESSION["iscreatorblocked"] = "1";
    }else{
    $this->iscreatorblocked = $_SESSION["iscreatorblocked"] = "0";
    }
    if($recepientblocked == "1"){
    $this->isrecepientblocked = $_SESSION["isrecepientblocked"] = "1";
    }else{
    $this->isrecepientblocked = $_SESSION["isrecepientblocked"] = "0";
    }
    //to check if chat has being blocked ends here	
	}
	}
   }//function closing braces 

     //function to handle  getting of chat starts here not going To BE Called if an ajax request
     public function getChat(){
	  $conn = $this->conn;
	  $uid = $this->getUid();
	  $chatid = $this->getChatId();
	  $patnerid = $this->getPatnerId();
	  $chatid = $this->getChatId();
      $data = ''; 
      $scrollid = '';
      $numc = 0;
      
	 $sql = "select receivermsg, numnewmsg,msgdetails from fchat where chatid='$chatid' limit 1";
     $result = $conn->query($sql);
     if($result->num_rows == 1)    {
	 if($row = $result->fetch_assoc()){
	 $receivermsg = $row["receivermsg"];
	 $numnewmsg = $row["numnewmsg"];
	 $msgdetails =  $row['msgdetails'];
	 } //fwct_assoc()

	  if($msgdetails == "" || empty($msgdetails)){
	  $this->msgdetails = $_SESSION["m"]= array();
	  return "";
	  }
	  $msgdetails = str_replace('®®©','\ud',$msgdetails);
	  $msgdetails = json_decode($msgdetails,true);
      $this->msgdetails = $_SESSION["m"]=$msgdetails = $this->updateArrayOpen($msgdetails);
      $msgdetails = json_encode($msgdetails);
      $msgdetails = str_replace('\ud','®®©',$msgdetails);
      $msgdetails= $conn->real_escape_string($msgdetails);
      $time = time();
      if($receivermsg == $uid){
      $sql2 = "update fchat set receivermsg = '',numnewmsg='0',shortnewmsg ='',msgdetails = '$msgdetails' where chatid= '$chatid' limit 1";
      }else{
      $sql2 = "update fchat set msgdetails = '$msgdetails' where chatid= '$chatid' limit 1";
      }
      $conn->query($sql2);
      $msgdetails = $this->msgdetails;
      /*print_r($msgdetails);
      exit();*/
      $msgdetails = str_replace(array('®®©','<','>'),array('\ud','',''),$msgdetails);
	  $num = count($msgdetails);
	  $index = $num -1;
	  foreach($msgdetails as $key => $a){
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
	  $index = $num -1;
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
	  $receiverlastseen = $this->getdatet($receiverid);
	  $data .="<div id='$msgid' class='mymsg w3-panel'style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-right w3-card msg_txt w3-blue w3-padding w3-animate w3-ripple  w3-animate-zoom w3-medium 'style='max-width:250px;word-wrap: break-word;border-radius: 15px 50px 30px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
     <p class='w3-tiny w3-left'style='margin:0;margin-left:5px;margin-right:10px;'>$time</p>";
  
	  if($opened == 1){
      $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>seen</span>
      </div></div>";
      }else if($opened == 0){
	  if($receiverlastseen - $ar[4] >= 1){
      $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>Delivered</span>
      </div></div>";
      }else{
      $data .="<span id='isseen$msgid' class='w3-tiny w3-right'>Sent</span>
      </div></div>";
      }
      }
	  }else if($receiverid == $uid && $receiverdelete == 0){
	  $data .="<div id='$msgid' class='w3-panel partnermsg' style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-left msg_txt w3-text-blue w3-white w3-card w3-padding w3-ripple w3-animate-zoom'style='max-width:250px;word-wrap: break-word;border-radius:10px 0px 15px 15px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
      <p class='w3-tiny w3-left'style='margin:0;'>$time</p>
      </div></div>";
	  }



	  }else if($msg == '' && $avatarmsg != ''){

	  if($senderid == $uid && $senderdelete == 0){
	  $receiverlastseen = $this->getdatet($receiverid);
	  $data .="<div id='$msgid' class='w3-panel mymsg'>
    <div class='w3-animate-zoom w3-right'>
    <a style='text-decoration:none;' href='$avatarmsg'>
    <img src='chatplaceholder1.jpg'data-src='$avatarmsg'class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-round-xxlarge w3-image w3-block w3-ripple'style='width:150px;max-height:200px;'>
    </a>
    </div>
    <div class='w3-right' style='width:100%;'>  
    <b onmousedown ='deleteMsg(\"$msgid\")'class='w3-right w3-small w3-text-blue w3-bold'style='margin-top:3px;'>Delete</b>";
	 if($opened == 1){
    $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-blue'style='margin-left:10px;margin-right:10px;'>seen</b>
    <b class='w3-right w3-tiny w3-text-blue'>$time</b>
    </div>
    </div>";
     }else if($opened == 0){
     if($receiverlastseen - $ar[4] >= 1){
     $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-blue'style='margin-left:10px;margin-right:10px;'>Delivered</b>
    <b class='w3-right w3-tiny w3-text-blue'>$time</b>
    </div>
    </div>";
     }else{
     $data .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-blue'style='margin-left:10px;margin-right:10px;'>sent</b>
     <b class='w3-right w3-tiny w3-text-blue'>$time</b>
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
      <b class='w3-left w3-tiny w3-text-blue'>$time</b>
      <b onmousedown ='deleteMsg(\"$msgid\")'class='w3-left w3-small w3-text-blue'style='margin-left:3px;'>Delete</b>
      <a href='$avatarmsg'download='oaumeetup/$avatarmsg' class='w3-tiny w3-text-blue w3-hover-text-green'>
      <i class='fa fa-download w3-tiny w3-text-blue w3-large'style='margin-left:10px;'></i>
      </a>
      </div>
      </div>";
	  }
	  
		
	  }	   
	  }//for each loop
	  return array($data,$scrollid);
	  } else{
	echo "<div>Something went Wrong could not get chat please refresh browser again</div>";
	exit();
	  }
      }
       
      //public function  to insert chat starts here
       public function insertChat($data){
	     $conn = $this->conn;
	     $uid = $this->getUid();
	     $frmsgdetails = $this->msgdetails;
	     $scrollid = "";
	     $partnerid = $this->getPatnerId();
	     $ndata = "";
	     $isimagechat = 'false';
	     $this->iscreatorblocked = $_SESSION["iscreatorblocked"];
         $this->isrecepientblocked = $_SESSION["isrecepientblocked"];
	     $numc = 0;
	     $chatid = $this->getChatId();
	     $time = "";
	     $chat = $data;
	     if($chat == '' || empty($chat)){
		 return 'Failed sorry your chat contains characters that are not accepted';
		 }
         if($this->iscreatorblocked == "1" || $this->isrecepientblocked == "1"){
         return "Blocked";
         }else{
         $sql = "select id from chatcreate where chatid='$chatid' and creatorblocked ='1' or chatid='$chatid' and  recepientblocked = '1' limit 1";
         $result = $conn->query($sql);
         if($result->num_rows == 1){
         return "Blocked";
         }
         }

		  $sql = "select receivermsg,numnewmsg,shortnewmsg,msgdetails from fchat where chatid = '$chatid' limit 1";
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
	      //checking to see what type of msg it is starts here
	      $time = time();
	      if(stripos($chat,"fchatphotomsg/") !== false && file_exists($chat)){
	      $isimagechat = 'true';
	      $array = array('',$chat,$uid,$partnerid,time(),'0','0','0',time());
	      }else{
          $array = array($chat,'',$uid,$partnerid,time(),'0','0','0',time());
	      }
	      
          //checking to see what type of msg it is ends here
          $dbmsgid = md5(rand(0,7000).rand(0,4000));
          $msgdetails[$dbmsgid] = $array;
          $this->msgdetails=$_SESSION["m"]=$msgdetails = $this->updateArrayOpen($msgdetails);
        

          $dbmsgdetails = json_encode($msgdetails);
          $dbmsgdetails = str_replace(array('\ud','<','>'),array('®®©','',''),$dbmsgdetails);
          
          //insert Into Database Starts Here
          $dbmsgdetails= $conn->real_escape_string($dbmsgdetails);
          if($receivermsg == $uid){
          $numnewmsg = 0;
          }
          ++$numnewmsg;
          $chat = $conn->real_escape_string($chat);
          if(empty($shortnewmsg)){
          if($isimagechat == 'true'){
          $chat = "photo";
          }
          $sql2 = "update fchat set receivermsg='$partnerid',numnewmsg='$numnewmsg',shortnewmsg='$chat',msgdetails='$dbmsgdetails',latestmsgtime ='$time' where chatid='$chatid' limit 1";
         }else{
          $sql2 = "update fchat set receivermsg='$partnerid',numnewmsg='$numnewmsg',msgdetails='$dbmsgdetails',latestmsgtime ='$time' where chatid='$chatid' limit 1";
         }

          
       if($conn->query($sql2) != "true"){
       $this->msgdetails =$_SESSION["m"]=$frmsgdetails;
       return "Failed".$conn->error;
          }
        //insert into Database Ends Here

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
	  $receiverlastseen = $this->getdatet($receiverid);
	  $ndata .="<div id='$msgid' class='mymsg w3-panel'style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-right w3-card msg_txt w3-blue w3-padding w3-animate w3-ripple  w3-animate-zoom w3-medium 'style='max-width:250px;word-wrap: break-word;border-radius: 15px 50px 30px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
     <p class='w3-tiny w3-left'style='margin:0;margin-left:5px;margin-right:10px;'>$time</p>";
      if($opened == 1){
      $ndata .="<span id='isseen$msgid' class='w3-tiny w3-right'>seen</span>
      </div></div>";
      }else if($opened == 0){
	  if($receiverlastseen - $ar[4] >= 1){
      $ndata .="<span id='isseen$msgid' class='w3-tiny w3-right'>Delivered</span>
      </div></div>";
      }else{
      $ndata .="<span id='isseen$msgid' class='w3-tiny w3-right'>Sent</span>
      </div></div>";
      }
      }
	  }else if($receiverid == $uid && $receiverdelete == 0){
	  $ndata .="<div id='$msgid' class='w3-panel partnermsg' style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-left msg_txt w3-text-blue w3-white w3-card w3-padding w3-ripple w3-animate-zoom'style='max-width:250px;word-wrap: break-word;border-radius:10px 0px 15px 15px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
      <p class='w3-tiny w3-left'style='margin:0;'>$time</p></div></div>";
	  }
	  }else if($msg == '' && $avatarmsg != ''){

	    if($senderid == $uid && $senderdelete == 0){
	   $receiverlastseen = $this->getdatet($receiverid);
	  $ndata .="<div id='$msgid' class='w3-panel mymsg'>
    <div class='w3-animate-zoom w3-right'>
    <a style='text-decoration:none;' href='$avatarmsg'>
    <img src='chatplaceholder1.jpg'data-src='$avatarmsg'class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-round-xxlarge w3-image w3-block w3-ripple'style='width:150px;max-height:200px;'>
    </a>
    </div>
    <div class='w3-right' style='width:100%;'>  
    <b onmousedown ='deleteMsg(\"$msgid\")'class='w3-right w3-small w3-text-blue w3-bold'style='margin-top:3px;'>Delete</b>";
	 if($opened == 1){
    $ndata .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-blue'style='margin-left:10px;margin-right:10px;'>seen</b>
    <b class='w3-right w3-tiny w3-text-blue'>$time</b>
    </div>
    </div>";
     }else if($opened == 0){
	if($receiverlastseen - $ar[4] >= 1){
     $ndata .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-blue'style='margin-left:10px;margin-right:10px;'>Delivered</b>
    <b class='w3-right w3-tiny w3-text-blue'>$time</b>
    </div>
    </div>";
     }else{
     $ndata .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-blue'style='margin-left:10px;margin-right:10px;'>sent</b>
     <b class='w3-right w3-tiny w3-text-blue'>$time</b>
     </div>
     </div>";
     }
	 }

	  }else if($receiverid == $uid && $receiverdelete == 0){
      $ndata .="<div id='$msgid' class='w3-panel'>
      <div class='w3-animate-zoom  w3-left'>
      <a style='text-decoration:none;' href='$avatarmsg'>
      <img src='chatplaceholder1.jpg' data-src='$avatarmsg' class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-image w3-block w3-ripple' style='width:150px;max-height:200px;'>
      </a>
      </div>
      <div class='w3-left' style='width:100%;'>
      <b class='w3-left w3-tiny w3-text-blue'>$time</b>
      <b onmousedown ='deleteMsg(\"$msgid\")'class='w3-left w3-small w3-text-blue'style='margin-left:3px;'>Delete</b>
      <a href='$avatarmsg'download='oaumeetup/$avatarmsg' class='w3-tiny w3-text-blue w3-hover-text-green'>
      <i class='fa fa-download w3-tiny w3-text-blue w3-large'style='margin-left:10px;'></i>
      </a>
      </div>
      </div>";
	  }
	  }	   
	  }//for each loop
     //to get new Messages EndS here
     $oldmsgtosend = implode("©©©",$oldarraytosend);
     return $oldmsgtosend."®®®".$ndata."®®®".$scrollid;
     
		  }else{
		  return "Failed".$conn->error;
		  }  
        }
        
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
    
    
    
    
    //public function To Update Chat Starts Here
    public function updateChat(){
    $conn = $this->conn;
    $uid = $this->getuid();
    $partnerid = $this->getPatnerId();
    $frmsgdetails = $this->msgdetails;
    $chatid = $this->getChatId();
    $scrollid = "";
    $ndata = "";
    $numc = 0;
    if(empty($chatid)){
    return "";
    }
    $sql = "select receivermsg,numnewmsg,shortnewmsg,msgdetails from fchat where chatid = '$chatid' limit 1";
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
    $this->msgdetails=$_SESSION["m"]=$msgdetails = $this->updateArrayOpen($msgdetails);
    $dbmsgdetails = json_encode($msgdetails);
    $dbmsgdetails = str_replace(array('\ud','<','>'),array('®®©','',''),$dbmsgdetails);
    //insert Into Database Starts Here
    $dbmsgdetails= $conn->real_escape_string($dbmsgdetails);
    if($receivermsg == $uid){
    $numnewmsg = 0;
    }
    ++$numnewmsg;
    if($receivermsg == $uid){
    $sql2 = "update fchat set   receivermsg = '',numnewmsg='0',shortnewmsg ='',msgdetails = '$dbmsgdetails' where chatid= '$chatid' limit 1";
    }else{
    $sql2 = "update fchat set msgdetails = '$dbmsgdetails'where chatid= '$chatid' limit 1";
    }

          
          if($conn->query($sql2) != "true"){
       $this->msgdetails =$_SESSION["m"]=$frmsgdetails;
       return "Failed".$conn->error;
          }
//insert into Database Ends Here
        $newmsgs= array_diff_key($msgdetails,$frmsgdetails);
        $oldmsgs = array_intersect_key($msgdetails,$frmsgdetails);
        
        //code to Get Update For Stale Messages Starts Here
        if(count($oldmsgs) > 0){
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
	  $receiverlastseen = $this->getdatet($receiverid);
	  $ndata .="<div id='$msgid' class='mymsg w3-panel'style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-right w3-card msg_txt w3-blue w3-padding w3-animate w3-ripple  w3-animate-zoom w3-medium 'style='max-width:250px;word-wrap: break-word;border-radius: 15px 50px 30px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
     <p class='w3-tiny w3-left'style='margin:0;margin-left:5px;margin-right:10px;'>$time</p>";
  
	  if($opened == 1){
      $ndata .="<span id='isseen$msgid' class='w3-tiny w3-right'>seen</span>
      </div></div>";
      }else if($opened == 0){
	  if($receiverlastseen - $ar[4] >= 1){
      $ndata .="<span id='isseen$msgid' class='w3-tiny w3-right'>Delivered</span>
      </div></div>";
      }else{
      $ndata .="<span id='isseen$msgid' class='w3-tiny w3-right'>Sent</span>
      </div></div>";
      }
      }
	  }else if($receiverid == $uid && $receiverdelete == 0){
	  $ndata .="<div id='$msgid' class='w3-panel partnermsg' style='padding:0;margin:0;'>
     <div onmousedown='deleteMsg(\"$msgid\")' class='w3-left msg_txt w3-text-blue w3-white w3-card w3-padding w3-ripple w3-animate-zoom'style='max-width:250px;word-wrap: break-word;border-radius:10px 0px 15px 15px;margin: 5px;font-size:16px;'>
     <p class=''style='margin:2px;'>$msg</p>
      <p class='w3-tiny w3-left'style='margin:0;'>$time</p></div></div>";
	  }
	  }else if($msg == '' && $avatarmsg != ''){
	    if($senderid == $uid && $senderdelete == 0){
	    $receiverlastseen = $this->getdatet($receiverid);
	  $ndata .="<div id='$msgid' class='w3-panel mymsg'>
    <div class='w3-animate-zoom w3-right'>
    <a style='text-decoration:none;' href='$avatarmsg'>
    <img src='chatplaceholder1.jpg'data-src='$avatarmsg'class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-round-xxlarge w3-image w3-block w3-ripple'style='width:150px;max-height:200px;'>
    </a>
    </div>
    <div class='w3-right' style='width:100%;'>  
    <b onmousedown ='deleteMsg(\"$msgid\")'class='w3-right w3-small w3-text-blue w3-bold'style='margin-top:3px;'>Delete</b>";
	 if($opened == 1){
    $ndata .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-blue'style='margin-left:10px;margin-right:10px;'>seen</b>
    <b class='w3-right w3-tiny w3-text-blue'>$time</b>
    </div>
    </div>";
     }else if($opened == 0){
	 if($receiverlastseen - $ar[4] >= 1){
     $ndata .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-blue'style='margin-left:10px;margin-right:10px;'>Delivered</b>
     <b class='w3-right w3-tiny w3-text-blue'>$time</b>
     </div>
     </div>";
     }else{
     $ndata .="<b id='isseen$msgid'class='w3-tiny w3-right w3-text-blue'style='margin-left:10px;margin-right:10px;'>sent</b>
     <b class='w3-right w3-tiny w3-text-blue'>$time</b>
     </div>
     </div>";
      }
	  }else if($receiverid == $uid && $receiverdelete == 0){
      $ndata .="<div id='$msgid' class='w3-panel'>
      <div class='w3-animate-zoom  w3-left'>
      <a style='text-decoration:none;' href='$avatarmsg'>
      <img src='chatplaceholder1.jpg' data-src='$avatarmsg' class='w3-round-xxlarge lazyload w3-animate-zoom w3-card-4 w3-image w3-block w3-ripple' style='width:150px;max-height:200px;'>
      </a>
      </div>
      <div class='w3-left' style='width:100%;'>
      <b class='w3-left w3-tiny w3-text-blue'>$time</b>
      <b onmousedown ='deleteMsg(\"$msgid\")'class='w3-left w3-small w3-text-blue'style='margin-left:3px;'>Delete</b>
      <a href='$avatarmsg'download='oaumeetup/$avatarmsg' class='w3-tiny w3-text-blue w3-hover-text-green'>
      <i class='fa fa-download w3-tiny w3-text-blue w3-large'style='margin-left:10px;'></i>
      </a>
      </div>
      </div>";
	  }
	  }
	  }  
	  }//for each loop
     //to get new Messages EndS here
     $oldmsgtosend = implode("©©©",$oldarraytosend);
     return $oldmsgtosend."®®®".$ndata."®®®".$scrollid;
     
		  }else{
		  return "Failed".$conn->error;
		  }
    
    }
    //public Function To UPdate Chat Ends Here
    
    //function to Get User Details starts Here
    public function getPartnerDetails(){
    $conn = $this->conn;
    $partnerid = $this->getPatnerId(); 
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

    //public function to delete a message starts here
    public function deleteMsg($msgid){
    $conn = $this->conn;
    $fallbackmsgdetails = $this->msgdetails;
    $frmsgdetails = $this->msgdetails;
    $chatid = $this->getChatId();
    $partnerid = $this->getPatnerId();
    $uid = $this->getUid();
    if(empty($msgid) || empty($partnerid) || empty($chatid)){
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
	return "Failed you are not involved in this chat and cannot modify messages";
	}
	//remove message for user side starts here
    $sql = "select msgdetails from fchat where chatid ='$chatid' limit 1";
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
    $sql2 = "update fchat set msgdetails = '$dbcompletenewmsgdetails' where chatid ='$chatid' limit 1";
    if($conn->query($sql2) == "true"){
    $this->msgdetails=$_SESSION["m"]=$completenewmsgdetails;
    return "success";
    }else{
    $this->msgdetails=$_SESSION["m"]=$fallbackmsgdetails;	
    return "Failed could not delete message at this time please try again".$conn->error;
    }
    }else{
    return "Failed couldnot delete message missing values to continue d".$conn->error;
    }
    }
    //public function to delete a message ends here

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
   
   //public function  clear all messages currently in chat starts here
    public function clearchat(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $chatid = $this->getChatId();
    $partnerid = $this->getPatnerId();
    $frmsgdetails = $this->msgdetails;
    if(empty($uid) || empty($chatid) || empty($partnerid)){
    return "Failed could not clear chat messages missing values to continue";
    }
    if(empty($frmsgdetails) || count($frmsgdetails) == 0){
    return "Failed no new chat to clear";
    }
    
    $this->msgdetails = $_SESSION["am"] = $this->setAllDelete($frmsgdetails); 
    //$conn->begin_transaction();
    $sql = "select msgdetails from fchat where chatid = '$chatid' limit 1";
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
    $sql2 = "update fchat set msgdetails = '$completenewmsgdetails' where chatid ='$chatid' limit 1";
    if($conn->query($sql2) == "true"){
    return "success";
    }else{
    $this->msgdetails = $_SESSION["am"] = $frmsgdetails;
    $error = $conn->error;  
    return "Failed could not delete messages at this time please try again".$error;
    }

    }else{
    $err = $conn->error;
    return "Failed could not clear messages at this time please try again".$err;
    }
     
    }
   //public function  clear all messages currently in chat ends here
   
   //public function to block userchat starts here
    public function blockChat(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $chatid = $this->getChatId();
    $formeriscreatorblocked = $_SESSION["iscreatorblocked"];
    $formerisrecepientblocked = $_SESSION["isrecepientblocked"];
    $partnerid = $this->getPatnerId();
    if(empty($uid) || empty($partnerid) || empty($chatid)){
    return "Failed could not block chat missing values to continue";
    }
    if($this->iscreatorchat == "true"){
    $sql = "update chatcreate set creatorblocked = '1' where chatid = '$chatid' limit 1";
    $this->iscreatorblocked = $_SESSION["iscreatorblocked"] = "1";
    }else if($this->iscreatorchat != "true"){
    $sql = "update chatcreate set recepientblocked = '1' where chatid = '$chatid' limit 1";
    $this->isrecepientblocked = $_SESSION["isrecepientblocked"] = "1";    
    }else{
    echo "Failed you are not invoved in this chat and do not have the authorization to modify its information";
    exit();
    }
    if($conn->query($sql) == "true"){
    return "success";
    }else{
    $this->iscreatorblocked = $_SESSION["iscreatorblocked"] = $formeriscreatorblocked;
    $this->isrecepientblocked = $_SESSION["isrecepientblocked"] = $formerisrecepientblocked;    
    return "Failed could not block chat at this time please try again".$conn->error;
    }
    }
    //public function to block user ends here

    //public function to unblock chat starts here
    public function unblockchat(){
    $conn = $this->conn;
    $uid = $this->getUid();
    $chatid = $this->getChatId();
    $formeriscreatorblocked = $_SESSION["iscreatorblocked"];
    $formerisrecepientblocked = $_SESSION["isrecepientblocked"];
    $partnerid = $this->getPatnerId();
    if(empty($uid) || empty($partnerid) || empty($chatid)){
    return "Failed could not block chat missing values to continue";
    }
    if($this->iscreatorchat == "true"){
    $sql = "update chatcreate set creatorblocked = '0' where chatid = '$chatid' limit 1";
    $this->iscreatorblocked = $_SESSION["iscreatorblocked"] = "0";
    }else if($this->iscreatorchat != "true"){
    $sql = "update chatcreate set recepientblocked = '0' where chatid = '$chatid' limit 1";
    $this->isrecepientblocked = $_SESSION["isrecepientblocked"] = "0";    
    }else{
    echo "Failed you are not invoved in this chat and do not have the authorization to modify its information";
    exit();
    }
    if($conn->query($sql) == "true"){
    return "success";
    }else{
    $this->iscreatorblocked = $_SESSION["iscreatorblocked"] = $formeriscreatorblocked;
    $this->isrecepientblocked = $_SESSION["isrecepientblocked"] = $formerisrecepientblocked;    
    return "Failed could not block chat at this time please try again".$conn->error;
    }
    }
    //public function to unblock chat ends here

    //public function to get last seen of current user starts here
    public function getdatet($uid){
    $conn = $this->conn;
    $uid = $this->clean_input($uid);
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
   
   //public function get last online starts here
    public function getupdatepartneronline(){
    $conn = $this->conn;
    $partnerid = $this->getPatnerId();
    $sql = "select lastlogindate from oaumeetupusers where userid = '$partnerid' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    $lastlogindate = $row["lastlogindate"];
    if(time() - $lastlogindate > 60){
    $lastlogindate = strftime("%b %d %Y @ %I:%M%p",$row["lastlogindate"]);
    }elseif(time() - $lastlogindate <= 60){
    $lastlogindate = "online";
    }
    return $lastlogindate;
    }else{
    return "";
    }
    }
   //public function get last online ends here





    
}
?>
