<?php
require "classachat.php";
$achat=$amsgs=$scrollid=$fonttype=$uid=$toseeid=$nottoseeid=$profileimg=$profilename=$lastseen=$footer=$optionlist="";
$achat = new achat();
if($achat->validateUser() == "false") {
header("location:oaumeetuplogin.php");
exit();
}
$fonttype = $achat->getFont();
$uid = $achat->getUid();
if(isset($_GET["achatid"])){
//code to clean former sessions if exists
//$achat->cleanSession();
//code to clean former sessions if exists
$achat->confirmAchat($_GET["achatid"]);
$chatarray = $achat->getTheAnonymousChat();
if(!empty($chatarray) && is_array($chatarray)){
$amsgs = $chatarray[0];
$scrollid = $chatarray[1];
}
$toseeid = $achat->getToSeeProfilePicId();
$nottoseeid = $achat->getToNotSeeProfilePicId();

//code to determine option to show starts here for block and unblock starts here
$optionlist = "<a id='blockchat'class='w3-bar-item w3-button w3-hover-purple w3-hover-text-white w3-text-purple'>Block Chat</a>
<!--<a id='rblockchat' class='w3-bar-item w3-button w3-hover-purple w3-hover-text-white w3-text-purple'>Report & Block Chat</a>-->";

if($achat->isacreatorblocked == "1" && $achat->iscreatorachat == "true"){
$optionlist = "<a id='unblockchat'class='w3-bar-item w3-button w3-hover-purple w3-hover-text-white w3-text-purple'>Unblock Chat</a>
<!--<a id='rblockchat' class='w3-bar-item w3-button w3-hover-purple w3-hover-text-white w3-text-purple'>Report & Block Chat</a>-->";	
}else if($achat->isarecepientblocked == "1" && $achat->iscreatorachat != "true"){
$optionlist = "<a id='unblockchat'class='w3-bar-item w3-button w3-hover-purple w3-hover-text-white w3-text-purple'>Unblock Chat</a>
<!--<a id='rblockchat' class='w3-bar-item w3-button w3-hover-purple w3-hover-text-white w3-text-purple'>Report & Block Chat</a>-->";
}
//code to determine option to show starts here for block and unblock starts here



//deciding what profile pic and name to be displayed starts here
if($achat->iscreatorachat == "true" && $toseeid == $uid){
$patnerdetails = $achat->getPartnerDetails();
if(is_array($patnerdetails)){
$profilename = $patnerdetails[0];
$profileimg = $patnerdetails[1];
$lastseen = $patnerdetails[2];
$gender = $patnerdetails[3];
}
if($gender == "male"){
$gender = "he";
}else{
$gender = "she";	
}

if($achat->isacreatorblocked == "1"){
$footer = "<div id='footer'class='w3-panel w3-bottom w3-center w3-large w3-bar w3-light-grey w3-text-red'style='width:100%;'>
<p><img src='chathide.jpg' style='width:30px;height:30px;''><b>You have blocked this chat and need to unblock if activity is to be resumed</b></p>
</div>";
if($gender == "male"){
$profileimg = "maledefault.png";
}else{
$profileimg = "femaledefault.jpeg";
}
$lastseen = "Blocked";
}else if($achat->isarecepientblocked == "1"){
$footer = "<div id='footer'class='w3-panel w3-bottom w3-center w3-large w3-bar w3-light-grey w3-text-red'style='width:100%;'>
<p><img src='chathide.jpg' style='width:30px;height:30px;''><b>Your other anonymous chat partner has blocked this chat and need to unblock if activity is to be resumed</b></p>
</div>";
if($gender == "male"){
$profileimg = "maledefault.png";
}else{
$profileimg = "femaledefault.jpeg";
}
$lastseen = "Blocked";
}else{
$footer = "<form id='footer'class='w3-bottom w3-bar w3-light-grey'style='width:100%;height:75px;'action='oaumeetupachat.php'>
<textarea id='chatarea' class='w3-bar-item  w3-round-xxlarge w3-padding w3-light-grey'placeholder='Send $profilename an anonymous message $gender won t know its you'style='word-wrap:break-word;resize: none;padding:5px;height:65px;border:1px groove #9c27b0;margin-bottom:4px;margin-top:5px;'></textarea>
 <label for='chatsphoto'>
<i id='callphoto'class='fa fa-camera-retro w3-bar-item w3-text-purple w3-xxlarge  w3-hover-text-green'style=' margin-top:15px;padding:0;width:10%;margin-left:9px;'></i>
 </label>
<button id='chatbtn'class='w3-bar-item w3-light-grey w3-text-purple w3-hover-text-green'
style=' margin-top:15px;padding:0;width:10%margin-left:0;'>
<i class='fa fa-send  w3-xxlarge'></i>
</button>
<input class='w3-hide'id='chatsphoto'type='file'name='chatsphoto'/>
</form>";
}


}else if($achat->iscreatorachat != "true" && $nottoseeid == $uid){
$patnerdetails = $achat->getPartnerDetails();
if(is_array($patnerdetails)){
$lastseen = $patnerdetails[2];
$gender = $patnerdetails[3];
}
$profileimg = "chathide.jpg";
if($gender == "male"){
$profilename ="<span>Male <i class='fa fa-male'></i></span><br>";	
}else{
$profilename ="<span>Female <i class='fa fa-female'></i></span><br>";	
}

if($achat->isacreatorblocked == "1"){
$footer = "<div id='footer'class='w3-panel w3-bottom w3-center w3-large w3-padding  w3-light-grey w3-text-red'style='width:100%;height:75px;'>
<p><img src='chathide.jpg' style='width:30px;height:30px;''><b>Your anonymous chat partner has blocked this chat and need to unblock if activity is to be resumed</b></p>
</div>";
if($gender == "male"){
$profileimg = "maledefault.png";
}else{
$profileimg = "femaledefault.jpeg";
}
$lastseen = "Blocked";
}else if($achat->isarecepientblocked == "1"){
$footer = "<div id='footer'class='w3-panel w3-bottom w3-center w3-large w3-bar  w3-light-grey w3-text-red'style='width:100%;'>
<p><img src='chathide.jpg' style='width:30px;height:30px;''><b>You have blocked this chat and need to unblock if activity is to be resumed</b></p>
</div>";
if($gender == "male"){
$profileimg = "maledefault.png";
}else{
$profileimg = "femaledefault.jpeg";
}
$lastseen = "Blocked";	
}else{
$footer = "<form id='footer'class='w3-bottom w3-bar w3-light-grey'style='width:100%;height:75px;'action='oaumeetupachat.php'>
<textarea id='chatarea' class='w3-bar-item  w3-round-xxlarge w3-padding w3-light-grey'placeholder='Find out what the anonymous messenger thinks about you'style='word-wrap:break-word;resize: none;padding:5px;height:65px;border:1px groove #9c27b0;margin-bottom:4px;margin-top:5px;'></textarea>
 <label for='chatsphoto'>
<i id='callphoto'class='fa fa-camera-retro w3-bar-item w3-text-purple w3-xxlarge  w3-hover-text-green'style=' margin-top:15px;padding:0;width:10%;margin-left:9px;'></i>
 </label>
<button id='chatbtn'class='w3-bar-item w3-light-grey w3-text-purple  w3-hover-text-green'
style=' margin-top:15px;padding:0;width:10%margin-left:0;'>
<i class='fa fa-send  w3-xxlarge'></i>
</button>
<input class='w3-hide'id='chatsphoto'type='file'name='chatsphoto'/>
</form>";
}

}else{
echo "Something went wrong missing critical values to continue please try";
exit();
}
//deciding what profile pic and name to be displayed starts here


}else if(!isset($_GET["achatid"]) && count($_POST) == 0 && count($_FILES) == 0){
echo "Failed missing values to continue";
exit();
}

