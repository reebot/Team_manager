<div class="span9">
	<?php
	
		if( Auth::Get("manager") )
		{
			$this->load->view("accounts/payments_manager.php");
		}
		else
		{
			$this->load->view("accounts/payments_player.php");
		}
	
	?>		
</div>
