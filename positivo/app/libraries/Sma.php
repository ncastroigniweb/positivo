<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
 *  ==============================================================================
 *  Author    : Mian Saleem
 *  Email    : saleem@tecdiary.com
 *  For        : Stock Manager Advance
 *  Web        : http://tecdiary.com
 *  ==============================================================================
 */

class Sma
{

    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function _rglobRead($source, &$array = array())
    {
        if (!$source || trim($source) == "") {
            $source = ".";
        }
        foreach ((array) glob($source . "/*/") as $key => $value) {
            $this->_rglobRead(str_replace("//", "/", $value), $array);
        }
        $hidden_files = glob($source . ".*") and $htaccess = preg_grep('/\.htaccess$/', $hidden_files);
        $files = array_merge(glob($source . "*.*"), $htaccess);
        foreach ($files as $key => $value) {
            $array[] = str_replace("//", "/", $value);
        }
    }

    private function _zip($array, $part, $destination, $output_name = 'sma')
    {
        $zip = new ZipArchive;
        @mkdir($destination, 0777, true);

        if ($zip->open(str_replace("//", "/", "{$destination}/{$output_name}" . ($part ? '_p' . $part : '') . ".zip"), ZipArchive::CREATE)) {
            foreach ((array) $array as $key => $value) {
                $zip->addFile($value, str_replace(array("../", "./"), null, $value));
            }
            $zip->close();
        }
    }

    public function formatMoney($number)
    {
        if ($this->Settings->sac) {
            return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
            $this->formatSAC($this->formatDecimal($number)) .
            ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
        }
        $decimals = $this->Settings->decimals;
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
        number_format($number, $decimals, $ds, $ts) .
        ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
    }