//code to handle posting of achattext starts here
if(isset($_POST["achat"]) && !empty($_POST["achat"])){
echo $achat->insertachat($_POST["achat"]);
exit();
}
//code to handle posting of achattext ends here

//code to handle sending of photo code starts here
if(count($_FILES) > 0 && isset($_POST["chatphoto"])){
$name = $_FILES["chatsphoto"]["name"];
$type = $_FILES["chatsphoto"] ["type"];
$tmploc = $_FILES["chatsphoto"]["tmp_name"];
$size = $_FILES["chatsphoto"]["size"];
$error = $_FILES["chatsphoto"]["error"];
$target_dir = "fchatphotomsg/";
$imagefiletype = pathinfo($name,PATHINFO_EXTENSION);
$dbfilename = "anonymouspics".rand().".".$imagefiletype;
$targetfile = $target_dir.$dbfilename;
if(!$tmploc){
echo "Failed please insert an image first";
exit();
}else if(!getimagesize($tmploc)){
echo "Failed file is not an image";
exit();
}else if(!preg_match("/\.(jpg|jpeg|png)$/i",$name)){
echo "Failed sorry file can only be a jpg jpeg or png";
exit();
}else if($size > 2097152){
echo "Failed sorry,file is larger than 2mb";
exit();
}else if(!is_uploaded_file($tmploc)){
echo "Failed file was not uploaded via http";
exit();
}else if($error === 1){
echo "Failed an error occured while processing your file please try again!";
exit();
}else{
if(move_uploaded_file($tmploc,$targetfile)){
$insertchat = $achat->insertachat($targetfile);
// if upload fails
if(strpos($insertchat, "Failed") !== false){
if(file_exists($targetfile)){
unlink($targetfile);
}
echo "Failed sorry could not upload image please try again";
exit();
}
//if upload fails ends here
echo $insertchat;
exit();
}else{
echo "Failed an error occured while uploading please try again";
exit();
}
}
}//end of pictire upload conditional statement
//code to handle sending of photo msg ends here

