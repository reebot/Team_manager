<?php
	class Accounts extends CI_Controller
	{
		public function Login()
		{
			if( ! $this->input->post() )
			{
				$this->json->error("You need to send POST data.");
			}
			
			// Try logging in
			if( $Account = Account::Login($this->input->post("email"), $this->input->post("password")) )
			{
				$this->json->success(array(
					"first_name" => $Account->first_name,
					"last_name"  => $Account->last_name,
					"email"      => $Account->email,
					"manager"    => (bool) $Account->manager
				));
			}

			$this->json->error("Incorrect credentials.");
		}
		
		public function GetMine()
		{
			Auth::RequireLogin(true);
			
			$this->Account = new Account( Auth::Get("id") );
			
			if( ! $this->Account->exists() )
			{
				$this->json->error("Your account could not be loaded.");
			}
			
			$this->json->send(array(
				
				// Success
				"success" => "OK",
				
				// Account Info
				"Credits" => $this->Account->credits
				
			));
		}
		
		public function Payments()
		{
			Auth::RequireLogin(true);
			
			if( Auth::Get("manager") )
			{
				// Get Payments - Manager
				$this->Payments = $this->db->query("
					SELECT fixtures.id as FixtureID, accounts.first_name, accounts.last_name, payments.amount, fixtures.location as FixtureLocation, game_blocks.time as GameBlockTime, payments.date as PaymentTime
					FROM payments
					LEFT JOIN game_blocks ON game_blocks.id = payments.game_block_id
					LEFT JOIN accounts ON accounts.id = payments.account_id
					LEFT JOIN fixtures ON fixtures.id = game_blocks.fixture_id
					WHERE fixtures.owner = ".Auth::Get("id")."
					ORDER BY payments.id DESC 
				")->result();
				
				$Payments = array();
				
				foreach( $this->Payments as $Payment )
				{
					$Payments[] = array(
						"payment_time"     => date(TIME_FORMAT, $Payment->PaymentTime),
						"fixture_location" => $Payment->FixtureLocation,
						"name"             => ucwords(strtolower( $Payment->first_name." ".$Payment->last_name )),
						"block_time"       => ( $Payment->GameBlockTime ) ? date(TIME_FORMAT, $Payment->GameBlockTime) : "Unknown",
						"amount"           => number_format( $Payment->amount, 2 )
					);
				}
				
				$this->json->send(array(
				
					// Success
					"success"  => "OK",
					
					// Payments
					"Payments" => $Payments
					
				));
			}
			else
			{
				// Get Payments - Player
				$this->Payments = $this->db->query("
					SELECT fixtures.id as FixtureID, payments.amount, fixtures.location as FixtureLocation, game_blocks.time as GameBlockTime, payments.date as PaymentTime
					FROM payments
					LEFT JOIN game_blocks ON game_blocks.id = payments.game_block_id
					LEFT JOIN fixtures ON fixtures.id = game_blocks.fixture_id
					WHERE payments.account_id = ".Auth::Get("id")."
					ORDER BY payments.id DESC 
				")->result();
				
				$Payments = array();
				
				foreach( $this->Payments as $Payment )
				{
					$Payments[] = array(
						"payment_time"     => date(TIME_FORMAT, $Payment->PaymentTime),
						"fixture_location" => $Payment->FixtureLocation,
						"block_time"       => ( $Payment->GameBlockTime ) ? date(TIME_FORMAT, $Payment->GameBlockTime) : "Unknown",
						"amount"           => number_format( $Payment->amount, 2 )
					);
				}
				
				$this->json->send(array(
				
					// Success
					"success"  => "OK",
					
					// Payments
					"Payments" => $Payments
					
				));
			}
		}
	}
?>