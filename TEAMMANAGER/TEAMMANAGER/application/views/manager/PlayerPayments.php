<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	
	$("input[name=paid]").click(function() {
		
		$.ajax({
			url: "<?php echo url('manager/Payment/') ?>" + $(this).attr("payment_id") + "/" + ( ( $(this).is(":checked") ) ? 1 : 0 ),
			dataType: "json",
			type: "POST",
			success: function(data) {
				
				if( data.error != undefined )
				{
					alert( data.error );
				}
				
			}
		});
		
	});
	
});
</script>

<div class="span9">
	<div class="row">
    	<div class="span6"><h2 style="margin-top:0"><?php echo ucwords(strtolower( $this->Player->first_name." ".$this->Player->last_name ))."'s" ?></h2></div>
    </div>
    	<div class="row">
		<div class="span8">
			<div class="widget widget-box">
	        	<div class="widget-header"><h3>Payments</h3></div>
		        	<div class="widget-content">

					
					<?php if( ! $this->Payments->exists() ): ?>
						There are no payments from this player.
					<?php else: ?>
					
						<table width="100%" class="table">
								
							<thead>
								<tr>
									<th>Date</th>
									<th>Fixture</th>
									<th>Type</th>
									<th>Amount</th>
									<th>Status</th>
									<th>Paid</th>
								</tr>
							</thead>
								
							<tbody>
								<?php foreach( $this->Payments as $Payment ): ?>
									
									<tr>
										<td><?php echo date("jS \of F o H:i", $Payment->date) ?></td>
										<td>
											<a href="<?php echo url("fixtures/view/".$Payment->fixture_id) ?>">
												<?php echo $Payment->fixture_location ?>
											</a>
										</td>
										<td>
											<?php if( $Payment->amount > 0 ): ?>
												<?php if( $Payment->type == "game" ): ?>
												
													Pay As You Play ( Game Date: <?php echo ( $Payment->game_block_time) ? date("jS \of F o H:i", $Payment->game_block_time) : "Unknown" ?> )
												
												<?php elseif( $Payment->type == "block" ): ?>
												
													Block Payment
												
												<?php endif ?>
											<?php else: ?>
												Refund
											<?php endif ?>
										</td>
										<td>&pound;<?php echo number_format($Payment->amount, 2) ?></td>
										<td>
											<?php echo ucwords( $Payment->status ) ?>
										</td>
										<td>
											<?php if( in_array( strtolower($Payment->status), array("active") ) ):  ?>
												<input type="checkbox" name="paid" value="1" payment_id="<?php echo $Payment->id ?>" <?php echo ( $Payment->paid ) ? "checked='checked'" : "" ?> />
											<?php endif ?>
										</td>
									</tr>
										
								<?php endforeach ?>
							</tbody>
								
						</table>
						
					
					<?php endif ?>
		        	</div>
			</div>
		</div>
    	</div>
</div>

