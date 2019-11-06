<?php
require "classdatingstories.php";
$datestory=$fonttype=$userstory=$trendstory="";
$datestory = new meetupdatingstories();
if($datestory->validateUser() == "false") {
header("location:oaumeetuplogin.php");
exit();
}
$datestory->clearexpired();
if(count($_POST) < 1){
$fonttype = $datestory->getFont();
$userstory = $datestory->getUserStories();
$trendstory = $datestory-> getTrendStories();
}
//code to handle loading of more user story starts here
if(isset($_POST["loadmoreustory"])){
$umore = $datestory->getMoreUserStories();
if(empty($umore)){
echo "no new";
exit();
}
echo $umore;
exit();
}
//code to handle loading of more user story ends here

//code to hanlde loading of more dating stories starts here
if(isset($_POST["loadmoreostory"])){
$umore = $datestory->getMoreTrendStories();
if(empty($umore)){
echo "no new";
exit();
}
echo $umore;
exit();
}
//code to hanlde loading of more dating stories ends here

//code to handle getting of read story starts here
if(isset($_POST["getreadstories"])){
echo $datestory->getReadStory();
exit();
}
//code to handle getting of read story ends here

//code to handle laoding of more read stories starts here
if(isset($_POST["loadmorereadstories"])){
echo $datestory->getMoreReadStories();
exit();
}
//code to handle loading of more read stories ends here

//code to handle loading of followed stories starts here
if(isset($_POST["getstoriesfollowed"])){
echo $datestory->getFollowedStories();
exit();	
}
//code to handle loading of followed stories ends here

//code to handle looading more of followed stories starts here
if(isset($_POST["loadmorefollowingstories"])){
echo $datestory->getmorefollowedstories();
exit();
}
//code to handle looading more of followed stories ends here

//code to handle loading followers starts here
if(isset($_POST["getfollowers"])){
echo $datestory->getfollowers();
exit();
}
//code to handle handle loading followers ends here

//code to handle loading of more followers starts here
if(isset($_POST["loadmorefollowers"])){
echo $datestory->getmorefollowers();
exit();
}
//code to handle loading of more followers ends here

//code to handle unfollowing starts here
if(isset($_POST["unfollowsharp"]) && !empty($_POST["unfollowsharp"])){
echo $datestory->unfollow($_POST["unfollowsharp"]);
exit();
}
//code to andle unfollowing ends here
//code to handle remove of follower from your list starts here
if(isset($_POST["removefollower"]) && !empty($_POST["removefollower"])){
echo $datestory->removefollower($_POST["removefollower"]);
exit();
}
//code to handle remove of follower from your list ends here
?>
<!DOCTYPE html>
<html>
<head>
<title>oameeetup|Dating Stories</title>
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
<body style="font-family:<?php echo $fonttype;?> ">
<!--navbar starts here-->
<?php include_once "navbar2.php"?>
<!--navbar ends here-->
<!--main page content starts here-->
<div class=""style="margin-top:135px;margin-bottom:60px;">
<!--container for your stories  list-->
<ul class="w3-ul w3-animate-left" id='firstslideul'style="">
<!--for your own story-->
<div id="taketowritestoryctn"class="" style="overflow-y:auto;">
<!-- button to take you to where you can write your story starts here-->
<a href="oaumeetupwritestory.php"style="text-decoration: none;">		
<h6 class="w3-light-grey w3-bold w3-text-black"style="padding:5px;margin:0;">Your Story</h6>
<div class="w3-ripple w3-bar"style="padding-top:5px;padding-bottom:2px;margin:5;width:90%;">
<button class="w3-circle w3-bar-item w3-display-container w3-card w3-white w3-center w3-border w3-text-blue w3-border-blue w3-tiny"style="width:60px;height:60px;word-wrap:break-word;overflow:hidden;padding: 0;margin-left: 8px;"/><i class="fa fa-pencil w3-display-middle w3-xxlarge"></i></button>
<span class="w3-text-blue w3-bar-item w3-large"style="padding: 2px;margin-top:12px;margin-left:3px;">Write your Story</span>
</div>
</a>
<!-- button to take you to where you can write your story ends here-->
</div>
<!--for your own story ends here-->

<!--dynamic stories from database starts here-->
<div id="yourstory" class=''style="width:100%;">
<?php echo $userstory;?>
<!--dynamic stories from database ends here-->


