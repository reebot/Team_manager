			<div class="span9">
				<div class="row">
					<div class="span6"><h2 style="margin-top:0">All payments</h2></div>
					</div>
				<div class="widget widget-box">
		        	<div class="widget-header"><h3>Player payments</h3></div>
		        		<div class="widget-content" style="min-height:250px">
						<?php if( ! $this->Payments ): ?>
							There are no recent transactions.
						<?php else: ?>
										
							<table class="table">
											
								<thead>
									<tr>
										<th>Date</th>
										<th>Fixture</th>
										<th>Player</th>
										<th>Game Block Date</th>
										<th>Amount</th>
									</tr>
								</thead>
											
								<tbody>
									<?php foreach( $this->Payments as $payment ): ?>
												
										<tr>
											<td><?php echo date("jS \of F o H:i", $payment->PaymentTime) ?></td>
											<td>
												<a href="<?php echo url("fixtures/view/".$payment->FixtureID) ?>">
													<?php echo $payment->FixtureLocation ?>
												</a>
											</td>
											<td><?php echo ucwords(strtolower( $payment->first_name." ".$payment->last_name )) ?></td>
											<td><?php echo ( $payment->GameBlockTime) ? date("jS \of F o H:i", $payment->GameBlockTime) : "Unknown" ?></td>
											<td>&pound;<?php echo number_format($payment->amount, 2) ?></td>
										</tr>
													
									<?php endforeach ?>
								</tbody>
											
							</table>
						<?php endif ?>
		        	</div>
				</div>
			</div>
						

