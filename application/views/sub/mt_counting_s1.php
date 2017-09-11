<h4 class="title"><b><?=lang('home_customer')?> (MT)</b></h4>
<div>
        <?php
			$ctl = $this->uri->segment(1);
            $siteurl = site_url($ctl.'/counting/1');                            
            $urlsearch = empty($keyword['srcust'])? '' : 'srcust='.$keyword['srcust'];
            $urlsearch = empty($keyword['srname']) ? $urlsearch : ($urlsearch.'&srname='.$keyword['srname']);
            $urlsearch = empty($keyword['srstreet']) ? $urlsearch : ($urlsearch.'&srstreet='.$keyword['srstreet']);
            $urlpage = 'page='.$curpage;
            $urlfull = empty($urlsearch) ? $urlpage : $urlsearch.'&'.$urlpage;
        ?>
    <div class="row">
            <table class="table table-bordered no-bottom table-hover table-striped text-center">
            <thead><tr><th style="width: 5%">STT<th style="width: 10%;">Mã K.H</th><th>Tên gọi</th><th>Địa chỉ</th><th style="width:15%"></th></tr></thead>
            <thead><form method="get" action="<?=$siteurl?>"><tr><th></th><th><input type="text" name="srcust" value="<?=isset($keyword['srcust'])?$keyword['srcust']:''?>" /></th><th><input type="text" name="srname" value="<?=isset($keyword['srname'])?$keyword['srname']:''?>" /></th><th><input type="text" name="srstreet" value="<?=isset($keyword['srstreet'])?$keyword['srstreet']:''?>" /></th><th><input type="submit" name="sr" class="btn btn-warning btntable" value="<?=lang('Search')?>" /></th></tr></form></thead>
            <tbody>
            
        <?php
            $utype = getUserType();
			$cn = 0;

            foreach($allCust as $item)
            {
                $cn++;
                echo '<tr><td>'.((($curpage - 1) * PAGE_SEARCH) + $cn).'</td><td>'.$item['custid'].'</td><td>'.$item['name'].'</td><td>'.$item['street'].'</td>';
                echo '<td class="text-center"><a class="btn btn-primary" href="'.site_url($ctl.'/counting/2/'.urlencode(VSencode($item['custid']))).'">Kiểm kê</a>';
                echo '</td></tr>';
            }
            echo '</tbody></table></div>';
            echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_SEARCH ? true : false)).'</div>';
        ?>
    </div> 
</div>