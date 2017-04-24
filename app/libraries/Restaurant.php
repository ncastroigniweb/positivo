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
class Restaurant {
    
    var $table;
    var $order;
    var $warehouse;
    var $product;
    var $config;
    
    public function __construct() {
        
        // Load CI core
        $this->CI =& get_instance();
        
        // Load required instances
        $this->CI->load->library('sma');
        $this->CI->load->helper(array('form', 'url'));
        $this->CI->load->model('Pos_model');
        $this->CI->load->model('Tables_model');
        $this->CI->load->model('Products_model');
        $this->CI->load->model('Companies_model');
        
        // Table Default values
        $this->table = new stdClass();
        $this->table->id = null;
        $this->table->guests = 1;
        $this->table->waiter = $this->CI->session->userdata('user_id');
        $this->table->comments = '';
        
        $this->pos_settings = $this->CI->Pos_model->getSetting();
        
        $this->config = new stdClass;
        $this->config->waiter_group = 'mozo';
        $this->config->warehouse = 1;
        
        // Order Default values
        $this->order = new stdClass();
        $this->order->id = 0;
        $this->order->customer = $this->pos_settings->default_customer;
        $this->order->biller = $this->pos_settings->default_biller;
        $this->order->total = 0;
        $this->order->product = 0;
        
        // Default Product values
        $this->product = new stdClass();
        $this->product->id = 0;
        $this->product->qty = 1;
        $this->product->comments = '';
        $this->product->status = 'pending';
        $this->product->option_id = null;
        
   
        if (!$this->pos_settings->default_biller || !$this->pos_settings->default_customer || !$this->pos_settings->default_category) {
            $this->session->set_flashdata('warning', lang('please_update_settings'));
            redirect('pos/settings');
        }
               
        
    }
    
    
    // Set table for procedures
    public function select_table($id = null){

        if ($id){
            $this->table->id = $id;
            $tableFields = $this->CI->Tables_model->get_table($id);
            
            foreach ($tableFields as $key => $value){
                $this->table->$key = $value;
            }
        }
    }
    
    // Set order information
    public function select_order($id = null){
        if($id || $this->table->bill){
            $this->order->id = ($id) ? $id : $this->table->bill;
            $tableFields = $this->CI->Pos_model->getOpenBillByID($this->order->id);
            
            foreach ($tableFields as $key => $value){
                $this->order->$key = $value;
            }
        }
    }
    
    
    // Return all available tables 
    public function get_tables(){
        return $this->CI->Tables_model->get_tables();
    }
    
    
    // Return specific table information from selected table
    public function get_table($id = null){
        if ($id){
            return $this->CI->Tables_model->get_table($id);
        }
    }
    
    
    // Update the table status and fields
    public function reserve_table(){
        
        if ($this->table->id && $this->table->waiter && $this->table->guests){
            
            $sData = array(
                'count' => 0,
                'biller_id' => $this->order->biller,
                'customer_id' => $this->order->customer,
                'id_waiter' => $this->table->waiter,
                'id_table' => $this->table->id,
                'warehouse_id' => $this->config->warehouse,
                'customer' => $this->CI->site->getCompanyByID($this->order->customer)->name,
                'date' => date("Y-m-d H:i:s"),
                'suspend_note' => $this->CI->sma->clear_tags($this->table->comments),
                'total' => 0,
                'order_tax_id' => 0,
                'order_discount_id' => NULL,
                'created_by' => $this->table->waiter
            );
            
            // Create a new suspend order
            $this->CI->db->insert('suspended_bills', $sData);
            $suspend_id = $this->CI->db->insert_id();
            
            // Update table details
            $data = array(
               'status' => 1,
               'waiter' => $this->table->waiter,
               'guests' => $this->table->guests,
               'bill'   => $suspend_id
            );
                        
            return $this->CI->Tables_model->update_table($this->table->id, $data);
            
        }else{
            return false;
        }
        
    }
    
