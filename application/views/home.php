<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta name="author" content=""/>
<title>AFF Sales</title>
</head>
<body>
	<a class="well well-1" href="<?=site_url('sales/itemlist')?>"><h3>AX- SẢN PHẨM/ SKU</h3></a>
    <a class="well" href="<?=site_url('sales/ordernew')?>"><h3>ĐƠN HÀNG MỚI</h3></a>
    <a class="well well-2" href="<?=site_url('sales/orderlist')?>"><h3>ĐƠN HÀNG TABLET</h3></a>
    <a class="well well-3" href="<?=site_url('sales/customerlist')?>"><h3>AX- KHÁCH HÀNG</h3>
    <?=(getChanel() == CHANEL_MT) ? '<a class="well well-7" href="'. site_url('salesmt/counting').'"/><h3>KIỂM KÊ</h3></a>' : ''?>
    <a class="well well-4" href="<?=site_url('sales/focitemlist')?>"><h3>AX- FOC</h3></a></div>
    <a class="well well-5" href="<?=site_url('sales/axorders')?>"><h3>AX- SALES ORDER</h3></a>
    <a class="well well-6" href="<?=site_url('sales/reports')?>"><h3>BÁO CÁO ĐỒ THỊ</h3></a>
	<a class="well well-0" href="<?=site_url('user/profile')?>"><h3>THÔNG TIN TÀI KHOẢN</h3></a>
	<a class="well well-0" href="<?=site_url('question')?>"><h3>HỎI &AMP; ĐÁP</h3></a>
</body>
</html>
<link href="<?=base_url('asset/css/mobile.css')?>" rel="stylesheet" type="text/css"/>