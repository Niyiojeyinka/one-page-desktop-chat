<?php
session_start();
require 'classmeetupvalidate.php';
/**
 *$_SESSION["userscrollnum"] $_SESSION["usersearchnum"] $_SESSION["storiessearchnum"]
 */
class search extends meetupvalidate
{
	
 function __construct(){
 $this->createConnection();
 if(isset($_SESSION["userid"])){
 $this->userid = $this->clean_input($_SESSION["userid"]);
 }
 }

 //public function  to get users by default starts here
 public function getusers(){
 $conn = $this->conn;
 $uid = $this->userid;
 $sql = "select userid,username,institution,gender,avatar,bio,attributes from oaumeetupusers where activated = '1' order by id desc limit 10";
 $result = $conn->query($sql);
 if($result->num_rows > 0){
 $_SESSION["userscrollnum"] = $result->num_rows;
 $data = "";
 while($row = $result->fetch_assoc()){
 $userid = $row["userid"];
 $username = $row["username"];
 $institution = $row["institution"];
 $gender = $row["gender"];
 $avatar = $row["avatar"];
 $bio = $row["bio"];
 $attr = $row["attributes"];

 if(empty($avatar) && $gender == "male"){
 $avatar = "maledefault.png";
 }else if(empty($avatar) && $gender == "female"){
 $avatar = "femaledefault.jpeg";
 }

if(empty($bio) && $gender == "female"){
 $bio = "Don't doubt it,you are a beauty :)";
}elseif(empty($bio) && $gender == "male"){
 $bio="Bro you are cool";
}else{
if(strlen($bio) > 25){
$bio = substr($bio,0,25)."...";
}	
}
 $data .="
 <a href='oaumeetupprofile.php?uid=$userid' style='text-decoration:none;'>
 <li id='$userid' class='w3-panel w3-animate-zoom w3-card w3-round-large w3-ripple w3-padding-large w3-bar w3-animate-zoom w3-display-container' style='width:90%;margin-left:auto;margin-right:auto;'>
<img src='chatplaceholder1.jpg' data-src ='$avatar' class='w3-image lazyload w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
<i class='fa fa-$gender w3-display-topright w3-text-blue w3-margin w3-large'></i>
<div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
<b class='w3-text-blue w3-small'><i class='fa fa-user'></i> $username</b><br>
<span class='w3-tiny w3-text-blue'>Status <i class='fa fa-pencil'></i> ~ $bio</span><br> 
<span class='w3-tiny w3-text-blue'>Gender ~ $gender <i class='fa fa-$gender'></i></span> <br>
<span class='w3-tiny w3-text-blue'>Institution <i class='fa fa-institution'></i> ~ $institution</span> <br>
</div>
</li>
</a>";
 }//while loop
 $data .= "<button id='loadmoreusers' class='w3-padding w3-ripple w3-btn w3-block w3-card-4  w3-round-xlarge w3-text-blue w3-center' style = 'width:70%;margin-left:auto;margin-right:auto;outline:none;'>
<b id='loadmoreusersshow' class=''>Click to load more users <i class='fa fa-users'></i></b>
<b id='loadmoreusersprg' class='w3-hide'><i class='fa fa-spinner w3-spin w3-xlarge'></i> Loading more users...</b>
</button>";
 return $data;
 }else{
 return "<div class='w3-center w3-text-blue w3-xlarge' style='margin-top:40px;'>
<i class='fa fa-meh-o w3-xxlarge'></i> No users yet
</div>";
 }
 }
 //public function  to get users by default ends here

