<style type="text/css">
body {
  font-family: helvetica;
}

.chat {
  width: 100%;
  border: solid 1px #EEE;
  display: flex;
  flex-direction: column;
  //padding: 10px;
}

.messages {
  margin-top: 30px;
  display: flex;
  flex-direction: column;
}

.message {
  border-radius: 20px;
  padding: 8px 15px;
  margin-top: 5px;
  margin-bottom: 5px;
  display: inline-block;
}

.yours {
  align-items: flex-start;
}

.yours .message {
  margin-right: 25%;
  background-color: #eee;
  position: relative;
}

.yours .message.last:before {
  content: "";
  position: absolute;
  z-index: 0;
  bottom: 0;
  left: -7px;
  height: 20px;
  width: 20px;
  background: #eee;
  border-bottom-right-radius: 15px;
}
.yours .message.last:after {
  content: "";
  position: absolute;
  z-index: 1;
  bottom: 0;
  left: -10px;
  width: 10px;
  height: 20px;
  background: white;
  border-bottom-right-radius: 10px;
}

.mine {
  align-items: flex-end;
}

.mine .message {
  color: white;
  margin-left: 25%;
  background: linear-gradient(to bottom, #e91e63 0%, #E91358 100%);
  background-attachment: fixed;
  position: relative;
}

.mine .message.last:before {
  content: "";
  position: absolute;
  z-index: 0;
  bottom: 0;
  right: -8px;
  height: 20px;
  width: 20px;
  background: linear-gradient(to bottom, #e91e63 0%, #E91358 100%);
  background-attachment: fixed;
  border-bottom-left-radius: 15px;
}

.mine .message.last:after {
  content: "";
  position: absolute;
  z-index: 1;
  bottom: 0;
  right: -10px;
  width: 10px;
  height: 20px;
  background: white;
  border-bottom-left-radius: 10px;
}

/*
#cover::-webkit-scrollbar { 
	width:0px; 
 }//work but notcompatible with most browser but on chrome and other webkit app
	*/

  .element, .outer {
     width: 100%;
     height: 100vh;}
      
      .outer {
      position: relative;
      overflow: hidden;}
      
      .inner {
     // position: absolute;
      left: 0;
      overflow-x: hidden;
      overflow-y: scroll;
      } 
      .inner::-webkit-scrollbar {
      display: none;}
      
      .inner {
      -ms-overflow-style: none;  
      overflow: -moz-scrollbars-none; }

</style>