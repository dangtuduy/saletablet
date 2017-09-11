<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VS_Controller extends CI_Controller {

	public $data, $body;
    
    function __construct()
    {
        parent::__construct();
        $this->data['content'] = '';
        $this->body['error'] = '';
        $this->body['lang'] = getLang();
        $this->lang->load('common', $this->body['lang']);
        //$this->session->set_userdata(array('preurl'=>current_url()));
        isLogin();
    }
}

class VS_SaleController extends CI_Controller {

	public $data, $body;
    
    function __construct()
    {
        parent::__construct();
        $this->data['content'] = '';
        $this->body['error'] = '';
        $this->body['lang'] = getLang();
        $this->lang->load('common', $this->body['lang']);
        isLogin();
    }
	
	/*****/
	//-------------------- PRIVATE METHOD -------------------
	/*****/
	private function itemBrand()
    {
        $list = $this->salesdb->getItemBrand();
        $brandlist = array('0'=> 'All brands');
        foreach($list as $item)
        {
            $brandlist[$item['brandid']] = ucfirst(strtolower($item['name']));
        }
        return $brandlist;
    }
	
	/*****/
	//-------------------- PROTECTED METHOD -------------------
	/*****/
	protected function showItemlist($view_main, $view_sub)
    {
        $get = getURLString();
        $curentPage = (isset($get['page']) && $get['page'] > 0) ? $get['page'] : 1;
        $keyword['sritem'] = isset($get['sritem']) ? $get['sritem'] : '';
        $keyword['srname'] = isset($get['srname']) ? $get['srname'] : '';
        $keyword['srbcode'] = isset($get['srbcode']) ? $get['srbcode'] : '';
        $keyword['srbrand'] = isset($get['srbrand']) ? $get['srbrand'] : '';
		$keyword['srwh'] = isset($get['srwh']) ? $get['srwh'] : '';
		
		$saleonwh = $this->salesdb->getSaleOnWH(getSaleCode());
		
        $this->body['curpage'] = $curentPage;
        $this->body['keyword'] = $keyword;
        $this->body['brands'] = $this->itemBrand();
        $this->body['allItems'] = $this->salesdb->getItemByFields($keyword, ($curentPage-1)*PAGE_ITEM, PAGE_ITEM, $saleonwh);
        $this->data['content'] = $this->load->view($view_sub, $this->body, TRUE);
        $this->load->view($view_main, $this->data);
    }
	
	protected function showCustomerlist($view_main, $view_sub)
    {
        $get = getURLString();
        $curentPage = (isset($get['page']) && $get['page'] > 0) ? $get['page'] : 1;
        $search = array();
        $search['srcust'] = isset($get['srcust']) ? $get['srcust'] : '';
        $search['srname'] = isset($get['srname']) ? $get['srname'] : '';
        $search['srstreet'] = isset($get['srstreet']) ? $get['srstreet'] : '';
        $search['srphone'] = isset($get['srphone']) ? $get['srphone'] : '';
        $search['srclass'] = isset($get['srclass']) ? $get['srclass'] : '';
        $search['srsaleman'] = isset($get['srsaleman']) ? $get['srsaleman'] : '';
        $utype = getUserType();
        if($utype == USER_SALEMAN)
            $search['srsaleman'] = getSalecode();
        if(isset($_POST['Update']) && $_POST['Update'] == TRUE)
        {
            $custid = $this->input->post('custaccount');
            if(!empty($custid))
            {
                $sm = (isset($_POST['sm1']) & !empty($_POST['sm1'])) ? ($_POST['sm1']) : '';
                $sm = (isset($_POST['sm2']) & !empty($_POST['sm2'])) ? ((empty($sm)?'':$sm.';').($_POST['sm2'])) : $sm;
                $sm = (isset($_POST['sm3']) & !empty($_POST['sm3'])) ? ((empty($sm)?'':$sm.';').($_POST['sm3'])) : $sm;
                $sm = (isset($_POST['sm4']) & !empty($_POST['sm4'])) ? ((empty($sm)?'':$sm.';').($_POST['sm4'])) : $sm;
                $sm = (isset($_POST['sm5']) & !empty($_POST['sm5'])) ? ((empty($sm)?'':$sm.';').($_POST['sm5'])) : $sm;
                
                if($this->salesdb->updateCustLoving($custid, $sm))
                    $this->body['action'] = TRUE;
                else
                    $this->body['action'] = FALSE;
            }    
        }
        $this->body['keyword'] = $search;
        $this->body['curpage'] = $curentPage;
        $this->body['allSaleman'] = $this->salesdb->getSalecode();
        $this->body['custloving'] = $this->salesdb->getCustomerLoving($search);
        $this->body['allCust'] = $this->salesdb->getCustomerByFields($search, ($curentPage-1)*PAGE_ITEM, PAGE_ITEM);
        $this->data['content'] = $this->load->view($view_sub, $this->body, TRUE);
        
        $this->load->view($view_main, $this->data);
    }
	