//code to handle update chat starts here
if(isset($_POST["updatechat"]) && $_POST["updatechat"] == "update"){
echo $achat->updateachat();
exit();
}
//code to handle update of chat ends here

//code to handle delete of message starts here
if(isset($_POST["deletemsg"]) && !empty($_POST["deletemsg"])){
echo $achat->deleteMsg($_POST["deletemsg"]);
exit();
}
//code to handle delete of message ends here

//code to block user starts here
if(isset($_POST["blockthefuck"])){
echo $achat->blockUser();
exit();
}
//code to block user ends here

//code to unblock user starts here
if(isset($_POST["unblock"]) && $_POST["unblock"] == "forgive"){
echo $achat->unblockachat();
exit();
}
//code to unblock user ends here
//code to clear messages starts here
if(isset($_POST["clearchat"])){
echo $achat->clearchat();
exit();
}
//code to clear messages ends here

?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|Anonymous Chat</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="jquery.form.min.js"></script>
<script type="text/javascript" src="lazysizemin.js"></script> 
<style type="text/css">
@media only screen and (max-width: 600px) {
#chatarea{
width:70%;
}
#callphoto{
margin-right:12px;
}
}
@media only screen and (min-width: 600px) {
#chatarea{
width:80%;
}
#callphoto{
margin-right:0px;
}
}

@media only screen and (min-width: 768px) {
#chatarea{
width:80%;
}
#callphoto{
margin-right:0px;
}	
}

@media only screen and (min-width: 992px) {
#chatarea{
width:80%;
}
#callphoto{
margin-right:0px;
}
}

@media only screen and (min-width: 1200px) {
#chatarea{
width:80%;
}
#callphoto{
margin-right:0px;
}
 }
