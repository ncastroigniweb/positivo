<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Restaurant
 *
 * @author Juan Manuel Pinzon
 */
class Tables_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('sma');
        $this->db->table_name = "tables";
    }
    
    // Return acomplete list of active tables
    public function get_tables(){
        
        $data = array();
        $this->db->order_by('name','asc');
        $q = $this->db->get_where($this->db->table_name, array('flg_del' => 0));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[$row->id] = $row;
                
                $data[$row->id]->available = false;
                        
                if ($this->session->userdata('user_id') == $row->waiter){
                    $data[$row->id]->available = true;
                }
                
                if ($this->sma->is_admin() || $this->sma->is_cashier() || $this->sma->is_product_admin()){
                    $data[$row->id]->available = true;
                }
                
                if ($row->status == 0){
                    $data[$row->id]->available = true;
                }
                
                if ($row->status == 2 && $row->waiter == $this->session->userdata('user_id')){
                    $data[$row->id]->available = true;
                }
                        
            }
        }
        
        return $data;
    }
    
    
    // Return acomplete list of active tables
    public function get_table($id = null){
        
        $table = new stdClass();

        if ($id){
            
            $q = $this->db->get_where($this->db->table_name, array('id' => $id),1);
            if ($q->num_rows() > 0) {
                    //$row->available = false;
                    $table = $q->row();
                    $table->available = ($table->status == 0) ? true : false;
                    
                        
                    if ($this->session->userdata('user_id') == $table->waiter){
                        $table->available = true;
                    }

                    if ($this->sma->is_admin() || $this->sma->is_cashier() || $this->sma->is_product_admin()){
                        $table->available = true;
                    }

                    if ($table->status == 0){
                        $table->available = true;
                    }

                    if ($table->status == 2 && $table->waiter == $this->session->userdata('user_id')){
                        $table->available = true;
                    }
                    
                    return $table;
                
                }else{
                    return false;
                }
            }
        
        return false;
    }
    
    
    // Update fields of a table record passed by array ( key => value )
    public function update_table($id = null, array $fields){
        if ($id and $fields){
            $this->db->where('id', $id);
            return $this->db->update($this->db->table_name, $fields); 
        }else{
            return false;
        }
    }
    
    public function changeTable($id, $idTable)
    {
        if ($id){

            $data = array('product_table' => $idTable);
            $this->db->where('suspend_id', $id);
            $this->db->update('suspended_items', $data);
            
            $data = array('id_table' => $idTable);
            $this->db->where('id', $id);
            return $this->db->update('suspended_bills', $data);

        }

        return FALSE;
    }
}