<!--other stories-->
<div class="w3-blue w3-padding w3-bold">Dating stories & Tips</div>
<div id ='otherstories'>
<?php echo $trendstory;?>
<!--other stories end here-->
</ul>
<!--container for stories list ends here-->	

<!--container for readstories list starts here-->
<ul class="w3-ul w3-hide" id='secondslideul'>
<b id='secondslideulload'class="w3-display-middle w3-hide w3-text-blue w3-center "><i id='secondslideulloadspin' class="fa fa-spinner w3-jumbo w3-spin"></i><br>
<span id='secondslideuloadprg'>loading read stories...</span><br>
<button class="w3-btn w3-small w3-blue w3-round-large w3-hide"id='secondslideultryagainbtn'>Try again</button>
</b>
<!--read stories starts here-->
<div id="secondslidectn"class=''style="width:100%;">
</div>
<!--read stories ends here-->

</ul>
<!--container for readstories list ends here-->

<!--container for followed story list starts here-->
<ul class="w3-ul w3-hide"id='thirdslideul' style="margin-top:150px;">
<b id='thirdslideulload'class="w3-display-middle w3-hide w3-text-blue w3-center "><i id='thirdslideulloadspin' class="fa fa-spinner w3-jumbo w3-spin"></i><br>
<span id='thirdslideuloadprg'>loading stories followed...</span><br>
<button class="w3-btn w3-small w3-blue w3-round-large w3-hide"id='thirdslideultryagainbtn'>Try again</button>
</b>
<!--followed stories starts here-->
<div id="thirdslidectn"class=''style="width:100%;">
</div>
<!--followed stories ends here-->
</ul>
<!--container for followed story list ends here-->

<!--container for those following your story starts here-->
<ul class="w3-ul w3-hide" id='fourthslideul' style="margin-top:150px;">
<b id='fourthslideulload'class="w3-display-middle w3-hide w3-text-blue w3-center "><i id='fourthslideulloadspin' class="fa fa-spinner w3-jumbo w3-spin"></i><br>
<span id='fourthslideuloadprg'>loading follwers..</span><br>
<button class="w3-btn w3-small w3-blue w3-round-large w3-hide"id='fourthslideultryagainbtn'>Try again</button>
</b>
<!--followed stories starts here-->
<div id="fourthslidectn"class=''style="width:100%;">
</div>
<!--followed stories ends here-->	
</ul>
<!--container for those following your story ends here-->



