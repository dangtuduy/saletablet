<?php include('mobile.php') ?>
<script src="<?=base_url('asset/jquery/jquery.min.js')?>" type="text/javascript"></script>
<script src="<?=base_url('asset/jquery/jquery-ui.js')?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?=base_url('asset/jquery/jquery-ui.css')?>"/>
<script src="<?=base_url('asset/js/bootstrap.min.js')?>"></script>
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
    $(".detailInfo").click(function(){
        $("#detailInfo").html("<?=lang('Getting data')?>.....");
        
        $.ajax({
            url: "<?=site_url('sales/orderinfo')?>", 
            data:"order="+$(this).val(),
            success: function(result){
                $("#detailInfo").html(result);
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
<style>
.modal-open .modal {overflow-x: hidden;overflow-y: auto;}
.fade.in{opacity:1}
.modal.in .modal-dialog {-webkit-transform: translate(0,0);-ms-transform: translate(0,0);-o-transform: translate(0,0);transform: translate(0,0);}
.modal {position: fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1050;display: none;overflow: hidden;-webkit-overflow-scrolling: touch;outline: 0;}
.fade {opacity: 0;-webkit-transition: opacity .15s linear;-o-transition: opacity .15s linear;transition: opacity .15s linear;}
.modal.fade .modal-dialog {-webkit-transition: -webkit-transform .3s ease-out;-o-transition: -o-transform .3s ease-out;transition: transform .3s ease-out;-webkit-transform: translate(0,0);-ms-transform: translate(0,0);-o-transform: translate(0,0);transform: translate(0,0);}
.modal-header {padding: 8px;border-bottom: 1px solid #e5e5e5;}
.modal-title {margin: 0;line-height: 1.42857143;}
.modal-content {position: relative;background-color: #fff;-webkit-background-clip: padding-box;background-clip: padding-box;border: 1px solid #999;border: 1px solid rgba(0,0,0,.2);border-radius: 6px;outline: 0;-webkit-box-shadow: 0 3px 9px rgba(0,0,0,.5);box-shadow: 0 3px 9px rgba(0,0,0,.5);}
.modal-content table{text-align:center;}
.modal-content h4{font-size:13pt;}
.modal-header .close {margin-top: -2px;}
.modal-body {position: relative;padding: 15px;}
.modal-footer {padding: 15px;text-align: right;border-top: 1px solid #e5e5e5;}
button.close {-webkit-appearance: none;padding: 0;cursor: pointer;background: 0 0;border: 0;}
.close {float: right;font-size: 21px;font-weight: 700;line-height: 1;color: #000;text-shadow: 0 1px 0 #fff;filter: alpha(opacity=20);opacity: .2;}
.modal-backdrop {position: fixed;top: 0;right: 0;bottom: 0;left: 0;z-index: 1040;background-color: #000;}
.modal-backdrop.fade {filter: alpha(opacity=0);opacity: 0;}
.modal-backdrop.in {filter: alpha(opacity=50);opacity: .5;}
@media (min-width: 768px){.modal-dialog {width: 75%;margin: 10px auto;background-color: #000;}}
</style>