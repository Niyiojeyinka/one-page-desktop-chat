<?php
require 'classreplystorycomment.php';
$replyobj=$fontpref=$commentitself=$replies=$storyid="";
$replyobj = new replystorycomment();
if($replyobj->validateUser() == "false"){
header("location:oaumeetuplogin.php");
exit();
}
$fontpref = $replyobj->getFont();
//code to prevent redundancy or 
if(count($_POST) == 0){
if(isset($_GET["commentid"])){
$replyobj->setcommentid($_GET["commentid"]);
}else{
echo "sorry missing values to continue";
exit();
}
$commentitself = $replyobj->getthecomment();
$replies = $replyobj->getcommentreplies();
$storyid = $replyobj->getstoryid();
}

//code to handle posting of reply starts here
if(isset($_POST["postreply"])){
echo $replyobj->insertreply($_POST["postreply"]);
exit();
}
//code to handle posting of reply ends here
//code to handle getting more stale messages starts here
if(isset($_POST["getmoreoldreplycmnt"])){
echo $replyobj->getmoreoldreplies();
exit();
}

//code to handle getting more stale messages ends here

//code to handle getting more new messages starts here
if(isset($_POST["getmorenewreplycmnt"])){
echo $replyobj->getmorenewreplies();
exit();
}
//code to handle getting more new messages ends here
?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|replycomments</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content="social & meetup website replycomment">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="lazysizemin.js"></script>
</head>
<body class="" style="font-family:<?php echo $fontpref;?>">
<!--navbar starts here-->
<div class="w3-top w3-bar  w3-white"style='border-bottom:1px solid #ddd;'>
<a href="<?php if(isset($_SERVER["HTTP_REFERER"])) echo $_SERVER["HTTP_REFERER"]; else echo 'oaumeetupstorycomments.php?storyid=$storyid';?>" class="w3-card w3-round-large w3-text-blue w3-bar-item w3-margin-top w3-margin-bottom w3-hover-blue w3-hover-text-white w3-margin-left" style="text-decoration:none;"><i class="fa fa-arrow-left"></i></a>
<b class="w3-text-blue w3-bar-item"style='padding:0;margin-left:10px;margin-top:25px;'>Replycomments</b>
</div>
<!--navbar ends here-->
<!--mainpage starts here-->
<div style="margin-top:70px;margin-bottom:70px;">
<!--main comment starts here-->
<ul class="w3-ul" id='maincmmntul'>
<?php echo $commentitself;?>
</ul>
<!--main comment ends here-->
<!--reply container starts here-->
<ul class="w3-ul"id='replyctn'>
<?php echo $replies;?>
</ul>
<!--reply container ends here-->
</div>
<!--mainpage ends here-->
<!--footer-->
<form id="replycommentform"method="post"class="w3-bottom w3-bar w3-light-grey"style='width:100%;height:65px;padding:0;'action="">
<!--to make the text area act well make it a bar-item-->
<textarea  id="replycmmntarea"class="w3-bar-item w3-textarea"placeholder="Write a reply"style="width:80%;word-wrap:break-word;resize: none;padding:5px;height:65px;border-color:white;border-bottom:1px groove #2196F3" maxlength="160"></textarea>
<button id="replycmmntbtn" class='w3-center  w3-bar-item w3-blue w3-btn w3-round-large w3-hover-white w3-hover-text-blue'style='height:50px;width:15%;padding:0;margin-top:5px;margin-left:5px;'><i class='fa fa-send'></i></button>
<span id="prgctn"class="w3-bar-item w3-right w3-text-blue w3-hide w3-animate-left"><i class="fa fa-spinner w3-spin"></i> <span id="prgtxt">posting reply..</span></span>
<span id="fprgctn"class="w3-bar-item w3-right w3-text-red w3-hide w3-animate-left"><i class="fa fa-exclamation-circle"></i> Could not post reply please try again</span>
</form>	
<!--footer-->
<!--javascript-->
<script type="text/javascript">
var replycommentform=replycmmntarea=replycmmntbtn=prgctn=fprgctn=replyctn=numreplytxt=maincmmntul=showmoreoldreplycmntbtn=showmoreoldreplycmntshow=showmoreoldreplycmntprg=replybtn="";
replycommentform = $("#replycommentform");
replycmmntarea = $("#replycmmntarea");
replycmmntbtn = $("#replycmmntbtn");
prgctn = $("#prgctn");
fprgctn = $("#fprgctn");
replyctn = $("#replyctn");
numreplytxt= $("#numreplytxt");
maincmmntul = $("#maincmmntul");
replybtn = $("#replybtn");
showmoreoldreplycmntbtn = $("#showmoreoldreplycmntbtn");
showmoreoldreplycmntshow = $("#showmoreoldreplycmntshow");
showmoreoldreplycmntprg =$("#showmoreoldreplycmntprg");
showmorenewreplycmntbtn = $("#showmorenewreplycmntbtn");
showmorenewreplycmntshow = $("#showmorenewreplycmntshow");
showmorenewreplycmntprg =$("#showmorenewreplycmntprg");
//code to handle posting of reply starts here
replycommentform.submit(function(e){
e.preventDefault();
if(replycmmntarea.val() != ""){
replycmmntbtn.attr("disabled",true);
prgctn.removeClass("w3-hide");
fprgctn.addClass("w3-hide");
replycommentform.animate({height:"100px"});
$.ajax({
url:"replycomment.php",
method:"post",
data:{postreply:replycmmntarea.val()},
success:function(data){
//alert(data);
if(data.indexOf("failed") > -1){
alert(data);
replycmmntbtn.removeAttr("disabled");
prgctn.addClass("w3-hide");
fprgctn.removeClass("w3-hide");
}else if(data.indexOf("li") > -1){
var dat = data.split("_[]_");
/*alert(dat[0]);
alert(dat[1]);*/
if(replyctn.find("li").html() != undefined){
showmorenewreplycmntbtn.after(dat[0]);
//alert(dat[0]);
}else{
replyctn.html(dat[0]);	
}
numreplytxt.addClass("w3-hide");
numreplytxt.html(dat[1]);
numreplytxt.removeClass("w3-hide");
replycmmntbtn.removeAttr("disabled");
prgctn.addClass("w3-hide");
fprgctn.addClass("w3-hide");
replycommentform.animate({height:"65px"});
replycmmntarea.val("");
$(window).scrollTop($(maincmmntul).offset().top);
//alert($("#"+dat[2]).html());
//alert($(dat[2]).offset().top);
}else{
alert("something went wrong please try again");
replycmmntbtn.removeAttr("disabled");
prgctn.addClass("w3-hide");
fprgctn.removeClass("w3-hide");
}
},
error:function(xhr,status,err){
replycmmntbtn.removeAttr("disabled");
prgctn.addClass("w3-hide");
fprgctn.removeClass("w3-hide");
}
});
}
});
//code to handle posting of reply ends here

