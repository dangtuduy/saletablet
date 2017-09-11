<div>
    <h4><b>ĐƠN SALETABLET ĐÃ LƯU</b></h4>
    <?php
        $siteurl = site_url('sales/orderlist');                           
        $cn = 0;  
        $urlsearch = empty($keyword['srcust'])? '' : 'srcust='.$keyword['srcust'];
        $urlsearch = empty($keyword['srstreet']) ? $urlsearch : ($urlsearch.'&srstreet='.$keyword['srstreet']);
        $urlsearch = empty($keyword['srodate']) ? $urlsearch : ($urlsearch.'&srodate='.$keyword['srodate']);
        $urlpage = 'page='.$curpage;
        $urlfull = empty($urlsearch) ? $urlpage : $urlsearch.'&'.$urlpage;
    ?>
            <table class="table table-bordered table-hover">
            <thead><tr><th><?=lang('No.')?></th><th style="width: 38%;"><?=lang('Customer')?></th><th style="width: 40%;"><?=lang('Delivery street')?></th><th style="width: 10%;"><?=lang('Order date')?></th><th><?=lang('Qty')?></th></tr></thead>
            <thead><form method="get" action="<?=$siteurl?>"><tr><th></th><th><input type="text" name="srcust" value="<?=isset($keyword['srcust'])?$keyword['srcust']:''?>" /></th><th><input type="text" name="srstreet" value="<?=isset($keyword['srstreet'])?$keyword['srstreet']:''?>" /></th><th><input type="text" autocomplete="off" id="srodate" name="srodate" value="<?=isset($keyword['srodate'])?$keyword['srodate']:''?>" /></th><th><input type="submit" name="sr" class="btn btn-warning btntable" value="<?=lang('Search')?>" /></tr></form></thead>
            <tbody>
            
        <?php                           
            $cn = 0;  
            $siteurl = site_url('sales/orderlist'); 
            $sid = array();      
            foreach($orderlist as $item)
            {
                $cn++;
                $sid[] = $item['orderid'];
				$status = date('Y-m-d', strtotime($item['lastsend_datetime']));
                $status = '<span style="color:#aaa">'.($item['status']== ORDER_SEND ? lang('Sent') : lang('Saved')).' '. ($status == '1970-01-01' ? '' : date('H:i d.M', strtotime($item['lastsend_datetime']))).'</span>';
                echo '<tr><td class="text-center name" title="#'.$item['orderid'].'">'.$cn.'</td><td class="text-center"><div><span class="name">'.$item['custid'].' #'.$item['custname'].'</span><span style="color:#aaa">#'.$item['orderid'].' #'.$item['salesman'].'</span></div>'
                .'<div><a href="#" class="btn btn-1 btntable" data-toggle="modal" data-target="#modal'.$item['orderid'].'">'.lang('Detail').'</a>&nbsp;<button class="btn btn-2 axdetail" data-toggle="modal" data-target="#modalaxdetail" value="'.$item['orderid'].'">'.lang('On AX').'</button><button value="'.$item['orderid'].'" data-toggle="modal" data-target="#modalsend" data-backdrop="static" class="btn btn-0 goingsend">'.lang('Send AX').'</button></div>'
                .'</td><td>'.$item['deliverystreet'].'<br/>'.$status.'</td><td class="text-center">'.date('Y-m-d', strtotime($item['created_datetime'])).'</td><td class="text-center">'.number_format($item['totalqty'], 0).'</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';
            echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false)).'</div>';    
        ?>
    <!-- Modal -->
    <?php 
        for($cn = 0; $cn < count($sid); $cn++)
        {
            $uid = getUserId();
            $utype = getUserType();
            if($utype != USER_SALEMAN)
                $uid = DATA_ALL;
            $detail = $this->salesdb->getOrderDetail($uid, $sid[$cn]);
            $totalqty = 0;
            $totalamt = 0;
    ?>
    <div id="modal<?=$sid[$cn]?>" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-title"><b><?=lang('Order')?>:&nbsp;</b><?=isset($detail[0]['orderid'])?$detail[0]['orderid']:'' ?><br /><b><?=lang('Customer')?>:</b>&nbsp;<?=isset($detail[0]['custname'])? $detail[0]['custname'] : ''?><br /><b><?=lang('Delivery street')?>:</b>&nbsp;<?=isset($detail[0]['deliverystreet'])? $detail[0]['deliverystreet'] : ''?>.<br /><b><?=lang('Order date')?>:</b> <?=isset($detail[0]['created_datetime'])? date('Y-m-d', strtotime($detail[0]['created_datetime'])) : ''?>.<b><?=lang('Send AX')?>:&nbsp;</b><?=isset($detail[0]['lastsend_datetime'])? date('Y-m-d', strtotime($detail[0]['lastsend_datetime'])) : ''?><div><b>Comment:</b> <?=isset($detail[0]['comment'])? $detail[0]['comment'] : ''?></div></div>
          </div>
          <div class=" clearfix modal-body"><form>
            <table class="table table-hover table-bordered"><thead><tr><th><?=lang('Item')?></th><th><?=lang('Qty')?></th><th><?=lang('Price')?></th><th><?=lang('Discount')?>.%</th><th><?=lang('Amount')?></th></tr></thead>
            <tbody>
        <?php
            
            foreach($detail as $item)
            {
                $attachedfile = isset($item['attachedfile']) ? $item['attachedfile'] : '';
                $totalqty += $item['qty'];
                $amount = $item['qty']*$item['price']*(1-$item['linepercent']*0.01);
                $totalamt += $amount;
                echo '<tr><td><b>'.$item['itemid'].'</b>-'.$item['itemname'].($item['itemid'] == 'FILE' ? '</div><div><a target="_blank" href="'.base_url('/uploads').'/'.$attachedfile.'"><strong>Xem file đơn hàng đính kèm</strong></div>' : '').'<td class="center">'.number_format($item['qty'], 0).'</td><td class="center">'.number_format($item['price'], 0).'</td><td class="center">'.($item['linepercent'] > 0 ? $item['linepercent'] : 0).'</td><td class="center">'.number_format($amount, 0).'</td></tr>';
                echo (isset($item['note']) && !empty($item['note'])) ? '<tr><td colspan="5" class="text-center featured">'.$item['note'].'</td></tr>' : '';
            }      
        ?>
            <tr><td class="center"><b><?=lang('Total')?></b></td><td class="center"><b><?=$totalqty?></b></td><td></td><td></td><td class="center"><b><?=number_format($totalamt, 0)?></b></td></tr>
            </tbody></table>
            </form>
          </div>
          <div class="clearfix modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('Close')?></button></div>
        </div>
      </div>
    </div>
    <?php }?>
    <div id="modalsend" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-title"><b>GỬI ĐẾN AX - ĐƠN HÀNG #<span id="idsend"></span></b></div>
          </div>
          <div class="clearfix modal-body">
			<div><div><b>Ghi chú email</b></div><div><textarea id="emailnote" rows="2"  style="width:90%"></textarea></div></div>
			<div style="text-align:center"><button value="" id="sendtoapi" class="btn btn-primary">Gửi Email &amp; đến AX</button></div>
			<div id="sendbody"></div>
          </div>
          <div class="clearfix modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('Close')?></button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal detail from ax-->
    <div id="modalaxdetail" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-title"><b><?=lang('On AX')?>: <?=lang('Order')?> #<span id="modaloid"></span> </b>&nbsp;<button id="PullAxSales" class="btn btn-green" type="button" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Pulling order"><?=lang('Pull from AX')?></button></div>
          </div>
          <div class="clearfix modal-body" id="axodetail">
          </div>
          <div class="clearfix modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('Close')?></button>
          </div>
        </div>
      </div>
    </div>
</div>
