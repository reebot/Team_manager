<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.js"></script>
<div pub-key="pub-39361f7b-6f1b-4949-a3ea-b2983637dd20" sub-key="sub-93b7356b-318b-11e1-bcd0-17179728d6d1" ssl="off" origin="pubsub.pubnub.com" id="pubnub"></div>
<script src="http://cdn.pubnub.com/pubnub-3.1.min.js"></script>
<script>$(document).ready(function() {

    // LISTEN FOR MESSAGES
    PUBNUB.subscribe({
        channel  : $("#ChatChannel input[name=channel]").val(),      // CONNECT TO THIS CHANNEL.
        error    : function() {        // LOST CONNECTION (auto reconnects)
            alert("Connection Lost. Will auto-reconnect when Online.")
        },
        callback : function(message) { // RECEIVED A MESSAGE.
            
			$("#ChatWindow #ChatMessages").prepend(message + "<br/>");

        },
        connect  : function() {        // CONNECTION ESTABLISHED.	    
        }
    });


	$("#ChatActions form").submit(function() {
		
		PUBNUB.publish({
	        channel : $("#ChatChannel input[name=channel]").val(),
	        message : $("#ChatActions input[name=message]").val()
	    });
	
		$("#ChatActions input[name=message]").val("");
		
		return false;
	});
	
});</script>
<!-- start boxes -->
<div class="gradient-up-with-border pt30 pb20">
	<div class="full-width">
		<h2>Messages</h2>
    			<div class="one-third">
	
			        <div class="outer-box"><div class="inner-box-filled-grey">
			            <h3></h3>
			            
							<div id="ChatWindow">

								<div id="ChatMessages" style="min-height:200px;"></div>
								
								<div id="ChatChannel">
									<input type="hidden" name="channel" placeholder="007" value="007" />
								</div>

								<div id="ChatActions" style="min-width:100px">
									<form method="POST" action="<?php echo current_url() ?>">
										<input type="text" name="message" placeholder="Type Your Message Here..." />
										<input type="submit" value="Send" />
									</form>
								</div>

							</div>
			        
					</div>
					</div>
			
			    </div>
		    <div class="clear"></div>
		</div>
	</div>
<!-- end boxes -->

