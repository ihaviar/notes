<?php

function uid()
{
	$ci =& get_instance();
	$ci->load->library('tank_auth');
	return $ci->tank_auth->get_user_id();
}

function logged_in()
{
	$ci =& get_instance();
	$ci->load->library('tank_auth');
	
	return $ci->tank_auth->is_logged_in();

}

function get_user_name()
{
	$ci =& get_instance();
	$ci->load->library('tank_auth');
	
	return $ci->tank_auth->get_username();

}

