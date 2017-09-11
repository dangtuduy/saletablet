<div class="panel panel-primary">
<div class="panel-heading"><b>ĐƠN HÀNG MỚI CỦA <i><?=(isset($ordercust['custid']) ? '['.$ordercust['custid'].'] ' : '[New] ').(isset($ordercust['name']) ? $ordercust['name']:'').(isset($ordercust['street']) ? ', '.$ordercust['street'] : '')?></i></b></div>
<div class="panel-body">
<?php
$custid = isset($ordercust['custid']) ? $ordercust['custid'] : '';

if(isset($action) && $action == ACTION_DONE)
{
	echo '<h3 class="text-center">ĐƠN HÀNG ĐÃ HOÀN TẤT</h3>';
	echo '<div class="text-center"><b><a href="'.site_url('sales/neworder/1').'" style="width:90%" class="btn-warning">TIẾP TỤC ĐẶT HÀNG</a></b></div><br/>';
	echo '<div class="text-center"><b><a href="'.site_url('sales/orderlist').'" class="btn-primary" style="width:90%">'.lang('VIEW YOUR ORDERS').'</a></b></div>';
}
else{
?>
<form method="POST" action="<?=site_url('sales/ordernew/3').'?type=File&c='.VSencode($custid)?>" class="form-horizontal" enctype="multipart/form-data">
<div><b>Đ/c giao hàng 1</b></div><div><textarea class="input form-control" rows="3" name="deliaddress" required><?=isset($ordercust['street']) ? $ordercust['street'] : ''?></textarea></div>
<div><b>Đ/c giao hàng 2</b></div><div><textarea class="input form-control" rows="2" name="deliaddress2"><?=isset($ordercust['street2']) ? $ordercust['street2'] : ''?></textarea></div>
<div><b>Comment</b></div><div><textarea class="form-control input" name="comment" rows="3" placeholder="<?=lang('Write order notes')?>"><?=isset($ordercust['comment']) ? $ordercust['comment'] : ''?></textarea></div>
<br/>
<div><b>File đơn hàng dạng png/jpg/gif/pdf, và không quá 5MB</b></div><div><input type="file" id="file" name="userfile" class="form-control"></div>
<br/>
<div class="clearfix text-right"><a class="btn btn-2" href="<?=site_url('sales/ordernew/1')?>">Đổi khách hàng</a><input type="submit" name="SAVESEND" class="btn btn-1" value="Lưu &amp; Gửi AX" onClick="return checkSubmit(1)" /></div>
</form>
<?php } ?>  
<script type="text/javascript">
function checkSubmit(sendCS){
	var file = document.getElementById('file');
	if(file.value == ''){
		alert('Please, select a attached order.')
		file.style="background-color:yellow";
		return false;
	}
	if(sendCS){
		return confirm("BẠN CHẮC GỬI ĐƠN HÀNG NÀY ĐẾN CS?");
	}
    return TRUE;
}
</script>

