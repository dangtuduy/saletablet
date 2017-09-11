	<div class="row">
    <?php
            $siteurl = site_url('backoffice/saleslist');
			if(isset($saleman))
			{
				echo '<h3 class="title">DANH SÁCH Khách hàng của <span class="text-primary">'.((isset($saleman['salecode'])?$saleman['salecode']:'').', '.(isset($saleman['fullname'])?$saleman['fullname']:'').', '.(isset($saleman['email'])?$saleman['email']:'')).'</span> <a href="'.site_url('backoffice/saleslist').'" class="btn btn-warning btntable">Về danh sách</a></h3>';
				$siteurl = $siteurl.'?sm='.(isset($saleman['email']) ? VSencode($saleman['email']) : '');
			}
			else
				echo '<h3 class="title"><b>DANH SÁCH Salesman</b> <span id="messid" class="text-danger"></span></h3>';
    ?>
	</div>
    <div class="row">
	<?php
		$cn = 0;
		$outlet = listOutlet();
		if(isset($allUser))
		{
	?>
            <table class="table table-bordered no-bottom table-hover table-striped">
            <thead><tr><th>STT</th><th>Mã sales</th><th style="width:410px">Email</th><th>Tên</th><th>Thuộc</th><th>Kênh</th><th></th></tr></thead>
            <tbody>
            
			<?php          
            foreach($allUser as $item)
            {
                $cn++;
                echo '<tr><td class="text-center">'.$cn.'</td><td class="text-center">'.$item['salecode'].'</td><td><span style="display:inline-block; width:270px">'.$item['email'].'</span><a href="'.$siteurl.'?sm='.VSencode($item['email']).'">Xem khách hàng</a>'.'</td><td id="nameL'.$cn.'">'.$item['fullname'].'</td><td class="text-center">'.$outlet[$item['outlet']].'</td><td class="text-center">'.$item['chanel'].'</td><td><button class="btn btn-danger btntable btnDelAll" value="L'.$cn.'">Xóa</button><input type="hidden" value="'.VSencode($item['salecode']).'" id="codeL'.$cn.'"/></td></tr>';
            }
			?>
            </tbody></table>
	<?php 
		}
		else if(isset($allCust))
		{
	?>
		<form method="POST" action="<?=$siteurl?>">
			<div>
			<label class="col-xs-1">Phân công mới</label><div class="col-xs-9"><input class="form-control" autocomplete="off" placeholder="mãkhách1, mãkhách2, mãkhách4, mãkhách4, mãkhách5,..." name="morecust"/></div><div class="col-xs-2"><input type="submit" class="btn btn-success" name="AddCustomer" value="Xác nhận"/></div>
			</div>
			<div class="clearfix">&nbsp;</div>
			<table class="table table-bordered no-bottom table-hover table-striped">
            <thead><tr><th>STT</th><th style="width: 10%;">Mã K.H</th><th>Tên khách hàng</th><th>Địa chỉ</th><th>Nhóm</th><th>Được theo bởi</th><th></th></tr></thead>
			<tbody>
		<?php
			$codeList = '';
			foreach($allCust as $item)
            {
				$cn++;
				$smancode = isset($item['salemans']) ? $item['salemans'] : '';
				$codeList = $codeList. $item['custid'].', ';
				
				echo '<tr><td class="text-center">'.$cn.'</td><td>'.$item['custid'].'</td><td>'.$item['name'].'</td><td>'.$item['street'].'</td><td class="text-center">'.$item['custclass'].'</td><td class="text-center" style="font-size:12px">'.str_replace(';', '; ', $smancode).'</td><td><a href="'.$siteurl.'&del='.$item['custid'].'" class="btn btn-danger btntable" >Gỡ bỏ</a></td></tr>';
			}
		?>
			</tbody></table>
			<div style="color:#999; padding:10px 0"><?=$codeList?></div>
		</form>
	<?php
		}
	?>
    </div>
<div class="clearfix"><br/></div>
<script>
	$(document).ready(function(){
		$(".btnDelAll").click(function(){
			var value = $(this).val();
			
			if(confirm('Bạn muốn xóa tất cả khách hàng của Saleman ['+$("#name"+value).html()+']?'))
			{
				$.ajax({
					url: "<?=site_url('backoffice/removeAllSaleLoving')?>", 
					data:"scode="+$("#code"+value).val(),
					success: function(result){
						$("#messid").html('----- Xóa thành công tất cả khách hàng của <i>'+result+'</i>');
					}
				});
				return true;
			}
			return false;
		});
	});
</script>
