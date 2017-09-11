
    <div class="col-xs-5">
        <div class="panel panel-primary">
            <div class="panel-heading"><h4><?=lang('Change password')?></h4></div>
            <div class="panel-body">
                <div class="text-right error"><b><?=isset($message) ? $message : ''?></b></div>
                <form method="post" class="form-horizontal">
                    <div class="form-group"><label class="col-xs-5"><?=lang('Current password')?></label><div class="col-xs-6"><input type="password" name="curpass" class="form-control" required /></div></div>
                    <div class="form-group"><label class="col-xs-5"><?=lang('New password')?></label><div class="col-xs-6"><input type="password" name="newpass1" class="form-control" required /></div></div>
                    <div class="form-group"><label class="col-xs-5"><?=lang('New password again')?></label><div class="col-xs-6"><input type="password" name="newpass2" class="form-control" required /></div></div>
                    <div class="text-right"><input type="submit" name="Update" class="btn btn-danger" value="<?=lang('Update')?>" /></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xs-6 list-group">
        <div class="list-group-item clearfix"><label class="col-xs-3"><?=lang('Full name')?></label><div class="col-xs-8"><?=(isset($userinfo['fullname'])?$userinfo['fullname']:'')?></div></div>
        <div class="list-group-item clearfix"><label class="col-xs-3"><?=lang('Email')?></label><div class="col-xs-8"><?=(isset($userinfo['email'])?$userinfo['email']:'')?></div></div>
        <div class="list-group-item clearfix"><label class="col-xs-3"><?=lang('Sale code')?></label><div class="col-xs-8"><?=(isset($userinfo['salecode'])?$userinfo['salecode']:'')?></div></div>
		<div class="list-group-item clearfix"><label class="col-xs-3">Thuộc </label><div class="col-xs-8">Kênh <?=(isset($userinfo['chanel'])? $userinfo['chanel'] : '')?>, <?=(isset($userinfo['outlet'])?getWarehouse($userinfo['outlet']):'')?></div></div>
        <div class="list-group-item clearfix"><label class="col-xs-3"><?=lang('Created on')?></label><div class="col-xs-8"><?=(isset($userinfo['createddate'])?date('d-M-Y', strtotime($userinfo['createddate'])):'')?></div></div>
		<div class="list-group-item clearfix"><label class="col-xs-3">Quản lý bởi </label><div class="col-xs-8"><?=(isset($userinfo['managedby'])?$userinfo['managedby']:'')?></div></div>
        <div class="list-group-item clearfix"><label class="col-xs-3"><?=lang('Forget password')?></label><div class="col-xs-8"><a href="#"><?=lang('Reset password')?></a></div></div>
		<div class="list-group-item clearfix"><a href="<?=site_url('user/logout')?>" class="btn btn-success">Đăng xuất</a></div>
    </div>

<div class="clearfix"></div>
<style>
.list-group-item:first-child {border-top-left-radius: 4px;border-top-right-radius: 4px;}
.list-group-item:last-child {margin-bottom: 0;border-bottom-right-radius: 4px;border-bottom-left-radius: 4px;}
.list-group {padding-left: 0;margin-bottom: 20px;}
.list-group-item {position: relative;display: block;padding: 15px 15px;margin-bottom: -1px;background-color: #fff;}
</style>