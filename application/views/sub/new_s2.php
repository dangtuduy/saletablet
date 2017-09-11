<div class="container">
<?php 
$siteurl = site_url('sales/ordernew/2/'); 
$urlsearch = !empty($keyword['sritem'])?('sritem='.$keyword['sritem']) : '';
$urlpage = ($curpage > 1) ? 'page='.$curpage : '';
$outlet = getOutlet();
?>
<div>
	<h3>CHỌN SẢN PHẨM (Kho <?= empty($outlet) ? 'CL.HCM' : getWarehouse($outlet)?>), hoặc <a class="btn btn-6" href="<?=site_url('exceldata/import/'.VSencode(isset($ordercust['custid']) ? $ordercust['custid'] : ''))?>">Import từ excel</a></h3>        
    <div><form method="get" action="<?=$siteurl?>"><div class="col-xs-10"><div class="col-xs-8"><input type="text" class="form-control input" placeholder="Tìm theo Mã/Tên" style="font-weight:bold" name="sritem"  id="sritem" value="<?=$keyword['sritem']?>" /></div><div class="col-xs-1"><input type="checkbox" class="form-control input" name="srhistory"  id="srhistory" <?=(isset($keyword['srhistory']) && $keyword['srhistory'] ? 'checked' : '')?> /></div><div class="col-xs-2">Chọn theo lịch sử khách hàng</div></div><div class="col-xs-1"><button type="submit" id="search" class="btn-warning btn">Tìm</div></div></form>
	<div class="clearfix text-right"><i>Mã# Giá bán# Tồn kho# Đang đặt hàng#</i></div>
	<?php                           
	$cn = 0;  
	foreach($itemlist as $item)
	{
		$cn++;
		echo '<div class="item"><div class="ct"><div><span>'.$item['itemid'].'#</span><span class="price">'.number_format($item['price'], 0).'đ#</span><span class="onhand">'.(isset($item['onhand'])?number_format($item['onhand'],0).$item['unitid'] : '').'#</span><span class="ordered">'.(isset($item['onorder'])?number_format($item['onorder'], 0):'').'#</span> <a href="'.(site_url('sales/instock').'?item='.$item['itemid']).'" target="_blank"><b>Xem PO</b></a></div><div>'.(isset($item['itemname'])?$item['itemname']:'').'</div></div>';
		echo '<form method="POST"><input type="hidden" value="'.$item['itemid'].'" name="addItemId" />Số lượng&nbsp;<input type="text" value="" name="qtyItem" class="text-center input-qty" autocomplete="off"  required /><input type="submit" name="ConfirmNew" class="btn btn-primary" value="Bán"/></form></div>';
	}
	echo '<div class="clearfix" style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_SEARCH ? true : false)).'</div>';
	?>
    </div>
</div>
<?php include('new_current.php')?>