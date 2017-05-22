<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chef extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }

        $this->lang->load('restaurant', $this->Settings->user_language);
        $this->load->model('settings_model');
        $this->load->model('Products_model');
        $this->load->model('auth_model');
        $this->load->library('restaurant');

        // config category view
        $this->category_chef = array("Platos","Plates");
        
        foreach($this->category_chef as $code){
            $cat = $this->Products_model->getCategoryByCode($code);
            if ($cat){
                $this->category_id = $cat->id;
            }
        }
        
       
        if($this->sma->is_waiter()){
            redirect("tables");
        }elseif($this->sma->is_barman()){
            redirect("barman");
        }
    }

    /**
     * @param null $action action for table
     * @param null $id_action number (id_table, id_order_product)
     */
    function index($action = null, $id_action = null)
    {   
        if($action != null){
            switch ($action) {
                case 'view':

                    // Load table information
                    $this->restaurant->select_table($id_action);

                    //load view_right information
                    $this->data['view_right'] = $this->site->getUser()->view_right;

                    // Load order information
                    $this->restaurant->select_order();
                    $this->data['table'] = $this->restaurant->table;
                    $this->data['order'] = $this->restaurant->order;

                    // list chef pending products
                    foreach ($this->restaurant->getOrderItems() as $product_config){
                        // get category info by id
                        $category = $this->settings_model->getCategoryByID($product_config->product_category);
                        //if category product is the same as __construct config chef
                        if (in_array($category->name,$this->category_chef)){
                            // if product is confirmed add to list
                            if ($product_config->product_status == 'confirmed'){


                                if ($product_config->option_id){
                                    $product_config->option_name = $this->restaurant->getProductOptionByID($product_config->option_id)->name;
                                }

                                $this->data['products'][] = $product_config;
                            }
                        }
                    }

                    break;

                case 'dispatch':

                    // Dispatch product by chef
                    $id = filter_var($id_action, FILTER_SANITIZE_NUMBER_INT);
                    $this->restaurant->dispatchOrderItem($id);

                    redirect('chef');

                    break;

                default:

                    break;
            }
        }

        // add customize js
        add_js('templates/chef.js');

        // set data to send in the view
        $this->data['title'] = lang('chef');
        $this->data['nav'] = array(
            'active' => 'kitchen',
            'title_top' => lang('tables'),
            'enable_search' => false
        );
        
        $method_format_date = $this->get_format_date_chef();
        $this->data['delay_product'] = $this->settings_model->getSettings()->delay_product;
        
        // list chef pending products
        $confirmedItems = $this->restaurant->getConfirmedItems();
        if(!empty($confirmedItems)){
            foreach ($confirmedItems as $product_confirmed){

                    // get category info by id
                    $category = $this->settings_model->getCategoryByID($product_confirmed->product_category);

                    //if category product is the same as __construct config chef
                    if (in_array($category->name,$this->category_chef)){

                        // set table list to view chef
                        $table = $this->restaurant->get_table($product_confirmed->product_table);
                        $this->data['list_tables'][$table->id] = $table;

                        $product_confirmed->table_name = $table->name;

                        // Difference minutes of orden
                        $date1 = new DateTime(date("Y-m-d H:i:s"));
                        $date2 = new DateTime($product_confirmed->date_confirmed);

                        $diff = $date1->diff($date2);

                        $product_confirmed->diff_minutes = $this->restaurant->format_interval_custom($diff, $method_format_date);
                        $product_confirmed->total_minutes = $this->restaurant->total_minutes($diff);

                        $dateToAverage = strtotime("-5 hours");
                        $dateToAverage = date("Y-m-d", $dateToAverage);
                        $average_day = $this->restaurant->get_average_day($dateToAverage, "chef");
                        $this->data['average_time'] = !empty($average_day) ? round($average_day->average,2) . " " . lang("minutes_res") : "0 " . lang("minutes_res") ;

                        // get image product
                        $product = $this->Products_model->getProductByID($product_confirmed->product_id);
                        $product_confirmed->image = $product->image;
                        $product_confirmed->subcategory_id = $product->subcategory_id;

                        if ($product_confirmed->option_id){
                            $product_confirmed->option_name = $this->restaurant->getProductOptionByID($product_confirmed->option_id)->name;
                        }

                        $this->data['list_products'][] = $product_confirmed;
                    }
            }
        }
        
        // load template files
        $this->load->view("restaurant/views/header", $this->data);
        $this->load->view("restaurant/views/nav", $this->data);
        $this->load->view("restaurant/views/chef/index");
        $this->load->view("restaurant/views/footer");
    }

    function ajax($request = null, $id_product = null, $param = null){
        if (!$this->input->is_ajax_request()) {
            // No direct script access allowed
            redirect("chef");
        }

        switch ($request) {
            case "list_products" :
                
                $method_format_date = $this->get_format_date_chef();
                
                // get list chef products
                foreach ($this->restaurant->getConfirmedItems() as $product_confirmed){

                    if (!$this->sma->is_cashier($this->site->getUser($product_confirmed->product_waiter)->id)){
                        // get category info by id
                        $category = $this->settings_model->getCategoryByID($product_confirmed->product_category);
                        //if category product is the same as __construct config chef
                        if (in_array($category->name,$this->category_chef)){
                            // Difference minutes of orden
                            $date1 = new DateTime(date("Y-m-d H:i:s"));
                            $date2 = new DateTime($product_confirmed->date_confirmed);
                            $diff = $date1->diff($date2);
                            
                            //data table
                            $table = $this->restaurant->get_table($product_confirmed->product_table);
                            $product_confirmed->table_name = $table->name;

                            $product_confirmed->diff_minutes = $this->restaurant->format_interval_custom($diff, $method_format_date);
                            $product_confirmed->total_minutes = $this->restaurant->total_minutes($diff);
                            $product_confirmed->hour = $this->data['hour'];
                            $product_confirmed->min = $this->data['min'];
                            $product_confirmed->seg = $this->data['seg'];
                            $product_confirmed->delay_product = $this->settings_model->getSettings()->delay_product;

                            // get waiter
                            $product_confirmed->waiter_name = $this->site->getUser($product_confirmed->product_waiter)->first_name;

                            // get image product
                            $product = $this->Products_model->getProductByID($product_confirmed->product_id);
                            $product_confirmed->image = $product->image;
                            $product_confirmed->subcategory_id = $product->subcategory_id;                            

                            $product_confirmed->option_name = $this->restaurant->getProductOptionByID($product_confirmed->option_id)->name;

                            $general_list[] = $product_confirmed;
                        }
                    }
                }
                
                echo json_encode($general_list);

                break;

            case 'dispatch':
                
                // Dispatch product by chef
                $id = filter_var($id_product, FILTER_SANITIZE_NUMBER_INT);
                
                $suspended_item = $this->Products_model->getSuspendedItem($id);
                $date1 = new DateTime(date("Y-m-d H:i:s"));
                $date2 = new DateTime($suspended_item->date_confirmed);
                $diff = $date1->diff($date2);
                
                //average
                $dateToAverage = strtotime("-5 hours");
                $dateToAverage = date("Y-m-d", $dateToAverage);
                $average_day = $this->restaurant->get_average_day($dateToAverage, "chef");
                
                if ($average_day){
                    $total_time = $this->restaurant->total_minutes($diff) + $average_day->total_minutes;
                    $amount = $average_day->amount_products + 1;
                    $average = $total_time/$amount;
                    
                    $this->restaurant->set_average_day($total_time, $amount, "chef", date("Y-m-d"), $average);
                }else {
                    $total_time = $this->restaurant->total_minutes($diff);
                    $amount = 1;
                    $this->restaurant->set_average_day($total_time, $amount, "chef");
                }
                    
                echo $this->restaurant->dispatchOrderItem($id);

                break;
            
            case 'update_view_right':
                ($param) ? $param = false : $param = true;
                //id_product is id_user
                if($this->sma->updateUserViewRight($id_product, $param)){
                    
                    if($param){
                        echo 1;
                    }else{
                        echo 0;
                    }
                }else{
                    echo "fallo";
                }
                
                break;
                
            case 'update_average':
                
                $dateToAverage = strtotime("-5 hours");
                $dateToAverage = date("Y-m-d", $dateToAverage);
                $average_day = $this->restaurant->get_average_day($dateToAverage, "chef");
                
                if($average_day){
                    if($average_day->average == 1 ){
                        $average_time = round($average_day->average,2) . " " . lang("minute");
                    }else{
                        $average_time = round($average_day->average,2) . " " . lang("minutes_res");
                    }
                }else{
                    $average_time = 0 . " " . lang("minutes_res");
                }
                
                echo $average_time;
                break;
                
            case 'send_email':
                
                $settings = $this->settings_model->getSettings();
                
                $product = $this->Products_model->getSuspendedItem($id_product);
                
                $message = sprintf(lang("chef_message"), $product->product_name, intval($product->quantity), $settings->delay_product);
                
                if(isset($settings->send_to)){
                    if ($settings->send_to != null) {
                        $emails = explode(",", $settings->send_to);
                    
                        foreach ($emails as $email) {
                            $this->sma->send_email($email, "Limite de tiempo superado", $message);
                        }
                    }
                }
                
                break;
                
            default :
                break;
        }
    }
    
    public function dispatched(){
            
         // set data to send in the view
        $this->data['title'] = lang('chef');
        $this->data['nav'] = array(
            'active' => 'chef/dispatched',
            'title_top' => lang('tables'),
            'enable_search' => false
        );

        $permissions = ($this->sma->is_admin() || $this->sma->is_cashier() ) ? true : false ;

        // list chef pending products
        foreach ($this->restaurant->getDispatchedItems(150,$this->category_id, $permissions) as $product){
            
            // set table list to view chef
            $table = $this->restaurant->get_table($product->product_table);
            $this->data['list_tables'][$table->id] = $table;

            $product->table_name = $table->name;

            // Difference minutes of orden
            $date1 = new DateTime($product->date_confirmed);
            $date2 = new DateTime($product->date_dispatched);
            $diff = $date1->diff($date2);

            $product->diff_minutes = format_interval($diff);

            if ($product->option_id){
                $product->option_name = $this->restaurant->getProductOptionByID($product->option_id)->name;
            }

            $this->data['list_products'][] = $product;
        }
        
        // load template files
        $this->load->view("restaurant/views/header", $this->data);
        $this->load->view("restaurant/views/nav", $this->data);
        $this->load->view("restaurant/views/tables/dispatched");
        $this->load->view("restaurant/views/footer");
        
        
    }
    
    public function get_format_date_chef($format_date_chef = null) {
        if($format_date_chef == null){
            $format_date_chef = $this->settings_model->getSettings()->date_format_chef;
        }
        
        $this->data['hour'] = '';
        $this->data['min'] = '';
        $this->data['seg'] = '';
        
        if(strpos($format_date_chef, "H") !== false ){
            $this->data['hour'] = 'hour';
        }
        if(strpos($format_date_chef, "i") !== false ){
            $this->data['min'] = ' min';
        }
        if(strpos($format_date_chef, "s") !== false ){
            $this->data['seg'] = ' seg';
        }
        
        if(strpos($format_date_chef, "H") !== false ){
            if (strpos($format_date_chef, "i") !== false) {
                if (strpos($format_date_chef, "s") !== false) {
                    $method_format_date = 1;
                }else{
                    $method_format_date = 2;
                }
            }else{
                if (strpos($format_date_chef, "s") !== false) {
                    $method_format_date = 3;
                }else{
                    $method_format_date = 4;
                }
            }
        }else{
            if (strpos($format_date_chef, "i") !== false) {
                if (strpos($format_date_chef, "s") !== false) {
                    $method_format_date = 5;
                }else{
                    $method_format_date = 6;
                }
            }else{
                if (strpos($format_date_chef, "s") !== false) {
                    $method_format_date = 7;
                }else{
                    $method_format_date = 8;
                }
            }
        }
        
        return $method_format_date;
    }

}
