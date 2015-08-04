<?php
	class Invite extends DataMapper
	{
		var $validation = array(
			
			"fixture_id" => array(
				"label" => "Fixture",
				"rules" => array("required", "integer")
			),
			
			"email" => array(
				"label" => "E-Mail",
				"rules" => array("required", "valid_email")
			),
			
			"tracking_code" => array(
				"label" => "Tracking Code",
				"rules" => array("required")
			)
			
		);
		
		var $has_one = array("fixture");
		
		public function __construct($id = null)
		{
			parent::DataMapper($id);
		}
		
		public function Invite()
		{
			$Check = new Invite;

			// If this invite already exists, skip it
			if( $Check->where(array("email" => $this->email, "fixture_id" => $this->fixture_id))->get()->result_count() > 0 )
			{
				return false;
			}
			
			// Try saving it
			if( $this->save() )
			{
				$CI =& get_instance();
				$CI->load->library("email");
				
				$CI->email->from('info@teamandme.com', 'Game Manager');
				$CI->email->to( $this->email );
			
				$CI->email->subject('Invitation to a Game!');
				$CI->email->message('Dear Player<br/><br/>'.ucwords(strtolower($this->Inviter->first_name.' '.$this->Inviter->last_name)).' has invited you to play at '.$this->Fixture->location.' on '.$this->Fixture->day.' at '.$this->Fixture->time.'. If you would

				like to join the team please click here and signup to the really easy '.$this->Fixture->type.' tool.
				<br/><br/>
				Signup now <a href="'.url("fixtures/join_game/".$this->tracking_code).'">Click Here</a>');

				$CI->email->send();
				
			}
		}
	}
?>