<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bieumau_model extends CI_Model{
	
	
	function __construct(){
        parent::__construct();
        $this->load->database();
    } 
    
   public function select_bieumau($id)
   {
   $this->db->select('*')->where('id_user',$id); 
        $query=$this->db->get("bieumau"); 
        return $query->result();
   }
   public function get_bieumau($id)
   {
   $this->db->select('file')->where('id',$id); 
        $query=$this->db->get("bieumau"); 
        return $query->result_array();
   }
   public function insert_bieumau($data)
     {
      $this->db->insert("bieumau",$data);
     }
    public function update_bieumau($id,$data)
     {
      $this->db->where('id',$id)
              ->update("bieumau",$data);
     }
    public function select_dulieu_id($id='')
    {
        $this->db->select('file,ten,type_bieumau,dulieu')->where('id',$id); 
        $query=$this->db->get("bieumau"); 
        return $query->first_row();

    }
    
}