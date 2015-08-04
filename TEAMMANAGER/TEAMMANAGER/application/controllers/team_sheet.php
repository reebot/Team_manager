<?php
	class Team_Sheet extends CI_Controller
	{
		public $errors;
		
		public $Positions = array(
			"Goal Keeper",
			"Left Defender",
			"Left Middle Defender",
			"Right Middle Defender",
			"Right Defender",
			"Left Midfielder",
			"Midfielder",
			"Right Midfielder",
			"Left Attacker",
			"Attacker",
			"Right Attacker"
		);
		
		public function __construct()
		{
			parent::__construct();
		}
		
		public function GetBlock($id = 0)
		{
			$this->Block = new Game_Block( $id );
			
			if( ! $this->Block->exists() )
			{
				$this->json->error("Block could not be found.");
			}
			
			$this->Fixture = $this->Block->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be loaded.");
			}
			
			// Get Players
			$this->Block->account->include_join_fields()->get_iterated();
			
			$this->Players = array();
			
			foreach( $this->Block->account as $Player )
			{
				$this->Players[] = array(
					"player_id"	 => $Player->id,
					"name"		 => (strlen($Player->first_name." ".$Player->last_name) <= 8 ) ? ucwords(strtolower($Player->first_name." ".$Player->last_name)) : substr(ucwords(strtolower($Player->first_name." ".$Player->last_name)), 0, 8)."...",
					"active"     => $Player->join_active,
					"position"   => $Player->join_position,
					"team"       => $Player->join_team,
					"avatar"	 => url( ( ! $Player->avatar) ? "public/img/player.png" : "public/uploads/".$Player->avatar, true)
				);
			}
			
			$this->json->send(array(
			
				// Success
				"success"       => "OK",
				
				"Players"       => $this->Players,
				
				"Positions"     => ( $this->Block->positions ) ? $this->Block->positions : "Full",
				"PositionNames" => $this->Block->position_names,
				
				"LeftTeamName"  => $this->Block->team_1,
				"RightTeamName" => $this->Block->team_2
				
			));
		}
		
		public function SavePosition()
		{
			// Require Manager Login
			Auth::RequireManager(true);
			
			if( ! $this->input->post() )
			{
				$this->json->error("POST Data needs to be sent.");
			}
			
			// Get Block
			$this->Block = new Game_Block( $this->input->post("block_id") );
			
			if( ! $this->Block->exists() )
			{
				$this->json->error("Block could not be found.");
			}
			
			// Get Fixture
			$this->Fixture = $this->Block->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be found.");
			}
			
			// Check ownership
			if( $this->Fixture->owner != Auth::Get("id") )
			{
				$this->json->error("You are not owner of this fixture.");
			}
			
			// Get Account
			$this->Account = new Account( $this->input->post("player_id") );
			
			if( ! $this->Account->exists() )
			{
				$this->json->error("Player could not be found.");
			}
			
			// Make sure it's related
			if( ! $this->Block->is_related_to( $this->Account ) )
			{
				$this->json->error("This player is not playing this game.");
			}
			
			// Make sure the team is correct
			$this->Team = $this->input->post("team");
			
			if( ! in_array($this->Team, array( 1, 2 )) )
			{
				$this->json->error("Incorrect Team.");
			}
			
			// Make sure the position is correct
			$this->Position = $this->input->post("position");
			
			if( ! in_array($this->Position, $this->Positions) )
			{
				$this->json->error("Incorrect Position.");
			}

			// Make sure there is no one on this position already
			if( $this->Block->account->where( array( "accounts_game_blocks.team" => $this->Team, "accounts_game_blocks.position" => $this->Position ) )->count() )
			{
				$this->json->error("There is already a player on this position.");
			}
			
			// Set Data
			$this->Block->set_join_field( $this->Account, "team", $this->Team );
			$this->Block->set_join_field( $this->Account, "position", $this->Position );
			
			// Send Success
			$this->json->success();
		}
		
		public function ResetPosition()
		{
			// Require Manager Login
			Auth::RequireManager(true);
			
			if( ! $this->input->post() )
			{
				$this->json->error("POST Data needs to be sent.");
			}
			
			// Get Block
			$this->Block = new Game_Block( $this->input->post("block_id") );
			
			if( ! $this->Block->exists() )
			{
				$this->json->error("Block could not be found.");
			}
			
			// Get Fixture
			$this->Fixture = $this->Block->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be found.");
			}
			
			// Check ownership
			if( $this->Fixture->owner != Auth::Get("id") )
			{
				$this->json->error("You are not owner of this fixture.");
			}
			
			// Get Account
			$this->Account = new Account( $this->input->post("player_id") );
			
			if( ! $this->Account->exists() )
			{
				$this->json->error("Player could not be found.");
			}
			
			// Make sure it's related
			if( ! $this->Block->is_related_to( $this->Account ) )
			{
				$this->json->error("This player is not playing this game.");
			}

			// Set Data
			$this->Block->set_join_field( $this->Account, "team", 0 );
			$this->Block->set_join_field( $this->Account, "position", "" );
			
			// Send Success
			$this->json->success();
		}
		
		public function ResetPositions($block = 0)
		{
			// Require Manager Login
			Auth::RequireManager(true);

			// Get Block
			$this->Block = new Game_Block( $block );
			
			if( ! $this->Block->exists() )
			{
				$this->json->error("Block could not be found.");
			}
			
			// Get Fixture
			$this->Fixture = $this->Block->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be found.");
			}
			
			// Check ownership
			if( $this->Fixture->owner != Auth::Get("id") )
			{
				$this->json->error("You are not owner of this fixture.");
			}
			
			foreach( $this->Block->account->include_join_fields()->get_iterated() as $Player )
			{
				if( $Player->join_team && $Player->join_position )
				{
					// Reset Data
					$this->Block->set_join_field( $Player, "team", 0 );
					$this->Block->set_join_field( $Player, "position", "" );
				}
			}
			
			$this->json->success();
		}
		
		public function TogglePositionNames($block = 0)
		{
			// Require Manager Login
			Auth::RequireManager(true);

			// Get Block
			$this->Block = new Game_Block( $block );
			
			if( ! $this->Block->exists() )
			{
				$this->json->error("Block could not be found.");
			}
			
			// Get Fixture
			$this->Fixture = $this->Block->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be found.");
			}
			
			// Check ownership
			if( $this->Fixture->owner != Auth::Get("id") )
			{
				$this->json->error("You are not owner of this fixture.");
			}
			
			$this->Block->position_names = ( $this->Block->position_names ) ? 0 : 1;
			$this->Block->save();
			
			$this->json->success();
		}
		
		public function Positions($team = 0, $type = "")
		{
			// Disable template
			$this->template->disable();
			
			// Make sure sent team is ok
			if( ! in_array($team, array( 1, 2 )) )
			{
				exit("Team could not be found.");
			}
			
			$type = strtolower($type);
			
			// Make sure sent position type is ok
			if( ! in_array($type, array("5-a-side", "6-a-side", "7-a-side", "full")) )
			{
				exit("Positions type could not be found.");
			}
			
			// Load the positions
			$this->load->view("team_sheet/Positions_".$team."/".strtolower($type).".php");
		}
		
		public function SetPositions($block = 0)
		{
			// Require Manager Login
			Auth::RequireManager(true);

			// Get Block
			$this->Block = new Game_Block( $block );
			
			if( ! $this->Block->exists() )
			{
				$this->json->error("Block could not be found.");
			}
			
			// Get Fixture
			$this->Fixture = $this->Block->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be found.");
			}
			
			// Check ownership
			if( $this->Fixture->owner != Auth::Get("id") )
			{
				$this->json->error("You are not owner of this fixture.");
			}
			
			$Positions = $this->input->post("positions");
			$Positions = strtolower($Positions);
			
			// Make sure sent position type is ok
			if( ! in_array($Positions, array("5-a-side", "6-a-side", "7-a-side", "full")) )
			{
				$this->json->error("Positions type could not be found.");
			}
			
			$this->Block->positions = $Positions;
			$this->Block->save();
			
			$this->json->success();
		}
		
		public function SaveTeamName($block = 0, $team = 0)
		{
			// Require Manager Login
			Auth::RequireManager(true);

			// Get Block
			$this->Block = new Game_Block( $block );
			
			if( ! $this->Block->exists() )
			{
				$this->json->error("Block could not be found.");
			}
			
			// Get Fixture
			$this->Fixture = $this->Block->fixture->get();
			
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be found.");
			}
			
			// Check ownership
			if( $this->Fixture->owner != Auth::Get("id") )
			{
				$this->json->error("You are not owner of this fixture.");
			}
			
			// Make sure the team is ok
			if( ! in_array($team, array( 1, 2 )))
			{
				$this->json->error("Team could not be found.");
			}

			$this->Block->{"team_".$team} = $this->input->post("name");
			
			// Try saving
			if( $this->Block->save() )
			{
				$this->json->success();
			}
			else
			{
				$this->json->error( strip_tags($this->Block->error->string) );
			}
		}
	}
?>