//code to handle focusing of something starts here
replybtn.click(function(){
replycmmntarea.focus();
});
//code to handle focusing of something ends here

//code to handle getting of more old replies starts here
showmoreoldreplycmntbtn.click(function(){
showmoreoldreplycmntbtn.attr("disabled",true);
showmoreoldreplycmntshow.addClass("w3-hide");
showmoreoldreplycmntprg.removeClass("w3-hide");
$.ajax({
url : "replycomment.php",
method : "post",
data:{getmoreoldreplycmnt:""},
success:function(data){
//alert(data);
if(data.indexOf("noreplies") > -1){
showmoreoldreplycmntshow.html("no more old replies to show");
showmoreoldreplycmntshow.removeClass("w3-hide");
showmoreoldreplycmntprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
showmoreoldreplycmntbtn.before(data);
showmoreoldreplycmntbtn.attr("disabled",false);
showmoreoldreplycmntshow.removeClass("w3-hide");
showmoreoldreplycmntprg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
showmoreoldreplycmntbtn.attr("disabled",true);
showmoreoldreplycmntshow.addClass("w3-hide");
showmoreoldreplycmntprg.removeClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("sorry could not load more old replies due to bad connection");
showmoreoldreplycmntbtn.attr("disabled",false);
showmoreoldreplycmntshow.removeClass("w3-hide");
showmoreoldreplycmntprg.addClass("w3-hide");
}
});
});
//code to handle getting of more old replies ends here

//code to handle getting of new replies starts here
showmorenewreplycmntbtn.click(function(){
var num = parseInt(numreplytxt.html());
showmorenewreplycmntbtn.attr("disabled",true);
showmorenewreplycmntshow.addClass("w3-hide");
showmorenewreplycmntprg.removeClass("w3-hide");
$.ajax({
url : "replycomment.php",
method : "post",
data:{getmorenewreplycmnt:""},
success:function(data){
//alert(data);
if(data.indexOf("noreplies") > -1){
showmorenewreplycmntshow.html("no new replies");
showmorenewreplycmntshow.removeClass("w3-hide");
showmorenewreplycmntprg.addClass("w3-hide");
setTimeout(function(){
showmorenewreplycmntshow.html("<i class='fa fa-comment-o'></i> Click to load new replies");
showmorenewreplycmntbtn.attr("disabled",false);
},5000);
}else if(data.indexOf("li") > -1){
var dat = data.split("_[]_");
showmorenewreplycmntbtn.after(dat[0]);
num += parseInt(dat[1]);
numreplytxt.html(num);
showmorenewreplycmntbtn.attr("disabled",false);
showmorenewreplycmntshow.removeClass("w3-hide");
showmorenewreplycmntprg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
showmorenewreplycmntbtn.attr("disabled",true);
showmorenewreplycmntshow.addClass("w3-hide");
showmorenewreplycmntprg.removeClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("sorry could not load more old replies due to bad connection");
showmorenewreplycmntbtn.attr("disabled",false);
showmorenewreplycmntshow.removeClass("w3-hide");
showmorenewreplycmntprg.addClass("w3-hide");
}
});
});
//code to handle getting of new replies ends here


/*cmmntbtn.click(function(e) {
e.preventDefault();
if(cmmntarea.val() != ""){
cmmntbtn.attr("disabled",true);
prgctn.removeClass("w3-hide");
fprgctn.addClass("w3-hide");
cmmntform.animate({height:"100px"});

$.ajax({
url : "oaumeetupstorycomments.php?storyid="+storyid,
method : "post",
data :{commentpost:cmmntarea.val()},
success:function(data) {
alert(data);
if(data.indexOf("failed") > -1){
cmmntbtn.removeAttr("disabled");
prgctn.addClass("w3-hide");
fprgctn.removeClass("w3-hide");
}else if (data.indexOf("failedes") > -1){
alert("Sorry your comment contains characters that are not accepted");
cmmntbtn.removeAttr("disabled");
prgctn.addClass("w3-hide");
fprgctn.addClass("w3-hide");
cmmntform.animate({height:"65px"});
}else{
var data = data.split("_^_");
cmmntctn.html(data[0]);
cmmntarea.val("");
numcmmnttxt.html(data[2]+"comments");
$(window).scrollTop($("#comment"+data[1]).offset().top-700);
//alert($("#comment"+data[1]).offset().top);
nocmmnt.addClass("w3-hide");
cmmntbtn.removeAttr("disabled");
prgctn.addClass("w3-hide");
fprgctn.addClass("w3-hide");
cmmntform.animate({height:"65px"});
}
},
error:function(xhr,status,err) {
cmmntbtn.removeAttr("disabled");
prgctn.addClass("w3-hide");
fprgctn.removeClass("w3-hide");
}
});
}//if cmment.val() !=""
});*/
</script>

<!--javascript-->
</body>
</html>