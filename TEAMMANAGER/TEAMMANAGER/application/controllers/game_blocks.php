<?php
	class Game_Blocks extends CI_Controller
	{
		public $errors;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function setTime($id = 0)
		{		
			Auth::RequireManager(true);
			
			$this->Block = new Game_Block( $id );
			
			if( ! $this->Block->exists() )
			{
				exit("Block could not be found.");
			}
			
			if( $this->Block->fixture->get()->owner != Auth::Get("id") )
			{
				exit("You cannot manage this Fixture.");
			}
			
			if( $this->input->post() )
			{
				$this->Block->time = strtotime( $this->input->post("date")." ".$this->input->post("hour").":".$this->input->post("minute"));
				
				if( $this->Block->setDate() )
				{
					$this->json->success();
				}
				else
				{
					$this->json->error( $this->Block->error->string );
				}
			}
			
			$this->load->view("game_blocks/setTime");
			
			$this->template->disable();
		}
		
		public function getData($block = 0)
		{
			$this->template->disable();
			
			$this->Block = new Game_Block( $block );
			
			if( ! $this->Block->exists() )
			{
				exit("Block could not be found.");
			}

			if( $this->auth->isLogged() )
			{
				$this->Account = new Account( Auth::Get("id") );
				
				if( ! $this->Account->exists() )
				{
					exit("Your account could not be loaded.");
				}
			}
			else
			{
				$this->JoinButtonVisible = false;
			}
			
			$this->Fixture = $this->Block->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				exit("Fixture could not be found.");
			}
			
			// Get Players
			$this->Block->account->include_join_fields()->get_iterated();
			
			$this->Players = array();
			
			foreach( $this->Block->account as $Account )
			{
				$this->Players[ $Account->id ] = array( "active" => $Account->join_active );
			}
			
			$this->load->view("game_blocks/getData");
		}
		
		public function join_block($fixture = 0)
		{
			$this->template->disable();
			
			if( ! $this->auth->isLogged() )
			{
				$this->json->error("You need to be logged in.");
			}
			
			// Get Fixture
			$this->Fixture = new Fixture($fixture);
			
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be found.");
			}
			
			// Get All Games in this Fixture
			$this->Fixture->game_block->get_iterated();
			
			// Make sure the fixture has games in
			if( ! $this->Fixture->game_block->count() )
			{
				$this->json->error("This Fixture does not have any games in.");
			}
			
			// Make sure there is no games in the past
			if( $this->Fixture->game_block->where(array( "time <" => time(), "time !=" => 0 ))->count() )
			{
				$this->json->error("This Fixture has already started.");
			}
			
			// Get My Account
			$this->Account = new Account( Auth::Get("id") );
			
			if( ! $this->Account->exists() )
			{
				$this->json->error("Your account could not be loaded.");
			}
			
			$MaxPlayers = ( (int) $this->Fixture->type ) * 2;
			
			// Make sure this player is not in any of the games in this fixture
			foreach( $this->Fixture->game_block as $GameBlock )
			{
				if( $GameBlock->is_related_to($this->Account) )
				{
					$this->json->error("You already joined at least one game in this fixture.");
				}
				
				if( $GameBlock->account->where("active", 1)->count() >= $MaxPlayers )
				{
					$this->json->error("At least one of the games is already full. You cannot join the whole block.");
				}
			}
			
			// Add Payment Record if this fixture is not free
			if( $this->Fixture->player_cost > 0 )
			{
				$this->Account->credits -= ( $this->Fixture->player_cost * $this->Fixture->blocks );
				$this->Account->save();
				
				// Get the first game
				$this->FirstGame = $this->Fixture->game_block->order_by("id", "ASC")->limit(1)->get();
				
				// Add Payment Record
				$payment = new Payment();
				
				$payment->account_id    = Auth::Get("id");
				$payment->game_block_id = ( $this->FirstGame->exists() ) ? $this->FirstGame->id : 0;
				$payment->amount        = ( $this->Fixture->player_cost * $this->Fixture->blocks );
				$payment->date          = time();
				$payment->type			= "block";
				$payment->fixture_id	= $this->Fixture->id;
				$payment->paid			= 0;
				$payment->status		= "active";
				
				$payment->save();
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
			
			// Join All Games
			foreach( $this->Fixture->game_block->get_iterated() as $GameBlock )
			{
				$GameBlock->save( $this->Account );
				$GameBlock->set_join_field( $this->Account, "active", 1 );
				$GameBlock->set_join_field( $this->Account, "type", "block" );
			}
			
			// Return Success
			$this->json->success();
		}
		
		public function join($block = 0)
		{
			$this->template->disable();
			
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
			if( $this->Related_Account->exists() )
			{
				$this->LastPayment = new Payment();
				
				$this->LastPayment->where(array(
					"account_id"    => Auth::Get("id"),
					"game_block_id" => $this->Block->id,
					"amount <"		=> 0
				))->order_by("id", "DESC")->limit(1)->get();
					
				if( $this->LastPayment->exists() )
				{
					$this->LastPayment->status = "cancelled";
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
		
		public function leave($block = 0)
		{
			$this->template->disable();
			
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
			if( $this->Fixture->payp_cost || $this->Fixture->player_cost )
			{
				// Mark his last payment cancelled
				$this->LastPayment = new Payment();
			
				$this->LastPayment = $this->LastPayment->where(array(
					"account_id"    => Auth::Get("id"),
					"game_block_id" => $this->Block->id,
					"type"			=> "game",
					"amount >"		=> 0
				))->order_by("id", "DESC")->limit(1)->get();
				
				if( $this->LastPayment->exists() )
				{
					$this->LastPayment->status = "cancelled";
					$this->LastPayment->save();
				}
				
				// Return the money
				$Payment = new Payment();
				
				if( $this->Related_Account->join_type == "game" )
				{
					$Payment->amount = 0 - $this->Fixture->payp_cost;
				}
				else if( $this->Related_Account->join_type == "block" )
				{
					$Payment->amount   = 0 - $this->Fixture->player_cost;
				}
				
				$Payment->date          = time();
				$Payment->account_id    = Auth::Get("id");
				$Payment->game_block_id = $this->Block->id;
				$Payment->fixture_id	= $this->Fixture->id;
				$Payment->type			= "game";
				$Payment->status		= "active";
				
				if( $this->LastPayment->exists() )
				{
					if( $this->LastPayment->paid )
					{
						$Payment->save();
					}
				}
				else
				{
					$Payment->save();
				}
				
				// Return credits to your account 
				$this->Account->credits += $Payment->amount;
				$this->Account->save();
				
				$CreditedAccount = abs($Payment->amount);
			}
			
			// Set this account inactive
			$this->Block->set_join_field( $this->Account, "active", 0 );
			
			$data = array();
			
			if( isset($CreditedAccount) )
			{
				$data['credited'] = $CreditedAccount;
			}
			
			$this->json->success($data);
		}
	}
?>