    public function formatQuantity($number, $decimals = null)
    {
        if (!$decimals) {
            $decimals = $this->Settings->qty_decimals;
        }
        if ($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number, $decimals));
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }

    public function formatNumber($number, $decimals = null)
    {
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        if ($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number, $decimals));
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }

    public function formatDecimal($number, $decimals = null)
    {
        if (!is_numeric($number)) {
            return null;
        }
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        return number_format($number, $decimals, '.', '');
    }

    public function clear_tags($str)
    {
        return htmlentities(
            strip_tags($str,
                '<span><div><a><br><p><b><i><u><img><blockquote><small><ul><ol><li><hr><big><pre><code><strong><em><table><tr><td><th><tbody><thead><tfoot><h3><h4><h5><h6>'
            ),
            ENT_QUOTES | ENT_XHTML | ENT_HTML5,
            'UTF-8'
        );
    }

    public function decode_html($str)
    {
        return html_entity_decode($str, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }

    public function roundMoney($num, $nearest = 0.05)
    {
        return round($num * (1 / $nearest)) * $nearest;
    }

    public function roundNumber($number, $toref = null)
    {
        switch ($toref) {
            case 1:
                $rn = round($number * 20) / 20;
                break;
            case 2:
                $rn = round($number * 2) / 2;
                break;
            case 3:
                $rn = round($number);
                break;
            case 4:
                $rn = ceil($number);
                break;
            default:
                $rn = $number;
        }
        return $rn;
    }

    public function unset_data($ud)
    {
        if ($this->session->userdata($ud)) {
            $this->session->unset_userdata($ud);
            return true;
        }
        return false;
    }

    public function hrsd($sdate)
    {
        if ($sdate) {
            return date($this->dateFormats['php_sdate'], strtotime($sdate));
        } else {
            return '0000-00-00';
        }
    }

    public function hrld($ldate)
    {
        if ($ldate) {
            return date($this->dateFormats['php_ldate'], strtotime($ldate));
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function fsd($inv_date)
    {
        if ($inv_date) {
            $jsd = $this->dateFormats['js_sdate'];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00';
        }
    }

    public function fld($ldate)
    {
        if ($ldate) {
            $date = explode(' ', $ldate);
            $jsd = $this->dateFormats['js_sdate'];
            $inv_date = $date[0];
            $time = $date[1];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2) . " " . $time;
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2) . " " . $time;
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
    {
        $this->load->library('email');
        $config['useragent'] = "Stock Manager Advance";
        $config['protocol'] = $this->Settings->protocol;
        $config['mailtype'] = "html";
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        if ($this->Settings->protocol == 'sendmail') {
            $config['mailpath'] = $this->Settings->mailpath;
        } elseif ($this->Settings->protocol == 'smtp') {
            $this->load->library('encrypt');
            $config['smtp_host'] = $this->Settings->smtp_host;
            $config['smtp_user'] = $this->Settings->smtp_user;
            $config['smtp_pass'] = $this->encrypt->decode($this->Settings->smtp_pass);
            $config['smtp_port'] = $this->Settings->smtp_port;
            if (!empty($this->Settings->smtp_crypto)) {
                $config['smtp_crypto'] = $this->Settings->smtp_crypto;
            }
        }

        $this->email->initialize($config);

        if ($from && $from_name) {
            $this->email->from($from, $from_name);
        } elseif ($from) {
            $this->email->from($from, $this->Settings->site_name);
        } else {
            $this->email->from($this->Settings->default_email, $this->Settings->site_name);
        }

        $this->email->to($to);
        if ($cc) {
            $this->email->cc($cc);
        }
        if ($bcc) {
            $this->email->bcc($bcc);
        }
        $this->email->subject($subject);
        $this->email->message($message);
        if ($attachment) {
            if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    $this->email->attach($file);
                }
            } else {
                $this->email->attach($attachment);
            }
        }

        if ($this->email->send()) {
            //echo $this->email->print_debugger(); die();
            return true;
        } else {
            //echo $this->email->print_debugger(); die();
            return false;
        }
    }

    public function checkPermissions($action = null, $js = null, $module = null)
    {
        if (!$this->actionPermissions($action, $module)) {
            $this->session->set_flashdata('error', lang("access_denied"));
            if ($js) {
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
            } else {
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
            }
        }
    }

    public function actionPermissions($action = null, $module = null)
    {
        if ($this->Owner || $this->Admin) {
            if ($this->Admin && stripos($action, 'delete') !== false) {
                return false;
            }
            return true;
        } elseif ($this->Customer || $this->Supplier) {
            return false;
        } else {
            if (!$module) {
                $module = $this->m;
            }
            if (!$action) {
                $action = $this->v;
            }
            //$gp = $this->site->checkPermissions();
            if ($this->GP[$module . '-' . $action] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function save_barcode($text = null, $bcs = 'code128', $height = 56, $stext = 1, $sq = null)
    {
        $file_name = 'assets/uploads/barcode' . $this->session->userdata('user_id') . ($sq ? $sq : '') . ($this->Settings->barcode_img ? '.jpg' : '.svg');
        $drawText = ($stext != 1) ? false : true;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $text, 'barHeight' => $height, 'drawText' => $drawText, 'factor' => 1);
        if ($this->Settings->barcode_img) {
            $rendererOptions = array('imageType' => 'jpg', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
            $imageResource = Zend_Barcode::draw($bcs, 'image', $barcodeOptions, $rendererOptions);
            imagejpeg($imageResource, $file_name);
            return "<img src='".base_url($file_name)."' alt='{$text}' class='bcimg' />";
        } else {
            $rendererOptions = array('renderer' => 'svg', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
            $imageResource = Zend_Barcode::render($bcs, 'svg', $barcodeOptions, $rendererOptions);
            return $imageResource;
        }
        return FALSE;
    }

    public function qrcode($type = 'text', $text = 'http://tecdiary.com', $size = 2, $level = 'H', $sq = null)
    {
        $file_name = 'assets/uploads/qrcode' . $this->session->userdata('user_id') . ($sq ? $sq : '') . ($this->Settings->barcode_img ? '.png' : '.svg');
        if ($type == 'link') {
            $text = urldecode($text);
        }
        $this->load->library('phpqrcode');
        $config = array('data' => $text, 'size' => $size, 'level' => $level, 'savename' => $file_name);
        if (!$this->Settings->barcode_img) {
            $config['svg'] = 1;
        }
        $this->phpqrcode->generate($config);
        return '<img src="'.base_url($file_name).'" alt="'.$text.'" style="float:right;"/>';
    }

    public function generate_pdf($content, $name = 'download.pdf', $output_type = null, $footer = null, $margin_bottom = null, $header = null, $margin_top = null, $orientation = 'P')
    {
        if (!$output_type) {
            $output_type = 'D';
        }
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 20;
        }
        $this->load->library('pdf');
        $pdf = new mPDF('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $pdf->debug = false;
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$pdf->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $pdf->SetTitle($this->Settings->site_name);
        $pdf->SetAuthor($this->Settings->site_name);
        $pdf->SetCreator($this->Settings->site_name);
        $pdf->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        $pdf->WriteHTML($stylesheet, 1);
        // $pdf->SetFooter($this->Settings->site_name.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text footer

        if (is_array($content)) {
            $pdf->SetHeader($this->Settings->site_name.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text header
            $as = sizeof($content);
            $r = 1;
            foreach ($content as $page) {
                $pdf->WriteHTML($page['content']);
                if (!empty($page['footer'])) {
                    $pdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
                }
                if ($as != $r) {
                    $pdf->AddPage();
                }
                $r++;
            }

        } else {

            $pdf->WriteHTML($content);
            if ($header != '') {
                $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', true);
            }
            if ($footer != '') {
                $pdf->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', true);
            }

        }

        if ($output_type == 'S') {
            $file_content = $pdf->Output('', 'S');
            write_file('assets/uploads/' . $name, $file_content);
            return 'assets/uploads/' . $name;
        } else {
            $pdf->Output($name, $output_type);
        }
    }

    public function print_arrays()
    {
        $args = func_get_args();
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo "</pre>";
        die();
    }

    public function logged_in()
    {
        return (bool) $this->session->userdata('identity');
    }


    public function is_admin($id = null){

        if ( ! $this->logged_in()) {
            return false;
        }

        if(!$id){
            $id = $this->session->userdata('user_id');
        }

        $group = $this->site->getUserGroup($id);

        if ($group){
            if ($group->name == "owner"){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    public function is_waiter($id = null){

        if ( ! $this->logged_in()) {
            return false;
        }

        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);

        if ($group->name == "waiter" || $group->name == "mozo"){
            return true;
        }else{
            return false;
        }
    }

    public function is_cashier($id = null){

        if ( ! $this->logged_in()) {
            return false;
        }

        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);

        if ($group->name == "cashier" || $group->name == "cajero"){
            return true;
        }else{
            return false;
        }
    }

    public function is_barman($id = null){

        if ( ! $this->logged_in()) {
            return false;
        }

        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);

        if ($group->name == "barman"){
            return true;
        }else{
            return false;
        }
    }


    public function is_chef($id = null){

        if ( ! $this->logged_in()) {
            return false;
        }

        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);

        if ($group->name == "chef" || $group->name == "cocinero"){
            return true;
        }else{
            return false;
        }
    }

    public function is_product_admin($id = null){

        if ( ! $this->logged_in()) {
            return false;
        }

        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);

        if ($group->name == "admin_product"){
            return true;
        }else{
            return false;
        }
    }


    public function getGroupByName($name = null) {
        return $this->db->get_where('groups', array('name' => $name), 1)->row();
    }




    public function in_group($check_group, $id = false)
    {
        if ( ! $this->logged_in()) {
            return false;
        }
        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);
        if ($group->name === $check_group) {
            return true;
        }
        return false;
    }

    public function log_payment($msg, $val = null)
    {
        $this->load->library('logs');
        return (bool) $this->logs->write('payments', $msg, $val);
    }

    public function update_award_points($total, $customer, $user, $scope = null)
    {
        if (!empty($this->Settings->each_spent) && $total >= $this->Settings->each_spent) {
            $company = $this->site->getCompanyByID($customer);
            $points = floor(($total / $this->Settings->each_spent) * $this->Settings->ca_point);
            $total_points = $scope ? $company->award_points - $points : $company->award_points + $points;
            $this->db->update('companies', array('award_points' => $total_points), array('id' => $customer));
        }
        if (!empty($this->Settings->each_sale) && !$this->Customer && $total >= $this->Settings->each_sale) {
            $staff = $this->site->getUser($user);
            $points = floor(($total / $this->Settings->each_sale) * $this->Settings->sa_point);
            $total_points = $scope ? $staff->award_points - $points : $staff->award_points + $points;
            $this->db->update('users', array('award_points' => $total_points), array('id' => $user));
        }
        return true;
    }

    public function zip($source = null, $destination = "./", $output_name = 'sma', $limit = 5000)
    {
        if (!$destination || trim($destination) == "") {
            $destination = "./";
        }

        $this->_rglobRead($source, $input);
        $maxinput = count($input);
        $splitinto = (($maxinput / $limit) > round($maxinput / $limit, 0)) ? round($maxinput / $limit, 0) + 1 : round($maxinput / $limit, 0);

        for ($i = 0; $i < $splitinto; $i++) {
            $this->_zip(array_slice($input, ($i * $limit), $limit, true), $i, $destination, $output_name);
        }

        unset($input);
        return;
    }

    public function unzip($source, $destination = './')
    {

        // @chmod($destination, 0777);
        $zip = new ZipArchive;
        if ($zip->open(str_replace("//", "/", $source)) === true) {
            $zip->extractTo($destination);
            $zip->close();
        }
        // @chmod($destination,0755);

        return true;
    }

    public function view_rights($check_id, $js = null)
    {
        if (!$this->Owner && !$this->Admin) {
            if ($check_id != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('warning', $this->data['access_denied']);
                if ($js) {
                    die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome') . "'; }, 10);</script>");
                } else {
                    redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
                }
            }
        }
        return true;
    }

    public function makecomma($input)
    {
        if (strlen($input) <= 2) {return $input;}
        $length = substr($input, 0, strlen($input) - 2);
        $formatted_input = $this->makecomma($length) . "," . substr($input, -2);
        return $formatted_input;
    }

    public function formatSAC($num)
    {
        $pos = strpos((string) $num, ".");
        if ($pos === false) {$decimalpart = "00";} else {
            $decimalpart = substr($num, $pos + 1, 2);
            $num = substr($num, 0, $pos);}

        if (strlen($num) > 3 & strlen($num) <= 12) {
            $last3digits = substr($num, -3);
            $numexceptlastdigits = substr($num, 0, -3);
            $formatted = $this->makecomma($numexceptlastdigits);
            $stringtoreturn = $formatted . "," . $last3digits . "." . $decimalpart;
        } elseif (strlen($num) <= 3) {
            $stringtoreturn = $num . "." . $decimalpart;
        } elseif (strlen($num) > 12) {
            $stringtoreturn = number_format($num, 2);
        }

        if (substr($stringtoreturn, 0, 2) == "-,") {$stringtoreturn = "-" . substr($stringtoreturn, 2);}

        return $stringtoreturn;
    }

    public function md()
    {
        die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome') . "'; }, 10);</script>");
    }

    public function analyze_term($term)
    {
        $spos = strpos($term, $this->Settings->barcode_separator);
        if ($spos !== false) {
            $st = explode($this->Settings->barcode_separator, $term);
            $sr = trim($st[0]);
            $option_id = trim($st[1]);
        } else {
            $sr = $term;
            $option_id = false;
        }
        return array('term' => $sr, 'option_id' => $option_id);
    }

    public function paid_opts($paid_by = null, $purchase = false)
    {
        $payment_means= file_get_contents('app/json/payment_means.json');
        $data=json_decode($payment_means,true);
        $datas = $data['payment_means'];
        $opts = '';
       
        foreach($datas as $data){
            $value = $data['code']."-".$data['name'];
            $name = $data['name'];
            if($data['code'] == 1){
                $opts = $opts.'<option value="'.$value.'" selected>'.$name.'</option>'; 
            }else{                
                $opts = $opts.'<option value="'.$value.'">'.$name.'</option>'; 
            }
        }

        if (!$purchase) {
//            $opts .= '<option value="deposit"'.($paid_by && $paid_by == 'deposit' ? ' selected="selected"' : '').'>'.lang("deposit").'</option>';
        }
        return $opts;
    }

    public function send_json($data)
    {
        header('Content-Type: application/json');
        die(json_encode($data));
        exit;
    }

    public function updateUserViewRight($id, $viewRight) {

        if ($this->db->update("users", array('view_right' => $viewRight ), array('id' => $id))) {
            return true;
        } else {
            return false;
        }
    }

    public function get_activated_tip($id = null) {

        if ( ! $this->logged_in()) {
            return false;
        }

        $id || $id = $this->session->userdata('user_id');

        $query = $this->db->get_where('users', array('id' => $id), 1);

        if ($query->num_rows() > 0) {

            $result = $query->row();

            if ($result->activated_tip) {
                return $result->activated_tip;
            }else{
                return false;
            }
        }
        return false;
    }

    public function set_activated_tip($activated_tip) {

        $activated_tip = ($activated_tip=="true") ?  1 : 0;

        if($this->db->update('users', array('activated_tip' => $activated_tip))){
            return TRUE;
        }
        return FALSE;

    }

    public function get_Waiter_On_Sale($id) {
        $query = $this->db->get_where('sma_suspended_bills', array('id' => $id), 1);

        if ($query->num_rows() > 0) {

            $result = $query->row();

            if ($result->id_waiter) {
                return $result->id_waiter;
            }else{
                return false;
            }
        }
        return false;
    }

    public function getGuest($idTable) {
        $query = $this->db->get_where('tables', array('id' => $idTable), 1);

        if ($query->num_rows() > 0) {

            $result = $query->row();

            if ($result->guests) {
                return $result->guests;
            }else{
                return false;
            }
        }
        return false;
    }

    public function get_Sessions() {

        $q = $this->db->get('sessions');
        return $q->result();

        return FALSE;
    }

    public function clean_Storage($url_redirect = null) {
        $clean = "<script>
                    if (localStorage.getItem('positems')) {
                            localStorage.removeItem('positems');
                    }
                    if (localStorage.getItem('posdiscount')) {
                            localStorage.removeItem('posdiscount');
                    }
                    if (localStorage.getItem('postax2')) {
                            localStorage.removeItem('postax2');
                    }
                    if (localStorage.getItem('order_tip')) {
                            localStorage.removeItem('order_tip');
                    }
                    if (localStorage.getItem('posshipping')) {
                            localStorage.removeItem('posshipping');
                    }
                    if (localStorage.getItem('posref')) {
                            localStorage.removeItem('posref');
                    }
                    if (localStorage.getItem('poswarehouse')) {
                            localStorage.removeItem('poswarehouse');
                    }
                    if (localStorage.getItem('postable')) {
                            localStorage.removeItem('postable');
                    }
                    if (localStorage.getItem('posnote')) {
                            localStorage.removeItem('posnote');
                    }
                    if (localStorage.getItem('posinnote')) {
                            localStorage.removeItem('posinnote');
                    }
                    if (localStorage.getItem('poscustomer')) {
                            localStorage.removeItem('poscustomer');
                    }
                    if (localStorage.getItem('poscurrency')) {
                            localStorage.removeItem('poscurrency');
                    }
                    if (localStorage.getItem('posdate')) {
                            localStorage.removeItem('posdate');
                    }
                    if (localStorage.getItem('posstatus')) {
                            localStorage.removeItem('posstatus');
                    }
                    if (localStorage.getItem('posbiller')) {
                            localStorage.removeItem('posbiller');
                    }";

                    if($url_redirect != null){
                        $clean .= "setTimeout(\"location.href='{$url_redirect}'\",100)";
                    }
                    $clean .= "</script>";

        echo $clean;
    }

    public function getUriOrderTable() {
        $this->load->library('restaurant');

        return $this->restaurant->getUriOrderTable();
    }

    public function get_products_tax($dateStart = null, $dateEnd = null, $user_id = null) {

        $this->db->select('SUM(COALESCE(product_tax, 0)) as product_tax');

        if(!$dateStart){
            $dateStart = $this->session->userdata('register_open_time');
        }

        if(!$user_id){
            $user_id = $this->session->userdata('user_id');
        }

        $this->db->where('date >=', $dateStart);

        if($dateEnd){
            $this->db->where('date <=', $dateEnd);
        }

        if($user_id){
            $this->db->where('created_by', $user_id);
        }

        $this->db->from('sales');

        $q = $this->db->get();
        if($q){
            if ($q->num_rows() > 0) {
                return $q->row();
            }
        }
        return FALSE;
    }

    public function sum_product_tax($pr_tax, $product_details, &$unit_price, &$price) {
        $item_net_price = $unit_price;
        $real_unit_price = $price;
        $item_tax = 0;
        $tax = "";
        $pr_item_tax = 0;

        if (isset($pr_tax) && $pr_tax != 0 && $pr_tax != null) {
            $tax_details = $this->site->getTaxRateByID($pr_tax);
            if(!empty($tax_details)){
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
                $pr_item_tax = $this->sma->formatDecimal($item_tax, 4);
            }
        }

        $unit_price = $item_net_price;
        $price = ($item_net_price + $pr_item_tax);

        return array($item_tax, $tax, $real_unit_price);
    }

}
