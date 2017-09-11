<?php 
if(isset($detail) && count($detail) > 0)
{
    echo '<div><b>Mã đơn:</b> ' . (isset($detail[0]['orderid']) ? $detail[0]['orderid'] : '') . '</div>';
    echo '<div><b>Khách hàng:</b> ' . (isset($detail[0]['custname']) ? $detail[0]['custname'] : '') . '</div>';
    echo '<div><b>Đ/C giao hàng:</b> '. (isset($detail[0]['deliverystreet']) ? $detail[0]['deliverystreet'] : '') .'</div>';
    echo '<div><b>Ngày thực hiện:</b> '. (isset($detail[0]['created_datetime']) ? date('Y-m-d', strtotime($detail[0]['created_datetime'])) : '') . '</div>';
    echo '<div><b>Đã gửi AX:</b> '. (isset($detail[0]['lastsend_datetime'])? date('Y-m-d', strtotime($detail[0]['lastsend_datetime'])) : '').'</div>';
    echo '<div><b>Comment:</b> '. (isset($detail[0]['comment'])? $detail[0]['comment'] :'') . '</div><br/>';
}    

if(isset($detail[0]['type']) && strtoupper($detail[0]['type']) == 'COUNTED')
{

?>
<table class="table text-center"><thead><tr><th>STT</th><th>Mặt hàng</th><th>Kiểm kê</th><th>Tối thiểu</th><th>Cần gấp</th></tr></thead>
<tbody>
<?php     

$totalqty = 0; $totalamt = 0;
$cn = 0;
foreach($detail as $item)
{
    $cn++;
    $totalqty += $item['qty'];
    echo '<tr><td>'.$cn.'</td><td class="text-left"><b>'.$item['itemid'].'</b>-'.$item['itemname'].'</td><td>'.number_format($item['counted'], 0).'</td><td>'.number_format(isset($item['minqty']) ? $item['minqty'] : 0, 0).'</td><td '.(($item['qty'] >= 0) ? 'style="color:red"' :'').'><b>'.number_format($item['qty'], 0).'</b></td></tr>';
}      
echo '<tr class="well-5"><td></td><td>Tổng</td><td></td><td></td><td><b>'.number_format($totalqty, 0).'</b></td></tr>';
?>
</tbody>
</table>
<?php

}
else
{

?>

<table class="table text-center"><thead><tr><th><?=lang('Item')?></th><th><?=lang('Qty')?></th><th><?=lang('Price')?></th><th><?=lang('Discount')?>.%</th><th><?=lang('Amount')?></th></tr></thead>
<tbody>
<?php     

$totalqty = 0; $totalamt = 0;

foreach($detail as $item)
{
    $totalqty += $item['qty'];
    $amount = $item['qty']*$item['price']*(1-$item['linepercent']*0.01);
    $totalamt += $amount;

    echo '<tr><td><b>'.$item['itemid'].'</b>-'.$item['itemname'].(isset($item['attachedfile']) && !empty($item['attachedfile']) ? ('<br/><a target="_blank" href="'.base_url('uploads/'.$item['attachedfile']).'"><b>Xem chi tiết file</b></a>') : '').'</td><td>'.number_format($item['qty'], 0).'</td><td>'.number_format($item['price'], 0).'</td><td>'.($item['linepercent'] > 0 ? $item['linepercent'] : 0).'</td><td>'.number_format($amount, 0).'</td></tr>';

    echo (isset($item['note']) && !empty($item['note'])) ? '<tr><td colspan="3" style="color:#999">'.$item['note'].'</td></tr>' : '';
}      
?>
    <tr><td><b>Tổng</b></td><td><b><?=$totalqty?></b></td><td></td><td></td><td><b><?=number_format($totalamt, 0)?></b></td></tr>
</tbody></table>

<?php

}

?>

        