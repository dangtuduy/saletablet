<?php include('chart_link.php') ?>
<div class="row">
		<div class="clearfix panel panel-info">
            <div class="panel-heading">
				<b class="panel-title"><i class="fa fa-bar-chart-o"></i> Doanh số 4 tháng gần nhất</b></div>
            <div class="panel-body">
                        <?php
							$salerpt = array();
							$str = "";
							$link = "";
                            $totalamt = 0;
                            if(isset($salesamt))
							{
                                $month = isset($salesamt[0]['month']) ? $salesamt[0]['month'] : '-1';
                                $one = array('o4'=>0, 'o3'=>0, 'o2'=>0, 'o1'=>0);
								
                                foreach($salesamt as $sale)
                                {
                                    $totalamt += $sale['amount'];
                                    if($month != $sale['month'])
                                    {
                                        $salerpt[] = array('month'=>$month, 'o4'=>$one['o4'], 'o3'=>$one['o3'], 'o2'=>$one['o2'], 'o1'=>$one['o1']);
                                        $one = array('o4'=>0, 'o3'=>0, 'o2'=>0, 'o1'=>0);
                                        $month = $sale['month'];
                                        $one['o'.$sale['linestatus']] = round($sale['amount'], 0);        
                                    }
                                    else
                                    {
                                        $one['o'.$sale['linestatus']] = round($sale['amount'], 0);
                                    }
                                }
								//last month
								$salerpt[] = array('month'=>$month, 'o4'=>$one['o4'], 'o3'=>$one['o3'], 'o2'=>$one['o2'], 'o1'=>$one['o1']);
                                $one = array('o4'=>0, 'o3'=>0, 'o2'=>0, 'o1'=>0);
                                foreach($salerpt as $sale)
                                {
                                    $str = $str."{x: '".$sale['month']."', y : ".$sale['o1'].", z: ".$sale['o2'].", a: ".$sale['o3'].", b: ".$sale['o4']."},";
									$link = $link.('&nbsp;<a href="'.site_url('sales/axdetail').'?srodate='.($sale['month'].'-01..'.$sale['month'].'-31').'">'.$sale['month'].'</a>&nbsp;|');
								}
                            }
                        ?>
                    <div style="text-align: right;"><b>Tổng giá trị: <?=number_format($totalamt, 0)?>&nbsp;đồng</b></div>
                    <div id="graph"></div>
                    <script>
                        var saledata = [<?=$str?>];
                        Morris.Bar({
                          element: 'graph',
                          data: saledata,
                          xkey: 'x',
                          ykeys: ['y', 'z', 'a', 'b'],
                          labels: ['Open', 'Delivered', 'Invoiced', 'Canceled'],
                          stacked: true
                        });
                    </script>
					<div class="right"><b>Xem chi tiết</b>: <?=$link?></div>
            </div>
        </div>
</div>