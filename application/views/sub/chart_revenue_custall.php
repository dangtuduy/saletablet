<?php include('chart_link.php') ?>
<div class="row">
    <?php 
        $transdate = date('Y-m-d');
        $cn = 1;
        $mth = date('Y-m');
        $mthList = array(''=>'All', $mth=>$mth);
        while($cn <= 12)
        {
            $transdate = strtotime('-1 month', strtotime($transdate));
            $mth = date('Y-m', $transdate);
            $mthList[$mth] = $mth;
            $transdate = date('Y-m-d', $transdate);
            $cn++;
        }
    ?>
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"><form method="GET"><b class="panel-title"><i class="fa fa-bar-chart-o"></i> Doanh thu khách hàng 80/20 (<?=(isset($utype) && $utype == USER_SALEMAN)?getSalecode():'All salesman'?>)</b>
                <?=form_dropdown('month', $mthList, $month, 'class="input"')?>&nbsp;<input type="submit" value="Xem" class="btn btn-primary" /></form>
            </div>
                <div class="panel-body">
                        <?php
                            $str = "";
                            $amtSort = array();
                            $totalamt = 0;
                            $amt = 0;
                            
                            if(isset($salesamt))
                            {
                                foreach($salesamt as $key=>$value)
                                {
                                    $amtSort[$key] = round($value['amount'], 0);
                                    $totalamt += round($value['amount'], 0);
                                }
                                array_multisort($amtSort, SORT_DESC, $salesamt);
                                $cn = 0;
                                foreach($salesamt as $sale)
                                {
                                    $amt += $sale['amount'];
                                    $cn++;
                                    if($cn > 20)
                                        break;
                                    $str = $str."{x: '".(empty($sale['custaccount'])?'None':$sale['custaccount'])."', y : ".round($sale['amount'], 0).", z: ".$sale['qty']."},";
                                }
                            }
                        ?>
                    <div style="text-align: right;"><b>Tổng giá trị:&nbsp;<?=number_format($totalamt)?> | ĐVT: đồng</b></div>
                    <div id="graph"></div>
                    <script>
                        var saledata = [<?=$str?>];
                        Morris.Bar({
                          element: 'graph',
                          data: saledata,
                          xkey: 'x',
                          ykeys: ['y', 'z'],
                          labels: ['Tổng tiền', 'Số lượng'],
                          xLabelAngle: 60
                        });
                    </script>
                
            </div>
        </div>
    </div>
</div>