</div>
<!--main page content ends here-->
<!--footer-->
<div class="w3-bottom">
<div class="w3-bar w3-card w3-white">
<button id="storyswipe" class="w3-button w3-tiny w3-bar-item w3-center w3-text-blue w3-hover-blue w3-hover-text-white"style="width:25%;"><i class="fa fa-book w3-xlarge"></i><br>Stories</button>
<button id="readstoryswipe" class="w3-button w3-tiny w3-bar-item w3-center w3-text-blue w3-hover-blue w3-hover-text-white"style="width:25%;"><i class="fa fa-envelope-open w3-xlarge"></i><br>Read</button>
<button id="followinstoryswipe" class="w3-button w3-tiny w3-bar-item w3-center w3-text-blue w3-hover-blue w3-hover-text-white"style="width:25%;"><i class="fa fa-book w3-xlarge"></i><br>Following</button>
<button id="followerstoryswipe" class="w3-button w3-tiny w3-bar-item w3-center w3-text-blue w3-hover-blue w3-hover-text-white"style="width:25%;"><i class="fa fa-users w3-xlarge"></i><br>Followers</button>
</div>
</div>
<?php //include_once "footer.php";?>
<!--footer-->
<!--javascript-->
<script type="text/javascript">
$(function(){
var yourstoryctn=loadmustories=loadmustoriesshow=loadmoreustoriesprg=otherstories=loadmostories=loadmostoriesshow=loadmoreostoriesprg=storyswipe=readstoryswipe=followinstoryswipe=followerstoryswipe=firstslideul=secondslideul=secondslidectn=thirdslideul=secondslideulload=thirdslideulload=secondslideulloadprg=secondslideultryagainbtn=secondslideulloadspin=thirdslideulloadprg=thirdslideultryagainbtn=thirdslideulloadspin=fourthslideul=fourthslideulloadprg=fourthslideultryagainbtn=fourthslideulloadspin="";
yourstoryctn = $("#yourstory");
loadmustoriesbtn = $("#loadmustories");
loadmustoriesshow = $("#loadmustoriesshow");
loadmoreustoriesprg = $("#loadmoreustoriesprg");
otherstoriesctn = $("#otherstories");
loadmostoriesbtn = $("#loadmostories");
loadmostoriesshow = $("#loadmostoriesshow");
loadmoreostoriesprg = $("#loadmoreostoriesprg");
firstslideul = $("#firstslideul");
secondslideul = $("#secondslideul");
secondslideulload = $("#secondslideulload");
secondslidectn = $("#secondslidectn");
secondslideulloadprg = $("#secondslideuloadprg");
secondslideultryagainbtn = $("#secondslideultryagainbtn");
secondslideulloadspin = $("#secondslideulloadspin");
thirdslideul = $("#thirdslideul");
thirdslideulload = $("#thirdslideulload");
thirdslidectn = $("#thirdslidectn");
thirdslideulloadprg = $("#thirdslideuloadprg");
thirdslideultryagainbtn = $("#thirdslideultryagainbtn");
thirdslideulloadspin = $("#thirdslideulloadspin");
fourthslideul =$("#fourthslideul");
fourthslideulload = $("#fourthslideulload");
fourthslidectn = $("#fourthslidectn");
fourthslideulloadprg = $("#fourthslideuloadprg");
fourthslideultryagainbtn = $("#fourthslideultryagainbtn");
fourthslideulloadspin = $("#fourthslideulloadspin");
storyswipe = $("#storyswipe");
readstoryswipe = $("#readstoryswipe");
followinstoryswipe = $("#followinstoryswipe");
followerstoryswipe = $("#followerstoryswipe");

//code to get more of your stories starts here
loadmustoriesbtn.click(function() {
loadmustoriesshow.addClass("w3-hide");
loadmoreustoriesprg.removeClass("w3-hide");
loadmustoriesbtn.attr("disabled",true);
$.ajax({
url :"oaumeetupdatingstories.php",
method : "post",
data:{loadmoreustory:""},
success:function(data){
if(data.indexOf("Failed") > -1){
alert(data);
loadmustoriesbtn.attr("disabled",false);
loadmustoriesshow.removeClass("w3-hide");
loadmoreustoriesprg.addClass("w3-hide");
}else if(data.indexOf('no new') > -1){
loadmustoriesbtn.html("<b>You dont have any more stories..</b>");
loadmustoriesshow.removeClass("w3-hide");
loadmoreustoriesprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
//alert(data);
yourstoryctn.append(data);
loadmustoriesbtn.attr("disabled",false);
loadmustoriesshow.removeClass("w3-hide");
loadmoreustoriesprg.addClass("w3-hide");
$(window).scrollTop($(loadmustoriesbtn).offset().top - 500);
}
},
error:function(xhr,status,err){
alert("Could not load more of your stories due to bad connection please try again");
loadmustoriesbtn.attr("disabled",false);
loadmustoriesshow.removeClass("w3-hide");
loadmoreustoriesprg.addClass("w3-hide");
}
});
});
//code to get more of your stories ends here


//code to get more dating stories not belong to users starts here
loadmostoriesbtn.click(function(){
loadmostoriesshow.addClass("w3-hide");
loadmoreostoriesprg.removeClass("w3-hide");
loadmostoriesbtn.attr("disabled",true);
$.ajax({
url :"oaumeetupdatingstories.php",
method : "post",
data:{loadmoreostory:""},
success:function(data){
if(data.indexOf("Failed") > -1){
alert(data);
loadmostoriesbtn.attr("disabled",false);
loadmostoriesshow.removeClass("w3-hide");
loadmoreostoriesprg.addClass("w3-hide");
}else if(data.indexOf('no new') > -1){
//alert(data);
loadmoreostoriesprg.html("<b>No new dating stories to load yet..</b>");
setTimeout(function() {
loadmostoriesshow.removeClass("w3-hide");
loadmoreostoriesprg.addClass("w3-hide");
loadmoreostoriesprg.html("<i class='fa fa-spinner w3-spin w3-xlarge'></i> Loading more dating stories...");
loadmostoriesbtn.attr("disabled",false);
},5000);
}else if(data.indexOf("li") > -1){
//alert(data);
otherstoriesctn.append(data);
loadmostoriesbtn.attr("disabled",false);
loadmostoriesshow.removeClass("w3-hide");
loadmoreostoriesprg.addClass("w3-hide");
$(window).scrollTop($(loadmostoriesbtn).offset().top - 500);
}
},
error:function(xhr,status,err){
alert("Could not load more dating stories may be due to bad connection please try again");
loadmostoriesbtn.attr("disabled",false);
loadmostoriesshow.removeClass("w3-hide");
loadmoreostoriesprg.addClass("w3-hide");
}
});});
//code to get more dating stories not belong to users ends here

//code to get read stories starts here
$("#readstoryswipe,#secondslideultryagainbtn").click(function(){
firstslideul.addClass("w3-hide");
thirdslideul.addClass("w3-hide");
fourthslideul.addClass("w3-hide");
//set the needed elements to normal
secondslideulload.addClass("w3-hide");
secondslideultryagainbtn.addClass("w3-hide");
secondslideulloadprg.html("loading read stories...");
secondslideulload.addClass("w3-text-blue");
secondslideulloadspin.addClass("w3-spin")
secondslideulload.removeClass("w3-text-red");
//set the needed elements to normal ends here
secondslideul.removeClass("w3-hide");
if($("#secondslideul li").length == 0){
secondslideulload.removeClass("w3-hide");
$.ajax({
url:"oaumeetupdatingstories.php",
method :"post",
data :{getreadstories:""},
success:function(data){
if(data.indexOf("no read") > -1){
secondslideulloadprg.html("You have no read unvanished story");
secondslideulloadspin.removeClass("w3-spin");
}else if(data.indexOf("li") > -1){
secondslidectn.append(data);
//secondslideul.append(data);
secondslideulload.addClass("w3-hide");
secondslideultryagainbtn.addClass("w3-hide");
secondslideulloadprg.html("loading read stories...");
secondslideulload.addClass("w3-text-blue");
secondslideulloadspin.addClass("w3-spin")
secondslideulload.removeClass("w3-text-red");
}else{
secondslideulloadprg.html("<i class='fa fa-meh-o'></i> something went wrong");
secondslideulload.removeClass("w3-text-blue");
secondslideulloadspin.removeClass("w3-spin")
secondslideulload.addClass("w3-text-red");
secondslideultryagainbtn.removeClass("w3-hide");	
}
},
error:function(xhr,status,err){
secondslideulloadprg.html("<i class='fa fa-exclamation-circle'></i> connection error");
secondslideulload.removeClass("w3-text-blue");
secondslideulloadspin.removeClass("w3-spin")
secondslideulload.addClass("w3-text-red");
secondslideultryagainbtn.removeClass("w3-hide");
}
});
}//closing if statement

});
//code to get read stories ends here

//code to hanlde showing of stories starts here
storyswipe.click(function(){
firstslideul.removeClass("w3-hide");
thirdslideul.addClass("w3-hide");
secondslideul.addClass("w3-hide");
fourthslideul.addClass("w3-hide");
});
//code to handle showing of stories ends here

//code to handle opening of followed stories starts here
$("#followinstoryswipe,#thirdslideultryagainbtn").click(function(){
firstslideul.addClass("w3-hide");
secondslideul.addClass("w3-hide");
fourthslideul.addClass("w3-hide");
//set the needed elements to normal
thirdslideulload.addClass("w3-hide");
thirdslideultryagainbtn.addClass("w3-hide");
thirdslideulloadprg.html("loading stories followed...");
thirdslideulload.addClass("w3-text-blue");
thirdslideulloadspin.addClass("w3-spin")
thirdslideulload.removeClass("w3-text-red");
//set the needed elements to normal ends here
thirdslideul.removeClass("w3-hide");
if($("#thirdslideul li").length == 0){
thirdslideulload.removeClass("w3-hide");

$.ajax({
url:"oaumeetupdatingstories.php",
method :"post",
data :{getstoriesfollowed:""},
success:function(data){
if(data.indexOf("no followed") > -1){
thirdslideulloadprg.html("You are not following any story");
thirdslideulloadspin.removeClass("w3-spin");
}else if(data.indexOf("li") > -1){
thirdslidectn.append(data);
//secondslideul.append(data);
thirdslideulload.addClass("w3-hide");
thirdslideultryagainbtn.addClass("w3-hide");
thirdslideulloadprg.html("loading read stories...");
thirdslideulload.addClass("w3-text-blue");
thirdslideulloadspin.addClass("w3-spin")
thirdslideulload.removeClass("w3-text-red");
}else{
thirdslideulloadprg.html("<i class='fa fa-meh-o'></i> something went wrong");
thirdslideulload.removeClass("w3-text-blue");
thirdslideulloadspin.removeClass("w3-spin")
thirdslideulload.addClass("w3-text-red");
thirdslideultryagainbtn.removeClass("w3-hide");	
}
},
error:function(xhr,status,err){
thirdslideulloadprg.html("<i class='fa fa-exclamation-circle'></i> connection error");
thirdslideulload.removeClass("w3-text-blue");
thirdslideulloadspin.removeClass("w3-spin")
thirdslideulload.addClass("w3-text-red");
thirdslideultryagainbtn.removeClass("w3-hide");
}
});

}//closing if statement


});
//code to handle opening of followed stories ends here

//code to handle opening of follwers starts here
$("#followerstoryswipe,#fourthslideultryagainbtn").click(function(){
firstslideul.addClass("w3-hide");
secondslideul.addClass("w3-hide");
thirdslideul.addClass("w3-hide");
//set the needed elements to normal
fourthslideulload.addClass("w3-hide");
fourthslideultryagainbtn.addClass("w3-hide");
fourthslideulloadprg.html("loading followers...");
fourthslideulload.addClass("w3-text-blue");
fourthslideulloadspin.addClass("w3-spin")
fourthslideulload.removeClass("w3-text-red");
//code to handle opening of followers ends here 
fourthslideul.removeClass("w3-hide");

if($("#fourthslideul li").length == 0){
fourthslideulload.removeClass("w3-hide");
$.ajax({
url:"oaumeetupdatingstories.php",
method :"post",
data :{getfollowers:""},
success:function(data){
if(data.indexOf("no followers") > -1){
fourthslideulloadprg.html("You have no followers yet");
fourthslideulloadspin.removeClass("w3-spin");
}else if(data.indexOf("li") > -1){
//alert(data);
fourthslidectn.append(data);
fourthslideulload.addClass("w3-hide");
fourthslideultryagainbtn.addClass("w3-hide");
fourthslideulloadprg.html("loading read stories...");
fourthslideulload.addClass("w3-text-blue");
fourthslideulloadspin.addClass("w3-spin")
fourthslideulload.removeClass("w3-text-red");
}else{
fourthslideulloadprg.html("<i class='fa fa-meh-o'></i> something went wrong");
fourthslideulload.removeClass("w3-text-blue");
fourthslideulloadspin.removeClass("w3-spin")
fourthslideulload.addClass("w3-text-red");
fourthslideultryagainbtn.removeClass("w3-hide");	
}
},
error:function(xhr,status,err){
fourthslideulloadprg.html("<i class='fa fa-exclamation-circle'></i> connection error");
fourthslideulload.removeClass("w3-text-blue");
fourthslideulloadspin.removeClass("w3-spin")
fourthslideulload.addClass("w3-text-red");
fourthslideultryagainbtn.removeClass("w3-hide");
}
});
}//if statement 
});
//code to handle opening of followers ends here

});
//document.ready function ends here

