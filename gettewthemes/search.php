<?php
require "classsearch.php";
$srch = new search();
if($srch->validateUser() == "false"){
header("location:oaumeetuplogin.php");
exit();
}
if(count($_POST) == 0){
$userlist = $srch->getusers();
$fonttype = $srch->getFont();
}
//code to handle loading of more users list starts here
if(isset($_POST["loadmoreusers"])){
$res = $srch->getmoreusers();
if(is_array($res)){
echo $res[0]."_>_".$res[1];
exit();
}else{
echo $res;
exit();
}
}
//code to handle loading of more users list ends here
//code to handle of searching starts here
if(isset($_POST["searchkey"]) && isset($_POST["searchtype"]) && !empty($_POST["searchkey"]) && !empty($_POST["searchtype"])){
if($_POST["searchtype"] == "stories"){
echo $srch->searchstory($_POST["searchkey"]);
exit();
}elseif($_POST["searchtype"] == "users"){
echo $srch->searchuser($_POST["searchkey"]);
exit();
}
}
//code to handle of searching ends here
//code to handle loading of more user search results starts here
if(isset($_POST["loadmoresearchuser"])){
echo $srch->getmoreusersearchresult();
exit();
}
//code to handle loading of more user search results ends here

//code to handle loading of more stories search result starts here
if(isset($_POST["loadmoresearchstories"])){
echo $srch->getmorestorysearchresult();
exit();
}
//code to handle loading of more stories search result ends here

?>
<!DOCTYPE html>
<html>
<head>
<title>oameeetup|search</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<meta name="keywords"content="Oau social website,oau meetup, oau meetup site,oau biggest social website,oau social page,oau meetup Dating stories ">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="lazysizemin.js"></script>
</head>
<body class="w3-light-grey" style="font-family:<?php echo $fonttype;?>">
<!--navbar starts here-->
<?php include_once "navbar2.php"?>
<!--navbar ends here-->
<!--mainpage starts here-->
<div class=""style="margin-top:135px;margin-bottom:80px;">
<!--search input container starts here-->
<div class="w3-bar  w3-center" style="width:98%;margin-left:auto;margin-right:auto; margin-top:140px;">
<input type="text" name="" id="searcharea" class="w3-bar-item w3-border w3-border-blue w3-round-xxlarge" maxlength="30" minlength="1" style="width:80%;height:45px;" placeholder="search here...">
</input>
<i id='searchbtn' class="fa fa-search w3-bar-item  w3-small w3-xlarge w3-text-blue w3-hover-text-green" style=""></i>
<i  id='searchprg' class="fa fa-spinner w3-bar-item w3-text-blue w3-hide w3-spin w3-xlarge"></i>
<b class="w3-small w3-text-blue w3-bar-item" style="margin-top:6px;">Search through :</b>
<select id="searchtype" class="w3-round-large w3-bar-item w3-blue w3-tiny"name="searchtype" style="margin-top:6px;">
<option value="users">username</option>
<option value="stories">Stories</option>
</select>	
</div>
<!--search input container ends here-->

<!---users list starts here-->
<ul id='userlist' class="w3-ul w3-panel w3-animate-left" style="padding:0px;">
<div class="w3-center">
<i class="fa fa-users w3-text-blue w3-large"> users</i>
</div>
<?php echo $userlist;?>
</ul>
<!---users list ends here-->
<!--search list starts here-->
<ul id='searchlistctn' class='w3-ul w3-panel w3-display-container w3-animate-left w3-hide'>
<button id='closesearchlistctn' class="w3-display-topright w3-round-xlarge w3-text-red w3-white w3-card-4 w3-button w3-hover-red  w3-hover-text-white" style="margin-right:10px;">
<i class="fa fa-times"></i>
</button>
<div id='searchliscntnt' style="margin-top:30px;">
</div>	
</ul>
<!--search list ends here-->

