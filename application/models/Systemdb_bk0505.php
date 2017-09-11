<?php
class Systemdb extends CI_Model{
    
    function __contruct(){
        parent::__construct();
        $this->load->database();
    }
    /*
    * Insert section
    */
    function addItemBatch($items = array())
    {
        if(count($items) < 1)
            return FALSE;
        if($this->db->insert_batch('axitem', $items))
            return TRUE;
        return FALSE;
    }
	function addStockDetail($items = array())
    {
        if(count($items) < 1)
            return FALSE;
        if($this->db->insert_batch('axstockdetail', $items))
            return TRUE;
        return FALSE;
    }
    function addItemBrand($brand = array())
    {
        if(count($brand) < 1)
            return FALSE;
        //$this->db->update_batch('axitembrand', $brand, 'brandid');
        if($this->db->insert_batch('axitembrand', $brand))
            return TRUE;
        return FALSE;
    }
	function addSalePrice($salePrice = array())
    {
        if(count($salePrice) < 1)
            return FALSE;
        if($this->db->insert_batch('axsaleprice', $salePrice))
            return TRUE;
        return FALSE;
    }
    function addCustBatch($items = array())
    {
        if(count($items) < 1)
            return FALSE;
        //$this->db->update_batch('customer', $items, 'custid');
        if($this->db->insert_batch('axcustomer', $items))
            return TRUE;
        return FALSE;
    }
    function addCustDisc($items = array())
    {
        if(count($items) < 1)
            return FALSE;
        if($this->db->insert_batch('axdiscount', $items))
            return TRUE;
        return FALSE;
    }
    function addOrderExtBatch($items = array())
    {
        if(count($items) < 1)
            return FALSE;
        if($this->db->insert_batch('axsaledetail', $items))
            return TRUE;
        return FALSE;
    }
    function addOHeaderBatch($items = array())
    {
        if(count($items) < 1)
            return FALSE;
        if($this->db->insert_batch('axsaleheader', $items))
            return TRUE;
        return FALSE;
    }
    function addFocItemBatch($items = array())
    {
        if(count($items) < 1)
            return FALSE;
        if($this->db->insert_batch('axfocitem', $items))
            return TRUE;
        return FALSE;
    }
	function addSaleWHBatch($items = array())
    {
        if(count($items) < 1)
            return FALSE;
        if($this->db->insert_batch('axsaleonwh', $items))
            return TRUE;
        return FALSE;
    }
    /*
    * Select section
    */
    function getAlItem($offset = 0, $limit = 100)
    {
        $this->db->from('axitem');
        //$this->db->where('disabled', 0);
        $this->db->order_by("itemid","asc");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    function getAllCustomer($offset = 0, $limit = 100)
    {
        $this->db->from('axcustomer');
        $this->db->where('disabled', 0);
        $this->db->order_by("name","asc");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    function getAllSalesExt($offset = 0, $limit = 100)
    {
        $this->db->from('axsaledetail');
        $this->db->where('disabled', 0);
        $this->db->order_by("orderdate","asc");
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    /*
    * Delete section
    */
    function delItemStock()
    {
        $this->db->empty_table('axitem');
    }
	function delStockDetail()
    {
        $this->db->empty_table('axstockdetail');
    }
    function delItemBrand()
    {
        $this->db->empty_table('axitembrand');
    }
	function delSalePrice()
    {
        $this->db->empty_table('axsaleprice');
    }
    function delCustomer()
    {
        //$this->db->set('disabled', 1);
        //$this->db->update('axcustomer');
        $this->db->empty_table('axcustomer');
    }
    function delDiscount()
    {
        $this->db->empty_table('axdiscount');
    }
    function delOrderExt($fromdate, $todate)
    {
        $this->db->where("(orderdate >='".date('Y-m-d', strtotime($fromdate))."' and orderdate <='".date('Y-m-d', strtotime($todate))."')");
        $this->db->delete('axsaledetail');
    }
    function delSaleHeader($fromdate, $todate)
    {
        $this->db->where("(orderdate >='".date('Y-m-d', strtotime($fromdate))."' and orderdate <='".date('Y-m-d', strtotime($todate))."')");
        $this->db->delete('axsaleheader');
        //$this->db->empty_table('axsaleheader');
    }
    function delFocSale()
    {
        $this->db->empty_table('axfocitem');
    }
	function delSaleOnWH()
    {
        $this->db->empty_table('axsaleonwh');
    }
}