</style>
</head> 
<body class="w3-light-grey" style="font-family: <?php echo $fonttype;?>">
<!--navbar starts here-->
<div class="w3-top">
<div class="w3-purple w3-bar w3-card w3-padding">
<a href="oaumeetupshowachats.php"style='text-decoration:none;'><button class="w3-btn w3-round-large w3-card-4 w3-text-white w3-bar-item w3-hover-white w3-hover-text-purple">
<i class="fa fa-arrow-left"></i>	
</button></a>
<img src="<?php echo $profileimg;?>" class="w3-image w3-circle w3-card-4 w3-bar-item"style='margin-left:6px;margin-right:6px;width:40px;padding:0;height:40px;'>
<div class="w3-bar-item"style="padding:0;width:30%;overflow-x:hidden;margin-right:20px;">
<?php echo $profilename;?>
<marquee class="w3-small"><?php echo $lastseen;?></marquee>	
</div>
<!--dropdown list starts here-->
<div class="w3-dropdown-hover">
<button class="w3-button w3-card-4 w3-round-large w3-hover-white w3-hover-text-purple"style="">
<i class="fa fa-ellipsis-v"></i>	
</button>
<div class="w3-dropdown-content w3-bar-block w3-card-4 w3-small"style="width:100px;">
<a id='clearmsg'class="w3-bar-item w3-button w3-hover-purple w3-hover-text-white w3-text-purple" id='clearmsgs'>Clear Messages</a>
<?php echo $optionlist;?>
</div>
</div>
<!--dropdown list ends here-->
</div>
</div>
<!--navbar ends here-->	
<!--mainpage starts here-->
<div class=''style="margin-top:75px; margin-bottom: 100px;">
<div id='chatcontainer' class=''style="height:80%; width:100%;overflow-y: auto;"> 
<?php echo $amsgs;?>
</div>	
</div>
<!--mainpage ends here-->

