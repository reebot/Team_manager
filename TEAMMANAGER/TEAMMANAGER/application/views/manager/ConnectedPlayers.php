<div class="span9">
	<div class="row">
    	<div class="span6"><h2 style="margin-top:0">Connected players</h2></div>
    </div>
	<div class="row">
		
		<?php if( ! $this->Players ): ?>
			<h4>You are not connected to any Players.</h4>
		<?php endif ?>
		
		<?php foreach( $this->Players as $Player ): ?>
			<?php $avatar = ( $Player->avatar ) ? url("public/uploads/".$Player->avatar, true) : url("public/img/player.png", true) ?>
		<div class="span3">
		<div class="widget widget-box">
			<div class="widget-header-players-box">
				<span style="font-size:19px; font-weight: 800; margin:30px 0 0 10px"><?php echo ucwords(strtolower($Player->first_name." ".$Player->last_name)) ?></span>
				<img class="img-polaroid" style="float:right; margin:5px 5px 0 0;" src="<?php echo $avatar ?>" />
			
			</div>
				<div class="widget-content">
					<div class="row-fluid">
						<div class="span7">
							<h5 style="margin-top:20px">Outstanding fees:</h5>
						</div>
						
						<div class="span5">
							<?php if( $Player->TotalOwned > 0 ): ?>
							<h3 style="color: #f00">&pound;<?php echo number_format($Player->TotalOwned, 2) ?></h3>
										
							<?php elseif( $Player->TotalOwned == 0 ): ?>
							<h3 style="">&pound;<?php echo number_format($Player->TotalOwned, 2) ?></h3>	
							<?php else: ?>
							<h3 style="">Game Credit: &pound;<?php echo number_format(abs($Player->TotalOwned), 2) ?></h3>		
							<?php endif ?>
						</div>
						
					</div>
					
					<div class="row-fluid">
						<div class="span7">
							<h5 style="margin-top:20px">Played games:</h5>
						</div>
						
						<div class="span4">
							<h3>0</h3>
						</div>
						
					</div>
							
					<a href="<?php echo url("manager/PlayerPayments/".$Player->id) ?>" class="btn btn-inverse btn-small"><span>Payments</span></a>
					
				</div>
				
				
		</div>
		</div>
		<?php endforeach ?>
		
	</div>
										
										

					
