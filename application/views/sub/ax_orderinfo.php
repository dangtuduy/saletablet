<div>
    <?php
        $first = isset($allOrder[0]) ? $allOrder[0] : array();

        if(isset($dispatch) && count($dispatch))
        {   
            echo '<div style="background:#baf3ed; border-radius:5px;"><h4>Từ Dispatch tool '. (isset($first['refoid'])?' <span>[Ref #'.$first['refoid'].']</span>' : '').'</h4>';
            echo '<ul style="margin:0">';
            
            foreach ($dispatch as $item) {
                foreach ($item as $key => $value) {
                    echo '<li style="float:left; width:30%; padding:5px; margin:0;"><b>'.strtoupper($key).'</b>: '.$value.'</li>';
                }
                break;
            }
            echo '</ul><div style="clear:both">&nbsp;</div></div><br/>';
        }
        else if(isset($allOrder[0]['salesid']))
        {
            echo '<h4>'.$first['salesid']. (isset($first['name']) ? ', '.$first['name'] :'').', '.$first['warehouse'].', '.status($first['headstatus']).(isset($first['docstatus']) ? '<i>('.docStatus($first['docstatus']).')</i>' : '').(isset($first['refoid'])?' <span>[Ref #'.$first['refoid'].']</span>' : '').'</h4>';
        }
    ?>
    <table class="table table-bordered no-bottom table-hover table-striped">
        <thead><tr><th><?=lang('No.')?></th><th style="width: 10%;"><?=lang('Item')?></th><th style="width: 50%;"><?=lang('Name')?></th><th style="width: 15%;"><?=lang('Status')?></th><th style="width: 15%;"><?=lang('Qty')?></th><th style="width: 15%;"><?=lang('Price')?></th><th><?=lang('Discount')?></th><th style="width: 15%;"><?=lang('Amount')?></th><th>FOC</th></tr></thead>
        <tbody>
            
        <?php         
		if(isset($allOrder)){
            $cn = 0;                         
            $totalamt = 0;
            $totaldisc = 0;
			
            foreach($allOrder as $item)
            {
                $cn++;;
                $amount = ($item['linestatus'] == 4)? 0 : $item['salesqty'] * $item['salesprice'];
                $discount = ($item['linestatus'] == 4)? 0 : ($amount*$item['linepercent']*0.01);
                $item['lineamount'] = ($item['linestatus'] == 4) ? 0 : $item['lineamount'];
                $totalamt += $item['lineamount'];
                $totaldisc += $discount;
                echo '<tr '.($item['foc'] ? 'style="background:#ffebcd"' : '').'><td class="center">'.$cn.'</td><td class="center">'.$item['itemid'].'</td><td>'.$item['itemname'].'</td><td class="center">'.status($item['linestatus']).'</td><td class="center"><b>'.number_format($item['salesqty']).'</b></td><td class="center">'.number_format($item['salesprice'],0).'</td><td class="center">'.($item['linepercent'] != 0 ? $item['linepercent'].'%' : '').'</td><td class="center"><b>'.number_format($item['lineamount'],0).'</b></td><td>'.($item['foc'] ? 'Yes':'').'</td></tr>';
            }
            echo '<tr class="total"><td class="center" colspan="4"></td><td colspan=3>'.lang('Discount').':&nbsp;'.number_format($totaldisc, 0).'</td><td></td><td></td><tr>';
			echo '<tr class="total"><td class="center" colspan="4"><b>Tổng tiền thanh toán</b></td><td colspan=3>&nbsp</td><td><b style="color:red">'.number_format($totalamt, 0).'</b></td><td></td><tr>';
        }
		?>
        </tbody>
    </table>
</div>