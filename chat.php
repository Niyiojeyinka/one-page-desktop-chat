<!--<div class="w3-container" style="height: 100vh;overflow-y: scroll;" id="cover">-->
	
      <div class="inner w3-white">
        <div data-chat class="element w3-padding">	

<!--MAIN PAGE CONTENT STARTS HERE-->


<br><br>
<span>Click user profile to view Conversation</span>



<!--</div>-->
</div>

</div>
<div data-key class="w3-center w3-bar w3-light-grey" style="padding-left: 48px;padding-top: 8px;padding-bottom: 8px;display:none">

<span class="w3-white  w3-border w3-margin w3-round-large" style="">
<span class="w3-padding w3-button w3-bar-item" style="height: 50px;margin-top:5px"  onclick="document.getElementById('fileModal').style.display='block'"><i class="fa fa-plus w3-text-pink"></i></span>
	<textarea  class="w3-bar-item w3-margin-bottom" placeholder="Your Message here" style="width:70%;word-wrap:break-word;resize: none;padding:5px;height:50px;outline:none;margin-top:5px;overflow:hidden;"></textarea>
 <span class="w3-padding w3-button w3-bar-item" style="height: 50px;margin-top:5px" onclick="saveMessage()"><i class="fa fa-location-arrow w3-text-pink"></i> <span class="w3-tiny">Send</span></span>
</span>
</div>




<!-- The Modal -->
<div id="fileModal" class="w3-modal w3-center">
  <div class="w3-modal-content">
    <div class="w3-container">
      <span onclick="document.getElementById('fileModal').style.display='none'"
      class="w3-button w3-display-topright">&times;</span>
      <p>Please Select File to Send</p><br>
         <div class="w3-padding-jumbo">
         <div class="upload-btn-wrapper ww-margin">
  <button class="btn">Upload a file</button>
  
  <input onChange="fileChosen(event)" type="file" name="myfile" id="FileUpload" />
</div>
<br>
<div class="w3-container w3-center" data-image-preview>
<!--if image the preview goes here-->

</div>
<button onClick="sendFile()" class="w3-button w3-margin w3-border  w3-round w3-large"><i class="fa fa-location-arrow w3-text-pink"></i> <span class="w3-tiny">Send</span></button>


         </div>
    </div>
  </div>
</div>