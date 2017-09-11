<div class="row"><form method="POST">
    <h3><b><?=lang('Settings')?></b></h3>
    <div align="right">
    <?php 
        if(isset($alert))
            echo '<h4 class="text-primary"><b>'.$alert.'</b></h4>';
        else
            echo '<a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" class="btn btn-default">Hủy thay đổi</a>&nbsp;&nbsp;<input type="submit" name="Save" value="Lưu thiết lập" class="btn btn-danger" />';
    ?>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#api"><?=lang('API connection')?></a></li>
        <li><a data-toggle="tab" href="#email"><?=lang('Email sending')?></a></li>
        <li><a data-toggle="tab" href="#datetime"><?=lang('Data section')?></a></li>
    </ul>

    <div class="tab-content">
        <div>&nbsp;</div>
        <?php $apisetting = isset($settings['apisetting']) ? $settings['apisetting'] : array(); ?>
        <div id="api" class="tab-pane fade in active">
            <div class="panel panel-bg2">
                <div class="panel-body">
                    <div class="form-group row">
                        <div class="col-xs-7"><label class="col-xs-1 col-form-label">Key</label><div class="col-xs-11"><input class="form-control input" type="text" name="apikey" value="<?=isset($apisetting['apikey'])? $apisetting['apikey'] : ''?>" /></div></div>
                        <div class="col-xs-5"><label class="col-xs-3 col-form-label">User ID</label><div class="col-xs-9"><input class="form-control input" type="text" name="userid" value="<?=isset($apisetting['userid'])? $apisetting['userid'] : ''?>" /></div></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-7"><label class="col-xs-1 col-form-label">Host</label><div class="col-xs-11"><input class="form-control input" type="text" name="apihost" value="<?=isset($apisetting['apihost'])? $apisetting['apihost'] : ''?>" /></div></div>
                        <div class="col-xs-5"><label class="col-xs-3 col-form-label">Phân trang</label><div class="col-xs-9"><input class="form-control input" type="text" name="limit" value="<?=isset($apisetting['limit'])? $apisetting['limit'] : ''?>" /></div></div>
                    </div>
                </div>
            </div>
            <h4><b>API actions</b></h4>
            <div class="panel panel-bg1">
                <div class="panel-body"><div class="form-group row">
                    <?php
                        $actions = isset($apisetting['actions']) ? $apisetting['actions'] : array();
                        $i = 0;
                        foreach($actions as $name=>$value)
                        {
                            echo '<div class="col-xs-4"><label class="col-xs-5 col-form-label">'.$name.'</label><div class="col-xs-7"><input class="form-control" type="text" name="'.$name.'" style="width: 98%;" value="'.$value.'" /></div></div>';
                            $i++;
                            if($i%3 == 0)
                                echo '</div><div class="form-group row">';
                        } 
                        
                    ?>
                </div></div>
            </div>
        </div>
        <?php $email = isset($settings['email_from']) ? $settings['email_from'] : array(); ?>
        <div id="email" class="tab-pane fade">
            <div class="panel panel-bg1">
                <div class="panel-body">
                    <div class="form-group row">
                        <label class="col-xs-1">From</label><div class="col-xs-3"><input type="text" class="form-control" name="from" placeholder="<?=lang('Name')?><web@abc.com>" value="<?=isset($email['from'])? $email['from'] : ''?>" /></div>
                        <label class="col-xs-1"><?=lang('Subject')?></label><div class="col-xs-5"><input type="text" class="form-control" name="subject" placeholder="" value="<?=isset($email['subject'])? $email['subject'] : '[subject]'?>" /></div>
						<div class="col-xs-2"><button type="button" id="TestEmail"  data-toggle="modal" data-target="#modalTestEmail"  class="btn btn-warning"><?=lang('Send email')?></button></div>
                    </div>
                </div>
            </div>
			<?php
			$outlet = $this->config->item('outlet');
			$chanel = $this->config->item('chanel');
			$email = $this->config->item('email_send');
			$etype = '';
			$color = array('panel-primary','panel-yellow', 'panel-green', 'panel-info', 'panel-warning','panel-bg1');
			$i = -1;
			foreach($outlet as $okey => $ovalue)
			{
				$i++;
				foreach($chanel as $ckey => $cvalue)
				{
					$etype = $ckey.'_'.$okey;
			?>
            <div class="col-xs-6"><div class="panel <?=$color[$i]?>">
                <div class="panel-heading"><?=$ckey.'_'.$ovalue?></div>
                <div class="panel-body">
                   <div class="form-group row">
                        <label class="col-xs-1">To</label><div class="col-xs-11"><textarea name="<?=$etype?>_to" class="form-control" cols="2"><?=isset($email[$etype]['to'])? $email[$etype]['to'] : ''?></textarea></div>
                    </div><div class="form-group row">
                        <label class="col-xs-1">CC</label><div class="col-xs-11"><input type="text" class="form-control" name="<?=$etype?>_cc" placeholder="a@abc.com, b@example.com" value="<?=isset($email[$etype]['cc'])? $email[$etype]['cc'] : ''?>" /></div>
					</div>
				</div>
			</div></div>
			<?php
				}
			}
			?>
		</div>
        <?php $syncdata = isset($settings['syncdata']) ? $settings['syncdata'] : array(); ?>
        <div id="datetime" class="tab-pane fade">
            <div class="panel panel-danger">
                <div class="panel-heading panel-body">
                    <div class="form-group row">
                        <label class="col-xs-4">Vùng cập nhật dữ liệu kéo từ AX, số ngày lùi từ hôm nay</label><div class="col-xs-7"><input type="number" min="0" name="todayago" class="form-control" value="<?=isset($syncdata['todayago'])? $syncdata['todayago'] : ''?>" /></div></div>
                    <div class="form-group row">
                        <label class="col-xs-4">Tự động cập nhật mỗi ngày, lúc </label><div class="col-xs-2"><input type="number" min="1" max="23" name="hour" autocomplete="off" class="form-control" value="<?=isset($syncdata['onhour'])? $syncdata['onhour'] : 22?>" /></div><label class="col-xs-1">giờ</label><div class="col-xs-2"><input type="number" name="minute" min="0" max="59" autocomplete="off" class="form-control" value="<?=isset($syncdata['onminute'])? $syncdata['onminute'] : 0?>" /></div><label class="col-xs-1">phút</label></div>
                </div>
            </div>
        </div>
    </div>
</form></div>
<div id="modalTestEmail" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-title"><b><?=lang('Check to send email')?></b></div>
          </div>
          <div class="clearfix modal-body" id="sendbody">
            <h3><?=lang('Requesting to server...Please, wait a minute')?>.</h3>
          </div>
          <div class="clearfix modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('Close')?></button>
          </div>
        </div>
    
      </div>
    </div>
<script>
$(document).ready(function(){
    $("#TestEmail").click(function(){
        $("#sendbody").html("<h3><?=lang('Sending email...Please, wait a minute')?>.</h3>");
        $.ajax({
            url: "<?=site_url('admin/alertEmail')?>", 
            data:{subject:"Testing", body:"Kiểm tra tính năng gửi email"},
            success: function(result){
                $("#sendbody").html(result);
            }
        });
        return true;
    });
});
</script>
