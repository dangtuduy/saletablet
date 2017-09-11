<div class="row">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
<script src="<?=base_url('asset/js/morris/morris.js')?>"></script>
    <?php 
        $mthList = array('6'=> '6 months', '12'=> '12 months', '24'=> '24 months')    
    ?>
    <div class="col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading"><form method="GET"><b class="panel-title">(<?=(isset($utype) && $utype == USER_SALEMAN)?getSalecode():'All salesman'?>) Revenue on customer</b>
                <?=form_dropdown('customer', $custList, $selectedCust, 'class="input" style="text-align:left"')?>&nbsp;<input type="submit" value="See revenue" class="btn btn-warning" /></form>
            </div>
                <div class="panel-body">
                        <?php
                            $str = '';
                            $linkdetail = '';
                            $amtSort = array();
                            $totalamt = 0;
                            $amt = 0;
                            if(isset($salesamt))
                            {   
                                $cn = 0;
                                foreach($salesamt as $sale)
                                {
                                    $totalamt += $sale['amount'];
                                    $cn++;
                                    if($cn >= 30)
                                        break;
                                    $str = $str."{x: '".$sale['month']."', y : ".round($sale['amount'], 0)."},";
                                    $date = $sale['month'].'-1..'.$sale['month'].'-31';
                                    $linkdetail = $linkdetail.'<a target="_blank" href="'.site_url('sales/axdetail').'?srdate='.$date.'">'.$sale['month'].'</a> | ';
                                }
                            }
                        ?>
                    <div style="text-align: right;"><b>Total revenue:&nbsp;<?=number_format($totalamt)?> | Unit amount: đồng</b></div>
                    <div id="graph"></div>
                    <div class="right">View detail:&nbsp;<?=$linkdetail?></div>
                    <script>
                        var saledata = [<?=$str?>];
                        Morris.Bar({
                          element: 'graph',
                          data: saledata,
                          xkey: 'x',
                          ykeys: ['y'],
                          labels: ['Amount'],
                          xLabelAngle: 60
                        });
                    </script>
                
            </div>
        </div>
    </div>
</div>