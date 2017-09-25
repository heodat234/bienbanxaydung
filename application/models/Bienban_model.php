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
    // public function select_dulieu_id($id='')
    // {
    //     $this->db->select('ten,dulieu')->where('id',$id); 
    //     $query=$this->db->get("bieumau"); 
    //     return $query->first_row();

    // }
    
}