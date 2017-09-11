<?php
class Salesdb extends CI_Model{
	
    private $_rowNumber;
	private $_default;
	
    function __contruct(){
        parent::__construct();
        $this->load->database();
    }
	private function initValue()
	{
		$this->_default = $this->config->item('default');
		
		if(!isset($this->_default['pricegroup']))
			$this->_default['pricegroup'] = '';
	}
    /*
    * Insert section
    */
    function saveOrder($header = array(), $line = array())
    {
        if(count($header) < 1 || count($line) < 1)
            return FALSE;
        $this->db->trans_begin();
        $this->db->insert('salesheader', $header);
        $this->db->insert_batch('salesline', $line);
        if($this->db->trans_status() == FALSE)
            $this->db->trans_rollback();
        else
            $this->db->trans_commit();
        return $this->db->trans_status();
    }
    function updateOrder($oid, $header = array())
    {
        if(count($header) > 0 && !empty($oid))
        {
            $this->db->where('orderid', $oid);
            if($this->db->update('salesheader', $header))
                return TRUE;
        }
        return FALSE;
    }
    function deleteAxDetail($refoid)
    {
        if(!empty($refoid))
        {
            $this->db->where('refoid', $refoid);
            if($this->db->delete('axsaledetail'))
                return TRUE;
                return TRUE;
        }
        return FALSE;
    }
    function insertAxDetail($axDetail = array())
    {
        if(count($axDetail) && isset($axDetail[0]['SALESID']))
        {
            if($this->db->insert_batch('axsaledetail', $axDetail))
                return TRUE;
        }
        return FALSE;
    }
    function updateCustLoving($custid, $salesman)
    {
        if(!empty($custid))
        {
            $custLoving = $this->getCustomerLoving(array('srcust'=>$custid));
            if(count($custLoving) > 0 && isset($custLoving[0]['clid']))
            {
                $this->db->where('custaccount', $custid);
                $data = array('salemans'=> $salesman, 'updated_date'=> nowdatetime());
                if($this->db->update('customerloving', $data))
                    return TRUE;
            }
            else
            {
                $data = array('salemans'=>$salesman.';', 'custaccount'=>$custid, 'created_by'=> getUserId(), 'created_date'=> nowdatetime());
                if($this->db->insert('customerloving',$data))
                    return TRUE;
            }
        }
        return FALSE;
    }
	function updateCustLovingList($list = array())
	{
		if(count($list) > 0)
		{
            //print_r($list);
			foreach($list as $item)
			{//print_r($item);
				if(isset($item['custid']) && !empty($item['custid']) && !empty($item['salecode']))
				{
					$custLoving = $this->getCustomerLoving(array('srcust'=>$item['custid']));
					
					if(count($custLoving) > 0)
					{
                        $custLoving = $custLoving[0];
						$salemans = str_replace($item['salecode'].';', '', (isset($custLoving['salemans']) ? $custLoving['salemans'] : ''));//remove duplicate
						$salemans = str_replace($item['salecode'], '', $salemans);
						$salemans = $salemans . $item['salecode'] . ';';
						
						$this->db->where('clid', $custLoving['clid']);
						$data = array('salemans'=> $salemans, 'updated_date'=> nowdatetime());
						$this->db->update('customerloving', $data);
					}
					else
					{// first time
						$data = array('salemans'=>$item['salecode'].';', 'custaccount'=>$item['custid'], 'created_by'=> getUserId(), 'created_date'=> nowdatetime());
						$this->db->insert('customerloving',$data);
					}
				}
			}			
			return TRUE;
		}
		return FALSE;
	}

	function removeCustLoving($custid, $salecode)
    {
        if(!empty($custid))
        {
            $custLoving = $this->getCustomerLoving(array('srcust'=>$custid));
			$custLoving = count($custLoving) > 0 ? $custLoving[0] : $custLoving;
            if(isset($custLoving['clid']))
            {
				$salemans = str_replace($salecode.';', '', $custLoving['salemans']);
				$salemans = str_replace(';'.$salecode, '', $salemans);
				$salemans = str_replace($salecode, '', $salemans);
				while(isset($salemans[0]) && $salemans[0] == ';')
				{
					$salemans = substr($salemans, 1, strlen($salemans)-1);
				}
                $this->db->where('custaccount', $custid);
                $data = array('salemans'=> $salemans, 'updated_date'=> nowdatetime());
				
                if($this->db->update('customerloving', $data))
                    return TRUE;
            }
        }
        return FALSE;
    }