 //public function to search users starts here
 public function searchuser($word){
 $conn = $this->conn;
 $word = $this->clean_input($word);
 if(empty($word)){
 $d = "'$word'";
 return "<div class='w3-center w3-text-blue w3-xlarge' style='margin-top:40px;'>
<i class='fa fa-meh-o w3-xxlarge'></i> No result for search $d </div>";
 }
 $sql = "select userid,username,institution,gender,avatar,bio,attributes from oaumeetupusers where username like '%$word%' limit 10";
 $result = $conn->query($sql);
 if($result->num_rows > 0){
 $num = $result->num_rows;
 $data = "<div class='w3-center'>
<i class='fa fa-search w3-text-blue w3-small'> $num search results</i>
</div>";
 $_SESSION["usersearchnum"] = array($num,$word);
 while($row = $result->fetch_assoc()){
 $userid = $row["userid"];
 $username = $row["username"];
 $institution = $row["institution"];
 $gender = $row["gender"];
 $avatar = $row["avatar"];
 $bio = $row["bio"];
 $attr = $row["attributes"];
 if(empty($bio) && $gender == "female"){
 $bio = "Don't doubt it,you are a beauty :)";
 }elseif(empty($bio) && $gender == "male"){
 $bio="Bro you are cool";
 }else{
 if(strlen($bio) > 25){
 $bio = substr($bio,0,25)."...";
 }	
 }
 if(empty($avatar) && $gender == "male"){
 $avatar= "maledefault.png";
 }else if(empty($avatar) && $gender == "female"){
 $avatar = "femaledefault.jpeg";
 }
 $data .="
 <a href='oaumeetupprofile.php?uid=$userid' style='text-decoration:none;'>
 <li id='$userid' class='w3-panel w3-animate-zoom w3-card w3-round-large w3-ripple w3-padding-large w3-bar w3-animate-zoom w3-display-container' style='width:90%;margin-left:auto;margin-right:auto;'>
<a href='oaumeetupprofile.php?uid=$userid'style='text-decoration:none;'>
<img src='chatplaceholder1.jpg' data-src ='$avatar' class='w3-image lazyload w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
</a>
<i class='fa fa-$gender w3-display-topright w3-text-blue w3-margin w3-large'></i>
<div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
<b class='w3-text-blue w3-small'><i class='fa fa-user'></i> $username</b><br>
<span class='w3-tiny w3-text-blue'>Status <i class='fa fa-pencil'></i> ~ $bio</span><br> 
<span class='w3-tiny w3-text-blue'>Gender ~ $gender <i class='fa fa-$gender'></i></span> <br>
<span class='w3-tiny w3-text-blue'>Institution <i class='fa fa-institution'></i> ~ $institution</span> <br>
</div>
</li>
</a>";
}//while loop 
if($num == 10){
$data .= "<button id='loadmoresearchusers' onclick='loadmoresearchuser()'class='w3-padding w3-ripple w3-btn w3-block w3-card-4  w3-round-xlarge w3-text-blue w3-center' style = 'width:70%;margin-left:auto;margin-right:auto;outline:none;'>
<b id='loadmoresearchusersshow' class=''>Click to load more search result <i class='fa fa-search'></i></b>
<b id='loadmoresearchusersprg' class='w3-hide'><i class='fa fa-spinner w3-spin w3-xlarge'></i> Loading more users...</b>
</button>";	 
}else{
unset($_SESSION["usersearchnum"]);
} 
return $data;
}else{
$word = "'$word'";
 return "<div class='w3-center w3-text-blue w3-xlarge' style='margin-top:40px;'>
<i class='fa fa-meh-o w3-xxlarge'></i> No result for search $word </div>";
}
}
//public function to search user endss here

