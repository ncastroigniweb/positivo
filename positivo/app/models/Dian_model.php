<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dian_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getAllConfDian()
    {
    	$q = $this->db->get('dian');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function addConfDian($data = array())
    {

    	if ($this->db->insert('dian', $data)) {
            return true;
        }
        return false;
    }
    public function updateDian($data = array())
    {
    	if ($this->db->update('dian', $data)) {
            return true;
        }
        return false;
    }
    function getDian_api()
    {
        $q = $this->db->get('dian_api');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function addDian_api($data=array())
    {
        if ($this->db->insert('dian_api',$data)) {
            return true;
        }
        return FALSE;
    }
    public function updateDian_api($data=array())
    {
        if ($this->db->update('dian_api',$data)) {
            return true;
        }
        return FALSE;
    }
    public function deleteDian_api()
    {
        if($this->db->truncate('dian_api')){
            return true;
        }
        return false;
    }
    function getOrder_ref()
    {
        $q= $this->db->get('order_ref');
        if ($q->num_rows()>0) {
            return $q->row();
        }
        return FALSE;
    }
    public function updateConfiDianId($current_number)
    {
        $q = $this->db->update('dian', array('current_number' => $current_number+1), array('dian_id' => 1));
        if ($q)
        {
            return TRUE;
        }
            return FALSE;
    }
    public function GetSalesRefDian($id)
    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function updateSalesStatusDian($id,$doc_status_dian,$pdf_dian)
    {
         $q = $this->db->update('sales', array('doc_status_dian'=>$doc_status_dian,'pdf_dian'=>$pdf_dian), array('id' => $id));
        if ($q)
        {
            return TRUE;
        }
            return FALSE;

    }
}