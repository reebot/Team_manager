			<div class="span9">
				<div class="row">
					<div class="span6"><h2 style="margin-top:0">My payments</h2></div>
				</div>
				<div class="widget widget-box">
		        	<div class="widget-header"><h3>Player payments</h3></div>
		        		<div class="widget-content" style="min-height:250px">
						
								<?php if( ! $this->Payments ): ?>
									There are no recent transactions.
								<?php else: ?>
												
									<table width="100%" class="table">
													
										<thead>
											<tr>
												<th style="width:30%" >Date</th>
												<th>Fixture</th>
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
													
													<td>&pound;<?php echo number_format($payment->amount, 2) ?></td>
												</tr>
															
											<?php endforeach ?>
										</tbody>
													
									</table>
								<?php endif ?>
						
							</div>
						</div>
				</div>
