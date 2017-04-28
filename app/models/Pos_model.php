<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pos_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function getSetting()
    {
        $q = $this->db->get('pos_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateSetting($data)
    {
        $this->db->where('pos_id', '1');
        if ($this->db->update('pos_settings', $data)) {
            return true;
        }
        return false;
    }

    public function products_count($category_id, $subcategory_id = NULL, $brand_id = NULL)
    {
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        if ($brand_id) {
            $this->db->where('brand', $brand_id);
        }
        $this->db->from('products');
        return $this->db->count_all_results();
    }

    public function fetch_products($category_id, $limit, $start, $subcategory_id = NULL, $brand_id = NULL)
    {
        $this->db->limit($limit, $start);
        if ($brand_id) {
            $this->db->where('brand', $brand_id);
        } elseif ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        $this->db->order_by("name", "asc");
        $query = $this->db->get("products");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function registerData($user_id)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('pos_register', array('user_id' => $user_id, 'status' => 'open'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function openRegister($data)
    {
        if ($this->db->insert('pos_register', $data)) {
            $this->autoUpdateWeekendProducts();
            return true;
        }
        return FALSE;
    }

    public function getOpenRegisters()
    {
        $this->db->select("date, user_id, cash_in_hand, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name, ' - ', " . $this->db->dbprefix('users') . ".email) as user", FALSE)
            ->join('users', 'users.id=pos_register.user_id', 'left');
        $q = $this->db->get_where('pos_register', array('status' => 'open'));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function closeRegister($rid, $user_id, $data)
    {
        if (!$rid) {
            $rid = $this->session->userdata('register_id');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        if ($data['transfer_opened_bills'] == -1) {
            $this->db->delete('suspended_bills', array('created_by' => $user_id));
        } elseif ($data['transfer_opened_bills'] != 0) {
            $this->db->update('suspended_bills', array('created_by' => $data['transfer_opened_bills']), array('created_by' => $user_id));
        }
        if ($this->db->update('pos_register', $data, array('id' => $rid, 'user_id' => $user_id))) {
            return true;
        }
        return FALSE;
    }

    public function getUsers()
    {
        $q = $this->db->get_where('users', array('company_id' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductsByCode($code)
    {
        $this->db->like('code', $code, 'both')->order_by("code");
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getWHProduct($code, $warehouse_id)
    {
        $this->db->select('products.*, categories.id as category_id, categories.name as category_name')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->join('categories', 'categories.id=products.category_id', 'left')
            ->group_by('products.id');
        $q = $this->db->get_where("products", array('products.code' => $code));
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getProductOptions($product_id, $warehouse_id)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
            ->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
            if(! $this->Settings->overselling) {
                $this->db->where('warehouses_products_variants.quantity >', 0);
            }
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductComboItems($pid, $warehouse_id)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, products.type as type, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->where('warehouses_products.warehouse_id', $warehouse_id)
            ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }
    
    public function updateProductStatus($bill = null, $status = 'pending', $item = null){
        if ($status) {
            
            if($item){
                $this->db->where('id',$item);
            }
            
            if($bill){
                $this->db->where('suspend_id',$bill);
            }
            
            $data = array('product_status' => $status);
            
            if ($status == "confirmed"){
                $this->db->where('date_confirmed IS NULL');
                $data['date_confirmed'] = date("Y-m-d H:i:s");
            }
            
            if ($status == "dispatched"){
                 $this->db->where('date_dispatched IS NULL');
                $data['date_dispatched'] = date("Y-m-d H:i:s");
            }
            
            if($this->db->update('suspended_items', $data)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function updateOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity + $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getProductOptionByID($id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        } else {
            $nq = 0 - $quantity;
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $nq))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }
    
    
    // Remove a product from suspented list given the id record
    public function removeSuspendItem($id) {
        if ($id){
            $this->db->where('id', $id);
            return $this->db->delete('suspended_items'); 
        }
    }

    public function updateFixedTax($value)
    {
        if ($value) {
            $this->db->where('name', "Fijo");
        }

        $data = array('rate' => $value);

        if($this->db->update('tax_rates', $data)) {
            return TRUE;
        }
    }
    
    public function updateFixedTip($value)
    {
        if ($value) {
            $this->db->where('name', "Fijo");
        }

        $data = array('rate' => $value);

        if($this->db->update('tip_rates', $data)) {
            return TRUE;
        }
    }

    public function addSale($data = array(), $items = array(), $payments = array(), $sid = NULL)
    { 
        
        $cost = $this->site->costing($items);
        // $this->sma->print_arrays($cost);
        
        foreach($items as $key => $item){
            unset($items[$key]['id']);
            unset($items[$key]['product_category']);
        }
        
        if ($this->db->insert('sales', $data)) {
            $sale_id = $this->db->insert_id();


            if($data['customer_id'] != 11){
                $this->site->updateReference('pos');
            }

            foreach ($items as $item) {

                $item['sale_id'] = $sale_id;
                $this->db->insert('sale_items', $item);
                $sale_item_id = $this->db->insert_id();
                if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {

                    $item_costs = $this->site->item_costing($item);
                    foreach ($item_costs as $item_cost) {
                        if (isset($item_cost['date'])) {
                            $item_cost['sale_item_id'] = $sale_item_id;
                            $item_cost['sale_id'] = $sale_id;
                            $item_cost['date'] = date('Y-m-d', strtotime($data['date']));
                            if(! isset($item_cost['pi_overselling'])) {
                                $this->db->insert('costing', $item_cost);
                            }
                        } else {
                            foreach ($item_cost as $ic) {
                                $ic['sale_item_id'] = $sale_item_id;
                                $ic['sale_id'] = $sale_id;
                                $ic['date'] = date('Y-m-d', strtotime($data['date']));
                                if(! isset($ic['pi_overselling'])) {
                                    $this->db->insert('costing', $ic);
                                }
                            }
                        }
                    }
                }
            }
            
            if ($data['sale_status'] == 'completed') {
                $this->site->syncPurchaseItems($cost);
            }

            $msg = array();
            if (!empty($payments)) {
                $paid = 0;
                foreach ($payments as $payment) {
                    if (!empty($payment) && isset($payment['amount']) ) {
                        $payment['sale_id'] = $sale_id;
                        $payment['reference_no'] = $this->site->getReference('pay');
                        if ($payment['paid_by'] == 'ppp') {
                            $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                            $result = $this->paypal($payment['amount'], $card_info);
                            if (!isset($result['error'])) {
                                $payment['transaction_id'] = $result['transaction_id'];
                                $payment['date'] = $this->sma->fld($result['created_at']);
                                $payment['amount'] = $result['amount'];
                                $payment['currency'] = $result['currency'];
                                unset($payment['cc_cvv2']);
                                $this->db->insert('payments', $payment);
                                $this->site->updateReference('pay');
                                $paid += $payment['amount'];
                            } else {
                                $msg[] = lang('payment_failed');
                                if (!empty($result['message'])) {
                                    foreach ($result['message'] as $m) {
                                        $msg[] = '<p class="text-danger">' . $m['L_ERRORCODE'] . ': ' . $m['L_LONGMESSAGE'] . '</p>';
                                    }
                                } else {
                                    $msg[] = lang('paypal_empty_error');
                                }
                            }
                        } elseif ($payment['paid_by'] == 'stripe') {
                            $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                            $result = $this->stripe($payment['amount'], $card_info);
                            if (!isset($result['error'])) {
                                $payment['transaction_id'] = $result['transaction_id'];
                                $payment['date'] = $this->sma->fld($result['created_at']);
                                $payment['amount'] = $result['amount'];
                                $payment['currency'] = $result['currency'];
                                unset($payment['cc_cvv2']);
                                $this->db->insert('payments', $payment);
                                $this->site->updateReference('pay');
                                $paid += $payment['amount'];
                            } else {
                                $msg[] = lang('payment_failed');
                                $msg[] = '<p class="text-danger">' . $result['code'] . ': ' . $result['message'] . '</p>';
                            }
                        } elseif ($payment['paid_by'] == 'authorize') {
                            $authorize_arr = array("x_card_num" => $payment['cc_no'], "x_exp_date" => ($payment['cc_month'].'/'.$payment['cc_year']), "x_card_code" => $payment['cc_cvv2'], 'x_amount' => $payment['amount'], 'x_invoice_num' => $sale_id, 'x_description' => 'Sale Ref '.$data['reference_no'].' and Payment Ref '.$payment['reference_no']);
                            list($first_name, $last_name) = explode(' ', $payment['cc_holder'], 2);
                            $authorize_arr['x_first_name'] = $first_name;
                            $authorize_arr['x_last_name'] = $last_name;
                            $result = $this->authorize($authorize_arr);
                            if (!isset($result['error'])) {
                                $payment['transaction_id'] = $result['transaction_id'];
                                $payment['approval_code'] = $result['approval_code'];
                                $payment['date'] = $this->sma->fld($result['created_at']);
                                unset($payment['cc_cvv2']);
                                $this->db->insert('payments', $payment);
                                $this->site->updateReference('pay');
                                $paid += $payment['amount'];
                            } else {
                                $msg[] = lang('payment_failed');
                                $msg[] = '<p class="text-danger">' . $result['msg'] . '</p>';
                            }
                        } else {
                            if ($payment['paid_by'] == 'gift_card') {
                                $this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
                                unset($payment['gc_balance']);
                            } elseif ($payment['paid_by'] == 'deposit') {
                                $customer = $this->site->getCompanyByID($data['customer_id']);
                                $this->db->update('companies', array('deposit_amount' => ($customer->deposit_amount-$payment['amount'])), array('id' => $customer->id));
                            }
                            unset($payment['cc_cvv2']);
                            $this->db->insert('payments', $payment);
                            $this->site->updateReference('pay');
                            $paid += $payment['amount'];
                        }
                    }
                }
                $this->site->syncSalePayments($sale_id);
            }
            
            $this->deleteBill($sid);

            $this->site->syncQuantity($sale_id);
            $this->sma->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by']);
            return array('sale_id' => $sale_id, 'message' => $msg);

        }

        return false;
    }

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductByName($name)
    {
        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getProductsByStatus($status = 'pending', $id = null,$limit = 10000,$order = null, $order_type = 'ASC',$category = null)
    {
        
        if($id){
            $this->db->where('suspend_id',$id);
        }
        
        if($category){
            $this->db->where('product_category',$category);
        }

        if($order){
            $this->db->order_by($order,$order_type);
        }
        
        $this->db->where('product_status', $status);
        
        $q = $this->db->get('suspended_items',$limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    /**
     * @param $id_item Id suspended item
     * @param $id_table Id table
     * @return bool action update
     */
    public function readNotify($id_item = null, $id_table = null){

        if ($id_table) {
            $this->db->where('product_table', $id_table);
            $this->db->where('product_status', 'dispatched');
        } else {
            $this->db->where('id', $id_item);
        }

        $data = array('read_notify' => 1);

        if($this->db->update('suspended_items', $data)) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @param null $id_waiter id waiter to condition
     * @return array list suspended items to notify
     */
    public function getNotifications($id_waiter = null)
    {

        if($id_waiter){
            $this->db->where('product_waiter',$id_waiter);
        }

        $this->db->where('product_status', 'dispatched');
        $this->db->where('read_notify', 0);

        $q = $this->db->get('suspended_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllBillerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'biller'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllCustomerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'customer'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getCompanyByID($id)
    {

        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllProducts()
    {
        $q = $this->db->query('SELECT * FROM products ORDER BY id');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getProductByID($id)
    {

        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllTaxRates()
    {
        $q = $this->db->get('tax_rates');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getTaxRateByID($id)
    {

        $q = $this->db->get_where('tax_rates', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function updateProductQuantity($product_id, $warehouse_id, $quantity)
    {

        if ($this->addQuantity($product_id, $warehouse_id, $quantity)) {
            return true;
        }

        return false;
    }

    public function addQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id)) {
            $new_quantity = $warehouse_quantity['quantity'] - $quantity;
            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                $this->site->syncProductQty($product_id, $warehouse_id);
                return TRUE;
            }
        } else {
            if ($this->insertQuantity($product_id, $warehouse_id, -$quantity)) {
                $this->site->syncProductQty($product_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->update('warehouses_products', array('quantity' => $quantity), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            return true;
        }
        return false;
    }

    public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }

    public function getItemByID($id)
    {
        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllSales()
    {
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function sales_count()
    {
        return $this->db->count_all("sales");
    }

    public function fetch_sales($limit, $start)
    {
        $this->db->limit($limit, $start);
        $this->db->order_by("id", "desc");
        $query = $this->db->get("sales");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllInvoiceItems($sale_id)
    {
        if ($this->pos_settings->item_order == 0) {
            $this->db->select('sale_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, product_variants.name as variant, products.details as details')
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        } elseif ($this->pos_settings->item_order == 1) {
            $this->db->select('sale_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, product_variants.name as variant, categories.id as category_id, categories.name as category_name, products.details as details')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('categories', 'categories.id=products.category_id', 'left')
            ->group_by('sale_items.id')
            ->order_by('categories.id', 'asc');
        }
        
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSuspendedSaleItems($id, $ARRAY_A = false)
    {
        $q = $this->db->get_where('suspended_items', array('suspend_id' => $id));
        if ($q->num_rows() > 0) {
            
            if (!$ARRAY_A){
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            }else{
                foreach (($q->result_array()) as $row) {
                    $data[$row['id']] = $row;
                }
            }

            return $data;
        }
    }

    public function getSuspendedSales($user_id = NULL)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        
        $q = $this->db->get_where('suspended_bills', array('created_by' => $user_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllSuspendedSales()
    {

        $q = $this->db->get('suspended_bills');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }


    public function getOpenBillByID($id)
    {

        $q = $this->db->get_where('suspended_bills', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getInvoiceByID($id)
    {

        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function bills_count()
    {
        if (!$this->Owner && !$this->Admin && !$this->sma->is_cashier()) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        return $this->db->count_all_results("suspended_bills");
    }

    public function fetch_bills($limit, $start, $all = false,$orderBy = "id")
    {
        if (!$this->Owner && !$this->Admin && !$all) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->limit($limit, $start);
        $this->db->order_by($orderBy, "asc");
        $query = $this->db->get("suspended_bills");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getTodaySales()
    {
        $sdate = date('Y-m-d 00:00:00');
        $edate = date('Y-m-d 23:59:59');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('sales.date >=', $sdate)->where('payments.date <=', $edate);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getCosting()
    {
        $date = date('Y-m-d');
        $this->db->select('SUM( COALESCE( purchase_unit_cost, 0 ) * quantity ) AS cost, SUM( COALESCE( sale_unit_price, 0 ) * quantity ) AS sales, SUM( COALESCE( purchase_net_unit_cost, 0 ) * quantity ) AS net_cost, SUM( COALESCE( sale_net_unit_price, 0 ) * quantity ) AS net_sales', FALSE)
            ->where('date', $date);

        $q = $this->db->get('costing');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCCSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cc_slips, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'CC');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCashSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'cash');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayRefunds()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('sales', 'sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayExpenses()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
            ->where('date >', $date);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCashRefunds()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('sales', 'sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date)->where('paid_by', 'cash');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayChSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'Cheque');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayPPPSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'ppp');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayStripeSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'stripe');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayAuthorizeSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'authorize');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterSales($date, $user_id = NULL, $date_end = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received');

        if ($date_end == null) {
            // register open time
            $this->db->where('payments.date >', $date);
        } else {
            // filter dates
            $this->db->where('payments.date >', $date);
            $this->db->where('payments.date <', $date_end);
        }

        if ($date_end == null) {
            $this->db->where('payments.created_by', $user_id);
        }

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }


    public function getRegisterCCSales($date, $user_id = NULL, $date_end = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cc_slips, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid, order_tax', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('paid_by', 'CC');

        if ($date_end == null) {
            // register open time
            $this->db->where('payments.date >', $date);
        } else {
            // filter dates
            $this->db->where('payments.date >', $date);
            $this->db->where('payments.date <', $date_end);
        }

        if ($date_end == null) {
            $this->db->where('payments.created_by', $user_id);
        }

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterReferences($date, $user_id = NULL, $date_end = NULL)
    {

        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }

        $this->db->select('sales.reference_no, sales.id, payments.paid_by, payments.amount, sales.total_discount')
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')
            ->order_by("sales.reference_no", "ASC");

        if ($date_end == null) {
            // register open time
            $this->db->where('payments.date >', $date);
        } else {
            // filter dates
            $this->db->where('payments.date >', $date);
            $this->db->where('payments.date <', $date_end);
        }

        if ($date_end == null) {
            $this->db->where('payments.created_by', $user_id);
        }

        $q = $this->db->get('payments');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getRegisterCashSales($date, $user_id = NULL, $date_end = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid, order_tax', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('paid_by', 'cash');

        if ($date_end == null) {
            // register open time
            $this->db->where('payments.date >', $date);
        } else {
            // filter dates
            $this->db->where('payments.date >', $date);
            $this->db->where('payments.date <', $date_end);
        }

        if ($date_end == null) {
            $this->db->where('payments.created_by', $user_id);
        }

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterTaxSales($date, $type = NULL, $user_id = NULL, $date_end = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        if(!$type){
            $types = array('cash', 'CC');
        }

        $this->db->select('order_tax, paid_by', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received');

        if ($date_end == null) {
            // register open time
            $this->db->where('payments.date >', $date);
        } else {
            // filter dates
            $this->db->where('payments.date >', $date);
            $this->db->where('payments.date <', $date_end);
        }

        if ($date_end == null) {
            $this->db->where('payments.created_by', $user_id);
        }
        
        $this->db->group_by("sma_sales.id");
        
        $q = $this->db->get('payments');
        
        $last_query = $this->db->last_query();
        $this->db->select('SUM( COALESCE( order_tax, 0 ) ) AS tax', FALSE)
                ->from('(' . $last_query . ') as q');
        
        if(!$type){
            $this->db->where_in('paid_by', $types);
        }else{
            $this->db->where('paid_by', $type);
        }
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    
    public function getRegisterTipSales($date, $user_id = NULL, $date_end = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }

        $this->db->select('SUM( COALESCE( order_tip, 0 ) ) AS tip', FALSE);

        if ($date_end == null) {
            // register open time
            $this->db->where('date >', $date);
        } else {
            // filter dates
            $this->db->where('date >', $date);
            $this->db->where('date <', $date_end);
        }

        if ($date_end == null) {
            $this->db->where('created_by', $user_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    
    public function getRegisterDiscountSales($date, $user_id = NULL, $date_end = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }

        $types = array('cash', 'CC');
        $this->db->select('order_discount', FALSE)
            ->from('payments')
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where_in('paid_by', $types);

        if ($date_end == null) {
            // register open time
            $this->db->where('payments.date >', $date);
        } else {
            // filter dates
            $this->db->where('payments.date >', $date);
            $this->db->where('payments.date <', $date_end);
        }

        if ($date_end == null) {
            $this->db->where('payments.created_by', $user_id);
        }
        
        $this->db->group_by("sma_sales.id");
        
        $q = $this->db->get();
        
        $last_query = $this->db->last_query();
        $this->db->select('SUM( COALESCE( order_discount, 0 ) ) AS discount', FALSE)
                ->from('(' . $last_query . ') as q');
        
        $q = $this->db->get();    
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterRefunds($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('sales', 'sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date);
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterCashRefunds($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('sales', 'sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date)->where('paid_by', 'cash');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterExpenses($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
            ->where('date >', $date);
        $this->db->where('created_by', $user_id);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterChSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'Cheque');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterPPPSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'ppp');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterStripeSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'stripe');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterAuthorizeSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'authorize');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function suspendSale($data = array(), $items = array(), $did = NULL)
    {   
        $sData = array(
            'id_table' => $data['id_table'],
            'id_waiter' => $data['id_waiter'],
            'count' => $data['total_items'],
            'biller_id' => $data['biller_id'],
            'customer_id' => $data['customer_id'],
            'warehouse_id' => $data['warehouse_id'],
            'customer' => $data['customer'],
            'date' => $data['date'],
            'suspend_note' => $data['suspend_note'],
            'total' => $data['grand_total'],
            'order_tax_id' => $data['order_tax_id'],
            'order_discount_id' => $data['order_discount_id'],
            'created_by' => $this->session->userdata('user_id'),
            'order_tip_id' => $data['order_tip_id']
        );

        $s_products = $this->pos_model->getSuspendedSaleItems($did,true);

        foreach($items as &$item){
                $id = $item['id'];
                unset($item['id']);

                $item['suspend_id'] = ($did) ? $did : 0;
                $item['product_table'] = $sData['id_table'];
                $item['product_waiter'] = $sData['id_waiter'];
                $item['product_status'] = "dispatched";
                $item['date_confirmed'] = date("Y-m-d H:i:s");
                $item['comments'] = "";

            if(isset($s_products[$id])){
                $item['product_waiter'] = $s_products[$id]['product_waiter'];
                $item['product_status'] = $s_products[$id]['product_status'];
                $item['date_confirmed'] = $s_products[$id]['date_confirmed'];
                $item['product_category'] = $s_products[$id]['product_category'];
                $item['comments'] = $s_products[$id]['comments'];
                $item['date_dispatched'] = $s_products[$id]['date_dispatched'];
                $item['read_notify'] = $s_products[$id]['read_notify'];
            }else{
                $item['date_dispatched'] = date("Y-m-d H:i:s");
                $item['read_notify'] = 1;
            }
        }
        
  
        if ($did) {
            
            $this->db->delete('suspended_items', array('suspend_id' => $did));
                    
            if ($this->db->update('suspended_bills', $sData, array('id' => $did))) {
                
                $this->load->model('tables_model');
                if($table = $this->tables_model->get_table($data['id_table'])){
                    if($table->status != 0){
                        // Update table details
                        $table_info = array(
                           'waiter' => $data['id_waiter'],
                        );

                        $this->tables_model->update_table($data['id_table'], $table_info);
                    }
                }
                
                if ($this->db->insert_batch('suspended_items', $items)) {
                    return $did;
                }
            }

        } else {

            if ($this->db->insert('suspended_bills', $sData)) {
                $suspend_id = $this->db->insert_id();
                
                $this->load->model('tables_model');
                if($table = $this->tables_model->get_table($data['id_table'])){
                    if(!$table->status){
                        // Update table details
                        $table_info = array(
                           'status' => 1,
                           'waiter' => $data['id_waiter'],
                           'guests' => $table->guests,
                           'bill'   => $suspend_id
                        );

                        $this->tables_model->update_table($data['id_table'], $table_info);
                    }
                }
                
                $addOn = array('suspend_id' => $suspend_id);
//                end($addOn);
                foreach ($items as &$var) {
                    $var = array_merge($var, $addOn);
                }
                if ($this->db->insert_batch('suspended_items', $items)) {
                    return $suspend_id;
                }
            }

        }
        return FALSE;
    }

    public function deleteBill($id = null)
    {
        if ($id){
            $this->log_suspended_sale($id, "delete_bill");
            
            $this->db->delete('suspended_items', array('suspend_id' => $id));
            $this->db->delete('suspended_bills', array('id' => $id));

            $data = array('status' => 0,'waiter' => 0,'bill'  => 0);
            $this->db->where('bill', $id);
            return $this->db->update('tables', $data);

        }

        return FALSE;
    }
    
    public function deleteBillItems($id = null) {
        if ($id != null){
            $this->db->delete('suspended_items', array('suspend_id' => $id));
            return true;
        }

        return FALSE;
    }

    public function getInvoicePayments($sale_id)
    {
        $q = $this->db->get_where("payments", array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    function stripe($amount = 0, $card_info = array(), $desc = '')
    {
        $this->load->model('stripe_payments');
        //$card_info = array( "number" => "4242424242424242", "exp_month" => 1, "exp_year" => 2016, "cvc" => "314" );
        //$amount = $amount ? $amount*100 : 3000;
        $amount = $amount * 100;
        if ($amount && !empty($card_info)) {
            $token_info = $this->stripe_payments->create_card_token($card_info);
            if (!isset($token_info['error'])) {
                $token = $token_info->id;
                $data = $this->stripe_payments->insert($token, $desc, $amount, $this->default_currency->code);
                if (!isset($data['error'])) {
                    $result = array('transaction_id' => $data->id,
                        'created_at' => date($this->dateFormats['php_ldate'], $data->created),
                        'amount' => ($data->amount / 100),
                        'currency' => strtoupper($data->currency)
                    );
                    return $result;
                } else {
                    return $data;
                }
            } else {
                return $token_info;
            }
        }
        return false;
    }

    function paypal($amount = NULL, $card_info = array(), $desc = '')
    {
        $this->load->model('paypal_payments');
        //$card_info = array( "number" => "5522340006063638", "exp_month" => 2, "exp_year" => 2016, "cvc" => "456", 'type' => 'MasterCard' );
        //$amount = $amount ? $amount : 30.00;
        if ($amount && !empty($card_info)) {
            $data = $this->paypal_payments->Do_direct_payment($amount, $this->default_currency->code, $card_info, $desc);
            if (!isset($data['error'])) {
                $result = array('transaction_id' => $data['TRANSACTIONID'],
                    'created_at' => date($this->dateFormats['php_ldate'], strtotime($data['TIMESTAMP'])),
                    'amount' => $data['AMT'],
                    'currency' => strtoupper($data['CURRENCYCODE'])
                );
                return $result;
            } else {
                return $data;
            }
        }
        return false;
    }

    public function authorize($authorize_data)
    {
        $this->load->library('authorize_net');
        // $authorize_data = array( 'x_card_num' => '4111111111111111', 'x_exp_date' => '12/20', 'x_card_code' => '123', 'x_amount' => '25', 'x_invoice_num' => '15454', 'x_description' => 'References');
        $this->authorize_net->setData($authorize_data);

        if( $this->authorize_net->authorizeAndCapture() ) {
            $result = array(
                'transaction_id' => $this->authorize_net->getTransactionId(),
                'approval_code' => $this->authorize_net->getApprovalCode(),
                'created_at' => date($this->dateFormats['php_ldate']),
            );
            return $result;
        } else {
            return array('error' => 1, 'msg' => $this->authorize_net->getError());
        }
    }

    public function addPayment($payment = array(), $customer_id = null)
    {
        if (isset($payment['sale_id']) && isset($payment['paid_by']) && isset($payment['amount'])) {
            $payment['pos_paid'] = $payment['amount'];
            $inv = $this->getInvoiceByID($payment['sale_id']);
            $paid = $inv->paid + $payment['amount'];
            if ($payment['paid_by'] == 'ppp') {
                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                $result = $this->paypal($payment['amount'], $card_info);
                if (!isset($result['error'])) {
                    $payment['transaction_id'] = $result['transaction_id'];
                    $payment['date'] = $this->sma->fld($result['created_at']);
                    $payment['amount'] = $result['amount'];
                    $payment['currency'] = $result['currency'];
                    unset($payment['cc_cvv2']);
                    $this->db->insert('payments', $payment);
                    $paid += $payment['amount'];
                } else {
                    $msg[] = lang('payment_failed');
                    if (!empty($result['message'])) {
                        foreach ($result['message'] as $m) {
                            $msg[] = '<p class="text-danger">' . $m['L_ERRORCODE'] . ': ' . $m['L_LONGMESSAGE'] . '</p>';
                        }
                    } else {
                        $msg[] = lang('paypal_empty_error');
                    }
                }
            } elseif ($payment['paid_by'] == 'stripe') {
                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                $result = $this->stripe($payment['amount'], $card_info);
                if (!isset($result['error'])) {
                    $payment['transaction_id'] = $result['transaction_id'];
                    $payment['date'] = $this->sma->fld($result['created_at']);
                    $payment['amount'] = $result['amount'];
                    $payment['currency'] = $result['currency'];
                    unset($payment['cc_cvv2']);
                    $this->db->insert('payments', $payment);
                    $paid += $payment['amount'];
                } else {
                    $msg[] = lang('payment_failed');
                    $msg[] = '<p class="text-danger">' . $result['code'] . ': ' . $result['message'] . '</p>';
                }
            } else {
                if ($payment['paid_by'] == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($payment['cc_no']);
                    $this->db->update('gift_cards', array('balance' => ($gc->balance - $payment['amount'])), array('card_no' => $payment['cc_no']));
                } elseif ($customer_id && $payment['paid_by'] == 'deposit') {
                    $customer = $this->site->getCompanyByID($customer_id);
                    $this->db->update('companies', array('deposit_amount' => ($customer->deposit_amount-$payment['amount'])), array('id' => $customer_id));
                }
                unset($payment['cc_cvv2']);
                $this->db->insert('payments', $payment);
                $paid += $payment['amount'];
            }
            if (!isset($msg)) {
                if ($this->site->getReference('pay') == $data['reference_no']) {
                    $this->site->updateReference('pay');
                }
                $this->site->syncSalePayments($payment['sale_id']);
                return array('status' => 1, 'msg' => '');
            }
            return array('status' => 0, 'msg' => $msg);

        }
        return false;
    }
    
    public function log_suspended_sale($id_suspended_sale, $message) {
        
        $suspended_sale = $this->getOpenBillByID($id_suspended_sale);
        $nombre_archivo = "logs_freetable_restaurant.txt";
        $mensaje = "";
        
        if(file_exists($nombre_archivo))
        {
//            $mensaje = "El Archivo $nombre_archivo se ha modificado ";
        }
        else
        {
//            $mensaje = "El Archivo $nombre_archivo se ha creado ";
        }
        
        if(!empty($message)){
           $mensaje .= $message; 
        }

        if(!empty($suspended_sale)){
            $mensaje .= " id = " . $suspended_sale->id .", date = " . $suspended_sale->date .  ", id_table = " . $suspended_sale->id_table .  ", id_waiter = " . $suspended_sale->id_waiter .  ", count_products = " . $suspended_sale->count .  ", total = " . $suspended_sale->total .  ", created_by = " . $suspended_sale->created_by;
            
            if(!empty($suspended_sale->suspend_note)){
                if(strcmp($suspended_sale->suspend_note, "") != 0){
                    $mensaje .=  ", suspend_note = " . $suspended_sale->suspend_note; 
                } 
            }
            
        }else{
            $mensaje .= " venta suspendida no existente id = " . $id_suspended_sale;
        }

        if($archivo = fopen($nombre_archivo, "a"))
        {
            if(fwrite($archivo, date("d m Y H:i:s"). " ". $mensaje. "\n"))
            {
//                    echo "Se ha ejecutado correctamente";
            }
            else
            {
//                    echo "Ha habido un problema al crear el archivo";
            }

            fclose($archivo);
        }
    }
    
    public function get_register_cashiers() {
        $registers  = $this->getOpenRegisters();
        $cashiers = array();
        
        foreach ($registers as $value) {
            if($this->sma->is_cashier($value->user_id)){
                $cashiers[] = $value;
            }
        }
        return $cashiers;
    }
    
    public function autoUpdateWeekendProducts() {
        $settings = $this->Settings;
        $days_premium_price = explode(",", $settings->days_premium_price);
        $day = date("l");
        
        if(in_array($day, $days_premium_price)){
            $this->updateStatusPremiumPrice($settings->setting_id, 1);
        }else {
            $this->updateStatusPremiumPrice($settings->setting_id, 0);
        }
        
        $this->load->model('settings_model');

        if(in_array($day, $days_premium_price)){
            $this->updateStatusCategory(26, 1, 1);
            
            $this->updateStatusCategory(15, 0, 0);
        }else{
            $this->updateStatusCategory(15, 1, 1);
            
            $this->updateStatusCategory(26, 0, 0);
        }
    }
    
    public function updateStatusCategory($id, $status, $toplist) {
        $data = array(
                'status' => $status,
                'top_list' => $toplist
            );
            
        $this->settings_model->updateCategory($id, $data);
    }
    
    public function getExpenseByCreateID($id, $register_open_time)
    {
        $q = $this->db->get_where('expenses', array('created_by' => $id, 'date >' => $register_open_time));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $q->result();
        }
        return FALSE;
    }
    
    public function updateStatusPremiumPrice($setting_id, $status) {
        $data = array(
                'status_premium_price' => $status
            );
            
        if ($this->db->update("settings", $data, array('setting_id' => $setting_id))) {
            return true;
        }
        return false;
    }
    
    public function getWaiters() {
//        $group->name == "waiter" || $group->name == "mozo"
        $group = $this->sma->getGroupByName("mozo")->id;
        $q = $this->db->get_where('users', array('group_id' => $group));
        
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[$row->id] = $row->first_name . " " . $row->last_name;
            }
            return $data;
        }
        return FALSE;
    }
}
