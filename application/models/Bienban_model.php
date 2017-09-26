<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bienban_model extends CI_Model{
	
	
	function __construct(){
        parent::__construct();
        $this->load->database();
    } 
    
   public function select_bienban($id)
   {
   $this->db->select('*')->where('id_user',$id); 
        $query=$this->db->get("bienban"); 
        return $query->result();
   }
    public function insert_bienban($data)
     {
      $this->db->insert("bienban",$data);
     }

    public function filename_Excel_id($id='')
    {
      $this->db->select('B.file');
      $this->db->from('bienban A');
      $this->db->where('A.id',$id);
      $this->db->join('bieumau B','A.id_bieumau = B.id');
      $query = $this->db->get();
      return $query->first_row();
    }
    public function get_dulieu_id($id='')
    {
        $this->db->select('dulieu')->where('id',$id); 
        $query=$this->db->get("bienban"); 
        return $query->first_row();

    }
    
}