    function removeAllCustLoving($saleCode)
    {
        if(!empty($saleCode))
        {
            $custLoving = $this->getCustomerLoving(array('srsaleman'=>$saleCode.';'));
            if(count($custLoving) > 0)
            {
                foreach ($custLoving as $key => $value) 
                {
                    $salemans = str_replace($saleCode.';', '', $value['salemans']);
                    $salemans = str_replace(';;', ';', $salemans);

                    $this->db->where('clid', $value['clid']);
                    $data = array('salemans'=> $salemans, 'updated_date'=> nowdatetime());
                
                    $this->db->update('customerloving', $data);
                }
                return TRUE;
            }
        }
        return FALSE;
    }
    /*
    * Select section
    */
    function getNumOfRow()
    {
        return $this->_rowNumber;
    }
    function getAllOrders($userid, $search = array(), $offset = 0, $limit = 100)
    {
        $this->db->from('salesheader');
        
        if($userid != DATA_ALL)
            $this->db->where('userid', $userid);
        
        if(!empty($search['srstreet']))
            $this->db->like('deliverystreet', $search['srstreet']);
        if(isset($search['srcust']) && !empty($search['srcust']))
            $this->db->where("(custid LIKE '%".$search['srcust']."%' OR custname LIKE '%".$search['srcust']."%')");
        if(!empty($search['srodate']))
            $this->db->where('DATE(created_datetime)', $search['srodate']);
            
        $this->db->order_by('created_datetime DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    function getSaleOnWH($salecode)
	{
		$this->db->from('axsaleonwh');
		$this->db->where('salecode', $salecode);
		
        $query = $this->db->get();
        $array = $query->result_array();
		$onWarehouse = array(getWarehouse());
		foreach($array as $item)
		{
			$onWarehouse[] = $item['warehouse'];
		}
		return $onWarehouse;
	}
    
    function getOrderDetail($userid, $saleid)
    {
        $this->db->select(' salesheader.orderid, custid, status, custname, deliverystreet, salesheader.created_datetime, lastsend_datetime, comment, type, attachedfile, itemid, itemname, qty, price, note, linepercent, counted, minqty');
        
        $this->db->from('salesheader');
        
        $this->db->join('salesline', 'salesheader.orderid = salesline.orderid');
        
        if($userid != DATA_ALL)
            $this->db->where('userid', $userid);

        $this->db->where('salesheader.orderid', $saleid);
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getItemByFields($search = array(), $offset = 0, $limit = 15, $onwh = array())
    {
		self::initValue();
		
		$this->db->select('axitem.itemid, itemname, barcode, onhand, axstockdetail.onorder, brandid, unitsale, unitid, price, axitem.warehouse, axitem.created_datetime, availphysical, inventbatch');
        $this->db->from('axitem');
		$this->db->join('axstockdetail', 'axitem.itemid = axstockdetail.itemid and axitem.warehouse = axstockdetail.warehouse');
		//$this->db->join('axsaleprice', 'axitem.itemid = axsaleprice.itemid', 'join');
        
		if(isset($search['sritem']) && !empty($search['sritem']))
            $this->db->like('axitem.itemid', trim($search['sritem']));
        if(isset($search['srname']) && !empty($search['srname']))
            $this->db->where("(`itemname` LIKE '%". str_replace('*', '%', trim($search['srname'])) ."%' OR `barcode` LIKE '%". str_replace('*', '%', trim($search['srname'])) ."%')");
        if(isset($search['srbrand']) && !empty($search['srbrand']))
            $this->db->like('brandid', trim($search['srbrand']));
		if(isset($search['srwh']) && !empty($search['srwh']))
            $this->db->like('axitem.warehouse', trim($search['srwh']));
		if(count($onwh) > 0)
			$this->db->where_in('axitem.warehouse', $onwh);
		
		//if(isset($search['srclass']) && !empty($search['srclass']))
			//$this->db->where('pricegroup', $search['srclass']);
		//else
			//$this->db->where('pricegroup', $this->_default['pricegroup']);
        
        $this->db->order_by("axitem.itemid ASC, axitem.warehouse DESC");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
	function searchItem($search = array(), $offset = 0, $limit = 10)
    {
		self::initValue();
		
        $this->db->select('axitem.itemid, itemname, barcode, onhand, onorder, brandid, unitsale, unitid, saleprice as price, warehouse, created_datetime');
        $this->db->from('axitem');
		$this->db->join('axsaleprice', 'axitem.itemid = axsaleprice.itemid', 'join');
        if(isset($search['sritem']) && !empty($search['sritem']))
        {
			$this->db->where("(`axitem`.`itemid` LIKE '%".trim($search['sritem'])."%' ESCAPE '!' OR `itemname` LIKE '%".str_replace('*', '%', trim($search['sritem']))."%' ESCAPE '!' OR `barcode` LIKE '%". trim($search['sritem'])."%' ESCAPE '!' )");
		}
        if(!empty($search['srbrand']))
            $this->db->like('brandid', trim($search['srbrand']));
		if(!empty($search['srwh']))
            $this->db->like('warehouse', trim($search['srwh']));
		
		if(isset($search['srclass']) && !empty($search['srclass']))
			$this->db->where('pricegroup', $search['srclass']);
		else
			$this->db->where('pricegroup', $this->_default['pricegroup']);
		
        $this->db->order_by("axitem.itemid ASC");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
	function getRecentItem($salecode = '', $offset = 0, $limit = 10, $keyword = array())
    {
        $this->db->select('custaccount, itemid, itemname, salesprice as price, sum(salesqty) as salesqty');
        $this->db->from('axsaledetail');
        //$this->db->where('salesresponsible', $salecode);
        $this->db->where('salesprice > 0');
        //$this->db->distinct();
        $this->db->group_by(array('custaccount', 'itemid'));
        
        if(isset($keyword['sritem']) && !empty($keyword['sritem']))
        {   
            $keyword['sritem'] = str_replace('*', '%', trim($keyword['sritem']));
            $this->db->where("(`itemid` LIKE '%".trim($keyword['sritem'])."%' ESCAPE '!' OR `itemname` LIKE '%".str_replace('*', '%', trim($keyword['sritem']))."%' ESCAPE '!' OR `barcode` LIKE '%". trim($keyword['sritem'])."%' ESCAPE '!' )");
        }

        if(isset($keyword['custid']))
            $this->db->where('custaccount', $keyword['custid']);
        
        $this->db->order_by("orderdate","DESC");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    function getItemInfo($itemid, $price_group = '', $barcode = '')
    {
		self::initValue();
		
        $this->db->select('axitem.itemid, itemname, barcode, onhand, onorder, brandid, unitsale, unitid, saleprice as price, warehouse');
        $this->db->from('axitem');
		$this->db->join('axsaleprice', 'axitem.itemid = axsaleprice.itemid', 'join');
        $this->db->where("axitem.itemid", trim($itemid));
		$this->db->where("warehouse", trim(getWarehouse()));
		
        if(!empty($barcode))
            $this->db->where("axitem.barcode", trim($barcode));

		if(!empty($price_group))
			$this->db->where('pricegroup', $price_group);
		else
			$this->db->where('pricegroup', $this->_default['pricegroup']);
		
		$this->db->order_by('saleprice DESC');
        $query = $this->db->get();
        if($query->num_rows()==0)
            return array();
        return $query->row_array();        
    }
    
    function getItemBrand($brandid = '')
    {
        $this->db->from('axitembrand');
        if(!empty($brandid))
            $this->db->where("brandid", trim($brandid));
        $query = $this->db->get();
        return $query->result_array();        
    }
    function getCustomers($offset = 0, $limit = 100, $custClass= 'GT')
    {
        $this->db->from('axcustomer');
        $this->db->like('custclass', $custClass);
        $this->db->order_by("name","ASC");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
	function searchCustomer($search = array(), $offset = 0, $limit = 10)
    {
        $this->db->from('axcustomer');
        if(isset($search['srcust']) && !empty($search['srcust']) && $search['srcust'] != '*')
			$this->db->where("(`custid` LIKE '%". trim($search['srcust']) . "%' OR `name` LIKE '%" . str_replace('*', '%', trim($search['srcust'])) ."%' OR `street` LIKE '%" . str_replace('*', '%', trim($search['srcust'])) ."%')");
		
        if(isset($search['srsaleman']) && !empty($search['srsaleman']))
        {
            $this->db->join('customerloving', 'custid = custaccount');
            $this->db->like('salemans', trim($search['srsaleman']));
        }        
        $this->db->order_by("custid","asc");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
	function getRecentCustomer($salecode = '', $offset = 0, $limit = 10)
	{
		$this->db->select('custaccount as custid, custname as name');
		$this->db->from('axsaleheader');
		$this->db->where('salesman', $salecode);
		$this->db->where("orderdate >= DATE_SUB(NOW(), INTERVAL 15 DAY)");
		$this->db->distinct();
		$this->db->order_by("orderdate","asc");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
	}
    function getCustomerByFields($fields = array(), $offset = 0, $limit = 10)
    {
        $this->db->from('axcustomer');
        if(isset($fields['srcust']) && !empty($fields['srcust']))
            $this->db->like('custid', trim($fields['srcust']));

        if(isset($fields['srname']) && !empty($fields['srname']))
            $this->db->where("(`name` LIKE '%". trim($fields['srname']) . "%' OR `custid` LIKE '%" .trim($fields['srname']. "%')"));
        if(isset($fields['srstreet']) && !empty($fields['srstreet']))
            $this->db->like('street', trim($fields['srstreet']));
        if(isset($fields['srclass']) && !empty($fields['srclass']))
            $this->db->like('custclass', trim($fields['srclass']));    
        if(isset($fields['srphone']) && !empty($fields['srphone']))
            $this->db->like('cellphone', trim($fields['srphone']));
        if(isset($fields['srlinedisc']) && !empty($fields['srlinedisc']))
            $this->db->like('slinedisc', trim($fields['srlinedisc']));

        if(isset($fields['srsaleman']) && !empty($fields['srsaleman']))
        {
            $this->db->join('customerloving', 'custid = custaccount');
            $this->db->like('salemans', trim($fields['srsaleman']));
        }        
        $this->db->order_by("custid","asc");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    function getCustInfo($custid)
    {
        $this->db->from('axcustomer');
        //$this->db->where('disabled',0);
        $this->db->where("custid", $custid);
        $query = $this->db->get();
        if($query->num_rows()==0)
            return array();
        return $query->row_array();        
    }
    function getCustDisc($custid)
    {
        $this->db->from('axdiscount');
        $this->db->where("accountrelation", $custid);
        $this->db->where("itemcode = 2 and accountcode = 0");
        $this->db->where("(fromdate <= '".date('Y-m-d')."' AND (todate >= '".date('Y-m-d')."' OR todate='1900-01-01'))");
        $query = $this->db->get();
        return $query->row_array();        
    }
    
    function getAxSalesByFields($saleman, $key = array(), $offset = 0, $limit = 100)
    {
        $this->db->from('axsaleheader');

        if(!empty($saleman))
		{
			$this->db->join('customerloving', 'customerloving.custaccount = axsaleheader.custaccount');
            $this->db->like('salemans', trim($saleman));
		}
        if(isset($key['srcust']) && !empty($key['srcust']))
            $this->db->where("(axsaleheader.custaccount LIKE '%".trim($key['srcust'])."%' OR custname LIKE '%".trim($key['srcust'])."%')");
        
        if(isset($key['srso']) && !empty($key['srso']))
            $this->db->like('salesid', trim($key['srso']));

        if(isset($key['refoid']) && !empty($key['refoid']))
            $this->db->like('refoid', trim($key['refoid']));
            
        if(isset($key['srodate']) && !empty($key['srodate']))
            $this->db->where('orderdate', trim($key['srodate']));

        if(isset($key['srwh']) && !empty($key['srwh']))
            $this->db->where('warehouse', trim($key['srwh']));
        
        $this->db->order_by("orderdate","desc");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    function getAxSalesDetail($saleman, $key = array(), $offset = 0, $limit = 100)
    {
        $this->db->select( 'axsaledetail.*, axcustomer.name');
        $this->db->from('axsaledetail');
        $this->db->join('axcustomer', 'axsaledetail.custaccount = custid', 'left');
        
        if($saleman != DATA_ALL)
        {
            $this->db->join('customerloving', 'axsaledetail.custaccount = customerloving.custaccount');
            $this->db->like('salemans', $saleman);
        }
            
        if(isset($key['srso']) && !empty($key['srso']))
            $this->db->where('salesid', trim($key['srso']));

        if(isset($key['srwarehouse']) && !empty($key['srwarehouse']))
            $this->db->where('warehouse', trim($key['srwarehouse']));
        
        if(isset($key['refoid']) && !empty($key['refoid']))
            $this->db->where('refoid', trim($key['refoid']));
            
        if(isset($key['srcust']) && !empty($key['srcust']))
            $this->db->where("(axsaledetail.custaccount LIKE '%".trim($key['srcust'])."%' OR custname LIKE '%".trim($key['srcust'])."%')");
        
        if(isset($key['sritem']) && !empty($key['sritem']))
            $this->db->where("(itemid LIKE '%".trim($key['srcust'])."%' OR itemname LIKE '%".trim($key['srcust'])."%')");
                
        if(isset($key['srfdate']) && !empty($key['srfdate']))
        {
            if(isset($key['srtdate']) && !empty($key['srtdate']))
                $this->db->where("(orderdate >= '".date('Y-m-d', strtotime($key['srfdate']))."' AND orderdate <= '".date('Y-m-d', strtotime($key['srtdate']))."')");
            else                                     
                $this->db->where('orderdate', trim($key['srfdate']));
        }
        $this->db->order_by('ORDERDATE DESC, SALESID DESC, ITEMID DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
		
        return $data;
    }
    
    function getSalesFocByFields($search, $offset = 0, $limit = 100)
    {
        $this->db->select(' axfocitem.*, itemname');
        $this->db->from('axfocitem');
		$this->db->join('axitem', 'itemidsales = itemid');
        if(isset($search['srcust']) || isset($search['srclass'])) 
        {
            if(!empty($search['srcust']) && !empty($search['srclass']))
                $this->db->where("(custrelation LIKE '%".trim($search['srcust'])."%' OR custrelation LIKE '%".trim($search['srclass'])."%')");
            else if(!empty($search['srcust']))
                $this->db->like("custrelation", trim($search['srcust']));
            else if(!empty($search['srclass']))
                $this->db->like("custrelation", trim($search['srclass']));
        }
        if(isset($search['sritem']) && !empty($search['sritem']))
            $this->db->like('itemidsales', trim($search['sritem']));
        if(isset($search['srdate']) && !empty($search['srdate']))
            $this->db->where("(fromdate <= '".date('Y-m-d', strtotime($search['srdate']))."' AND todate >= '".date('Y-m-d', strtotime($search['srdate']))."')");
        if(isset($search['fromqty']) && $search['fromqty'] > 0)
            $this->db->where('qtyfrom <= '.($search['fromqty'] > 0 ? $search['fromqty'] : 1000));
        
		$this->db->where('axitem.warehouse', getWarehouse());
		
		if(isset($search['srwh']) && !empty($search['srwh']))
			$this->db->where("(axfocitem.warehouse = '". trim($search['srwh']) ."' OR axfocitem.warehouse = '')");
        
		$this->db->order_by('fromdate ASC, itemidsales ASC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();        
    }

    function getHistoryOrder($search, $offset = 0, $limit = 100)
    {
        $this->db->select(' line.*');
        $this->db->from('axsaledetail line');

        if(isset($search['sritem']) && !empty($search['sritem']))
            $this->db->where("(itemid like '%". $search['sritem']."%' OR itemname like '%".$search['sritem']."%')");

        if(isset($search['srcust']) && !empty($search['srcust']))
            $this->db->where("(custaccount like '%". $search['srcust']."%' OR custname like '%".$search['srcust']."%')");

        if(isset($search['srorderid']) && !empty($search['srorderid']))
            $this->db->where('orderid', $search['srorderid']);

        if(isset($search['srsalecode']))
        {
        	if($search['srsalecode'] != DATA_ALL)
            	$this->db->where('salesman', $search['srsalecode']);
        }
        else
            $this->db->where('salesman', getSalecode());

        $this->db->order_by('itemname ASC, orderdate DESC, custaccount ASC');
        
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getRevenueByCust($saleman, $fromdate, $todate, $customer = '')
    { 
        $this->db->select( 'axsaledetail.custaccount, sum(lineamount) as amount, sum(salesqty) as qty');
        $this->db->from('axsaledetail');
        if($saleman != DATA_ALL)
        {
            $this->db->join('customerloving', 'axsaledetail.custaccount = customerloving.custaccount');
            $this->db->like('salemans', trim($saleman));
        }
        $this->db->where('(linestatus = 3 OR linestatus = 2)');//invoiced
        $this->db->where("orderdate >='".$fromdate."' AND orderdate<='".$todate."'");
        if(!empty($customer))
            $this->db->where('axsaledetail.custaccount', trim($customer));
        $this->db->where('warehouse', 'WMAYW');
        //$this->db->order_by("month","asc");
        $this->db->group_by('axsaledetail.custaccount');
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    
    function getRevenueMthByCust($saleman, $fromdate, $todate, $customer = '')
    { 
        $this->db->select( 'axsaledetail.custaccount, month, sum(lineamount) as amount, sum(salesqty) as qty');
        $this->db->from('axsaledetail');
        if($saleman != DATA_ALL)
        {
            $this->db->join('customerloving', 'axsaledetail.custaccount = customerloving.custaccount');
            $this->db->like('salemans', trim($saleman));
        }
        $this->db->where('(linestatus = 3 OR linestatus = 2)');//invoiced
        $this->db->where("orderdate >='".$fromdate."' AND orderdate<='".$todate."'");
        if(!empty($customer))
            $this->db->where('axsaledetail.custaccount', trim($customer));
        $this->db->where('warehouse', 'WMAYW');
        $this->db->order_by("month","asc");
        $this->db->group_by('axsaledetail.custaccount, month');
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    
    function getSalesByStatus($saleman, $fromdate, $todate)
    {
        $this->db->select( ' month, linestatus, sum(lineamount) as amount, sum(salesqty) as qty');
        $this->db->from('axsaledetail');
        if($saleman != DATA_ALL)
        {
            $this->db->join('customerloving', 'axsaledetail.custaccount = customerloving.custaccount');
            $this->db->like('salemans', trim($saleman));
        }
        $this->db->where("orderdate >='".$fromdate."' AND orderdate<='".$todate."'");
        //$this->db->where('disabled', 0);
        $this->db->where('warehouse', 'WMAYW');
        $this->db->order_by('month ASC, linestatus DESC');
        $this->db->group_by('linestatus, month');
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    function getRevenueByDate($saleman, $fromdate, $todate)
    {
        $this->db->select( ' salesresponsible, orderdate, sum(lineamount) as amount, sum(salesqty) as qty');
        $this->db->from('axsaledetail');
        if($saleman != DATA_ALL)
        {
            $this->db->join('customerloving', 'axsaledetail.custaccount = customerloving.custaccount');
            $this->db->like('salemans', trim($saleman));
        }
        $this->db->where("orderdate >='".$fromdate."' AND orderdate<='".$todate."'");
        //$this->db->where('disabled', 0);
        $this->db->order_by('orderdate ASC');
        $this->db->group_by('orderdate');
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    function getSalecode()
    {
        $this->db->select(' salecode, fullname');
        $this->db->from('user');
        $this->db->where('active', TRUE);
        //$this->db->where('usergroup', USER_SALEMAN);
        $this->db->where('salecode != ""');
        $this->db->order_by('fullname', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        $list = array('' => 'None');
        foreach($result as $item)
        {
            $list[$item['salecode']] = $item['salecode'].' ('.$item['fullname'].')';
        }
        return $list;
    }
    function getCustomerLoving($search = array(), $arrCust = array())
    {
        //$this->db->select('customerloving.*, ');
        $this->db->from('customerloving');

        if(isset($search['srsaleman']) && !empty($search['srsaleman']))
            $this->db->like('salemans', $search['srsaleman']);

        if(isset($search['srcust']) && !empty($search['srcust']))
            $this->db->where('custaccount', $search['srcust']);

        if(count($arrCust) > 0)
            $this->db->where_in('custaccount', $arrCust);

        $this->db->order_by('created_date', 'asc');

        $query = $this->db->get();
        $list = $query->result_array();
        return $list;
    }
    /*
    * Delete section
    */
}