	protected function showAxOrder($view_main, $view_sub)
    {
        $get = getURLString();
        $curentPage = (isset($get['page']) && $get['page'] > 0) ? $get['page'] : 1;
        $this->body['curpage'] = $curentPage;
        
        $saleman = getSaleCode();
		
        $keyword = array();
        $keyword['srso'] = isset($get['srso']) ? $get['srso'] : '';
        $keyword['srcust'] = isset($get['srcust']) ? $get['srcust'] : '';
        $keyword['sritem'] = isset($get['sritem']) ? $get['sritem'] : '';
        $keyword['srodate'] = isset($get['srodate']) ? $get['srodate'] : '';
        $this->body['keyword'] = $keyword;
        $this->body['allOrder'] = $this->salesdb->getAxSalesByFields($saleman, $keyword, ($curentPage-1)*PAGE_ITEM, PAGE_ITEM);
        $this->data['content'] = $this->load->view($view_sub, $this->body, TRUE);
        $this->load->view($view_main, $this->data);
    }
	
	protected function showOrderlist($view_main, $view_sub)
    {
        $userid = getUserId();
        $utype = getUserType();
        if($utype != USER_SALEMAN)
            $userid = DATA_ALL;
        
        $get = getURLString();
        $curentPage = isset($get['page']) ? $get['page'] : 1;
        $offset = ($curentPage - 1)* PAGE_ITEM;
        
        $keyword['srstreet'] = isset($get['srstreet']) ? $get['srstreet'] : '';
        $keyword['srcust'] = isset($get['srcust']) ? $get['srcust'] : '';
        $keyword['srodate'] = isset($get['srodate']) ? $get['srodate'] : '';
                
        $this->body['keyword'] = $keyword;
        $this->body['curpage'] = $curentPage;
        $this->body['orderlist'] = $this->salesdb->getAllOrders($userid, $keyword, $offset, PAGE_ITEM);
        $this->data['content'] = $this->load->view($view_sub, $this->body, TRUE);
        $this->load->view($view_main, $this->data);
    }
	
	protected function showAxDetail($view_main, $view_sub)
    {
        $get = getURLString();
        $curentPage = (isset($get['page']) && $get['page'] > 0) ? $get['page'] : 1;
        $this->body['curpage'] = $curentPage;
        
        if(isAdmin())
            $saleman = isset($get['sam']) ? $get['sam'] : DATA_ALL;
        else
            $saleman = getSaleCode();
        $keyword = array();
        $keyword['srso'] = isset($get['srso']) ? $get['srso'] : '';
        $keyword['srcust'] = isset($get['srcust']) ? $get['srcust'] : '';
        $keyword['sritem'] = isset($get['sritem']) ? $get['sritem'] : '';
        $odate = isset($get['srodate']) ? $get['srodate'] : '';
        if(!empty($odate))
        {
            $keyword['srodate'] = $odate;
            $odate_r = explode('..', $odate);
            $keyword['srfdate'] = $odate_r[0];
            if(count($odate_r) == 2)
                $keyword['srtdate'] = $odate_r[1];
        }
        $this->body['keyword'] = $keyword;
        $this->body['allOrder'] = $this->salesdb->getAxSalesDetail($saleman, $keyword, ($curentPage-1)*PAGE_ITEM, PAGE_ITEM);
        $this->data['content'] = $this->load->view($view_sub, $this->body, TRUE);
        $this->load->view($view_main, $this->data);
    }
	
