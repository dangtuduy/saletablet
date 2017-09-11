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
            <thead><tr><th><?=lang('No.')?></th><th style="width: 38%;"><?=lang('Customer')?></th><th style="width: 40%;"><?=lang('Delivery street')?></th><th style="width: 10%;">Ngày thực hiện</th><th>Loại</th></tr></thead>
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
                $status = '<span style="color:#aaa">'.($item['status']== ORDER_SEND ? lang('Sent') : lang('Saved')).' '. ($status == '1970-01-01' ? '' : date('H:ip d.M', strtotime($item['lastsend_datetime']))).'</span>';
                
                echo '<tr><td class="text-center name" title="#'.$item['orderid'].'">'.$cn.'</td>';

                if((strtoupper($item['type']) == 'COUNTED'))
                {
                  echo '<td colspan="2" class="text-center"><span class="name">'.$item['custid'].' #'.$item['custname'].'</span> <span style="color:#aaa">#'.$item['orderid'].' #'.$item['salesman'].'</span><br/><button class="btn btn-1 detailInfo" data-toggle="modal" data-target="#modalDetailInfo" value="'.$item['orderid'].'">'.lang('Detail').'</button></td>';
                }
                else
                {
                  echo '<td colspan="2" class="text-center"><div><span class="name">'.$item['custid'].' #'.$item['custname'].'</span> <span style="color:#aaa">#'.$item['orderid'].' #'.$item['salesman'].'</span></div><div>'.$item['deliverystreet'].'</div>'
                  .'<div><button class="btn btn-1 detailInfo" data-toggle="modal" data-target="#modalDetailInfo" value="'.$item['orderid'].'">'.lang('Detail').'</button><button class="btn btn-2 axdetail" data-toggle="modal" data-target="#modalaxdetail" value="'.$item['orderid'].'">'.lang('On AX').'</button><button value="'.$item['orderid'].'" data-toggle="modal" data-target="#modalsend" data-backdrop="static" class="btn btn-0 goingsend">'.lang('Send AX').'</button>'.$status.'</div>'
                  .'</td>';
                }
                  echo '<td class="text-center">'.date('Y-m-d', strtotime($item['created_datetime'])).'</td><td class="text-center">'.strtoupper($item['type']).'</td></tr>';
            }
            echo '</tbody></table></div>';
            echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false)).'</div>';    
        ?>
    <!-- Modal -->
    <div id="modalDetailInfo" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-title"><b>THÔNG TIN CHI TIẾT</b></div>
          </div>
          <div class=" clearfix modal-body" id="detailInfo"><form>
            <!-- chi tiet don hang, dung ajax -->
          </div>
          <div class="clearfix modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('Close')?></button></div>
        </div>
      </div>
    </div>
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
