<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends VS_Controller {

    private $_numof_request;
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('systemdb');
    }
	public function index()
	{
        if(!isAdmin())
        {
            $this->data['content'] = lang('<h3>This function belongs to admin. Please contact to him.</h3>');
        }
        else
        {
            $apisetting = $this->config->item('apisetting');
            $this->body['apisetting'] = $apisetting;
            $this->body['apitesting'] = $this->getAPIQuery('GetStock', 'WMAYW');
            $this->data['content'] = $this->load->view('sub/syncdata', $this->body, TRUE);
        }
        $this->load->view('backoffice', $this->data);
	}
    /*
    * API connections
    */
    function pulldata()
    {
        $this->_numof_request = 250;
        if(isAdmin())
        {
            set_time_limit(150);
            $this->load->library('xml2array');
            $get = getURLString();
            if(isset($get['item'] ) && $get['item'] == true)
            {
				set_time_limit(400);
                $this->updateItemStock();
				$this->updateStockDetail();
                $this->updateItemBrand();
				$this->updateSaleOnWH();
            }
            elseif(isset($get['cust']) && $get['cust'] == true)
            {
                $this->updateCustomer();
                $this->updateCustDisc();
				$this->updateSalePrice();
                $this->updateItemCustomer();
            }
            elseif(isset($get['foc']) && $get['foc'] == true)
            {
                $this->updateSaleFOC();
            }
            elseif(isset($get['sheader']) && $get['sheader'] == true)
            {
                $warehouse = $this->config->item('warehouse');
                if(count($warehouse) > 0)
                {
                    foreach ($warehouse as $key => $value) {
                        echo 'Update '.$value.' ## ';
                        $this->updateSaleHeader($value);
                    }
                }
                else
                    $this->updateSaleHeader();
            }
            elseif(isset($get['sline']) && $get['sline'] == true)
            {
                $warehouse = $this->config->item('warehouse');
                if(count($warehouse) > 0)
                {
                    foreach ($warehouse as $key => $value) {
                        echo 'Update '.$value.' ## ';
                        $this->updateSaleDetail($value);
                    }
                }
                else
                    $this->updateSaleDetail();
            }
        }
        else
            echo lang('You must be a administrator to execute this function');
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
        echo $msg.'<br/>';
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
        echo $msg.'<br/>';
    }
	
	private function updateStockDetail($action = 'GetStockDetail', $warehouse = '')
    {
        $msg = '';
        $page = 0;
        $count = 0;
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
                            $this->systemdb->delStockDetail();
                        }
                        $this->systemdb->addStockDetail($items);
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
        $msg = $msg.(nowdatetime().': Finish updating item stock detail '.$count.' lines ('.$page.' pages)');
        echo $msg.'<br/>';
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
        echo $msg.'<br/>';
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
        echo $msg.'<br/>';
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
        echo $msg.'<br/>';
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
        echo $msg.'<br/>';
    }
    
    private function updateSaleHeader($warehouse = 'WMAYW', $action = 'GetOrder')
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
                            $this->systemdb->delAxSaleHeader($fromdate, $todate, $warehouse);
                        }
                        $this->systemdb->addAxHeaderBatch($items);
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
        echo $msg.'<br/>';
    }
    
    private function updateSaleDetail($warehouse = 'WMAYW', $action = 'GetSaleDetail')
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
                            $this->systemdb->delAxSaleDetail($fromdate, $todate, $warehouse);
                        }
                        $this->systemdb->addAxSaleDetailBatch($items);
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
        echo $msg.'<br/>';
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
        echo $msg.'<br/>';
    }

    private function updateItemCustomer($action = 'GetItemOnCust')
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
                        $itemCust = $xml2array['xml']['item'];
                        $count += count($itemCust);
                        if(count($itemCust) > 0 && $page == 1)
                        {
                            $this->systemdb->delItemCustomer();
                        }
                        $this->systemdb->addItemCustBatch($itemCust);
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
        $msg = $msg.(nowdatetime().': Finish updating item on each customer '.$count.' lines');
        echo $msg.'<br/>';
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
        $parameters['format'] = 'xml';
        foreach($arrParams as $name=>$value)
        {
            if(!empty($value))
		      $parameters[strtolower($name)] = $value;
        }
		$queryString = $apiseting['apihost']."?".http_build_query($parameters, '', '&');
        return $queryString;
    }
    private function getAPIAction($action, $warehouse, $page = 1, $arrParams = array())
	{
        if (!function_exists('curl_setopt') || !function_exists('curl_setopt')) 
        {
            echo 'Please, require cURL and CLI installations on this server.' ; exit ; 
        }
        $queryString = $this->getAPIQuery($action, $warehouse, $page, $arrParams);
        //echo $queryString.'<br/>'; //return array();
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
    /*# End API*/
    /*
    * Parameter settings 
    */
    public function settings()
	{
        $configList = array();
        if(isAdmin())
        {
            $setname = $this->config->item('settings');
            foreach($setname as $name)
            {
                $configList[$name] = $this->config->item($name);
            }
            
            if(isset($_POST['Save']) && $_POST['Save'] && count($configList) > 0)
            {
                //--api
                $subcon = array();
                $i = 0;
                $con = isset($configList['apisetting']) ? $configList['apisetting'] : array();
                $actions = isset($con['actions']) ? $con['actions'] : array();
                
                $con['apikey'] = xss_clean($this->input->post('apikey'));
                $con['apihost'] = xss_clean($this->input->post('apihost'));
                $con['userid'] = xss_clean($this->input->post('userid'));
                $con['limit'] = xss_clean($this->input->post('limit'));
                
                foreach($actions as $na=>$va)
				{
					$va_new = $this->input->post($na);
                    $subcon[$na] = $va_new;
                } 
				$con['actions'] = $subcon;
                $configList['apisetting'] = $con;
				
				//--email_from
                $con = array();
                $con = isset($configList['email_from']) ? $configList['email_from'] : array();
				
                $con['from'] = xss_clean($this->input->post('from'));
                $con['subject'] = xss_clean($this->input->post('subject'));
				$con['to'] = '';
				
                $configList['email_from'] = $con;
				
				//--email_send
                $subcon = array();
                $i = 1;
                $con = isset($configList['email_send']) ? $configList['email_send'] : array();
				
                $chanel = isset($configList['chanel']) ? $configList['chanel'] : array();
				$outlet = isset($configList['outlet']) ? $configList['outlet'] : array();
				foreach($chanel as $chkey => $chvalue)
				{
					$subcon = array('to'=>'', 'cc'=>'');
					foreach($outlet as $outkey => $outvalue)
					{
						$type = $chkey.'_'.$outkey;
						$to = xss_clean($this->input->post($type.'_to'));
						$cc = xss_clean($this->input->post($type.'_cc'));
						$subcon = array('to'=>$to, 'cc'=>$cc);
						$con[$type] = $subcon;
					}	
				}
                $configList['email_send'] = $con;
                
				//--syncdata
                $con = isset($configList['syncdata']) ? $configList['syncdata'] : array();
				
                $con['todayago'] = xss_clean($this->input->post('todayago'));
                $con['onhour'] = xss_clean($this->input->post('hour'));
				$con['onminute'] = xss_clean($this->input->post('minute'));
				
                $configList['syncdata'] = $con;
				
                if(count($configList) > 0)
                {
					
					$content = "<?php defined('BASEPATH') OR exit('No direct script access allowed');";
					$content = $content."\r\n";
					foreach($configList as $key => $value)
					{
						$content = $content . '$'."config['".$key."'] = array(". $this->array2String($value) . ");\r\n";
					}
					file_put_contents(APPPATH.'config/avitas.php', $content);
                    $this->body['alert'] = lang('Updated succesfully');
                    goredirect('admin/settings', 'refresh', 2);
                }
                else
                    $this->body['alert'] = lang('Update is failed');
            }
        }
        $this->body['settings'] = $configList;
        $this->data['content'] = $this->load->view('sub/setting', $this->body, TRUE);
        $this->load->view('backoffice', $this->data);
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
    /*# End settings*/
    /*
    * Email section
    */
    public function alertEmail()
    {
        $get = getURLString();
        
        $body = '<h2>This is a testing email</h2>';
        $subject = lang('Tablet PO#Data Admin').'--'.base_url();
        if(isset($get['body']) && !empty($get['body']))
            $body = $get['body']; 
		
		$from_email = '';	
		$to_email = $this->session->userdata('email');
		sendEmailLocal($from_email, $to_email, $subject, $body, '');
        echo '<h3>'.lang('Email is sent').'.<h3>';
    }
    private function sendEmail($body, $obj)
    {
        $email_subj = $this->config->item('email_subj');
		$email_send = $this->config->item('email_send');
		$email = isset($email_send[getOutlet]) ? $email_send[getOutlet] : array();
        if(isset($email['to']) && !empty($mail['to']))
        {
            $sale_email = $this->session->userdata('email');
            $from_email = ((isset($email_subj['from']) && !empty($email_subj['from'])) ? $email_subj['from'] : '');
            $to_mail = $email['to'];
            $cc_mail = $sale_email.(isset($email['cc']) ? ','.$email['cc'] : '');
    		$subject = (isset($email_subj['subject']) ? $email_subj['subject'].'. ' : '');
            $subject = str_replace('[subject]', $obj, $subject).'--'.base_url();
            sendEmailLocal($from_email, $to_mail, $subject, $body, $cc_mail);
		}
    }
}
