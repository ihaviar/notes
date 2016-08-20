<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
	
	function register()
	{

		$data = array(
		
		'meno' => $_POST['meno'],
		'priezvisko' => $_POST['priezvisko'],
		'email' => $_POST['email'],
		'heslo' => sha1($_POST['heslo']),
	);
						
	return $this->db->insert('users',$data);
	}
						
	function check()
	{
		$select = $this->db->where('meno',$_POST['meno'])
						   ->where('heslo',$_POST['heslo'])
						   ->get('users');
	    
		return $select->num_rows();
	}

	function getUserData($meno)
	{
		$select = $this->db->select('uid','meno','priezvisko','email')
						->where('meno',$meno)
					   ->limit(1)
					   ->get('users');
					   
	if ($select->num_rows() > 0) return $select->row_array();
	else return false;
	}
}