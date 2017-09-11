<div class="row">
    <div class="col-xs-6 col-md-4">
            <?php echo (isset($error) && !empty($error)) ?  '<h3 class="error">'.$error.'</h3>' : '';
				$outlet = listOutlet();
				$chanel = $this->config->item('chanel');
                if(isset($email) && !empty($email))//edited
                {
            ?>
                <div class="panel panel-warning"><div class="panel-heading"><h3 class="panel-title"><?=lang('Edit user')?></h3></div>
                <div class="panel-body">
                    <form method="post" action="<?=site_url('admin/useredit?user='.$email)?>">
                        <div align="center" style="color: red;font-weight: bold;"> <?php if(isset($ok)){ echo $error.' '. ($ok == TRUE) ? '>>> '.lang('Success').'!':'>>> '.lang('Failed. Do again');}?></div>
                        <div class="form-group input-group"><b><?=lang('Email')?>:&nbsp;<?=isset($userinfo['email'])?$userinfo['email']:''?></b></div>
                        <div><input value="<?=isset($userinfo['email'])?$userinfo['email']:''?>" type="hidden" name="email"/></div>
                        <div class="form-group input-group"><span class="input-group-addon">N</span><input value="<?=isset($userinfo['fullname'])?$userinfo['fullname']:''?>" type="text" name="fullname" class="form-control" autocomplete="off" placeholder="<?=lang('Fullname')?>" required /></div>
                        <div class="form-group input-group"><span class="input-group-addon">P</span><input type="password" name="pass" class="form-control" placeholder="<?=lang('Password')?>" /></div>
                        <div class="form-group input-group"><span class="input-group-addon">P</span><input type="password" name="repass" class="form-control" placeholder="<?=lang('Password again')?>"/></div>
                        <div class="form-group input-group"><span class="input-group-addon">S</span><input value="<?=isset($userinfo['salecode'])?$userinfo['salecode']:''?>" type="text" name="salecode" class="form-control" placeholder="<?=lang('Salecode')?>" /></div>
                        <div class="form-group input-group"><span class="input-group-addon">G</span><?php echo form_dropdown('group', $usergroup, (isset($userinfo['usergroup'])?$userinfo['usergroup']:''), 'class="form-control"') ?></div>
						<div class="form-group input-group"><span class="input-group-addon">O</span><?php echo form_dropdown('outlet', $outlet, (isset($userinfo['outlet']) ? $userinfo['outlet']:''), 'class="form-control"') ?></div>
						<div class="form-group input-group"><span class="input-group-addon">C</span><?php echo form_dropdown('chanel', $chanel, (isset($userinfo['chanel']) ? $userinfo['chanel']:''), 'class="form-control"') ?></div>
						<div class="form-group input-group"><span class="input-group-addon">M</span><input value="<?=isset($userinfo['managedby']) ? $userinfo['managedby']:''?>" type="text" name="managedby" class="form-control" autocomplete="off" placeholder="Quản lý bởi (email)" /></div>
                        <div class="form-check"><label class="form-check-label"><?=lang('Active')?>&nbsp;<input name="active" <?=(isset($userinfo['active']) && $userinfo['active'] == TRUE)?'checked':''?> class="form-check-input" type="checkbox"/></label></div>
                        <div align="center"><input type="submit" value="<?=lang('Update')?>" name="UpdateUser" class="btn btn-warning" />&nbsp;<a href="<?=site_url('admin/user')?>"><b><?=lang('Go to create user')?></b></a></div>
                    </form>
                </div>
            <?php    
                }
                else
                {
            ?>
                <div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title"><?=lang('New user')?> </h3></div>
                <div class="panel-body">
                    <form method="post" action="<?=site_url('admin/user')?>">
                        <div align="center" style="color: red;font-weight: bold;"><?php if(isset($ok)){ echo $error.' '. ($ok == TRUE) ? '>>> '.lang('Success').'!':'>>> '.lang('Failed. Do again');}?></div>
                        <div class="form-group input-group"><span class="input-group-addon">@</span><input value="<?=isset($userinfo['email'])?$userinfo['email']:''?>" type="text" name="email" class="form-control" placeholder="Email" autocomplete="off" required /></div>
                        <div class="form-group input-group"><span class="input-group-addon">T</span><input value="<?=isset($userinfo['fullname'])?$userinfo['fullname']:''?>" type="text" name="fullname" class="form-control" autocomplete="off" placeholder="<?=lang('Full name')?>" required /></div>
                        <div class="form-group input-group"><span class="input-group-addon">P</span><input type="password" name="pass" class="form-control" placeholder="<?=lang('Password')?>" required value="annam123" /></div>
                        <div class="form-group input-group"><span class="input-group-addon">P</span><input type="password" name="repass" class="form-control" placeholder="<?=lang('Password again')?>" required value="annam123" /></div>
                        <div class="form-group input-group"><span class="input-group-addon">C</span><input value="<?=isset($userinfo['salecode'])?$userinfo['salecode']:'S-HNFD-'?>" type="text" name="salecode" class="form-control" placeholder="<?=lang('Sale code')?>" /></div>
                        <div class="form-group input-group"><span class="input-group-addon">G</span><?php echo form_dropdown('group', $usergroup, (isset($userinfo['usergroup'])?$userinfo['usergroup']:''), 'class="form-control"') ?></div>
						<div class="form-group input-group"><span class="input-group-addon">O</span><?php echo form_dropdown('outlet', $outlet, (isset($userinfo['outlet'])?$userinfo['outlet']:''), 'class="form-control"') ?></div>
						<div class="form-group input-group"><span class="input-group-addon">C</span><?php echo form_dropdown('chanel', $chanel, (isset($userinfo['chanel']) ? $userinfo['chanel']:''), 'class="form-control"') ?></div>
						<div class="form-group input-group"><span class="input-group-addon">M</span><input value="<?=isset($userinfo['managedby']) ? $userinfo['managedby']:''?>" type="text" name="managedby" class="form-control" autocomplete="off" placeholder="Quản lý bởi (email)" /></div>
                        <div align="center"><input type="submit" value="<?=lang('Create')?>" name="AddUser" class="btn btn-primary" />&nbsp;&nbsp;&nbsp;<a href="<?=site_url('admin/user')?>"><?=lang('Clear input')?></a></div>
                    </form>
                </div>
            <?php } ?>
    </div></div>
    <div class="col-xs-12 col-md-8">
        <div class="table-responsive">
        <table class="table table-bordered table-hover">
        <thead><tr><th><?=lang('Email user')?></th><th><?=lang('Group')?></th><th><?=lang('Sale code')?></th><th><?=lang('Active')?></th><th>Outlet-Chanel</th><th>Managed</th><th></th></tr></thead>
        <thead><tr><th></th><th></th><th></th><th></th><th><?=form_dropdown('sroutlet', $outlet, $sroutlet, 'class="" onchange="changing(this)"')?></th><th></th></tr></thead>
        <tbody>
        
    <?php                                    
        foreach($alluser as $user)
        {
			$active = (isset($user['active']) && $user['active'] == TRUE) ? TRUE : FALSE;
			$mblist = explode(',', $user['managedby']);
			$managedby = '';
			$email = '';
			
			foreach($mblist as $mb)
			{
				$email = explode('@', $mb);
				$managedby = (empty($managedby) ? $managedby : $managedby . ', ') . $email[0];
			}
            echo '<tr class="text-center" '.($active ? '' : 'style="background:#999;"').'><td class="text-left"><i style="color:#1545d2">'.$user['email'].'</i><br/>'.$user['fullname'].'</td><td>'.(isset($usergroup[$user['usergroup']]) ? $usergroup[$user['usergroup']] : '').'</td><td>'.$user['salecode'].'</td><td>'.($active ? lang('Yes') : lang('No')).'</td><td>'.$user['outlet'].'-'.$user['chanel'].'</td>';
            echo '<td>'.$managedby.'</td><td><a class="btn btn-warning btntable" href="'.site_url('admin/useredit').'?user='.rawurlencode($user['email']).'"><span class="glyphicon glyphicon-pencil"></span></a></td></tr>';
        }
    ?>
        </tbody></table></div>
    </div>
</div>
<script type="text/javascript">
function changing(obj){
	window.location = "<?=site_url('admin/user')?>?outlet="+obj.value;
}
</script>