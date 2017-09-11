<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>AFF Sales</title>
</head>
<body>
    <div class="container">
		<?php echo $content; ?>
	</div>
    <?php include('menu.php')?>
</body>
</html>
<!-- Bootstrap Core CSS -->
<link href="<?=base_url('asset/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css"/>
<link href="<?=base_url('asset/css/mobile.css')?>" rel="stylesheet" type="text/css"/>
<script src="<?=base_url('asset/jquery/jquery.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('asset/jquery/jquery-ui.js')?>" type="text/javascript"></script>
<script src="<?=base_url('asset/js/bootstrap.min.js')?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?=base_url('asset/jquery/jquery-ui.css')?>"/>
<script>
$( function() {
    $( "#srodate" ).datepicker({dateFormat:"yy-mm-dd"});
} );
</script>
<script>
$(document).ready(function(){
    $(".goingsend").click(function(){
        $("#sendtoapi").val($(this).val());
		$("#idsend").html($(this).val());
		$("#sendtoapi").prop('disabled', false);
		$("#sendbody").html("");
		$("textarea#emailnote").val("");
    });
	$("#sendtoapi").click(function(){
        $("#sendbody").html("<?=lang('Sending order')?>");
		$("#sendtoapi").prop('disabled', true);
        $.ajax({
            url: "<?=site_url('sales/sendtoapi')?>", 
            data:{oid:$(this).val(),note:$("textarea#emailnote").val()},
            success: function(result){
                $("#sendbody").html(result);
            }
        });
        return true;
    });
    $(".axdetail").click(function(){
        $("#axodetail").html("<?=lang('Getting data')?>.....");
        $("#modaloid").html($(this).val());
        $.ajax({
            url: "<?=site_url('sales/axorderdetail')?>", 
            data:"refoid="+$(this).val(),
            success: function(result){
                $("#axodetail").html(result);
            }
        });
        return true;
    });
    $("#PullAxSales").click(function(){
        $("#axodetail").html("Cập nhật đơn hàng từ AX");
        var $this = $(this);
        $this.button('loading');
        setTimeout(function() {$this.button('reset');}, 20000);
        $.ajax({
            url: "<?=site_url('sales/PullAxOrder')?>", 
            data:"refoid="+$("#modaloid").html(),
            success: function(result){
                $this.button('reset');
                $("#axodetail").html(result);
            }
        });
        return true;
    });
});
</script>
	<script>
	$(document).ready(function(){
		$(".btndetail").click(function(){
			$("#sendbody").html("<h3><?=lang('Requesting to server...Please, wait a minute')?>.</h3>");
			$.ajax({
				url: "<?=site_url('sales/axorderdetail')?>", 
				data:"oid="+$(this).val(),
				success: function(result){
					$("#detailbody").html(result);
				}
			});
			return true;
		});
	});
	</script>
