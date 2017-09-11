<div>
    <h4><b>ĐƠN SALETABLET ĐÃ LƯU</b></h4>
    <div class="row">
    <?php
        $siteurl = site_url('backoffice/orderlist');                           
        $cn = 0;  
        $urlsearch = empty($keyword['srcust'])? '' : 'srcust='.$keyword['srcust'];
        $urlsearch = empty($keyword['srstreet']) ? $urlsearch : ($urlsearch.'&srstreet='.$keyword['srstreet']);
        $urlsearch = empty($keyword['srodate']) ? $urlsearch : ($urlsearch.'&srodate='.$keyword['srodate']);
        $urlpage = 'page='.$curpage;
        $urlfull = empty($urlsearch) ? $urlpage : $urlsearch.'&'.$urlpage;
    ?>
            <table class="table table-bordered table-hover text-center">
            <thead><tr><th><?=lang('No.')?></th><th style="width: 25%;"><?=lang('Customer')?></th><th style="width: 35%;"><?=lang('Delivery street')?></th><th><?=lang('Order date')?></th><th><?=lang('Qty')?></th><th><?=lang('Status')?></th><th style="width: 15%;"></th></tr></thead>
            <thead><form method="get" action="<?=$siteurl?>"><tr><th></th><th><input type="text" name="srcust" value="<?=isset($keyword['srcust'])?$keyword['srcust']:''?>" /></th><th><input type="text" name="srstreet" value="<?=isset($keyword['srstreet'])?$keyword['srstreet']:''?>" /></th><th><input type="text" autocomplete="off" id="srodate" name="srodate" value="<?=isset($keyword['srodate'])?$keyword['srodate']:''?>" /></th><th></th><th></th><th><input type="submit" name="sr" class="btn btn-warning btntable" value="<?=lang('Search')?>" />&nbsp;&nbsp;<a href="<?=$siteurl?>" title="<?=lang('Search all')?>" class="btn btn-danger btntable"><span class="fa fa-remove"></span></a></tr></form></thead>
            <tbody>
            
        <?php                           
            $cn = 0;  
            $siteurl = site_url('backoffice/orderlist'); 
            $sid = array();      
            foreach($orderlist as $item)
            {
                $cn++;
                $sid[] = $item['orderid'];
				$status = date('Y-m-d', strtotime($item['lastsend_datetime']));
                $status = ($item['status']== ORDER_SEND ? lang('Sent') : lang('Saved')).' '. ($status == '1970-01-01' ? '' : date('H:i d.M', strtotime($item['lastsend_datetime'])));
                echo '<tr><td class="text-center" title="#'.$item['orderid'].'">'.$cn.'</td><td>['.$item['custid'].'] '.$item['custname'].' <span style="color:#aaa">#'.$item['orderid'].' #'.$item['salesman'].'</span>'.'</td><td>'.(strtoupper($item['type']) != 'SALES' ? '<b>'.strtoupper($item['type']).'</b>' : '').$item['deliverystreet'].'</td><td class="center">'.date('Y-m-d', strtotime($item['created_datetime'])).'</td><td class="center">'.number_format($item['totalqty'], 0).'</td><td class="center">'.$status.'</td>';
                echo '<td class="text-center"><a href="#" class="btn btn-info btntable" data-toggle="modal" data-target="#modal'.$item['orderid'].'">'.lang('Detail').'</a>&nbsp;<button class="btn btn-success btntable axdetail" data-toggle="modal" data-target="#modalaxdetail" value="'.$item['orderid'].'">'.lang('On AX').'</button></td></tr>';
            }
            echo '</tbody></table></div>';
            echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false)).'</div>';    
        ?>
        
    </div>
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
            <table class="table table-hover table-bordered text-center"><thead><tr><th><?=lang('Item')?></th><th><?=lang('Qty')?></th><th><?=lang('Price')?></th><th><?=lang('Discount')?>.%</th><th><?=lang('Amount')?></th></tr></thead>
            <tbody>
        <?php
            $editqty = isset($detail[0]['lastsend_datetime']) ? 'disabled' : '';        
            foreach($detail as $item)
            {
                $totalqty += $item['qty'];
                $amount = $item['qty']*$item['price']*(1-$item['linepercent']*0.01);
                $totalamt += $amount;
                echo '<tr><td><b>'.$item['itemid'].'</b>-'.$item['itemname'].'</div><td class="center">'.number_format($item['qty'], 0).'</td><td class="center">'.number_format($item['price'], 0).'</td><td class="center">'.($item['linepercent'] > 0 ? $item['linepercent'] : 0).'</td><td class="center">'.number_format($amount, 0).'</td></tr>';
                echo (isset($item['note']) && !empty($item['note'])) ? '<tr><td colspan="4" class="center" style="color:#999">'.$item['note'].'</td></tr>' : '';
            }      
        ?>
            <tr><td class="center"><b><?=lang('Total')?></b></td><td class="center"><b><?=$totalqty?></b></td><td></td><td></td><td class="center"><b><?=number_format($totalamt, 0)?></b></td></tr>
            </tbody></table>
            <hr />
            <div align="right"><button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('Close')?></button></div>
            </form>
          </div>
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
<script>
$(document).ready(function(){
    $(".goingsend").click(function(){
        $("#sendtoapi").val($(this).val());
		$("#idsend").html($(this).val());
		$("#sendtoapi").prop('disabled', false);
		$("#sendbody").html("");
		$("textarea#emailnote").val("");
    });
	$("#sendtoapi").click(function(){
        $("#sendbody").html("<?=lang('Sending order')?>");
		$("#sendtoapi").prop('disabled', true);
        $.ajax({
            url: "<?=site_url('backoffice/sendtoapi')?>", 
            data:{oid:$(this).val(),note:$("textarea#emailnote").val()},
            success: function(result){
                $("#sendbody").html(result);
            }
        });
        return true;
    });
	
    $(".axdetail").click(function(){
        $("#axodetail").html("<?=lang('Getting data')?>.....");
        $("#modaloid").html($(this).val());
        $.ajax({
            url: "<?=site_url('backoffice/axorderdetail')?>", 
            data:"refoid="+$(this).val(),
            success: function(result){
                $("#axodetail").html(result);
            }
        });
        return true;
    });
    $("#PullAxSales").click(function(){
        $("#axodetail").html("Cập nhật đơn hàng từ AX");
        var $this = $(this);
        $this.button('loading');
        setTimeout(function() {$this.button('reset');}, 20000);
        $.ajax({
            url: "<?=site_url('backoffice/PullAxOrder')?>", 
            data:"refoid="+$("#modaloid").html(),
            success: function(result){
                $this.button('reset');
                $("#axodetail").html(result);
            }
        });
        return true;
    });
});
</script>