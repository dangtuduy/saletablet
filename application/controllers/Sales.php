<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//updated on 01-July-2017

class Sales extends VS_SaleController {

	function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('salesdb');
        $this->body['utype'] = getUserType();
    }

	public function index()//dashboard
    {
        $this->load->view('home', $this->data);
    }
    
    function ordernew()
    {
        $salecode = getSaleCode();
        if(empty($salecode))
		{
			$this->data['content'] = lang('Undefined salecode');
            $this->load->view('mobile', $this->data);
			return;
		}
        
        $step = $this->uri->segment(3);
        
        if($step==2)
        {
            $this->data['content'] = $this->newOrderStep2();
        }
        else if($step==3)
        {
            $this->data['content'] = $this->newOrderStep3();
        }
        else
        {
            $this->data['content'] = $this->newOrderStep1();
        }
		
        $this->load->view('mobile', $this->data);
    }

    private function newOrderS3SendFile()
    {
        $get = getURLString();
        if(isset($get['type']) && strtoupper($get['type']) == 'FILE')
        {
            $selectedCust = isset($get['c']) ? $get['c'] : '';
            $selectedCust = VSdecode($selectedCust);

            $custinfo = array();
            if(!empty($selectedCust))
                $custinfo = $this->salesdb->getCustInfo($selectedCust);

            $custid = isset($custinfo['custid']) ? $custinfo['custid'] : '';

            if(!empty($custid) && isset($_POST['SAVESEND']) && $_POST['SAVESEND'] == TRUE)
            {
                $address = xss_clean($this->input->post('deliaddress'));
                $address2 = xss_clean($this->input->post('deliaddress2'));
                $comment = xss_clean($this->input->post('comment'));
                
                $sellingCust['street'] = str_replace("\r\n", '.#', $address);
                $sellingCust['street2'] = str_replace("\r\n", '.#', $address2);
                $sellingCust['comment'] = str_replace("\r\n", '.#', $comment);

                $OID = generateOID(getChanel());

                $file = $this->do_upload($OID);
                $attached_file = isset($file['file_name']) ? $file['file_name'] : '';
                
                if(!is_array($file))
                {
                    $attached_file = $file;
                    echo '<h2 class="text-center">'.$file.'</h2>';
                }

                $client_name = isset($file['client_name']) ? $file['client_name'] : $attached_file;

                $bodyemail = '<br/><p><b style="color:blue">'.lang('See Attached file'). ' '. $attached_file.'</b></p>';    

                $salesheader = array('orderid'=>$OID, 'userid'=>getUserId(), 'salesman'=>getSaleCode(), 'custid'=>$custid, 'custname'=>(isset($custinfo['name'])?$custinfo['name']:'Undefined'),'deliverystreet'=>($address.(empty($address2)?'':', @Or: '.$address2)),'status'=>ORDER_SEND, 'totalqty'=>0, 'totalamount'=>0, 'comment'=>$comment, 'created_datetime'=>nowdatetime(), 'attachedfile'=> $attached_file);

                $salesline[0] = array('orderid'=>$OID,'itemid'=>'FILE', 'itemname'=>$client_name, 'price'=> 0 ,'qty'=>0, 'note'=>'', 'linepercent'=>0);
                
                $this->body['action'] = ACTION_DONE;
                            
                $salesheader['lastsend_datetime'] = nowdatetime();
                $salesheader['status'] = ORDER_SEND;
                
                if($this->salesdb->saveOrder($salesheader, $salesline))

                $attached_file = realpath(isset($file['file_path']) ? $file['file_path'] : FCPATH.'uploads') .'/' . $attached_file;
                            
                $xmlsend = $this->xmlneworder($OID, $custinfo['custid'], $custinfo['name'], $address.(empty($address2)?'':', @Or: '.$address2), $comment, array());
                
                $email1 = '<b>Customer</b>:'.lang($custinfo['custid']).' - '.$custinfo['name'].'<br/><b>Delivery 1</b>: '.$address.'<br/><b>Delivery 2</b>: '.$address2.'<br/><b>Comment: </b>'.$comment.'<br/>';
                
                $bodyemail = $email1.$bodyemail;
                
                $this->sendEmail($bodyemail, $OID, ($custinfo['custid'] .' #'.$custinfo['name']), $attached_file);
                                
                if(!empty($xmlsend))
                    $this->execApiAction($xmlsend);    
            }
            $this->body['custid'] = $custid;
            $this->body['ordercust'] = $custinfo;
            return $this->data['content'] = $this->load->view('sub/new_s3_file', $this->body, TRUE);
        }
        return array();
    }

    private function newOrderStep3()
    {
    	$get = getURLString();
        $curPage = (isset($get['page']) && $get['page'] > 0) ? $get['page'] : 1;

    	try
    	{
            if(isset($get['type']) && strtoupper($get['type']) == 'FILE')
                return $this->newOrderS3SendFile();

    		//FINISH
            $sellingCust = $this->session->userdata('selectedCust');
            $discount = $this->salesdb->getCustDisc(isset($sellingCust['custid'])?$sellingCust['custid']:'');
            $discount = array('amount'=>$discount['amount'], 'percent'=>(isset($discount['percent2']) ? $discount['percent2']: 0));
            $this->body['discount'] = $discount;
            
            $salesline = array();
            $command = $this->uri->segment(4);
            $deletedItem = $this->uri->segment(5);
            $sellingItems = $this->session->userdata('sellitem');
			
            if($command == ACTION_DEL)
            {
                if(isset($sellingItems[$deletedItem]))
                    unset($sellingItems[$deletedItem]);
            }   
            else
            { 
                $action = $this->input->post('action');
                if(isset($action) && $action == ACTION_UPDATE && isset($sellingItems))
                {
                    $OID = generateOID(getChanel());
                    $totalqty = 0;
                    $totalamount = 0;
					$bodyemail = '';
                    foreach($sellingItems as $code=>$value)
                    {
                        $qty = $this->input->post('qty'.$code);
                        $qty = preg_replace('/\D/', '', $qty);
                        $note = $this->input->post('note'.$code);
						
                        if($qty >= 0)
                        {
							if(empty($note))
							{
								$custclass = isset($sellingCust['custclass']) ? $sellingCust['custclass'] : getChanel();
                                $custid = isset($sellingCust['custid']) ? $sellingCust['custid'] : '';

								$focitemlist = $this->salesdb->getSalesFocByFields(array('srcust'=>$custid,'srclass'=>$custclass, 'sritem'=>$code, 'srwh'=>getWarehouse(), 'srdate'=>date('Y-m-d'), 'fromqty'=>$qty));
								foreach($focitemlist as $foc)
								{
									$note = (empty($note)?'':($note.', '.lang('or').' ')).lang('Buy').' '.$foc['itemqtysales'].(empty($foc['inventbatchid']) ? '' : '#'.$foc['inventbatchid'].'').' '.lang('get FREE').' '.$foc['itemqtyfoc'].' '.$foc['itemidfoc'].(empty($foc['inventbatchfoc']) ? '' : '#'.$foc['inventbatchfoc'].'');
								} 
							}
                            $totalqty += $qty;
                            $amount = $qty * $value['price'] * (1-$discount['percent']/100);
                            $totalamount += $amount; 
                            $salesline[] = array('orderid'=>$OID,'itemid'=>$code, 'itemname'=>$value['name'], 'price'=> $value['price'] ,'qty'=>$qty, 'note'=>$note, 'linepercent'=>$discount['percent']);
                            $sellingItems[$code] = array('name'=>$value['name'], 'price'=>$value['price'], 'qty'=>$qty, 'note'=>$note, 'discpct'=>$discount['percent']);
                            $bodyemail = $bodyemail. ('<tr><td>'.$code .'</td><td>'.$value['name'].'</td><td>'.$qty.'</td><td>'.number_format($value['price'],0).'</td><td>'.(($discount['percent']>0)?$discount['percent']:'').'</td><td>'.number_format($amount, 0).'</td><td class="note">'.htmlspecialchars($note).'</td></tr>'."\r\n");
                        }
                    }
					$address = xss_clean($this->input->post('deliaddress'));
                    $address2 = xss_clean($this->input->post('deliaddress2'));
                    $comment = xss_clean($this->input->post('comment'));
					$sellingCust['street'] = str_replace("\r\n", '.#', $address);
					$sellingCust['street2'] = str_replace("\r\n", '.#', $address2);
					$sellingCust['comment'] = str_replace("\r\n", '.#', $comment);
					$this->session->set_userdata(array('selectedCust'=> $sellingCust));
                    
                    if((isset($_POST['SAVE']) && $_POST['SAVE'] == TRUE) || (isset($_POST['SAVESEND']) && $_POST['SAVESEND'] == TRUE))
                    {
						$bodyemail = '<table cellspacing="0" cellpadding="0"><tr><th>Item </th><th>Name</th><th>Quantity</th><th>Price</th><th>Disc %</th><th>Amount</th><th>Note</th></tr><tbody>'.$bodyemail.'</tbody></table>';
                        $custinfo = $sellingCust;
                        $custinfo['custid'] = isset($custinfo['custid']) ? $custinfo['custid'] : 'NEW';
						
                        $salesheader = array('orderid'=>$OID, 'userid'=>getUserId(), 'salesman'=>getSaleCode(), 'custid'=>$custinfo['custid'], 'custname'=>(isset($custinfo['name'])?$custinfo['name']:'Undefined'),'deliverystreet'=>($address.(empty($address2)?'':', @Or: '.$address2)),'status'=>ORDER_SAVE, 'totalqty'=>$totalqty, 'totalamount'=>$totalamount, 'comment'=>$comment, 'created_datetime'=>nowdatetime());
                        if(isset($_POST['SAVESEND']) && $_POST['SAVESEND'] == TRUE)
                        {
                            $salesheader['lastsend_datetime'] = nowdatetime();
                            $salesheader['status'] = ORDER_SEND;
                        }
                        if($this->salesdb->saveOrder($salesheader, $salesline))
                        {
                            $sellingItems = array();
							$this->session->set_userdata('sellitem', $sellingItems);
                            $this->body['action'] = ACTION_DONE;
							
                            if(isset($_POST['SAVESEND']) && $_POST['SAVESEND'] == TRUE)
                            {
								$custinfo['name'] = isset($custinfo['name']) ? $custinfo['name'] : 'Undefined';
                                $xmlsend = $this->xmlneworder($OID, $custinfo['custid'], $custinfo['name'], $address.(empty($address2)?'':', @Or: '.$address2), $comment, $salesline);
                                $email1 = '<b>Customer</b>:'.lang($custinfo['custid']).' - '.$custinfo['name'].'<br/><b>Delivery 1</b>: '.$address.'<br/><b>Delivery 2</b>: '.$address2.'<br/><b>Comment: </b><span style="color:blue">'.$comment.'</span><br/><br/>';
                                $bodyemail = $email1.$bodyemail;
                                $this->sendEmail($bodyemail, $OID, (($custinfo['custid'] == 'NEW' ? lang('NEW') : $custinfo['custid']) .' #'.$custinfo['name']));
								
                                if(!empty($xmlsend))
                                    $this->execApiAction($xmlsend);
                            }    
                        }
                    }
                }
            }
            $this->session->set_userdata('sellitem', $sellingItems);
    	}
    	catch (Exception $e)
    	{
    		var_dump($e->getMessage());
    	}

    	$this->body['sellingItems'] = $sellingItems;

        $this->body['custid'] = isset($sellingCust['custid']) ? $sellingCust['custid'] : '';
        $this->body['ordercust'] = $sellingCust;

        $this->body['step'] = 3;
        $this->body['curpage'] = $curPage;

    	return $this->load->view('sub/new_s3', $this->body, TRUE);
    }

    private function newOrderStep2()
    {
    	//ITEMs
        $selectedCust = $this->uri->segment(4);
		$selectedCust = VSdecode($this->uri->segment(4));
		
        $get = getURLString();
        $curPage = (isset($get['page']) && $get['page'] > 0) ? $get['page'] : 1;

        if(!empty($selectedCust) && strtolower($selectedCust) == 'new')
        {
        	$sellingCust = array('name' => $this->input->post('custname'), 'street' => '', 'cellphone' => '', 'custclass' => '');
            if(!empty($sellingCust['name']))
            	$this->session->set_userdata(array('selectedCust'=> $sellingCust));
        }
        else if(!empty($selectedCust) && strtolower($selectedCust) != 'yes')
        {
        	$sellingCust = $this->salesdb->getCustInfo($selectedCust);
            $this->session->set_userdata(array('selectedCust'=> $sellingCust));
        }
        else
        	$sellingCust = $this->session->userdata('selectedCust');
                  
        $keyword['sritem'] = trim(isset($get['sritem']) ? $get['sritem'] : ''); 
        $keyword['srbrand'] = trim(isset($get['srbrand']) ? $get['srbrand'] : '');
		$keyword['srwh'] = getWarehouse(getOutlet());
        $keyword['srhistory'] = 0;
        $keyword['pricegroup'] = isset($sellingCust['pricegroup']) ? $sellingCust['pricegroup'] : '';

		//$keyword['srclass'] = isset($sellingCust['custclass']) ? $sellingCust['custclass'] : '';

        $history = $this->input->get('srhistory');
        if($history == 'on' || $history == 1)
            $keyword['srhistory'] = 1;
            
        if($keyword['srhistory'] == 0)
        {   
            $this->body['itemlist'] = $curPage < 1 ? array() : $this->salesdb->searchItem($keyword, ($curPage-1)*PAGE_SEARCH, PAGE_SEARCH);
        }
        else    
        {
            $keyword['custid'] = isset($sellingCust['custid']) ? $sellingCust['custid'] : '@';
            $this->body['itemlist'] = $this->salesdb->getRecentItem(getSalecode(), ($curPage-1)*PAGE_SEARCH, PAGE_SEARCH, $keyword);
        }
		
		$this->body['itembrand'] = $this->itemBrand();
            
        $sellingItems = $this->session->userdata('sellitem');
			
        if(isset($_POST['ConfirmNew']) && $_POST['ConfirmNew'] == TRUE)
        {
            $selectedItem = xss_clean($this->input->post('addItemId'));
            $qty = xss_clean($this->input->post('qtyItem'));
            $qty = preg_replace('/\D/', '', $qty);
            $iteminfo = $this->salesdb->getItemInfo($selectedItem, $keyword['pricegroup']);

            if(count($iteminfo) > 0 && isset($iteminfo['itemid']) && $qty > 0)
            {
                if(isset($sellingItems[$selectedItem]))
                {
    	            $sellingItems[$selectedItem]['qty'] += $qty;
					$sellingItems[$selectedItem]['price'] = $iteminfo['price'];
                }
                else
            	    $sellingItems[$selectedItem] = array('name'=>$iteminfo['itemname'], 'qty'=> $qty, 'price'=>$iteminfo['price']);
                $custclass = isset($sellingCust['custclass']) ? $sellingCust['custclass'] : getChanel();
                $fromqty = isset($sellingItems[$selectedItem]['qty']) ? $sellingItems[$selectedItem]['qty'] : 0;
                $custid = isset($sellingCust['custid']) ? $sellingCust['custid'] : '';

                $focitemlist = $this->salesdb->getSalesFocByFields(array('srcust'=>$custid, 'srclass'=>$custclass, 'sritem'=>$selectedItem, 'srwh'=>getWarehouse(), 'srdate'=>date('Y-m-d'), 'fromqty'=>$fromqty));
                
                $note = '';
                foreach($focitemlist as $foc)
                {
					$newfoc = lang('Buy').' '.$foc['itemqtysales'].(empty($foc['inventbatchid']) ? '' : '#'.$foc['inventbatchid'].'').' '.lang('get FREE').' '.$foc['itemqtyfoc'].' '.$foc['itemidfoc'].(empty($foc['inventbatchfoc']) ? '' : '#'.$foc['inventbatchfoc'].'');
                        $note = (empty($note)?'':($note.', '.lang('or').' ')). $newfoc;
                }
                $sellingItems[$selectedItem]['note'] = $note;  			
                $this->session->set_userdata('sellitem', $sellingItems);
            }
        }

    	$this->body['sellingItems'] = $sellingItems;

        $this->body['custid'] = isset($sellingCust['custid']) ? $sellingCust['custid'] : '';
        $this->body['ordercust'] = $sellingCust;

        $this->body['step'] = 2;
        $this->body['curpage'] = $curPage;
        $this->body['keyword'] = $keyword;
        
		return $this->load->view('sub/new_s2', $this->body, TRUE);
    }

    private function newOrderStep1()
    {
    	$get = getURLString();
        $curPage = (isset($get['page']) && $get['page'] > 0) ? $get['page'] : 1;

        $sellingCust = $this->session->userdata('selectedCust');
        
        $keyword['srcust'] = xss_clean(isset($get['srcust']) ? $get['srcust'] : ''); 
        $keyword['srsaleman'] = getSalecode();

		if(!empty($keyword['srcust']))
		{
			$this->body['customer'] = $curPage < 1 ? array() : $this->salesdb->searchCustomer($keyword, ($curPage-1)*PAGE_SEARCH, PAGE_SEARCH);
		}
		else
			$this->body['customer'] = $this->salesdb->getRecentCustomer(getSaleCode(), 0, PAGE_SEARCH+2);
        
        $sellingItems = $this->session->userdata('sellitem');

        $this->body['sellingItems'] = $sellingItems;

        $this->body['custid'] = isset($sellingCust['custid']) ? $sellingCust['custid'] : '';
        $this->body['ordercust'] = $sellingCust;

        $this->body['step'] = 1;
        $this->body['curpage'] = $curPage;
        $this->body['keyword'] = $keyword;
        
		return $this->load->view('sub/new_s1', $this->body, TRUE);
    }

    function itemlist()
    {
    	$this->viewItemList('mobile', 'sub/ax_itemlist');
    }
    
    function customerlist()
    {
    	$this->viewCustomerList('mobile', 'sub/ax_customer');
    }
    
    function axorders()
    {
    	$this->viewAXOrderList('mobile_ext', 'sub/ax_saleorders');
    }
    
    function axdetail()
    {
    	$this->viewAXDetail('mobile', 'sub/ax_saleitem');
    }
    
    function axorderdetail()//ajax
    {
        $this->viewDetailPerAxOrder('sub/ax_orderinfo');
    }
    
    function focitemlist()
    {
    	$this->viewFOCItemList('mobile_ext', 'sub/ax_salesfoc');
    }
    
    function orderlist()
    {
        if(getChanel() == CHANEL_MT)
            $this->viewOrderList('mobile_ext', 'sub/mt_order_list');
        else    
    	   $this->viewOrderList('mobile_ext', 'sub/order_list');
    }

    function orderinfo()
    {
        $get = getURLString();
        $oid = isset($get['order']) ? $get['order'] : '';

        $orderInfo = $this->salesdb->getOrderDetail(DATA_ALL, $oid);
        
        $this->body['detail'] = $orderInfo;
  
        $this->load->view('sub/order_info', $this->body);
    }

    function history()
    {
        $this->viewSaleHistory('mobile', 'sub/historysale');
    }

    function instock()
    {
    	$get = getURLString();
    	$type = '';
        if(isset($get['item']))
        {
        	$url = getDispatchURL('pobyitem', array('id'=> $get['item'], 'wh'=>getWarehouse()));
        	$this->body['iteminfo'] = $this->salesdb->getItemInfo($get['item']);
        }
        elseif (isset($get['po'])) 
        {
        	$url = getDispatchURL('po', array('id'=> $get['po'], 'wh'=>getWarehouse()));
        }
        
        $this->body['dispatch'] = $this->getAPIDispatchJson($url);
        $this->data['content'] = $this->load->view('sub/dispatch_item_po', $this->body, TRUE);

        $this->load->view('mobile', $this->data);
    }
    //======================================================================
    //======================================================================

    function pullAxOrder()//ajax
    {
        $this->doPullAXOrder();
    }

    function sendtoapi()
    {
        $this->doSendItem2API();
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
    
    private function do_upload($filename)
    {
        if(isset($_FILES['userfile']['name']))
        {
            $file = explode(".", $_FILES['userfile']['name']);
            $ext = strtolower($file[count($file)-1]);
            if($ext != 'gif' && $ext != 'jpg' && $ext != 'png' && $ext != 'pdf')
                return array('message'=> lang('File type is not allowed.'));
            
            $config['upload_path'] = FCPATH.'uploads/';
            $config['allowed_types'] = 'gif|jpg|png|pdf';
            $config['max_size'] = 6000;
            $config['max_width'] = 2500;
            $config['max_height'] = 2500;
            $config['overwrite'] = TRUE;
            $config['file_name'] = str_replace(' ', '', $filename).'_'.time();
            
            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('userfile'))
            {
                return $this->upload->display_errors();
            }
            return $this->upload->data();
        }
        return array('message'=> lang('Can not find a upploaded file.'));
    }
}
