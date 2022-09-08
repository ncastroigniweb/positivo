<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_actions extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(-1);
        $this->load->model('pos_model');
        $this->load->model('sales_model');
    }
    
    function index() {
        echo "Bienvenido";
    }
    
    public function delete_items_by_suspendId($id = null) {
        if($id != null){
            if($this->pos_model->deleteBillItems($id)){
                echo "eliminado";
            }else{
                echo "No eliminó";
            }
        }else{
            echo 'Falta dato';
        }
        
    }
    
    public function change_created_by_sales($id = null, $id_creator = null) {
        if($id != null && $id_creator != null){
            if($this->sales_model->updateCreatedBy($id, $id_creator)){
                echo "Cambiado el created by";
            }else{
                echo "No cambió";
            }
        }else{
            echo 'Falta dato';
        }
    }
    
    public function change_state_table($id = null, $status = null) {
        $this->load->model('tables_model');
        if($id != null && $status != null){
            $fields = array(
                'status' => $status
            );
            if($this->tables_model->update_table($id, $fields)){
                echo "Cambiado el status";
            }else{
                echo "No cambió";
            }
        }else{
            echo 'Falta dato';
        }
        
    }
}