	protected function showAxOrderDetail($view_main, $view_sub)//ajax
    {
        $get = getURLString();
        $oid = isset($get['oid']) ? $get['oid'] : '';
        $refoid = isset($get['refoid']) ? $get['refoid'] : '';
        $this->body['allOrder'] = $this->salesdb->getAxSalesDetail(DATA_ALL, array('srso'=>$oid, 'refoid'=>$refoid));
        $this->load->view($view_sub, $this->body);
    }
	
	protected function showFocItemlist($view_main, $view_sub)
    {
        $get = getURLString();
        $curentPage = (isset($get['page']) && $get['page'] > 0) ? $get['page'] : 1;
        $this->body['curpage'] = $curentPage;
        
        $keyword['sritem'] = isset($get['sritem']) ? $get['sritem'] : '';
        $keyword['srcust'] = isset($get['srcust']) ? $get['srcust'] : '';
        $keyword['srclass'] = isset($get['srclass']) ? $get['srclass'] : '';
        $keyword['srdate'] = isset($get['srdate']) ? $get['srdate'] : '';
		//$keyword['srwh'] = isset($get['srwh']) ? $get['srdate'] : getWarehouse();
        
        $this->body['keyword'] = $keyword;
        $this->body['allFocItem'] = $this->salesdb->getSalesFocByFields($keyword,($curentPage-1)*PAGE_ITEM, PAGE_ITEM);
        $this->data['content'] = $this->load->view($view_sub, $this->body, TRUE);
        $this->load->view($view_main, $this->data);
    }
	
	protected function showRevCurrent()
    {
		$get = getURLString();
		$todate = date('Y-m-d');
		
		if(isset($get['todate']) && !empty($get['todate']))
			$todate = date('Y-m-t', strtotime($get['todate']));
			
        $fromdate = date('Y-m', strtotime($todate)).'-01';
        $utype = getUserType();
        if($utype != USER_SALEMAN)
            $salecode = DATA_ALL;
        else
            $salecode = getSalecode();
		
		$this->body['todate'] = $todate;
        $this->body['salesamt'] = $this->salesdb->getRevenueByDate($salecode, $fromdate, $todate);
        $this->data['content'] = $this->load->view('sub/chart_revenue_date', $this->body, TRUE);
        $this->load->view('main_report', $this->data);
    }
    protected function showRevByMthStatus()
    {
        $get = getURLString();
        $month = isset($get['month']) ? $get['month'] : date('Y-m');
        $fromdate = $month.'-01';
        $todate = $month.'-31';
        
        $utype = getUserType();
        $salecode = getSalecode();
        if($utype != USER_SALEMAN)
            $salecode = DATA_ALL;
            
        $todate = date('Y-m-d');
        $fromdate = date('Y-m', strtotime('-4 month', strtotime($todate))).'-01';
        $this->body['salesamt'] = $this->salesdb->getSalesByStatus($salecode, $fromdate, $todate);
        $this->data['content'] = $this->load->view('sub/chart_revenue_4mth', $this->body, TRUE);
        $this->load->view('main_report', $this->data);
    }
    
