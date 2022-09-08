<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dian extends MY_Controller
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
        $this->lang->load('dian', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
        $this->load->model('dian_model');
        $this->load->model('settings_model');
        $this->load->library('nusoap_library');
        $this->load->model('pos_model');

    }

    function index()
    {
        $this->sma->checkPermissions();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('dian')));
        $meta = array('page_title' => lang('dian'), 'bc' => $bc);

        $data_dian=$this->dian_model->getAllConfDian();
        $this->data['tax_rates'] = $this->settings_model->getAllTaxRates();
        $settings=$this->settings_model->getSettings();
        $this->data['settings'] = $this->settings_model->getSettings();
        $this->data['currencies'] = $this->settings_model->getAllCurrencies();
        $fiscal_respon=file_get_contents('app/json/fiscal_respon.json');
        $this->data['fiscal_respon']= json_decode($fiscal_respon,true);
        $this->data['username']=$this->session->userdata('username');
        $this->data['order_ref']=$this->dian_model->getOrder_ref();
        $this->data['biller']=$this->companies_model->getCompanyByID(3);
        /*  xml desde memoria

            $objetoXML = new XMLWriter();
            $objetoXML->openMemory();
            $objetoXML->setIndent(true);
            $objetoXML->setIndentString("\t");
            $objetoXML->startDocument('1.0', 'utf-8');
            $objetoXML->startElement("FACTURA");
                $objetoXML->startElement("ss");
                    $objetoXML->text($fact);
                $objetoXML->endElement();
            $objetoXML->endElement();
            $objetoXML->endDocument();

            //$cadenaXML = trim($objetoXML->outputMemory());*/

            // convert xml a base64 parametro faturación dian

        if($data_dian)
        {

          if($this->dian_model->getDian_api())
            {

                $this->data['api_flag']="enabled";
                $this->data['state_btn_api']=lang("edit");


                //data conexión api DIAN
                $api=$this->dian_model->getDian_api();
                //start biller test DIAN
                if ($data_dian[0]->environment==2)
                {
                    $this->data['msg_api']="";



                    //parameter xml DIAN a base64
                    $file= simplexml_load_file('venta.xml');
                    $string= $file->asXML();
                    $base= base64_encode($string);
                    $params = array('username'=>$api->username,
                                    'password'=>$api->password,
                                    'xmlBase64'=>$base);

                    //invoke method conection and parameters FTECHACTION.UPLOADINVOICEFILE
                    $getsoap=$this->nusoap_library->soaprequest('FtechAction.uploadInvoiceFile',$api,$params);
                    //Get UPLOADINVOICEFILE
                    $this->data['getsoap']=$getsoap;

                    //invoke method FTECHACTION.DOCUMENTSTATUSFILE
                    if ($getsoap['Result']['code']==200 || $getsoap['Result']['code']==201)
                    {
                        $this->pos_model->updateSalesTransId($getsoap['Result']['transaccionID'],($settings->sales_prefix).($data_dian[0]->current_number-1));

                        $params=array('username'=>$api->username,
                                        'password'=>$api->password,
                                        'transaccionID' => $getsoap['Result']['transaccionID'],
                                    );
                        $getStatusFile=$this->nusoap_library->soaprequest('FtechAction.documentStatusFile',$api,$params);
                        $this->data['getStatusFile']=$getStatusFile;


                    }

                }else
                {
                    $this->data['msg_api']="hidden";
                }
                // End biller test DIAN

            }
            else
            {

                $this->data['visible_api']="hidden";
                $this->data['msg_api']="hidden";
                $this->data['api_flag']="disabled";
                $this->data['state_btn_api']=lang("activate");
            }

            $this->data['api']=$this->dian_model->getDian_api();
            $this->data['settings_dian']=$this->dian_model->getAllConfDian();
            $this->data['visible']="";

            $this->page_construct('dian/view', $meta, $this->data);

        }
        else
        {
            $this->data['visible']="hidden";
            $this->data['msg_api']="hidden";
            $this->session->set_flashdata('message', lang("unconfigured"));
            $this->page_construct('dian/view', $meta, $this->data);
        }

    }
    public function update()
    {
        $this->sma->checkPermissions();
         //validate form input
        $this->form_validation->set_rules('cod_activity', $this->lang->line("cod_activity"), 'required');
        $this->form_validation->set_rules('commer_regist', $this->lang->line("commer_regist"), 'required');
        $this->form_validation->set_rules('dian_num', $this->lang->line("dian_num"), 'required');
        $this->form_validation->set_rules('start_dian', $this->lang->line("start_dian"), 'required');
        $this->form_validation->set_rules('end_dian', $this->lang->line("end_dian"), 'required');
        $this->form_validation->set_rules('min_number', $this->lang->line("min_number"), 'required');
        $this->form_validation->set_rules('max_number', $this->lang->line("max_number"), 'required');

        //$this->form_validation->set_rules('min_number', lang("min_number"), 'required');
       // $this->form_validation->set_rules('max_number', lang("max_number"), 'required');

        if ($this->form_validation->run() == TRUE) {
            $measure="94-".lang("unit");

            $data = array(
                'environment'           =>$this->input->post('environments'),
                'document_type'         =>$this->input->post('document_type'),
                'ubl_version'           =>$this->input->post('ubl_version'),
                'format_version'        =>$this->input->post('format_version'),
                'invoce'                =>$this->input->post('sales_invoice'),
                'operation'             =>$this->input->post('operation'),
                'types_person'          =>$this->input->post('types_person'),
                'tax_identifier'        =>$this->input->post('tax_identifier'),
                'responsible_iva'       =>$this->input->post('responsible_iva'),
                'cod_activity'          =>$this->input->post('cod_activity'),
                'fiscal_respon'         =>$this->input->post('fiscal_respon'),
                'commer_regist'         =>$this->input->post('commer_regist'),
                'dian_num'              =>$this->input->post('dian_num'),
                'start_dian'            =>$this->input->post('start_dian'),
                'end_dian'              =>$this->input->post('end_dian'),
                'min_number'            =>$this->input->post('min_number'),
                'max_number'            =>$this->input->post('max_number'),
                'payment_method'        =>$this->input->post('payment_method'),
                'measure'               =>$measure,
                'billing_prefix'        =>$this->input->post('billing_prefix'),
                'billing'               =>$this->input->post('billing'),
                'badge'                 =>$this->input->post('badge'),
                'tribute'               =>$this->input->post('tribute'),

            );
        }

        if($this->form_validation->run()==TRUE)
            {
               if($this->dian_model->getAllConfDian())
               {
                    $this->dian_model->updateDian($data);
                    $this->session->set_flashdata('message', lang("update_dian"));
                    redirect("dian");
               }
               else
               {
                    $this->dian_model->addConfDian($data);
                    $this->session->set_flashdata('message', lang("success_inserted"));
                    redirect("dian");
                    //$this->page_construct('dian/view', $meta, $this->data);
               }




            }
    }
    public function addDianApi()
    {
        $this->sma->checkPermissions();
        $this->form_validation->set_rules('api_url', $this->lang->line("api_url"), 'required');
        $this->form_validation->set_rules('service', $this->lang->line("service"), 'required');
        $this->form_validation->set_rules('username', $this->lang->line("username"), 'required');
        $this->form_validation->set_rules('password', $this->lang->line("password"), 'required');

        if ($this->form_validation->run()==TRUE) {
            $data= array(
                'api_url'   =>$this->input->post('api_url'),
                'service'   =>$this->input->post('service'),
                'username'  =>$this->input->post('username'),
                'password'  =>$this->input->post('password'),
                'proxyhost' =>$this->input->post('proxyhost'),
                'proxyport' =>$this->input->post('proxyport'),
            );

            if ($this->dian_model->getDian_api())
            {
                $this->dian_model->updateDian_api($data);
            }
            else
            {
                $this->dian_model->addDian_api($data);
            }


            redirect("dian");

        }

    }
    public function deleteDian_api()
    {
        if ($this->input->post('api_flag')) {
            $this->dian_model->deleteDian_api();

            redirect("dian");

        }

    }
    public function invoice_pdf($id)
    {
        $this->load->library('nusoap_library');
        $api=$this->dian_model->getDian_api();
        $settings=$this->settings_model->getSettings();
        $reference_dian= $this->dian_model->GetSalesRefDian($id);

        if ($reference_dian->reference_dian!=NULL && $reference_dian->transaccion_id!=NULL) {



            $folio=explode($settings->sales_prefix, $reference_dian->reference_dian);

            if (($reference_dian->doc_status_dian)!=201)
            {
            	$params=array('username'=>$api->username,
                        'password'=>$api->password,
                        'transaccionID'=>$reference_dian->transaccion_id);

            	$this->nusoap_library->soaprequest('FtechAction.documentStatusFile',$api,$params);
            	$documentStatusFile=$this->nusoap_library->soaprequest('FtechAction.documentStatusFile',$api,$params);


            }


            if(($reference_dian->doc_status_dian)==201 && ($reference_dian->pdf_dian)!=NULL )
            {
                $filePdf=base64_decode($reference_dian->pdf_dian);
                $pdf=fopen("factura.pdf", 'w');
                fwrite($pdf, $filePdf);
                fclose($pdf);
                header("Content-type: application/pdf");
                header("Content-Disposition: inline; filename=factura.pdf");
                readfile("factura.pdf");

            }elseif (($documentStatusFile['Result']['code'])==201 || ($reference_dian->doc_status_dian)==201) {

                $params=array('username'=>$api->username,
                        'password'=>$api->password,
                        'prefijo' => $settings->sales_prefix,
                        'folio'=>$folio[1]);

                $dataMethoPdf=$this->nusoap_library->soaprequest("FtechAction.downloadPDFFile",$api,$params);

                $status=$documentStatusFile['Result']['code']?$documentStatusFile['Result']['code']:$reference_dian->doc_status_dian;

                $this->dian_model->updateSalesStatusDian($id,$status,$dataMethoPdf['Result']['resourceData']);

                $filePdf=base64_decode($dataMethoPdf['Result']['resourceData']);
                $pdf=fopen("factura.pdf", 'w');
                fwrite($pdf, $filePdf);
                fclose($pdf);
                header("Content-type: application/pdf");
                header("Content-Disposition: inline; filename=factura.pdf");
                readfile("factura.pdf");

            }
            else
            {
                $msg=lang("Documento se esta firmando intente nuevamente - codigo: ".$documentStatusFile['Result']['code']);
                $this->session->set_flashdata('message', $msg);
                redirect("pos/sales");
            }



        }else
        {
            $msg=lang("without_invoice");
            $this->session->set_flashdata('message', $msg);
            redirect("pos/sales");

        }


    }


}