    public function changeTable($id_newTable = null) {
        
        if($id_newTable){
            
            $new_table = $this->get_table($id_newTable);
            
            if($new_table && $new_table->bill == 0 && $new_table->status == 0){
                //data to old table
                $data_old = array(
                       'status' => 0,
                       'waiter' => 0,
                       'guests' => 0,
                        'bill'  => 0
                    );
                // Update table details
                $data = array(
                   'status' => 1,
                   'waiter' => $this->table->waiter,
                   'guests' => $this->table->guests,
                   'bill'   => $this->table->bill
                );

                $this->CI->Tables_model->changeTable($this->table->bill, $id_newTable);

                $this->CI->Tables_model->update_table($this->table->id, $data_old);
                return $this->CI->Tables_model->update_table($id_newTable, $data);
            }
        }
    }
    
    
    // Update the table status and fields when order is closed
    public function free_table(){
        
        if ($this->table->id && $this->table->waiter && $this->table->guests){
            
            $data = array(
               'status' => 0,
               'waiter' => 0,
               'guests' => 0,
                'bill'  => 0
            );
            
            $this->CI->Pos_model->log_suspended_sale($this->table->bill, "free-table");
//            $this->CI->Pos_model->deleteBillItems(0);
            
            // Remove suspended order
            $this->CI->Pos_model->deleteBill($this->table->bill);
            
            return $this->CI->Tables_model->update_table($this->table->id, $data);
            
        }else{
            return false;
        }
        
    }
    
    
    // Update the table status and fields when a bill is requested
    public function bill_table(){
        
        if ($this->table->id && $this->table->waiter && $this->table->guests){

            $data = array(
               'status' => 2,
            );
            return $this->CI->Tables_model->update_table($this->table->id, $data);
        }else{
            return false;
        }
        
    }
    
    
    
    // Add a product to suspended order
    public function product2order(){
        
        if ($this->table->bill && $this->product->id && $this->product->qty){
            
            // Get product information
            $product = $this->CI->Pos_model->getProductByID($this->product->id);
            
            $this->Settings = $this->CI->site->get_setting();
            if ($this->Settings->status_premium_price && !empty($product->premium_price)) {
                $product->price = $product->premium_price;
            }
            if ($product->promotion) {
                $product->price = $product->promo_price;
            }
            
            $product->unit_price = $product->price;
            list($item_tax, $tax, $real_unit_price) = $this->CI->sma->sum_product_tax($product->tax_rate, $product, $product->unit_price, $product->price);
            
            if ($this->product->option_id){
                $option = (object) $this->CI->Pos_model->getProductOptionByID($this->product->option_id);
            }
            
            $unitById = $this->CI->site->getUnitByID($product->purchase_unit);
            
                $item = array(
                    'suspend_id'      => $this->table->bill,
                    'product_id'      => $product->id,
                    'product_table'   => $this->table->id,
                    'product_code'    => $product->code,
                    'product_status'  => $this->product->status,
                    'product_waiter'  => $this->CI->session->userdata('user_id'),
                    'product_name'    => $product->name,
                    'product_type'    => $product->type,
                    'product_category'=> $product->category_id,
                    'option_id'       => $this->product->option_id,
                    'net_unit_price'  => (!$this->product->option_id) ? $product->unit_price : $product->unit_price + $option->price,
                    'unit_price'      => (!$this->product->option_id) ? $product->price : $product->price + $option->price,
                    'quantity'        => $this->product->qty,
                    'product_unit_id' => $product->purchase_unit,
                    'product_unit_code'=> ($unitById ? $unitById->code : false ) ? $product->purchase_unit : NULL,
                    'unit_quantity'   => $this->product->qty,
                    'warehouse_id'    => $this->config->warehouse,
                    'item_tax'        => $item_tax,
                    'tax_rate_id'     => $product->tax_rate,
                    'tax'             => $tax,
                    'discount'        => 0,
                    'item_discount'   => 0,
                    'subtotal'        => ((!$this->product->option_id) ? $product->price : $product->price + $option->price) * $this->product->qty,
                    'serial_no'       => NULL,
                    'real_unit_price' => (!$this->product->option_id) ? $real_unit_price : $real_unit_price + $option->price,
                    'comments'        => $this->product->comments
                );
            
                return $this->CI->db->insert('suspended_items', $item);
            
        }else{
            return false;
        }                    
    }
    
