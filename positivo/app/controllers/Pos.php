<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pos extends MY_Controller
{

    public function __construct()
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

        $this->load->model('pos_model');
        $this->load->model('sales_model');
        $this->load->model('dian_model');
        $this->load->model('Tables_model');
        $this->load->helper('text');
        $this->pos_settings = $this->pos_model->getSetting();
        $this->pos_settings->pin_code = $this->pos_settings->pin_code ? md5($this->pos_settings->pin_code) : NULL;
        $this->data['pos_settings'] = $this->pos_settings;
        $this->session->set_userdata('last_activity', now());
        $this->lang->load('pos', $this->Settings->user_language);
        $this->lang->load('dian', $this->Settings->language);
        $this->lang->load('restaurant', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->library('restaurant');
        $this->load->library('nusoap_library');
        $this->load->model('settings_model');
        $this->load->model('companies_model');
    }
    #función prueba vista prueba
    public function manuBilling()
    {
       /* $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('pos'), 'page' => lang('pos')), array('link' => '#', 'page' => 'prueba'));
        $meta = array('page_title' => lang('pos_sales'), 'bc' => $bc);*/

        #logica facturación manual

        if ($this->pos_settings->after_sale_page)
        {

            $this->load->helper('text');
            $this->load->helper('array');


            $getAllDian=$this->dian_model->getAllConfDian();
            if($getAllDian[0]->billing!=0)
            {
                if ($this->dian_model->getAllConfDian() && $this->dian_model->getDian_api())
                {
                    $conf_dian=$this->dian_model->getAllConfDian();
                    $reference_dian=$conf_dian[0]->billing_prefix.$conf_dian[0]->current_number;
                    $this->dian_model->updateConfiDianId($conf_dian[0]->current_number);


                    $salesDay = $this->pos_model->countSalesDay();
                    $this->pos_model->updateSaleDianId($salesDay['0']->id,$reference_dian);
                    $inv = $this->pos_model->getInvoiceByID($salesDay['0']->id);
                    $rows = $this->pos_model->getAllInvoiceItems($salesDay['0']->id);
                    $payments = $this->pos_model->getInvoicePayments($salesDay['0']->id);


                    $numSalesDay=count($salesDay);
                    for ($i=1; $i < $numSalesDay ; $i++)
                    {
                        $inv->total+=$salesDay[$i]->total;
                        $inv->product_discount+=$salesDay[$i]->product_discount;
                        $inv->total_discount+=$salesDay[$i]->total_discount;
                        $inv->order_discount+=$salesDay[$i]->order_discount;
                        $inv->product_tax+=$salesDay[$i]->product_tax;
                        $inv->order_tax+= $salesDay[$i]->order_tax;
                        $inv->total_tax+=$salesDay[$i]->total_tax;
                        $inv->shipping+=$salesDay[$i]->shipping;
                        $inv->grand_total+=$salesDay[$i]->grand_total;
                        $inv->paid+=$salesDay[$i]->paid;
                        $inv->surcharge+=$salesDay[$i]->surcharge;
                        $inv->return_sale_total+=$salesDay[$i]->return_sale_total;
                        $inv->rounding+=$salesDay[$i]->rounding;
                        $inv->guests+=$salesDay[$i]->guests;

                        $items=$this->pos_model->getAllInvoiceItems($salesDay[$i]->id);
                        for ($j=0; $j < count($items) ; $j++)
                        {
                            #$rows[(count($rows)+1)]=$items[$j];
                            array_push($rows, $items[$j]);
                        }

                        $payment = $this->pos_model->getInvoicePayments($salesDay[$i]->id);
                        $payments['0']->amount+=$payment['0']->amount;
                        $payments['0']->pos_paid+=$payment['0']->pos_paid;
                        $payments['0']->pos_balance+=$payment['0']->pos_balance;

                        $this->pos_model->updateSaleDianId($salesDay[$i]->id,$reference_dian);
                    }

                    $inv->order_tax         =$inv->order_tax==0 ? number_format($inv->order_tax, 4, '.', ','):$inv->order_tax;
                    $inv->product_discount  =$inv->product_discount==0 ? number_format($inv->product_discount, 4, '.', ','):$inv->product_discount;
                    $inv->total_discount    =$inv->total_discount==0?  number_format($inv->total_discount, 4, '.', ','):$inv->total_discount;
                    $inv->order_discount    =$inv->order_discount==0? number_format($inv->order_discount, 4, '.', ','):$inv->order_discount;
                    $inv->product_tax       =$inv->product_tax==0? number_format($inv->product_tax, 4, '.', ','):$inv->product_tax;
                    $inv->total_tax         =$inv->total_tax==0? number_format($inv->total_tax, 4, '.', ','): $inv->total_tax;
                    $inv->shipping          =$inv->shipping==0? number_format($inv->shipping, 4, '.', ','):$inv->shipping;
                    $inv->surcharge         =$inv->surcharge==0? number_format($inv->surcharge, 4, '.', ','): $inv->surcharge;
                    $inv->return_sale_total =$inv->return_sale_total==0? number_format($inv->return_sale_total, 4, '.', ','):$inv->return_sale_total;
                    $inv->rounding          =$inv->rounding==0? number_format($inv->rounding, 4, '.', ','):$inv->rounding;
                    $inv->guests            =$inv->guests==0? number_format($inv->guests, 4, '.', ','):$inv->guests;


                    $this->data['getSalesTax'] = $this->pos_model->getSalesTax($inv->order_tax_id);
                    $this->data['rows'] = $rows;
                    #$this->data['rows'] = $this->pos_model->getAllInvoiceItems($inv->id);

                    $this->data['biller'] =   $this->pos_model->getCompanyByID($inv->biller_id);
                    $this->data['customer'] = $this->pos_model->getCompanyByID($inv->customer_id);
                    $this->data['payments'] = $payments;
                    #$this->data['payments'] =$payments = $this->pos_model->getInvoicePayments($inv->id);
                    $this->data['pos'] = $this->pos_model->getSetting();
                    $this->data['conf_dian'] = $this->dian_model->getAllConfDian();
                    $this->data['barcode'] = $this->barcode($inv->reference_no, 'code128', 30);
                    $this->data['inv'] = $inv;
                    #$this->data['inv']=$this->pos_model->getInvoiceByID($inv->id);
                    $this->data['sid'] = $inv->id;
                    $this->data['id_venta']=$inv->reference_no;

                    //invocación al metodo de construcción del xml
                    $this->create_xml($this->data);
                    //Envio de venta DIAN - carga de archivo y actualización de transacciónID sales
                    if($this->dian_model->getDian_api())
                    {
                        $api=$this->dian_model->getDian_api();
                        $data_dian=$this->dian_model->getAllConfDian();
                        $settings=$this->settings_model->getSettings();
                        //parameter xml DIAN a base64
                        $file= simplexml_load_file(base_url().'venta.xml');
                        $string= $file->asXML();
                        $base= base64_encode($string);
                        $params = array('username'=>$api->username,
                                        'password'=>$api->password,
                                        'xmlBase64'=>$base);

                        //invoke method conection and parameters FTECHACTION.UPLOADINVOICEFILE
                        $getsoap=$this->nusoap_library->soaprequest('FtechAction.uploadInvoiceFile',$api,$params);
                        if ($getsoap['Result']['code']==200 || $getsoap['Result']['code']==201)
                        {
                            $this->pos_model->updateSalesTransId($getsoap['Result']['transaccionID'],($settings->sales_prefix).($data_dian[0]->current_number-1));
                        }
                    }
                }
            }
        }
        redirect('pos/sales');
        #$this->page_construct('pos/prueba', $meta ,$this->data);
    }
    //////////////////////////////////////
    public function sales($warehouse_id = NULL)
    {
        $this->sma->checkPermissions('index');

        if($this->sma->is_cashier()){
            redirect("tables");
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $user = $this->site->getUser();
            $this->data['warehouses'] = NULL;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
        }
            $this->data['tpFAct']= $this->dian_model->getAllConfDian();
            $this->data['countSales']=$this->pos_model->countSalesDay();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('pos'), 'page' => lang('pos')), array('link' => '#', 'page' => lang('pos_sales')));
        $meta = array('page_title' => lang('pos_sales'), 'bc' => $bc);
        $this->page_construct('pos/sales', $meta, $this->data);
    }

    public function getSales($warehouse_id = NULL)
    {
      $this->sma->checkPermissions('index');

          if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
              $user = $this->site->getUser();
              $warehouse_id = $user->warehouse_id;
          }
          $detail_link = anchor('pos/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('view_receipt'));
          $detail_dian_link = anchor('dian/invoice_pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('view_receipt_dian'),'target="_blank"');
          $detail_link2 = anchor('sales/modal_view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details_modal'), 'data-toggle="modal" data-target="#myModal"');
          $detail_link3 = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'));
          $payments_link = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
          $add_payment_link = anchor('pos/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
          $add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
          $email_link = anchor('pos/sales', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'class="email_receipt" data-id="$1" data-email-address="$2"');
          $edit_link = anchor('pos/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_sale'), 'class="sledit"');
          $return_link = anchor('sales/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
          $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"
              . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete/$1') . "'>"
              . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
              . lang('delete_sale') . "</a>";
          $action = '<div class="text-center"><div class="btn-group text-left">'
              . '<button type="button" class="btn btn-xs  dropdown-toggle" data-toggle="dropdown">'
              . '<i class="fa fa-bars" aria-hidden="true"></i>'. '</button>
      <ul class="dropdown-menu pull-right" role="menu">
          <li>' . $detail_link . '</li>
          <li>' . $detail_dian_link . '</li>
          <li>' . $detail_link2 . '</li>
          <li>' . $detail_link3 . '</li>
          <li>' . $payments_link . '</li>
          <li>' . $add_payment_link . '</li>
          <li>' . $add_delivery_link . '</li>
          <li>' . $email_link . '</li>
          <li>' . $return_link . '</li>
          <li>' . $delete_link . '</li>
      </ul>
  </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('sales') . ".id as id, date, reference_no, biller, customer, (grand_total+rounding), paid, (grand_total-paid) as balance, payment_status, companies.email as cemail")
                ->from('sales')
                ->join('companies', 'companies.id=sales.customer_id', 'left')
                ->where('warehouse_id', $warehouse_id)
                ->group_by('sales.id');
        } else {
            $this->datatables
                ->select($this->db->dbprefix('sales') . ".id as id, date, reference_no, biller, customer, (grand_total+rounding), paid, (grand_total+rounding-paid) as balance, payment_status, companies.email as cemail")
                ->from('sales')
                ->join('companies', 'companies.id=sales.customer_id', 'left')
                ->group_by('sales.id');
        }
        $this->datatables->where('pos', 1);
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('user_id'));
        }
        $this->datatables->add_column("Actions", $action, "id, cemail")->unset_column('cemail');
        echo $this->datatables->generate();
    }



    /*-------------------------------------------------------------------------------------------------------*/

    public function index($sid = NULL)
    {
        $this->sma->checkPermissions();

        $pr = array();

        if (!$this->pos_settings->default_biller || !$this->pos_settings->default_customer || !$this->pos_settings->default_category) {
            $this->session->set_flashdata('warning', lang('please_update_settings'));
            redirect('pos/settings');
        }
        if ($register = $this->pos_model->registerData($this->session->userdata('user_id'))) {
            $register_data = array('register_id' => $register->id, 'cash_in_hand' => $register->cash_in_hand, 'register_open_time' => $register->date);
            $this->session->set_userdata($register_data);
        } else {
            $this->session->set_flashdata('error', lang('register_not_open'));
            redirect('pos/open_register');
        }

        $this->data['sid'] = $this->input->get('suspend_id') ? $this->input->get('suspend_id') : $sid;
        $did = $this->input->post('delete_id') ? $this->input->post('delete_id') : NULL;
        $suspend = $this->input->post('suspend') ? TRUE : FALSE;
        $count = $this->input->post('count') ? $this->input->post('count') : NULL;

        //validate form input
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'trim|required');
        $this->form_validation->set_rules('warehouse', $this->lang->line("warehouse"), 'required');
        $this->form_validation->set_rules('biller', $this->lang->line("biller"), 'required');

        if ($this->form_validation->run() == TRUE) {

            $date = date('Y-m-d H:i:s');
            $warehouse_id = $this->input->post('warehouse');
            $customer_id = $this->input->post('customer');
            $table_id = $this->input->post('table');
            $biller_id = $this->input->post('biller');
            $total_items = $this->input->post('total_items');
            $sale_status = 'completed';
            $payment_status = 'due';
            $payment_term = 0;
            $due_date = date('Y-m-d', strtotime('+' . $payment_term . ' days'));
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $customer_details = $this->site->getCompanyByID($customer_id);
            $customer = $customer_details->company != '-'  ? $customer_details->company : $customer_details->name;
            $biller_details = $this->site->getCompanyByID($biller_id);
            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note = $this->sma->clear_tags($this->input->post('pos_note'));
            $staff_note = $this->sma->clear_tags($this->input->post('staff_note'));

            // special case for the facture 22/12/2016 - no reference number with customer carlos
            $reference = ($customer_id != 11) ? $this->site->getReference('pos') : $this->site->getReference('pos', true) ;
            $item_category = 0;

            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $order_tip = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $i = isset($_POST['product_code']) ? sizeof($_POST['product_code']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_sid = $_POST['product_sid'][$r];
                $item_id = $_POST['product_id'][$r];
                $item_type = $_POST['product_type'][$r];
                $item_code = $_POST['product_code'][$r];
                $item_name = $_POST['product_name'][$r];
                $item_option = isset($_POST['product_option'][$r]) && $_POST['product_option'][$r] != 'false' ? $_POST['product_option'][$r] : NULL;
                $real_unit_price = $this->sma->formatDecimal($_POST['real_unit_price'][$r]);
                $unit_price = $this->sma->formatDecimal($_POST['unit_price'][$r]);
                $item_unit_quantity = $_POST['quantity'][$r];
                $item_serial = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_tax_rate = isset($_POST['product_tax'][$r]) ? $_POST['product_tax'][$r] : NULL;
                $item_discount = isset($_POST['product_discount'][$r]) ? $_POST['product_discount'][$r] : NULL;
                $item_unit = $_POST['product_unit'][$r];
                $item_quantity = $_POST['product_base_quantity'][$r];

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->pos_model->getProductByCode($item_code) : NULL;

                    // $unit_price = $real_unit_price;
                    $pr_discount = 0;

                    if (isset($item_discount)) {
                        $discount = $item_discount;
                        $dpos = strpos($discount, $percentage);
                        if ($dpos !== FALSE) {
                            $pds = explode("%", $discount);
                            $pr_discount = $this->sma->formatDecimal(((($this->sma->formatDecimal($unit_price)) * (Float)($pds[0])) / 100), 4);
                        } else {
                            $pr_discount = $this->sma->formatDecimal($discount);
                        }
                    }

                    $item_category = $product_details->category_id;
                    $unit_price = $this->sma->formatDecimal($unit_price - $pr_discount);
                    $item_net_price = $unit_price;
                    $pr_item_discount = $this->sma->formatDecimal($pr_discount * $item_unit_quantity);
                    $product_discount += $pr_item_discount;
                    $pr_tax = 0;
                    $pr_item_tax = 0;
                    $item_tax = 0;
                    $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / 100, 4);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate), 4);
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                        } elseif ($tax_details->type == 2) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / 100, 4);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate), 4);
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                            $item_tax = $this->sma->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->sma->formatDecimal(($item_tax * $item_unit_quantity), 4);

                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_unit_quantity) + $pr_item_tax);
                    $unit = $this->site->getUnitByID($item_unit);

                    $products[] = array(
                        'id'              =>$item_sid,
                        'product_id'      => $item_id,
                        'product_code'    => $item_code,
                        'product_name'    => $item_name,
                        'product_type'    => $item_type,
                        'product_category'=> $item_category,
                        'option_id'       => $item_option,
                        'net_unit_price'  => $item_net_price,
                        'unit_price'      => $this->sma->formatDecimal($item_net_price + $item_tax),
                        'quantity'        => $item_quantity,
                        'product_unit_id' => $item_unit,
                        'product_unit_code' => $unit ? $unit->code : NULL,
                        'unit_quantity' => $item_unit_quantity,
                        'warehouse_id'    => $warehouse_id,
                        'item_tax'        => $pr_item_tax,
                        'tax_rate_id'     => $pr_tax,
                        'tax'             => $tax,
                        'discount'        => $item_discount,
                        'item_discount'   => $pr_item_discount,
                        'subtotal'        => $this->sma->formatDecimal($subtotal),
                        'serial_no'       => $item_serial,
                        'real_unit_price' => $real_unit_price,
                    );

                    $total += $this->sma->formatDecimal(($item_net_price * $item_unit_quantity), 4);
                }
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } elseif ($this->pos_settings->item_order == 1) {
                krsort($products);
            }

            if ($this->input->post('discount')) {
                $order_discount_id = $this->input->post('discount');
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== FALSE) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = $this->sma->formatDecimal(((($total + $product_tax) * (Float)($ods[0])) / 100), 4);
                } else {
                    $order_discount = $this->sma->formatDecimal($order_discount_id);
                }
            } else {
                $order_discount_id = NULL;
            }
            $total_discount = $this->sma->formatDecimal($order_discount + $product_discount);

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->sma->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->sma->formatDecimal(((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100), 4);
                    }
                }
            } else {
                $order_tax_id = NULL;
            }

            $total_tax = $this->sma->formatDecimal(($product_tax + $order_tax), 4);

            if ($this->Settings->tip) {
                $order_tip_id = $this->input->post('order_tip');
                if ($order_tip_details = $this->site->getTipRateByID($order_tip_id)) {
                    if ($order_tip_details->type == 2) {
                        $order_tip = $this->sma->formatDecimal($order_tip_details->rate);
                    }
                    if ($order_tip_details->type == 1) {
                        if($this->Settings->sale_tax_method == 0){
                            $order_tip = $this->sma->formatDecimal(round((($total - $total_tax - $order_discount) * $order_tip_details->rate) / 100), 4);
                        }else{
                            $order_tip = $this->sma->formatDecimal(round((($total - $order_discount) * $order_tip_details->rate) / 100), 4);
                        }
                    }
                }
            } else {
                $order_tip_id = NULL;
            }

            if($this->Settings->sale_tax_method == 0){
                $grand_total = $this->sma->formatDecimal(($total + $order_tip + $this->sma->formatDecimal($shipping) - $order_discount), 4);
            }else {
                $grand_total = $this->sma->formatDecimal(($total + $total_tax + $order_tip + $this->sma->formatDecimal($shipping) - $order_discount), 4);
            }

            $rounding = 0;
            if ($this->pos_settings->rounding) {
                $round_total = $this->sma->roundNumber($grand_total, $this->pos_settings->rounding);
                $rounding = $this->sma->formatMoney($round_total - $grand_total);
            }
            $waiter_on_sale = $this->sma->get_Waiter_On_Sale($this->input->post('id_suspended_sale'));
            if(!$waiter_on_sale && $this->pos_settings->default_waiter != null ){
                $waiter_on_sale = $this->pos_settings->default_waiter;
            }

            $data = array('date'              => $date,
                          'reference_no'      => $reference,
                          'customer_id'       => $customer_id,
                          'customer'          => $customer,
                          'biller_id'         => $biller_id,
                          'biller'            => $biller,
                          'warehouse_id'      => $warehouse_id,
                          'note'              => $note,
                          'staff_note'        => $staff_note,
                          'total'             => $total,
                          'product_discount'  => $product_discount,
                          'order_discount_id' => $order_discount_id,
                          'order_discount'    => $order_discount,
                          'total_discount'    => $total_discount,
                          'product_tax'       => $product_tax,
                          'order_tax_id'      => $order_tax_id,
                          'order_tax'         => $order_tax,
                          'total_tax'         => $total_tax,
                          'shipping'          => $this->sma->formatDecimal($shipping),
                          'grand_total'       => $grand_total,
                          'total_items'       => $total_items,
                          'sale_status'       => $sale_status,
                          'payment_status'    => $payment_status,
                          'payment_term'      => $payment_term,
                          'rounding'          => $rounding,
                          'pos'               => 1,
                          'paid'              => $this->input->post('amount-paid') ? $this->input->post('amount-paid') : 0,
                          'created_by'        => $this->session->userdata('user_id'),
                          'id_table'          => $table_id,
                          'id_waiter'         => $waiter_on_sale,
                          'guests'            => $this->sma->getGuest($table_id),
                          'order_tip_id'      => $order_tip_id,
                          'order_tip'         => $order_tip,
                          'sale_tax_method'   => $this->Settings->sale_tax_method
            );

            if (!$suspend) {
                $p = isset($_POST['amount']) ? sizeof($_POST['amount']) : 0;
                $paid = 0;
                $discount_all = ($total == $total_discount);
                for ($r = 0; $r < $p; $r++) {
                    if (isset($_POST['amount'][$r]) && isset($_POST['paid_by'][$r]) && !empty($_POST['paid_by'][$r])) {
                        if(!empty($_POST['amount'][$r]) || $discount_all){
                        $amount = $this->sma->formatDecimal($_POST['balance_amount'][$r] > 0 ? $_POST['amount'][$r] - $_POST['balance_amount'][$r] : $_POST['amount'][$r]);
                        if ($_POST['paid_by'][$r] == 'deposit') {
                            if ( ! $this->site->check_customer_deposit($customer_id, $amount)) {
                                $this->session->set_flashdata('error', lang("amount_greater_than_deposit"));
                                redirect($_SERVER["HTTP_REFERER"]);
                            }
                        }
                        if ($_POST['paid_by'][$r] == 'gift_card') {
                            $gc = $this->site->getGiftCardByNO($_POST['paying_gift_card_no'][$r]);
                            $amount_paying = $_POST['amount'][$r] >= $gc->balance ? $gc->balance : $_POST['amount'][$r];
                            $gc_balance = $gc->balance - $amount_paying;
                            $payment[] = array(
                                'date'         => $date,
                                // 'reference_no' => $this->site->getReference('pay'),
                                'amount'       => $amount,
                                'paid_by'      => $_POST['paid_by'][$r],
                                'cheque_no'    => $_POST['cheque_no'][$r],
                                'cc_no'        => $_POST['paying_gift_card_no'][$r],
                                'cc_holder'    => $_POST['cc_holder'][$r],
                                'cc_month'     => $_POST['cc_month'][$r],
                                'cc_year'      => $_POST['cc_year'][$r],
                                'cc_type'      => $_POST['cc_type'][$r],
                                'cc_cvv2'      => $_POST['cc_cvv2'][$r],
                                'created_by'   => $this->session->userdata('user_id'),
                                'type'         => 'received',
                                'note'         => $_POST['payment_note'][$r],
                                'pos_paid'     => $_POST['amount'][$r],
                                'pos_balance'  => $_POST['balance_amount'][$r],
                                'gc_balance'  => $gc_balance,
                                );

                        } else {
                            $payment[] = array(
                                'date'         => $date,
                                // 'reference_no' => $this->site->getReference('pay'),
                                'amount'       => $amount,
                                'paid_by'      => $_POST['paid_by'][$r],
                                'cheque_no'    => $_POST['cheque_no'][$r],
                                'cc_no'        => $_POST['cc_no'][$r],
                                'cc_holder'    => $_POST['cc_holder'][$r],
                                'cc_month'     => $_POST['cc_month'][$r],
                                'cc_year'      => $_POST['cc_year'][$r],
                                'cc_type'      => $_POST['cc_type'][$r],
                                'cc_cvv2'      => $_POST['cc_cvv2'][$r],
                                'created_by'   => $this->session->userdata('user_id'),
                                'type'         => 'received',
                                'note'         => $_POST['payment_note'][$r],
                                'pos_paid'     => $_POST['amount'][$r],
                                'pos_balance'  => $_POST['balance_amount'][$r],
                                );

                        }
                        }
                    }
                    if( $discount_all ){ $r = $p; }
                }
            }
            if (!isset($payment) || empty($payment)) {
                $payment = array();
            }

            // $this->sma->print_arrays($data, $products, $payment);
        }

        if ($this->form_validation->run() == TRUE && !empty($products) && !empty($data)) {
            if ($suspend) {
                $data['suspend_note'] = $this->input->post('suspend_note');
                if(!empty($this->input->post('waiter'))){
                    $data['id_waiter'] = $this->input->post('waiter');
                }
                if ($suspend_id = $this->pos_model->suspendSale($data, $products, $did)) {
//                    $this->session->set_userdata('remove_posls', 1);
                    $this->session->set_flashdata('message', $this->lang->line("sale_suspended"));
                    if(!empty($suspend_id)){
                        redirect("pos/index/{$suspend_id}");
                    }
//                    $this->sma->clean_Storage('pos');
//                    redirect("pos");
                }
            } else {
                if ($sale = $this->pos_model->addSale($data, $products, $payment, $did)) {

                    //Edición campo cantidad del producto tipo combo  
                    $prodId= $this->pos_model->getProductId($sale['sale_id']);
                    if($prodId->product_type=='combo')
                    {                          
                        $this->pos_model->updateProductQuantity2($prodId->product_id);                    
                    }
                    //

                    $this->pos_model->log_suspended_sale($did, "add-sale");

                    $this->pos_model->deleteBill($did);

                    $this->session->set_userdata('remove_posls', 1);

                    $msg = $this->lang->line("sale_added");
                    if (!empty($sale['message'])) {
                        foreach ($sale['message'] as $m) {
                            $msg .= '<br>' . $m;
                        }
                    }
                    //echo $msg; die();

                    //Código para facturación Dian.

                        $this->session->set_flashdata('message', $msg);
                        if ($this->pos_settings->after_sale_page)
                        {

                            $this->load->helper('text');
                            $this->load->helper('array');



                            $getAllDian=$this->dian_model->getAllConfDian();
                            if($getAllDian[0]->billing!=1)
                            {
                                if ($this->dian_model->getAllConfDian() && $this->dian_model->getDian_api())
                                {
                                    $conf_dian=$this->dian_model->getAllConfDian();
                                    $reference_dian=$conf_dian[0]->billing_prefix.$conf_dian[0]->current_number;
                                    $this->pos_model->updateSaleDianId($sale['sale_id'],$reference_dian);
                                    $this->dian_model->updateConfiDianId($conf_dian[0]->current_number);
                                }
                                $inv = $this->pos_model->getInvoiceByID($sale['sale_id']);
                                $sale_id=$sale['sale_id'];
                                $data;
                                $data['getSalesTax']=$this->pos_model->getSalesTax($inv->order_tax_id);
                                $data['rows'] = $this->pos_model->getAllInvoiceItems($sale_id);
                                $biller_id = $inv->biller_id;
                                $customer_id = $inv->customer_id;
                                $data['biller'] =   $this->pos_model->getCompanyByID($biller_id);
                                $data['customer'] = $this->pos_model->getCompanyByID($customer_id);
                                $data['payments'] = $this->pos_model->getInvoicePayments($sale_id);
                                $data['pos'] = $this->pos_model->getSetting();
                                $data['conf_dian']=$this->dian_model->getAllConfDian();
                                $data['barcode'] = $this->barcode($inv->reference_no, 'code128', 30);
                                $data['inv'] = $inv;
                                $data['sid'] = $sale_id;
                                $data['id_venta']=$reference;

                                //invocación al metodo de construcción del xml
                                $this->create_xml($data);
                                //Envio de venta DIAN - carga de archivo y actualización de transacciónID sales
                                if($this->dian_model->getDian_api())
                                {
                                    $api=$this->dian_model->getDian_api();
                                    $data_dian=$this->dian_model->getAllConfDian();
                                    $settings=$this->settings_model->getSettings();
                                    //parameter xml DIAN a base64
                                    $file= simplexml_load_file(base_url().'venta.xml');
                                    $string= $file->asXML();
                                    $base= base64_encode($string);
                                    $params = array('username'=>$api->username,
                                                    'password'=>$api->password,
                                                    'xmlBase64'=>$base);

                                    //invoke method conection and parameters FTECHACTION.UPLOADINVOICEFILE
                                    $getsoap=$this->nusoap_library->soaprequest('FtechAction.uploadInvoiceFile',$api,$params);
                                    if ($getsoap['Result']['code']==200 || $getsoap['Result']['code']==201)
                                    {
                                        $this->pos_model->updateSalesTransId($getsoap['Result']['transaccionID'],($settings->sales_prefix).($data_dian[0]->current_number-1));

                                    }
                                }
                            }


                            // End facturación DIAN------------------------
                            $this->session->set_userdata( 'id_venta' , $data  );
                            redirect("pos");


                           // echo "<script>console.log(".json_encode($data).")</script>";


                        }
                        else
                        {
                            redirect("pos/view/" . $sale['sale_id']);
                        }

                       //redirect($this->pos_settings->after_sale_page ? "pos" : "pos/view/" . $sale['sale_id']);
                }
            }
        } else {
            $this->data['suspend_sale'] = NULL;
            if ($sid) {
                if ($suspended_sale = $this->pos_model->getOpenBillByID($sid)) {
                    $inv_items = $this->pos_model->getSuspendedSaleItems($sid);
                    ($inv_items) ? krsort($inv_items): $inv_items = array();
                    $c = rand(100000, 9999999);
                    foreach ($inv_items as $item) {
                        $row = $this->site->getProductByID($item->product_id);
                        if (!$row) {
                            $row = json_decode('{}');
                            $row->tax_method = 0;
                            $row->quantity = 0;
                        } else {
                            $category = $this->site->getCategoryByID($row->category_id);
                            $row->category_name = $category->name;
                            unset($row->cost, $row->details, $row->product_details, $row->image, $row->barcode_symbology, $row->cf1, $row->cf2, $row->cf3, $row->cf4, $row->cf5, $row->cf6, $row->supplier1price, $row->supplier2price, $row->cfsupplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1, $row->supplier2, $row->supplier3, $row->supplier4, $row->supplier5, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);
                        }
                        $pis = $this->site->getPurchasedItems($item->product_id, $item->warehouse_id, $item->option_id);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $row->quantity += $pi->quantity_balance;
                            }
                        }
                        $row->id = $item->product_id;
                        $row->code = $item->product_code;
                        $row->name = $item->product_name;
                        $row->type = $item->product_type;
                        $row->quantity += $item->quantity;
                        $row->discount = $item->discount ? $item->discount : '0';
                        $row->price = $this->sma->formatDecimal($item->net_unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity));
                        $row->unit_price = $row->tax_method ? $item->unit_price + $this->sma->formatDecimal($item->item_discount / $item->quantity) + $this->sma->formatDecimal($item->item_tax / $item->quantity) : $item->unit_price + ($item->item_discount / $item->quantity);
                        $row->real_unit_price = $item->real_unit_price;
                        $row->base_quantity = $item->quantity;
                        $row->base_unit = isset($row->unit) ? $row->unit : $item->product_unit_id;
                        $row->base_unit_price = $row->price ? $row->price : $item->unit_price;
                        $row->unit = $item->product_unit_id;
                        $row->qty = $item->unit_quantity;
                        $row->tax_rate = $item->tax_rate_id;
                        $row->serial = $item->serial_no;
                        $row->option = $item->option_id;
                        $options = $this->pos_model->getProductOptions($row->id, $item->warehouse_id);

                        if ($options) {
                            $option_quantity = 0;
                            foreach ($options as $option) {
                                $pis = $this->site->getPurchasedItems($row->id, $item->warehouse_id, $item->option_id);
                                if ($pis) {
                                    foreach ($pis as $pi) {
                                        $option_quantity += $pi->quantity_balance;
                                    }
                                }
                                if ($option->quantity > $option_quantity) {
                                    $option->quantity = $option_quantity;
                                }
                            }
                        }

                        $combo_items = false;
                        if ($row->type == 'combo') {
                            $combo_items = $this->sales_model->getProductComboItems($row->id, $item->warehouse_id);
                            
                        }
                        $units = $this->site->getUnitsByBUID($row->base_unit);
                        $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                        $ri = $this->Settings->item_addition ? $row->id : $c;

                        $pr[$ri] = array('sid' => $item->id, 'id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")",
                                'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options);
                        $c++;
                    }

                    $this->data['items'] = json_encode($pr);
                    $this->data['sid'] = $sid;

                    // set table local storage
                    $suspended_sale->table_lang = lang('table');
                    $suspended_sale->table_name = $this->restaurant->get_table($suspended_sale->id_table)->name;

                    // set waiter name local storage
                    $suspended_sale->waiter_name = $this->site->getUser($suspended_sale->id_waiter)->first_name . " " . $this->site->getUser($suspended_sale->id_waiter)->last_name;
                    $suspended_sale->lang_waiter_name = lang("waiter_name");
                    $suspended_sale->lang_customer = lang("customer");

                    // set message to bill
                    $suspended_sale->bill_title = lang("bill");
                    $suspended_sale->message_bill = $this->pos_settings->cf_value1 . "<br>";
                    $suspended_biller = $this->pos_model->getCompanyByID($suspended_sale->biller_id);
                    $suspended_sale->biller = $suspended_biller->name . "<br>" . lang("NIT"). ": " . $suspended_biller->cf1 . "<br>" .$suspended_biller->address . " " . $suspended_biller->city . " " . $suspended_biller->postal_code . " " . $suspended_biller->state . " " . $suspended_biller->country .
                        "<br>" . lang("tel") . ": " . $suspended_biller->phone . "<br>";
                    $suspended_sale->biller_tel = lang("tel") . ": " . $suspended_biller->phone;
                    $suspended_sale->biller_logo = base_url() . 'assets/uploads/logos/' . $suspended_biller->logo;

                    $this->data['suspend_sale'] = $suspended_sale;
                    $this->data['message'] = lang('suspended_sale_loaded');
                    $this->data['customer'] = $this->pos_model->getCompanyByID($suspended_sale->customer_id);
                    $this->data['reference_note'] = $suspended_sale->suspend_note;
                } else {
                    $this->session->set_flashdata('error', lang("bill_x_found"));
                    redirect("pos");
                }
            } else {
                $this->data['customer'] = $this->pos_model->getCompanyByID($this->pos_settings->default_customer);
                $this->data['reference_note'] = NULL;
            }

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['message'] = isset($this->data['message']) ? $this->data['message'] : $this->session->flashdata('message');

            $this->data['biller'] = $this->site->getCompanyByID($this->pos_settings->default_biller);
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['tip_rates'] = $this->site->getAllTipRates();
            $this->data['user'] = $this->site->getUser();
            $this->data["tcp"] = $this->pos_model->products_count($this->pos_settings->default_category);
            $this->data['products'] = $this->ajaxproducts($this->pos_settings->default_category);
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['brands'] = $this->site->getAllBrands();
            $this->data['subcategories'] = $this->site->getSubCategories($this->pos_settings->default_category);
            $this->data['pos_settings'] = $this->pos_settings;

            $waiters = $this->restaurant->getWaiters();
            $this->data['all_waiters'] = $waiters;

            foreach($waiters as $waiter){
                $this->data['waiters'][$waiter->id] = "{$waiter->first_name} {$waiter->last_name}";
            }

            if($this->data['user']->only_tables_taken){
                $tables = $this->restaurant->getTablesTaken();
            } else {
                $tables = $this->restaurant->get_tables();
            }

            $this->data['tables'][0] = lang('no_table');

            foreach($tables as $table){
                if(!empty($waiters) && $table->waiter != null ){
                    if($table->waiter == 0 && $table->status != 0){
                        $table_name = lang('table') . " : {$table->name} (" . lang('no_waiter') . ")";
                    }else if($table->waiter != 0){
                        $table_name = lang('table') . " : {$table->name} ({$this->data['waiters'][$table->waiter]})";
                    }else{
                        $table_name = lang('table')." : {$table->name}";
                    }
                }
                else{
                    $table_name = lang('table')." : {$table->name}";
                }
                $this->data['tables'][$table->id] = $table_name;
//                $this->data['tables'][$table->id] = (isset($this->data['waiters'][$table->waiter])) ? lang('table')." : {$table->name} ({$this->data['waiters'][$table->waiter]})" : lang('table')." : {$table->name}";
            }
            ///json payments means
            $payment_means= file_get_contents('app/json/payment_means.json');
            $this->data['payment_mean']=json_decode($payment_means,true);
            $this->load->view($this->theme . 'pos/add', $this->data);
        }
    }


    public function view_bill()
    {
        $this->sma->checkPermissions('index');
        $this->data['tax_rates'] = $this->site->getAllTaxRates();
        $this->load->view($this->theme . 'pos/view_bill', $this->data);
    }

    public function stripe_balance()
    {
        if (!$this->Owner) {
            return FALSE;
        }
        $this->load->model('stripe_payments');

        return $this->stripe_payments->get_balance();
    }

    public function paypal_balance()
    {
        if (!$this->Owner) {
            return FALSE;
        }
        $this->load->model('paypal_payments');

        return $this->paypal_payments->get_balance();
    }

    public function registers()
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['registers'] = $this->pos_model->getOpenRegisters();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('pos'), 'page' => lang('pos')), array('link' => '#', 'page' => lang('open_registers')));
        $meta = array('page_title' => lang('open_registers'), 'bc' => $bc);
        $this->page_construct('pos/registers', $meta, $this->data);
    }

    public function open_register()
    {
        $this->sma->checkPermissions('index');
        $this->form_validation->set_rules('cash_in_hand', lang("cash_in_hand"), 'trim|required|numeric');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'date' => date('Y-m-d H:i:s'),
                'cash_in_hand' => $this->input->post('cash_in_hand'),
                'user_id'      => $this->session->userdata('user_id'),
                'status'       => 'open',
                );
        }
        if ($this->form_validation->run() == TRUE && $this->pos_model->openRegister($data)) {
            $this->session->set_flashdata('message', lang("welcome_to_pos"));
            redirect("pos");
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('open_register')));
            $meta = array('page_title' => lang('open_register'), 'bc' => $bc);
            $this->page_construct('pos/open_register', $meta, $this->data);
        }
    }

    public function close_register($user_id = NULL)
    {
        $this->sma->checkPermissions('index');
        if (!$this->Owner && !$this->Admin) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->form_validation->set_rules('total_cash', lang("total_cash"), 'trim|required|numeric');
        $this->form_validation->set_rules('total_cheques', lang("total_cheques"), 'trim|required|numeric');
        $this->form_validation->set_rules('total_cc_slips', lang("total_cc_slips"), 'trim|required|numeric');

        if ($this->form_validation->run() == TRUE) {
            if ($this->Owner || $this->Admin) {
                $user_register = $user_id ? $this->pos_model->registerData($user_id) : NULL;
                $rid = $user_register ? $user_register->id : $this->session->userdata('register_id');
                $user_id = $user_register ? $user_register->user_id : $this->session->userdata('user_id');
            } else {
                $rid = $this->session->userdata('register_id');
                $user_id = $this->session->userdata('user_id');
            }
            $data = array(
                'closed_at'                => date('Y-m-d H:i:s'),
                'total_cash'               => $this->input->post('total_cash'),
                'total_cheques'            => $this->input->post('total_cheques'),
                'total_cc_slips'           => $this->input->post('total_cc_slips'),
                'total_cash_submitted'     => $this->input->post('total_cash_submitted'),
                'total_cheques_submitted'  => $this->input->post('total_cheques_submitted'),
                'total_cc_slips_submitted' => $this->input->post('total_cc_slips_submitted'),
                'note'                     => $this->input->post('note'),
                'status'                   => 'close',
                'transfer_opened_bills'    => $this->input->post('transfer_opened_bills'),
                'closed_by'                => $this->session->userdata('user_id'),
                );
        } elseif ($this->input->post('close_register')) {
            $this->session->set_flashdata('error', (validation_errors() ? validation_errors() : $this->session->flashdata('error')));
            redirect("pos");
        }

        if ($this->form_validation->run() == TRUE && $this->pos_model->closeRegister($rid, $user_id, $data)) {
            $this->session->set_flashdata('message', lang("register_closed"));
            $register_data = array('register_id' => null, 'cash_in_hand' => 0.0000, 'register_open_time' => null);
            $this->session->set_userdata($register_data);
            redirect("welcome");
        } else {
            if ($this->Owner || $this->Admin) {
                $user_register = $user_id ? $this->pos_model->registerData($user_id) : NULL;
                $register_open_time = $user_register ? $user_register->date : NULL;
                $this->data['cash_in_hand'] = $user_register ? $user_register->cash_in_hand : NULL;
                $this->data['register_open_time'] = $user_register ? $register_open_time : NULL;
            } else {
                $register_open_time = $this->session->userdata('register_open_time');
                $this->data['cash_in_hand'] = NULL;
                $this->data['register_open_time'] = NULL;
            }
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['ccsales'] = $this->pos_model->getRegisterCCSales($register_open_time, $user_id);
            $this->data['cashsales'] = $this->pos_model->getRegisterCashSales($register_open_time, $user_id);
            $this->data['chsales'] = $this->pos_model->getRegisterChSales($register_open_time, $user_id);
            $this->data['pppsales'] = $this->pos_model->getRegisterPPPSales($register_open_time, $user_id);
            $this->data['taxsalesCash'] = $this->pos_model->getRegisterTaxSales($register_open_time, 'cash');
            $this->data['taxsalesCC'] = $this->pos_model->getRegisterTaxSales($register_open_time, 'CC');
            $this->data['taxsales'] = $this->pos_model->getRegisterTaxSales($register_open_time);
            $this->data['discountsales'] = $this->pos_model->getRegisterDiscountSales($register_open_time);
            $this->data['stripesales'] = $this->pos_model->getRegisterStripeSales($register_open_time, $user_id);
            $this->data['authorizesales'] = $this->pos_model->getRegisterAuthorizeSales($register_open_time, $user_id);
            $this->data['totalsales'] = $this->pos_model->getRegisterSales($register_open_time, $user_id);
            $this->data['refunds'] = $this->pos_model->getRegisterRefunds($register_open_time, $user_id);
            $this->data['cashrefunds'] = $this->pos_model->getRegisterCashRefunds($register_open_time, $user_id);
            $this->data['expenses'] = $this->pos_model->getRegisterExpenses($register_open_time, $user_id);
            $this->data['tipsales'] = $this->pos_model->getRegisterTipSales($register_open_time, $user_id);
            $this->data['users'] = $this->pos_model->getUsers($user_id);
            $this->data['suspended_bills'] = $this->pos_model->getSuspendedsales($user_id);
            $this->data['user_id'] = $user_id;
            $this->data['modal_js'] = $this->site->modal_js();

            if($this->Settings->tax1){
                $this->data['products_tax'] = $this->sma->get_products_tax($register_open_time, null, $user_id);
            }

            $this->load->view($this->theme . 'pos/close_register', $this->data);
        }
    }

    public function getProductDataByCode($code = NULL, $warehouse_id = NULL)
    {
        $this->sma->checkPermissions('index');
        if ($this->input->get('code')) {
            $code = $this->input->get('code', TRUE);
        }
        if ($this->input->get('warehouse_id')) {
            $warehouse_id = $this->input->get('warehouse_id', TRUE);
        }
        if ($this->input->get('customer_id')) {
            $customer_id = $this->input->get('customer_id', TRUE);
        }
        if (!$code) {
            echo NULL;
            die();
        }
        $warehouse = $this->site->getWarehouseByID($warehouse_id);
        $customer = $this->site->getCompanyByID($customer_id);
        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);
        $row = $this->pos_model->getWHProduct($code, $warehouse_id);
        $option = false;
        if ($row) {
            unset($row->cost, $row->details, $row->product_details, $row->image, $row->barcode_symbology, $row->cf1, $row->cf2, $row->cf3, $row->cf4, $row->cf5, $row->cf6, $row->supplier1price, $row->supplier2price, $row->cfsupplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1, $row->supplier2, $row->supplier3, $row->supplier4, $row->supplier5, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);
            $row->item_tax_method = $row->tax_method;
            $row->qty = 1;
            $row->discount = '0';
            $row->serial = '';
            $options = $this->pos_model->getProductOptions($row->id, $warehouse_id);
            if ($options) {
                $opt = current($options);
                if (!$option) {
                    $option = $opt->id;
                }
            } else {
                $opt = json_decode('{}');
                $opt->price = 0;
            }
            $row->option = $option;
//            $row->quantity = 0;
//            $pis = $this->site->getPurchasedItems($row->id, $warehouse_id, $row->option);
//            if ($pis) {
//                foreach ($pis as $pi) {
//                    //$row->quantity += $pi->quantity_balance;
//                }
//            }
            if ($row->type == 'standard' && $row->quantity < 1) {
                echo NULL; die();
            }
            if ($options) {
                $option_quantity = 0;
                foreach ($options as $option) {
                    $pis = $this->site->getPurchasedItems($row->id, $warehouse_id, $row->option);
                    if ($pis) {
                        foreach ($pis as $pi) {
                            $option_quantity += $pi->quantity_balance;
                        }
                    }
                    if ($option->quantity > $option_quantity) {
                        $option->quantity = $option_quantity;
                    }
                }
            }
            if ($this->Settings->status_premium_price && !empty($row->premium_price)) {
                $row->price = $row->premium_price;
            }
            if ($row->promotion) {
                $row->price = $row->promo_price;
            } elseif ($customer->price_group_id) {
                if ($pr_group_price = $this->site->getProductGroupPrice($row->id, $customer->price_group_id)) {
                    $row->price = $pr_group_price->price;
                }
            } elseif ($warehouse->price_group_id) {
                if ($pr_group_price = $this->site->getProductGroupPrice($row->id, $warehouse->price_group_id)) {
                    $row->price = $pr_group_price->price;
                }
            }
            $row->price = $row->price + (($row->price * $customer_group->percent) / 100);
            $row->real_unit_price = $row->price;
            $row->base_quantity = 1;
            $row->base_unit = $row->unit;
            $row->base_unit_price = $row->price;
            $row->unit = $row->sale_unit ? $row->sale_unit : $row->unit;
            $combo_items = false;
            if ($row->type == 'combo') {
                $combo_items = $this->pos_model->getProductComboItems($row->id, $warehouse_id);
            }
            $units = $this->site->getUnitsByBUID($row->base_unit);
            $tax_rate = $this->site->getTaxRateByID($row->tax_rate);

            $pr = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'category' => $row->category_id, 'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options);

            $this->sma->send_json($pr);
        } else {
            echo NULL;
        }
    }

    public function ajaxproducts($category_id = NULL, $brand_id = NULL)
    {
        $this->sma->checkPermissions('index');
        if ($this->input->get('brand_id')) {
            $brand_id = $this->input->get('brand_id');
        }
        if ($this->input->get('category_id')) {
            $category_id = $this->input->get('category_id');
        } else {
            $category_id = $this->pos_settings->default_category;
        }
        if ($this->input->get('subcategory_id')) {
            $subcategory_id = $this->input->get('subcategory_id');
        } else {
            $subcategory_id = NULL;
        }
        if ($this->input->get('per_page') == 'n') {
            $page = 0;
        } else {
            $page = $this->input->get('per_page');
        }

        $this->load->library("pagination");

        $config = array();
        $config["base_url"] = base_url() . "pos/ajaxproducts";
        $config["total_rows"] = $this->pos_model->products_count($category_id, $subcategory_id, $brand_id);
        $config["per_page"] = $this->pos_settings->pro_limit;
        $config['prev_link'] = FALSE;
        $config['next_link'] = FALSE;
        $config['display_pages'] = FALSE;
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;

        $this->pagination->initialize($config);

        $products = $this->pos_model->fetch_products($category_id, $config["per_page"], $page, $subcategory_id, $brand_id);
        $pro = 1;
        $prods = '<div>';
        if (!empty($products)) {
            foreach ($products as $product) {
                $count = $product->id;
                if ($count < 10) {
                    $count = "0" . ($count / 100) * 100;
                }
                if ($category_id < 10) {
                    $category_id = "0" . ($category_id / 100) * 100;
                }

                $prods .= "<button id=\"product-" . $category_id . $count . "\" type=\"button\" value='" . $product->code . "' title=\"" . $product->name . "\" class=\"btn-prni btn-" . $this->pos_settings->product_button_color . " product pos-tip\" data-container=\"body\"><img src=\"" . base_url() . "assets/uploads/thumbs/" . $product->image . "\" alt=\"" . $product->name . "\" style='width:" . $this->Settings->twidth . "px;height:" . $this->Settings->theight . "px;' class='img-rounded' /><span>" . character_limiter($product->name, 40) . "</span></button>";

                $pro++;
            }
        }
        $prods .= "</div>";

        if ($this->input->get('per_page')) {
            echo $prods;
        } else {
            return $prods;
        }
    }

    public function ajaxcategorydata($category_id = NULL)
    {
        $this->sma->checkPermissions('index');
        if ($this->input->get('category_id')) {
            $category_id = $this->input->get('category_id');
        } else {
            $category_id = $this->pos_settings->default_category;
        }

        $subcategories = $this->site->getSubCategories($category_id);
        $scats = '';
        if ($subcategories) {
            foreach ($subcategories as $category) {
                $scats .= "<button id=\"subcategory-" . $category->id . "\" type=\"button\" value='" . $category->id . "' class=\"btn-prni subcategory\" ><img src=\"assets/uploads/thumbs/" . ($category->image ? $category->image : 'no_image.png') . "\" style='width:" . $this->Settings->twidth . "px;height:" . $this->Settings->theight . "px;' class='img-rounded img-thumbnail' /><span>" . $category->name . "</span></button>";
            }
        }

        $products = $this->ajaxproducts($category_id);

        if (!($tcp = $this->pos_model->products_count($category_id))) {
            $tcp = 0;
        }

        $this->sma->send_json(array('products' => $products, 'subcategories' => $scats, 'tcp' => $tcp));
    }

    public function ajaxbranddata($brand_id = NULL)
    {
        $this->sma->checkPermissions('index');
        if ($this->input->get('brand_id')) {
            $brand_id = $this->input->get('brand_id');
        }

        $products = $this->ajaxproducts(FALSE, $brand_id);

        if (!($tcp = $this->pos_model->products_count(FALSE, FALSE, $brand_id))) {
            $tcp = 0;
        }

        $this->sma->send_json(array('products' => $products, 'tcp' => $tcp));
    }

    /* ------------------------------------------------------------------------------------ */

    public function view($sale_id = NULL, $modal = NULL)
    {
        $this->sma->checkPermissions('index');
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }
        $this->load->helper('text');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');
        $inv = $this->pos_model->getInvoiceByID($sale_id);
        $inv->bill_no = str_replace("SALE/POS/","",$inv->reference_no);

        if (!$this->session->userdata('view_right')) {
            $this->sma->view_rights($inv->created_by, true);
        }
        $this->data['rows'] = $this->pos_model->getAllInvoiceItems($sale_id);
        $biller_id = $inv->biller_id;
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer'] = $this->pos_model->getCompanyByID($customer_id);
        $this->data['payments'] = $this->pos_model->getInvoicePayments($sale_id);
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['barcode'] = $this->barcode($inv->reference_no, 'code128', 30);
        $this->data['return_sale'] = $inv->return_id ? $this->pos_model->getInvoiceByID($inv->return_id) : NULL;
        $this->data['return_rows'] = $inv->return_id ? $this->pos_model->getAllInvoiceItems($inv->return_id) : NULL;
        $this->data['return_payments'] = $this->data['return_sale'] ? $this->pos_model->getInvoicePayments($this->data['return_sale']->id) : NULL;

        // set table local storage
        $inv->table_name = $this->restaurant->get_table($inv->id_table)->name;

        // set waiter name local storage
        $inv->waiter_name = $this->site->getUser($inv->id_waiter)->first_name . " " . $this->site->getUser($inv->id_waiter)->last_name;

        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;
        $this->data['modal'] = $modal;
        $this->data['page_title'] = $this->lang->line("invoice");
        $this->data['userBill']= $this->pos_model->getUserBill($inv->created_by);

        $this->load->view($this->theme . 'pos/view', $this->data);
    }

    public function register_details()
    {
        $this->sma->checkPermissions('index');
        $register_open_time = $this->session->userdata('register_open_time');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['ccsales'] = $this->pos_model->getRegisterCCSales($register_open_time);
        $this->data['cashsales'] = $this->pos_model->getRegisterCashSales($register_open_time);
        $this->data['taxsalesCash'] = $this->pos_model->getRegisterTaxSales($register_open_time, 'cash');
        $this->data['taxsalesCC'] = $this->pos_model->getRegisterTaxSales($register_open_time, 'CC');
        $this->data['taxsales'] = $this->pos_model->getRegisterTaxSales($register_open_time);
        $this->data['discountsales'] = $this->pos_model->getRegisterDiscountSales($register_open_time);
        $this->data['chsales'] = $this->pos_model->getRegisterChSales($register_open_time);
        $this->data['pppsales'] = $this->pos_model->getRegisterPPPSales($register_open_time);
        $this->data['stripesales'] = $this->pos_model->getRegisterStripeSales($register_open_time);
        $this->data['authorizesales'] = $this->pos_model->getRegisterAuthorizeSales($register_open_time);
        $this->data['totalsales'] = $this->pos_model->getRegisterSales($register_open_time);
        $this->data['refunds'] = $this->pos_model->getRegisterRefunds($register_open_time);
        $this->data['expenses'] = $this->pos_model->getRegisterExpenses($register_open_time);
        $this->data['tipsales'] = $this->pos_model->getRegisterTipSales($register_open_time);

        if($this->Settings->tax1){
            $this->data['products_tax'] = $this->sma->get_products_tax($register_open_time);
        }

        $this->load->view($this->theme . 'pos/register_details', $this->data);
    }

    public function daily_details()
    {
        $this->sma->checkPermissions('index');
        $register_open_time = $this->session->userdata('register_open_time');
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['ccsales'] = $this->pos_model->getRegisterCCSales($register_open_time);
        $this->data['cashsales'] = $this->pos_model->getRegisterCashSales($register_open_time);
        $this->data['taxsalesCash'] = $this->pos_model->getRegisterTaxSales($register_open_time, 'cash');
        $this->data['taxsalesCC'] = $this->pos_model->getRegisterTaxSales($register_open_time, 'CC');
        $this->data['taxsales'] = $this->pos_model->getRegisterTaxSales($register_open_time);
        $this->data['discountsales'] = $this->pos_model->getRegisterDiscountSales($register_open_time);
        $this->data['chsales'] = $this->pos_model->getRegisterChSales($register_open_time);
        $this->data['pppsales'] = $this->pos_model->getRegisterPPPSales($register_open_time);
        $this->data['stripesales'] = $this->pos_model->getRegisterStripeSales($register_open_time);
        $this->data['authorizesales'] = $this->pos_model->getRegisterAuthorizeSales($register_open_time);
        $this->data['totalsales'] = $this->pos_model->getRegisterSales($register_open_time);
        $this->data['refunds'] = $this->pos_model->getRegisterRefunds($register_open_time);
        $this->data['expenses'] = $this->pos_model->getRegisterExpenses($register_open_time);
        $this->data['biller'] = $this->site->getCompanyByID($this->pos_settings->default_biller);
        $this->data['tipsales'] = $this->pos_model->getRegisterTipSales($register_open_time);
        $get_references = $this->pos_model->getRegisterReferences($register_open_time);

        if($this->Settings->tax1){
            $this->data['products_tax'] = $this->sma->get_products_tax($register_open_time);
        }

        // get reference by user
        $reference_list = array();
        $sales = array();
        $total_cash = 0;
        $total_CC = 0;

        if(!empty($get_references)){
            foreach ($get_references as $reference_key => $reference_value){
                $number = str_replace("SALE/POS/","", $reference_value->reference_no);
                $reference_list[] = $number;

                if(isset($sales[$reference_value->reference_no])){
                    if(strcmp($reference_value->paid_by, "cash") == 0){
                        $total_cash += $reference_value->amount;

                        if(isset($sales[$reference_value->reference_no]['paymentCash'])){
                            $new_total = $sales[$reference_value->reference_no]['paymentCash']['total'];
                            $sales[$reference_value->reference_no]['paymentCash'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount + $new_total);
                        }else{
                            $sales[$reference_value->reference_no]['paymentCash'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount);
                        }
                    }else if(strcmp($reference_value->paid_by, "CC") == 0){
                        $total_CC += $reference_value->amount;

                        if(isset($sales[$reference_value->reference_no]['paymentCC'])){
                            $new_total = $sales[$reference_value->reference_no]['paymentCC']['total'];
                            $sales[$reference_value->reference_no]['paymentCC'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount + $new_total);
                        }else{
                            $sales[$reference_value->reference_no]['paymentCC'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount);
                        }
                    }
                }else{
                    $sales[$reference_value->reference_no]['id'] = $reference_value->id;
                    $sales[$reference_value->reference_no]['reference'] = $number;

                    if(strcmp($reference_value->paid_by, "cash") == 0){
                        $total_cash += $reference_value->amount;
                        $sales[$reference_value->reference_no]['paymentCash'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount);

                    }else if(strcmp($reference_value->paid_by, "CC") == 0){
                        $total_CC += $reference_value->amount;
                        $sales[$reference_value->reference_no]['paymentCC'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount);

                    }

                }
            }
        }

        $this->data['min_reference'] = $reference_list ? min($reference_list) : 0;
        $this->data['max_reference'] = $reference_list ? max($reference_list) : 0;
        $this->data['num_reference'] = count($sales);
        $this->data['sales'] = $sales;
        $this->data['total_cash'] = $total_cash;
        $this->data['total_CC'] = $total_CC;

        $this->load->view($this->theme . 'pos/daily_details', $this->data);
    }

    public function daily_details_filter()
    {
        $this->sma->checkPermissions('index');

        $date = date('Y-m-d H:i:s', strtotime(str_replace('/','-',$this->input->get('date'))));
        $date_end = date('Y-m-d H:i:s', strtotime(str_replace('/','-',$this->input->get('date_end'))));

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['ccsales'] = $this->pos_model->getRegisterCCSales($date, null, $date_end);
        $this->data['cashsales'] = $this->pos_model->getRegisterCashSales($date, null, $date_end);
        $this->data['taxsalesCash'] = $this->pos_model->getRegisterTaxSales($date, 'cash', null, $date_end);
        $this->data['taxsalesCC'] = $this->pos_model->getRegisterTaxSales($date, 'CC', null, $date_end);
        $this->data['taxsales'] = $this->pos_model->getRegisterTaxSales($date, null ,null, $date_end);
        $this->data['discountsales'] = $this->pos_model->getRegisterDiscountSales($date, null, $date_end);
        $this->data['totalsales'] = $this->pos_model->getRegisterSales($date, null, $date_end);
        $this->data['biller'] = $this->site->getCompanyByID($this->pos_settings->default_biller);
        $this->data['tipsales'] = $this->pos_model->getRegisterTipSales($date, null, $date_end);
        $get_references = $this->pos_model->getRegisterReferences($date, null, $date_end);

        if($this->Settings->tax1){
            $this->data['products_tax'] = $this->sma->get_products_tax($register_open_time);
        }

        // get reference by user
        $reference_list = array();
        $sales = array();
        $total_cash = 0;
        $total_CC = 0;

        if(!empty($get_references)){
            foreach ($get_references as $reference_key => $reference_value){
                $number = str_replace("SALE/POS/","", $reference_value->reference_no);
                $reference_list[] = $number;

                if(isset($sales[$reference_value->reference_no])){
                    if(strcmp($reference_value->paid_by, "cash") == 0){
                        $total_cash += $reference_value->amount;

                        if(isset($sales[$reference_value->reference_no]['paymentCash'])){
                            $new_total = $sales[$reference_value->reference_no]['paymentCash']['total'];
                            $sales[$reference_value->reference_no]['paymentCash'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount + $new_total);
                        }else{
                            $sales[$reference_value->reference_no]['paymentCash'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount);
                        }
                    }else if(strcmp($reference_value->paid_by, "CC") == 0){
                        $total_CC += $reference_value->amount;

                        if(isset($sales[$reference_value->reference_no]['paymentCC'])){
                            $new_total = $sales[$reference_value->reference_no]['paymentCC']['total'];
                            $sales[$reference_value->reference_no]['paymentCC'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount + $new_total);
                        }else{
                            $sales[$reference_value->reference_no]['paymentCC'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount);
                        }
                    }
                }else{
                    $sales[$reference_value->reference_no]['id'] = $reference_value->id;
                    $sales[$reference_value->reference_no]['reference'] = $number;

                    if(strcmp($reference_value->paid_by, "cash") == 0){
                        $total_cash += $reference_value->amount;
                        $sales[$reference_value->reference_no]['paymentCash'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount);

                    }else if(strcmp($reference_value->paid_by, "CC") == 0){
                        $total_CC += $reference_value->amount;
                        $sales[$reference_value->reference_no]['paymentCC'] = array('paid_by' => $reference_value->paid_by, 'total' => $reference_value->amount);

                    }

                }
            }
        }

        $this->data['min_reference'] = $reference_list ? min($reference_list) : 0;
        $this->data['max_reference'] = $reference_list ? max($reference_list) : 0;
        $this->data['num_reference'] = count($sales);
        $this->data['sales'] = $sales;
        $this->data['total_cash'] = $total_cash;
        $this->data['total_CC'] = $total_CC;

        echo json_encode($this->data);
    }

    public function today_sale()
    {
        if (!$this->Owner && !$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            $this->sma->md();
        }

        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['ccsales'] = $this->pos_model->getTodayCCSales();
        $this->data['cashsales'] = $this->pos_model->getTodayCashSales();
        $this->data['chsales'] = $this->pos_model->getTodayChSales();
        $this->data['pppsales'] = $this->pos_model->getTodayPPPSales();
        $this->data['stripesales'] = $this->pos_model->getTodayStripeSales();
        $this->data['authorizesales'] = $this->pos_model->getTodayAuthorizeSales();
        $this->data['totalsales'] = $this->pos_model->getTodaySales();
        $this->data['refunds'] = $this->pos_model->getTodayRefunds();
        $this->data['expenses'] = $this->pos_model->getTodayExpenses();
        $this->load->view($this->theme . 'pos/today_sale', $this->data);
    }

    public function check_pin()
    {
        $pin = $this->input->post('pw', TRUE);
        if ($pin == $this->pos_pin) {
            $this->sma->send_json(array('res' => 1));
        }
        $this->sma->send_json(array('res' => 0));
    }

    public function barcode($text = NULL, $bcs = 'code128', $height = 50)
    {
        return site_url('products/gen_barcode/' . $text . '/' . $bcs . '/' . $height);
    }

    public function settings()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('pro_limit', $this->lang->line('pro_limit'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('pin_code', $this->lang->line('delete_code'), 'numeric');
        $this->form_validation->set_rules('category', $this->lang->line('default_category'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('customer', $this->lang->line('default_customer'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('biller', $this->lang->line('default_biller'), 'required|is_natural_no_zero');

        if ($this->form_validation->run() == TRUE) {

            $data = array(
                'pro_limit'                 => $this->input->post('pro_limit'),
                'pin_code'                  => $this->input->post('pin_code') ? $this->input->post('pin_code') : NULL,
                'default_category'          => $this->input->post('category'),
                'default_customer'          => $this->input->post('customer'),
                'default_biller'            => $this->input->post('biller'),
                'display_time'              => $this->input->post('display_time'),
                'receipt_printer'           => $this->input->post('receipt_printer'),
                'cash_drawer_codes'         => $this->input->post('cash_drawer_codes'),
                'cf_title1'                 => $this->input->post('cf_title1'),
                'cf_title2'                 => $this->input->post('cf_title2'),
                'cf_value1'                 => $this->input->post('cf_value1'),
                'cf_value2'                 => $this->input->post('cf_value2'),
                'focus_add_item'            => $this->input->post('focus_add_item'),
                'add_manual_product'        => $this->input->post('add_manual_product'),
                'customer_selection'        => $this->input->post('customer_selection'),
                'add_customer'              => $this->input->post('add_customer'),
                'toggle_category_slider'    => $this->input->post('toggle_category_slider'),
                'toggle_subcategory_slider' => $this->input->post('toggle_subcategory_slider'),
                'toggle_brands_slider'      => $this->input->post('toggle_brands_slider'),
                'cancel_sale'               => $this->input->post('cancel_sale'),
                'suspend_sale'              => $this->input->post('suspend_sale'),
                'print_items_list'          => $this->input->post('print_items_list'),
                'finalize_sale'             => $this->input->post('finalize_sale'),
                'today_sale'                => $this->input->post('today_sale'),
                'open_hold_bills'           => $this->input->post('open_hold_bills'),
                'close_register'            => $this->input->post('close_register'),
                'tooltips'                  => $this->input->post('tooltips'),
                'keyboard'                  => $this->input->post('keyboard'),
                'pos_printers'              => $this->input->post('pos_printers'),
                'java_applet'               => $this->input->post('enable_java_applet'),
                'product_button_color'      => $this->input->post('product_button_color'),
                'paypal_pro'                => $this->input->post('paypal_pro'),
                'stripe'                    => $this->input->post('stripe'),
                'authorize'                 => $this->input->post('authorize'),
                'rounding'                  => $this->input->post('rounding'),
                'item_order'                => $this->input->post('item_order'),
                'after_sale_page'           => $this->input->post('after_sale_page'),
                'default_waiter'            => $this->input->post('default_waiter')
            );
            $payment_config = array(
                'APIUsername'            => $this->input->post('APIUsername'),
                'APIPassword'            => $this->input->post('APIPassword'),
                'APISignature'           => $this->input->post('APISignature'),
                'stripe_secret_key'      => $this->input->post('stripe_secret_key'),
                'stripe_publishable_key' => $this->input->post('stripe_publishable_key'),
                'api_login_id'           => $this->input->post('api_login_id'),
                'api_transaction_key'    => $this->input->post('api_transaction_key'),
            );
        } elseif ($this->input->post('update_settings')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("pos/settings");
        }

        if ($this->form_validation->run() == TRUE && $this->pos_model->updateSetting($data)) {
            if ($this->write_payments_config($payment_config)) {
                $this->session->set_flashdata('message', $this->lang->line('pos_setting_updated'));
                redirect("pos/settings");
            } else {
                $this->session->set_flashdata('error', $this->lang->line('pos_setting_updated_payment_failed'));
                redirect("pos/settings");
            }
        } else {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $this->data['pos'] = $this->pos_model->getSetting();
            $this->data['categories'] = $this->site->getAllCategories();
            //$this->data['customer'] = $this->pos_model->getCompanyByID($this->pos_settings->default_customer);
            $this->data['billers'] = $this->pos_model->getAllBillerCompanies();
            $this->config->load('payment_gateways');
            $this->data['stripe_secret_key'] = $this->config->item('stripe_secret_key');
            $this->data['stripe_publishable_key'] = $this->config->item('stripe_publishable_key');
            $authorize = $this->config->item('authorize');
            $this->data['api_login_id'] = $authorize['api_login_id'];
            $this->data['api_transaction_key'] = $authorize['api_transaction_key'];
            $this->data['APIUsername'] = $this->config->item('APIUsername');
            $this->data['APIPassword'] = $this->config->item('APIPassword');
            $this->data['APISignature'] = $this->config->item('APISignature');
            $this->data['paypal_balance'] = NULL; // $this->pos_settings->paypal_pro ? $this->paypal_balance() : NULL;
            $this->data['stripe_balance'] = NULL; // $this->pos_settings->stripe ? $this->stripe_balance() : NULL;
            $this->data['waiters'] = $this->pos_model->getWaiters();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('pos_settings')));
            $meta = array('page_title' => lang('pos_settings'), 'bc' => $bc);
            $this->page_construct('pos/settings', $meta, $this->data);
        }
    }

    public function write_payments_config($config)
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $file_contents = file_get_contents('./assets/config_dumps/payment_gateways.php');
        $output_path = APPPATH . 'config/payment_gateways.php';
        $this->load->library('parser');
        $parse_data = array(
            'APIUsername'            => $config['APIUsername'],
            'APIPassword'            => $config['APIPassword'],
            'APISignature'           => $config['APISignature'],
            'stripe_secret_key'      => $config['stripe_secret_key'],
            'stripe_publishable_key' => $config['stripe_publishable_key'],
            'api_login_id'           => $config['api_login_id'],
            'api_transaction_key'    => $config['api_transaction_key'],
        );
        $new_config = $this->parser->parse_string($file_contents, $parse_data);

        $handle = fopen($output_path, 'w+');
        @chmod($output_path, 0777);

        if (is_writable($output_path)) {
            if (fwrite($handle, $new_config)) {
                @chmod($output_path, 0644);

                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function opened_bills($per_page = 0)
    {
        $this->load->library('pagination');

        //$this->table->set_heading('Id', 'The Title', 'The Content');
        if ($this->input->get('per_page')) {
            $per_page = $this->input->get('per_page');
        }

        $config['base_url'] = site_url('pos/opened_bills');
        $config['total_rows'] = $this->pos_model->bills_count();
        $config['per_page'] = 6;
        $config['num_links'] = 3;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        $data['r'] = TRUE;
        $bills = $this->pos_model->fetch_bills($config['per_page'], $per_page,true,'id_table');

        if (!empty($bills)) {
            $html = "";
            $html .= '<ul class="ob">';
            foreach ($bills as $bill) {

                $table = $this->Tables_model->get_table($bill->id_table);
                $status = ($table->status == 2) ? 'success' : 'info';

                $html .= "<li class='li-suspended-sale'>";

                if ($this->sma->is_admin()) {
                    $html .= '<div id="delete-sale' . $bill->id . '" onclick="delete_sale(' . $bill->id . ')"><i class="fa fa-2x delete-suspended-sale">&times;</i></div>';
                }

                $html .= "<button type='button' class='btn btn-{$status} sus_sale' "
                        . "id='{$bill->id}'>"
                        . "<strong>".lang('table')." : {$table->name}</strong>"
                        . "<br>".lang('date')." : {$bill->date}"
                        . "<br>".lang('waiter')." : {$this->site->getUser($bill->id_waiter)->first_name}<br>"
                        . "<p>{$bill->suspend_note}</p></button></li>";
            }
            $html .= '</ul>';
        } else {
            $html = "<h3>" . lang('no_opeded_bill') . "</h3><p>&nbsp;</p>";
            $data['r'] = FALSE;
        }

        $data['html'] = $html;

        $pending_orders = $this->Pos_model->getAllSuspendedSales();

        $suspend_sales_total = "";
        if(!empty($suspend_sales_total)){
            foreach ($pending_orders as $order){
                $order_items = $this->Pos_model->getSuspendedSaleItems($order->id);
                if(!empty($order_items)){
                    foreach ($order_items as $order_item) {
                        if ($order_item->product_status != "pending") {
                            $suspend_sales_total += $order_item->subtotal;
                        }
                    }
                }
            }
        }

        //suspend sales total
        $data['suspended_total'] = $suspend_sales_total;

        $data['page'] = $this->pagination->create_links();
        echo $this->load->view($this->theme . 'pos/opened', $data, TRUE);

    }

    public function delete($id = NULL)
    {

        $this->sma->checkPermissions('index');

        if ($this->pos_model->deleteBill($id)) {
            echo lang("suspended_sale_deleted");
        }
    }

    public function email_receipt($sale_id = NULL)
    {
        $this->sma->checkPermissions('index');
        if ($this->input->post('id')) {
            $sale_id = $this->input->post('id');
        } else {
            die();
        }
        if ($this->input->post('email')) {
            $to = $this->input->post('email');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['message'] = $this->session->flashdata('message');

        $this->data['rows'] = $this->pos_model->getAllInvoiceItems($sale_id);
        $inv = $this->pos_model->getInvoiceByID($sale_id);
        $biller_id = $inv->biller_id;
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->pos_model->getCompanyByID($biller_id);
        $this->data['customer'] = $this->pos_model->getCompanyByID($customer_id);

        $this->data['payments'] = $this->pos_model->getInvoicePayments($sale_id);
        $this->data['pos'] = $this->pos_model->getSetting();
        $this->data['barcode'] = $this->barcode($inv->reference_no, 'code128', 30);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;
        $this->data['page_title'] = $this->lang->line("invoice");
        $this->data['userBill']= $this->pos_model->getUserBill($inv->created_by);

        if (!$to) {
            $to = $this->data['customer']->email;
        }
        if (!$to) {
            $this->sma->send_json(array('msg' => $this->lang->line("no_meil_provided")));
        }
        $receipt = $this->load->view($this->theme . 'pos/email_receipt', $this->data, TRUE);

        if ($this->sma->send_email($to, 'Receipt from ' . $this->data['biller']->company, $receipt)) {
            $this->sma->send_json(array('msg' => $this->lang->line("email_sent")));
        } else {
            $this->sma->send_json(array('msg' => $this->lang->line("email_failed")));
        }

    }

    public function active()
    {
        $this->session->set_userdata('last_activity', now());
        if ((now() - $this->session->userdata('last_activity')) <= 20) {
            die('Successfully updated the last activity.');
        } else {
            die('Failed to update last activity.');
        }
    }

    public function add_payment($id = NULL)
    {
        $this->sma->checkPermissions('payments', TRUE, 'sales');
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('amount-paid', lang("amount"), 'required');
        $this->form_validation->set_rules('paid_by', lang("paid_by"), 'required');
        $this->form_validation->set_rules('userfile', lang("attachment"), 'xss_clean');
        if ($this->form_validation->run() == TRUE) {
            if ($this->input->post('paid_by') == 'deposit') {
                $sale = $this->pos_model->getInvoiceByID($this->input->post('sale_id'));
                $customer_id = $sale->customer_id;
                if ( ! $this->site->check_customer_deposit($customer_id, $this->input->post('amount-paid'))) {
                    $this->session->set_flashdata('error', lang("amount_greater_than_deposit"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $customer_id = null;
            }
            if ($this->Owner || $this->Admin) {
                $date = $this->sma->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $payment = array(
                'date'         => $date,
                'sale_id'      => $this->input->post('sale_id'),
                'reference_no' => $this->input->post('reference_no'),
                'amount'       => $this->input->post('amount-paid'),
                'paid_by'      => $this->input->post('paid_by'),
                'cheque_no'    => $this->input->post('cheque_no'),
                'cc_no'        => $this->input->post('paid_by') == 'gift_card' ? $this->input->post('gift_card_no') : $this->input->post('pcc_no'),
                'cc_holder'    => $this->input->post('pcc_holder'),
                'cc_month'     => $this->input->post('pcc_month'),
                'cc_year'      => $this->input->post('pcc_year'),
                'cc_type'      => $this->input->post('pcc_type'),
                'cc_cvv2'      => $this->input->post('pcc_ccv'),
                'note'         => $this->input->post('note'),
                'created_by'   => $this->session->userdata('user_id'),
                'type'         => 'received',
            );

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $payment['attachment'] = $photo;
            }

            //$this->sma->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == TRUE && $msg = $this->pos_model->addPayment($payment, $customer_id)) {
            if ($msg) {
                if ($msg['status'] == 0) {
                    $error = '';
                    foreach ($msg as $m) {
                        $error .= '<br>' . (is_array($m) ? print_r($m, TRUE) : $m);
                    }
                    $this->session->set_flashdata('error', '<pre>' . $error . '</pre>');
                } else {
                    $this->session->set_flashdata('message', lang("payment_added"));
                }
            } else {
                $this->session->set_flashdata('error', lang("payment_failed"));
            }
            redirect("pos/sales");
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $sale = $this->pos_model->getInvoiceByID($id);
            $this->data['inv'] = $sale;
            $this->data['payment_ref'] = $this->site->getReference('pay');
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'pos/add_payment', $this->data);
        }
    }

    public function updates()
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->form_validation->set_rules('purchase_code', lang("purchase_code"), 'required');
        $this->form_validation->set_rules('envato_username', lang("envato_username"), 'required');
        if ($this->form_validation->run() == TRUE) {
            $this->db->update('pos_settings', array('purchase_code' => $this->input->post('purchase_code', TRUE), 'envato_username' => $this->input->post('envato_username', TRUE)), array('pos_id' => 1));
            redirect('pos/updates');
        } else {
            $fields = array('version' => $this->pos_settings->version, 'code' => $this->pos_settings->purchase_code, 'username' => $this->pos_settings->envato_username, 'site' => base_url());
            $this->load->helper('update');
            $protocol = is_https() ? 'https://' : 'http://';
            $updates = get_remote_contents($protocol . 'tecdiary.com/api/v1/update/', $fields);
            $this->data['updates'] = json_decode($updates);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('updates')));
            $meta = array('page_title' => lang('updates'), 'bc' => $bc);
            $this->page_construct('pos/updates', $meta, $this->data);
        }
    }

    public function install_update($file, $m_version, $version)
    {
        if (DEMO) {
            $this->session->set_flashdata('warning', lang('disabled_in_demo'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect("welcome");
        }
        $this->load->helper('update');
        save_remote_file($file . '.zip');
        $this->sma->unzip('./files/updates/' . $file . '.zip');
        if ($m_version) {
            $this->load->library('migration');
            if (!$this->migration->latest()) {
                $this->session->set_flashdata('error', $this->migration->error_string());
                redirect("pos/updates");
            }
        }
        $this->db->update('pos_settings', array('version' => $version, 'update' => 0), array('pos_id' => 1));
        unlink('./files/updates/' . $file . '.zip');
        $this->session->set_flashdata('success', lang('update_done'));
        redirect("pos/updates");
    }

    function ajax($request = null, $param1 = null){
        if (!$this->input->is_ajax_request()) {
            // No direct script access allowed
            redirect("pos");
        }

        switch ($request) {
            case "update_tax" :

                echo $this->pos_model->updateFixedTax($param1);

                break;

            case "update_order_tip" :

                echo $this->pos_model->updateFixedTip($param1);

                break;

            case "update_tip" :

                if($this->sma->set_activated_tip($param1)){
                    echo $param1;
                }else {
                    echo FALSE;
                }

                break;

            case "load_table_info" :

                $table = $this->restaurant->get_table($param1);
                if($table){
                    echo site_url('pos/index') . "/" . $table->bill;
                }else {
                    echo FALSE;
                }

                break;

            case "change_table_info" :

                if(strcmp ($param1 , "true" ) == 0){
                    $status = true;
                }else{
                    $status = false;
                }

                $data[0] = lang('no_table');

                if($this->restaurant->updateOnlyTables($this->session->userdata('user_id'), $status)){
                    $all_waiters = $this->restaurant->getWaiters();

                    foreach($all_waiters as $waiter){
                        $waiters[$waiter->id] = "{$waiter->first_name} {$waiter->last_name}";
                    }

                    if($status){
                        $tables = $this->restaurant->getTablesTaken();
                    } else {
                        $tables = $this->restaurant->get_tables();
                    }

                    if($tables){
                        foreach($tables as $table){
                            if(!empty($waiters) && $table->waiter != null ){
                                if($table->waiter == 0 && $table->status != 0){
                                    $table_name = lang('table') . " : {$table->name} (" . lang('no_waiter') . ")";
                                }else if($table->waiter != 0){
                                    $table_name = lang('table') . " : {$table->name} ({$waiters[$table->waiter]})";
                                }else{
                                    $table_name = lang('table')." : {$table->name}";
                                }
                            }
                            else{
                                $table_name = lang('table')." : {$table->name}";
                            }
//                            $data[$table->id] = (!empty($waiters) && !empty($table->waiter)) ? lang('table') . " : {$table->name} ({$waiters[$table->waiter]})" : lang('table')." : {$table->name}";
                            $data[$table->id] = $table_name;
                        }
                    }
                }

                echo json_encode($data);

                break;

            case "charge_info_localstorage":

                $info = (object)[];

                // set table local storage
                $info->table_lang = lang('table');
                $info->table_name = $this->restaurant->get_table($param1)->name;

                // set waiter name local storage
                $info->lang_waiter_name = lang("waiter_name");
                $info->waiter_name = $this->site->getUser()->first_name . " " . $this->site->getUser()->last_name;
                $info->lang_customer = lang("customer");
                $info->customer = $this->pos_settings->default_customer;

                // set message to bill
                $info->bill_title = lang("bill");
                $info->message_bill = $this->pos_settings->cf_value1 . "<br>";
                $info_biller = $this->pos_model->getCompanyByID($this->pos_settings->default_biller);
                $info->biller = $info_biller->name . "<br>" . lang("NIT"). ": " . $info_biller->cf1 . "<br>" .$info_biller->address . " " . $info_biller->city . " " . $info_biller->postal_code . " " . $info_biller->state . " " . $info_biller->country .
                    "<br>" . lang("tel") . ": " . $info_biller->phone . "<br>";
                $info->biller_tel = lang("tel") . ": " . $info_biller->phone;
                $info->biller_logo = base_url() . 'assets/uploads/logos/' . $info_biller->logo;
                $info->biller_id = $this->pos_settings->default_biller;

                // set message to warehouse
                $info->warehouse = $this->Settings->default_warehouse;

                echo json_encode($info);

                break;

            default :
                break;
        }
    }

    function delete_suspended_sale($id_bill = null) {
        if($this->pos_model->deleteBill($id_bill)){
            redirect("pos");
        }
    }

    public function view_expenses() {

        $user_id = $this->session->userdata('user_id');

        if ($this->Owner || $this->Admin) {
                $user_register = $user_id ? $this->pos_model->registerData($user_id) : NULL;
                $register_open_time = $user_register ? $user_register->date : NULL;
                $this->data['cash_in_hand'] = $user_register ? $user_register->cash_in_hand : NULL;
                $this->data['register_open_time'] = $user_register ? $register_open_time : NULL;
            } else {
                $register_open_time = $this->session->userdata('register_open_time');
                $this->data['cash_in_hand'] = NULL;
                $this->data['register_open_time'] = NULL;
            }

        $expenses = $this->pos_model->getExpenseByCreateID($user_id, $register_open_time);

        $this->data['expenses'] = $expenses;

        $this->load->view($this->theme . 'pos/view_expenses', $this->data);
    }
    public function create_xml($billing_data=NULL)
        {

            json_encode($billing_data);

            $this->load->helper('array');
            // Separación fecha facturación y hora
            $payments_data= explode(" ", $billing_data['payments'][0]->date);
            // Separación digito de verificación NIT
            $doc_nit= explode("-",$billing_data['biller']->cf1 );
            // Separación departamento biller
            $dpto=explode("-",$billing_data['biller']->state);
            // Separación ciudad biller
            $city=explode("-",$billing_data['biller']->city);
            // Separador pais/country biller
            $country=explode("-", $billing_data['biller']->country);
            // separador documento
            $docu_type=explode("-", $billing_data['customer']->docu_type);
            // responsible_for_IVA ADQ
            $responsible_IVA=explode("-",$billing_data['customer']->responsible_IVA);
            // separador name
            $full_name=explode(" ", $billing_data['customer']->name);
             // Separación departamento customer
            $dpto_cust=explode("-",$billing_data['customer']->state);
            // Separación ciudad customer
            $city_cust=explode("-",$billing_data['customer']->city);
            // Separador pais/country customer
            $country_cust=explode("-", $billing_data['customer']->country);
            //Código correspondiente al medio de pago.
            $payment_mean=explode("-", $billing_data['payments'][0]->paid_by);
            //ITE item pay
            $num_items=count($billing_data['rows'])-1;
            //variable items witdh iva
            $val_tot_3="0.000";
            //tribute
            $tribute=explode("-", $billing_data['conf_dian'][0]->tribute);
            //unidad de medida
            $unit=explode("-", $billing_data['conf_dian'][0]->measure);
            //Consecu factur
            $invoice_number= explode("/", $billing_data['inv']->reference_no);


            //logic name AQD tip full names
                if(count($full_name)>3)
                {
                    $name=$full_name[0]." ".$full_name[1];
                    $surnames=$full_name[2]." ".$full_name[3];
                }elseif(count($full_name)==3)
                {
                    $name=$full_name[0];
                    $surnames=$full_name[1]." ".$full_name[2];
                }else
                {
                    $name=$full_name[0];
                    $surnames=$full_name[1];
                }
            //suma de valores brutos items con impuesto
            if ($num_items>=0)
            {
                for ($i=0; $i <=$num_items ; $i++)
                {
                    if ($billing_data['rows'][$i]->item_tax!=0)
                    {

                    $val_tot_3=$val_tot_3+$billing_data['rows'][$i]->net_unit_price * $billing_data['rows'][$i]->quantity;
                    }
                }
            }



            // array data biller

            $data = array(
                array(
                'group'  =>'ENC',
                'fields' => array(
                                    $billing_data['conf_dian'][0]->document_type,   //EMI_1 Tipo de factura
                                    $doc_nit[0],                                    //EMI_2 Nit Vendedor
                                    $billing_data['customer']->docu_num,              //EMI_3 Nit comprador
                                    $billing_data['conf_dian'][0]->ubl_version,     //EMI_4 Especificación
                                    $billing_data['conf_dian'][0]->format_version,  //EMI_5 Espeficicación DIAN
                                    $billing_data['inv']->reference_dian,           //EMI_6 Consecutivo entregao por la DIAN
                                    $payments_data[0],                              //EMI_7 Fecha de pago
                                    $payments_data[1]."-05:00",                     //EMI_8 Hora del pago GTM ?
                                    $billing_data['conf_dian'][0]->invoce,          //EMI_9 Tipo factura
                                    $billing_data['conf_dian'][0]->badge,           //EMI_10 Divisa
                                    "",                                             //EMI_11 Fecha para fac centros educativ
                                    "",                                             //EMI_12 Fecha fact segín ENC_11
                                    "",                                             //EMI_13 N° Centro de Costos
                                    "",                                             //EMI_14 Descripción Cód contabil / N° Negocio comprador
                                    count($billing_data['rows']),                   //EMI_15 consecutivo de aviso enviado al comprador
                                    $payments_data[0],                              //EMI_16 Fecha de pago asociada
                                    "",                                             //EMI_17 url archivos anexos
                                    "",                                             //EMI_18 url para pago factura
                                    "",                                             //EMI_19 Unidad de negocio vendor/emisor
                                    $billing_data['conf_dian'][0]->environment,     //EMI_20 Ambiente en que en cuentra 1=produc, 2=pruebas
                                    $billing_data['conf_dian'][0]->operation,       //EMI_21 Tipo de operación
                                    "",                                             //EMI_22 Fecha de pago de impuestos
                                ),
                     ),
                array(
                'group'  =>'EMI',
                'fields' => array(
                                    $billing_data['conf_dian'][0]->types_person,  //EMI_1 Id del emisor
                                    $doc_nit[0],                            //EMI_2 NIT emisor
                                    $billing_data['conf_dian'][0]->tax_identifier,    //EMI_3 Id NIT Colombia
                                    $billing_data['conf_dian'][0]->responsible_iva,   //EMI_4 48 Responsanle IVA - No responsable 49
                                    "",                                     //EMI_5 N° Id interna
                                    $billing_data['biller']->company,       //EMI_6 Razon social emiso
                                    $billing_data['biller']->company,       //EMI_7 Nombre emisor
                                    "",                                     //EMI_8 Campo eliminado
                                    "",                                     //EMI_9 Campo eliminado
                                    $billing_data['biller']->address,       //EMI_10 Dirección
                                    $dpto[0],                               //EMI_11 Cod Departa
                                    "",                                     //EMI_12 Campo eliminado
                                    $city[1],                               //EMI_13 ciudad
                                    $billing_data['biller']->postal_code,   //EMI_14 Cod Postal
                                    $country[0],                            //EMI_15 Cod pais
                                    "",                                     //EMI_16 Cod EAN
                                    "",                                     //EMI_17 Eliminado
                                    "",                                     //EMI_18 Eliminado
                                    $dpto[1],                               //EMI_19 Nom Dpto
                                    "",                                     //EMI_20 Eliminado
                                    $country[1],                            //EMI_21 Pais emisor
                                    $doc_nit[1],                            //EMI_22 Dig Nit verificación
                                    $city[0],                               //EMI_23 Cod municipio
                                    $billing_data['biller']->company,       //EMI_24 Nom RUT
                                    $billing_data['conf_dian'][0]->cod_activity,  //EMI_25 Cod CIIU emisor *

                                ),
                                    array(
                                            'subgroup'  =>  'TAC',
                                            'fact'      =>  array(
                                                        $billing_data['conf_dian'][0]->fiscal_respon,   //TAC_1 Obligacione del contribu *
                                                        ),
                                        ),
                                    array(
                                            'subgroup'  =>  'DFE',
                                            'fact'      =>  array(
                                                        $city[0],                                //DFE_1 Cod Municipio
                                                        $dpto[0],                                //DFE_2 Cod dto
                                                        $country[0],                             //DFE_3 Cod pais
                                                        $billing_data['biller']->postal_code,    //DFE_4 Cod postal
                                                        $country[1],                             //DFE_5 Pais emisor
                                                        $dpto[1],                                //DFE_6 Nom Dpto
                                                        $city[1],                                //DFE_7 ciudad
                                                        $billing_data['biller']->address,        //DFE_8 Dirección
                                                        ),
                                        ),
                                    array(
                                            'subgroup'  =>  'ICC',
                                            'fact'      =>  array(
                                                        $billing_data['conf_dian'][0]->commer_regist, //ICC_1 N° matr mercant *
                                                        "",                                         //ICC_2 Eliminado para 2.1
                                                        "",                                         //ICC_3 Eliminado para 2.1
                                                        "",                                         //ICC_4 Eliminado para 2.1
                                                        "",                                         //ICC_5 Eliminado para 2.1
                                                        "",                                         //ICC_6 Eliminado para 2.1
                                                        "",                                         //ICC_7 Eliminado para 2.1
                                                        "",                                         //ICC_8 Eliminado para 2.1
                                                        $billing_data['conf_dian'][0]->billing_prefix,   //ICC_9 Prefijo de la facturación entregado DIAN ?
                                                        ),
                                        ),
                                    array(
                                            'subgroup'  =>  'CDE',
                                            'fact'      =>  array(
                                                        "1",                                        //CDE_1 tipo de contacto *
                                                        $billing_data['biller']->name,              //CDE_2 person contac
                                                        $billing_data['biller']->phone,             //CDE_3 person tel
                                                        $billing_data['biller']->email,             //CDE_4 person email

                                                        ),
                                        ),
                                    array(
                                            'subgroup'  =>  'GTE',
                                            'fact'      =>  array(
                                                        $tribute[0],                         //GTE_1 Identificador del tributo 01 IVA *
                                                        $tribute[1],                        //GTE_2 Nombre del tributo *
                                                        ),
                                        ),
                    ),
                array(
                'group'  =>'ADQ',
                'fields' => array(
                                    "2",                                    //ADQ_1 tipo de persona 1juridica, 2natural *
                                    $billing_data['customer']->docu_num,      //ADQ_2 N° doc
                                    $docu_type[0],                          //ADQ_3 Tipo de documento
                                    $responsible_IVA[0],                    //ADQ_4 Reg fiscal aquiriente
                                    $billing_data['customer']->id,          //ADQ_5 id interno asignado por el proveedor
                                    $billing_data['customer']->name,        //ADQ_6 Razon social
                                    $billing_data['customer']->name,        //ADQ_7 Nombre comercial
                                    $name,                                  //ADQ_8 Nombres aquiriente,si ADQ_1=2
                                    $surnames,                              //ADQ_9 Apellidos aquiriente,si ADQ_1=2
                                    $billing_data['customer']->address,     //ADQ_10 info extra
                                    $dpto_cust[0],                          //ADQ_11 dpto
                                    "",                                     //ADQ_12 eliminado
                                    $city_cust[1],                          //ADQ_13 Nom city
                                    $billing_data['customer']->postal_code, //ADQ_14 Cod postal
                                    $country_cust[0],                       //ADQ_15 cod pais
                                    "",                                     //ADQ_16 cod localización EAN
                                    "",                                     //ADQ_17  eliminado
                                    "",                                     //ADQ_18  eliminado
                                    $dpto_cust[1],                          //ADQ_19 nom dpto
                                    "",                                     //ADQ_20 eliminado
                                    $country_cust[1],                       //AQD_21 nom pais
                                    "1",                                    //ADQ_22 digit verif nit
                                    $city_cust[0],                          //ADQ_23 cod minicip
                                    $billing_data['customer']->docu_num,      //ADQ_24 doc adqui
                                    "",                                     //ADQ_25 id del documento adquiri
                                    "",                                     //AD°_26 DV documento *
                                ),
                                    array(
                                'subgroup'  =>  'TCR',
                                'fact'      =>  array(
                                                        "O-49",             //TCR_1 Responsabilidades del adquiriente *cliente
                                                        ),
                                         ),
                                    array(
                                'subgroup'  =>  'ILA',
                                'fact'      =>  array(
                                                        $billing_data['customer']->name,             //ILA_1 NOMBRE EN RUT
                                                        $billing_data['customer']->docu_num,           //ILA_2 identifi adquiriente
                                                        $docu_type[0],                               //ILA_3 tipo documento
                                                        "1",                                         //ILA_4 Digito de verificación adqu *cliente
                                                        ),
                                         ),
                                    array(
                                'subgroup'  =>  'DFA',
                                'fact'      =>  array(
                                                        $country_cust[0],                           //DFA_1 Cod pais
                                                        $dpto_cust[0],                              //DFA_2  Cod_dpto
                                                        $billing_data['customer']->postal_code,     //DFA_3 Cod postal
                                                        $city_cust[0],                              //DFA_4 Cod municipio
                                                        $country_cust[1],                           //DFA_5 Nombre pais
                                                        $dpto_cust[1],                              //DFA_6  Nombre DPto
                                                        $city_cust[1],                              //DFA_7 Nombre de la ciudad
                                                        $billing_data['customer']->address,         //DFA_8 campo libre
                                                        ),
                                         ),
                                    array(
                                'subgroup'  =>  'ICR',
                                'fact'      =>  array(
                                                        $billing_data['pos']->default_biller,        //TCR_1 id punto facturación
                                                        ),
                                         ),
                                    array(
                                'subgroup'  =>  'CDA',
                                'fact'      =>  array(
                                                        "1",                                //CDA_1 libre *
                                                        $billing_data['customer']->name,    //CDA_2 nom y carg person contac
                                                        $billing_data['customer']->phone,   //CDA_3 Tel person contact
                                                        $billing_data['customer']->email,   //CDA_4 email
                                                        "",                                 //CDA_5 Telefax
                                                        "",                                 //CDA_6 Nota adicional
                                                        ),
                                         ),
                                    array(
                                'subgroup'  =>  'GTA',
                                'fact'      =>  array(
                                                        $billing_data['getSalesTax']->code,  //GTA_1 id del tributo
                                                        $billing_data['getSalesTax']->name,  //GTA_2 Nom tributo
                                                        ),
                                         ),
                    ),
                array(
                'group'  =>'TOT',
                'fields' => array(
                                    $billing_data['inv']->total,                //TOT_1 Valor bruto antes de tributos =ITE_5
                                    $billing_data['conf_dian'][0]->badge,       //TOT_2 moneda VENTA
                                    $val_tot_3,                                 //TOT_3 Total Valor Base Imponible
                                    $billing_data['conf_dian'][0]->badge,       //TOT_4  Moneda base imponible
                                    $billing_data['inv']->paid,                 //TOT_5  Total a pgar
                                    $billing_data['conf_dian'][0]->badge,       //TOT_6 Moneda fact
                                    $billing_data['inv']->paid,                 //TOT_7 Valor bruto
                                    $billing_data['conf_dian'][0]->badge,       //TOT_8 moneda v bruto
                                    $billing_data['inv']->order_discount,       //TOT_9 desc total
                                    $billing_data['conf_dian'][0]->badge,       //TOT_10 moneda desc
                                    "",                                         //TOT_11 Cargo Total
                                    "",                                         //TOT_12 Money car total
                                    "",                                         //TOT_13 Anticipo Total
                                    "",                                         //TOT_14 Mone act total
                                    "",                                         //TOT_15 Redondeo aplicado
                                    "",                                         //TOT_16 mone redond
                                 ),
                    ),
                 array(
                'group'  =>'TIM',
                'fields' => array(
                                    "false",                                //TIM_1 Indica que el elemento es un: Impuesto false=iva
                                    $billing_data['inv']->order_tax,        //TIM_2 Valor del tributo = imp_4 ?
                                    $billing_data['conf_dian'][0]->badge,   //TIM_3 moneda tibuto
                                 ),
                            array(
                                'subgroup'  =>  'IMP',
                                'fact'      =>  array(
                                                        $billing_data['getSalesTax']->code,       //IMP_1 ID DEL TRIBUTO
                                                        $billing_data['inv']->total_tax,          //IMP_2 Base Imponible calcula valor tributo. ?
                                                        $billing_data['conf_dian'][0]->badge,     //IMP_3 Moneda = ECN_10
                                                        $billing_data['inv']->order_tax,          //IMP_4 Valor tributo
                                                        $billing_data['conf_dian'][0]->badge,     //IMP_5 Mone valor tribut
                                                        $billing_data['getSalesTax']->rate,       //IMP_6 tarifa tributo
                                                        "",                                       //IMP_7 Unidad de medida base para el tributo
                                                        "",                                       //IMP_8 Identificación de la unidad de medida
                                                        "",                                       //IMP_9 Valor del tributo por unidad
                                                        "",                                       //IMP_10 Moneda del valor del tributo por unidad
                                                    ),
                                 ),

                        ),
                array(
                'group'  =>'DRF',
                'fields' => array(
                                    $billing_data['conf_dian'][0]->dian_num,           //DRF_1 Número autorización DIAN -pendiente
                                    $billing_data['conf_dian'][0]->start_dian,         //DRF_2 Fecha de inicio autorizacion DIAN - pendinte
                                    $billing_data['conf_dian'][0]->end_dian,           //DRF_3  Fecha de fin autorizacion DIAN -pendiente
                                    $billing_data['conf_dian'][0]->billing_prefix,     //DRF_4  prefijo facturación
                                    $billing_data['conf_dian'][0]->min_number,         //DRF_5 Rango de numeración min
                                    $billing_data['conf_dian'][0]->max_number,         //DRF_6 Rango de numeración max

                                 ),
                    ),
                array(
                'group'  =>'MEP',
                'fields' => array(
                                    $payment_mean[0],                               //MEP_1 Código medio de pago DIAN.
                                    $billing_data['conf_dian'][0]->payment_method,  //MEP_2 metodo de pago 1 efectivo 2 credito *
                                 ),
                     ),

            );
            //push data to array $data item purchased - dynamic
            if ($num_items>=0)
            {
                for ($i=0; $i <=$num_items ; $i++)
                {
                    if ($billing_data['rows'][$i]->item_tax!=0)
                    {
                    array_push($data, array(
                                            'group'=>'ITE',
                                            'fields'=>array(
                                                            $i+1,
                                                            "",                                              //ITE_2 eliminado
                                                            $billing_data['rows'][$i]->quantity,              //ITE_3 cantdad del producto
                                                            $unit[0],                                           //ITE_4 unidad de medidad 94=unidad*
                                                            $billing_data['rows'][$i]->net_unit_price * $billing_data['rows'][$i]->quantity,              //ITE_5 precio con arandelas
                                                            $billing_data['conf_dian'][0]->badge,            //ITE_6  moneda valor precio total
                                                            $billing_data['rows'][$i]->net_unit_price,       //ITE_7 Valor articulo o servicio
                                                            $billing_data['conf_dian'][0]->badge,            //ITE_8 Moneda de articulo
                                                            "",                                             //ITE_9 Eliminado
                                                            $billing_data['rows'][$i]->product_code,          //ITE_10 campo adicional libre
                                                            $billing_data['rows'][$i]->product_name,          //ITE_11 Descrip articulo
                                                            $billing_data['rows'][$i]->product_name,          //ITE_12 Descrip articulo
                                                            "",                                             //ITE_13 eliminado
                                                            "",                                             //ITE_14 eliminado
                                                            "1",                                            //ITE_15 Cantidad por empaque producto ?
                                                            "",                                             //ITE_16 eliminado
                                                            $billing_data['rows'][$i]->product_code,          //ITE_17 cod articulo por proveedor
                                                            $billing_data['rows'][$i]->id,                    //ITE_18 cod arti vendedor
                                                            $billing_data['rows'][$i]->subtotal,              //ITE_19 total valor items
                                                            $billing_data['conf_dian'][0]->badge,           //ITE_20 moneda total va item
                                                            $billing_data['rows'][$i]->subtotal,              //ITE_21 valor a pagar del item
                                                            $billing_data['conf_dian'][0]->badge,            //ITE_22 Moneda ITE_21
                                                            $billing_data['rows'][$i]->subtotal,              //ITE_23 valor subtotal item
                                                            $billing_data['conf_dian'][0]->badge,            //ITE_24 modena ite_22
                                                            "",                                             //ITE_25 vacias no hay contrato de compra
                                                            "",                                             //ITE_26 vacias no hay contrato de compra
                                                            $billing_data['rows'][$i]->quantity,              //ITE_27 cantindad de item a aplicar precio
                                                            $unit[0],                                           //ITE_28 Unidad de medidad
                                                            "",                                             //ITE_29 Código del vendedor subespecificación del artículo
                                                           ),

                                            array(
                                                'subgroup'=>'TII',
                                                'fact'=>array(
                                                                $billing_data['rows'][$i]->item_tax,         //TII_1 tributo del elemento item.
                                                                $billing_data['conf_dian'][0]->badge,        //TII_2 Mondena valor tributo
                                                                "false",                                     //TII_3 Indica que es un impuesto
                                                             ),
                                                array(
                                                    's_subgroup'=>'IIM',
                                                    's_fact'=>array(
                                                                    $billing_data['rows'][$i]->tax_code,          //IIM_1 Identificador del tributo
                                                                    $billing_data['rows'][$i]->item_tax,          //IIM_2 valor del tributo
                                                                    $billing_data['conf_dian'][0]->badge,         //IIM_3 moneda valor tributo
                                                                    $billing_data['rows'][$i]->net_unit_price * $billing_data['rows'][$i]->quantity,    //IIM_4 base del producto
                                                                    $billing_data['conf_dian'][0]->badge,        //IIM_5 Moneda base tributo
                                                                    $billing_data['rows'][$i]->tax_rate,          //IIM_6 tafira del tributo

                                                                   ),
                                                     ),
                                                 ),

                                            )
                              );
                    }else
                    {
                            array_push($data, array(
                                            'group'=>'ITE',
                                            'fields'=>array(
                                                            $i+1,
                                                            "",                                              //ITE_2 eliminado
                                                            $billing_data['rows'][$i]->quantity,              //ITE_3 cantdad del producto
                                                            $unit[0],                                           //ITE_4 unidad de medidad 94=unidad*
                                                            $billing_data['rows'][$i]->net_unit_price * $billing_data['rows'][$i]->quantity,              //ITE_5 precio con arandelas
                                                            $billing_data['conf_dian'][0]->badge,            //ITE_6  moneda valor precio total
                                                            $billing_data['rows'][$i]->net_unit_price,       //ITE_7 Valor articulo o servicio
                                                            $billing_data['conf_dian'][0]->badge,            //ITE_8 Moneda de articulo
                                                            "",                                             //ITE_9 Eliminado
                                                            $billing_data['rows'][$i]->product_code,          //ITE_10 campo adicional libre
                                                            $billing_data['rows'][$i]->product_name,          //ITE_11 Descrip articulo
                                                            $billing_data['rows'][$i]->product_name,          //ITE_12 Descrip articulo
                                                            "",                                             //ITE_13 eliminado
                                                            "",                                             //ITE_14 eliminado
                                                            "1",                                            //ITE_15 Cantidad por empaque producto ?
                                                            "",                                             //ITE_16 eliminado
                                                            $billing_data['rows'][$i]->product_code,          //ITE_17 cod articulo por proveedor
                                                            $billing_data['rows'][$i]->id,                    //ITE_18 cod arti vendedor
                                                            $billing_data['rows'][$i]->subtotal,              //ITE_19 total valor items
                                                            $billing_data['conf_dian'][0]->badge,            //ITE_20 moneda total va item
                                                            $billing_data['rows'][$i]->subtotal,              //ITE_21 valor a pagar del item
                                                            $billing_data['conf_dian'][0]->badge,             //ITE_22 Moneda ITE_21
                                                            $billing_data['rows'][$i]->subtotal,              //ITE_23 valor subtotal item
                                                            $billing_data['conf_dian'][0]->badge,           //ITE_24 modena ite_22
                                                            "",                                             //ITE_25 vacias no hay contrato de compra
                                                            "",                                             //ITE_26 vacias no hay contrato de compra
                                                            $billing_data['rows'][$i]->quantity,              //ITE_27 cantindad de item a aplicar precio
                                                            $unit[0],                                           //ITE_28 Unidad de medidad
                                                            "",                                             //ITE_29 Código del vendedor subespecificación del artículo
                                                           ),


                                            )
                              );

                    }
                }
            }

            //echo "<script>console.log(".json_encode($data).")</script>";

            //instance librery php
            $objetoXML = new XMLWriter();

            // Estructura básica del XML

            $objetoXML->openURI('venta.xml');
            $objetoXML->setIndent(true);
            $objetoXML->setIndentString("\t");
            $objetoXML->startDocument('1.0', 'utf-8');
                // Inicio del nodo raíz
                $objetoXML->startElement("FACTURA");
                    //array ENC

                    foreach ($data as $datas)
                    {
                        //echo "<script> console.log(".json_encode($datas).") </script>";
                        $objetoXML->startElement($datas['group']);
                                // Element type document
                                $number=0;
                                foreach ($datas['fields'] as $values)
                                {   $number=$number+1;
                                    if ($values){
                                        $objetoXML->startElement($datas['group']."_".$number);
                                            // Tyte document
                                            $objetoXML->text($values);
                                        // End Element type document
                                        $objetoXML->endElement();
                                    }
                                }
                                    //Start read subgroup
                                    $number=0;
                                    foreach ($datas as $sub)
                                {
                                         if($datas[$number])
                                    {
                                        $objetoXML->startElement($datas[$number]['subgroup']);
                                            $i=0;
                                            foreach ($datas[$number]['fact'] as $fact)
                                            {
                                                $i=$i+1;
                                                if($fact)
                                                {
                                                    // Start element
                                                    $objetoXML->startElement($datas[$number]['subgroup']."_".$i);
                                                        // Tyte document
                                                        $objetoXML->text($fact);

                                                    // End Element type document
                                                    $objetoXML->endElement();
                                                }

                                            }

                                           //start element s-subgroup
                                            if ($datas[$number][0]) {
                                                $objetoXML->startElement($datas[$number][0]['s_subgroup']);
                                                    $j=0;
                                                    foreach ($datas[$number][0]['s_fact'] as $rs)
                                                    {
                                                        $j=$j+1;
                                                         $objetoXML->startElement($datas[$number][0]['s_subgroup']."_".$j);
                                                            // Tyte document
                                                            $objetoXML->text($rs);
                                                        $objetoXML->endElement();
                                                    // End Element type document
                                                    }
                                                $objetoXML->endElement();
                                            }
                                           // end element s-subgroup
                                        // End Element type document
                                        $objetoXML->endElement();
                                    }
                                     $number=$number+1;



                                }
                                //End read sudgroup
                        //End array ENC
                        $objetoXML->endElement();
                    }


                // Final del nodo raíz, "Factura"
                $objetoXML->endElement();
            // Final del documento
            $objetoXML->endDocument();
        }
}
