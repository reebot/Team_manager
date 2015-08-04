<?php
	class JSON
	{
		public function error($string = "")
		{
			exit(json_encode(array("error" => strip_tags($string))));
		}
		
		public function success($data = array())
		{
			exit(json_encode(array_merge(array("success" => "OK"), $data)));
		}
		
		public function send(array $data = array())
		{
			exit(json_encode($data));
		}
		
		public function unloggedError()
		{
			$this->send(array(
				"error"  => "You need to be logged in.",
				"logout" => 1
			));
		}
	}
?>