    // Update the customer of order
    public function customer2Order($id = null){
        if ($this->table->bill && $id){
            $customer = $this->CI->Companies_model->getCompanyByID($id);
            
            $data = array(
               'customer_id' => $id,
               'customer' => $customer->name,
            );

            $this->CI->db->where('id', $this->table->bill);
            return $this->CI->db->update('suspended_bills', $data); 
        }
    }

    // Update the customer of order
    public function change_waiter($id_waiter = null){

        if ($id_waiter && $this->table->id){
            // update waiter to suspended bills
            $data = array(
                'id_waiter' => $id_waiter,
            );

            $this->CI->db->where('id', $this->table->bill);
            $this->CI->db->update('suspended_bills', $data);

            // Update table details
            $data = array(
                'waiter' => $id_waiter
            );

            return $this->CI->Tables_model->update_table($this->table->id, $data);

        }else{
            return false;
        }
    }
    
    // Remove a producto from order
    public function removeProductOrder($id) {
        if ($this->table && $id){
            return $this->CI->Pos_model->removeSuspendItem($id);
        }
        
    }
    
    // Return a list of order items
    public function getOrderItems(){
        if ($this->table->id && $this->table->bill || $this->order->id){
            $items = $this->CI->Pos_model->getSuspendedSaleItems($this->table->bill);
            return ($items) ? $items : array();
            
        }else{
            return array();
        }
    }

    // Return option information given id
    public function getProductOptionByID($id){
        if ($id){
            return (object) $this->CI->Pos_model->getProductOptionByID($id);
        }        
    }
    
    
    // Return an unconfirmed items list
    public function getConfirmedItems($id = null){
        return $this->CI->Pos_model->getProductsByStatus('confirmed',$id);
    }
    
    // Return an unconfirmed items list
    public function getDispatchedItems($limit = 50,$category = null, $permissions){
//        $active = $this->CI->Pos_model->getProductsByStatus('dispatched',$id,$limit,'date_dispatched','DESC',$category);
//        $dispatched = $this->CI->Chef_model->get_dispatched_list($limit - count($active));
//        return array_merge($active, $dispatched);
        return $this->CI->Products_model->get_dispatched_list($limit,$category,$permissions);
        
    }
    
    public function confirmOrderItems(){
        if ($this->table->bill != 0){
            return $this->CI->Pos_model->updateProductStatus($this->table->bill,'confirmed');
        }
    }
    
    // Mark as dispatched a product by chef or barman
    public function dispatchOrderItem($item = null){
        if ($item){
            
            $product = (array) $this->CI->Products_model->getSuspendedItem($item);
            $this->CI->Products_model->dispatchItem($product);
            return $this->CI->Pos_model->updateProductStatus(null,'dispatched',$item);
        }
    }
    
    
    // Update suspend bill counter
    public function billCounter(){
        
        if ($this->table->bill){
                    
            $this->CI->db->like('suspend_id', $this->table->bill);
            $this->CI->db->from('suspended_items');
            $items = $this->CI->db->count_all_results();
        
            $data = array(
               'count'  => $items
            );
            
            $this->CI->db->where('id', $this->table->bill);
            return $this->CI->db->update('suspended_bills', $data);
        }
    }
    
    
    // Return parent categories of products
    public function getParentCategories(){
        return $this->CI->Products_model->getParentCategories();
    }
    

    // Return child categories given a parent id
    public function getSubCategoriesByParent($parent = null){
        if ($parent){
            return  $this->CI->Products_model->getSubCategoryByParent($parent);
        }
    }
    
    // Return child categories given parent categories
    public function getSubCategoriesByParents(array $parents){
        if ($parents){
            return  $this->CI->Products_model->getSubCategoriesByParents($parents);
        }
    }
    
