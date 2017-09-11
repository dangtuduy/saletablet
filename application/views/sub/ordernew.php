<div class="container">
<?php
if(isset($step) && $step < 3)
{ 
    if($step == 1)
    { 
        $siteurl = site_url('sales/ordernew'); 
        $urlsearch = !empty($keyword['srcust'])?('srcust='.$keyword['srcust']) : '';
        $urlpage = ($curpage > 1) ? 'page='.$curpage : '';
    ?>
<div class="col-md-7">
        <h3><?=lang('Select customer')?>  <button data-toggle="modal" data-target="#modalnewcust" class="btn btn-primary"><?=lang('New customer')?></button></h3>
		<div class="row"><form method="get" action="<?=$siteurl?>"><div class="col-xs-9"></label><input type="text" class="form-control input" style="font-weight:bold" name="srcust" id="srcust" value="<?=$keyword['srcust']?>" placeholder="Tìm khách hàng theo Mã/ Tên/ Địa chỉ" /></div><div class="col-xs-3"><button class="btn-success btn" id="<?=lang('search')?>"><?=lang('Search')?></button></div></form></div>
        <br/><div class="clearfix">
        <?php                           
            $cn = 0;
            foreach($customer as $item)
            {
                $cn++;
                echo '<div class="customer"><div class="ct"><div class="name"><i>'.$item['custid'].'</i> #'.$item['name'].'</div><div>'.(isset($item['street'])?$item['street']:'').(isset($item['cellphone'])?' #'.$item['cellphone']:'').'</div></div><div><a href="'.$siteurl.'/2/'.$item['custid'].'" class="btn-success btn">Chọn</a></div></div><!--customer-->';
            }
            echo '<br class="clearfix"/>';
			echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_SEARCH ? true : false)).'</div>';
        ?>
        
		</div>
</div>
<?php 
    }
    else if($step == 2)
    {
        $siteurl = site_url('sales/ordernew/2/yes').'/';
        $urlsearch = !empty($keyword['sritem'])?('sritem='.$keyword['sritem']) : '';
        $urlsearch = !empty($keyword['srname'])?(empty($urlsearch)?'srname='.$keyword['srname'] : $urlsearch.'&srname='.$keyword['srname']) : $urlsearch;
        $urlsearch = !empty($keyword['srbrand'])?(empty($urlsearch)?'srbrand='.$keyword['srbrand'] : $urlsearch.'&srbrand='.$keyword['srbrand']) : $urlsearch;
        $urlpage = ($curpage > 1) ? 'page='.$curpage : '';
        $urlfull = empty($urlsearch) ? $urlpage : $urlsearch.(empty($urlpage)?'' : '&'.$urlpage);
?>
<div class="col-md-7">
    <h3>Chọn hàng bán</h3>        
    <div class="row"><form method="get" action="<?=$siteurl?>"><div class="col-xs-7"><input type="text" class="form-control input" placeholder="Tìm sản phẩm theo Mã/Tên" style="font-weight:bold" name="sritem" id="sritem" value="<?=$keyword['sritem']?>" /></div><div class="col-xs-3"><?php echo form_dropdown('srbrand', $itembrand, $keyword['srbrand'], ' class="form-control input"');?></div><div class="col-xs-2"><button type="submit" id="search" class="btn-warning btn">Tìm</div></div></form>
	<br/><div class="clearfix">
        <?php                           
            $cn = 0;  
            foreach($itemlist as $item)
            {
                $cn++;
                echo '<div class="item"><div class="ct"><div><span>'.$item['itemid'].'#</span><span class="price">'.number_format($item['price'], 0).'đ#</span><span class="onhand">'.(isset($item['onhand'])?number_format($item['onhand'],0).$item['unitid'] : '').'#</span><span class="ordered">'.(isset($item['onorder'])?number_format($item['onorder'], 0):'').'#</span></div><div>'.(isset($item['itemname'])?$item['itemname']:'').'</div></div><div><button data-toggle="modal" data-target="#modaladditem" value="'.$item['itemid'].'" class="btn btn-warning btnadditem">Bán</button></div></div>';
            }
            echo '<div class="clearfix" style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_SEARCH ? true : false)).'</div>';
        ?>
        
    </div>
</div>    
<?php        
    }
