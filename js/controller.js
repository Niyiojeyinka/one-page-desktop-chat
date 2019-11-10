const state = {
	mineMessagePosition:"right",
	mineChatTemplate: "<test>",
	imageChatTemplate:"",
	fileChatTemplate:"",
	videoChatTemplate:"",
	conversation_id: "hey",
	friendsData: "loading..."
	 };

function sendGetRequest(url){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      
      state.friendsData= JSON.parse(this.responseText);
    }
  };
  xhttp.open("GET", url, true);
  xhttp.send();
}