function moreread() {
var btn = $("#loadmorereadstories");
var show = $("#loadmorereadstoriesshow");
var prg = $("#loadmorereadstoriesprg");
btn.attr("disabled",true);
show.addClass("w3-hide");
prg.removeClass("w3-hide");
$.ajax({
url:"oaumeetupdatingstories.php",
method:"post",
data:{loadmorereadstories:""},
success:function(data){
if(data.indexOf("no read") > -1){
show.html("no read stories left to load..");
prg.addClass("w3-hide");
show.removeClass("w3-hide");
}else if(data.indexOf("Failed") > -1){
alert("Something went wrong please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
btn.before(data);
prg.addClass("w3-hide");
show.removeClass("w3-hide");
btn.attr("disabled",false);
}
},
error:function(xhr,status,err){
alert("could not load more stories maybe due to bad connection please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
});
}

//code to handle loading of more stories starts here
function  morefollowing(){
var btn = $("#loadmorefollowing");
var show = $("#loadmorefollowingshow");
var prg = $("#loadmorefollowingprg");
btn.attr("disabled",true);
show.addClass("w3-hide");
prg.removeClass("w3-hide");
$.ajax({
url:"oaumeetupdatingstories.php",
method:"post",
data:{loadmorefollowingstories:""},
success:function(data){
if(data.indexOf("no followed") > -1){
show.html("no read stories left to load..");
prg.addClass("w3-hide");
show.removeClass("w3-hide");
}else if(data.indexOf("Failed") > -1){
alert("Something went wrong please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
btn.before(data);
prg.addClass("w3-hide");
show.removeClass("w3-hide");
btn.attr("disabled",false);
}
},
error:function(xhr,status,err){
alert("could not load more stories maybe due to bad connection please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
});
}
//code to handle loading of more stories ends here

//code to handle loading of more followers starts here
function morefollowers(){
var btn = $("#loadmorefollowers");
var show = $("#loadmorefollowersshow");
var prg = $("#loadmorefollowersprg");
btn.attr("disabled",true);
show.addClass("w3-hide");
prg.removeClass("w3-hide");
$.ajax({
url:"oaumeetupdatingstories.php",
method:"post",
data:{loadmorefollowers:""},
success:function(data){
alert(data);
if(data.indexOf("no followers") > -1){
show.html("no more followers..");
prg.addClass("w3-hide");
show.removeClass("w3-hide");
}else if(data.indexOf("Failed") > -1){
alert("Something went wrong please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
btn.before(data);
prg.addClass("w3-hide");
show.removeClass("w3-hide");
btn.attr("disabled",false);
}
},
error:function(xhr,status,err){
alert("could not load more stories maybe due to bad connection please try again");
btn.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
});
}
//code to handle loading of more followers ends here

//function to handle unfollowing of a particular story starts here
function unfollow(writerid){
if(writerid != ""){
var con = confirm("Are you sure you want to unfollow this story?");
if(con != true){
return;
}
var writerid = writerid;
var ctn = $("#follower"+writerid);
var btn = $("#btn"+writerid);
var prg = $("#showunfollowprg"+writerid);
var show = $("#showunfollow"+writerid);
btn.attr("disabled",true);
prg.removeClass("w3-hide");
show.addClass("w3-hide");
$.ajax({
url :"oaumeetupdatingstories.php",
method:"post",
data:{unfollowsharp:writerid},
success:function(data){
//alert(data);
if(data.indexOf("Failed") > -1){
btn.attr("disabled",false);
prg.addClass("w3-hide");
show.removeClass("w3-hide");
}else if(data.indexOf("success") > -1){
ctn.remove();
}else{
btn.attr("disabled",false);
prg.addClass("w3-hide");
show.removeClass("w3-hide");
}
},
error:function(xhr,status,err){
btn.attr("disabled",false);
prg.addClass("w3-hide");
show.removeClass("w3-hide");
}
});
}//if statement 
}
//function to handle unfollowing of a particular story ends here

//function to unfollow particular user starts here
function remove(userid) {
if(userid != ""){
var con = confirm("Are you sure you want to remove this user from your followers list?");
if(con != true){
return;
}
var userid = userid;
var ctn = $("#followers"+userid);
var btn = $("#btn"+userid);
var prg = $("#showunfollowprg"+userid);
var show = $("#showfollower"+userid);
btn.attr("disabled",true);
prg.removeClass("w3-hide");
show.addClass("w3-hide");
$.ajax({
url :"oaumeetupdatingstories.php",
method:"post",
data:{removefollower:userid},
success:function(data){
//alert(data);
if(data.indexOf("Failed") > -1){
btn.attr("disabled",false);
prg.addClass("w3-hide");
show.removeClass("w3-hide");
}else if(data.indexOf("success") > -1){
ctn.remove();
}else{
btn.attr("disabled",false);
prg.addClass("w3-hide");
show.removeClass("w3-hide");
}
},
error:function(xhr,status,err){
btn.attr("disabled",false);
prg.addClass("w3-hide");
show.removeClass("w3-hide");
}
});
}//if statement 
}
//function to unfollow particular user ends here

</script>
<!--javascript-->
</body>
</html>