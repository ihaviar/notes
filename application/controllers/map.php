<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Map extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->load->library('googlemaps');
		
	}

	function map()
	{
		$this->googlemaps->initialize();
		$data['map'] = $this->googlemaps->create_map();
		return $data['map'];
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */