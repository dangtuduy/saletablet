<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Syncauto extends CI_Controller {

    private $_numof_request;
    
	function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('systemdb');
    }
	
    function pulldata()
    {
		$message = '';
		
        $this->_numof_request = 250;
		$get = getURLString();
		$i = 1;
        if(isset($get['authekey']) && $get['authekey'] == '2985fc3f33bd9f9f916f01037d3795b1')//md5 annamGroup@2015
        {
            $this->load->library('xml2array');
			
            foreach($get as $key=>$value)
			{
				$i++;
				set_time_limit(100);
				if($key == 'item' && $value == 1)
				{
					set_time_limit(300);
					$message = $message . $this->updateItemStock();
					$message = $message . $this->updateItemBrand();
					$message = $message . $this->updateSalePrice();
					$message = $message . $this->updateSaleOnWH();
				}
				elseif($key == 'cust' && $value == 1)
				{
					$message = $message . $this->updateCustomer();
					$message = $message . $this->updateCustDisc();
				}
				elseif($key == 'foc' && $value == 1)
				{
					$message = $message . $this->updateSaleFOC();
				}
				elseif($key == 'sheader' && $value == 1)
				{
					$message = $message . $this->updateSaleHeader();
				}
				elseif($key == 'sline' && $value == 1)
				{
					$message = $message . $this->updateSaleDetail();
				}
			}
        }
        else
            $message = lang('Can not find authekey to execute this function');
		
		if(!empty($message))
			$this->alertEmail($message);
		echo $message;
    }
    
    private function updateItemBrand($action = 'GetItemBrand')
    {
        $page = 0;
        $count = 0;
        $msg = '';
        try
        {
            //do
            {
                $page++;
                $xmlItems = $this->getAPIAction($action, '', $page);
                if(empty($xmlItems))
                    break;
                else
                {    
                    $xml2array = Xml2array::createArray($xmlItems);
                           
                    if(isset($xml2array['status']) || isset($xml2array['xml']['status']))
                        break;
                    if(isset($xml2array['xml']['item']))
                    {
                        $brand = $xml2array['xml']['item'];
                        $count += count($brand);
                        if(count($brand) > 0 && $page == 1)
                        {
                            $this->systemdb->delItemBrand();
                        }
                        $this->systemdb->addItemBrand($brand);
                    }
                    else
                        break;
                }
            }
            //while(!empty($xmlItems) && $page <= $this->_numof_request);
        }
        catch(Exception $e)
        {
            $msg = 'Caught exception: '.$e->getMesage()."\n";
        }
        $msg = $msg.(nowdatetime().': Finish updating item brands '.$count.' lines');
        return $msg.'<br/>';
    }
    
    private function updateItemStock($action = 'GetStock', $warehouse = '')
    {
        $msg = '';
        $page = 0;
        $count = 0;
        //ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        try
        {
            do
            {
                $page++;
                $xmlItems = $this->getAPIAction($action, $warehouse, $page);
                
                if(empty($xmlItems))
                    break;
                else
                {
                    $xml2array = Xml2array::createArray($xmlItems);
                    if(isset($xml2array['status']) || isset($xml2array['xml']['status']))
                        break;
                    if(isset($xml2array['xml']['item']))
                    {
                        $items = $xml2array['xml']['item'];
                        $count += count($items);
                        if(count($items) > 0 && $page == 1)
                        {
                            $this->systemdb->delItemStock();
                        }
                        $this->systemdb->addItemBatch($items);
                    }
                    else
                        break;
                }
            }
            while(!empty($xmlItems) && $page < $this->_numof_request);
        }
        catch (Exception $e)
        {
            $msg = 'Caught exception: '.$e->getMesage()."\n";
        }
        $msg = $msg.(nowdatetime().': Finish updating item stock '.$count.' lines ('.$page.' pages)');
        return $msg.'<br/>';
    }
	
    private function updateSalePrice($action = 'GetPrice')
    {
        $msg = '';
        $page = 0;
        $count = 0;
        try
        {
            do
            {
                $page++;
                $xmlPrice = $this->getAPIAction($action, '', $page);
                
                if(empty($xmlPrice))
                    break;
                else
                {
					//$this->load->library('xml2array');
                    $xml2array = Xml2array::createArray($xmlPrice);
                    if(isset($xml2array['status']) || isset($xml2array['xml']['status']))
                        break;
                    if(isset($xml2array['xml']['item']))
                    {
                        $items = $xml2array['xml']['item'];
                        $count += count($items);
                        if(count($items) > 0 && $page == 1)
                        {
                            $this->systemdb->delSalePrice();
                        }
                        $this->systemdb->addSalePrice($items);
                    }
                    else
                        break;
                }
            }
            while(!empty($xmlPrice) && $page < $this->_numof_request);
        }
        catch (Exception $e)
        {
            $msg = 'Caught exception: '.$e->getMesage()."\n";
        }
        $msg = $msg.(nowdatetime().': Finish updating sale price '.$count.' lines ('.$page.' pages)');
        return $msg.'<br/>';
    }
	private function updateSaleOnWH($action = 'GetSaleOnWH')
    {
        $page = 0;
        $count = 0;
        $msg = '';
        try
        {
            do
            {
                $page++;
                $xmlItems = $this->getAPIAction($action, '', $page);
                if(empty($xmlItems))
                    break;
                else
                {    
                    $xml2array = Xml2array::createArray($xmlItems);
                           
                    if(isset($xml2array['status']) || isset($xml2array['xml']['status']))
                        break;
                    if(isset($xml2array['xml']['item']))
                    {
                        $userwh = $xml2array['xml']['item'];
                        $count += count($userwh);
                        if(count($userwh) > 0 && $page == 1)
                        {
                            $this->systemdb->delSaleOnWH();
                        }
                        $this->systemdb->addSaleWHBatch($userwh);
                    }
                    else
                        break;
                }
            }
            while(!empty($xmlItems) && $page <= $this->_numof_request);
        }
        catch(Exception $e)
        {
            $msg = 'Caught exception: '.$e->getMesage()."\n";
        }
        $msg = $msg.(nowdatetime().': Finish updating Sale on WH '.$count.' lines');
        return $msg.'<br/>';
    }
    private function updateCustomer($action = 'GetCustomer')
    {
        $msg = '';
        $page = 0;
        $count = 0;
        try
        {
            do
            {
                $page++;                
				$params = array();//array('group'=>'GT');
                $xmlCustomer = $this->getAPIAction($action, '', $page, $params);
                if(empty($xmlCustomer))
                    break;
                else
                {
                    $xml2array = Xml2array::createArray($xmlCustomer);
                        
                    if(isset($xml2array['status']) || isset($xml2array['xml']['status']))
                        break;
                    if(isset($xml2array['xml']['item']))
                    {
                        $items = $xml2array['xml']['item'];
                        $count += count($items);
                        if(count($items) > 0 && $page == 1)
                        {
                            $this->systemdb->delCustomer();
                        }
                        $this->systemdb->addCustBatch($items);
                    }
                    else
                        break;
                }
            }
            while($page < $this->_numof_request);
        }
        catch(Exception $ex)
        {
            $msg = 'Caught exception: '.$e->getMesage()."\n";
        }
        $msg = $msg.(nowdatetime().': Finish updating customer '.$count.' lines ('.$page.' pages)');
        return $msg.'<br/>';
    }
    private function updateCustDisc($action = 'GetDiscount')
    {
        $page = 0;
        $count = 0;
        $msg = '';
        try
        {
            do
            {
                $page++;
                $xmlDisc = $this->getAPIAction($action, '', $page);
                if(empty($xmlDisc))
                    break;
                else
                {
                    $xml2array = Xml2array::createArray($xmlDisc);
                    if(isset($xml2array['status']) || isset($xml2array['xml']['status']))
                        break;
                    if(isset($xml2array['xml']['item']))
                    {
                        $items = $xml2array['xml']['item'];
                        $count += count($items);
                        if(count($items) > 0 && $page == 1)
                        {
                            $this->systemdb->delDiscount();
                        }
                        $this->systemdb->addCustDisc($items);
                    }
                    else
                        break;
                }
            }
            while($page < $this->_numof_request);
        }
        catch(Exception $ex)
        {
            $msg = 'Caught exception: '.$e->getMesage()."\n";
        }
        $msg = $msg.(nowdatetime().': Finish updating discount '.$count.' lines ('.$page.' pages)');
        return $msg.'<br/>';
    }
    
    private function updateSaleHeader($action = 'GetOrder', $warehouse = 'WMAYW')
    {
        $page = 0;
        $count = 0;
        $msg = '';        
        try
        {
            set_time_limit(1000);
            do
            {
                $page++;
                $params = array();
                $syncdata = $this->config->item('syncdata');
                $todate = date('Y-m-d');
                $fromdate = calFromDate($todate, isset($syncdata['todayago']) ? $syncdata['todayago'] : 0);  
                    
                $params = array(
                        'salesman'=> isset($get['sm'])? $get['sm'] : '',
                        'orderid'=> isset($get['axoid'])? $get['axoid']: '',
                        'refoid'=> isset($get['refoid'])? $get['refoid']:'',
                        'fromdate'=> $fromdate, 'todate'=> $todate,
                        );
                        
                $xmlOrder = $this->getAPIAction($action, $warehouse, $page, $params);
                if(empty($xmlOrder))
                    break;
                else
                {
                    $xml2array = Xml2array::createArray($xmlOrder);
                        
                    if(isset($xml2array['status']) || isset($xml2array['xml']['status']))
                        break;
                    if(isset($xml2array['xml']['item']))
                    {
                        $items = $xml2array['xml']['item'];
                        $count += count($items);
                        if(count($items) > 0 && $page == 1)
                        {
                            $this->systemdb->delSaleHeader($fromdate, $todate);
                        }
                        $this->systemdb->addOHeaderBatch($items);
                    }
                    else
                        break;
                }
            }
            while ($page < $this->_numof_request);
        }
        catch(Exception $ex)
        {
            $msg = 'Caught exception: '.$e->getMesage()."\n";
        }
        $msg = $msg.(nowdatetime().': Finish updating sales header '.$count.' lines ('.$page.' pages)');
        return $msg.'<br/>';
    }
    
    private function updateSaleDetail($action = 'GetSaleDetail', $warehouse = 'WMAYW')
    {
        $page = 0;
        $count = 0;
        $msg = '';
        try
        {
            set_time_limit(1000);
            do
            {
                $page++;
                $params = array();
                $syncdata = $this->config->item('syncdata');
                $todate = date('Y-m-d');
                $fromdate = calFromDate($todate, isset($syncdata['todayago']) ? $syncdata['todayago'] : 0);    
                $params = array(
                        'salesman'=> isset($get['sm'])? $get['sm'] : '',
                        'orderid'=> isset($get['axoid'])? $get['axoid']: '',
                        'refoid'=> isset($get['refoid'])? $get['refoid']:'',
                        'fromdate'=> $fromdate, 'todate'=> $todate,
                        );        
                $xmlOrder = $this->getAPIAction($action, $warehouse, $page, $params);
                if(empty($xmlOrder))
                    break;
                else
                {
                    $xml2array = Xml2array::createArray($xmlOrder);
                        
                    if(isset($xml2array['status']) || isset($xml2array['xml']['status']))
                        break;
                    if(isset($xml2array['xml']['item']))
                    {
                        $items = $xml2array['xml']['item'];
                        $count += count($items);
                        if(count($items) > 0 && $page == 1)
                        {
                            $this->systemdb->delOrderExt($fromdate, $todate);
                        }
                        $this->systemdb->addOrderExtBatch($items);
                    }
                    else
                        break;
                }
            }
            while ($page < $this->_numof_request);
        }
        catch(Exception $ex)
        {
            $msg = 'Caught exception: '.$e->getMesage()."\n";
        }
        $msg = $msg.(nowdatetime().': Finish updating sales detail '.$count.' lines ('.$page.' pages)');
        return $msg.'<br/>';
    }
    
    private function updateSaleFOC($action = 'GetSaleFoc', $warehouse = 'WMAYW')
    {
        $page = 0;
        $count = 0;
        $msg = '';
        try
        {
            do
            {
                $page++;
                $xmlReturn = $this->getAPIAction($action, '', $page);
                if(empty($xmlReturn))
                    break;
                else
                {
                    $xml2array = Xml2array::createArray($xmlReturn);
                    if(isset($xml2array['status']) || isset($xml2array['xml']['status']))
                        break;
                    if(isset($xml2array['xml']['item']))
                    {
                        $items = $xml2array['xml']['item'];
                        $count += count($items);
                        if(count($items) > 0 && $page == 1)
                        {
                            $this->systemdb->delFocSale();
                        }
                        $this->systemdb->addFocItemBatch($items);
                    }
                    else
                        break;
                }
            }
            while ($page < $this->_numof_request);
        }
        catch(Exception $ex)
        {
            $msg = 'Caught exception: '.$e->getMesage()."\n";
        }
        $msg = $msg.(nowdatetime().': Finish updating FOC '.$count.' lines ('.$page.' pages)');
        return $msg.'<br/>';
    }
    private function getAPIQuery($action, $warehouse, $page = 1, $arrParams = array())
    {
        $this->load->library('curl');
		$parameters = array();
		$params = array();
        $apiseting = $this->config->item('apisetting');
        
		$parameters['action'] = $action;
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
		//$queryString = "http://10.0.5.38/webapi/index.php/inventaff?".http_build_query($parameters, '', '&');
        return $queryString;
    }
    private function getAPIAction($action, $warehouse, $page = 1, $arrParams = array())
	{
        if (!function_exists('curl_setopt') || !function_exists('curl_setopt')) 
        {
            echo 'Requires cURL and CLI installations.' ; exit ; 
        }
        $queryString = $this->getAPIQuery($action, $warehouse, $page, $arrParams);
        //echo $queryString.'<br/>';
        // Open Curl connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $queryString);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);     		
        $data = curl_exec($ch); // as XML		 
        // Close Curl connection
        curl_close($ch);
        return utf8_encode($data);
	}
	
    private function array2String($config = array())
    {
        $str = '';
		foreach($config as $key => $value)
		{
			if(is_array($value))
				$str = $str."\r\n\t"."'".$key."'=>array(". $this->array2String($value)."), ";
			else
				$str = $str."'".$key."'=>'".$value."', ";
		}
		return $str;
    }
    
    private function alertEmail($body)
    {
        $body = '<h2>This is the email for pulling data</h2>'.$body;
        $subject = lang('Tablet PO#Data Admin').'--'.base_url();	
		sendEmailLocal('', 'duy.dt@annam-group.com', $subject, $body, '');
    }
}
