<?php 
require "classsettings.php";
$setings =$fontpref="";
$setings = new settings();
if($setings->validateUser() == "false"){
header("location:oaumeetuplogin.php");
exit();
}
if(count($_POST) < 1){
$fonttype = $setings->getFont();
}

//code to handle remove of profile pic starts here
if(isset($_POST["rempic"])){
echo $setings->removepic();
exit();
}
//code to handle remove of profile pic ends here

//code to handle changing of password starts here
if(isset($_POST["oldpass"]) && isset($_POST["newpass"])){
echo $setings->changepass($_POST["oldpass"],$_POST["newpass"]);
exit();
}
//code to handle changing of password ends here

//code to handle changing of phonenumber starts here
if(isset($_POST["oldphone"]) && isset($_POST["newphone"])){
echo $setings->changephone($_POST["oldphone"],$_POST["newphone"]);
exit();
}
//code to handle changing of phonumber ends here

//code to handle changing of username starts here
if(isset($_POST["newname"])){
echo $setings->changename($_POST["newname"]);
exit();
}
//code to handle changing of username ends here

//code to handle log out starts here
if(isset($_POST["logout"])){
echo $setings->logout();
exit();
}
//code to handle log out ends here
?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|Settings Page</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body style="font-family:<?php $fonttype;?>" class="w3-light-grey">
<!--Navbar starts here-->
<div class="w3-top">
<div class="w3-bar w3-blue w3-card w3-padding">
<a href='oaumeetupprofile.php'class="w3-btn w3-block w3-bar-item w3-hover-white w3-hover-text-blue w3-large w3-round-large"style=":0px;padding: 8px;"> <i class="fa fa-arrow-left" style="text-decoration:none;"></i></a>
<span class="w3-text-white  w3-bar-item w3-large"style="padding: 8px;">Settings</span>
<span class="w3-text-blue  w3-large w3-round-large w3-white w3-bar-item  w3-btn w3-hover-text-blue w3-card w3-right"style="margin: 3px;"><i class="fa fa-gear w3-spin"></i></span>		
</div>
</div>
<!--Navbar ends here-->	
<!--Mainpage starts here-->
<div class=""style="margin-top:80px;margin-bottom:60px;">
<ul class="w3-ul w3-text-blue w3-container" style="">
<button id="rempic"class="w3-card-4 w3-block w3-ripple w3-small  w3-btn w3-text-blue  w3-animate-zoom w3-padding-large w3-round-large" style="outline:none;">
<span id="rempicshow" class=" w3-left"><i class="fa fa-picture-o"></i>  Remove Profile photo</span>
<span id="rempicsprg" class="w3-hide"><i class="fa fa-spinner w3-spin"></i> removing profile pics</span>
</button>
<button id="logout"class="w3-card-4 w3-block w3-ripple w3-panel w3-small  w3-btn w3-text-blue  w3-animate-zoom w3-padding-large w3-round-large" style="outline:none;">
<span id="logoutshow" class="w3-left"><i class="fa fa-power-off"></i> Logout</span>
<span id="logoutprg" class="w3-hide"><i class="fa fa-spinner w3-spin"></i> logging out...</span>
</button>
<a href="meetupprefrence.php"style="text-decoration:none;"><li class="w3-card-4 w3-panel w3-border-bottom w3-animate-zoom w3-round-large w3-ripple  w3-small"><img src ="lovematch.png"class="w3-circle"style="width:40px;height:40px;"/> <span class="w3-small">Update Your Meetup Preference</span> <img src ="love.png"class="w3-circle w3-right w3-spin"style="width:40px;height:40px;"/></li></a>

<a href="setattributes.php"style="text-decoration:none;"><li class="w3-card-4 w3-panel w3-display-container  w3-small w3-border-bottom w3-animate-zoom  w3-round-large w3-ripple"> <i class="fa fa-smile-o w3-spin w3-text-blue w3-xxlarge"></i> <span class="w3-display-middle" style="margin-bottom:30px;">Update things about you</span> <img src ="love.png"class="w3-circle w3-right w3-spin"style="width:40px;height:40px;"/></li></a>

