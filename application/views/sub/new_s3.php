<div class="panel panel-primary">
<div class="panel-heading"><b>ĐƠN HÀNG MỚI CỦA <i><?=(isset($ordercust['custid']) ? '['.$ordercust['custid'].'] ' : '[New] ').(isset($ordercust['name']) ? $ordercust['name']:'').(isset($ordercust['street']) ? ', '.$ordercust['street'] : '')?></i></b></div>
<div class="panel-body">
<?php
if(isset($action) && $action == ACTION_DONE)
{
	echo '<h3 class="text-center">ĐƠN HÀNG ĐÃ HOÀN TẤT</h3>';
	echo '<div class="text-center"><b><a href="'.site_url('sales/ordernew/1').'" style="width:100%" class="btn btn-warning">'.lang('CREATE A NEW SALE ORDER').'</a></b></div>';
	echo '<div class="text-center"><b><a href="'.site_url('sales/orderlist').'" class="btn btn-primary" style="width:100%">'.lang('VIEW YOUR ORDERS').'</a></b></div>';
}
else{
?>
<form method="POST" action="<?=site_url('sales/ordernew/3/')?>" class="form-horizontal">
<div><b>Đ/c giao hàng 1</b></div><div><textarea class="input form-control" rows="3" name="deliaddress" required><?=isset($ordercust['street']) ? $ordercust['street'] : ''?></textarea></div>
<div><b>Đ/c giao hàng 2</b></div><div><textarea class="input form-control" rows="2" name="deliaddress2"><?=isset($ordercust['street2']) ? $ordercust['street2'] : ''?></textarea></div>
<div><b>Comment</b></div><div><textarea class="form-control input" name="comment" rows="3" placeholder="<?=lang('Write order notes')?>"><?=isset($ordercust['comment']) ? $ordercust['comment'] : ''?></textarea></div>
<table class="table table-hover table-bordered text-center">
<thead><tr style="font-weight: bold;"><th>STT</th><th>Sản phẩm</th><th style="width: 15%;">Số lượng</th><th>Thành tiền</th><th style="width: 10%;"></th></tr></thead>
<tbody>
<?php
    $ci = 0;
    $qtytotal = 0;
    $amttotal = 0;
    $discAmount = isset($discount['amount']) ? $discount['amount'] : 0;
    $discPct = isset($discount['percent']) ? $discount['percent'] : 0;
    $discTotal = 0; 
    if(isset($sellingItems))
    {   
        //ksort($sellingItems);
        foreach($sellingItems as $name=>$value)
        {
            $ci++;
            $qtytotal += $value['qty'];
            $amttotal += ($value['qty'] * $value['price']);
            
            echo '<tr style="border-bottom:1pt solid #f0f0f0; padding:3px;"><td>'.$ci.'</td><td class="text-left"><span class="text-bold">'.$name.'</span>#'.$value['name'].' #<span class="price">'.$value['price'].'đ</span></td>';
            echo '<td><input type="text" name="qty'.$name.'" class="form-control input-qty qtytable" value="'.$value['qty'].'" min="1" max="1000">';
            echo '</td><td class="text-right">'.number_format($value['qty']*$value['price']).'</td><td><a href="'.site_url('sales/ordernew/3/'.ACTION_DEL.'/'.$name).'" class="btn btn-danger">Xóa</a>';
            echo '</td></tr>';
            echo '<tr><td colspan="5" style="font-size:9pt"><input type="text" onChange="changeValue(this)" style="background-color:#dafff6"" class="form-control" name="note'.$name.'" value="'.(isset($value['note']) ? $value['note'] : '').'" /></td></tr>';
        }   
    }
    $discTotal = $discPct * $amttotal * 0.01;
    echo '<tr class="paging text-center text-bold"><td colspan=2><input type="hidden" name="action" value="'.ACTION_UPDATE.'"/>Tổng</td><td>'.$qtytotal.'</td><td class="text-right">'.number_format($amttotal, 0).'</td><td></td></tr>';
    echo '<tr class="paging text-center text-bold"><td colspan=4>'.($discPct > 0 ? '('.$discPct.'%)':'').lang('Discount').': '.number_format($discTotal, 0).'Đ. Và thanh toán tạm tính: <span class="price">'.number_format($amttotal-$discTotal, 0).'Đ</span></td><td></td></tr>';
?>
</tbody></table>
<br/>
<div class="clearfix text-right"><a class="btn btn-none" href="<?=site_url('sales/ordernew/2/')?>">Tiếp tục bán</a><?=(($ci > 0)?'<button class="btn btn-warning">Cập nhật số lượng</button> <input type="submit" name="SAVESEND" class="btn btn-primary" value="Lưu &amp; Gửi AX" />':'')?></div>
</form>
<?php } ?>  
<script>
function changeValue(obj)
{
	obj.style.color = "blue";
	obj.style.fontWeight = "900";
}
</script>
<script type="text/javascript">

function checkSubmit(sendCS, file){
	
	if(file)
	{
		var file = document.getElementById('file');
		if(file.value == ''){
			alert('Please, select a attached order.')
			file.style="background-color:yellow";
			return false;
		}
	}
	if(sendCS){
		return confirm("BẠN CHẮC GỬI ĐƠN HÀNG NÀY ĐẾN CS + ADMIN?");
	}
	return confirm("ĐƠN HÀNG CHỈ LƯU, VÀ KHÔNG GỬI ĐẾN CS?");
}
</script>
