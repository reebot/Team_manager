<?php
	class Fixtures extends CI_Controller
	{
		public $errors;
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function index() 
		{
			$this->template->setData("SelectedTab", "All games");

			// Get Player
			$this->player = new Account( Auth::Get("id") );

			// Get players fixtures
			$this->PlayerFixtures = $this->player->game_block->include_related("fixture", array("location", "id", "postcode"))->where("time > ", time())->or_where("time", 0)->get_iterated();

			// Get all fixtures
			$this->fixtures = new Fixture;
			$this->fixtures->get_iterated();

			$this->load->view("fixtures/index");
		}
		
		public function join_fixture($id = 0)
		{
			// We don't use this function anymore
			exit;
			
			Auth::RequireLogin();
			
			$this->player = new Account( Auth::Get("id") );

			$this->fixture = new Fixture($id);

			if( ! $this->fixture->exists() )
			{
				Session::GoBack("Fixture could not be found.");
			}

			if( $this->player->is_related_to($this->fixture) )
			{
				Session::GoBack("You are already playing this match.");
			}

			$this->player->save( $this->fixture );

			Session::GoBack("You are now in!");
		}
		
		public function my_fixtures()
		{
			Auth::RequireManager();
			
			$this->template->setData("SelectedTab", "Games I manage");

			$this->Account = new Account( Auth::Get("id") );
			
			if( ! $this->Account->exists() )
			{
				Session::GoBack("Your account could not be loaded.");
			}
			
			$this->Account->Fixtures = new Fixture;
			
			$this->Account->Fixtures->where("owner", $this->Account->id)->get_iterated();

			// Get all fixtures
			$this->Fixtures = new Fixture;
			$this->Fixtures->get_iterated();

			$this->load->view("fixtures/my_fixtures");
		}
		
		public function add()
		{
			Auth::RequireManager();
			
			$this->template->setData("SelectedTab", "Games I manage");

			$this->Fixture = new Fixture();

			if( $this->input->post() )
			{
				$this->Fixture->type        		= $this->input->post("type");
				$this->Fixture->location    		= $this->input->post("location");
				$this->Fixture->address     		= $this->input->post("address");
				$this->Fixture->postcode    		= $this->input->post("postcode");
				$this->Fixture->directions  		= $this->input->post("directions");
				$this->Fixture->pitch_cost  		= $this->input->post("pitch_cost");
				$this->Fixture->owner				= Auth::Get("id");
				$this->Fixture->blocks				= $this->input->post("blocks");
				$this->Fixture->player_cost 		= $this->input->post("player_cost");
				$this->Fixture->payp_cost			= $this->input->post("payp_cost");
				
				if( $this->Fixture->save() )
				{	
					$this->Account = new Account( Auth::Get("id") );
					
					if( ! $this->Account->exists() )
					{
						Session::GoBack("Your account could not be loaded.");
					}
					
					// Relate this fixture to this account
					$this->Account->save( $this->Fixture );
					
					// Add Games
					for( $i = 1; $i <= $this->Fixture->blocks; $i++ )
					{
						$GameBlock = new Game_Block;
						
						$GameBlock->fixture_id     = $this->Fixture->id;
						$GameBlock->time           = 0;
						$GameBlock->position_names = 0;
						$GameBlock->positions      = $this->input->post("type");
						$GameBlock->team_1		   = "Red Team";
						$GameBlock->team_2		   = "Orange Team";
						
						$GameBlock->save();
					}
					
					if( $_FILES['userfile']['name'] )
					{
						$config['upload_path']   = './public/uploads/';
						$config['allowed_types'] = 'gif|jpg|png';
						$config['max_size']      = '100';
						$config['max_width']     = '1024';
						$config['max_height']    = '768';
						$config['overwrite']	 = TRUE;
						$config['encrypt_name']	 = TRUE;

						$this->load->library('upload', $config);

						if ( ! $this->upload->do_upload())
						{
							$this->errors = $this->upload->display_errors();
						}
						else
						{
							$config = array();
						
							$data = $this->upload->data();
						
							$config['image_library']  = 'gd2';
							$config['source_image']   = './public/uploads/'.$data['file_name'];
							$config['create_thumb']   = FALSE;
							$config['maintain_ratio'] = FALSE;
							$config['width']          = 194;
							$config['height']         = 113;

							$this->load->library('image_lib', $config);

							$this->image_lib->resize();
						
							$this->Fixture->image = $data['file_name'];
							$this->Fixture->save();
						
							redirect('fixtures/manage_fixture/'.$this->Fixture->id);
						}
					}
					else
					{
						redirect('fixtures/manage_fixture/'.$this->Fixture->id);
					}
	
				}
				else
				{
					$this->errors = $this->Fixture->error->string;
				}

			}

			$this->load->view("fixtures/add");
		}
		
		public function view($id = 0, $code = "", $game = 0)
		{
			$this->Fixture = new Fixture( $id );
			
			if( ! $this->Fixture->exists() )
			{
				Session::GoBack("Fixture could not be found.");
			}
			
			if( $this->Fixture->owner == Auth::Get("id") )
			{
				redirect("fixtures/manage_fixture/".$this->Fixture->id."/".$code."/".$game);
			}
			
			if( $code )
			{
				if( ! $this->auth->isLogged() )
				{
					redirect("accounts/join/".$code);
				}

				$this->Invite = new Invite;

				$this->Invite->where(array("tracking_code" => $code, "valid" => 1))->get();

				if( ! $this->Invite->exists() )
				{
					Session::GoBack("Invite could not be found.");
				}

				if( $this->Invite->fixture_id != $this->Fixture->id )
				{
					Session::GoBack("Invite does not match Fixture.");
				}
			}
			
			// Get Owner's Account
			$this->Owner = new Account( $this->Fixture->owner );
			
			if( ! $this->Owner->exists() )
			{
				Session::GoBack("Owner's account for this fixture could not be loaded.");
			}
			
			$this->Fixture->game_block->order_by("time", "ASC")->get();
			
			foreach( $this->Fixture->game_block as $GameBlock )
			{
				if( $GameBlock->time >= time() )
				{
					$this->NextGame = $GameBlock->time;
					break;
				}
			}
			
			if( $this->Fixture->owner == Auth::Get("id") )
			{
				// Calculate total payments
				$this->TotalPayments = $this->db->query("
					SELECT sum(payments.amount) as total from fixtures
					LEFT JOIN game_blocks ON game_blocks.fixture_id = fixtures.id
					LEFT JOIN payments ON payments.game_block_id = game_blocks.id
					WHERE fixtures.id = ".$this->Fixture->id."
				")->result();
				
				$this->TotalPayments = $this->TotalPayments[0]->total;
			}
			
			// Is the Join Block button visible
			$this->BlockButtonVisible = true;
			
			// Determine if it's really visible
			if( $this->auth->isLogged() )
			{
				$this->Account = new Account( Auth::Get("id") );
				
				foreach( $this->Fixture->game_blocks as $GameBlock )
				{
					// Make sure the game is not in the past
					if( $GameBlock->time != 0 && $GameBlock->time <= time() )
					{
						$this->BlockButtonVisible = false;
					}
					else
					{
						// Make sure player is not playing this game
						if( $GameBlock->is_related_to($this->Account) )
						{
							$this->BlockButtonVisible = false;
						}
					}
				}
			}
			else
			{
				$this->BlockButtonVisible = false;
			}
			
			$this->Game = $game;
			
			$this->load->view("fixtures/view");
		}
		
		public function invite_players($id = 0)
		{
			Auth::RequireManager();
			
			$this->Fixture = new Fixture( $id );
			
			// Get all invites
			$this->Fixture->invite->get_iterated();
			
			if( ! $this->Fixture->exists() )
			{
				Session::GoBack("Fixture could not be found.");
			}
			
			$this->Account = new Account( Auth::Get("id") );
			
			if( $this->Fixture->owner != $this->Account->id )
			{
				Session::GoBack("You don't have permissions to invite anyone.");
			}
			
			if( $this->input->post() )
			{
				$invites = ( is_array($this->input->post("invite")) ) ? $this->input->post("invite") : array();
				
				if( count($invites) > 100 )
				{
					Session::GoBack("You cannot invite more than 100 players.");
				}

				foreach( $invites as $invite )
				{
					$this->Invite = new Invite;
					
					$this->Invite->email         = $invite;
					$this->Invite->tracking_code = sha1( uniqid().time() );
					$this->Invite->valid		 = 1;
					$this->Invite->fixture_id	 = $this->Fixture->id;
					
					$this->Invite->Inviter = $this->Account;
					$this->Invite->Fixture = $this->Fixture;
					
					$this->Invite->invite();
				}
				
				$this->Invites = $this->Fixture->invite->get_iterated();
				
				$this->template->disable();
				return $this->load->view("fixtures/InvitedPlayers");
			}
			
			$this->load->view("fixtures/invite_players");
		}
		
		public function join_game($code = "")
		{
			if( ! $this->auth->isLogged() )
			{
				redirect("accounts/join/".$code);
			}
			
			$this->Invite = new Invite;
			
			$this->Invite->where(array("tracking_code" => $code, "valid" => 1))->get();
			
			if( ! $this->Invite->exists() )
			{
				Session::GoBack("Invite could not be found.");
			}
			
			$this->Fixture = $this->Invite->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				Session::GoBack("Fixture could not be found.");
			}

			redirect("fixtures/view/".$this->Fixture->id."/".$code);
		}
		
		public function my_games()
		{
			Auth::RequireLogin();
			
			$this->template->setData("SelectedTab", "Games I play");
			
			$this->Account = new Account( Auth::Get("id") );
			
			if( ! $this->Account->exists() )
			{
				Session::GoBack("Your account could not be loaded.");
			}
			
			$this->Fixtures = $this->Account->game_block->include_related("fixture", array("location", "id", "postcode", "type"))->where(array("time > " => time(), "accounts_game_blocks.active" => 1))->or_where("time", 0)->include_related_count("account", "account_count", array("active" => 1))->order_by("game_blocks.time", "ASC")->get_iterated();
			
			$this->load->view("fixtures/my_games");
		}
		
		public function manage_fixture($id = 0, $code = "", $game = 0)
		{
			Auth::RequireManager();
			
			$this->template->setData("SelectedTab", "Games I manage");
			
			$this->Fixture = new Fixture($id);
			
			if( ! $this->Fixture->exists() )
			{
				Session::GoBack("Fixture could not be found.");
			}
			
			if( $this->Fixture->owner != Auth::Get("id") )
			{
				Session::GoBack("You are not owner of this fixture.");
			}
			
			// Get Players
			$this->Fixture->game_block->order_by("time", "ASC")->get();
			
			// Get Invites
			$this->Fixture->invite->get_iterated();
			
			// Get Owner
			$this->Owner = new Account( Auth::Get("id") );
			
			if( ! $this->Owner->exists() )
			{
				Session::GoBack("Your account could not be loaded.");
			}
			
			// Get Payments
			$this->Payments = new Payment();
			$this->Payments->include_related("game_block", array("id", "time"))->include_related("account", array("first_name", "last_name"))->where("fixture_id", $this->Fixture->id)->order_by("id", "DESC")->get();
			
			// Calculate total payments
			$this->TotalPayments = $this->db->query("
				SELECT sum(payments.amount) as total from fixtures
				LEFT JOIN game_blocks ON game_blocks.fixture_id = fixtures.id
				LEFT JOIN payments ON payments.game_block_id = game_blocks.id
				WHERE fixtures.id = ".$this->Fixture->id."
				AND payments.status = 'active'
			")->result();
			
			$this->TotalPayments = $this->TotalPayments[0]->total;
			
			// Is the Join Block button visible
			$this->BlockButtonVisible = true;
			
			// Determine if it's really visible
			if( $this->auth->isLogged() )
			{
				$this->Account = new Account( Auth::Get("id") );
				
				foreach( $this->Fixture->game_blocks as $GameBlock )
				{
					// Make sure the game is not in the past
					if( $GameBlock->time != 0 && $GameBlock->time <= time() )
					{
						$this->BlockButtonVisible = false;
					}
					else
					{
						// Make sure player is not playing this game
						if( $GameBlock->is_related_to($this->Account) )
						{
							$this->BlockButtonVisible = false;
						}
					}
				}
			}
			else
			{
				$this->BlockButtonVisible = false;
			}
			
			$this->Game = $game;
			
			$this->load->view("fixtures/manage_fixture");
		}
		
		public function kick_out($player = 0, $block = 0)
		{
			Auth::RequireManager();
			
			$this->Block = new Game_Block($block);
			
			if( ! $this->Block->exists() )
			{
				Session::GoBack("Game could not be found.");
			}
			
			if( ! $this->Block->fixture->get()->exists() )
			{
				Session::GoBack("Fixture could not be found.");
			}
			
			if( $this->Block->fixture->owner != Auth::Get("id") )
			{
				Session::GoBack("You cannot manage this block.");
			}
			
			$this->Account = new Account( $player );
			
			if( ! $this->Account->exists() )
			{
				Session::GoBack("Player could not be found.");
			}
			
			if( ! $this->Block->is_related_to($this->Account) )
			{
				Session::GoBack("This player is not playing in this game.");
			}
			
			$this->Block->set_join_field( $this->Account, "active", 0 );
			
			Session::GoBack("Player has been kicked out.");
		}
		
		public function enter($player = 0, $block = 0)
		{
			Auth::RequireManager();
			
			$this->Block = new Game_Block($block);
			
			if( ! $this->Block->exists() )
			{
				Session::GoBack("Game could not be found.");
			}
			
			// Try Getting Fixture
			if( ! $this->Block->fixture->get()->exists() )
			{
				Session::GoBack("Fixture could not be found.");
			}
			
			if( $this->Block->fixture->owner != Auth::Get("id") )
			{
				Session::GoBack("You cannot manage this block.");
			}
			
			$this->Account = new Account( $player );
			
			if( ! $this->Account->exists() )
			{
				Session::GoBack("Player could not be found.");
			}
			
			if( ! $this->Block->is_related_to($this->Account) )
			{
				Session::GoBack("This player is not playing in this game.");
			}
			
			// Check the limit of this block
			$this->PlayersCount = $this->Block->account->include_join_fields()->where(array("active" => 1))->count();
			
			if( $this->PlayersCount >= ( ((int) $this->Block->fixture->type) * 2 ) )
			{
				Session::GoBack("This game is already full!");
			}
			
			$this->Block->set_join_field( $this->Account, "active", 1 );
			
			Session::GoBack("Player has joined.");
		}
	}
?>