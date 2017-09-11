<div class="panel panel-info">
<div class="panel-heading"><b>KIỂM KÊ MỚI CỦA <i><?=(isset($ordercust['custid']) ? '['.$ordercust['custid'].'] ' : '').' #'.(isset($ordercust['name']) ? $ordercust['name']:'').(isset($ordercust['street']) ? ', '.$ordercust['street'] : '')?></i></b></div>
<div class="panel-body">
<?php
if(isset($action) && $action == ACTION_DONE)
{
	echo '<h3 class="text-center">KIỂM KÊ ĐÃ HOÀN TẤT</h3>';
	echo '<div class="text-center"><b><a href="'.site_url('salesmt/counting/1').'" style="width:100%" class="btn btn-warning">TIẾP TỤC KIỂM KÊ</a></b></div><br/>';
	echo '<div class="text-center"><b><a href="'.site_url('sales/orderlist').'" class="btn btn-primary" style="width:100%">XEM LỊCH SỬ KIỂM KÊ</a></b></div>';
}
else{
?>
<form method="POST" action="<?=site_url('salesmt/counting/3/')?>" class="form-horizontal">
<div><b>Ghi chú</b></div><div><textarea class="form-control input" name="comment" rows="3" placeholder="<?=lang('Write order notes')?>"><?=isset($ordercust['comment']) ? $ordercust['comment'] : ''?></textarea></div><br/>
<table class="table table-hover table-bordered text-center">
<thead><tr style="font-weight: bold;"><th>STT</th><th>Sản phẩm</th><th>Tối thiểu</th><th>Đã đếm</th><th>Cần đặt hàng</th><th style="width: 10%;"></th></tr></thead>
<tbody>
<?php
    $ci = 0;
    $qtytotal = 0;
    $discTotal = 0;

    if(isset($countingItems))
    {   
        foreach($countingItems as $name=>$value)
        {
            $ci++;
            $qtytotal += $value['qty'];
            
            echo '<tr style="border-bottom:1pt solid #f0f0f0; padding:3px;"><td>'.$ci.'</td><td class="text-left"><span class="text-bold">'.$name.'</span>#'.$value['name'].'</td>';
            echo '<td>'.(isset($value['minqty']) ? $value['minqty'] : '').'</td><td>'.(isset($value['cntqty']) ? $value
                ['cntqty'] : 0).'</td><td class="text-center"><input type="number" name="qty'.$name.'" style="width:87%; color:blue" class="form-control input-qty qtytable" value="'.(isset($value['qty']) ? $value['qty'] : 0).'" max="9999">';
            echo '</td><td><a href="'.site_url('salesmt/counting/3/'.ACTION_DEL.'/'.$name).'" class="btn btn-danger">Xóa</a>';
            echo '</td></tr>';
        }   
    }
    echo '<tr class="paging text-center text-bold"><td colspan=2>Tổng</td><td></td><td></td><td>'.$qtytotal.'</td><td></td></tr>';
?>
</tbody></table>
<br/>
<div class="clearfix text-right"><a class="btn btn-2" href="<?=site_url('salesmt/counting/2')?>">TIẾP TỤC KIỂM</a><input type="submit" name="SAVESEND" class="btn btn-primary" value="XÁC NHẬN KIỂM KÊ" /></div>
</form>
<?php } ?>  
