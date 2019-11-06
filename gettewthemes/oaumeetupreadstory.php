<?php
require 'classreadstory.php';
$storyread=$fonttype=$story="";
$storyread = new readstory();
if ($storyread->validateUser() == "false") {
header("location:oaumeetuplogin.php");
exit();
}
$fonttype = $storyread->getFont();

if(isset($_GET["storyid"]) && !empty($_GET["storyid"])){
$storyread->setStoryid($_GET["storyid"]);
$story = $storyread->getStory();
if(empty($story) || $story == ""){
$story = "<div class='w3-center w3-text-red w3-large w3-animate-left'style='margin-top:40%;'>
<span><i class='fa fa-exclamation-triangle w3-xxlarge'></i> Story not found</span>
</div>";
}
}else{
$story = "<div class='w3-center w3-text-red w3-large w3-animate-left'style='margin-top:40%;'>
<span><i class='fa fa-exclamation-triangle w3-xxlarge'></i> Missing values to continue</span>
</div>";
}

//code to handle delete of story starts here
if(isset($_POST["clear"]) && $_POST["clear"] == "delete"){
$result = $storyread->deleteStory();
if($result == "delete succesful"){
echo "delete succesful";
exit();
}else{
echo $result;
exit();
}
}
//code to handle delete of story ends here

//code to handle follow of story starts here
 if(isset($_POST["followstory"])){
 echo  $storyread->followStory();
 exit();
 }
//code to handle follow of story ends here
 //code to handle follow of story starts here
 if(isset($_POST["unfollowstory"])){
 echo  $storyread->unfollowstory();
 exit();
 }
//code to handle follow of story ends here

?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|Read Story</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
</head>
<body class=""style="font-family: <?php echo $fonttype;?>">
<!--slideshow content starts here-->
<div class="readstoryctn w3-display-container"style="">
<?php echo $story;?>
<button onclick="preV()"class="w3-round-large back w3-display-left w3-btn w3-card-4 w3-text-blue w3-black w3-opacity w3-hover-white w3-hover-text-blue"style="margin:5px;"><i class="fa fa-angle-left w3-xlarge "></i></button>
<button onclick="nexT()" class="w3-round-large next w3-display-right w3-btn w3-text-blue w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue"style="margin:5px;"><i class="fa fa-angle-right w3-xlarge"></i></button>
 </div>
 <!--slideshow content ends here-->	
 <!--javascript!!!-->
 <script type="text/javascript">
var count =stories=length="";
count = 1;
stories = $(".storyctent");
length = stories.length;
$(function() {
$(".storyctent:nth-child(1)").removeClass("w3-hide");
var deletebtn=commentbtn=prg=trash=slideindex=sharebtn=followbtn=followicon=followprg=unfollowbtn=unfollowicon=unfollowprg="";
deletebtn = $(".deletebtn");
prg = $("#delprg");
trash = $("#trashicon");
slideindex = $("#slideindex");
sharebtn = $("#sharebtn");
followbtn = $("#followbtn");
followicon = $("#followicon");
followprg = $("#followprg");
unfollowbtn = $("#unfollowbtn");
unfollowicon = $("#unfollowicon");
unfollowprg = $("#unfollowprg");



//code to handle delete of story starts here
deletebtn.click(function() {
var sure = confirm("Are you sure you want to delete this story");
if(sure == true){
trash.addClass("w3-hide");
prg.removeClass("w3-hide");
deletebtn.attr("disabled",true);
$.ajax({
url : "oaumeetupreadstory.php",
method:"post",
data :{clear:"delete"},
success:function(data){
if(data.indexOf("delete successful") > -1){
alert("Story Deleted");
trash.removeClass("w3-hide");
prg.addClass("w3-hide");
window.location = "oaumeetupdatingstories.php";
}else{
alert(data);
trash.removeClass("w3-hide");
prg.addClass("w3-hide");
deletebtn.removeAttr("disabled");
}
},
error:function(xhr,status,error) {
alert("could not delete story maybe due to bad connection please try again");
trash.removeClass("w3-hide");
prg.addClass("w3-hide");
deletebtn.removeAttr("disabled");
}
});
}
});
//code to handle delete of story ends here

//code to handle sharing of story starts here
sharebtn.click(function(){
});
//code to handle sharing of story ends here

//code to handle follow starts here
followbtn.click(function(){
var check = confirm("Follow story?");
if(check != true){
return;
}
followicon.addClass("w3-hide");
followprg.removeClass("w3-hide");
$.ajax({
url : "oaumeetupreadstory.php",
method : "post",
data :{followstory:""},
success:function(data){
if(data.indexOf("Failed") > -1){
alert(data);
followicon.removeClass("w3-hide");
followprg.addClass("w3-hide");
}else if(data.indexOf("success") > -1){
alert("Succesfully Followed");
followbtn.addClass("w3-hide");
unfollowbtn.removeClass("w3-hide");
followicon.removeClass("w3-hide");
followprg.addClass("w3-hide");
}else{
alert(data);
followicon.removeClass("w3-hide");
followprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not follow story maybe due to bad connection please try again");
followicon.removeClass("w3-hide");
followprg.addClass("w3-hide");
}
});
});
//code to handle follow ends here

//code to handle unfollowing starts here
unfollowbtn.click(function(){
var check = confirm("Unfollow story?");
if(check != true){
return;
}
unfollowicon.addClass("w3-hide");
unfollowprg.removeClass("w3-hide");
$.ajax({
url : "oaumeetupreadstory.php",
method : "post",
data :{unfollowstory:""},
success:function(data){
if(data.indexOf("Failed") > -1){
alert(data);
unfollowicon.removeClass("w3-hide");
unfollowprg.addClass("w3-hide");
}else if(data.indexOf("success") > -1){
alert("Succesfully Unfollowed");
unfollowbtn.addClass("w3-hide");
followbtn.removeClass("w3-hide");
unfollowicon.removeClass("w3-hide");
unfollowprg.addClass("w3-hide");
}else{
alert(data);
unfollowicon.removeClass("w3-hide");
unfollowprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not follow story maybe due to bad connection please try again");
unfollowicon.removeClass("w3-hide");
unfollowprg.addClass("w3-hide");
}
});

});
//code to handle unfollowing ends here


});

function preV() {
//here we check if the index is less than one
if(count < 1){
count = 1;
}else if(count == 1){
//here we check if the index is equal to one
count = count;
}else{
--count;
stories.addClass("w3-hide");
$(".storyctent:nth-child("+count+")").removeClass("w3-hide");
slideindex.html(count+"/"+length);
}	
}

function nexT() {
if (count < length) {
++count;	
}else if (count >= length) {
count = 1;
}
stories.addClass("w3-hide");
$(".storyctent:nth-child("+count+")").removeClass("w3-hide");
slideindex.html(count+"/"+length);
}

 </script>
</body>
</html>