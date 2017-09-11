<div class="row">
    <h4 class="title"><?=lang('home_foc')?> &nbsp;(<?=lang('Today').': '.date('d-M-Y', strtotime(nowdatetime()))?>)</h4>
    <?php
        $siteurl = site_url('backoffice/focitemlist');
        $urlsearch = empty($keyword['srcust']) ? '' : 'srcust='.$keyword['srcust'];
        $urlsearch = empty($keyword['srclass']) ? $urlsearch : ($urlsearch.'&srclass='.$keyword['srclass']);
        $urlsearch = empty($keyword['sritem']) ? $urlsearch : ($urlsearch.'&sritem='.$keyword['sritem']);
        $urlsearch = empty($keyword['srdate']) ? $urlsearch : ($urlsearch.'&srdate='.$keyword['srdate']);
        $urlsearch = empty($urlsearch) ? $urlsearch : '&'.$urlsearch;
        $urlpage = 'page='.$curpage;
    ?>
    <table class="table table-bordered no-bottom table-hover table-striped">
        <thead><tr><th><?=lang('No.')?></th><th style="width: 13%;"><?=lang('Customer')?></th><th><?=lang('Item code')?></th><th><?=lang('Order date')?></th><th style="width: 25%;"><?=lang('Description')?><th><?=lang('Rules')?></th></tr></thead>
        <thead><form method="get" action="<?=$siteurl?>"><tr><th></th><th><input style="width: 45%;" type="text" name="srcust" value="<?=isset($keyword['srcust'])?$keyword['srcust']:''?>" placeholder="<?=lang('Customer')?>" />&nbsp;<input type="text" style="width: 45%;" name="srclass" value="<?=isset($keyword['srclass'])?$keyword['srclass']:''?>" placeholder="<?=lang('classgroup')?>" /></th><th><input type="text" name="sritem" value="<?=isset($keyword['sritem'])?$keyword['sritem']:''?>" /></th><th><input type="text" autocomplete="off" id="srdate" name="srdate" value="<?=isset($keyword['srdate'])?$keyword['srdate']:''?>"  /></th><th colspan="2"><input type="submit" name="sr" class="btn btn-warning btntable" value="<?=lang('Search')?>" />&nbsp;&nbsp;<a href="<?=$siteurl?>" class="btn btn-danger btntable"><span class="fa fa-remove"></span></a></th></tr></form></thead>
        <tbody>
    <?php
        $cn = 0;
        foreach($allFocItem as $item)
        {
            $cn++;
            $condition = (empty($item['qtyfrom'])?'': ' Mua từ '.$item['qtyfrom']).(empty($item['qtyto'])?'':' đến '.$item['qtyto']);
            $condition = $condition.(empty($item['warehouse'])?'':', và tại '.$item['warehouse']); 
            $condition = ucfirst((substr($condition, 0, 1) == ',') ? substr($condition, 2) : $condition);
            echo '<tr class="center"><td>'.$cn.'</td><td>'.$item['custrelation'].'</td><td>'.$item['itemidsales'].(empty($item['inventbatchid']) ? '' : ' #'.$item['inventbatchid']).'<br/><i>'.$item['itemname'].'</i></td><td>'.date('Y-m-d', strtotime($item['fromdate'])).' -> '.date('Y-m-d', strtotime($item['todate'])).'</td><td>Mua <b>'.$item['itemqtysales'].'</b>, FREE <b>'.$item['itemqtyfoc'].'</b> '.$item['itemidfoc'].(empty($item['inventbatchfoc']) ? '' : ' {'.$item['inventbatchfoc'].'}').'</td>';
            echo '<td>'.(empty($condition)?'': $condition).'</td></tr>';
        }        
    ?>
        </tbody>
    </table>
    <?php
        echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false)).'</div>';    
    ?>
</div>
<link rel="stylesheet" href="<?=base_url('asset/jquery/jquery-ui.css')?>"/>
<script>
$( function() {
    $( "#srdate" ).datepicker({dateFormat:"yy-mm-dd"});
} );
</script>
