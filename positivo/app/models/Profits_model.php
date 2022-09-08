<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profits_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getOtherProfitsByID($id)
    {
        $q = $this->db->get_where('other_profits', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addOtherProfits($data = array())
    {
        if ($this->db->insert('other_profits', $data)) {
            if ($this->site->getReference('ex') == $data['reference']) {
                $this->site->updateReference('ex');
            }
            return true;
        }
        return false;
    }

    public function updateOtherProfits($id, $data = array())
    {   
        if ($this->db->update('other_profits', $data, array('id' => $id))) {
//            print_r("hola");
//            $this->sma->print_arrays($data);
            return true;
        }
        return false;
    }

    public function deleteOtherProfits($id)
    {
        if ($this->db->delete('other_profits', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getOtherProfitsCategories()
    {
        $q = $this->db->get('other_profits_categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getOtherProfitsCategoryByID($id)
    {
        $q = $this->db->get_where("other_profits_categories", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

}
