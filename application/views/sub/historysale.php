<div>
<?php
	$obclass = $this->uri->segment(1);
    $siteurl = site_url($obclass.'/history');
	$urlsearch = empty($search['srcust'])? '' : 'srcust='.VSencode($search['srcust']);
	$urlsearch = empty($keyword['sritem']) ? $urlsearch : ($urlsearch.'&sritem='.$keyword['sritem']);
?>
	<h4>LỊCH SỬ ĐẶT HÀNG CỦA SKU</h4></div>
	<table class="table table-bordered no-bottom table-hover table-striped">
        <thead><tr><th><?=lang('No.')?></th><th>Customer</th><th style="width: 50%;">Item/Sku</th><th>Ngày đặt</th><th>Đặt hàng</th><th>Tình trạng</th><th>Kho</th></thead>
		<thead><form method="GET"><tr><th></th><th><input type="text" name="srcusta" value="<?=isset($search['srcust'])?$search['srcust']:''?>" /></th><th><input type="text" name="sritem" value="<?=isset($search['sritem'])?$search['sritem']:''?>" /></th><th></th><th><input type="submit" name="sr" class="btn btn-warning" value="Tìm" /></th><th colspan="2"></th></tr></form></thead>
        <tbody>
            
        <?php         
        $cn = 0;               
		$totalCounted = 0;
		$totalQty = 0;
		if(isset($orderlist)){
            foreach($orderlist as $item)
            {
                $cn++;;
				$totalQty += $item['salesqty'];
                echo '<tr class="text-center"><td>'.$cn.'</td><td class="text-left">'.$item['custaccount'].' #'.$item['custname'].'</td><td class="text-left">'.$item['itemid'].' #'.$item['itemname'].'</td><td>'.date('Y-m-d', strtotime($item['orderdate'])).'</td><td>'.number_format($item['salesqty'],0).'</td><td>'.status($item['linestatus']).'</td><td>'.$item['warehouse'].'</td></tr>';
            }
			echo '<tr class="total text-center"><td colspan="3"><b>Tổng</b></td><td></td><td><b>'.number_format($totalQty, 0).'</b></td><td colspan="3"></td></tr>';
			
        }
		?>
		</tbody></table>
		<?php echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == 2*PAGE_SEARCH ? true : false)).'</div>'; ?>
    
</div>