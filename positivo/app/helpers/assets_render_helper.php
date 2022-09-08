<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(!function_exists('add_js')){
    function add_js($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_js  = $ci->config->item('header_js');
        
        if(empty($file)){
            return;
        }
 
        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_js[] = $item;
            }
            $ci->config->set_item('header_js',$header_js);
        }else{
            $str = $file;
            $header_js[] = $str;
            $ci->config->set_item('header_js',$header_js);
        }
    }
}
 
//Dynamically add CSS files to header page
if(!function_exists('add_css')){
    function add_css($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('header_css');
 
        if(empty($file)){
            return;
        }
 
        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){   
                $header_css[] = $item;
            }
            $ci->config->set_item('header_css',$header_css);
        }else{
            $str = $file;
            $header_css[] = $str;
            $ci->config->set_item('header_css',$header_css);
        }
    }
}
 
if(!function_exists('put_headers')){
    function put_headers()
    {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('header_css');
        $header_js  = $ci->config->item('header_js');
 
        foreach($header_css AS $item){
            if (strpos($item, "//") !== false) {
                $str .= '<link rel="stylesheet" href="'.$item.'" type="text/css" />'."\n";
            } else {
                $str .= '<link rel="stylesheet" href="'.base_url().'themes/'.$ci->config->item('theme').'/assets/css/'.$item.'" type="text/css" />'."\n";
            }
        }
 
        foreach($header_js AS $item){
            if (strpos($item, "//") !== false) {
                $str .= '<script type="text/javascript" src="'.$item.'"></script>'."\n";
            } else {
                $str .= '<script type="text/javascript" src="'.base_url().'themes/'.$ci->config->item('theme').'/assets/js/'.$item.'"></script>'."\n";
            }
        }
 
        return $str;
    }

}

if(!function_exists('put_footer')){
    function put_footer()
    {
        $str = '';
        $ci = &get_instance();
        $footer_js  = $ci->config->item('footer_js');

        foreach($footer_js AS $item){
            if (strpos($item, "//") !== false) {
                $str .= '<script type="text/javascript" src="'.$item.'"></script>'."\n";
            } else {
                $str .= '<script type="text/javascript" src="'.base_url().'themes/'.$ci->config->item('theme').'/assets/js/'.$item.'"></script>'."\n";
            }
        }

        return $str;
    }

}

if ( ! function_exists('format_interval'))
{
    function format_interval(DateInterval $interval) {

        // Load settings languages
        $ci = &get_instance();
        $ci->lang->load('settings', $ci->Settings->user_language);

        $result = "";
        if ($interval->y) { $result .= $interval->format("%y ".lang("years")." "); }
        if ($interval->m) { $result .= $interval->format("%m ".lang("months")." "); }
        if ($interval->d) { $result .= $interval->format("%d ".lang("days")." "); }
        if ($interval->h) { $result .= $interval->format("%h ".lang("hours")." "); }
        if ($interval->i) { $result .= $interval->format("%i ".lang("minutes")." "); }
        if (!$interval->d && $interval->s) { $result .= $interval->format("%s ".lang("seconds")." "); }

        return $result;
    }
}

if ( ! function_exists('compare'))
{
    function compare($a, $b)
    {
        $withC = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú');
        $withoutC = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');

        $a =  str_replace($withC, $withoutC, $a->name);
        $b =  str_replace($withC, $withoutC, $b->name);

        return strcmp($a, $b);
    }
}

if (!function_exists("put_header_alerts")){
    
    function put_header_alerts($message = null ,$error = null ,$warning = null,$info = array()){
            ?>
            <div class="row">
               <div class="col-xs-12">
                    <?php if ($message) { ?>
                    <div class="margin-05">
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= $message; ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if ($error) { ?>
                    <div class="margin-05">
                        <div class="alert alert-danger">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= $error; ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if ($warning) { ?>
                   <div class="margin-05">
                        <div class="alert alert-warning">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <?= $warning; ?>
                        </div>
                   </div>
                    <?php } ?>
                    <?php
                    if ($info) {
                        foreach ($info as $n) {
                            if (!$this->session->userdata('hidden' . $n->id)) {
                                ?>
                                <div class="alert alert-info">
                                    <a href="#" id="<?= $n->id ?>" class="close hideComment external"
                                       data-dismiss="alert">&times;</a>
                                    <?= $n->comment; ?>
                                </div>
                            <?php }
                        }
                    } ?>
                    <div class="alerts-con"></div>
                 </div>
            </div>
            <?php
    }
}