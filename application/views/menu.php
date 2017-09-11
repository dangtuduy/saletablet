<div id="footer"><a class="quickmenu img" href="<?=site_url('sales')?>"><span class="home"></span></a><a class="quickmenu" href="<?=site_url('sales/itemlist')?>"/>SKUs</a><a class="quickmenu" href="<?=site_url('sales/customerlist')?>"/>KHÁCH HÀNG</a><a class="quickmenu" href="<?=site_url('sales/focitemlist')?>"/>FOC</a><a class="quickmenu" href="<?=site_url('sales/axorders')?>"/>AX-SO</a>
<?php 
	$type = getUserType();
	$chanel = getChanel();
	
	if($type == USER_SALEMAN)
		echo '<a class="quickmenu" href="' .site_url('sales/ordernew'). '"/>ĐƠN HÀNG MỚI</a>';
	
	echo '<a class="quickmenu" href="' .site_url('sales/orderlist'). '"/>ĐƠN TABLET</a>';
	
	if($chanel == CHANEL_MT && $type == USER_SALEMAN)
		echo '<a class="quickmenu" href="' .site_url('salesmt/counting'). '"/>KIỂM KÊ</a>';
	
	if($type != USER_SALEMAN)
		echo '<a class="quickmenu" href="' .site_url('backoffice'). '"/>QUẢN TRỊ</a>';
?>
</div>