<!--<li id='sendadminmsg' class="w3-card-4 w3-panel  w3-small w3-border-bottom w3-animate-zoom w3-padding-large w3-round-large w3-ripple"><i class="fa fa-user-md w3-large"></i> Send a message to admin</li>
<div id='sendadminmsgctn'class="w3-panel w3-center w3-small w3-bar w3-hide">
<textarea id='sendadminmsgarea' class="w3-textarea w3-round-large w3-border w3-border-blue"  maxlength="240" style="outline:none; width:80%;height:100px;resize:none;"placeholder="Drop your complaint or suggestion here :)"></textarea><br>
<button id='sendadminmsgbtn' class="w3-blue w3-round-large w3-hover-white w3-hover-text-blue w3-btn w3-card">Submit</button>
</div>-->
<!-- send msg to admin container-->
<li id='changepass' class="w3-card-4 w3-panel  w3-small w3-border-bottom w3-animate-zoom w3-padding-large w3-round-large w3-ripple"><i class="fa fa-eye-slash"></i> Change password</li>
<!--change passwword container starts here-->
<div id='changepassctn' class="w3-center w3-small w3-bar w3-hide">
<label class="w3-label w3-text-blue" for="oldpass"><b>Old Pass:</b></label>
<input id='oldpass' type="text" name="oldpass" class="w3-round-large w3-border w3-border-blue" style="width:70%;height:40px;outline:none;" maxlength="10"><br><br>
<label class="w3-label w3-text-blue" for="newpass"><b>New Pass:</b></label>
<input id='newpass'type="text" name="newpass" class="w3-round-large w3-border w3-border-blue" style="width:70%;height:40px;outline:none;"maxlength="10"><br><br>
<button id='changepassbtn' class="w3-blue w3-round-large w3-hover-white w3-hover-text-blue w3-btn w3-card">
<span id='changepassshow' class="">Change</span>
<span id='changepassprg' class="w3-hide"><i class="fa fa-spinner w3-spin"></i> changing</span>
</button>
</div>
<!--change password container ends here-->

<li id='changephonenum'class="w3-card-4  w3-small w3-panel w3-border-bottom w3-animate-zoom w3-padding-large w3-round-large w3-ripple"><i class="fa fa-phone"></i> Change phonenumber</li>
<!--change phonenumber container starts here-->
<div id='changephonectn' class="w3-center w3-small w3-bar w3-hide">
<label class="w3-label w3-text-blue" for="oldpass"><b>Old Phonenumber:</b></label>
<input id='oldphone' type="tel" name="oldphone" class="w3-round-large w3-border w3-border-blue" style="width:70%;height:40px;outline:none;" maxlength="11" placeholder="080......"><br><br>
<label class="w3-label w3-text-blue" for="newpass"><b>New phonenumber:</b></label>
<input id='newphone'type="tel" name="newphone" class="w3-round-large w3-border w3-border-blue" style="width:70%;height:40px;outline:none;"maxlength="11"placeholder="080......"><br><br>
<button id='changephonebtn' class="w3-blue w3-round-large w3-hover-white w3-hover-text-blue w3-btn w3-card">
<span id='changephoneshow' class="">Change</span>
<span id='changephoneprg' class="w3-hide"><i class="fa fa-spinner w3-spin"></i> changing</span>
</button>
</div>
<!--change phonenumber container ends here-->
<li id='changeusername'class="w3-card-4  w3-small w3-panel w3-border-bottom w3-animate-zoom w3-padding-large w3-round-large w3-ripple"><i class="fa fa-user"></i> Change Username</li>
<!--change username container starts here-->
<div id='changeusernamectn' class="w3-center w3-small w3-bar w3-hide">
<label class="w3-label w3-text-blue" for="userarea"><b>New username:</b></label><br>
<input id='userarea' type="text" name="userarea" class="w3-round-large w3-border w3-border-blue" style="width:70%;height:40px;outline:none;" maxlength="15" minlength="3" placeholder="johnnydoe"><br><br>
<button id='changeusernamebtn' class="w3-blue w3-round-large w3-hover-white w3-hover-text-blue w3-btn w3-card">
<span id='changeusernameshow' class="">Change</span>
<span id='changeusernameprg' class="w3-hide"><i class="fa fa-spinner w3-spin"></i> changing</span>
</button>
<!--change username container ends here-->



