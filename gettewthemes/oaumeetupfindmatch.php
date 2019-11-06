<?php
require "classfindmatch.php";
$fmatch=$fonttype="";
$fmatch = new findmatch();
if($fmatch->validateUser() == "false") {
header("location:oaumeetuplogin.php");
exit();
}
//to prevent redundancy of code starts here
if(count($_POST)  == 0){
$fonttype = $fmatch->getFont();
$searchmatch = $fmatch->getlovematch();
}
//to prevent redundancy of code ends here
//code to handle loading of more love match starts here
if(isset($_POST["loadmorematches"])){
echo $fmatch->loadmorematches();
exit();
}
//code to handle loading of more love match ends here
?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|Find Match</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="lazysizemin.js"></script>
<style type="text/css">
@media only screen and (max-width: 600px) {
.love_match_list{
	width: 90%;
}
}
@media only screen and (min-width: 600px) {
	.love_match_list{
	width: 90%;
}

}
@media only screen and (min-width: 768px) {
.love_match_list{
	width: 500px;
}
} 
@media only screen and (min-width: 992px) {

.love_match_list{
	width: 900px;
}
} 
@media only screen and (min-width: 1200px) {
 .love_match_list{
	width: 900px;
}
}
</style>
</head>
<body class="w3-light-grey"style="font-family:<?php echo $fonttype;?>">
<!--navbar starts here-->
<?php include_once "navbar2.php";?>
<!--navbar ends here-->
<!--Mainpage starts here-->
<div class=""style="margin-top:140px;margin-bottom:60px;">
<!--find match container starts here-->
<ul id='findmatchul'class="w3-ul" style="">
<?php echo $searchmatch;?>
</ul>
<!--find match container ends here-->
</div>
<!--Mainpage ends here-->
<!--footer-->
<?php include_once "footer.php";?>
<!--footer-->
<!--script-->
<script type="text/javascript">
$(function(){
var showmorematchbtn =findmatchul=showmorematchshow=showmorematchprg="";
showmorematchbtn= $("#showmorematchbtn");
findmatchul = $("#findmatchul");
showmorematchshow = $("#showmorematchshow");
showmorematchprg = $("#showmorematchprg");

showmorematchbtn.click(function(){
showmorematchbtn.attr("disabled",true);
showmorematchshow.addClass("w3-hide");
showmorematchprg.removeClass("w3-hide");
$.ajax({
url:"oaumeetupfindmatch.php",
method:"post",
data:{loadmorematches:""},
success:function(data){
if(data.indexOf('nomatches') > -1){
showmorematchshow.html("no more matches to get yet..");
showmorematchshow.removeClass("w3-hide");
showmorematchprg.addClass("w3-hide");
setTimeout(function(){
showmorematchshow.html("<i class='fa fa-heart w3-text-red'></i> click to find more matches");
showmorematchbtn.attr("disabled",false);	
}, 5000);
}else if(data.indexOf("li") > -1){
showmorematchbtn.before(data);
showmorematchbtn.attr("disabled",false);
showmorematchshow.removeClass("w3-hide");
showmorematchprg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
showmorematchbtn.attr("disabled",false);
showmorematchshow.removeClass("w3-hide");
showmorematchprg.addClass("w3-hide");
}

},
error:function(xhr,status,err){
alert("could not load more due to bad connection please try again");
showmorematchbtn.attr("disabled",false);
showmorematchshow.removeClass("w3-hide");
showmorematchprg.addClass("w3-hide");
}
});
});

});
</script>
<!--script-->
</body>
</html>
