<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notes extends CI_Controller {
	
function __construct()
{
		parent::__construct();			//nacitam veci pre kazdu metodu tejto class
		
		
		$this->load->model('notes_model');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('typography');
		$this->load->library('googlemaps');
		$this->load->library('javascript');
		$this->load->library('table');
		$this->load->helper('array');
		
			// if (!$this->tank_auth->is_logged_in()) 
			// {									// logged in
			// redirect('login');
			// }
			// else
			// {
			// $data = $this->tank_auth->get_user_id();	
			// $data = $this->tank_auth->get_username();
			// }
			
			if(!logged_in()){
				redirect('login');
				
			}
			else{
				//echo uid();
				//echo get_user_name();
				//$data['logged'] = get_user_name();
				
			}
			
		
}


	public function index()
	{
		/*Mapa*/
		$data['map'] = $this->map();
		$data['logged'] = get_user_name();
		// $this->googlemaps->initialize();
		// $data['map'] = $this->googlemaps->create_map();	
				
		$data['notes'] = $this->notes_model->getNotes(); //nacitanie dat a ich zobrazenie
		
		$data['vodici'] = $this->notes_model->getVodic();
		
		$vodici = $this->notes_model->getVodic();
		$cestujuci = $this->notes_model->getCestujuci();

		
		/* mselect list vodicov na zobrazenie*/
		$mselectV = array();
		$mselectC = array();
		
		foreach ($vodici as $v)
		{
			$mselectV[$v->id] = $v->priezvisko . ' ' . $v->meno . ' | ' . $v->pozicia . ' | ' . $v->pocmiest . ' | ' . $v->spz;
			// $this->table->add_row($v->meno, $v->priezvisko, $v->pozicia);
		}

		foreach ($cestujuci as $c)
		{
			$mselectC[$c->id] = $c->priezvisko . ' ' . $c->meno . ' | ' . $c->pozicia . ' | ' . $c->tcislo . ' | ' . $c->poplatok;
		}
		
		$blank = array(
		'priezvisko' =>' ',
		'meno' =>' ',
		'pozicia' =>' ',
		'tcislo' =>' ',
		'poplatok' =>' ',
		
		);
	
		
		// print_r($mselectC);
		// exit;
		

		$data['mselectC'] = $mselectC;
		$data['mselectV'] = $mselectV;
		$this->load->view('notes_view', $data); //poslanie dat do view
	
	}
	
	
	
	function add()
	{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('text','Novy text', 'trim|required|htmlspecialchars'); //nacitanie form validation validacia a trim
			
			if ($this->form_validation->run())
			{
			// validacia prebehla, ulozime	
				$data = array(
					'text' => $_POST['text'],
				);
				$this->notes_model->addNote($data);
				redirect('notes'); //presmerovanie na notes teda na horny index()
				
			}
			else
			{
			//neprebehla, vypiseme chybu
			
			$data['notes'] = $this->notes_model->getNotes(); //nacitanie dat a ich zobrazenie
			$this->load->view('notes_view', $data);
			redirect('notes');
			}
	}
	
		function addVodic()  //pridanie vodica do db
	{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('input_pozicia','', 'trim|required|htmlspecialchars'); //nacitanie form validation validacia a trim
			
			if ($this->form_validation->run())
			{
				/*odstranenie diakritiky kvoli geocodingu*/
				$diakritika = array('Á','á','Č','č','Ď','ď','É','é','Í','í','Ľ','ľ','Ň','ň','Ó','ó','Š','š','Ť','ť','Ú','ú','Ž','ž','Ý','ý');
				$bez = array('A','a','C','c','D','d','E','e','I','i','L','l','N','n','O','o','S','s','T','t','U','u','Z','z','Y','y');
				$_POST['input_pozicia'] = str_ireplace($diakritika,$bez,$_POST['input_pozicia']);
				/*ziskanie zemepisnej sirky a vysky, pre vykreslenie pouzijeme tuto hodnotu, keby pouzivame adresu vzniklo by prilis vela requstov na goecoding API*/
				$ilatlong = $this->googlemaps->get_lat_long_from_address($_POST['input_pozicia']);
				
				// print_r ($_POST['color1']);
				// exit;
			// validacia prebehla, ulozime	
				$data = array(
					'pozicia' => $_POST['input_pozicia'],
					'meno' => $_POST['input_meno'],
					'priezvisko' => $_POST['input_priezvisko'],
					'tcislo' => $_POST['input_telcislo'],
					'pocmiest' => $_POST['input_pmiest'],
					'spz' => $_POST['input_spz'],
					'latlong' => $ilatlong[0] . ',' . $ilatlong[1],
					'radius' => $_POST['input_radius'],
					'rcolor' => $_POST['input_color'],
				);
				$this->notes_model->addVodic($data);
				redirect('notes'); //presmerovanie na notes teda na horny index()
				
			}
			else
			{
			//neprebehla, vypiseme chybu
			$data['notes'] = $this->notes_model->getNotes(); //nacitanie dat a ich zobrazenie
			$this->load->view('notes_view', $data);
			redirect('notes');
			}
	}
	

	
		function delVodic()  //vymazanie vodica z db
	{
			$this->load->library('form_validation');
			//$this->form_validation->set_rules('input_pozicia','', 'trim|required|htmlspecialchars'); //nacitanie form validation validacia a trim
			
			$post = $_POST;
			
			foreach ($post['input_mselect'] as $vid) // prechadza id vodicov ktore ideme mazat
			{
				$this->notes_model->delVodicById($vid);
			}
			
			redirect('notes');
			
	}
	
		function naviguj()  //naviguj
	{
		
			$this->load->library('form_validation');
			//$this->form_validation->set_rules('input_pozicia','', 'trim|required|htmlspecialchars'); //nacitanie form validation validacia a trim
			
			$post = $_POST;
			// print_r($_POST['waypoint'][0]);
			// exit;
			
			$s = $post['input_start'];
			$w1 = $post['waypoint'][0];
			$w2 = $post['waypoint'][1];
			$w3 = $post['waypoint'][2];
			$w4 = $post['waypoint'][3];
			$w5 = $post['waypoint'][4];
			
			$vodic = $this->notes_model->getVodicById($s);
			
			$waypoints = $this->notes_model->getCestujuciById($w1);
	
			$data = array(
			'start' => $vodic[0]->pozicia,
			'waypoint1' => $w1,
			'waypoint2' => $w2,
			'waypoint3' => $w3,
			'waypoint4' => $w4,
			'waypoint5' => $w5,
			
			'end' => 'bratislava',
			);
			
			$dataS = array('navON' => $post['navON']);
			$this->notes_model->addMapSettings($dataS);
			$this->notes_model->addNavigacia($data);
			
			redirect('notes');
			
	}
	
	
			function delCestujuci()  //pridanie vodica do db
	{
			$this->load->library('form_validation');
			//$this->form_validation->set_rules('input_pozicia','', 'trim|required|htmlspecialchars'); //nacitanie form validation validacia a trim
			
			$post = $_POST;
			
			foreach ($post['input_mselect'] as $cid) // prechadza id vodicov ktore ideme mazat
			{
				$this->notes_model->delCestujuciById($cid);
			}
			
			redirect('notes');
			
	}
	
		function updateVodic()  //pridanie vodica do db
	{
			$this->load->library('form_validation');
			//$this->form_validation->set_rules('input_pozicia','', 'trim|required|htmlspecialchars'); //nacitanie form validation validacia a trim
			
			
			
			$data = array(
			'pozicia' => $_POST['input_pozicia'],
			'meno' => $_POST['input_meno'],
			'priezvisko' => $_POST['input_priezvisko'],
			'tcislo' => $_POST['input_telcislo'],
			'pocmiest' => $_POST['input_pmiest'],
			'spz' => $_POST['input_spz'],
			'latlong' => $ilatlong[0] . ',' . $ilatlong[1],
			'radius' => $_POST['input_radius'],
			'rcolor' => $_POST['input_color'],
			);
			
			// print_r($data);
			// exit;
			
			$this->notes_model->updateVodicById($_POST['input_mselect'],$data);
			
			$post = $_POST;
			
			// foreach ($post['input_mselect'] as $vid) // prechadza id vodicov ktore ideme mazat
			// {
				// $this->notes_model->updateVodicById($vid,$data);
			// }
			
			redirect('notes');
			
	}
	
	
		function addCestujuci()  //pridanie cestujuceho do db
	{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('input_pozicia','', 'trim|required|htmlspecialchars'); //nacitanie form validation validacia a trim
			
			if ($this->form_validation->run())
			{
				/*odstranenie diakritiky kvoli geocodingu*/
				$diakritika = array('Á','á','Č','č','Ď','ď','É','é','Í','í','Ľ','ľ','Ň','ň','Ó','ó','Š','š','Ť','ť','Ú','ú','Ž','ž','Ý','ý');
				$bez = array('A','a','C','c','D','d','E','e','I','i','L','l','N','n','O','o','S','s','T','t','U','u','Z','z','Y','y');
				$_POST['input_pozicia'] = str_ireplace($diakritika,$bez,$_POST['input_pozicia']);
				/*ziskanie zemepisnej sirky a vysky, pre vykreslenie pouzijeme tuto hodnotu, keby pouzivame adresu vzniklo by prilis vela requstov na goecoding API*/
				$ilatlong = $this->googlemaps->get_lat_long_from_address($_POST['input_pozicia']);
				
			// validacia prebehla, ulozime	
				$data = array(
					'pozicia' => $_POST['input_pozicia'],
					'meno' => $_POST['input_meno'],
					'priezvisko' => $_POST['input_priezvisko'],
					'tcislo' => $_POST['input_telcislo'],
					'poplatok' => $_POST['input_poplatok'],
					'latlong' => $ilatlong[0] . ',' . $ilatlong[1],
				);
				$this->notes_model->addCestujuci($data);
				redirect('notes'); //presmerovanie na notes teda na horny index()
				
			}
			else
			{
			//neprebehla, vypiseme chybu
			$data['notes'] = $this->notes_model->getNotes(); //nacitanie dat a ich zobrazenie
			$this->load->view('notes_view', $data);
			redirect('notes');
			}
	}
		
		
		function map()
	{
		$map_settings = $this->notes_model->getMapSettings();
		// print_r($map_settings);
		// exit;
		/*conifg map*/
		$config = array();
		$config['center'] = 'slovakia';
		$config['zoom'] = '8';
		$config['map_div_id']='map';
		$config['map_height'] ='80%';
		$config['map_width'] = '75%';
		$config['loadAsynchronously'] = FALSE;
		
		/*autocomplete*/
		$config['places'] = TRUE;
		$config['placesAutocompleteInputID'] = 'myPlaceTextBox';
		$config['placesAutocompleteInputID2'] = 'myPlaceTextBox2';
		$config['placesAutocompleteBoundsMap'] = TRUE; // set results biased towards the maps viewport
		$config['placesAutocompleteOnChange'] = '';
		
		/*drawing*/
		$config['drawing'] = FALSE;
		$config['drawingDefaultMode'] = 'circle';
		$config['drawingModes'] = array('circle','rectangle','polygon');
		
		/*traffic*/
		$config['trafficOverlay'] = FALSE;
		
		/*coordinates at click*/
		//$config['onclick'] = 'alert(\'You just clicked at: \' + event.latLng.lat() + \', \' + event.latLng.lng());';

		
		/*markers*/
		
		/*2*/
	


		

		
		/*circles*/
		// $circle = array();
		// $circle['center'] = 'banska bystrica, slovakia';
		// $circle['radius'] = '50000';
		// $this->googlemaps->add_circle($circle);
		
		/*Navigation*/
		
		$nav = $this->notes_model->getNavigacia();
		
		
		$config['directions'] = $map_settings[0]->navON;
		
		$config['directionsStart'] = $nav[0]->start;
		
		// print_r($nav);
		// exit;
		$waypoints = array();
		if($nav[0]->waypoint1!=''){
			array_push($waypoints,$nav[0]->waypoint1);
		}
		if($nav[0]->waypoint2!=''){
			array_push($waypoints,$nav[0]->waypoint2);
		}
		if($nav[0]->waypoint3!=''){
			array_push($waypoints,$nav[0]->waypoint3);
		}
		if($nav[0]->waypoint4!=''){
			array_push($waypoints,$nav[0]->waypoint4);
		}
		if($nav[0]->waypoint5!=''){
			array_push($waypoints,$nav[0]->waypoint5);
		}
		$config['directionsWaypointArray'] = $waypoints;
		//$config['directionsWaypointArray'] = 'poprad, slovakia';
		
		$config['directionsEnd'] = 'bratislava, slovakia';
		$config['directionsDivID'] = 'directionsDiv';
		
		/*kreslenie z db*/
		
		/* kreslenie vsetkych vodicov v loop*/
		
		$vodici = $this->notes_model->getVodic();
		for ($x = 0; $x <= count($vodici)-1; $x++)
		{
		$circle = array();
		
		if($vodici[$x]->rcolor == '#000000') // ked je nevyplneney teda cierny nastav default farbu, ak nie daj userovu
		{
		$circle['fillColor'] = '#FF0000';
		}
		else
		{
		$circle['fillColor'] = $vodici[$x]->rcolor;
		}
																	
		$circle['fillOpacity'] = '0.15';							
		$circle['onclick'] = '';	
		
		$circle['center'] = $vodici[$x]->latlong;
		if($vodici[$x]->radius == 0)		//ked je radius nevyplneney defaultne nastav na 50km 
		{$circle['radius'] = 50000;}
		else
		$circle['radius'] = $vodici[$x]->radius*1000;
		$this->googlemaps->add_circle($circle);
		
		$marker = array();
		$marker['draggable'] = FALSE;
		$marker['animation'] = 'DROP';
		$marker['icon'] = 'assets/pickup_camper.png';
		
	
		$marker['infowindow_content'] = 'Meno:' . ' ' . $vodici[$x]->meno.'<br></br>' 
		. 'Priezvisko:' . ' ' . $vodici[$x]->priezvisko . ' <br></br> '
		. 'Pozicia:' . ' ' . $vodici[$x]->pozicia . ' ' . '<br></br>'
		. 'Telfonne cislo:' . ' ' . $vodici[$x]->tcislo . '<br></br>'
		. 'Pocet miest:' . ' ' . $vodici[$x]->pocmiest . '<br></br>'
		. 'SPZ:' . ' ' . $vodici[$x]->spz;
		
		$marker['position'] = $vodici[$x]->latlong;
		$this->googlemaps->add_marker($marker);	
		}
		
		/* kreslenie vsetkych cestujucich v loop*/
		
		
		$cestujuci = $this->notes_model->getCestujuci();
		for ($x = 0; $x <= count($cestujuci)-1; $x++)
		{
			
	


		$marker = array();
		$marker['draggable'] = FALSE;
		$marker['animation'] = 'DROP';
		
		$marker['infowindow_content'] = 'Meno:' . ' ' . $cestujuci[$x]->meno.'<br></br>' 
		. 'Priezvisko:' . ' ' . $cestujuci[$x]->priezvisko . ' <br></br> '
		. 'Pozicia:' . ' ' . $cestujuci[$x]->pozicia . ' ' . '<br></br>'
		. 'Telfonne cislo:' . ' ' . $cestujuci[$x]->tcislo . '<br></br>'
		. 'Poplatok:' . ' ' . $cestujuci[$x]->poplatok . '<br></br>';
		
		
		$marker['position'] = $cestujuci[$x]->latlong;
		$this->googlemaps->add_marker($marker);	
		}
			
		$this->googlemaps->initialize($config);
		
		$data['map'] = $this->googlemaps->create_map();
		return $data['map'];
	}
}
