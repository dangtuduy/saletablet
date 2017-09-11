<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salesmt extends VS_SaleController {

	function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('salesdb');
        $this->load->model('salesmtdb');
    }
    
    function counting()
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
            $this->data['content'] = $this->counting_s2();
            $this->load->view('mobile', $this->data);
        }
        else if($step==3)
        {
            $this->data['content'] = $this->counting_s3();
            $this->load->view('mobile', $this->data);
        }
        else
            $this->data['content'] = $this->counting_s1();
    }

    private function counting_s1()
    {
        $this->viewCustomerList('mobile', 'sub/mt_counting_s1');
    }

    private function counting_s2()
    {
        $get = getURLString();

        $custID = VSdecode($this->uri->segment(4));
        if(empty($custID))
            $custInfo = $this->session->userdata('countingCust');
        else
            $custInfo = $this->salesdb->getCustInfo($custID);

        $itemlist = array();
        $custItems = array();
        $personalBrand = array('0'=> '');

        if(count($custInfo))
        {
            if(isset($_POST['PUTORDER']))
            {
                $itemlist_json = $this->input->post('onItemList');

                $itemlist = json_decode($itemlist_json, true);
                
                $countingItem = array();
                
                foreach ($itemlist as $item) 
                {
                    $cntQty = $this->input->post('cnt'.$item['itemid']);
                    $outStock = false;

                    if($cntQty == 0 || $cntQty == '')
                    {
                        $isCheck = $this->input->post('cntck'.$item['itemid']);
                        if($isCheck == 'on')
                            $outStock = true;
                        $cntQty = 0;
                    }

                    if($cntQty >= 0  && ($cntQty > 0 || $outStock))
                    {
                        $countingItem[$item['itemid']] = array('name' => $item['itemname'], 'cntqty'=> $cntQty, 'minqty'=> $item['minqty'], 'qty'=> ($item['minqty'] - $cntQty));
                    }
                } 
                //print_r($countingItem);
                $this->session->set_userdata('countingItem', $countingItem);
                redirect(site_url('salesmt/counting/3'));
            }

            $this->session->set_userdata('countingCust', $custInfo);

            $custItems = $this->salesmtdb->getItemsByCustomer($custInfo['custid']); //get all items assigned to customer

            $countingItem = $this->session->userdata('countingItem');// get counted items

            $allBrands = $this->itemBrand(); //all current brands

            for ($i = 0; $i < count($custItems); $i++)
            {
                $item = $custItems[$i];
                    
                $countedQty = isset($countingItem[$item['itemid']]['cntqty']) ? $countingItem[$item['itemid']]['cntqty'] : '';

                $custItems[$i]['cntqty'] = $countedQty;
                    
                $itemlist[]= array('id'=> $item['itemid'], 'name'=> $item['itemname'], 'minqty'=> $item['minqty']);
                    
                $bid = $item['brandid'];

                if(!array_key_exists($bid, $personalBrand) && !empty($bid))
                {
                    $personalBrand[$bid] = $bid.' #'.(isset($allBrands[$bid]) ? $allBrands[$bid] : '');
                }
            }
        }
        $this->body['onItemList'] = json_encode($itemlist);//full item info
        $this->body['customer'] = $custInfo; 
        $this->body['allItems'] = $custItems; //brife item list
        $this->body['someBrands'] = $personalBrand;

        return $this->load->view('sub/mt_counting_s2', $this->body, TRUE);
    }

    private function counting_s3()
    {
        $countingCust = $this->session->userdata('countingCust');
        $countingItems = $this->session->userdata('countingItem');

        $command = $this->uri->segment(4);
        
        if(isset($countingItems) && count($countingItems) > 0)
        {
            if($command == ACTION_DEL)
            {
                $deletedItem = $this->uri->segment(5);

                if(isset($countingItems[$deletedItem]))
                    unset($countingItems[$deletedItem]);
            }   
            else if(isset($_POST['SAVESEND']) || isset($_POST['UPDATEQTY']))
            { 
                $comment = xss_clean($this->input->post('comment'));
                $address = xss_clean($this->input->post('comment'));
                $countingCust['comment'] = str_replace("\r\n", '. #', $comment);

                $countedLines = array();

                $bodyemail = '';
                
                $OID = generateOID(getChanel().'CNT');

                foreach($countingItems as $code=>$value)
                {
                    $qty = $this->input->post('qty'.$code);
                    //$qty = preg_replace('/\D/', '', $qty);
                    if($qty == '')
                        $qty = 0;

                    $countedLines[] = array('orderid'=>$OID,'itemid'=>$code, 'itemname'=>$value['name'], 'counted'=> $value['cntqty'] ,'qty'=>$qty, 'minqty'=> $value['minqty']);
                    
                    $countingItems[$code]['qty'] = $qty;

                    $bodyemail = $bodyemail. ('<tr><td>'.$code .'</td><td>'.$value['name'].'</td><td>'.$value['minqty'].'</td><td>'.number_format($value['cntqty'],0).'</td><td '.($qty>=0 ? 'style="color:red"' : '').'>'.number_format($qty).'</td></tr>'."\r\n");

                }
                if(isset($_POST['SAVESEND']) && $_POST['SAVESEND'])
                {
                    $bodyemail = '<table cellspacing="0" cellpadding="0"><tr><th>Item</th><th>'.lang('Name').'</th><th>'.lang('Min qty').'</th><th>'.lang('Counted').'</th><th>'.lang('Demand').'</th></tr><tbody>'.$bodyemail.'</tbody></table>';
                    
                    $countedHeader = array('orderid'=>$OID, 'userid'=>getUserId(), 'salesman'=>getSaleCode(), 'custid'=>$countingCust['custid'], 'custname'=>(isset($countingCust['name'])?$countingCust['name']:''),'comment'=>$comment, 'created_datetime'=>nowdatetime(),'lastsend_datetime'=>nowdatetime(), 'type'=> 'COUNTED', 'deliverystreet'=>isset($countingCust['street']) ? $countingCust['street'] : '',);

                    if($this->salesdb->saveOrder($countedHeader, $countedLines))
                    {
                        $this->body['action'] = ACTION_DONE;
                        
                        $bodyemail = '<b>'.lang('Customer').'</b>: '.lang($countingCust['custid']).' - '.$countingCust['name'].'<br/><b>'.lang('Address').': </b>'.$countedHeader['deliverystreet'].'<br/><b>'.lang('Comment').': </b><span style="color:blue">'.$comment.'</span><br/><br/>' . $bodyemail; 
                        
                        if(!empty($bodyemail))
                            $this->sendEmailManager($bodyemail, $OID, lang('COUNTING ').(isset($countingCust['custid']) ? $countingCust['custid'] : '') .' #'.$countingCust['name']);

                        $countingItems = array();
                        $countingCust = array();
                    }
                }
                $this->session->set_userdata('countingCust', $countingCust);
                $this->session->set_userdata('countingItem', $countingItems);
            }
        }
        $this->body['ordercust'] = $countingCust;
        $this->body['countingItems'] = $countingItems;
        return $this->load->view('sub/mt_counting_s3', $this->body, TRUE);
    }

    protected function sendEmailManager($body, $countId, $customer = '')
    {
        $email_from = $this->config->item('email_from');
        $email_send = $this->config->item('email_send');
        $etype = $this->session->userdata('chanel').'_'.$this->session->userdata('outlet');
        
        if(isset($email_send[$etype]))
        {
            $emailgoing = $email_send[$etype];
            
            $from_email = $this->session->userdata('email');
            
            $to_email = $this->session->userdata('managedby');

            $cc_mail = (empty($sale_email) ? $from_email : $from_email.','.$sale_email) . (isset($emailgoing['cc']) ? ','.$emailgoing['cc'] : '');
            
            $subject = (isset($email_from['subject']) ? $email_from['subject'].'. ' : '');
            $subject = str_replace('[code]', $countId, $subject);
            $subject = str_replace('[salesman]', getSaleCode(). ' ' . getFullname(), $subject);
            $subject = str_replace('[customer]', $customer , $subject);
            $subject = str_replace('[wh]', getWarehouse(getOutlet()) , $subject);
            $subject = str_replace('PO', '' , $subject);
            
            if(sendEmailLocal($from_email, $to_email, $subject, $body, $cc_mail))
                echo '<h4 class="text-center">'.lang('Email is sent').'</h4>';
            else
                echo '<h4 class="text-center">'.lang('Error on sending email').'</h4>';
        }
    }
}