    protected function showRevAllCust()
    {
        $get = getURLString();
        $month = isset($get['month']) ? $get['month'] : '';
        if(empty($month))
        {
            $fromdate = '2000-01-01';
            $todate = '2050-12-31';
        }
        else
        {
            $fromdate = $month.'-01';
            $todate = $month.'-31';
        }
        $utype = getUserType();
        $salecode = getSalecode();
        if($utype != USER_SALEMAN)
            $salecode = DATA_ALL;
        
        $this->body['month'] = $month;
        $this->body['salesamt'] = $this->salesdb->getRevenueByCust($salecode, $fromdate, $todate);
        
        $this->data['content'] = $this->load->view('sub/chart_revenue_custall', $this->body, TRUE);
        $this->load->view('main_report', $this->data);
    }
    
    protected function showRevOneCust()
	{
        $usertype = getUserType();
        $saleman = getSalecode();
        if($usertype != USER_SALEMAN)
            $saleman = DATA_ALL;
        $cust_arr = $this->salesdb->getCustomerByFields(array('srsaleman'=> $saleman),0,100);
        $custList = array('none' => '');
        foreach($cust_arr as $item)
        {
            $custList[$item['custid']] = $item['custid'].'-'.$item['name'];
        }
        $selectedCust = isset($_GET['customer']) ?  $_GET['customer'] : 'none';
        
        $todate = date('Y-m-d');
        $fromdate = date('Y-m-01', strtotime('-12 month', strtotime($todate)));
        $this->body['salesamt'] = $this->salesdb->getRevenueMthByCust($saleman, $fromdate, $todate, $selectedCust);
        $this->body['custList'] = $custList;
        $this->body['selectedCust'] = $selectedCust;
        $this->data['content'] = $this->load->view('sub/chart_revenue_custone', $this->body, TRUE);
        $this->load->view('main_report', $this->data);
	}
	
	protected function xmlneworder($oid, $custid, $custname, $delistreet, $comment, $itemlist)
    {
        if(!empty($oid))
        {
            $xmlsend = '<ORDERID>'.$oid.'</ORDERID><CUSTID>'.$custid.'</CUSTID><CUSTNAME>'.$custname.'</CUSTNAME><DELIVERYSTREET>'.$delistreet.'</DELIVERYSTREET><SALESMAN>'.getSalecode().'</SALESMAN><SALENOTE>'.$comment.'</SALENOTE>';
            $itemsend = '';
            foreach($itemlist as $item)
            {
                $itemsend = $itemsend.('<item><ITEMID>'.$item['itemid'].'</ITEMID><ITEMNAME>'.$item['itemname'].'</ITEMNAME><LINEQTY>'.$item['qty'].'</LINEQTY><LINEPRICE>'.$item['price'].'</LINEPRICE><LINENOTE>@@'.$item['note'].'</LINENOTE></item>');
            }
            $xmlsend = '<item>'.$xmlsend.'<ORDERITEM>'.$itemsend.'</ORDERITEM></item>';
            return $xmlsend;
        }
        return ''; 
    }
	
	protected function doPullAxOrder()//ajax
    {
        $get = getURLString();
        $refoid = isset($get['refoid']) ? $get['refoid'] : '';
        $axdetail = $this->getAPIAction('GetSaleDetail', 'WMAYW', 1, array('refoid'=>$refoid));
        
        if(isset($axdetail['status']))
            echo '<h3 class="center">'.(isset($axdetail['message']) ? lang($axdetail['message']) : lang('Exception error')).'</h3>';
        else
        {
            if(isset($axdetail['item']['SALESID']))//one item
            {
                $axdetail[0] = $axdetail['item'];
                unset($axdetail['item']);
            }
            else
                $axdetail = $axdetail['item'];
            
            if(count($axdetail) && isset($axdetail[0]['SALESID']))
            {
                $this->salesdb->deleteAxDetail($refoid);
                $this->salesdb->insertAxDetail($axdetail);
                $this->axorderdetail();
            }
            else
                echo '<h3 class="center">'.lang('Can not found this order').'</h3>';
        }
    }
	
