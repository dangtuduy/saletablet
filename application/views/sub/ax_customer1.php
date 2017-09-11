<h4 class="title"><b><?=lang('home_customer')?></b></h4>
<div>
        <?php
            $siteurl = site_url('sales/customerlist');                            
            $urlsearch = empty($keyword['srcust'])? '' : 'srcust='.$keyword['srcust'];
            $urlsearch = empty($keyword['srname']) ? $urlsearch : ($urlsearch.'&srname='.$keyword['srname']);
            $urlsearch = empty($keyword['srstreet']) ? $urlsearch : ($urlsearch.'&srstreet='.$keyword['srstreet']);
            $urlsearch = empty($keyword['srphone']) ? $urlsearch : ($urlsearch.'&srphone='.$keyword['srphone']);
            $urlsearch = empty($keyword['srdisc']) ? $urlsearch : ($urlsearch.'&srdisc='.$keyword['srdisc']);
            $urlsearch = empty($keyword['srclass']) ? $urlsearch : ($urlsearch.'&srclass='.$keyword['srclass']);
            $urlpage = 'page='.$curpage;
            $urlfull = empty($urlsearch) ? $urlpage : $urlsearch.'&'.$urlpage;
        ?>
    <div class="row">
            <table class="table table-bordered no-bottom table-hover table-striped">
            <thead><tr><th style="width: 10%;"><?=lang('Customer code')?></th><th><?=lang('Name')?></th><th><?=lang('Street')?></th><th style="width:15%"></th></tr></thead>
            <thead><form method="get" action="<?=$siteurl?>"><tr><th><input type="text" name="srcust" value="<?=isset($keyword['srcust'])?$keyword['srcust']:''?>" /></th><th><input type="text" name="srname" value="<?=isset($keyword['srname'])?$keyword['srname']:''?>" /></th><th><input type="text" name="srstreet" value="<?=isset($keyword['srstreet'])?$keyword['srstreet']:''?>" /></th><th><input type="submit" name="sr" class="btn btn-warning btntable" value="<?=lang('Search')?>" /></th></tr></form></thead>
            <tbody>
            
        <?php
            $utype = getUserType();
			$cn = 0; 
            foreach($allCust as $item)
            {
                $cn++;
                echo '<tr><form method="POST" action="'.$siteurl.'?'.$urlfull.'"><td>'.$item['custid'].'</td><td>'.$item['name'].' <a target="_blank" class="btn btn-2" href="'.site_url('sales/ordernew/2/'.VSencode($item['custid'])).'">Chọn bán</a></td><td>'.$item['street'].'</td>';
                echo '<td class="center"><a href="'.(site_url('sales/focitemlist').'?srcust='.$item['custid'].'&srclass='.$item['custclass']).'" target="_blank" class="btn btn-default">FOC</a>&nbsp;<a target="_blank" class="btn btn-1" href="'.site_url('sales/reports/revenue-one-customer').'?customer='.$item['custid'].'">S.R.N</a>';
                echo '</td></form></tr>';
            }
            echo '</tbody></table></div>';
            echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false)).'</div>';
        ?>
    </div> 
</div>