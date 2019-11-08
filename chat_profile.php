<?php
require 'backend/ChatProfile.php';

$profile= new ChatProfile();
var_dump($profile->getUserProfileById(1));

?>

<div  class="w3-opacity w3-pink w3-padding-jumbo w3-center" style="background-image: url('./images/profile6.jpg');height: 100vh">
	<br>
	<center>
<div class="w3-circle w3-white w3-center w3-border w3-border-white w3-bottombar w3-topbar w3-leftbar w3-rightbar" style="height: 150px;width: 150px;overflow: hidden;">
	

<img src='./images/profile6.jpg' class="w3-image" style="height: 150px;width: 150px;">

</div>
<br>
<span class="w3-xlarge">Willi Deborah</span>
<br>
<i class="fa fa-map-marker w3-xlarge"></i><span class="w3-large"> Lagos , Nigeria</span>
<br>
<div class="w3-margin">
<i class="w3-bold fa fa-qoute-left w3-white"></i><span class="w3-small" style="letter-spacing: 1.5px;"> I treat fucked up asses like a free pussy!!!</span>
<i class="w3-bold fa fa-qoute-right w3-white"></i>
</div>
</center>
</div>