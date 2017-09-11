<?php
    $siteurl = site_url('backoffice/axorders');
    $urlsearch = empty($keyword['srso'])? '' : 'srso='.$keyword['srso'];
    $urlsearch = empty($keyword['srcust']) ? $urlsearch : ($urlsearch.'&srcust='.$keyword['srcust']);
    $urlsearch = empty($keyword['sritem']) ? $urlsearch : ($urlsearch.'&sritem='.$keyword['sritem']);
    $urlsearch = empty($keyword['srodate']) ? $urlsearch : ($urlsearch.'&srodate='.$keyword['srodate']);
    $urlsearch = empty($keyword['srwh']) ? $urlsearch : ($urlsearch.'&srwh='.$keyword['srwh']);
    $urlsearch = empty($urlsearch) ? $urlsearch : '&'.$urlsearch;
    $urlpage = 'page='.$curpage;
            
?>
<div>
    <h4 class="title"><?=lang('home_salesorder')?></h4>
        <table class="table table-bordered no-bottom table-hover table-striped text-center">
        <thead><tr><th><?=lang('No.')?></th><th style="width: 10%;"><?=lang('AX.SO')?></th><th><?=lang('Customer')?></th><th style="width: 10%;"><?=lang('Order date')?></th><th style="width: 10%;"><?=lang('Delivery date')?></th><th style="width: 10%;">Kho</th><th style="width: 10%;"><?=lang('Status')?></th><th style="width: 10%;"><?=lang('Extra status')?></th><th></th></tr></thead>
        <thead><form method="get" action="<?=$siteurl?>"><tr><th></th><th><input type="text" name="srso" value="<?=isset($keyword['srso'])?$keyword['srso']:''?>" /></th><th><input type="text" name="srcust" value="<?=isset($keyword['srcust'])?$keyword['srcust']:''?>" /></th><th><input type="text" autocomplete="off" id="srodate" name="srodate" value="<?=isset($keyword['srodate'])?$keyword['srodate']:''?>" /></th><th></th><th><input type="text" name="srwh" value="<?=isset($keyword['srwh'])?$keyword['srwh']:''?>" /></th><th colspan="3"><input type="submit" name="sr" class="btn btn-warning btntable" value="<?=lang('Search')?>" />&nbsp;&nbsp;<a href="<?=$siteurl?>" title="<?=lang('Search all')?>" class="btn btn-danger btntable"><span class="fa fa-remove"></span></a></th></tr></form></thead>
        <tbody>
            
        <?php                           
            $cn = 0;       
            foreach($allOrder as $item)
            {
                $cn++;
                echo '<tr><td>'.$cn.'</td><td>'.$item['salesid'].'</td><td class="text-left">'.$item['custaccount'].'-'.$item['custname'].'</td><td>'.date('Y-m-d', strtotime($item['orderdate'])).'</td><td>'.date('Y-m-d', strtotime($item['deliverydate'])).'</td><td>'.$item['warehouse'].'</td><td>'.status($item['salesstatus']).'</td><td>'.statusextra($item['extrastatus']).'</td><td><button data-toggle="modal" data-target="#modaldetail" value="'.$item['salesid'].'" class="btn btn-primary btntable btndetail">'.lang('Detail').'</button></td></tr>';
            }
            echo '</tbody></table>';
            echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false)).'</div>';
        ?>
</div>
    <div id="modaldetail" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-title"><b>(AX) <?=lang('ORDER DETAIL')?></b></div>
          </div>
          <div class="clearfix modal-body" id="detailbody">
            <h3><?=lang('Requesting to server...Please, wait a minute')?>.</h3>
          </div>
          <div class="clearfix modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    
      </div>
    </div>
