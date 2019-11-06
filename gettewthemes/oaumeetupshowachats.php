<?php
require "classshowachats.php";
$showachat=$fonttype="";
$showachat = new showachats();
if($showachat->validateUser() == "false") {
header("location:oaumeetuplogin.php");
exit();
}
if(count($_POST) == 0){
$fonttype = $showachat->getFont();
$anewchats = $showachat->getAnonymousNewMsg();
$aoldchats = $showachat->getAnonymousOldMsg();
}

//code to get old anonymous message starts here
if(isset($_POST["moreoachat"])){
echo $showachat->getmoreoachat();
exit();
}
//code to get old anonymous message ends here

//code to get new anonymous message starts here
if(isset($_POST["morenachat"])){
echo $showachat->getmorenachat();
exit();
}
//code to get new anonymous message ends here

?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|Show Anonymous Chat</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="lazysizemin.js"></script> 
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css"> 
</head>
<body class="w3-light-grey" style="font-family:<?php echo $fonttype;?>">
<!--navbar starts here-->
<?php include_once "navbar2.php";?>
<!--navbar ends here-->

<!--mainpage starts here-->
<div class=""style="margin-top:150px;margin-bottom:60px;">
<!--new anonymous chat start here-->
<ul class="w3-ul w3-animate-left" id='newmsg'>
<?php echo $anewchats;?>
</ul>
<!--new anonymous chat ends here-->	
<!--stale anonymous chat starts here-->
<ul class="w3-ul w3-animate-left w3-hide" id ='stalemsg'>
<?php echo $aoldchats;?>
</ul>
<!--stale anonymous chat ends here-->
</div>
<!--mainpage ends here-->
<!--footer-->
<div class="w3-bar w3-light-grey w3-card-2 w3-bottom"style="vertical-align:bottom;">
<button id='snewmsg'class="w3-bar-item w3-button w3-center w3-small w3-hover-blue w3-text-blue w3-hover-text-white"style='width:50%;height:100%;'><img src="chathide.jpg" class="" style="width:25px;height:25px;"> New Anonymous messages</button>
<button id='soldmsg'class="w3-bar-item w3-button w3-center w3-small  w3-hover-blue w3-text-blue  w3-hover-text-white"style='width:50%;height:100%;'><img src="chathide.jpg" class="" style="width:25px;height:25px;"> Stale Anonymous Messages</button>
</div>
<!--footer-->
<!--script-->
<script type="text/javascript">
$(function(){
var newmsg=stalemsg=snewmsg=soldmsg=moreoachatbtn=moreoachatshow=moreoachatprg=morenachatbtn=morenachatshow=morenachatprg="";
newmsg = $("#newmsg");
stalemsg = $("#stalemsg");
snewmsg = $("#snewmsg");
soldmsg = $("#soldmsg");
moreoachatbtn = $("#moreoachatbtn");
moreoachatshow = $("#moreoachatshow");
moreoachatprg = $("#moreoachatprg");
morenachatbtn = $("#morenachatbtn");
morenachatshow = $("#morenachatshow");
morenachatprg = $("#morenachatprg");
snewmsg.click(function(){
stalemsg.addClass("w3-hide");
newmsg.removeClass("w3-hide");
});

soldmsg.click(function(){
stalemsg.removeClass("w3-hide");
newmsg.addClass("w3-hide");
});

//code to handle getting of more chat messages starts here
moreoachatbtn.click(function(){
moreoachatbtn.attr("disabled",true);
moreoachatshow.addClass("w3-hide");
moreoachatprg.removeClass("w3-hide");
$.ajax({
url:"oaumeetupshowachats.php",
method:"post",
data:{moreoachat:""},
success:function(data){
//alert(data);
if(data.indexOf("nomoreoachat") > -1){
moreoachatshow.html("no more chat to get");
moreoachatshow.removeClass("w3-hide");
moreoachatprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
moreoachatbtn.before(data);
moreoachatbtn.attr("disabled",false);
moreoachatshow.removeClass("w3-hide");
moreoachatprg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
moreoachatbtn.attr("disabled",false);
moreoachatshow.removeClass("w3-hide");
moreoachatprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not load more notifications due to bad connection please try again");
moreoachatbtn.attr("disabled",false);
moreoachatshow.removeClass("w3-hide");
moreoachatprg.addClass("w3-hide");
}
});
});

//code to handle getting more new anonymous messages
morenachatbtn.click(function(){
morenachatbtn.attr("disabled",true);
morenachatshow.addClass("w3-hide");
morenachatprg.removeClass("w3-hide");
$.ajax({
url:"oaumeetupshowachats.php",
method:"post",
data:{morenachat:""},
success:function(data){
//alert(data);
if(data.indexOf("nomorenachat") > -1){
morenachatshow.html("no more chat to get");
morenachatshow.removeClass("w3-hide");
morenachatprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
morenachatbtn.before(data);
morenachatbtn.attr("disabled",false);
morenachatshow.removeClass("w3-hide");
morenachatprg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
morenachatbtn.attr("disabled",false);
morenachatshow.removeClass("w3-hide");
morenachatprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not load more notifications due to bad connection please try again");
morenachatbtn.attr("disabled",false);
morenachatshow.removeClass("w3-hide");
morenachatprg.addClass("w3-hide");
}
});
});
//code to handle getting more new anonymous messages

});		
</script>
<!--script-->
</body>
</html>