 //public function to search for stories starts here
 public function searchstory($word)
 {
 $conn = $this->conn;
 $word = $this->clean_input($word);
 if(empty($word)){
 $d = "'$word'";
 return "<div class='w3-center w3-text-blue w3-xlarge' style='margin-top:40px;'>
<i class='fa fa-meh-o w3-xxlarge'></i> No result for search $d </div>";
 }
 $sql = "select * from stories where  storytitle like '%$word%' limit 10";
 $result = $conn->query($sql);
 if($result->num_rows > 0){
 $num = $result->num_rows;
 $_SESSION["storiessearchnum"] = array($num,$word);
 $data = "";
 while($row = $result->fetch_assoc()){
  $id = $row["id"];
  $writerid = $row["writerid"];
  $writerdetails = $this->getPartnerDetails($writerid);
  if(is_array($writerdetails)){
  $writername= $writerdetails[0];
  $gender = $writerdetails[1];
  }  
  $storyid = $row["storyid"];
  $storytitle = $row["storytitle"];
  $storycontent = $row["storycontent"];
  $mood = $row["mood"];
  $numviews = $row["numviews"];
  $viewerslist = $row["viewerslist"];
  $numcomment = $row["numcomment"];
  $commenterlist = $row["commenterslist"];
  $anonymous = $row["anonymous"];
  $expired = $row["expired"];
  $date = $row["date"];
  $storycontent = str_replace("_^_","\ud",$storycontent);
  $stry = json_decode($storycontent,true);
  $stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
  $count = count($stry);
  $colorstory = array_keys($stry);
  $color_first = $colorstory[0];
  $shrtstry = substr($stry[$color_first], 0,18);
  $shrtstry = str_replace("#389","\\",$shrtstry);
  $writername ="<span class='w3-small'><i class='fa fa-user'> </i> $writername</span>"; 
  if($anonymous == "1") {
  $writername =  "<span class='w3-small'><i class='fa fa-eye-slash'> </i> <i class='fa fa-$gender'></i> $gender Anonymous</span>";
  }
  if($mood == ""){
  $mood = "Happy";
  }
  $data .="
  <a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
  <li id='$writerid' class='w3-bar w3-ripple w3-display-container'style='padding-left:4px;'>
  <span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>
  <span class='w3-text-grey w3-display-topmiddle  w3-small'style='margin-left:30vw;'>Mood : $mood</span>
  <span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
  <div class='w3-bar-item w3-circle'style='border: 3px dashed #2196F3;padding: 5px;margin-top:15px;'>
  <div class='w3-circle w3-bar-item  $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow: ellipsis;letter-spacing:2px;'>$shrtstry</div> 
       </div>
  <div class='w3-bar-item' style='width: 70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
  <span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
  <span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
  <span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp
  <span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
  </li></a>";
  }//while  loop
  if($num == 10){
  $data .= "<button id='loadmoresearchstories' onclick='loadmoresearchstory()'class='w3-padding w3-ripple w3-btn w3-block w3-card-4  w3-round-xlarge w3-text-blue w3-center' style = 'width:70%;margin-left:auto;margin-right:auto;outline:none;'>
<b id='loadmoresearchstoriesshow' class=''>Click to load more search result <i class='fa fa-search'></i></b>
<b id='loadmoresearchstoriesprg' class='w3-hide'><i class='fa fa-spinner w3-spin w3-xlarge'></i> Loading more users...</b>
</button>"; 
  }else{
  unset($_SESSION["storiessearchnum"]);
  }
 return $data;
 }else{
$word = "'$word'";
return "<div class='w3-center w3-text-blue w3-xlarge' style='margin-top:40px;'>
<i class='fa fa-meh-o w3-xxlarge'></i> No result for search $word </div>"; }
 }
 //public function to search for stories ends here

 //public function to handle loading of more user stories starts here
 public function getmoreusers(){
 $conn = $this->conn;
 if(!isset($_SESSION["userscrollnum"]) || empty($_SESSION["userscrollnum"]) || !is_numeric($_SESSION["userscrollnum"])){
 return "no result";
 }
 $num = $_SESSION["userscrollnum"];
 $scrollid = "";
 $sql = "select userid,username,institution,gender,avatar,bio,attributes from oaumeetupusers where activated = '1' order by id desc limit $num,10";
 $result = $conn->query($sql);
 if($result->num_rows > 0){
 $_SESSION["userscrollnum"] += $result->num_rows;
 while($row = $result->fetch_assoc()){
 $userid = $row["userid"];
 $username = $row["username"];
 $institution = $row["institution"];
 $gender = $row["gender"];
 $avatar = $row["avatar"];
 $bio = $row["bio"];
 $attr = $row["attributes"];

 if(empty($avatar) && $gender == "male"){
 $avatar = "maledefault.png";
 }else if(empty($avatar) && $gender == "female"){
 $avatar = "femaledefault.jpeg";
 }

 if(empty($scrollid)){
 $scrollid = $userid;
 }

if(empty($bio) && $gender == "female"){
 $bio = "Don't doubt it,you are a beauty :)";
}elseif(empty($bio) && $gender == "male"){
 $bio="Bro you are cool";
}else{
if(strlen($bio) > 25){
$bio = substr($bio,0,25)."...";
}	
}
 $data .="
 <a href='oaumeetupprofile.php?uid=$userid' style='text-decoration:none;'>
 <li id='$userid' class='w3-panel w3-animate-zoom w3-card w3-round-large w3-ripple w3-padding-large w3-bar w3-animate-zoom w3-display-container' style='width:90%;margin-left:auto;margin-right:auto;'>
<img src='chatplaceholder1.jpg' data-src ='$avatar' class='w3-image lazyload w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
<i class='fa fa-$gender w3-display-topright w3-text-blue w3-margin w3-large'></i>
<div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
<b class='w3-text-blue w3-small'><i class='fa fa-user'></i> $username</b><br>
<span class='w3-tiny w3-text-blue'>Status <i class='fa fa-pencil'></i> ~ $bio</span><br> 
<span class='w3-tiny w3-text-blue'>Gender ~ $gender <i class='fa fa-$gender'></i></span> <br>
<span class='w3-tiny w3-text-blue'>Institution <i class='fa fa-institution'></i> ~ $institution</span> <br>
</div>
</li>
</a>";	
 }//while loop
 return array($data,$scrollid);
 }else{
 //unset($_SESSION["userscrollnum"]);
 return "no result";
 }
 }
 //public function to handle loading of more user stories ends here

 //public function to load more user search result starts here
 public function getmoreusersearchresult(){
 if(!isset($_SESSION["usersearchnum"]) || !is_array($_SESSION["usersearchnum"]) || count($_SESSION["usersearchnum"]) != 2){
 return "no result";
 }
 $conn = $this->conn;
 $usersearch = $_SESSION["usersearchnum"]; 
 $num = $this->clean_input($usersearch[0]);
 $word = $this->clean_input($usersearch[1]);
 $scrollid = "";
 $sql = "select userid,username,institution,gender,avatar,bio,attributes from oaumeetupusers where username like '%$word%' limit $num,10";
 $result = $conn->query($sql);
 if($result->num_rows > 0){
 $data = "";
 $tnum = $result->num_rows;
 $num += $result->num_rows;
 $_SESSION["usersearchnum"] = array($num,$word);
 while($row = $result->fetch_assoc()){
 $userid = $row["userid"];
 $username = $row["username"];
 $institution = $row["institution"];
 $gender = $row["gender"];
 $avatar = $row["avatar"];
 $bio = $row["bio"];
 $attr = $row["attributes"];
 if(empty($bio) && $gender == "female"){
 $bio = "Don't doubt it,you are a beauty :)";
 }elseif(empty($bio) && $gender == "male"){
 $bio="Bro you are cool";
 }else{
 if(strlen($bio) > 25){
 $bio = substr($bio,0,25)."...";
 }	
 }
 if(empty($avatar) && $gender == "male"){
 $avatar= "maledefault.png";
 }else if(empty($avatar) && $gender == "female"){
 $avatar = "femaledefault.jpeg";
 }
 if(empty($scrollid)){
 $scrollid = $userid;
 }
 $data .="
 <a href='oaumeetupprofile.php?uid=$userid' style='text-decoration:none;'>
 <li id='$userid' class='w3-panel w3-animate-zoom w3-card w3-round-large w3-ripple w3-padding-large w3-bar w3-animate-zoom w3-display-container' style='width:90%;margin-left:auto;margin-right:auto;'>
<a href='oaumeetupprofile.php?uid=$userid'style='text-decoration:none;'>
<img src='chatplaceholder1.jpg' data-src ='$avatar' class='w3-image lazyload w3-circle w3-animate-zoom w3-bar-item' style='padding:0;width:50px;height:50px;'>
</a>
<i class='fa fa-$gender w3-display-topright w3-text-blue w3-margin w3-large'></i>
<div class='w3-bar-item' style='width:75%;padding:0;margin:0;margin-left:10px;'> 
<b class='w3-text-blue w3-small'><i class='fa fa-user'></i> $username</b><br>
<span class='w3-tiny w3-text-blue'>Status <i class='fa fa-pencil'></i> ~ $bio</span><br> 
<span class='w3-tiny w3-text-blue'>Gender ~ $gender <i class='fa fa-$gender'></i></span> <br>
<span class='w3-tiny w3-text-blue'>Institution <i class='fa fa-institution'></i> ~ $institution</span> <br>
</div>
</li>
</a>";
}//while loop 
if($tnum < 10){
unset($_SESSION["usersearchnum"]);
}
return $data."_>_".$scrollid;
}else{
unset($_SESSION["usersearchnum"]);
return "no result";
}
}
//public function to load more user search result ends here
//public function to load more of stories search result starts here
public function getmorestorysearchresult(){
if(!isset($_SESSION["storiessearchnum"]) || !is_array($_SESSION["storiessearchnum"]) || count($_SESSION["storiessearchnum"]) != 2){
return "no result";
}
$conn = $this->conn;
$storiesearch = $_SESSION["storiessearchnum"]; 
$num = $this->clean_input($storiesearch[0]);
$word = $this->clean_input($storiesearch[1]);
$scrollid = "";

$sql = "select * from stories where storytitle like '%$word%' limit $num,10";
$result = $conn->query($sql);
if($result->num_rows > 0){
$data = "";
$tnum = $result->num_rows;
$num += $result->num_rows;
$_SESSION["storiessearchnum"] = array($num,$word);
while($row = $result->fetch_assoc()){
$id = $row["id"];
$writerid = $row["writerid"];
$writerdetails = $this->getPartnerDetails($writerid);
if(is_array($writerdetails)){
$writername= $writerdetails[0];
$gender = $writerdetails[1];
}  
$storyid = $row["storyid"];
$storytitle = $row["storytitle"];
$storycontent = $row["storycontent"];
$mood = $row["mood"];
$numviews = $row["numviews"];
$viewerslist = $row["viewerslist"];
$numcomment = $row["numcomment"];
$commenterlist = $row["commenterslist"];
$anonymous = $row["anonymous"];
$expired = $row["expired"];
$date = $row["date"];
$storycontent = str_replace("_^_","\ud",$storycontent);
$stry = json_decode($storycontent,true);
$stry = str_replace(array("_-_","_+_","_#_"),"",$stry);
$count = count($stry);
$colorstory = array_keys($stry);
$color_first = $colorstory[0];
$shrtstry = substr($stry[$color_first], 0,18);
$shrtstry = str_replace("#389","\\",$shrtstry);
$writername ="<span class='w3-small'><i class='fa fa-user'> </i> $writername</span>"; 
if($anonymous == "1") {
$writername =  "<span class='w3-small'><i class='fa fa-eye-slash'> </i> <i class='fa fa-$gender'></i> $gender Anonymous</span>";
}
if($mood == ""){
$mood = "Happy";
}
if(empty($scrollid)){
$scrollid = $writerid;
}
$data .="
<a href='oaumeetupreadstory.php?storyid=$storyid'style='text-decoration:none;'>
<li id='$writerid' class='w3-bar w3-ripple w3-display-container'style='padding-left:4px;'>
<span class='w3-text-grey w3-display-topmiddle w3-margin-bottom'> $writername</span>
<span class='w3-text-grey w3-display-topmiddle  w3-small'style='margin-left:30vw;'>Mood : $mood</span>
<span class='w3-small w3-text-grey w3-right w3-display-topright w3-margin-left w3-margin-right'style='margin-top:3px;'><i class='fa fa-sticky-note'> </i> $count </span>
<div class='w3-bar-item w3-circle'style='border: 3px dashed #2196F3;padding: 5px;margin-top:15px;'>
<div class='w3-circle w3-bar-item  $color_first w3-center w3-tiny'style='width:60px;height:60px;word-wrap:break-word;overflow:hidden;text-overflow: ellipsis;letter-spacing:2px;'>$shrtstry</div> 
       </div>
<div class='w3-bar-item' style='width: 70%; padding: 0;margin:3px; margin-left: 8px;margin-top:20px;border-bottom:0.3px solid lightgrey;'>
<span class='w3-text-black w3-bold  w3-block'style='width:100%;font-size: 16px;'><b>$storytitle</b></span>
<span class='w3-text-grey w3-small'style='letter-spacing:2px;'>$shrtstry...</span><br>
<span class='w3-text-grey w3-small'><i class='fa fa-eye'> $numviews</i></span>&nbsp
<span class='w3-small w3-text-grey'><i class='fa fa-comments'> </i> $numcomment</span></div>
</li>
</a>";
}//while  loop
return array($num,$data);
if($tnum < 10){
unset($_SESSION["storiessearchnum"]);
}
}else{
unset($_SESSION["storiessearchnum"]);
return "no result c";
}
}
//public function to load more of stories search result ends here



//function to Get User Details starts Here
public function getPartnerDetails($id){
$conn = $this->conn;
$id = $this->clean_input($id);
$sql = "select username,gender from oaumeetupusers where userid = '$id' limit 1";
$result = $conn->query($sql);
if($result->num_rows == 1){
$row = $result->fetch_assoc();
$uname = $row["username"];
$gender = $row["gender"];
/*if(empty($avatar) && $gender == "male"){
$avatar= "maledefault.png";
}else if(empty($avatar) && $gender == "female"){
$avatar = "femaledefault.jpeg";
}*/
return array($uname,$gender);
}else{
return "";
}    
}
//function to get User DetaIls Ends Here






}

?>