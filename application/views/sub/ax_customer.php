<h4 class="title"><b><?=lang('home_customer')?></b></h4>
<div>
        <?php
            $obclass = $this->uri->segment(1);

            $siteurl = site_url($obclass.'/customerlist');                            
            $urlsearch = 'srname='.$keyword['srname'];
            $urlsearch = empty($keyword['srstreet']) ? $urlsearch : ($urlsearch.'&srstreet='.$keyword['srstreet']);
            $urlpage = 'page='.$curpage;
            $urlfull = empty($urlsearch) ? $urlpage : $urlsearch.'&'.$urlpage;
        ?>
    <div class="row">
        <div class="clearfix"><form class="searchfrm" method="GET" action="<?=$siteurl.'?page=1'?>" >
            <div class="col-xs-5"><input type="text" name="srname" value="<?=isset($keyword['srname'])?$keyword['srname']:''?>" placeholder="Tìm theo tên/mã" /></div>
            <div class="col-xs-5"><input type="text" name="srstreet" value="<?=isset($keyword['srstreet'])?$keyword['srstreet']:''?>" placeholder="Tìm theo địa chỉ" /></div>
            <div class="col-xs-1"><input type="submit" name="sr" class="btn btn-warning" value="<?=lang('Search')?>" /></div>
        </form></div>
        <div class="clearfix">    
        <?php
            $utype = getUserType();
			$cn = 0; 
            foreach($allCust as $item)
            {
                $cn++;
                echo '<div class="alist"><div><b><span class="name">'.$item['custid'].'</span> #'.$item['name'].'</b></div><div class="ct">'.$item['street'];
                echo '</div><div><a target="_blank" class="btn" href="'.site_url($obclass.'/ordernew/2/'.VSencode($item['custid'])).'">Chọn bán</a><a href="'.(site_url($obclass.'/focitemlist').'?srcust='.$item['custid'].'&srclass='.$item['custclass']).'" target="_blank" class="btn btn-3">FOC</a>&nbsp;<a target="_blank" class="btn btn-1" href="'.site_url($obclass.'/reports/revenue-one-customer').'?customer='.$item['custid'].'">Đồ thị</a><a target="_blank" class="btn btn-2" href="'.site_url($obclass.'/history?srcust='.VSencode($item['custid'])).'">Lịch sử</a></div></div>';
            }
            echo '<div class="clearfix"></div>';
            echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false)).'</div>';
        ?>
        </div>
    </div> 
</div>