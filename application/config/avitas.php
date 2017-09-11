<?php defined('BASEPATH') OR exit('No direct script access allowed');
$config['settings'] = array('0'=>'settings', '1'=>'apisetting', '2'=>'email_from', '3'=>'email_send', '4'=>'usergroup', '5'=>'syncdata', '6'=>'chanel', '7'=>'outlet', '8'=>'warehouse', );
$config['apisetting'] = array('userid'=>'support.ax@annam-group.com', 'apikey'=>'ii7le418lgrjb288r2m8geb6aq286asg9kackgb683k6s', 'apihost'=>'http://10.0.5.39/webapp/api/index.php/inventaff', 'limit'=>'500', 
	'actions'=>array('salesorder'=>'GetOrder', 'saledetail'=>'GetSaleDetail', 'customer'=>'GetCustomer', 'custdiscount'=>'GetDiscount', 'itemstock'=>'GetStock', 'itembrand'=>'GetItemBrand', 'salesfoc'=>'GetSaleFoc', ), );
$config['email_from'] = array('subject'=>'[customer] @Tablet PO#[code] của [salesman] -- $$DemoApp', 'from'=>'', 'to'=>'', );
$config['email_send'] = array(
	'GT_HCM'=>array('to'=>'trang.ptt@annam-finefood.com', 'cc'=>'duy.dt@annam-group.com', ), 
	'GT_HAN'=>array('to'=>'admin.sales.hn@annam-finefood.com,thanh.nt@annam-finefood.com,thu.ptl@annam-finefood.com,nguyet.la@annam-finefood.com,', 'cc'=>'duy.dt@annam-group.com', ), 
	'MT_HCM'=>array('to'=>'hoai.ptn@annam-finefood.com', 'cc'=>'huong.ltx@annam-finefood.com,hoai.ptn@annam-finefood.com,duy.dt@annam-group.com', ), 
	'MT_DAN'=>array('to'=>'le.dnt@annam-group.com, posdn02@annam-finefood.com', 'cc'=>'duy.dt@annam-group.com', ), 
	'MT_CAT'=>array('to'=>'', 'cc'=>'', ), 
	'MT_HOA'=>array('to'=>'quyen.dtl@annam-finefood.com ', 'cc'=>'duy.dt@annam-group.com', ), 
	'MT_HUE'=>array('to'=>'tam.dth@annam-group.com', 'cc'=>'duy.dt@annam-group.com', ), 
	'MT_HAN'=>array('to'=>'admin.sales.hn@annam-finefood.com,thanh.nt@annam-finefood.com,thu.ptl@annam-finefood.com,nguyet.la@annam-finefood.com,', 'cc'=>'duy.dt@annam-group.com', ), 
	'GT_DAN'=>array('to'=>'le.dnt@annam-group.com, posdn02@annam-finefood.com', 'cc'=>'duy.dt@annam-group.com', ), 
	'GT_CAT'=>array('to'=>'', 'cc'=>'', ), 
	'GT_HOA'=>array('to'=>'quyen.dtl@annam-finefood.com ', 'cc'=>'duy.dt@annam-group.com', ), 
	'GT_HUE'=>array('to'=>'tam.dth@annam-group.com', 'cc'=>'duy.dt@annam-group.com', ), 
	'HC_HCM'=>array('to'=>'foodservice@annam-finefood.com, customerservice@annam-group.com', 'cc'=>'duy.dt@annam-group.com', ), 
	'HC_DAN'=>array('to'=>'le.dnt@annam-group.com, posdn02@annam-finefood.com, salesadmin.dn@annam-professional.com', 'cc'=>'duy.dt@annam-group.com', ), 
	'HC_CAT'=>array('to'=>'', 'cc'=>'', ), 
	'HC_HOA'=>array('to'=>'quyen.dtl@annam-finefood.com ', 'cc'=>'duy.dt@annam-group.com', ), 
	'HC_HUE'=>array('to'=>'tam.dth@annam-group.com', 'cc'=>'duy.dt@annam-group.com', ), 
	'HC_HAN'=>array('to'=>'admin.sales.hn@annam-finefood.com,thanh.nt@annam-finefood.com,thu.ptl@annam-finefood.com,nguyet.la@annam-finefood.com,', 'cc'=>'duy.dt@annam-group.com', ), );
$config['usergroup'] = array('90'=>'Saleman', '91'=>'Sales supervisor', '95'=>'Sale admin', '99'=>'Admin', );
$config['syncdata'] = array('todayago'=>'15', 'onhour'=>'22', 'onminute'=>'0', );
$config['chanel'] = array('GT'=>'GT', 'MT'=>'MT', 'HC'=>'HORECA', );
$config['outlet'] = array('HAN'=>'Hà Nội', );
$config['warehouse'] = array('HAN'=>'WHNW');