</div>
<!--mainpage ends here-->
<!--footer-->
<?php include_once "footer.php"?>
<!--footer-->
<!--script-->
<script type="text/javascript">
$(function(){
var searcharea=searchbtn=searchprg=searchtype=loadmoreusers=loadmoreusersshow=loadmoreusersprg=userlistctn=searchlistctn=closesearchlistctn="";
searchtype = $("#searchtype");
searchprg = $("#searchprg");
searchbtn = $("#searchbtn");
searcharea = $("#searcharea");
loadmoreusers = $("#loadmoreusers");
loadmoreusersshow = $("#loadmoreusersshow");
loadmoreusersprg = $("#loadmoreusersprg");
userlistctn = $("#userlist");
searchlistctn = $("#searchlistctn");
closesearchlistctn = $("#closesearchlistctn");

//code to handle starting of chat starts here
searchbtn.click(function(){
if(searcharea.val() != "" && searchtype.val() != ""){
searchbtn.addClass("w3-hide");
searchprg.removeClass("w3-hide");
$.ajax({
url:"search.php",
method:"post",
data:{searchkey:searcharea.val(),searchtype:searchtype.val()},
success:function(data){
$("#searchliscntnt").html(data);
searchbtn.removeClass("w3-hide");
searchprg.addClass("w3-hide");
userlistctn.addClass("w3-hide");
searchlistctn.removeClass('w3-hide');
searcharea.val("");
},
error:function(xhr,status,err){
alert("could not carry out search due to poor connection please try again");
searchbtn.removeClass("w3-hide");
searchprg.addClass("w3-hide");
}
});
}

});
//code to handle starting of chat ends here

//code to handle loading of more userslist starts here
loadmoreusers.click(function(){
loadmoreusers.attr("disabled",true);
loadmoreusersshow.addClass("w3-hide");
loadmoreusersprg.removeClass("w3-hide");
$.ajax({
url:"search.php",
method:"post",
data:{loadmoreusers:""},
success:function(data){
//alert(data);
if(data.indexOf("no result") > -1){
loadmoreusersshow.html("no new users to show");
loadmoreusersprg.addClass("w3-hide");
loadmoreusersshow.removeClass("w3-hide");
setTimeout(function(){
loadmoreusers.attr("disabled",false);
loadmoreusersshow.html("Click to load more users <i class='fa fa-users'></i>");
loadmoreusersshow.removeClass("w3-hide");
loadmoreusersprg.addClass("w3-hide");	
},5000);
}else if(data.indexOf("li") > -1){

var arr = data.split("_>_");
userlistctn.append(arr[0]);
$(window).scrollTop($("#"+arr[1]).offset().top);
loadmoreusers.attr("disabled",false);
loadmoreusersshow.removeClass("w3-hide");
loadmoreusersprg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
loadmoreusers.attr("disabled",false);
loadmoreusersshow.removeClass("w3-hide");
loadmoreusersprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not load more users maybe due to bad connection please try again");
loadmoreusers.attr("disabled",false);
loadmoreusersshow.removeClass("w3-hide");
loadmoreusersprg.addClass("w3-hide");
}
})
});
//code to handle loading of more userslist ends here

//code to handle switching back to users list starts here
closesearchlistctn.click(function(){
userlistctn.removeClass("w3-hide");
searchlistctn.addClass('w3-hide');
});
//code to handle switching back to user list starts here
});


//code to handle loading of more search users starts here
function loadmoresearchuser(){
var btn = $("#loadmoresearchusers");
var show = $("#loadmoresearchusersshow");
var prg = $("#loadmoresearchusersprg");
btn.attr("disabled",true);
show.addClass("w3-hide");
prg.removeClass("w3-hide");
$.ajax({
url:"search.php",
method:"post",
data:{loadmoresearchuser:""},
success:function(data){
//alert(data);
if(data.indexOf("no result") > -1){
show.html("no more search result to show");
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
$("#searchliscntnt").append(data);
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");	
}
},
error:function(xhr,status,err){
alert("could not load more search result maybe due to poor connection please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
});
}
//code to handle loading of more search users ends here

//function to load more stories search result starts here
function loadmoresearchstory(){
var btn = $("#loadmoresearchstories");
var show = $("#loadmoresearchstoriesshow");
var prg = $("#loadmoresearchstoriesprg");
btn.attr("disabled",true);
show.addClass("w3-hide");
prg.removeClass("w3-hide");
$.ajax({
url:"search.php",
method:"post",
data:{loadmoresearchstories:""},
success:function(data){
//alert(data);
if(data.indexOf("no result") > -1){
show.html("no more search result to show");
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
$("#searchliscntnt").append(data);
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");	
}
},
error:function(xhr,status,err){
alert("could not load more search result maybe due to poor connection please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
});
}
//function to load more stories search result ends here

</script>
<!--script-->
</body>
</html>