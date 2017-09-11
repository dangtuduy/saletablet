<div>
<?php
	$custid = isset($custid) ? $custid:'';
    $siteurl = site_url('salesmt/counting/1/'.VSencode($custid));

	if(isset($allItems))
	{        
        $tojson = json_encode($allItems);
	}
?>	<form method="post">
	<div class="col-xs-9"><h4><b>TẤT CẢ SKU TẠI #<?=(isset($customer['custid']) ? $customer['custid'] : '').', '.(isset($customer['name'])? rawurldecode($customer['name']) : '')?></b></h4></div>
	<div class="col-xs-2"><input type="submit" name='PUTORDER' class="btn btn-danger bolder" value="KIỂM KÊ XONG"/></div>
	<div class="clearfix"><input type='hidden' name='onItemList' value='<?=$tojson?>'/></div>
    <table class="table table-bordered no-bottom table-hover table-striped text-center">
    <thead><tr><th>STT</th><th>Sản phẩm</th><th>Barcode</th><th style="width:10%">Nhãn</th><th>Đang có</th><th>Tối thiểu</th><th>Đơn vị</th><th style="width: 20%">Kiểm kê</th></tr></thead>
    <thead><tr><th></th><th><input type="text" id="sritem" onkeyup="lookupItem(this)" /></th><th><input type="text" id="srbarcode" onkeyup="lookupBarcode(this)" /></th><th><?=form_dropdown('srbrand', $someBrands, '', 'class="text-left" onchange="lookupBrand(this)"')?></th><th></th><th></th><th></th></tr></thead>
    <tbody id="itemline">
    </tbody>
    </table>
    </form>
<script type="text/javascript">
	var jsonString = <?= $tojson ?>;

	var linenum = jsonString.length;
	
	var bodyitem = document.getElementById('itemline');
	
	var timer = 0;

	function buildRow(row)
	{
		var jsonObject = jsonString[row];
		var checked = (jsonObject['cntqty'] == '0') ? 'checked' : '';

		var tr = '<tr id="'+jsonObject['itemid']+'"><td>' + (row+1) + '</td><td class="text-left">'+ jsonObject['itemid'] + ' #' + jsonObject['itemname'] + '</td><td>'+ jsonObject['itembarcode'] + '</td><td>'+ jsonObject['brandid'] + '</td><td><b>'+ Math.floor(jsonObject['onhand']) + '</b></td><td>'+ jsonObject['minqty'] + '</td><td>'+ jsonObject['unitid'] + '</td><td><input style="width:50px;" class="text-center price" type="number" min="0" name="cnt'+jsonObject['itemid']+'" value="'+jsonObject['cntqty']+'" /><input type="checkbox" '+ checked +' name="cntck'+jsonObject['itemid']+'" />Hết hàng</td></tr>';
		return tr;
	}

	function searchRow(id, style)
	{
		var row = document.getElementById(id);
		row.style = style;
	}

	function buildAll()
	{
		if(timer)
		{
			clearTimeout(timer);
		}
		timer = setTimeout(function(){
			var alltr = '';
			var row = '';
			var qtytotal = 0;
			for (var i = 0; i < linenum; i++) 
			{
				var jsonObject = jsonString[i];
		    	alltr = alltr + buildRow(i);
		    	qtytotal = qtytotal + parseInt(jsonObject['minqty']);
			}
			alltr = alltr + '<tr class="total"><td colspan="4">Tổng</td><td>' + qtytotal + '</td><td></td><td></td><td></td></tr>';	
			
			bodyitem.innerHTML = alltr; 

		}, 300);
	}

	function showAll()
	{
		for (var i = 0; i < linenum; i++) 
		{
			var jsonObject = jsonString[i];
		    searchRow(jsonObject['itemid'], '');
		}
	}
</script>
<script type="text/javascript">
	
	buildAll();

	function lookupItem(obj)
	{
		var lkitem = obj.value;
		lkitem = lkitem.trim();
		if(lkitem.length == 0)
		{
			showAll();
		}
		else if(lkitem.length > 3)
		{
			if(timer)
			{
				clearTimeout(timer);
			}

			timer = setTimeout(function(){
				var alltr = '';
				var qtytotal = 0;
				var row = '';
				lkitem = lkitem.toLowerCase();

				for (i = 0; i < linenum; i++) 
				{
					var jsonObject = jsonString[i];
					itemCode = jsonObject['itemid'].toLowerCase();
					itemName = jsonObject['itemname'].toLowerCase();
					if(itemCode.indexOf(lkitem) > -1 || itemName.indexOf(lkitem) > -1)
					{
			    		searchRow(jsonObject['itemid'], '');
			    		qtytotal = qtytotal + parseInt(jsonObject['minqty']);
			    	}
			    	else
			    		searchRow(jsonObject['itemid'], 'display:none');
				}
				
			}, 500); //delay 1second	
		}
	}

	function lookupBarcode(obj)
	{
		var lkitem = obj.value;
		lkitem = lkitem.trim();
		if(lkitem.length == 0)
		{
			showAll();
		}
		else if(lkitem.length > 3)
		{
			if(timer)
			{
				clearTimeout(timer);
			}
			timer = setTimeout(function(){
				var alltr = '';
				var qtytotal = 0;
				var row = '';
				lkitem = lkitem.toLowerCase();
				for (i = 0; i < linenum; i++) 
				{
					var jsonObject = jsonString[i];
					itemBarcode = jsonObject['itembarcode'].toLowerCase();
					if(itemBarcode.indexOf(lkitem) > -1)
					{
			    		searchRow(jsonObject['itemid'], '');
			    		qtytotal = qtytotal + parseInt(jsonObject['minqty']);
			    	}
			    	else
			    		searchRow(jsonObject['itemid'], 'display:none');
				}
			}, 500);	
		}
	}

	function lookupBrand(obj)
	{
		var lkitem = obj.value;
		var alltr = '';
		var qtytotal = 0;
		var row = '';
		lkitem = lkitem.toLowerCase();
		if(lkitem == "" || lkitem == '0')
		{
			showAll();
		}
		else
		{
			for (i = 0; i <= linenum; i++) 
			{
				var jsonObject = jsonString[i];
				brandid = jsonObject['brandid'].toLowerCase();
				if(lkitem == brandid)
				{
					//alltr = alltr + buildRow(i);
					searchRow(jsonObject['itemid'], '');
				    qtytotal = qtytotal + parseInt(jsonObject['minqty']);
			    }
			    else
			    	searchRow(jsonObject['itemid'], 'display:none');
			}
			//alltr = alltr + '<tr class="pagination"><td colspan="5">Tổng</td><td>' + qtytotal + '</td><td></td><td></td></tr>';
			//bodyitem.innerHTML = alltr;
		}
	}
</script>
</div>