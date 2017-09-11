<?php
class Salesmtdb extends CI_Model{

    function __contruct(){
        parent::__construct();
        $this->load->database();
    }

    function counting($idnum, $data = array())
    {
        if(count($data) > 0 && !empty($idnum))
        {
            $this->db->where('icid', $idnum);
            if($this->db->update_batch('axitemcustomer', $data))
                return TRUE;
        }
        return FALSE;
    }

    function getItemsByCustomer($customer, $item = '')
    {
        $this->db->select('ic.itemid, REPLACE(itembarcode, "\'", "") as itembarcode, ic.itemname, minqty, ic.unitid, ic.brandid, onhand');
        $this->db->from('axitemcustomer ic');
        $this->db->join('axitem', "ic.itemid = axitem.itemid");

        $this->db->distinct();

        $this->db->where("(custaccount LIKE '%".$customer."%' OR custname LIKE '%".$customer."%')");    
        if(!empty($item))
            $this->db->where("(ic.itemid LIKE '%".$item."%' OR ic.itemname LIKE '%".$item."%')");

        $this->db->where('axitem.warehouse', getWarehouse());
        $this->db->order_by('ic.itemname ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    function getAllItemsCustomer($search = array())
    {
        $this->db->from('axitemcustomer');
        $this->db->distinct();
        
        if(isset($search['customer']) && !empty($search['customer']))
            $this->db->where("(custaccount LIKE '%".$search['customer']."%' OR custname LIKE '%".$search['customer']."%')");    

        if(isset($search['item']) && !empty($search['item']))
            $this->db->where("(itemid LIKE '%".$search['item']."%' OR itemname LIKE '%".$search['item']."%')");    

        if(isset($search['saleman']) && !empty($search['saleman']))
        {
            $this->db->join('customerloving', 'customerloving.custaccount = axitemcustomer.custaccount');
            $this->db->like('salemans', trim($search['saleman']));
        }

        $this->db->order_by('itename ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    function getOrderByFields($search, $offset = 0, $limit = 100)
    {
        $this->db->select(' line.*, custid, custname, created_datetime, salesman, status, lastsend_datetime');
        $this->db->from('salesline line');
        $this->db->join('salesheader head', 'line.orderid = head.orderid');

        if(isset($search['sritem']) && !empty($search['sritem']))
            $this->db->where("(itemid like '%". $search['sritem']."%' OR itemname like '%".$search['sritem']."%')");

        if(isset($search['srcust']) && !empty($search['srcust']))
            $this->db->where("(custid like '%". $search['srcust']."%' OR custname like '%".$search['srcust']."%')");

        if(isset($search['srorderid']) && !empty($search['srorderid']))
            $this->db->where('line.orderid', $search['srorderid']);

        if(isset($search['srsalecode']) && !empty($search['srsalecode']))
            $this->db->where('salesman', $search['srsalecode']);
        else
            $this->db->where('salesman', getSalecode());

        $this->db->order_by('itemname ASC, created_datetime DESC, custid ASC');
        
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result_array();
    }
}
