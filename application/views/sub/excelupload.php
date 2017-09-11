<form method="post" enctype="multipart/form-data" class="form-control">                      
	<h4>Chọn tập tin Excel để nhập SKU</h4>                        
	<div class="col-xs-8"><input type="file" class="btn btn-6" name="userfile" style="width:90%"/></div>
	<div class="col-xs-3"><input type="submit" class="btn btn-0" value="Xác nhận đọc dữ liệu" name="upload" /></div>
	<div class="clearfix"></div>
</form>
<?php
if(isset($reading))
{
	echo '<h2>Danh sách SKU từ tập tin excel</h3>';
	echo '<table class="table table-bordered text-center"><tr><th>STT</th><th>Mã SKU</th><th>Barcode</th><th>Số lượng</th><th>Tên trong excel</th><th>FOC trên excel</th><th>Tên trên hệ thống</th></tr><tbody>';
	$cn = 0;
	foreach ($reading as $value) 
	{
		$cn++;
		echo '<tr><td>'.$cn.'</td><td>'.$value['item'].'</td><td>'.$value['barcode'].'</td><td>'.number_format($value['qty'], 0).'</td><td>'.$value['namefile'].'</td><td>'.$value['focfile'].'</td><td>'.$value['name'].'</td></tr>';
	}
	echo '</tbody></table><br/>';
	echo '<form method="POST"><div align="right"><input type="submit" class="btn btn-primary" name="ConfirmedExcel" value="Xác nhận nhập đơn hàng"/><div></form>';
}
?>