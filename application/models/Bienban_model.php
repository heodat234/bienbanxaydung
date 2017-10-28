<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bienban_model extends CI_Model{
	
	
	function __construct(){
        parent::__construct();
        $this->load->database();
    } 
    
   public function select_bienban($id)
   {
    $this->db->select('B.ten,B.id as id_congtrinh,A.ten_bienban,A.id,A.created_at,A.type_bienban');
      $this->db->from('bienban A');
      $this->db->where('A.id_user',$id);
      $this->db->order_by("A.created_at","desc");
      $this->db->join('congtrinh B','A.id_congtrinh = B.id');
      $query = $this->db->get();
        return $query->result_array();
   }

   public function get_bienban($id)
   {
   $this->db->select('*')->where('id',$id); 
        $query=$this->db->get("bienban");
        return $query->first_row();
   }

   public function get_time_update($id)
   {
   $this->db->select('created_at')->where('id',$id); 
        $query=$this->db->get("bienban");
        return $query->first_row();
   }

    public function insert_bienban($data)
     {
      $this->db->insert("bienban",$data);
     }
    public function update_data_bien_ban($id,$data)
     {
      $this->db->where('id',$id)
              ->update('bienban',$data);
     }
    // public function select_dulieu_id($id='')
    // {
    //     $this->db->select('ten,dulieu')->where('id',$id); 
    //     $query=$this->db->get("bieumau"); 
    //     return $query->first_row();

     
    public function filename_id($id='')
    {
      $this->db->select('B.file');
      $this->db->from('bienban A');
      $this->db->where('A.id',$id);
      $this->db->join('bieumau B','A.id_bieumau = B.id');
      $query = $this->db->get();
      return $query->first_row();
    }
    
    
}