</ul>
</div>
<!--Mainpage ends here-->
<!--footer-->
<?php include_once "footer.php"; ?>
<!--foooter-->
<!--script-->
<script type="text/javascript">
$(function(){
var rempic =rempicshow=rempicsprg=changepass=changepassctn=oldpass=newpass=changepassbtn=changepassshow=changepassprg=changephonenum=changephonectn=oldphone=newphone=changephonebtn=changephoneshow=changephoneprg=changeusername=changeusernamectn=userarea=changeusernamebtn=changeusernameshow=changeusernameprg=logout=logoutshow=logoutprg="";
rempic = $("#rempic");
rempicshow = $("#rempicshow");
rempicsprg = $("#rempicsprg");
changepass = $("#changepass");
changepassctn = $("#changepassctn");
changepassbtn = $("#changepassbtn");
changepassshow = $("#changepassshow");
changepassprg = $("#changepassprg");
oldpass = $("#oldpass");
newpass = $("#newpass");
changephonenum = $("#changephonenum");
changephonectn = $("#changephonectn");
changephonebtn = $("#changephonebtn");
changephoneshow = $("#changephoneshow");
changephoneprg = $("#changephoneprg");
oldphone = $("#oldphone");
newphone = $("#newphone");
changeusername = $("#changeusername");
changeusernamectn = $("#changeusernamectn");
changeusernamebtn = $("#changeusernamebtn");
changeusernameshow = $("#changeusernameshow");
changeusernameprg = $("#changeusernameprg");
userarea = $("#userarea");
logout = $("#logout");
logoutshow = $("#logoutshow");
logoutprg = $("#logoutprg")
//code to handle removing of profile pic starts here
rempic.click(function(){
var check = confirm("Are you sure you want to remove your profile picture?");
if(check == false){
return;
}
rempic.attr("disabled",true);
rempicshow.addClass("w3-hide");
rempicsprg.removeClass("w3-hide");
$.ajax({
url:"settings.php",
method:"post",
data:{rempic:""},
success:function(data){
if(data.indexOf("failed") > -1){
alert(data);
rempic.attr("disabled",false);
rempicshow.removeClass("w3-hide");
rempicsprg.addClass("w3-hide");
}else if(data.indexOf("success")  > -1){
alert("profile picture removed sucessfully");
rempic.attr("disabled",false);
rempicshow.removeClass("w3-hide");
rempicsprg.addClass("w3-hide");
}else{
alert(data);
rempic.attr("disabled",false);
rempicshow.removeClass("w3-hide");
rempicsprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could remove profile picture due to bad network please try again");
rempic.attr("disabled",false);
rempicshow.removeClass("w3-hide");
rempicsprg.addClass("w3-hide");
}
});
});
//code to handle removing of profile pic ends here

//code to handle showinng change of password  ctn starts here
changepass.click(function(){
if(changepassctn.hasClass("w3-hide")){
changepassctn.removeClass("w3-hide");
}else{
changepassctn.addClass("w3-hide");
}
});
//code to handle showing change of password  ctn ends here

//code to handle changing of password starts here
changepassbtn.click(function(){
var oldpas = oldpass.val();
var newpas = newpass.val();
if(oldpas == "" || newpas == ""){
alert("input empty");
return;
}else if(oldpas == newpas){
alert("new password cannot be the same as old password");
return;
}
changepassbtn.attr("disabled",true);
changepassshow.addClass("w3-hide");
changepassprg.removeClass("w3-hide");
$.ajax({
url:"settings.php",
method:"post",
data:{oldpass:oldpas,newpass:newpas},
success:function(data){
if(data.indexOf("failed") > -1){
alert(data);
changepassbtn.attr("disabled",false);
changepassshow.removeClass("w3-hide");
changepassprg.addClass("w3-hide");
}else if(data.indexOf("success") > -1){
alert("password changed successfully");
oldpass.val("");
newpass.val("");
changepassbtn.attr("disabled",false);
changepassshow.removeClass("w3-hide");
changepassprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not change password due to poor connection please try again");
changepassbtn.attr("disabled",false);
changepassshow.removeClass("w3-hide");
changepassprg.addClass("w3-hide");
}
});
});
//code to handle changing of password ends here



//code to handle changing of phone container starts here
changephonenum.click(function(){
if(changephonectn.hasClass("w3-hide")){
changephonectn.removeClass("w3-hide");
}else{
changephonectn.addClass("w3-hide");
}
});
//code to handle changing of phone container ends here

//code to handle  changing of phonenumber starts here
changephonebtn.click(function(){
var oldphon = oldphone.val();
var newphon = newphone.val();
if(oldphon == "" || newphon == ""){
alert("input empty");
return;
}else if(oldphon == newphon){
alert("new password cannot be the same as old password");
return;
}
changephonebtn.attr("disabled",true);
changephoneshow.addClass("w3-hide");
changephoneprg.removeClass("w3-hide");
$.ajax({
url:"settings.php",
method:"post",
data:{oldphone:oldphon,newphone:newphon},
success:function(data){
//alert(data);
if(data.indexOf("failed") > -1){
alert(data);
changephonebtn.attr("disabled",false);
changephoneshow.removeClass("w3-hide");
changephoneprg.addClass("w3-hide");
}else if(data.indexOf("success") > -1){
alert("phonenumber changed successfully");
oldphone.val("");
newphone.val("");
changephonebtn.attr("disabled",false);
changephoneshow.removeClass("w3-hide");
changephoneprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not change password due to poor connection please try again");
changephonebtn.attr("disabled",false);
changephoneshow.removeClass("w3-hide");
changephoneprg.addClass("w3-hide");
}
});
});
//code to handle changing of phonenumber ends here

//code to handle opening and closing of change user container starts here
changeusername.click(function(){
if(changeusernamectn.hasClass("w3-hide")){
changeusernamectn.removeClass("w3-hide");
}else{
changeusernamectn.addClass("w3-hide");
}
});
//code to handle opening and closing of change user container ends here

//code to handle change username starts here
changeusernamebtn.click(function(){
if(userarea.val() == ""){
alert("input empty");
return;
}
changeusernamebtn.attr("disabled",true);
changeusernameshow.addClass("w3-hide");
changeusernameprg.removeClass("w3-hide");
$.ajax({
url:"settings.php",
method:"post",
data:{newname:userarea.val()},
success:function(data){
//alert(data);
if(data.indexOf("failed") > -1){
alert(data);
changeusernamebtn.attr("disabled",false);
changeusernameshow.removeClass("w3-hide");
changeusernameprg.addClass("w3-hide");
}else if(data.indexOf("success") > -1){
alert("username changed successfully");
userarea.val("");
changeusernamebtn.attr("disabled",false);
changeusernameshow.removeClass("w3-hide");
changeusernameprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not change username due to poor connection please try again");
changeusernamebtn.attr("disabled",false);
changeusernameshow.removeClass("w3-hide");
changeusernameprg.addClass("w3-hide");
}
});
});
//code to handle change username ends here

//code to handle logout starts here
logout.click(function(){
var check = confirm("Are you sure you want to log out?");
if(check == false){
return;
}
logout.attr("disabled",true);
logoutshow.addClass("w3-hide");
logoutprg.removeClass("w3-hide");
$.ajax({
url:"settings.php",
method:"post",
data:{logout:""},
success:function(data){
if(data.indexOf("failed") > -1){
alert(data);
logout.attr("disabled",false);
logoutshow.removeClass("w3-hide");
logoutprg.addClass("w3-hide");
}else if(data.indexOf("success")  > -1){
alert("logout successful");
logout.attr("disabled",false);
logoutshow.removeClass("w3-hide");
logoutprg.addClass("w3-hide");
window.location = "oaumeetuplogin.php";
}else{
alert(data);
logout.attr("disabled",false);
logoutshow.removeClass("w3-hide");
logoutprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("Unable to logout due to bad connection please try again");
logout.attr("disabled",false);
logoutshow.removeClass("w3-hide");
logoutprg.addClass("w3-hide");
}
});
});
//code to handle logout ends here


});
</script>
<!--script-->
</body>
</html>