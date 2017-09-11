<div class="row"><h4 class="title"><b><?=lang('home_item')?> <?=(isset($allItems[0]['created_datetime']) ? '('.$allItems[0]['created_datetime'].')' : '')?></b></h4></div>
    <?php
            $siteurl = site_url('sales/itemlist');
            $cn = 0;
            $urlsearch = empty($keyword['sritem'])? '' : 'sritem='.$keyword['sritem'];
            $urlsearch = empty($keyword['srname']) ? $urlsearch : ($urlsearch.'&srname='.$keyword['srname']);
            $urlsearch = empty($keyword['srbcode']) ? $urlsearch : ($urlsearch.'&srbcode='.$keyword['srbcode']);
            $urlsearch = empty($keyword['srbrand']) ? $urlsearch : ($urlsearch.'&srbrand='.$keyword['srbrand']);
			$urlsearch = empty($keyword['srwh']) ? $urlsearch : ($urlsearch.'&srwh='.$keyword['srwh']);
			
            $urlsearch = empty($urlsearch) ? $urlsearch : '&'.$urlsearch;
            $urlpage = 'page='.$curpage;
    ?>
    <div class="row">
            <table class="table table-bordered no-bottom table-hover table-striped">
            <thead><tr><th><?=lang('No.')?></th><th><?=lang('Item code')?></th><th><?=lang('Name')?></th><th><?=lang('Brand')?></th><th style="width: 10%;">WH</th><th style="width: 10%;"><?=lang('Price')?></th><th>Batch</th><th>Tồn kho</th><th><?=lang('Ordered')?></th><th></th></tr></thead>
            <thead><form method="get" action="<?=$siteurl?>"><tr><th></th><th><input type="text" name="sritem" value="<?=isset($keyword['sritem'])?$keyword['sritem']:''?>" /></th><th><input type="text" name="srname" value="<?=isset($keyword['srname'])?$keyword['srname']:''?>" /></th><th><?php echo form_dropdown('srbrand', $brands, $keyword['srbrand'], 'style="max-width:200px"');?></th><th><input type="text" name="srwh" value="<?=isset($keyword['srwh'])?$keyword['srwh']:''?>" /></th><th></th><th></th><th colspan="4"><input type="submit" name="sr" class="btn btn-warning" value="Tìm" /></th></tr></form></thead>
            <tbody>
        <?php                           
            foreach($allItems as $item)
            {
                $cn++;
                echo '<tr><td class="text-center">'.$cn.'</td><td>'.$item['itemid'].'</td><td>'.$item['itemname'] . (empty($item['barcode'])?'':' <i>@'.$item['barcode']) . '</i></td><td>'.(isset($brands[$item['brandid']])?$brands[$item['brandid']]:'').'</td><td class="text-center">'.$item['warehouse'].'</td><td class="text-center">'.number_format($item['price'], 0).'</td><td>'.$item['inventbatch'].'</td><td class="text-center"><b>'.number_format($item['availphysical'], 0).'</b> / '.number_format($item['onhand'], 0).'</td><td class="text-center">'.$item['onorder'].'</td>';
                echo '<td><a href="'.(site_url('sales/focitemlist').'?sritem='.$item['itemid']).'" target="_blank" class="btn btn-primary"><span class="glyphicon glyphicon-th"></span>Foc</a><a href="'.(site_url('sales/instock').'?item='.$item['itemid']).'" target="_blank" class="btn btn-0"><span class="fa fa-list"></span>PO</a>';
                echo '</td></tr>';
            }
            echo '</tbody></table>';
            echo makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false));
        ?>
    </div>

