<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta name="description" content=""/>
<meta name="author" content=""/>
<title>AFF Sales</title>
<!-- Bootstrap Core CSS -->
<link href="<?=base_url('asset/css/bootstrap.min.css')?>" rel="stylesheet"/>
<link href="<?=base_url('asset/css/plugins/morris.css')?>" rel="stylesheet"/>
<link href="<?=base_url('asset/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css"/>
<script src="<?=base_url('asset/js/morris/jquery.min.js')?>"></script>
<script src="<?=base_url('asset/jquery/jquery-ui.js')?>"></script>
<script src="<?=base_url('asset/js/bootstrap.min.js')?>"></script>
<script src="<?=base_url('asset/js/morris/raphael-min.js')?>"></script>
<script src="<?=base_url('asset/js/morris/morris.js')?>"></script>
<link href="<?=base_url('asset/css/backo.css')?>" rel="stylesheet"/>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
    <div class="container">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" style="padding:0px 15px; margin:0px;" href="<?=site_url('backoffice')?>"><img src="<?=base_url()?>/asset/images/logo.png" height="52px" alt="AFF Sales" title="AFF Sales"/></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
                <li><a href="<?=site_url('backoffice')?>"><i class="glyphicon glyphicon-home"></i></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-th-list"></i><b class="caret"></b>&nbsp;<?=lang('menu_ax')?></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=site_url('backoffice/itemlist')?>"><i class="fa fa-list-alt"></i>&nbsp;<?=lang('home_item')?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?=site_url('backoffice/customerlist')?>"><i class="fa fa-users"></i>&nbsp;<?=lang('home_customer')?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?=site_url('backoffice/axorders')?>"><i class="fa fa-list"></i>&nbsp;<?=lang('home_salesorder')?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?=site_url('backoffice/focitemlist')?>"><i class="fa fa-gift"></i>&nbsp;<?=lang('home_foc')?></a></li>
						<li class="divider"></li>
						<li><a href="<?=site_url('backoffice/saleslist')?>"><i class="fa fa-child"></i>&nbsp;DANH SÁCH SALESMAN</a></li>
                    </ul>
                </li>
                <li><a href="<?=site_url('backoffice/orderlist')?>"><i class="fa fa-eye"></i>&nbsp;<?=lang('home_tablist')?></a></li> 
                <li><a href="<?=site_url('backoffice/reports/revenue-now')?>"><i class="fa fa-bar-chart"></i>&nbsp;<?=lang('home_report')?></a></li>       
                <?php if(isAdmin()){ ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> <b class="caret"></b>&nbsp;<?=lang('menu_admin')?></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li>
                            <a href="<?=site_url('admin/user')?>"><i class="fa fa-user"></i>&nbsp;<?=lang('menu_user')?></span></a>
                        </li><li class="divider"></li>
                        <li>
                            <a href="<?=site_url('admin/apisync')?>"><i class="fa fa-database"></i>&nbsp;<?=lang('home_sync')?></span></a>
                        </li><li class="divider"></li>
                        <li>
                            <a href="<?=site_url('admin/settings?name=apisetting')?>"><i class="fa fa-cogs"></i>&nbsp;<?=lang('home_setting')?></span></a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i><?=lang('Welcome').', '.getFullname()?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=site_url('user/profile')?>"><i class="fa fa-fw fa-user"></i> <?=lang('Profile')?></a></li>
                        <li class="divider"></li>
                        <li><a href="<?=site_url('user/logout')?>"><i class="fa fa-fw fa-power-off"></i> <?=lang('Log out')?></a></li>
                    </ul>
                </li>
            </ul></div>
        </nav>    
        <div style="margin-top:5px;">
            <?php echo $content; ?>
        </div>
        <footer>
        </footer>
        <a id="back-to-top" href="#" class="btn btn-warning btn-lg back-to-top" role="button" title="Click to return on the top page" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>
    </div>
</body>
</html>
<!-- Bootstrap Core CSS -->
<link rel="stylesheet" href="<?=base_url('asset/jquery/jquery-ui.css')?>"/>
<script>
$(document).ready(function(){
     $(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });
        $('#back-to-top').click(function () {
            $('#back-to-top').tooltip('hide');
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
        $('#back-to-top').tooltip('show');

});
</script>
<?php $control = strtoupper($this->uri->segment(2));
if($control  == 'ORDERNEW'){ ?>
	<script>
	$(document).ready(function(){
		$(".btnadditem").click(function(){
			item = $(this).val();
			$("#additemid").val(item);
			$("#additemid_l").html(item);
		});
	});
	</script>
<?php }
elseif($control  == 'AXORDERS'){ ?>
	<script>
	$( function() {
		$( "#srodate" ).datepicker({dateFormat:"yy-mm-dd"});
	} );
	</script>
	<script>
	$(document).ready(function(){
		$(".btndetail").click(function(){
			$("#sendbody").html("<h3><?=lang('Requesting to server...Please, wait a minute')?>.</h3>");
			$.ajax({
				url: "<?=site_url('backoffice/axorderdetail')?>", 
				data:"oid="+$(this).val(),
				success: function(result){
					$("#detailbody").html(result);
				}
			});
			return true;
		});
	});
	</script>
<?php } 
elseif($control == "ORDERLIST" || empty($control)){?>
<script>
$( function() {
    $( "#srodate" ).datepicker({dateFormat:"yy-mm-dd"});
} );
</script>
<script>
$(document).ready(function(){
    $(".sendapi").click(function(){
        $("#sendbody").html("<?=lang('Sending order')?>");
        $.ajax({
            url: "<?=site_url('backoffice/sendtoapi')?>", 
            data:"oid="+$(this).val(),
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
            url: "<?=site_url('backoffice/axorderdetail')?>", 
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
            url: "<?=site_url('backoffice/PullAxOrder')?>", 
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
<?php }?>