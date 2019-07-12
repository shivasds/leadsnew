<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('email_config'))
{
    function email_config()
    {
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://smtp.gmail.com';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'fasilk008@gmail.com';
        $config['smtp_pass']    = 'gjrsqxauohnslzag';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not 

        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';

        return $config;
    }   
}

if(!function_exists('paginitaion')) {
    function paginitaion($url, $segment, $perpage, $totalRecords)
    {
        $CI =& get_instance();
        //pagination config
        $config['base_url']    = $url;
        $config['uri_segment'] = $segment;
        $config['total_rows']  = $totalRecords;
        $config['per_page']    = $perpage;
        
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $CI->pagination->initialize($config);
        return $CI->pagination->create_links();
    }
}

if(!function_exists('paginitaionWithQueryString')) {
    function paginitaionWithQueryString($url, $segment, $perpage, $totalRecords, $suffix)
    {
        $CI =& get_instance();
        //pagination config
        $config['base_url']    = $url;
        $config['uri_segment'] = $segment;
        $config['total_rows']  = $totalRecords;
        $config['per_page']    = $perpage;
        $config['suffix']      = '?' .urldecode(http_build_query($suffix));
        $config['first_url']    = $config['base_url'] . $config['suffix'];
        //styling
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['next_tag_open'] = '<li class="pg-next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="pg-prev">';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $CI->pagination->initialize($config);
       return $CI->pagination->create_links();
    }
}