<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tables extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }

        if ($this->Customer || $this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
       
        $this->load->helper(array('form', 'url'));
         $this->lang->load('pos', $this->Settings->user_language);
        $this->lang->load('restaurant', $this->Settings->user_language);
        $this->load->library('restaurant');
        $this->load->model('products_model');
        $this->load->model('reports_model');
        $this->load->model('pos_model');

        if($this->sma->is_chef()){
            redirect("chef");
        }elseif($this->sma->is_barman()){
            redirect("barman");
        }
        
    }

    function index()
    {
        $this->sma->checkPermissions('index', null, 'sales');
         
        // set data to send in the view
        $this->data['title'] = lang('tables');
        $this->data['nav'] = array(
            'active' => 'home',
            'title_top' =>  lang('tables'),
            'enable_search' => true
        );
        
        if ($this->input->post('table')){
            // Search table
            $table_data = $this->restaurant->get_table($this->input->post('table'));
            if($table_data){
                $this->data['tables'][] = $table_data;
            }else{
                redirect("tables");
            }
        }else{
            // Get all available tables
            $this->data['tables'] = $this->restaurant->get_tables();
        }

        $pending_orders = $this->Pos_model->getAllSuspendedSales();

        $suspend_sales_total = "";
        
        if(!empty($pending_orders)){
            foreach ($pending_orders as $order){
                $order_items = $this->Pos_model->getSuspendedSaleItems($order->id);
                if($order_items){
                    foreach ($order_items as $order_item) {
                        if ($order_item->product_status != "pending") {
                            $suspend_sales_total += $order_item->subtotal;
                        }
                    }
                }
            }
        }
        
        $cashiers = $this->Pos_model->get_register_cashiers();
        $taxsales = array();
        $tipsales = array();
        $totalsales = array();
        foreach ($cashiers as $value) {
            if($value->date != null){
                $cashier_taxsales = $this->Pos_model->getRegisterTaxSales($value->date, null, $value->user_id);
                $cashier_tipsales = $this->Pos_model->getRegisterTipSales($value->date, $value->user_id);
                $cashier_totalsales = $this->Pos_model->getRegisterSales($value->date, $value->user_id);

                if(empty($taxsales) && empty($tipsales) && empty($totalsales)){
                    $taxsales = $cashier_taxsales;
                    $tipsales = $cashier_tipsales;
                    $totalsales = $cashier_totalsales;
                }else{
                    $taxsales->tax += $cashier_taxsales->tax;
                    $tipsales->tip += $cashier_tipsales->tip;
                    $totalsales->total += $cashier_totalsales->total;
                    $totalsales->paid += $cashier_totalsales->paid;
                }
            }
        }
        
        if(empty($taxsales) && empty($totalsales)){
            $taxsales = (object)[];
            $tipsales = (object)[];
            $totalsales = (object)[];

            $taxsales->tax = 0;
            $tipsales->tip = 0;
            $totalsales->total = 0;
            $totalsales->paid = 0;
            
        }
        
        $this->data['taxsales'] = $taxsales;
        $this->data['tipsales'] = $tipsales;
        $this->data['totalsales'] = $totalsales;
        
        //suspend sales total
        $this->data['suspended_total'] = $suspend_sales_total;
        
        // load template files
        $this->load->view("restaurant/views/header", $this->data);
        $this->load->view("restaurant/views/nav");
        $this->load->view("restaurant/views/tables/index");
        $this->load->view("restaurant/views/footer");
    }


    function order($action = null , $table = null)
    {

        if (!$action || !$table){
            redirect("tables");
        }
        
        list($flag, $bill_id) = $this->restaurant->getUriOrderTable($table);
        
        if($flag && $bill_id == 0){
            redirect("tables");
        }
        
        // Load table information
        $this->restaurant->select_table($table);
        
        $template = $action;
        
        switch ($action){
            case 'edit' :
                
                    $this->sma->checkPermissions('edit', null, 'sales');

                    // read notifications table
                    $this->Pos_model->readNotify(null,$table);
                
                    // Checking if the user has enough privileges
                    if ($this->restaurant->table->waiter != $this->session->userdata('user_id')) {
                        if(!$this->sma->is_admin() && !$this->sma->is_cashier() && !$this->sma->is_product_admin()){
                            redirect("tables");
                        }
                    }

                    // add customize js
                    add_js('templates/generate-order-actions.js?ver=' . date('Y-m-d'));

                    // set data to send in the view
                    $this->data['title'] = lang('title_edit_order');
                    $this->data['info_table'] = $table;
                    $this->data['table'] = $this->restaurant->table;
                    $this->data['nav'] = array(
                        'active' => 'home',
                        'title_top' => lang('subtitle_categories'),
                        'enable_search' => false
                    );

                    // if get category id by post set nav
                    $category_view = filter_input(INPUT_POST,'category_id',FILTER_SANITIZE_NUMBER_INT);
                    if ($category_view){
                        $this->data['js_nav_category'] = $category_view;
                    }

                    // Get waiter id's
                    $this->data['waiters'] = $this->restaurant->getWaitersIDS();

                    // Load order information
                    $this->restaurant->select_order();

                    $this->data['order'] = $this->restaurant->order;

                    $this->data['biller'] = $this->pos_model->getCompanyByID($this->restaurant->order->biller_id);

                    // set waiter name local storage
                    $this->data['waiter_name'] = $this->site->getUser($this->restaurant->order->id_waiter)->first_name . " " . $this->site->getUser($this->restaurant->order->id_waiter)->last_name;

                    $this->data['pos_settings'] = $this->restaurant->pos_settings;
                    $this->data['products'] = $this->restaurant->getOrderItems();
                    
                    // Get main and sub categories to display
                    $this->data['categories'] = $this->restaurant->getParentCategories();
                    $this->data['subcategories'] = $this->restaurant->getSubCategoriesByParents(array_column($this->data['categories'],'id'));
                    
                    // Info require to top 10 products
                    foreach ($this->data['subcategories'] as $key => $value) {
                        if (empty($value)) {
                            $this->data['subcategories'][$key] = array('id' => $this->data['categories'][$key]['id'], 'code' => $this->data['categories'][$key]['code']);
                        }
                    }
                    
                    //top 10 products
                    $y1 = date('Y', strtotime('-1 month'));
                    $m1 = date('m', strtotime('-1 month'));
                    $m1sdate = $y1.'-'.$m1.'-01 00:00:00';
                    $m1edate = date("Y-m-d H:i:s");
                    $this->data['m1'] = date('M Y', strtotime($y1.'-'.$m1));
                    $topProducts = $this->reports_model->getBestSeller($m1sdate, $m1edate, null, 50);
                    
                    //categories required in top list
                    foreach ($this->data['subcategories'] as $key => $value) {
                        if($value){
                            foreach ($value as $key_subcategory => $value_subcategory) {
                                if(isset($value_subcategory['top_list'])){
                                    if ($value_subcategory['top_list'] == true && $value_subcategory['status'] == true) {
                                        $top_list_subcategories[$value_subcategory['id']] = $value_subcategory;
                                    }
                                }
                            }
                        }
                    }
                    
                    //only products with the categories in the toplist_subcategories
                    $counter_top = 0;
                    
                    if(!empty($topProducts)) {
                        foreach ($topProducts as $key => $value) {
                            if($counter_top < 12){
                                $product = $this->products_model->getProductByCode($value->product_code);

                                if(array_key_exists($product->subcategory_id, $top_list_subcategories)){
                                    $product->options = $this->Products_model->getProductOptions($product->id);
                                    $top_final_products[] = $product;
                                    $counter_top++;
                                }

                            }
                        }
                    }
                    $this->data['top_10_products'] = !empty($top_final_products) ? $top_final_products : NULL;
                    
                    break;
            
            case 'create' :
                
                    $this->sma->checkPermissions('add', null, 'sales');
                
                    // Table validation 
                    if ($this->restaurant->table->available == false ){
                        redirect("tables");
                    }
                    
                    // set data to send in the view
                    $this->data['title'] = lang('title_new_order');
                    $this->data['table'] = $this->restaurant->table;
                    $this->data['info_table'] = $table;
                    $this->data['nav'] = array(
                        'active' => 'home',
                        'title_top' => lang('title_new_order'),
                        'enable_search' => false
                    );
                    
                    
                    // Create order
                    if ($this->input->post()){
                        
                        if (!$this->form_validation->run('tables/neworder')) {
                             $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                        }else{
                            $table_flag = $this->restaurant->get_table($this->restaurant->table->id);
                            if($table_flag->bill == 0){
                                // set table data
                                $this->restaurant->table->guests = $this->input->post('guests');
                                $this->restaurant->table->waiter = ($this->sma->is_waiter()) ? $this->session->userdata('user_id') : $this->input->post('waiter');
                                $this->restaurant->table->comments = filter_var($this->input->post('comments'),FILTER_SANITIZE_STRING);

                                // Update table status
                                $this->restaurant->reserve_table();

                                // Redirect to edit order created
                                redirect("tables/order/edit/{$this->restaurant->table->id}");
                            }else{
                                redirect("tables/order/edit/{$this->restaurant->table->id}");
                            }
                        }
                    }
                break;

            case 'confirm' :
                
                $this->sma->checkPermissions('edit', null, 'sales');

                // Checking if the user has enough privileges
                if ($this->restaurant->table->waiter != $this->session->userdata('user_id')) {
                    if(!$this->sma->is_admin() && !$this->sma->is_cashier() && !$this->sma->is_product_admin()){
                        redirect("tables");
                    }
                }
                
                // Load order information
                $this->restaurant->select_order();
                
                $this->restaurant->confirmOrderItems();
                
                // Redirect to edit order created
                redirect("tables/order/edit/{$this->restaurant->table->id}");


                break;
            
            case 'close' :
                
                // Checking order status
                if($this->restaurant->table->status == 1 || $this->restaurant->table->status == 2){
                    
                    // Checking if the user has enough privileges
                    if($this->sma->is_admin() || $this->sma->is_cashier() || $this->restaurant->table->waiter == $this->session->userdata('user_id') || $this->sma->is_product_admin()){
                        
                        // Update table status
                        $this->restaurant->free_table();
                    }
                    
                }
                
                redirect("tables");

                break;
               
            case 'bill' :
                
                // Checking order status
                if($this->restaurant->table->status == 1 || $this->restaurant->table->status == 2){
                    
                    // Checking if the user has enough privileges
                    if($this->sma->is_admin() || $this->sma->is_cashier() || $this->restaurant->table->waiter == $this->session->userdata('user_id') || $this->sma->is_product_admin()){
                        
                        // Update table status
                        $this->restaurant->bill_table();
                    }
                }
                
                redirect("tables/order/edit/{$this->restaurant->table->id}");
                break;

            case 'change_waiter' :

                if($this->input->post('waiter')){
                    $this->restaurant->change_waiter($this->input->post('waiter'));
                }
                redirect("tables/order/edit/{$this->restaurant->table->id}");

                break;
            
            case 'change_table' :

                if($this->input->post('table')){
                    $this->restaurant->changeTable($this->input->post('table'));
                }
                redirect("tables/order/edit/{$this->input->post('table')}");

                break;
                
            default:
                redirect("tables");
                break;
        }
        
        // load template files
        $this->load->view("restaurant/views/header", $this->data);
        $this->load->view("restaurant/views/nav");
        $this->load->view("restaurant/views/tables/{$template}");
        $this->load->view("restaurant/views/footer");
        
        
    }
    
    
    function category($action = null, $category = null, $table = null){
        
       if (!$action || !$category || !$table){
            redirect("tables");
        }
        

        // Load table information
        $this->restaurant->select_table($table); 
        
        switch ($action) {
            case 'view':

               
                // set data to send in the view
                $this->data['title'] = lang('title_view_category');
                $this->data['table'] = $this->restaurant->table;
                $this->data['info_table'] = $table;
                $this->data['nav'] = array(
                    'active' => 'home',
                    'title_top' => lang('title_new_order'),
                    'enable_search' => false
                );
                $this->data['parent_category'] = true;

                // Get main and sub categories to display
                $this->data['categories_parents'] = $this->restaurant->getParentCategories();
                $this->data['category'] = $this->restaurant->getCategoryDetails($category);
                $this->data['categories'] = ($this->data['category']->parent_id > 0) ?  $this->restaurant->getSubCategoriesByParent($this->data['category']->parent_id) : array();
                $this->data['products'] = $this->restaurant->getCategoryProducts($category);

                // use function compare to sort asc products name
                // function compare there helpers file
                usort($this->data['products'], "compare");
                
                $template = "category-products";
                
                break;

            default:
                break;
        }
        
        
        // load template files
        $this->load->view("restaurant/views/header", $this->data);
        $this->load->view("restaurant/views/nav");
        $this->load->view("restaurant/views/tables/{$template}");
        $this->load->view("restaurant/views/footer");
        
    }
    
    
    function product($action = null, $product = null, $table = null){
        
        if (!$action || !$product || !$table){
            redirect("tables");
        }
        
        // Load table information
        $this->restaurant->select_table($table);

        switch ($action) {
            case 'view':

                // set data to send in the view
                $this->data['title'] = lang('title_view_category');
                $this->data['table'] = $this->restaurant->table;
                $this->data['info_table'] = $table;
                $this->data['nav'] = array(
                    'active' => 'home',
                    'title_top' => lang('title_new_order'),
                    'enable_search' => false
                );

                $this->data['product'] = $this->restaurant->getProductByID($product);
                $this->data['parent_category'] = true;
                $this->data['category'] = (object) array(
                    'parent_id' => $this->data['product']->category_id
                );

                // Get main and sub categories to display
                $this->data['categories_parents'] = $this->restaurant->getParentCategories();
                $this->data['categories'] = ($this->data['product']->category_id > 0) ?  $this->restaurant->getSubCategoriesByParent($this->data['product']->category_id) : array();
                
                // add customize js
                add_js('templates/product.js');
                $template = "product-view";
                
                break;
            
            case 'add2order' :
                
                $this->sma->checkPermissions('add', null, 'sales');
                
                $this->data['product'] = $this->restaurant->getProductByID($product);

                
                $this->restaurant->product->id = $product;
                $this->restaurant->product->qty = $this->input->post('qty');
                $this->restaurant->product->comments = $this->input->post('comments');
                $this->restaurant->product->option_id = $this->input->post('product_option');
                
                if($this->restaurant->product2order()){
                    $this->restaurant->billCounter();
                    redirect("tables/order/edit/{$table}");
                }else{
                    redirect("tables/product/view/{$product}/{$table}");
                }
                
                break;
                
            case 'remove' :
                
                $this->sma->checkPermissions('edit', null, 'sales');
                
                // Remove item from table
                $this->restaurant->removeProductOrder($product);
                
                // Update order count
                $this->restaurant->billCounter();
                
                // Redirec to to table view
                redirect("tables/order/edit/{$table}");
                
                break;

            default:
                break;
        }
        
        
        // load template files
        $this->load->view("restaurant/views/header", $this->data);
        $this->load->view("restaurant/views/nav");
        $this->load->view("restaurant/views/tables/{$template}");
        $this->load->view("restaurant/views/footer");
        
    }
    
    
    public function customer($action = null, $table = null)
    {
        
        if (!$action || !$table){
            redirect("tables");
        }
        
        $this->sma->checkPermissions('edit', null, 'sales');
        
        // Load table information
        $this->restaurant->select_table($table);
        
        // Load order information
        $this->restaurant->select_order();
        $this->data['order'] = $this->restaurant->order;
        
        // Checking if the user has enough privileges
        if ($this->restaurant->table->waiter != $this->session->userdata('user_id')) {
            if(!$this->sma->is_admin() && !$this->sma->is_cashier() && !$this->sma->is_product_admin()){
                redirect("tables");
            }
        }
        
       
        switch ($action) {
            case 'add':
                
                 // set data to send in the view
                $this->data['title'] = lang('title_edit_order');
                $this->data['info_table'] = $table;
                $this->data['table'] = $this->restaurant->table;
                $this->data['nav'] = array(
                    'active' => 'home',
                    'title_top' => lang('subtitle_categories'),
                    'enable_search' => false
                );

                $template = "customer";

                // Get main and sub categories to display
                $this->data['categories'] = $this->restaurant->getParentCategories();
                $this->data['subcategories'] = $this->restaurant->getSubCategoriesByParents(array_column($this->data['categories'],'id'));
                
                break;
            case 'load':
                
                if($this->input->post('customer')){
                    $this->restaurant->customer2Order($this->input->post('customer'));
                }
                redirect("tables/order/edit/{$this->restaurant->table->id}");

                break;
                
            case 'create':
                
                // Create customer
                if ($this->input->post()){

                    // Form validation
                    if (!$this->form_validation->run('tables/newcustomer')) {
                         $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                         $this->session->set_flashdata('message', $this->data['error']);
                         redirect("tables/customer/create/{$this->restaurant->table->id}");
                    }else{
                        
                        $name = $this->input->post('cname');
                        $email = $this->input->post('email');
                        $phone = $this->input->post('phone');
                        $address = $this->input->post('address');
                        
                        $customer = $this->restaurant->addCustomer($name,$email,$phone,$address);
                        $this->restaurant->customer2Order($customer);
                        // Redirect to edit order created
                        redirect("tables/order/edit/{$this->restaurant->table->id}");
                    }
                }else{
                    redirect("tables");
                }

                break;

            default:
                redirect("tables");
                break;
        }
                    
                    
         // load template files
        $this->load->view("restaurant/views/header", $this->data);
        $this->load->view("restaurant/views/nav");
        $this->load->view("restaurant/views/tables/{$template}");
        $this->load->view("restaurant/views/footer");
                    
        
    }

    public function notifications($action = null, $id_item = null, $id_table)
    {

        if (!$action){
            redirect("tables");
        }

        switch ($action) {
            case 'view':

                // set data to send in the view
                $this->data['title'] = lang('notifications');
                $this->data['nav'] = array(
                    'active' => 'notify',
                    'enable_search' => false
                );

                foreach ($this->Pos_model->getNotifications($this->session->userdata('user_id')) as $notify){
                    $table = $this->restaurant->get_table($notify->product_table);

                    $notify->table_name = $table->name;

                    $this->data['notifications'][] = $notify;

                }

                // load template files
                $this->load->view("restaurant/views/header", $this->data);
                $this->load->view("restaurant/views/nav", $this->data);
                $this->load->view("restaurant/views/tables/notifications");
                $this->load->view("restaurant/views/footer");

                break;

            case 'read':

                $id_item= filter_var($id_item, FILTER_SANITIZE_NUMBER_INT);

                // read notify and redirect to order
                if($this->Pos_model->readNotify($id_item)) {
                    redirect("tables/order/edit/{$id_table}");
                }

                break;

            default:
                redirect("tables");
                break;
        }

    }

    function ajax($request = null, $param1 = null, $param2 = null){
        if (!$this->input->is_ajax_request()) {
            // No direct script access allowed
            redirect("tables");
        }

        switch ($request) {
            case "search_product" :

                // get search text
                $param1 = str_replace('%20', ' ', $param1);
                $param1 = str_replace('-n-', '#', $param1);
                $param1 = str_replace('%C3%B1', 'ñ', $param1);
                $param1 = str_replace('-pa-', '(', $param1);
                $param1 = str_replace('-pc-', ')', $param1);
                $param1 = str_replace('-y-', '&', $param1);
                
                $text = filter_var($param1, FILTER_SANITIZE_STRING);

                $list_products = $this->products_model->getProductsByText($text);

                // set symbol and format price
                if($list_products){
                    foreach ($list_products as $key => $product){
                        if ($this->Settings->status_premium_price && !empty($product->premium_price)) {
                            $product->price = $product->premium_price;
                        }
                        if ($product->promotion) {
                            $product->price = $product->promo_price;
                        }

                        $product->unit_price = $product->price;
                        $this->sma->sum_product_tax($product->tax_rate, $product, $product->unit_price, $product->price);

                        $list_products[$key]->final_price = $this->Settings->symbol . $this->sma->formatDecimal($product->price);
                    }
                }

                echo json_encode($list_products);

                break;

            case "notifications" :

                $notifications = $this->Pos_model->getNotifications($this->session->userdata('user_id'));

                echo count($notifications);

                break;
            
            case "show_products" :
                $this->db->cache_delete_all();
                $products = $this->restaurant->getCategoryProducts($param1);
                
                foreach ($products as $product) {
                    
                    if ($this->Settings->status_premium_price && !empty($product->premium_price)) {
                        $product->price = $product->premium_price;
                    }
                    if ($product->promotion) {
                        $product->price = $product->promo_price;
                    }
                    
                    $product->unit_price = $product->price;
                    $this->sma->sum_product_tax($product->tax_rate, $product, $product->unit_price, $product->price);
                    
                    $product->price = $this->sma->formatDecimal($product->price);
                    $product->symbol = $this->Settings->symbol;
                    $product->lang_product_unavailable = lang("product-unavailable");
                    $product->lang_unavailable = lang("unavailable");
                    $product->options = $this->Products_model->getProductOptions($product->id);
                }
                usort($products, "compare");
                echo json_encode($products);
                
                break;
                
            case "add_product" :
                
                $this->restaurant->select_table($param2);
                
                $this->sma->checkPermissions('add', null, 'sales');
                
                $waiters = $this->restaurant->getWaitersIDS();
                
                $this->restaurant->product->id = $param1;
                $this->restaurant->product->qty = 1;
                $this->restaurant->product->comments = '';
                $this->restaurant->product->option_id = null;
                
                if($this->restaurant->product2order()){
                    $insert_id = $this->db->insert_id();
                    $product = $this->Products_model->getSuspendedItem($insert_id);
                    $this->restaurant->billCounter();
                    
                    $product->in_array = (in_array($product->product_waiter,$waiters)) ? true : false;
                    $product->symbol = $this->Settings->symbol;
                    $product->quantity = intval($product->quantity);
                    $product->unit_price = $this->sma->formatDecimal($product->unit_price);
                    $product->subtotal = $this->sma->formatDecimal($product->subtotal);
                    $product->table = $param2;
                    $product->text_confirm = lang('confirm');
                    
                    echo json_encode($product);
                }

                break;

            default :
                break;
        }
    }
    
}
