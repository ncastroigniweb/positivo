<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Billers extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('billers', $this->Settings->user_language);
        $this->lang->load('customers', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
    }

    function index($action = NULL)
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('billers')));
        $meta = array('page_title' => lang('billers'), 'bc' => $bc);
        $this->page_construct('billers/index', $meta, $this->data);
    }

    function getBillers()
    {
        $this->sma->checkPermissions('index');

        $this->load->library('datatables');
        $this->datatables
            ->select("id, company, name, vat_no, phone, email, city, country")
            ->from("companies")
            ->where('group_name', 'biller')
            ->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("edit_biller") . "' href='" . site_url('billers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_biller") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('billers/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();
    }

    function add()
    {
        $this->sma->checkPermissions(false, true);

        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'is_unique[companies.email]');
        $this->form_validation->set_rules('docu_num', lang("docu_num"), 'integer');

        if ($this->form_validation->run('companies/add') == true) {

            $reg_cods = file_get_contents('app/json/regime_code.json');
            $reg_cod=json_decode($reg_cods, true);
            if(($this->input->post('responsible_for_IVA'))==1){
                $responsible_IVA=$reg_cod['regime_code'][0]['cod']."-".$reg_cod['regime_code'][0]['Description'];
                //48-respond iva
            }else{
                $responsible_IVA=$reg_cod['regime_code'][1]['cod']."-".$reg_cod['regime_code'][1]['Description'];
                //49-no respon iva
            }

            $address= explode("-",$this->input->post('city'));

            $data = array(
                'name'                      => $this->input->post('name'),
                'docu_type'                 => $this->input->post('docu_type'),
                'docu_num'                  => $this->input->post('id_number'),
                'responsible_IVA'           => $responsible_IVA,
                'gender'                    => $this->input->post('gender'),
                'blood_type'                => $this->input->post('blood_type'),
                'born'                      => $this->input->post('born'),
                'email'                     => $this->input->post('email'),
                'group_id'                  => NULL,
                'group_name'                => 'biller',
                'company'                   => $this->input->post('company'),
                'address'                   => $this->input->post('address'),
                'vat_no'                    => $this->input->post('vat_no'),
                'city'                      => $address[0]."-".$address[1],
                'state'                     => $address[2]."-".$address[3],
                'postal_code'               => $address[4],
                'country'                   => $this->input->post('country'),
                'phone'                     => $this->input->post('phone'),
                'logo'                      => $this->input->post('logo'),
                'cf1'                       => $this->input->post('id_number'),
                'cf2'                       => $this->input->post('cf2'),
                'cf3'                       => $this->input->post('cf3'),
                'cf4'                       => $this->input->post('cf4'),
                'cf5'                       => $this->input->post('cf5'),
                'cf6'                       => $this->input->post('cf6'),
                'invoice_footer'            => $this->input->post('invoice_footer'),
            );
        } elseif ($this->input->post('add_biller')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('billers');
        }

        if ($this->form_validation->run() == true && $this->companies_model->addCompany($data)) {
            $this->session->set_flashdata('message', $this->lang->line("biller_added"));
            redirect("billers");
        } else {
            $this->data['logos'] = $this->getLogoList();
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['form_customer']=array(
                'id_form'=>'add-biller-form',
                'page'=>'add',
                'name_form'=>lang('add_biller'),
                'name_button'=>lang('add_biller'),
                'role'=>"billers",
            );

            $this->data['state_iva']=array(
                                            'iva_val'   =>0,
                                            'msg_iva'   =>lang("no").", ".lang("responsible_for_IVA"),
                                            'msg_help'  =>lang('select')." ".lang("yes").", ".lang("responsible_for_IVA"),

                                                    );


            //files json DIAN
            $dptos = file_get_contents('app/json/dptos.json');
            $countrys=file_get_contents('app/json/pais.json');
            $docu_types = file_get_contents('app/json/docu_type.json');

            $this->data['citys']=json_decode($dptos, true);
            $this->data['country']=json_decode($countrys, true);
            $this->data['docu_type'] = json_decode($docu_types, true);
            //$this->load->view($this->theme . 'form_templates/customer_form', $this->data);
            $this->load->view($this->theme . 'form_templates/customer_form', $this->data);

        }
    }

    function edit($id = NULL)
    {
        $this->sma->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $company_details = $this->companies_model->getCompanyByID($id);
        if ($this->input->post('email') != $company_details->email) {
            $this->form_validation->set_rules('code', lang("email_address"), 'is_unique[companies.email]');
        }


        if ($this->form_validation->run('companies/add') == true) {
                $reg_cods = file_get_contents('app/json/regime_code.json');
                $reg_cod=json_decode($reg_cods, true);
                if(($this->input->post('responsible_for_IVA'))==1){
                   $responsible_IVA=$reg_cod['regime_code'][0]['cod']."-".$reg_cod['regime_code'][0]['Description'];
                   //48-respond iva
                }else{
                   $responsible_IVA=$reg_cod['regime_code'][1]['cod']."-".$reg_cod['regime_code'][1]['Description'];
                   //49-no respon iva
                }

                $address= explode("-",$this->input->post('city'));
            $data = array(
                'name'                      => $this->input->post('name'),
                'docu_type'                 => $this->input->post('docu_type'),
                'docu_num'                  => $this->input->post('id_number'),
                'responsible_IVA'           => $responsible_IVA,
                'gender'                    => $this->input->post('gender'),
                'blood_type'                => $this->input->post('blood_type'),
                'born'                      => $this->input->post('born'),
                'email'                     => $this->input->post('email'),
                'group_id'                  => NULL,
                'group_name'                => 'biller',
                'company'                   => $this->input->post('company'),
                'address'                   => $this->input->post('address'),
                'vat_no'                    => $this->input->post('vat_no'),
                'city'                      => $address[0]."-".$address[1],
                'state'                     => $address[2]."-".$address[3],
                'postal_code'               => $address[4],
                'country'                   => $this->input->post('country'),
                'phone'                     => $this->input->post('phone'),
                'logo'                      => $this->input->post('logo'),
                'cf1'                       => $this->input->post('id_number'),
                'cf2'                       => $this->input->post('cf2'),
                'cf3'                       => $this->input->post('cf3'),
                'cf4'                       => $this->input->post('cf4'),
                'cf5'                       => $this->input->post('cf5'),
                'cf6'                       => $this->input->post('cf6'),
                'invoice_footer'            => $this->input->post('invoice_footer'),
            );
        } elseif ($this->input->post('edit_biller')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('billers');
        }

        if ($this->form_validation->run() == true && $this->companies_model->updateCompany($id, $data)) {
            $this->session->set_flashdata('message', $this->lang->line("biller_updated"));
            redirect("billers");
        } else {
            $this->data['company'] = $company_details;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['logos'] = $this->getLogoList();
            $this->data['modal_js'] = $this->site->modal_js();

            $this->data['form_customer']=array(
                'id_form'         =>'add-biller-form',
                'page'            =>'edit/',
                'name_form'       =>lang('edit_biller'),
                'name_button'     =>lang('edit_biller'),
                'action_type'     =>'disabled',
                'role'            =>"billers",
            );

            $iva=explode("-", $company_details->responsible_IVA);
                if ($iva[0]==48)
                {
                    $this->data['state_iva']=array(
                                                'iva_val'   =>1,
                                                'msg_iva'   =>$iva[1],
                                                'msg_help'  =>"Cliente".", ".$iva[1],
                                                'val_check' =>"checked",
                                            );
                }
                elseif($iva[0]==49)
                {
                    $this->data['state_iva']=array(
                                                'iva_val'   =>0,
                                                'msg_iva'   =>$iva[1],
                                                'msg_help'  =>"Cliente".", ".$iva[1],
                                            );
                }else
                {
                    $this->data['state_iva']=array(
                                                'msg_iva'   =>lang("no").", ".lang("responsible_for_IVA"),
                                                'msg_help'  =>"Marque si cliente reporta IVA",
                                                    );
                }

                //files json DIAN
            $dptos = file_get_contents('app/json/dptos.json');
            $countrys=file_get_contents('app/json/pais.json');
            $docu_types = file_get_contents('app/json/docu_type.json');

            $this->data['citys']=json_decode($dptos, true);
            $this->data['country']=json_decode($countrys, true);
            $this->data['docu_type'] = json_decode($docu_types, true);

                $this->load->view($this->theme . 'form_templates/customer_form', $this->data);
        }
    }


    function delete($id = NULL)
    {
        $this->sma->checkPermissions(NULL, TRUE);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->companies_model->deleteBiller($id)) {
            echo $this->lang->line("biller_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('biller_x_deleted_have_sales'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        $this->sma->checkPermissions('index');

        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->companies_model->getBillerSuggestions($term, $limit);
        $this->sma->send_json($rows);
    }

    function getBiller($id = NULL)
    {
        $this->sma->checkPermissions('index');

        $row = $this->companies_model->getCompanyByID($id);
        $this->sma->send_json(array(array('id' => $row->id, 'text' => $row->company)));
    }

    public function getLogoList()
    {
        $this->load->helper('directory');
        $dirname = "assets/uploads/logos";
        $ext = array("jpg", "png", "jpeg", "gif");
        $files = array();
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle)))
                for ($i = 0; $i < sizeof($ext); $i++)
                    if (stristr($file, "." . $ext[$i])) //NOT case sensitive: OK with JpeG, JPG, ecc.
                        $files[] = $file;
            closedir($handle);
        }
        sort($files);
        return $files;
    }

    function biller_actions()
    {
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    $this->sma->checkPermissions('delete');
                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->companies_model->deleteBiller($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('billers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("billers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('billers'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('email'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('city'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->phone);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->email);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->city);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'billers_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_biller_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

}
