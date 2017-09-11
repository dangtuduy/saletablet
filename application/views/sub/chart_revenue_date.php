<?php include('chart_link.php') ?>
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-warning">
            <div class="panel-heading"><b class="panel-title"><i class="fa fa-bar-chart-o"></i> (<?=(isset($utype) && $utype == USER_SALEMAN)?getSalecode():'Tất cả'?>)Doanh số bán trong tháng <?=date('m/Y', strtotime($todate))?></b></div>
                <div class="panel-body">
                        <?php
                            $totalamt = 0;
                            if(isset($salesamt))
                            {
                                $str = "";
                                foreach($salesamt as $sale)
                                {
                                    $totalamt += $sale['amount'];
                                    $str = $str.'{"date": "'.date('d.m', strtotime($sale['orderdate'])).'", "amount": '.round($sale['amount'], 0).'},';
                                }
                            }
                        ?>
                    <div style="text-align: right;"><b>Total amount: <?=number_format($totalamt, 0)?> đồng</b></div>
                    <div id="graph"></div>
                    <script>
                        var day_data = [<?=$str?>];
                        Morris.Bar({
                          element: 'graph',
                          data: day_data,
                          xkey: 'date',
                          ykeys: ['amount'],
                          labels: ['Tổng tiền']
                        });
                    </script>
                
            </div>
        </div>
    </div>
</div>