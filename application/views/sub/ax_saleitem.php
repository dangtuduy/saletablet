<?php
    $siteurl = site_url('sales/axdetail');
    $urlsearch = empty($keyword['srso'])? '' : 'srso='.$keyword['srso'];
    $urlsearch = empty($keyword['srcust']) ? $urlsearch : ($urlsearch.'&srcust='.$keyword['srcust']);
    $urlsearch = empty($keyword['sritem']) ? $urlsearch : ($urlsearch.'&sritem='.$keyword['sritem']);
    $urlsearch = empty($keyword['srodate']) ? $urlsearch : ($urlsearch.'&srodate='.$keyword['srodate']);
    $urlsearch = empty($urlsearch) ? $urlsearch : '&'.$urlsearch;
    $urlpage = 'page='.$curpage;
            
?>
<div>
    <h4><b>SALES ORDER DETAIL LIST</b></h4>
        <table class="table table-bordered no-bottom table-hover table-striped">
        <thead><tr><th><?=lang('No.')?></th><th style="width: 10%;"><?=lang('AX.SO')?></th><th><?=lang('Customer')?></th><th style="width: 10%;"><?=lang('Order date')?></th><th style="width: 10%;"><?=lang('Status')?></th><th><?=lang('Item code')?></th><th><?=lang('Qty')?></th><th><?=lang('Amount')?></th></tr></thead>
        <thead><form method="get" action="<?=$siteurl?>"><tr><th></th><th><input type="text" name="srso" value="<?=isset($keyword['srso'])?$keyword['srso']:''?>" /></th><th><input type="text" name="srcust" value="<?=isset($keyword['srcust'])?$keyword['srcust']:''?>" /></th><th><input type="text" placeholder="yyyy-mm-dd" id="srodate" name="srodate" value="<?=isset($keyword['srodate'])?$keyword['srodate']:''?>" /></th><th></th><th><input type="text" placeholder="item search" name="sritem" value="<?=isset($keyword['sritem'])?$keyword['sritem']:''?>" /></th><th colspan="2"><input type="submit" name="sr" class="btn btn-warning btntable" value="<?=lang('Search')?>" /></th></tr></form></thead>
        <tbody>
            
        <?php                           
            $cn = 0;        
            foreach($allOrder as $item)
            {
                $cn++;
                echo '<tr><td class="text-center">'.$cn.'</td><td class="text-center">'.$item['salesid'].'</td><td>'.$item['custaccount'].'-'.$item['name'].'</td><td class="center">'.date('Y-m-d', strtotime($item['orderdate'])).'</td><td class="text-center">'.status($item['linestatus']).'</td><td>'.($item['itemid'].'- '.$item['itemname']).'</td><td class="text-center">'.$item['salesqty'].'</td><td class="text-right">'.(($item['linestatus'] == 2 || $item['linestatus'] == 3)? number_format($item['salesqty']*$item['salesprice']) : 0).'</td></tr>';
            }
            echo '</tbody></table>';
            echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false)).'</div>';
        ?>
</div>