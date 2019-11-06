<?php
require 'classadmin.php';
$admin = new adminin();
/*if($admin->validateUser() != "false") {
header("location:oaumeetuplogin.php");
exit();
}*/
//code to handle getting of users starts here
if(isset($_POST["users"])){
echo $admin->getuser();
exit();
}
//code to handle getting of users ends here
//code to handle getting of more users starts here
if(isset($_POST["moreuser"])){
echo $admin->getmoreuser();
exit();
}
//code to handle getting of more users ends here
//code to handle getting of more chat starts here
if(isset($_POST["getchat"])){
echo $admin->getnumchat();
exit();
}
//code to handle getting of more chat  ends here

//code to handle getting of more achat starts here
if(isset($_POST["getachat"])){
echo $admin->getnumachat();
exit();
}
//code to handle getting of more achat ends here

//code to handle getting of more achat starts here
if(isset($_POST["getstats"])){
echo $admin->getwebstats();
exit();
}
//code to handle getting of more achat ends heregetmorewebstats()

//code to handle getting of more stats starts here
if(isset($_POST["morestat"])){
echo $admin->getmorewebstats();
exit();
}
//code to handle getting of starts ends here
?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|Admin</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="lazysizemin.js"></script>
</head>
<body  class="w3-light-grey">
<!--navbar starts here-->
<div class=" w3-bar w3-top  w3-card w3-blue">
<button id='totalusers' class="w3-bar-item w3-hover-white w3-hover-text-blue" style="padding:0;width:20%;">Total users</button>
<button id='totalnumchat' class="w3-bar-item w3-hover-white w3-hover-text-blue"style="padding:0;width:20%;">Total num chat</button>
<button id='totalnumachat' class="w3-bar-item w3-hover-white w3-hover-text-blue"style="padding:0;width:20%;">Total num achat</button>
<button id='totalnumstories' class="w3-bar-item w3-hover-white w3-hover-text-blue"style="padding:0;width:20%;">Total num stories</button>
<button id='totalstat' class="w3-bar-item w3-hover-white w3-hover-text-blue"style="padding:0;width:20%;">Stat</button>
</div>
<!--navbar ends here-->	
<!--mainpage starts here-->
<div style="margin-top:90px;">
<!--user ctn-->
<ul class=" w3-display-container w3-ul w3-animate-left w3-panel" id="usersctn" style="height:100vh;">
<span id='getuserprg'class="w3-display-middle w3-text-blue w3-hide"><i class="fa fa-spinner w3-spin"></i> getting users</span>
</ul>
<!--user ctn-->
<!--chatctn-->
<ul class=" w3-display-container w3-hide w3-ul  w3-animate-left"style="height:100vh;" id="chatctn">
<span id='getchatprg'class="w3-display-middle w3-text-blue w3-hide"><i class="fa fa-spinner w3-spin"></i> getting chat</span>
</ul>
<!--chatctn-->

<!--achatctn-->
<ul class=" w3-display-container w3-hide w3-ul  w3-panel w3-animate-left"style="height:100vh;" id="achatctn">
<span id='getachatprg'class="w3-display-middle w3-text-blue w3-hide"><i class="fa fa-spinner w3-spin"></i> getting achat</span>
</ul>
<!--achatctn-->

<!--stories-->
<ul class=" w3-display-container w3-hide w3-ul  w3-panel w3-animate-left"style="height:100vh;" id="statctn">
<span id='getstatprg'class="w3-display-middle w3-text-blue w3-hide"><i class="fa fa-spinner w3-spin"></i> getting stat</span>
</ul>
<!--stories-->

<!--stories-->
<ul class=" w3-display-container w3-hide w3-ul  w3-animate-left"style="height:100vh;" id="storiesctn">
<span id='getstoriesprg'class="w3-display-middle w3-text-blue w3-hide"><i class="fa fa-spinner w3-spin"></i> getting stories</span>
</ul>
<!--stories-->

