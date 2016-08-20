<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notes_model extends CI_Model {


		function getNotes()
	{
		$q = $this->db->order_by('time desc')
					  ->get('ci_notes'); 		//vytahovanie s db + zoradenie
		
		return $q->result();
	}
	
		function addNote($data)
	{
		$this->db->insert('ci_notes',$data);
		
	}
	
		function addVodic($data)
	{
	
		$this->db->insert('vodici',$data);
		
	}
	
		function addNavigacia($data)
	{
	
		$this->db->update('navigacia',$data);
		
	}
	
	function addMapSettings($data)
	{
	
		$this->db->update('mapsettings',$data);
		
	}
	
	function getMapSettings()
	{
	
		$q = $this->db->get('mapsettings');
		
		return $q->result();
		
	}
	
		function getNavigacia()
	{
	
		$q = $this->db->get('navigacia');
		
		return $q->result();
		
	}
	
		function delVodicById($data)
	{
		$this->db->where('id',$data)
				->delete('vodici');
		
	}
	
		function delCestujuciById($data)
	{
		$this->db->where('id',$data)
				->delete('cestujuci');
		
	}
	
		function updateVodicById($id,$data)
	{
		$this->db->where('id',$id)
				->update('vodici',$data);
		
	}
	
	
	
		function addCestujuci($data)
	{
		$this->db->insert('cestujuci',$data);
		
	}
	
		function getVodic()
	{
		$q = $this->db->get('vodici'); 		//vytahovanie s db + zoradenie
		
		return $q->result();
		
	}
	
		function getVodicById($id)
	{
		$q = $this->db->where('id',$id)
				->get('vodici');
		
		return $q->result();
	}
	
		function getCestujuciById($id)
	{
		$q = $this->db->where('id',$id)
				->get('cestujuci');
		
		return $q->result();
	}
	
		function getCestujuci()
	{
		$q = $this->db->get('cestujuci'); 		//vytahovanie s db + zoradenie
		
		return $q->result();
		
	}
	
	function getKey()
	{
		$q = $this->db
					  ->get('kluc'); 		
	}
}