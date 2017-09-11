    <?php
        $actions = isset($apisetting['actions']) ? $apisetting['actions'] : array();
        $a_itemstock = isset($actions['itemstock']) ? $actions['itemstock'] : '';
        $a_salesorder = isset($actions['salesorder']) ? $actions['salesorder'] : '';
        $a_saledetail = isset($actions['saledetail']) ? $actions['saledetail'] : '';
        $a_customer = isset($actions['customer']) ? $actions['customer'] : '';
        $a_custdiscount = isset($actions['custdiscount']) ? $actions['custdiscount'] : '';
        $a_itembrand = isset($actions['itembrand']) ? $actions['itembrand'] : '';
        $a_salesfoc = isset($actions['salesfoc']) ? $actions['salesfoc'] : '';
    ?>
    <div class="panel panel-red">
        <div class="panel-heading"><?=lang('UPDATE DATA FROM AX')?></div>
        <div class="panel-body">
            <div class="form-inline">
                <div class="row text-center">
                    <div class="col-xs-4"><label class="col-xs-8"><span><?=lang('Item stock')?>&nbsp;<span class="text-gray"><?=$a_itemstock?></span></span><br/><span class="text-gray">(<?=lang('Including brand list')?>)</span></label><div class="col-xs-1"><input type="checkbox" id="ckItem" name="<?=$a_itemstock?>" checked /></div><div class="col-xs-3"></div></div>
                    <div class="col-xs-4"><label class="col-xs-8"><span><?=lang('Customers')?>&nbsp;<span class="text-gray"><?=$a_customer?></span></span><br/><span class="text-gray">(<?=lang('Including discount')?>)</span></label><div class="col-xs-1"><input type="checkbox" id="ckCust" name="<?=$a_customer?>" checked /></div><div class="col-xs-3"></div></div>
                    <div class="col-xs-4"><label class="col-xs-8"><span><?=lang('FOC sales')?>&nbsp;<span class="text-gray"><?=$a_salesfoc?></span></span><br/><span class="text-gray">(<?=lang('Running')?>)</span></label><div class="col-xs-1"><input type="checkbox" id="ckFoc" name="<?=$a_salesfoc?>" checked /></div><div class="col-xs-3"></div></div>
                </div>
                <div class="row"><br /></div>
                <div class="row text-center">
                    <div class="col-xs-4"><label class="col-xs-8"><span><?=lang('Sales header')?>&nbsp;<span class="text-gray"><?=$a_salesorder?></span></span><br/><span class="text-gray">(<?=lang('Overview')?>)</span></label><div class="col-xs-1"><input type="checkbox" id="ckHeader" name="<?=$a_salesorder?>" checked /></div><div class="col-xs-3"></div></div>
                    <div class="col-xs-4"><label class="col-xs-8"><span><?=lang('Sales line')?>&nbsp;<span class="text-gray"><?=$a_saledetail?></span></span><br/><span class="text-gray">(<?=lang('Item, Qty, Price')?>)</span></label><div class="col-xs-1"><input type="checkbox" id="ckLine" name="<?=$a_saledetail?>"/></div><div class="col-xs-3"></div></div>
                    <div class="col-xs-4"></div>
                </div>
                <div class="row"><br /></div>
                <div class="panel-footer">
                    <div class="col-xs-9"><div><?='<b>'.lang('API host').'</b>: '.(isset($apisetting['apihost'])?$apisetting['apihost']:'')?></div>
                    <div><b><?=lang('API connection')?>:</b> <a target="_blank" href="<?=(isset($apisetting['apihost'])?$apisetting['apihost']:'')?>?Action=GetStock"><?=lang('Checking')?></a></div>
                    </div>
                    <div class="col-xs-3"><button id="updatelist" class="btn btn-primary" data-toggle="modal" data-target="#pulldata" data-backdrop="static" data-keyboard="false"><?=lang('Pull from AX')?></button></div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="pulldata" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=lang('UPDATE DATA FROM AX')?></h4>
      </div>
      <div class="modal-body" id="pulling"></div>
    </div>
  </div>
</div>
<div id="results"></div>
<script src="<?=base_url('asset/jquery/jquery.min.js')?>"></script>
<script>
var selected = [];
$(document).ready(function(){
    $("#updatelist").click(function(){
        query = "";
        if($("#ckItem").is(":checked") == true)
        {
            selected[selected.length] = "item";
            query = query + "&item=1";
        }
        if($("#ckCust").prop("checked") == true)
        {
            selected[selected.length] = "cust";
            query = query + "&cust=1";
        }
        if($("#ckFoc").prop("checked") == true)
        {
            selected[selected.length] = "foc";
            query = query + "&foc=1";    
        }
        if($("#ckHeader").prop("checked") == true)
        {
            selected[selected.length] = "sheader";
            query = query + "&sheader=1";
        }
        if($("#ckLine").prop("checked") == true)
        {
            selected[selected.length] = "sline";
            query = query + "&sline=1";
        }
        
        isok = confirm('Are you sure to update: '+query+'?');
        index = 0;
        if(isok == true)
        {
            $("#pulling").html('<div><?=lang('Updating.....Please wait a moment')?>.</div><div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>');
			$("#results").html('');
            $.ajax({
                type:"GET",
                url: "<?=site_url('admin/pulldata')?>", 
                data: selected[index] + "=true",
                success: function(result){
                    $("#results").html($("#results").html() + '<div class="row">'+result+'</div>');
                    updateData(1);
                }
            });
        }
    });
});
function updateData(idx){
    if(idx < selected.length && idx > 0)
    {
        $.ajax({
            type:"GET",
            url: "<?=site_url('admin/pulldata')?>", 
            data:selected[idx]+"=true",
            success: function(result){
                $("#results").html($("#results").html() + '<div class="row">'+result+'</div>');
                updateData(idx + 1);
            }
        });
    }
	if(idx >= selected.length)
	{
		$("#pulling").html("<h3><?=lang('Finish')?>!!!</h3>");
		$.ajax({
		   type:"GET",
		   url:"<?=site_url('admin/alertEmail')?>",
		   data:{subject:"Cập nhật dữ liệu", body:$("#results").html()},
		   success:function(){
				alert("<?=strtoupper(lang('Finish'))?>");
		   }
		});
	}
}
</script>