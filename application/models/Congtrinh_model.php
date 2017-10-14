<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Congtrinh_model extends CI_Model{
	
	
	function __construct(){
        parent::__construct();
        $this->load->database();
    } 
    
   public function select_congtrinh($id)
   {
   $this->db->select('*')->where('id_user',$id); 
        $query=$this->db->get("congtrinh"); 
        return $query->result();
   }
   public function get_congtrinh($id)
   {
   $this->db->select('file')->where('id',$id); 
        $query=$this->db->get("bieumau"); 
        return $query->result_array();
   }
   public function insert_congtrinh($data)
     {
      $this->db->insert("congtrinh",$data);
     }
    public function update_congtrinh($id,$data)
     {
      $this->db->where('id',$id)
              ->update("congtrinh",$data);
     }
    public function select_dulieu_id($id='')
    {
        $this->db->select('*')->where('id',$id); 
        $query=$this->db->get("congtrinh"); 
        return $query->first_row();

    }
    
}