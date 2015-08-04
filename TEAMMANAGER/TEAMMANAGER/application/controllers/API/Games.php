<?php
	class Games extends CI_Controller
	{
		public function All()
		{
			// Login is required
			Auth::RequireLogin(true);
			
			// Load Account
			$this->Account = new Account( Auth::Get("id") );
			
			// Make sure account is loaded
			if( ! $this->Account->exists() )
			{
				$this->json->error("Your account could not be loaded.");
			}
			
			// Fixture
			$this->Fixture = new Fixture();
			
			// Get My Fixtures
			$this->Fixtures = $this->Fixture->order_by("id", "DESC")->get_iterated();
			
			$Fixtures = array();
			
			// Loop through fixtures
			foreach( $this->Fixtures as $Fixture )
			{
				$Fixtures[] = array(
					"fixture_id" => $Fixture->id,
					"location"   => $Fixture->location,
					"type"       => $Fixture->type,
					"postcode"   => $Fixture->postcode
				);
			}
			
			// Send Response
			$this->json->success(array(
				"Fixtures" => $Fixtures
			));
		}
		
		public function MyGames()
		{
			// Login is required
			Auth::RequireLogin(true);
			
			// Load Account
			$this->Account = new Account( Auth::Get("id") );
			
			// Make sure account is loaded
			if( ! $this->Account->exists() )
			{
				$this->json->error("Your account could not be loaded.");
			}
			
			// Get My Fixtures
			$this->Fixtures = $this->Account->game_block->include_related("fixture", array("location", "id", "postcode"))->where("time > ", time())->or_where("time", 0)->get_iterated();
			
			$Fixtures = array();
			
			// Loop through fixtures
			foreach( $this->Fixtures as $Fixture )
			{
				$Fixtures[] = array(
					"fixture_id" => $Fixture->fixture_id,
					"block_id"   => $Fixture->id,
					"location"   => $Fixture->fixture_location,
					"time"       => ( $Fixture->time ) ? date(TIME_FORMAT, $Fixture->time) : 0,
					"postcode"   => $Fixture->fixture_postcode,
					"day"  		 => date("l", $Fixture->time),
					"future"	 => ( $Fixture->time > time() ) ? true : false,
				);
			}
			
			// Send Response
			$this->json->success(array(
				"Fixtures" => $Fixtures
			));
		}
		
		public function ManagedGames()
		{
			// Login is required
			Auth::RequireLogin(true);
			
			// Load Account
			$this->Account = new Account( Auth::Get("id") );
			
			// Make sure account is loaded
			if( ! $this->Account->exists() )
			{
				$this->json->error("Your account could not be loaded.");
			}
			
			// Fixture
			$this->Fixture = new Fixture();
			
			// Get My Fixtures
			$this->Fixtures = $this->Fixture->where( array("owner" => $this->Account->id) )->order_by("id", "DESC")->get_iterated();
			
			$Fixtures = array();
			
			// Loop through fixtures
			foreach( $this->Fixtures as $Fixture )
			{
				$Fixtures[] = array(
					"fixture_id" => $Fixture->id,
					"location"   => $Fixture->location,
					"type"       => $Fixture->type,
					"postcode"   => $Fixture->postcode
				);
			}
			
			// Send Response
			$this->json->success(array(
				"Fixtures" => $Fixtures
			));
		}
		
		public function GetGame($id = 0)
		{
			// Login is required
			Auth::RequireLogin(true);
			
			// Get Game Block
			$this->GameBlock = new Game_Block( $id );
			
			if( ! $this->GameBlock->exists() )
			{
				$this->json->error("Game Block could not be found.");
			}
			
			// Get Fixture
			$this->Fixture = $this->GameBlock->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be found.");
			}
			
			// Get Players
			$this->Players = array();
			
			foreach( $this->GameBlock->account->include_join_fields()->get_iterated() as $Player )
			{
				$this->PaymentID     = 0;
				$this->PaymentStatus = 0;
				$this->PaymentAmount = 0;

				if( strtolower($Player->join_type) == "game" )
				{
					$this->Payment = new Payment();
					$this->Payment = $this->Payment->where(array(
						"game_block_id" => $this->GameBlock->id,
						"type"          => "Game",
						"amount >"      => 0
					))->get();
						
					if( $this->Payment->exists() )
					{
						$this->PaymentID     = $this->Payment->id;
						$this->PaymentStatus = $this->Payment->paid;
						$this->PaymentAmount = $this->Payment->amount;
					}
				}
				
				$this->Players[ $Player->id ] = array(
					"id"         => $Player->id,
					"first_name" => $Player->first_name,
					"last_name"  => $Player->last_name,
					"status"     => (bool) $Player->join_active,
					"team"		 => $Player->join_team,
					"type"		 => $Player->join_type,
					"PaymentID"  => $this->PaymentID,
					"PaymentStatus" => $this->PaymentStatus,
					"PaymentAmount" => $this->PaymentAmount,
					"avatar"		=> ( $Player->avatar ) ? url("public/uploads/".$Player->avatar, true) : url("public/img/player.png", true)
				);
			}
			
			// Get Your Status on this game
			$this->YourStatus = 0;
			
			// If you are in this game and are active
			if( isset($this->Players[ Auth::Get("id") ]) && $this->Players[ Auth::Get("id") ]['status'] == "active" )
			{
				$this->YourStatus = 1;
			}
			
			$this->json->send(array(			
				// Success
				"success"          => "OK",
				
				// Game Block
				"time"        => ( $this->GameBlock->time == 0 ) ? "Unknown" : date(TIME_FORMAT, $this->GameBlock->time),
				"is_manager"  => ( $this->Fixture->owner == Auth::Get("id") ),
				"status"      => (bool) $this->YourStatus,
				"team_1"      => $this->GameBlock->team_1,
				"team_2"      => $this->GameBlock->team_2,
				
				// Fixture
				"location"    => $this->Fixture->location,
				"type"        => $this->Fixture->type,
				"pitch_cost"  => $this->Fixture->pitch_cost,
				"player_cost" => $this->Fixture->player_cost,
				"payp_cost"   => $this->Fixture->payp_cost,
				"blocks"      => $this->Fixture->blocks,
				"address"     => $this->Fixture->address,
				"location"    => $this->Fixture->location,
				"directions"  => $this->Fixture->directions,
				"postcode"    => $this->Fixture->postcode,
				"is_manager"  => ( $this->Fixture->owner == Auth::Get("id") ),
				
				// Players
				"Players"          => $this->Players,				
			));
		}
		
		public function Join($block = 0)
		{
			Auth::RequireLogin(true);
			
			if( ! $this->auth->isLogged() )
			{
				$this->json->error("You need to be logged in.");
			}
			
			$this->Block = new Game_Block( $block );
			
			// Make sure the game exists
			if( ! $this->Block->exists() )
			{
				$this->json->error("Block could not be found.");
			}
			
			// Make sure the game is not in the past
			if( $this->Block->time != 0 && $this->Block->time < time() )
			{
				$this->json->error("This block is already in the past.");
			}
			
			$this->Account = new Account( Auth::Get("id") );
				
			if( ! $this->Account->exists() )
			{
				$this->json->error("Your account could not be loaded.");
			}
			
			$this->Related_Account = $this->Block->account->where( "id", Auth::Get("id") )->include_join_fields()->get();
			
			if( $this->Related_Account->exists() && $this->Related_Account->join_active )
			{
				$this->json->error("You are already playing this block.");
			}
			
			// Get the Fixture
			$this->Fixture = $this->Block->fixture->get();
			
			// Make sure the fixture exists
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be loaded.");
			}
			
			// Check the limit of this block
			$this->PlayersCount = $this->Block->account->include_join_fields()->where(array("active" => 1))->count();
			
			if( $this->PlayersCount >= ( ((int) $this->Fixture->type) * 2 ) )
			{
				$this->json->error("This game is already full!");
			}
			
			if( $this->Fixture->payp_cost > 0 && ( ! $this->Related_Account->exists() || $this->Related_Account->join_type == "game" ) )
			{		
				$this->Account->credits -= $this->Fixture->payp_cost;
				$this->Account->save();
				
				// Add Payment Record
				$payment = new Payment();
				
				$payment->account_id    = Auth::Get("id");
				$payment->game_block_id = $this->Block->id;
				$payment->amount        = $this->Fixture->payp_cost;
				$payment->date          = time();
				$payment->type			= "game";
				$payment->fixture_id	= $this->Fixture->id;
				$payment->paid			= 0;
				$payment->status		= "active";
				
				$payment->save();
			}
			
			// Cancel refund if there is one
			if( $this->Related_Account->exists() && $this->Related_Account->join_type == "block" )
			{
				$this->LastPayment = new Payment();
				
				$this->LastPayment->where(array(
					"account_id"    => Auth::Get("id"),
					"game_block_id" => $this->Block->id,
					"amount <"      => 0
				))->order_by("id", "DESC")->limit(1)->get();
					
				if( $this->LastPayment->exists() )
				{
					$this->LastPayment->status = "canceled";
					$this->LastPayment->save();
				}
			}
			
			// Make all invites inactive for this fixture
			$Invite = new Invite;
			
			$Invite->where(array(
				"email"      => Auth::Get("email"),
				"fixture_id" => $this->Fixture->id,
				"valid"      => 1,
				"joined"     => 0
			))->limit(1)->get();
			
			// Check for Invitation
			if( $Invite->exists() )
			{
				$Invite->joined         = 1;
				$Invite->valid          = 0;
				$Invite->joined_on      = time();
				$Invite->joined_account = Auth::Get("id");
				
				// If invitation exists, mark it as accepted
				$Invite->save();
			}
			
			// Relate this player with a manager
			$this->PlayerManager = new Player_Manager();
			
			$this->PlayerManager->player_id  = Auth::Get("id");
			$this->PlayerManager->manager_id = $this->Fixture->owner;
			
			$this->PlayerManager->save();
			
			// Relate Account
			$this->Block->save( $this->Account );
			$this->Block->set_join_field( $this->Account, "active", 1 );
			
			if( ! $this->Related_Account->exists() )
			{
				$this->Block->set_join_field( $this->Account, "type", "game" );
			}
			
			// Send success response
			$this->json->success();
		}
		
		public function Leave($block = 0)
		{
			Auth::RequireLogin(true);
			
			// Make sure the user is logged in
			if( ! $this->auth->isLogged() )
			{
				$this->json->error("You need to be logged in.");
			}
			
			// Find the game
			$this->Block = new Game_Block( $block );
			
			// Make sure the game exists
			if( ! $this->Block->exists() )
			{
				$this->json->error("Block could not be found.");
			}
			
			// Make sure the game is not in the past
			if( $this->Block->time != 0 && $this->Block->time < time() )
			{
				$this->json->error("This block is already in the past.");
			}
			
			// Load your account
			$this->Account = new Account( Auth::Get("id") );
			
			// Make sure your account has been loaded
			if( ! $this->Account->exists() )
			{
				$this->json->error("Your account could not be loaded.");
			}
			
			// Make sure you are playing this game
			if( ! $this->Block->is_related_to( $this->Account) )
			{
				$this->json->error("You are not playing this block.");
			}
			
			// Try getting the fixture
			$this->Fixture = $this->Block->fixture->get();
			
			// Make sure fixture is loaded
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be loaded.");
			}
			
			$this->Related_Account = $this->Block->account->where( "id", Auth::Get("id") )->include_join_fields()->get();
			
			if( $this->Related_Account->exists() && ! $this->Related_Account->join_active )
			{
				$this->json->error("You already left this game.");
			}
			
			// If the fixture is not free
			if( $this->Fixture->payp_cost )
			{
				// Mark his last payment canceled
				$this->LastPayment = new Payment();
			
				$this->LastPayment = $this->LastPayment->where(array(
					"account_id"    => Auth::Get("id"),
					"game_block_id" => $this->Block->id
				))->order_by("id", "DESC")->limit(1)->get();
				
				if( $this->LastPayment->exists() )
				{
					$this->LastPayment->status = "canceled";
					$this->LastPayment->save();
				}
				
				// Return the money
				$Payment = new Payment();
				
				if( $this->Related_Account->join_type == "game" )
				{
					$Payment->amount = $this->Fixture->payp_cost - ( $this->Fixture->payp_cost * 2 );
				}
				else if( $this->Related_Account->join_type == "block" )
				{
					$this->BlocksCount = $this->Fixture->game_block->count();
					$Payment->amount = floor( $this->Fixture->player_cost / $this->BlocksCount ) - ( floor( ($this->Fixture->player_cost / $this->BlocksCount) * 2 ) );
				}
				
				$Payment->date          = time();
				$Payment->account_id    = Auth::Get("id");
				$Payment->game_block_id = $this->Block->id;
				$Payment->fixture_id	= $this->Fixture->id;
				$Payment->type			= "game";
				$Payment->status		= "active";
				
				$Payment->save();
				
				// Return credits to your account 
				$this->Account->credits += $Payment->amount;
				$this->Account->save();
			}
			
			// Set this account inactive
			$this->Block->set_join_field( $this->Account, "active", 0 );
			
			$this->json->success();
		}
		
		public function Payment($id = 0, $status = 0)
		{
			Auth::RequireManager(true);
			
			$this->Payment = new Payment();
		
			// Try finding the payment
			$this->Payment->include_related("fixture", array("owner"))->where("id", $id)->get();
		
			// Make sure the payment exists
			if( ! $this->Payment->exists() )
			{
				$this->json->error("Payment could not be found.");
			}
		
			// Make sure the payment has right status
			if( ! in_array( strtolower($this->Payment->status), array("active") ) )
			{
				$this->json->error("Payment has invalid status.");
			}
		
			// Make sure you are the owner of paid fixture
			if( $this->Payment->fixture_owner != Auth::Get("id") )
			{
				$this->json->error("Payment could not be found.");
			}
		
			// Make sure status is valid
			if( ! in_array($status, array( 0, 1 )) )
			{
				$this->json->error("Invalid payment status.");
			}
		
			// Set and save the status
			$this->Payment->paid = $status;
			$this->Payment->save();
		
			// Send success response
			$this->json->success();
		}
		
	}
?>