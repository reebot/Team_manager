<?php
	class Fixtures extends CI_Controller
	{
		public function Get($id = 0)
		{
			// Load Fixture
			$this->Fixture = new Fixture($id);
			
			// Make sure it exists
			if( ! $this->Fixture->exists() )
			{
				$this->json->error("Fixture could not be found.");
			}
			
			// Get Game Blocks
			$this->GameBlocks = array();
			
			foreach( $this->Fixture->game_block->order_by("time", "ASC")->get_iterated() as $GameBlock )
			{
				$this->GameBlocks[] = array(
					"id"   => $GameBlock->id,
					"time" => ( $GameBlock->time == 0 ) ? "Unknown" : date(TIME_FORMAT, $GameBlock->time),
					"day"  => date("l", $GameBlock->time),
					"future" => ( $GameBlock->time > time() ) ? true : false
				);
			}
			
			// Send Back Response
			$this->json->send(array(
				
				// We need to send 'success' in order to trigger right event
				"success" => "OK",
				
				// Fixture Basic Details
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
				
				// Game Blocks
				"GameBlocks" => $this->GameBlocks
			));
		}
	}
?>