<?php
require "classnotification.php";
$notify=$fonttype=$notes="";
$notify = new notification();
if($notify->validateUser() == "false") {
header("location:oaumeetuplogin.php");
exit();
}
if(count($_POST) == 0){
$fonttype = $notify->getFont();
$notes = $notify->getnotes();
$notify->updatenotes();
}
//code to handle getting of more notifications starts here
if(isset($_POST["morenote"])){
echo $notify->getmorenotes();
exit();
}
//code to handle getting of more notifications ends here
?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|Notifications</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="lazysizemin.js"></script>
</head>
<body class="" style="font-family:<?php echo $fonttype;?>">
<!--navbar starts here-->
<?php include_once "navbar2.php";?>
<!--navbar ends here-->
<!--mainpage start here-->
<div class=""style="margin-top:140px;margin-bottom:60px;">
<!--conatiner list for  notification starts here-->
<ul class="w3-ul" id="notifyctn">
<?php echo $notes?>
</ul>
<!--conatiner list for  notification ends here-->
</div>
<!--mainpage ends here-->
<!--footerr-->
<?php include_once "footer.php";?>
<!--footer-->
<!--script-->
<script type="text/javascript">
var morenotebtn =morenoteshow=morenoteprg=notifyctn="";
morenotebtn = $("#morenotebtn");
morenoteshow = $("#morenoteshow");
morenoteprg = $("#morenoteprg");
notifyctn = $("#notifyctn");

morenotebtn.click(function(){
morenotebtn.attr("disabled",true);
morenoteshow.addClass("w3-hide");
morenoteprg.removeClass("w3-hide");
$.ajax({
url:"notification.php",
method:"post",
data:{morenote:""},
success:function(data){
//alert(data);
if(data.indexOf("nomorenotes") > -1){
morenoteshow.html("no more notifications to get");
morenoteshow.removeClass("w3-hide");
morenoteprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
morenotebtn.before(data);
morenotebtn.attr("disabled",false);
morenoteshow.removeClass("w3-hide");
morenoteprg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
morenotebtn.attr("disabled",false);
morenoteshow.removeClass("w3-hide");
morenoteprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not load more notifications due to bad connection please try again");
morenotebtn.attr("disabled",false);
morenoteshow.removeClass("w3-hide");
morenoteprg.addClass("w3-hide");
}
});
});
</script>
<!--script-->
</body>
</html>