    // Return subcategory products
    public function getCategoryProducts($id = null){
        if($id){
            return $this->CI->Products_model->getSubCategoryProducts($id);
        }
    }
    
    // Return category or subcategory details
    public function getCategoryDetails($id = null){
        if ($id){
            return $this->CI->Products_model->getCategoryByID($id);
        }
    }
    
    // Retunr a product detals given an id
    public function getProductByID($id = null){
        if ($id){
            
            // Get general product information
            $product = $this->CI->Products_model->getProductByID($id);
            // Get product variants
            $product->options = $this->CI->Products_model->getProductOptions($id);
            return $product;
            
        }
    }
    
    // Return a complete list of waiters
    public function getWaiters(){
        $group = $this->CI->sma->getGroupByName($this->config->waiter_group)->id;
        return $this->CI->db->get_where('users', array('group_id' => $group))->result_object();
    }
    
    public function getWaitersIDS(){
        $group = $this->CI->sma->getGroupByName($this->config->waiter_group)->id;
        $this->CI->db->select('id');
        $this->CI->db->where('group_id',$group);
        $waiters  = $this->CI->db->get('users')->result_array();
        return (array_column($waiters,'id'));
    }
    
    // Return a complete list of tables
    public function getFreeTables(){
        
        $this->CI->db->select('*');
        $this->CI->db->where('status',0);
        $this->CI->db->order_by('id', 'ASC');
        $this->CI->db->from('tables');
        $query=$this->CI->db->get();
        return $query->result();
    }
    
    // Return a complete list of tables taken
    public function getTablesTaken(){
        
        $this->CI->db->select('*');
        $this->CI->db->where('status',1);
        $this->CI->db->or_where('status',2);
        $this->CI->db->order_by('id', 'ASC');
        $this->CI->db->from('tables');
        $query=$this->CI->db->get();
        return $query->result();
    }
    
    public function getCustomers(){
        return $this->CI->Companies_model->getCustomers();
    }
    
    
    public function addCustomer($name = null,$email = '',$phone  = '',$address = ''){
            if ($name){
                $user = array(
                    'group_id' => '3',
                    'group_name' => 'customer',
                    'customer_group_id' => '1',
                    'customer_group_name' => 'General',
                    'name' => $name,
                    'company' => $name,
                    'vat_no' => '',
                    'address' => $address,
                    'city' => '',
                    'state' => '',
                    'postal_code' => '',
                    'country' => 'Colombia',
                    'phone' => $phone,
                    'email' => $email,
                    'invoice_footer' => NULL,
                    'payment_term' => '0',
                    'logo' => 'logo.png',
                    'award_points' => '0',
                    'deposit_amount' => NULL,
                    'price_group_id' => '1',
                    'price_group_name' => 'Default'
                );
        
                return $this->CI->Companies_model->addCompany($user);
            }
            return false;
    }
    
    public function format_interval_custom(DateInterval $interval, $method) {
        
        switch ($method) {
            case 1:
                $hour = $this->total_hours($interval);
                $result = "";
                if ($interval->h) { $result .= $interval->format("{$hour}h "); }
                if ($interval->i) { $result .= $interval->format("%i' "); }
                if ($interval->s) { $result .= $interval->format("%s'' "); }

                return $result;
            
            case 2:
                $hour = $this->total_hours($interval);
                $result = "";
                if ($interval->h) { $result .= $interval->format("{$hour}h "); }
                if ($interval->i) { $result .= $interval->format("%i' "); }

                return $result;
            
            case 3:
                
                $hour = $this->total_hours($interval);
                $result = "";
                if ($interval->h) { $result .= $interval->format("{$hour}h "); }
                if ($interval->s) { $result .= $interval->format("%s'' "); }

                return $result;
            
            case 4:
                $hour = $this->total_hours($interval);
                $result = "";
                if ($interval->h) { $result .= $interval->format("{$hour}h "); }

                return $result;
            
            case 5:
                $minutes = $this->total_minutes($interval);
                $result = "";
                if ($interval->i) { $result .= $interval->format("{$minutes}' "); }
                if ($interval->s) { $result .= $interval->format("%s'' "); }

                return $result;
            
            case 6:
                $minutes = $this->total_minutes($interval);
                $result = "";
                if ($interval->i) { $result .= $interval->format("{$minutes}' "); }

                return $result;
            
            case 7:
                $seg = $this->total_seg($interval);
                $result = "";
                if ($interval->s) { $result .= $interval->format("{$seg}'' "); }

                return $result;
            
            case 8:
                
                $result = "";
                if ($interval->y) { $result .= $interval->format("%yy "); }
                if ($interval->m) { $result .= $interval->format("%mm "); }
                if ($interval->d) { $result .= $interval->format("%dd "); }
                if ($interval->h) { $result .= $interval->format("%hh "); }
                if ($interval->i) { $result .= $interval->format("%i' "); }
                if (!$interval->d && $interval->s) { $result .= $interval->format("%s'' "); }

                return $result;

        }
        
        
    }
    