<!--footer starts here-->
<div id='footer2'class='w3-panel w3-bottom w3-center w3-large  w3-hide w3-light-grey w3-text-red'style='width:100%;'>
<p><img src="chathide.jpg" style="width:30px;height:30px;"> <b>Blocking achat between the two users.<i class='fa fa-spinner w3-large w3-spin'></i></b></p>
</div>
<div id='footerclear'class='w3-panel w3-bottom w3-center w3-large  w3-hide w3-light-grey w3-text-red'style='width:100%;'>
<p><img src="chathide.jpg" style="width:30px;height:30px;"> <b>Clearing chat...<i class='fa fa-spinner w3-large w3-spin'></i></b></p>
</div>
<div id='footer1'class='w3-panel w3-bottom w3-center w3-large  w3-hide w3-light-grey w3-text-red'style='width:100%;'>
<p><img src="chathide.jpg" style="width:30px;height:30px;"> <b>Unblocking achat between the two users.<i class='fa fa-spinner w3-large w3-spin'></i></b></p>
</div>
<?php echo $footer;?>
<!--footer ends here-->
<script type="text/javascript">
$(document).ready(function(){
var origscrollid ="<?php echo $scrollid;?>";
//alert(origscrollid);

if(origscrollid != ""){
if($("#"+origscrollid).length > 0){
$(window).scrollTop($("#"+origscrollid).offset().top);
}else{
origscrollid = $("#chatcontainer").children().last().attr("id");
if($("#"+origscrollid).length > 0){
$(window).scrollTop($("#"+origscrollid).offset().top);	
}
}
}
var  filephoto =chatarea=chatbtn=chatctn=lastinsertid=form=clearmsg=blockuser=rblockuser=ublockchat="";
filephoto = $("#chatsphoto");
chatbtn = $("#chatbtn");
chatarea = $("#chatarea");
chatctn = $("#chatcontainer");
form = $("#footer");
clearmsg = $("#clearmsg");
blockuser = $("#blockchat");
rblockuser = $("#rblockchat");
unblockchat=$("#unblockchat");
lastinsertid =origscrollid;
//to handle posting of anonymous chat message starts here
chatbtn.click(function(e){
e.preventDefault();
var text = chatarea.val();
//text area not empty run ajaxonclick='reSend(\""+text+"\",\""+prgid+"\")'
if(text != ""){
chatarea.val("");
var id = "coolBlow"+Math.floor(Math.random()*1000000000);
prgid = "prgss"+id;
timeid= "time"+id;
hideid ="hide"+id;
chatctn.append("<div id ='"+id+"'class='w3-panel'style='padding:0;margin:0;'><div class='w3-right w3-card msg_txt w3-purple w3-padding w3-animate w3-ripple w3-animate-zoom w3-medium'style='max-width:250px;word-wrap: break-word;border-radius:15px 50px 30px;margin: 5px;font-size:16px;'><p style='margin:2px;'>"+text+"</p><p id ='"+prgid+"'class='w3-tiny w3-left'style='margin:0;margin-left:5px;margin-right:10px;'>time loading...</p><span id ='"+timeid+"'class='w3-tiny w3-right'><i class='fa fa-spinner w3-spin'></i>sending...</span></div><div style='width:100%;text-align:right;'class='w3-text-red w3-right w3-hide w3-margin-right w3-ripple'id ='"+hideid+"'><b onclick='reSend(\""+text+"\",\""+prgid+"\",\""+timeid+"\",\""+hideid+"\",\""+id+"\")'><i class='fa fa-exclamation-circle'></i> Could not send tap to retry</b></div></div>");
$(window).scrollTop(chatctn.prop( "scrollHeight" ));
$.ajax({
url : "oaumeetupachat.php",
method :"post",
data:{chatm:"in",achat:text},
success:function(data){
if(data.indexOf("Failed") > -1){
alert(data);
$("#"+prgid).html("failed");
$("#"+timeid).html("failed");
$("#"+hideid).removeClass("w3-hide");
}else if(data.indexOf("Blocked") > -1){
alert("Failed sorry chat not sent because one of you chat partners blocked this chat");
$("#"+id).remove();
location.reload();
}else{
//alert(data);
var a = data.split("®®®");
var arr = a[0];
//alert(data);
if(arr != ""){
var arr = arr.split("©©©");
if(arr instanceof Array){
for(i = 0;i < arr.length;i++){
if($("#"+arr[i]).length > 0){
$("#isseen"+arr[i]).html("seen");
}//if
}
}//for Loop
}//iF Not Empty
$("#"+id).remove();
if(a[1] != "" && a[2] != ""){
chatctn.append(a[1]);
$(window).scrollTop($("#"+a[2]).offset().top);
lastinsertid = a[2];
}else{
alert("sorry missing messasge to update");
}
}//closing else statement

},
error:function(xhr,status,err){
//alert("couldnot connect to server maybe due to bad connection");
$("#"+prgid).html("failed");
$("#"+timeid).html("failed");
$("#"+hideid).removeClass("w3-hide");
//reSend(text,prgid,timeid,hideid);
/*alert("couldnot connect to server maybe due to bad connection");
$("#"+id).remove();*/
}
});
}
});
//to handle posting of anonymous chat message ends here

//To Handle Scrolling When Chat area Is Typing
chatarea.on({
focus:function(){
$(window).scrollTop(chatctn.prop( "scrollHeight" ));
},
click:function(){
$(window).scrollTop(chatctn.prop("scrollHeight"));
},
change:function(){
$(window).scrollTop(chatctn.prop( "scrollHeight" ));
},
hover:function(){
$(window).scrollTop(chatctn.prop( "scrollHeight" ));
},
keydown:function(){
$(window).scrollTop(chatctn.prop( "scrollHeight" ));
}
});
//To Handle Scrolling When Chat area Is Typing ends here

//sending of chatphoto to partner starts here
filephoto.change(function(){
if(filephoto.val() != ""){
var filesize = document.getElementById("chatsphoto").files[0].size;
if(filesize > 2097152){
alert("sorry File is Larger Than 2 Mb");
return;
}

var id = "coolBlow"+parseInt(Math.random());
prgid = "pgress"+id;
spin = "spin"+id;	
form.ajaxSubmit({
url:"oaumeetupachat.php",
type:"post",
data:{chatphoto:""},
beforeSubmit:function(){	
chatctn.append("<div id ='"+id+"'class='w3-panel mymsg' style=''><div class='w3-right'><div class='w3-animate-zoom w3-display-container'style='width:150px;height:250px;padding:0;'><img src='chatplaceholder1.jpg' class='w3-round-xxlarge w3-card w3-image w3-block w3-ripple'style='width:100%;height:100%;'><div class='w3-display-middle w3-text-white w3-center w3-display-container'style='width:50px;'><i class='fa fa-spinner w3-xxxlarge w3-spin w3-text-purple'id='"+spin+"'></i><span class='w3-display-middle w3-small'id='"+prgid+"'>0%</span></div></div><span class='w3-tiny w3-text-purple w3-left'style='margin-top:8px;'>time loading..</span><span class='w3-tiny w3-right w3-text-purple'>sending...</span></div></div>");
$(window).scrollTop(chatctn.prop( "scrollHeight" ));
},
uploadProgress:function(event,position,total,percentagecomplete){
$('#'+prgid).html(percentagecomplete+"%");
},
success:function(data){
if(data.indexOf("Failed") > -1){
alert(data);
$('#'+id).remove();	
}else{
$("#"+spin).removeClass("w3-text-blue").addClass("w3-text-green");
$('#'+prgid).addClass("w3-text-green");
var a = data.split("®®®");
var arr = a[0];
//alert(data);
//alert(arr);
if(arr != ""){
var arr = arr.split("©©©");
if(arr instanceof Array){
for(i = 0;i < arr.length;i++){
if($("#"+arr[i]).length > 0){
$("#isseen"+arr[i]).html("seen");
}//if
}
}//for Loop
}//iF Not Empty
$("#"+id).remove();
if(a[1] != "" && a[2] != ""){
chatctn.append(a[1]);
$(window).scrollTop($("#"+a[2]).offset().top);
lastinsertid = a[2];
}else{
alert("sorry missing messasge to update");
}
}
},
error:function(xhr,status,err){
alert("Could not connect to server maybe due to bad network please try again");
$('#'+id).remove();	
}
});

}
});
//sending of chatphoto to partner ends here

//code to handle blocking ofchatuser starts here
blockuser.click(function(){
var con = confirm('Are you sure you want to block this chat ?');
if(con != true){
return;
}
form.addClass("w3-hide");
$("#footer2").removeClass("w3-hide");
$.ajax({
url : "oaumeetupachat.php",
method : "post",
data :{blockthefuck:""},
success:function(data){
if(data.indexOf("success") > -1){
alert("Chat blocked successfully");
form.remove();
$("#footer2").remove();
location.reload();
}else if(data.indexOf("Failed") > -1){
alert(data);
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
}else{
alert(data);
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("Could not connect to server maybe due to bad connection please click again");
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
}
});

});
//code to handle blocking of chatuser ends here

//code to unblock user starts here
unblockchat.click(function(){
var con = confirm('Are you sure you want to unblock this chat ?');
if(con != true){
return;
}
form.addClass("w3-hide");
$("#footer2").addClass("w3-hide");
$("#footer1").removeClass("w3-hide");
//alert("So you have found it in your heart to forgive, don't bro you an asshole don't change");
$.ajax({
url: "oaumeetupachat.php",
method: "post",
data:{unblock:"forgive"},
success:function(data){	
if(data.indexOf("success") > -1){
alert("The block you placed on the chat has being removed successfully");
$("#footer2").remove();
$("#footer1").remove();
location.reload();
}else if(data.indexOf("Failed") > -1){
alert(data);
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
$("#footer1").addClass("w3-hide");
}else{
alert(data);
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
$("#footer1").addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not connect to server maybe due to bad connection please try again");
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
$("#footer1").addClass("w3-hide");
}
});
});
//code to unblock user ends here

//code to clear all messages starts here
$("#clearmsg").click(function(){
var c = confirm("Clear chat message for you?");
if(c != true){
return;
}
form.addClass("w3-hide");
$("#footer2").addClass("w3-hide");
$("#footer1").addClass("w3-hide");
$("#footerclear").removeClass("w3-hide");	
$.ajax({
url:"oaumeetupachat.php",
method:"post",
data :{clearchat:""},
success:function(data){
if(data.indexOf("Failed") > -1){
alert(data);
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
$("#footer1").addClass("w3-hide");
$("#footerclear").addClass("w3-hide");
}else if(data.indexOf("success") > -1){
alert("Chat messages cleared");
chatctn.html("");
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
$("#footer1").addClass("w3-hide");
$("#footerclear").addClass("w3-hide");
}else{
alert(data);
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
$("#footer1").addClass("w3-hide");
$("#footerclear").addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not connect to server maybe due to  bad connection please try again");
form.removeClass("w3-hide");
$("#footer2").addClass("w3-hide");
$("#footer1").addClass("w3-hide");
$("#footerclear").addClass("w3-hide");
}
});
});
//code to clear all messages ends here

});//document.ready function closing braces

//function to update chat starts here
function updateChat(){
$.ajax({
url : "oaumeetupachat.php",
method : "post",
data :{updatechat:"update"},
success:function(data){
if(data.indexOf("Failed") > -1 || data == ""){	
/*alert("cool");
alert(data);*/
}else{
var a = data.split("®®®");
var arr = a[0];
//alert(a[0]);
if(arr != ""){
var arr = arr.split("©©©");
if(arr instanceof Array){
for(i = 0;i < arr.length;i++){
if($("#"+arr[i]).length > 0){
$("#isseen"+arr[i]).html("seen");
}
}//for Loop
}else if($("#"+arr).length > 0){
$("#isseen"+arr).html("seen");
}
}//if empty

if(a[1] != "" && a[2] != ""){
chatctn.append(a[1]);
$(window).scrollTop($("#"+a[2]).offset().top);
lastinsertid = a[2];
}
}
},
error:function(xhr,status,err){
console.log(xhr);
}
});
}
setInterval(updateChat,5000);
//function to update chat starts here




//function to handle resending of message starts here
function reSend(text,prgid,timeid,hideid,id){
var text = text;
$("#"+timeid).html("time loading...");
$("#"+prgid).html("<i class='fa fa-spinner w3-spin'></i>sending...");
$("#"+hideid).addClass("w3-hide");
$.ajax({
url : "oaumeetupachat.php",
method :"post",
data:{chatm:"in",achat:text},
success:function(data){
if(data.indexOf("Failed") > -1){
//alert(data);
console.log(data);
$("#"+prgid).html("failed");
$("#"+timeid).html("failed");
$("#"+hideid).removeClass("w3-hide");
}else{
//alert(data);
console.log(data);
var a = data.split("®®®");
var arr = a[0];
//alert(data);
if(arr != "" && arr instanceof Array){
var arr = arr.split("©©©");
for(i = 0;i < arr.length;i++){
if($("#"+arr[i]).length > 0){
$("#isseen"+arr[i]).html("seen");
}//if
}//for Loop
}//iF Not Empty
$("#"+id).remove();
//alert(a[1]);
chatctn.append(a[1]);
$(window).scrollTop($("#"+a[2]).offset().top);
lastinsertid = a[2];
}//closing else statement

},
error:function(xhr,status,err){
//alert("couldnot connect to server maybe due to bad connection");
$("#"+prgid).html("failed");
$("#"+timeid).html("failed");
$("#"+hideid).removeClass("w3-hide");
//reSend(text,prgid,timeid,hideid);
/*alert("couldnot connect to server maybe due to bad connection");
$("#"+id).remove();*/
}
});
}
//function to handle resending of message ends here

//function to delete particular message starts here
function deleteMsg(msgid){
$("#"+msgid).addClass("w3-light-blue");	
var text = $("#isseen"+msgid).html();
var c = confirm("Delete particular message for you");
if(c == true){
$("#isseen"+msgid).html("<b><i class='fa fa-spinner w3-spin'></i> DELETING....</b>");
$.ajax({
url :"oaumeetupachat.php",
method : "post",
data:{deletemsg:msgid},
success:function(data){
if(data.indexOf("Failed") > -1){
alert(data);
$("#isseen"+msgid).html(text);
$("#"+msgid).removeClass("w3-light-blue");
}else if(data.indexOf("success") > -1){
$("#"+msgid).remove();
}else{
alert(data);
$("#isseen"+msgid).html(text);
$("#"+msgid).removeClass("w3-light-blue");
}
},
error:function(xhr,status,err){
alert("Could not connect server maybe due to bad connection please try again");
$("#isseen"+msgid).html(text);
$("#"+msgid).removeClass("w3-light-blue");
}
});
}else{
$("#"+msgid).removeClass("w3-light-blue");
}
}
//function to delete particular message ends here


</script>
</body>
</html>