	protected function doSend2ApiAgain()
    {
        $get = getURLString();
        
        $oid = isset($get['oid']) ? $get['oid'] : '';
		$note = isset($get['note']) ? $get['note'] : '';
		
        if(empty($oid))
        {
            echo lang('<h4 class="text-center">'.lang('Can not found this order'). '('.lang('OrderID is blank').')</h4>');
        }
        else
        {
            $detail = $this->salesdb->getOrderDetail(getUserId(), $oid);
			$bodyemail = '';
            if(isset($detail[0]['orderid']))
            {
                $first = $detail[0];
                $xmlsend = '<ORDERID>'.$first['orderid'].'</ORDERID><CUSTID>'.$first['custid'].'</CUSTID><CUSTNAME>'.$first['custname'].'</CUSTNAME><DELIVERYSTREET>'.$first['deliverystreet'].'</DELIVERYSTREET><SALESMAN>'.getSalecode().'</SALESMAN><SALENOTE>'.$first['comment'].'</SALENOTE>';
                          
				$itemsend = '';
                foreach($detail as $item)
                {
                    $itemsend = $itemsend.('<item><ITEMID>'.$item['itemid'].'</ITEMID><ITEMNAME>'.$item['itemname'].'</ITEMNAME><LINEQTY>'.number_format($item['qty'], 0).'</LINEQTY><LINEPRICE>1'.$item['price'].'</LINEPRICE><LINENOTE>@@'.$item['note'].'</LINENOTE></item>');
					$bodyemail = $bodyemail. ('<tr><td>'.$item['itemid'] .'</td><td>'.$item['itemname'].'</td><td>'.$item['qty'].'</td><td>'.number_format($item['price'],0).'</td><td>'.(($item['linepercent']>0)?$item['linepercent']:'').'</td><td>'.number_format($item['qty']*$item['price']*(1-$item['linepercent']*0.01)).'</td><td class="note">'.$item['note'].'</td></tr>'."\r\n");
                }
				$bodyemail = '<table cellspacing="0" cellpadding="0"><tr><th>Item </th><th>Name</th><th>Quantity</th><th>Price</th><th>Disc %</th><th>Amount</th><th>Note</th></tr><tbody>'.$bodyemail.'</tbody></table>';
				$bodyemail = '<div><b>Customer</b>:'.$first['custid'].' - '.$first['custname'].'<br/><b>Delivery</b>: '.$first['deliverystreet'].'<br/><b>Comment: </b><span style="color:blue">'.$note.'</span><br/><b>Sent on:</b>'.(isset($first['lastsend_datetime']) ? $first['lastsend_datetime'] : '').'</div><br/>'.$bodyemail;
                $xmlsend = '<item>'.$xmlsend.'<ORDERITEM>'.$itemsend.'</ORDERITEM></item>';
                $result = $this->execApiAction($xmlsend);
                
                if(isset($result['status']) && $result['status'] == TRUE)
                {
                    $header = array('lastsend_datetime'=> nowdatetime(), 'status'=> ORDER_SEND);
					
					$this->sendEmail($bodyemail, $first['orderid'].' #-AGAIN-#####', $first['custname']);
					
                    $this->salesdb->updateOrder($first['orderid'], $header);
                    echo lang('<h4 class="text-center">'.lang('Sent AX successfully').'</h4>');
                }
                else
                    echo lang('<div class="text-center"><h4>'.lang('Order').' #'.$oid.' '.lang('can not be sent to AX[API]').'.</h4><p>'.(isset($result['message'])?lang($result['message']):'').'.</p></div>');
            }
            else
                echo lang('<div class="text-center"><h4>'.lang('Can not found the order').' #'.$oid.'.</h4><h4>'.lang('Or it is not yourself').'.</h4>');
        }
    }
    