?>
<div class="col-md-5"><h3>&nbsp;</h3>
    <div class="panel panel-info">
        <div class="panel-heading"><?=lang('Current order')?></div>
        <div class="panel-body ordernow">
            <h5><b><?=lang('Customer')?>:</b>&nbsp;<?=isset($custid) && empty($custid)?'<a href="#" data-toggle="modal" data-target="#modalnewcust">'.lang('Change').'</a>&nbsp;&nbsp;':''?><span style="color: green;"><?php echo (isset($ordercust['custid']) ? '['.$ordercust['custid'].'] ' : '').(isset($ordercust['name']) ? $ordercust['name'].'; '.$ordercust['street'] : '').';{'.(isset($ordercust['custclass'])? $ordercust['custclass']:'').'}'?></span></h5>
            <div class="clearfix"><b class="text-primary"><?=lang('Selling items')?></b></div>
                <?php
                    $cisell = 0; 
                    if(isset($sellingItems))
                    {
                        ksort($sellingItems);
                        foreach($sellingItems as $name=>$value)
                        {
                            $cisell++;
                            echo '<div style="border-bottom:1pt solid #f0f0f0; height:22px; padding-top:3px; clear:both"><div class="col-xs-10">'.$name.'-'.$value['name'].(empty($value['note']) ? '': '<br/><i style="color:#aaa">'.$value['note'].'</i>').'</div><div class="col-xs-2 center"><b>'.$value['qty'].'</b></div></div>';
                        }   
                    }  
                ?>
        </div>
    </div>
</div>
<?php
}
else if(isset($step) && $step == 3)
{
?>
<div>
    <div class="panel panel-yellow">
        <div class="panel-heading"><b>ĐƠN HÀNG MỚI CỦA <i style="color:#000"><?=(isset($ordercust['custid']) ? '['.$ordercust['custid'].'] ' : '[New] ').(isset($ordercust['name']) ? $ordercust['name']:'').(isset($ordercust['street']) ? ', '.$ordercust['street'] : '')?></i></b></div>
        <div class="panel-body">
        <?php
            if(isset($action) && $action == ACTION_DONE)
            {
                echo '<h3 class="center">'.lang('YOUR ORDER HAS BEEN COMPLETED').'</h3>';
                echo '<div style="margin:5px" class="center"><b><a href="'.site_url('sales/ordernew/1').'" style="width:100%" class="btn btn-warning">'.lang('CREATE A NEW SALE ORDER').'</a></b></div>';
                echo '<div style="margin: 5px 10px" class="center"><b><a href="'.site_url('sales/orderlist').'" class="btn btn-primary" style="width:100%">'.lang('VIEW YOUR ORDERS').'</a></b></div>';
            }
            else
            {
        ?>
            <form method="POST" action="<?=site_url('sales/ordernew/3/')?>" class="form-horizontal">
				
                <div class="form-group"><div class="col-xs-2"><label>Đ/c giao hàng 1</label></div><div class="col-xs-10"><textarea class="form-control" name="deliaddress" required><?=isset($ordercust['street']) ? $ordercust['street'] : ''?></textarea></diV>
                </div><div class="form-group"><div class="col-xs-2"><label>Đ/c giao hàng 2</label></div><div class="col-xs-10"><textarea class="form-control" name="deliaddress2"><?=isset($ordercust['street2']) ? $ordercust['street2'] : ''?></textarea></div>
                </div><div class="form-group"><div class="col-xs-2"><label>Comment</label></div><div class="col-xs-10"><textarea class="form-control" name="comment" placeholder="<?=lang('Write order notes')?>"></textarea></div>
                </div>
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
        ksort($sellingItems);
        foreach($sellingItems as $name=>$value)
        {
            $ci++;
            $qtytotal += $value['qty'];
            $amttotal += ($value['qty'] * $value['price']);
            
            echo '<tr style="border-bottom:1pt solid #f0f0f0; padding:3px;"><td>'.$ci.'</td><td class="text-left"><span class="text-bold">'.$name.'</span>#'.$value['name'].' #<span class="price">'.$value['price'].'đ</span></td>';
            echo '<td><input type="text" name="qty'.$name.'" class="form-control input-number qtytable" value="'.$value['qty'].'" min="1" max="100">';
            echo '</td><td class="text-right">'.number_format($value['qty']*$value['price']).'</td><td><a href="'.site_url('sales/ordernew/3/'.ACTION_DEL.'/'.$name).'" class="btn btn-danger quantity-delete btntable"><span class="glyphicon glyphicon-remove"></span></a>';
            echo '</td></tr>';
            echo (isset($value['note']) && !empty($value['note']) ?'<tr><td colspan="5" style="color:#aaa; font-size:9pt"><textarea style="width:100%; height:40px" name="note'.$name.'">'.$value['note'].'</textarea></td></tr>' : '');
        }   
    }
    $discTotal = $discPct * $amttotal * 0.01;
    echo '<tr class="paging text-center" style="padding:3px; background:#f9eec6;"><td colspan=2><input type="hidden" name="action" value="'.ACTION_UPDATE.'"/>Tổng</td><td>'.$qtytotal.'</td><td class="text-right">'.number_format($amttotal, 0).'</td><td></td></tr>';
    echo '<tr class="paging text-center" style="padding:3px; font-weight:bold"><td colspan=4>'.($discPct > 0 ? '('.$discPct.'%)':'').lang('Discount').': '.number_format($discTotal, 0).'đ &nbsp;&nbsp;&nbsp;&nbsp; '.lang('Payment').': '.number_format($amttotal-$discTotal, 0).'đ</td><td></td></tr>';
?>
				</tbody></table>
				<div class="clearfix text-right" style="margin-top: 10px; padding-top:10px; border-top: 1pt solid rgb(249, 231, 205);"><a href="<?=site_url('sales/ordernew/2/')?>"><?=lang('Back to selling item')?></a>&nbsp;<button class="btn btn-warning"><?=lang('Update qty')?></button>&nbsp;<input type="submit" name="SAVE" class="btn btn-success" value="<?=lang('Save order')?>"/>&nbsp;<input type="submit" name="SAVESEND" class="btn btn-primary" value="<?=lang('Save & Send')?>" /></div>
            </form>
    <?php 
        }
    ?>  
        </div>
    </div>