    public function total_seg(DateInterval $interval) {
        $seg = 0;
        
        if ($interval->d) { $seg+= ($interval->d*86400); }
        if ($interval->h) { $seg+= ($interval->h*3600); }
        if ($interval->i) { $seg+= $interval->i*60; }
        if ($interval->s) { $seg+= $interval->s; }
        
        return $seg;
    }
    
    public function total_minutes(DateInterval $interval) {
        $minutes = 0;
        
        if ($interval->y) { $minutes+= ($interval->y*525600); }
        if ($interval->m) { $minutes+= ($interval->m*43200); }
        if ($interval->d) { $minutes+= ($interval->d*1440); }
        if ($interval->h) { $minutes+= ($interval->h*60); }
        if ($interval->i) { $minutes+= $interval->i; }
        
        return $minutes;
    }
    
    public function total_hours(DateInterval $interval) {
        $hours = 0;
        
        if ($interval->y) { $hours+= ($interval->y*8760); }
        if ($interval->m) { $hours+= ($interval->m*730); }
        if ($interval->d) { $hours+= ($interval->d*24); }
        if ($interval->h) { $hours+= ($interval->h); }
        
        return $hours;
    }
    
    public function get_average_day($date, $role) {
        $query = $this->CI->db->get_where('average_day', array('date' => $date, 'role' => $role), 1);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return FALSE;
    }
    
    public function set_average_day($total_minutes, $amount_products, $role, $date = null, $average = 0) {
        
        if($date == null){
            $dateToAverage = strtotime("-5 hours");
            $date = date("Y-m-d", $dateToAverage);
            $insert = true;
            $average = $total_minutes;
        }else{
            $insert = false;
        }
        
        $data = array(
            'role' => $role,
            'date' => $date,
            'total_minutes' => $total_minutes,
            'amount_products' => $amount_products,
            'average' => $average
        );
        
        if($insert){
            $this->CI->db->set($data);
            return $this->CI->db->insert('average_day'); 
        }else{
            return $this->CI->db->update('average_day', $data, array('date' => $date, 'role' => $role));
        }
        
    }
    
    public function updateOnlyTables($id, $statusOnlyTablesTaken) {
        return $this->CI->db->update('users', array('only_tables_taken' => $statusOnlyTablesTaken), array('id' => $id));
    }
    
    public function getUriOrderTable() {
        $controller = $this->CI->uri->segment(1, 0);
        
        if($controller && strcmp($controller, "tables") == 0){
            
            $method = $this->CI->uri->segment(2, 0);
            
            if($method && strcmp($method, "order") == 0){
                
                $action = $this->CI->uri->segment(3, 0);
                
                if($action && strcmp($action, "edit") == 0){
                    
                    $id = $this->CI->uri->segment(4, 0);
                    
                    if($id){
                        $table = $this->get_table($id);
                        if(isset($table->bill)){
                            return array(true, $table->bill);
                        }
                    }
                }
            }
        }
        
        return array(false, 0);
    }
}
