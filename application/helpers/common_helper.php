<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('USER_ADMIN', 99);
define('USER_SALEADMIN', 95);
define('USER_SALEMAN', 90);

define('PAGE_ITEM', 12);
define('PAGE_SEARCH', 12);
define('PAGE_ITEM_BO', 30);

define('ACTION_DEL', 11);
define('ACTION_UPDATE', 12);
define('ACTION_DONE', 19);

define('ORDER_SAVE', 1);
define('ORDER_SEND', 2);

define('DATA_ALL', -100);

define('LOVING_ITEM', 1);
define('LOVING_CUSTOMER', 2);

define('CHANEL_MT', 'MT');
define('CHANEL_HC', 'HC');

define('OBJ_ITEM', 31);
define('OBJ_CUST', 32);
define('OBJ_CUSTDISC', 36);
define('OBJ_ORDER', 33);
define('OBJ_SALEFOC', 34);
define('OBJ_ORDERHEAD', 35);

define('APIGET_STOCK', 11);
define('APIGET_OHEAD', 12);
define('APIGET_ODETAIL', 13);
define('APIGET_CUST', 14);
define('APIGET_CUSTDISC', 15);
define('APIGET_FOC', 16);
define('APIGET_ITEMBRAND', 17);
define('APIPOST_SALES', 21);

function buildToken($userid){
    return md5('level'.$userid.nowdatetime());
}

function getUserToken(){
    $CI =& get_instance();
    if($CI->session->userdata('token'))
        return $CI->session->userdate('token');
    return '';
}

function nowdatetime(){
    return date("Y-m-d H:i:s");
}

function isLogin(){
    $CI =& get_instance();
    if($CI->session->userdata("email") && $CI->session->userdata("email")!=""
        && $CI->session->userdata("isLoggedIn") && $CI->session->userdata("isLoggedIn")==TRUE)
        return TRUE;    
    redirect(site_url('user/login'));
}

function isAdmin(){
    $CI =& get_instance();
    if($CI->session->userdata("group") && $CI->session->userdata("group")== USER_ADMIN)
        return TRUE;    
    return FALSE;
}

function getSalecode(){
    $CI =& get_instance();
    return $CI->session->userdata('salecode');
}

function getOutlet(){
    $CI =& get_instance();
    return $CI->session->userdata('outlet');
}

function getChanel(){
    $CI =& get_instance();
    return $CI->session->userdata('chanel');
}

function listOutlet($e = ''){
	$CI =& get_instance();
	$list = $CI->config->item('outlet');
	//$list = array('HCM'=>'Sài gòn', 'HAN'=>'Hà nội', 'CAT'=>'Cần thơ', 'DAN'=>'Đà nẵng');
	if(empty($e))
		return $list;
	return (isset($list[$e]) ? $list[$e] : '');
}

function listOutletCentral()
{
    return array('DAN', 'HOA', 'HUE');
}

function getWarehouse($outlet = ''){
	$CI =& get_instance();
	$wh = $CI->config->item('warehouse');
	//$wh = array('HCM'=>'WMAYW', 'HAN'=>'WHNW', 'CAT'=>'WCTW', 'DAN'=>'WDNW', 'HOA'=>'WHAW', 'HUE'=>'WHUEW');
	$outlet = empty($outlet) ? getOutlet() : $outlet;
	return (isset($wh[$outlet]) ? $wh[$outlet] : 'wmayw');
}

function getUserType(){
    $CI =& get_instance();
    return $CI->session->userdata('group');
}

function getUserId(){
    $CI =& get_instance();
    return $CI->session->userdata('userid');
}

function getFullname(){
    $CI =& get_instance();
    return $CI->session->userdata('fullname');
}

function lang($name){
    $CI =& get_instance();
    $text = $CI->lang->line($name);
    return empty($text) ? $name : $text;
}

