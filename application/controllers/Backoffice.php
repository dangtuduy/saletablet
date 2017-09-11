<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backoffice extends VS_SaleController {

	function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('salesdb');
        $this->body['utype'] = getUserType();
		if($this->body['utype'] == USER_SALEMAN)
			redirect(site_url('sales'));
    }
    function phpinfo()
	{
		echo phpinfo();
	}
	public function index()//dashboard
    {
		$this->orderlist();
    }
    
    function itemlist()
    {
		$this->viewItemList('backoffice', 'sub/ax_itemlist_bo');
    }
    
    function customerlist()
    {
		$this->viewCustomerList('backoffice', 'sub/ax_customer_bo');
    }
    
    function axorders()
    {
		$this->viewAXOrderList('backoffice', 'sub/ax_saleorders_bo');
    }
    
    function axdetail()
    {
		$this->viewAXDetail('list', 'sub/ax_saleitem_bo');
    }
    
    function axorderdetail()//ajax
    {
		$this->viewDetailPerAxOrder('sub/ax_orderinfo');
    }
    
    function pullAxOrder()//ajax
    {
        $this->doPullAXOrder();
    }
    
    function focitemlist()
    {
		$this->viewFocItemlist('backoffice', 'sub/ax_salesfoc_bo');
    }
    
    function orderlist()
    {
		$this->viewOrderlist('backoffice', 'sub/order_list_bo');
    }
    
	function sendtoapi()
    {
        $this->doSendItem2API();//again
    }
    
    function reports()
    {
        $type = $this->uri->rsegment(3);
        if($type == 2)
            $this->revenueOneCust();
        elseif($type == 4)
            $this->revenueAllCust();
        elseif($type == 3)
            $this->revenueByMthStatus();
        else
            $this->revenue_current();
    }
    
    public function removeAllSaleLoving()
    {
    	$usertype = getUserType();
        if($usertype == USER_ADMIN)
        {
	    	$get = getURLString();
	    	$saleCode = isset($get['scode']) ? VSdecode($get['scode']) : '';
			if(!empty($saleCode))
			{
				$this->salesdb->removeAllCustLoving($saleCode);
				return $saleCode;
			}
			return 'none';
		}
		return '';
    }
	public function saleslist()
	{
		$usertype = getUserType();
        if($usertype == USER_SALEMAN)
			redirect(site_url('sales'));
		
		$get = getURLString();
        $salemail = isset($get['sm']) ? VSdecode($get['sm']) :''; 

		if(!empty($salemail))
		{
			$this->load->model('userdb');
			$account = $this->userdb->getInfo($salemail, -1);
			if(isset($_POST['AddCustomer']) && $_POST['AddCustomer'] == TRUE && isset($account['salecode']) && !empty($account['salecode']))
			{
				$add_cust = $this->input->post('morecust');
				$list = array();
				$cust_arr = explode(',', $add_cust);
				foreach($cust_arr as $item)
				{
					$list[] = array('custid'=>trim($item), 'salecode'=>trim($account['salecode']));
				}
				
				$this->salesdb->updateCustLovingList($list);
			}
			else 
			{
				$cust = isset($get['del']) ? $get['del'] : '';
				if(!empty($cust))
					$this->salesdb->removeCustLoving($cust, $account['salecode']);
			}
			$search['srsaleman'] = isset($account['salecode']) ? $account['salecode'] : '@';
			$this->body['saleman'] = $account;
			$this->body['allCust'] = $this->salesdb->getCustomerByFields($search, 0, 300);
		}
		else
		{
			$this->load->model('userdb');
			
			if($usertype == USER_ADMIN)
				$this->body['allUser'] = $this->userdb->getActiveSalesman(0, 100);
			else
			{
				$outlet = getOutlet();
				if($outlet == 'DAN')
				{
					$list = array();
					foreach (listOutletCentral() as $value) {
						$ls = $this->userdb->getActiveSalesman(0, 100, $value, getChanel());
						$list = array_merge($ls, $list);
					}
					$this->body['allUser'] = $list;
				}
				else
					$this->body['allUser'] = $this->userdb->getActiveSalesman(0, 100, getOutlet(), getChanel());
			}
		}
		$this->data['content'] = $this->load->view('sub/saleslist_bo', $this->body, TRUE);
		$this->load->view('backoffice', $this->data);
	}
}
