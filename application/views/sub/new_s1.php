<div class="container">
<?php 
$siteurl = site_url('sales/ordernew'); 
$urlsearch = !empty($keyword['srcust'])?('srcust='.$keyword['srcust']) : 'srcust=*';
$urlpage = ($curpage > 1) ? 'page='.$curpage : '';
?>
<div>
	<h3>Chọn khách hàng</h3>
	<div><form method="get" action="<?=$siteurl?>"><div class="col-xs-9"></label><input type="text" class="form-control input" style="font-weight:bold" name="srcust" id="srcust" value="<?=$keyword['srcust']?>" placeholder="Tìm khách hàng theo Mã/ Tên/ Địa chỉ" /></div><div class="col-xs-2"><button class="btn-success btn">Tìm</button></div></form></div>
	<div class="clearfix"></div>
	<?php                           
		$cn = 0;
        foreach($customer as $item)
        {
			$cn++;
			if(isset($item['street']) && strlen($item['street']) > 80)
				$item['street'] = (substr($item['street'], 0, 80).'...');
            echo '<div class="customer"><div class="ct"><div><b><i class="name">'.$item['custid'].'</i> #'.$item['name'].'</b></div><div>'. (isset($item['street'])?$item['street']:'').(isset($item['cellphone']) && !empty($item['cellphone'])?' #'.$item['cellphone']:'').'</div></div><div class="text-right"><a href="'.$siteurl.'/2/'.VSencode($item['custid']).'" class="btn-success btn"><b>Chọn bán</b></a><a href="'.$siteurl.'/3/?type=File&c='.VSencode($item['custid']).'" class="btn btn-0"><b>Gửi File</b></a></div></div><!--customer-->';
        }
        echo '<br class="clearfix"/>';
		echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_SEARCH ? true : false)).'</div>';
    ?>    
	</div>
	<div><form method="POST" action="<?=site_url('sales/ordernew/2/new')?>">
	<div><b>Khách hàng mới</b></div><div class="col-xs-9"><textarea rows="2" class="form-control" name="custname" required=""></textarea></div><div class="col-xs-2"><button type="submit" class="btn btn-primary">Chọn mới</button></div>
	<div class="clearfix"></div>
	</form></div>
</div>
<?php include('new_current.php')?>