function getLang(){
    $CI =& get_instance();
    $lang = $CI->session->userdata('lang');
    return empty($lang) ? 'vietnamese' : $lang;
}
function getApiAction($type){
    $CI =& get_instance();
    $action = $CI->config->item('apiaction');
    
}
function getURLString(){
    //parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);
    $url = $_SERVER['REQUEST_URI'];
    $aurl = explode('?', $url);
    if(count($aurl) > 1)
    {    
        $query = $aurl[1];
        parse_str($query, $params);
        return $params;
    }
    return array();
}
function generateOID($prefix = '')
{
    return $prefix.getUserId().strtotime(nowdatetime());
}
function goredirect($uri, $method = 'location', $time = 0, $http_response_code = 302)
{
    if ( ! preg_match('#^https?://#i', $uri))
	{
	   $uri = site_url($uri);
	}
	if($method)
    {
	   if($method == 'refresh')
	       header("Refresh:$time;url=".$uri);
       else
	       header("Location: ".$uri, TRUE, $http_response_code);
    }
}
function status($id)
{
    $status = array(
        '0' => 'Open',
        '1' => 'Open',
        '2' => 'Delivered',
        '3' => 'Invoiced',
        '4' => 'Cancelled',
        '99' => 'Full invoiced',
        '98' => 'Partial invoiced',
        '89' => 'Full delivered',
        '88' => 'Partial delivery',
        '79' => 'Full picked',
        '78' => 'Partial picked',
    );
    if(empty($id))
        return $id;
    return isset($status[$id]) ? $status[$id] : '';
}
function statusextra($id)
{
    $status = array(
        '0' => 'Open',
        '3' => 'Cancelled',
        '99' => 'Full invoiced',
        '98' => 'Partial invoiced',
        '89' => 'Full delivered',
        '88' => 'Partial delivery',
        '79' => 'Full picked',
        '78' => 'Partial picked',
    );
    if(empty($id))
        return $status[0];
    return isset($status[$id]) ? $status[$id] : '';
}
function calFromDate($todate, $dayago)
{
    $fromdate = date('Y-m-d', strtotime(-$dayago.' days', strtotime($todate)));
    return $fromdate;
}
function sendEmailLocal($from, $to, $subject, $body, $cc = '', $attached = '')
{
	$body = '<div class="order">'. $body .'<p><i class="note">P/s: Đây là đơn hàng tự động từ ứng dụng SaleTablet. Price và Discount chỉ mang tính tham khảo. Mọi thắc mắc vui lòng liên hệ với Salesman phụ trách. Cảm ơn.<br/>('.base_url().')</i></p></div>'
			.'<style style="text/css">td,th{padding:2px 10px; text-align:center; border: 1pt solid #ccc;font-size:10.5pt} .order{font-size:11pt;} table{border:none} .note{font-size:9.5pt;}</style>';
    
    if(empty($attached))
	{
		// Set content-type for sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers = $headers. "Content-type:text/html;charset=UTF-8" . "\r\n";

		// Additional headers
		$headers = $headers. 'From: '.(empty($from) ? 'Tablet Sales<support.ax@annam-group.com>' : $from) . "\r\n";
		$headers = $headers. (!empty($cc)?'CC: '.$cc. "\r\n" : '');

		// Send email
		$errno = 0;
		$errstr = '';
		return (mail($to, $subject, $body, $headers));
    }
    return (sendEmailAttached($from, $to, $subject, $body, $cc, $attached));
}

function sendEmailAttached($from, $to, $subject, $body, $cc, $attached)
{
	$CI =& get_instance();
	$config = Array(
		'mailtype' => 'html',
		'charset' => 'UTF-8',
		'wordwrap' => TRUE
	);
	$CI->load->library('email', $config);

	$CI->email->from($from);
	$CI->email->to($to); 
	$CI->email->cc($cc); 

	$CI->email->subject($subject);
	$CI->email->message($body); 
		
	$CI->email->attach($attached);  /* Enables you to send an attachment */
	if($CI->email->send())
		return TRUE;
	return FALSE;
}

function makePaging($curUrl, $curPage, $next = true)
{
	if($next == false && $curPage <= 1)
		return '';
	$paging = '<div class="pagination" style="text-align:center">';
	if($curPage > 1)
		$paging = $paging.'<a class="paging" href="'.$curUrl.'&page='.($curPage-1).'"><<< Trang trước</a>';
    $paging = $paging.('<span class="active">Trang '.$curPage.'</span>');
	if($next == true)
		$paging = $paging.('<a class="paging" href="'.$curUrl.'&page='.($curPage+1).'">Trang sau >>></a>');
    return ($paging.'</div>');
}

function docStatus($v){
	$list = array(
		0  => 'None',
		3  => 'Confirmation',
		4  => 'Picking list',
		5  => 'Packing slip',
		7  => 'Invoiced',
		13 => 'Cancelled',
	);
	return isset($list[$v]) ? $list[$v] : '';
}

function VSencode($value)
{
    return urlencode(base64_encode($value));
}

function VSdecode($value)
{
    return base64_decode(urldecode($value));
}
function getControl()
{
    $chanel = getChanel();
    if($chanel == CHANEL_MT)
        return 'salesmt';
    if($chanel == CHANEL_HC)
        return 'saleshc';
    return 'sales';
}

function getDispatchURL($type, $param = array(), $outlet = 'wmayw')
{
    $url = "http://10.0.11.154/dispatch/api/".$type; 

    if(strtoupper($outlet) == 'WHNW')
        $url = "http://10.0.15.18/dispatch/api/".$type;

    foreach ($param as $key => $value) {
        $url = $url .'/'.$key.'/'.$value;
    }
    return $url;
}