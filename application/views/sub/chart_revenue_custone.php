<?php include('chart_link.php') ?>
<div class="row">
    <?php 
        $mthList = array('6'=> '6 months', '12'=> '12 months', '24'=> '24 months')    
    ?>
    <div class="col-xs-12">
        <div class="panel panel-success">
            <div class="panel-heading"><form method="GET"><b class="panel-title">(<?=(isset($utype) && $utype == USER_SALEMAN)?getSalecode():'Tất cả'?>) Doanh thu theo từng khách hàng</b>
                <?=form_dropdown('customer', $custList, $selectedCust, 'class="input" style="text-align:left"')?>&nbsp;<input type="submit" value="Xem" class="btn btn-primary" /></form>
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
                                    $params = 'srodate='.$sale['month'].'-01..'.$sale['month'].'-31&srcust='.$selectedCust;
                                    $linkdetail = $linkdetail.'<a target="_blank" href="'.site_url('sales/axdetail').'?'.$params.'">'.$sale['month'].'</a> | ';
                                }
                            }
                        ?>
                    <div style="text-align: right;"><b>Tổng doanh thu:&nbsp;<?=number_format($totalamt)?> | ĐVT: đồng</b></div>
                    <div id="graph"></div>
                    <div class="right">Xem chi tiết:&nbsp;<?=$linkdetail?></div>
                    <script>
                        var saledata = [<?=$str?>];
                        Morris.Bar({
                          element: 'graph',
                          data: saledata,
                          xkey: 'x',
                          ykeys: ['y'],
                          labels: ['Doanh thu'],
                          xLabelAngle: 60
                        });
                    </script>
                
            </div>
        </div>
    </div>
</div>