</div>
<!--mainpage ends here-->
<!--script-->
<script type="text/javascript">
$(function(){
var usersctn=chatctn=achatctn=statctn=storiesctn=getuserprg=getchatprg=getstatprg=getstoriesprg=totalusersbtn=totalnumchatbtn=totalnumachatbtn=totalnumstories=totalstatbtn=getachatprg="";
usersctn = $("#usersctn");
chatctn = $("#chatctn");
achatctn = $("#achatctn");
statctn = $("#statctn");
storiesctn = $("#storiesctn");
getuserprg = $("#getuserprg");
getchatprg = $("#getchatprg");
getachatprg = $("#getachatprg");
getstatprg = $("#getstatprg");
getstoriesprg = $("#getstoriesprg");
totalusersbtn = $("#totalusers");
totalnumchatbtn = $("#totalnumchat");
totalnumachatbtn = $("#totalnumachat");
totalstatbtn = $("#totalstat");

totalusersbtn.click(function(){
usersctn.removeClass("w3-hide");
chatctn.addClass("w3-hide");
achatctn.addClass("w3-hide");
statctn.addClass("w3-hide");
storiesctn.addClass("w3-hide");
if($("#usersctn li").length == 0){
getuserprg.removeClass("w3-hide");
$.ajax({
url:"jesusislord.php",
method:"post",
data:{users:""},
success:function(data){
if(data.indexOf("failed") > -1){
alert(data);
getuserprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
usersctn.append(data);
getuserprg.addClass("w3-hide");
}else{
alert(data);
getuserprg.addClass("w3-hide");	
}
},
error:function(xhr,status,err){
alert("could not get users due to poor connection please try again");
getuserprg.addClass("w3-hide");
}
});
}//if userlength == 0
});

//code to get number of chat starts here
totalnumchatbtn.click(function(){
usersctn.addClass("w3-hide");
chatctn.removeClass("w3-hide");
achatctn.addClass("w3-hide");
statctn.addClass("w3-hide");
storiesctn.addClass("w3-hide");

if($("#chatctn li").length == 0){
getchatprg.removeClass("w3-hide");

$.ajax({
url:"jesusislord.php",
method:"post",
data:{getchat:""},
success:function(data){
if(data.indexOf("failed") > -1){
alert(data);
getchatprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
chatctn.append(data);
getchatprg.addClass("w3-hide");
}else{
alert(data);
getchatprg.addClass("w3-hide");	
}
},
error:function(xhr,status,err){
alert("could not get chat due to poor connection please try again");
getuserprg.addClass("w3-hide");
}
});
}//length == 0
});
//code to get number of chat ends here

//code to get number of achat starts here
totalnumachatbtn.click(function(){
usersctn.addClass("w3-hide");
chatctn.addClass("w3-hide");
achatctn.removeClass("w3-hide");
statctn.addClass("w3-hide");
storiesctn.addClass("w3-hide");

if($("#achatctn li").length == 0){
getachatprg.removeClass("w3-hide");

$.ajax({
url:"jesusislord.php",
method:"post",
data:{getachat:""},
success:function(data){
//alert(data);
if(data.indexOf("failed") > -1){
alert(data);
getachatprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
achatctn.append(data);
getachatprg.addClass("w3-hide");
}else{
alert(data);
getachatprg.addClass("w3-hide");	
}
},
error:function(xhr,status,err){
alert("could not get achat due to poor connection please try again");
getachatprg.addClass("w3-hide");
}
});
}//length == 0
});
//code to get number of achat ends here

//code to get stats starts here
totalstatbtn.click(function(){
usersctn.addClass("w3-hide");
chatctn.addClass("w3-hide");
achatctn.addClass("w3-hide");
statctn.removeClass("w3-hide");
storiesctn.addClass("w3-hide");

if($("#statctn li").length == 0){
getstatprg.removeClass("w3-hide");

$.ajax({
url:"jesusislord.php",
method:"post",
data:{getstats:""},
success:function(data){
//alert(data);
if(data.indexOf("failed") > -1){
alert(data);
getstatprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
statctn.append(data);
getstatprg.addClass("w3-hide");
}else{
alert(data);
getstatprg.addClass("w3-hide");	
}
},
error:function(xhr,status,err){
alert("could not get stat due to poor connection please try again");
getstatprg.addClass("w3-hide");
}
});
}//length == 0
});
//code to get stats ends here



});//document.ready






//function to get more user starts here
function getmuser(){
var btn=show=prg="";
btn = $("#moreuserbtn");
show = $("#moreusershow");
prg = $("#moreuserprg");
btn.attr("disabled",true);
show.addClass("w3-hide");
prg.removeClass("w3-hide");
//code to handle ajax request starts here
$.ajax({
url:"jesusislord.php",
method:"post",
data:{moreuser:""},
success:function(data){
//alert(data);
if(data.indexOf("nomoreuser") > -1){
show.html("no more users to show");
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
btn.before(data);
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else{
alert(data);
btn.before(data);
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not get more users due to bad connection please try");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
});
//code to handle ajax request ends here
}
//function to get more user ends here


//function to get more stats starts here
function getmorestat(){
var btn=show=prg="";
btn = $("#morestatbtn");
show = $("#morestatshow");
prg = $("#morestatprg");
btn.attr("disabled",true);
show.addClass("w3-hide");
prg.removeClass("w3-hide");
//code to handle ajax request starts here
$.ajax({
url:"jesusislord.php",
method:"post",
data:{morestat:""},
success:function(data){
//alert(data);
if(data.indexOf("nomorestat") > -1){
show.html("no more stats to show");
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
btn.before(data);
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else{
alert(data);
btn.before(data);
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not get more stat due to bad connection please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
});
//code to handle ajax request ends here
}
//function to get more statsr ends here
</script>
<!--script-->
</body>
</html>