</div>
<?php
} //END#STEP3
?>
</div><!--#Container-->
<div id="modalnewcust" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Thông tin khách hàng mới</h4>
            </div>
            <div class="modal-body" id="modalbody">
                <form method="POST" action="<?=site_url('sales/ordernew/2/new')?>">
                    <div class="form-group input-group"><span class="input-group-addon">N</span><input value="<?=(isset($custid)&& empty($custid) && isset($ordercust['name']) ? $ordercust['name'] : '')?>" type="text" name="custname" class="form-control" placeholder="<?=lang('name').' '.lang('customer')?>" required /></div>
                    <div class="form-group input-group"><span class="input-group-addon">A</span><input type="text" value="<?=(isset($custid)&& empty($custid) && isset($ordercust['street']) ? $ordercust['street'] : '')?>" name="custstreet" class="form-control" placeholder="<?=lang('street')?>" required/></div>
                    <div class="form-group input-group"><span class="input-group-addon">P</span><input type="text" value="<?=(isset($custid)&& empty($custid) && isset($ordercust['cellphone']) ? $ordercust['cellphone'] : '')?>" name="custphone" class="form-control" placeholder="<?=lang('mobile')?>"/></div>
                    <div class="form-group input-group"><span class="input-group-addon">C</span><input type="text" value="<?=(isset($custid)&& empty($custid) && isset($ordercust['custclass']) ? $ordercust['custclass'] : '')?>" name="custclass" class="form-control" placeholder="<?=lang('classgroup')?>" /></div>              
                    <div align="center"><button type="submit" class="btn btn-primary">Tiếp theo</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="modaladditem" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modalbody" id="additembody">
                <form class="form-inline" method="POST">
                    <div class="center"><h4>Bạn muốn bán item <span id="additemid_l"></span>?</h4></div>
                    <br />
                    <div><input type="hidden" id="additemid" name="addItemId" /></div>
                    <div align="center"><label>Số lượng</label>&nbsp;<input value="1" type="text" name="qtyItem" class="text-center" class="form-control" required />&nbsp;<input type="submit" name="ConfirmNew" class="btn btn-primary" value="Xác nhận"/></div>
                </form>
            </div>
            <div class="clearfix modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>
<div class="clearfix" style="height:70px;"></div>
<div class="row" style="position:fixed; bottom:0px; background:#ddd; width:100%"><div class="<?=(isset($cisell)?'col-xs-7':'col-xs-12')?>"><?php include('ordernew_steplink.php')?></div><?php if(isset($cisell) && $cisell > 0) echo '<div class="col-xs-5"><div class="center" style="margin-top:10px;"><a href="'.site_url('sales/ordernew/3/edit/').'" class="btn btn-success">'.lang('Edit items').'</a>&nbsp;&nbsp;<a href="'.site_url('sales/ordernew/3/').'" class="btn btn-danger">'.lang('Finish order').'</a></div></div>';?></div>