    protected function execApiAction($xmlsend)
    {
        $this->load->library('curl');
		$parameters = array();
		$params = array();
        $apiseting = $this->config->item('apisetting');
        
		$parameters['action'] = 'CreateSO';
		$parameters['userid'] = $apiseting['userid'];
        $parameters['storeid'] = getWarehouse(getOutlet());
		ksort($parameters);
        
        foreach ($parameters as $name => $value)
		{
			$params[] = rawurlencode($name) . '=' . rawurlencode($value);
		}
        $strToSign = strtolower(implode('&', $params));
        $parameters['signature'] = rawurlencode(hash_hmac('sha256', $strToSign, $apiseting['apikey'], false));
		
        $xmlsend = '<?xml version="1.0" ?><Request>'.$xmlsend.'</Request>';
        $queryString = $apiseting['apihost']."/?".http_build_query($parameters, '', '&');
        // Open Curl connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryString);
        // Save response to the variable $data
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlsend);     		
        $result = curl_exec($ch);		 
        // Close Curl connection
        curl_close($ch);
		
        if(empty($result))
            return array();
        $this->load->library('xml2array');
		//print $result;
		//if(strpos($result, 'xml version="1.0" encoding="utf-8"'))
		{
			$xml2array = Xml2array::createArray($result);
			$xml2array = isset($xml2array['xml']) ? $xml2array['xml'] : array();
			return $xml2array;  
		}
		//return array();
    }
    
    protected function getAPIAction($action, $warehouse, $page = 1, $arrParams = array())
	{
        $this->load->library('curl');
		$parameters = array();
		$params = array();
        $apiseting = $this->config->item('apisetting');
        
		$parameters['action'] = $action;//'GetStock';
		$parameters['userid'] = $apiseting['userid'];
        if(!empty($warehouse))
            $parameters['storeid'] = $warehouse;
		ksort($parameters);
        foreach ($parameters as $name => $value)
		{
			$params[] = rawurlencode($name) . '=' . rawurlencode($value);
		}
        $strToSign = strtolower(implode('&', $params));
        $parameters['signature'] = rawurlencode(hash_hmac('sha256', $strToSign, $apiseting['apikey'], false));
		
        $parameters['limit'] = $apiseting['limit'];
        $parameters['offset'] = ($page-1)* $apiseting['limit'];
        foreach($arrParams as $name=>$value)
        {
            if(!empty($value))
		      $parameters[strtolower($name)] = $value;
        }
		$queryString = $apiseting['apihost']."?".http_build_query($parameters, '', '&');
        
        //echo $queryString.'*****';
        // Open Curl connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryString);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);     		
        $data = curl_exec($ch); // as XML		 
        // Close Curl connection
        curl_close($ch);
        if(empty($data))
            return array();
        $this->load->library('xml2array');
        $xml2array = Xml2array::createArray($data);
        $xml2array = isset($xml2array['xml']) ? $xml2array['xml'] : array();
        return $xml2array;
	}
	
	protected function sendEmail($body, $oid, $customer = '')
    {
        $email_from = $this->config->item('email_from');
		$email_send = $this->config->item('email_send');
		$etype = $this->session->userdata('chanel').'_'.$this->session->userdata('outlet');
		
        if(isset($email_send[$etype]))
        {
			$emailgoing = $email_send[$etype];
			
			$from_email = $this->session->userdata('email');
			
			$sale_email = $this->session->userdata('managedby');
            $cc_mail = (empty($sale_email) ? $from_email : $from_email.','.$sale_email) . (isset($emailgoing['cc']) ? ','.$emailgoing['cc'] : '');
			
            $to_mail = (isset($emailgoing['to']) ? $emailgoing['to'] : '');
            
    		$subject = (isset($email_from['subject']) ? $email_from['subject'].'. ' : '');
            $subject = str_replace('[code]', $oid, $subject);
			$subject = str_replace('[salesman]', getSaleCode(). ' ' . getFullname(), $subject);
			$subject = str_replace('[customer]', $customer , $subject);
			$subject = str_replace('[wh]', getWarehouse(getOutlet()) , $subject);
			
            if(sendEmailLocal($from_email, $to_mail, $subject, $body, $cc_mail))
				echo '<h4 class="text-center">'.lang('Email is sent').'</h4>';
			else
				echo '<h4 class="text-center">'.lang('Error on sending email').'</h4>';
		}
    }
}

