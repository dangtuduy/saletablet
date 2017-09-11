<div class="row"><h4 class="well well-1"><b>ĐỐI SOÁT HÀNG VỀ NHẬP KHO <?=getWarehouse()?></b></h4></div>
<h3>THÔNG TIN SKU: <b>#<?=isset($iteminfo['itemid']) ? $iteminfo['itemid'] : ''?> - <?=isset($iteminfo['itemname']) ? $iteminfo['itemname'] : '##'?></b>, <b>đang có</b>: <?=isset($iteminfo['onhand']) ? $iteminfo['onhand'] : '0'?> <?=isset($iteminfo['unitid']) ? $iteminfo['unitid'] : ''?></b>.</h3>
<p style="color:#53709a; line-height: 1.45;">Chú thích: <b>PoNum</b>- Số PO; <b>Postatus</b>- Trạng thái; <b>Delidate</b>- Ngày nhập kho gần nhất; <b>LoadCont</b>- Bắt đầu load container; <b>LoadedCont</b>- Đã load xong; <b>Label</b>- Bắt đầu dán nhãn; <b>Labeled</b>- Đã dán nhãn xong.</p>
<?php          
$cn = 0;
$first = isset($dispatch[0]) ? $dispatch[0] : array();

echo '<table class="table table-bordered table-hover text-center"><thead><tr><th>STT</th>';
foreach ($first as $key => $value) 
{
    echo '<th>'.$key.'</th>';
}
echo '</tr></thead><tbody>';

foreach($dispatch as $item)
{
    $cn++;
    echo '<tr><td>'.$cn.'</td>';
    foreach ($item as $key => $value) 
    {
        if($key == 'PoNum')
            echo '<td><a href="'.site_url('sales/instock').'?po='.$value.'">'.$value.'</a></td>';
        else
            echo '<td>'.$value.'</td>';
    }
    echo '</tr>';
}
echo '</tbody></table>';
            
?>
</div>

