<h4 class="title"><b><?=lang('home_customer')?></b></h4>
<div>
        <?php
            $siteurl = site_url('backoffice/customerlist');                            
            $urlsearch = empty($keyword['srcust'])? '' : 'srcust='.$keyword['srcust'].'&';
            $urlsearch = empty($keyword['srname']) ? $urlsearch : ($urlsearch.'srname='.$keyword['srname'].'&');
            $urlsearch = empty($keyword['srstreet']) ? $urlsearch : ($urlsearch.'srstreet='.$keyword['srstreet'].'&');
            $urlsearch = empty($keyword['srsaleman']) ? $urlsearch : ($urlsearch.'srsaleman='.$keyword['srsaleman'].'&');
            $urlsearch = empty($keyword['srclass']) ? $urlsearch : ($urlsearch.'srclass='.$keyword['srclass'].'&');
            $urlpage = 'page='.$curpage;
            $urlfull = empty($urlsearch) ? $urlpage : $urlsearch.$urlpage;
        ?>
    <div class="row">
            <table class="table table-bordered no-bottom table-hover table-striped">
            <thead><tr><th><?=lang('No.')?></th><th style="width: 10%;"><?=lang('Customer code')?></th><th style="width: 20%;"><?=lang('Name')?></th><th style="width: 30%;"><?=lang('Street')?></th><th style="width: 10%;"><?=lang('Classfication')?></th><th style="width: 13%;">Salesman</th><th></th></tr></thead>
            <thead><form method="get" action="<?=$siteurl?>"><tr><th></th><th><input type="text" name="srcust" value="<?=isset($keyword['srcust'])?$keyword['srcust']:''?>" /></th><th><input type="text" name="srname" value="<?=isset($keyword['srname'])?$keyword['srname']:''?>" /></th><th><input type="text" name="srstreet" value="<?=isset($keyword['srstreet'])?$keyword['srstreet']:''?>" /></th><th><input type="text" name="srclass" value="<?=isset($keyword['srclass'])?$keyword['srclass']:''?>" /></th><th><input type="text" name="srsaleman" value="<?=isset($keyword['srsaleman'])?$keyword['srsaleman']:''?>" /></th><th><input type="submit" name="sr" class="btn btn-warning btntable" value="<?=lang('Search')?>" />&nbsp;&nbsp;<a href="<?=$siteurl?>" title="<?=lang('Search all')?>" class="btn btn-danger btntable"><span class="fa fa-remove"></span></a></a></th></tr></form></thead>
            <tbody>
            
        <?php
            $utype = getUserType();
			$cn = 0; 
            foreach($allCust as $item)
            {
                //get salesman
                $smancode = '';
                foreach($custloving as $loving)
                {
                    if($loving['custaccount'] == $item['custid'])
                    {
                        $smancode = $loving['salemans'];
                        break;
                    }
                }
                //
                $cn++;
                echo '<tr><form method="POST" action="'.$siteurl.'?'.$urlfull.'"><td class="text-center">'.$cn.'</td><td>'.$item['custid'].'</td><td>'.$item['name'].'</td><td>'.$item['street'].'</td><td class="text-center" title="Updated on '.$item['created_datetime'].'">'.$item['custclass'].'</td><td class="text-center" style="font-size:12px">'.str_replace(';', '; ', $smancode);
                echo '</td><td class="center"><a href="'.(site_url('sales/focitemlist').'?srcust='.$item['custid'].'&srclass='.$item['custclass']).'" target="_blank" class="btn btn-default btntable"><span class="glyphicon glyphicon-th"></span>Foc</a>&nbsp;<a target="_blank" class="btn btn-success btntable" href="'.site_url('sales/reports/revenue-one-customer').'?customer='.$item['custid'].'"><span class="fa fa-bar-chart"></span></a>';
                echo '&nbsp;<button onclick="return false" data-toggle="modal" data-target="#modalsaleman" value="'.$item['custid'].';'.$smancode.'" class="btn btn-primary btntable updsaleman"><span class="fa fa-pencil"></span></button>';
                echo '</td></form></tr>';
            }
            echo '</tbody></table></div>';
            echo '<div style="text-align:center">'.makePaging($siteurl.'?'.$urlsearch, $curpage, ($cn == PAGE_ITEM ? true : false)).'</div>';
        ?>
    </div> 
</div>
<div id="modalsaleman" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 450px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title"><b><?=lang('CHANGE SALESMAN ON CUSTOMER')?> <span id="customer" class="text-primary"></span></b></div>
            </div>
            <form class="form-inline" method="post" action="<?=$siteurl.'?'.$urlfull?>">
			
            <div class="clearfix modal-body" id="updateSaleman">
                <div class="clearfix form-group"><label class="col-xs-4">Salesman 1</label> <div class="col-xs-8"><?php echo form_dropdown('sm1', $allSaleman, '', ' id="sm1" ')?></div></div>
                <div class="clearfix form-group"><label class="col-xs-4">Salesman 2</label> <div class="col-xs-8"><?php echo form_dropdown('sm2', $allSaleman, '', ' id="sm2" ')?></div></div>
                <div class="clearfix form-group"><label class="col-xs-4">Salesman 3</label> <div class="col-xs-8"><?php echo form_dropdown('sm3', $allSaleman, '', ' id="sm3" ')?></div></div>
                <div class="clearfix form-group"><label class="col-xs-4">Salesman 4</label> <div class="col-xs-8"><?php echo form_dropdown('sm4', $allSaleman, '', ' id="sm4" ')?></div></div>
                <div class="clearfix form-group"><label class="col-xs-4">Salesman 5</label> <div class="col-xs-8"><?php echo form_dropdown('sm5', $allSaleman, '', ' id="sm5" ')?></div></div>
                <input type="hidden" name="custaccount" id="custaccount" value="" />
            </div>
            <div class="clearfix modal-footer">
                <?php if($utype != USER_SALEMAN) echo '<input type="submit" name="Update" class="btn btn-primary" value="'.lang('Update').'"/>';?>&nbsp;&nbsp;<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('Cancel')?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $(".updsaleman").click(function(){
        var value = $(this).val();
        var arrValue = value.split(';');
        $("#customer").html(arrValue[0]);
        $("#custaccount").val(arrValue[0]);
        $("#sm1").val(arrValue[1]);
        $("#sm2").val(arrValue[2]);
        $("#sm3").val(arrValue[3]);
        $("#sm4").val(arrValue[4]);
        $("#sm5").val(arrValue[5]);
        return true;
    });
});
</script>
