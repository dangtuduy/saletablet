<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>AFF Sales</title>
<style>
.qa{border-bottom:1px solid #ccc; padding: 5px; margin-bottom: 20px; font-size:10.5pt;}
.qa h2{color:#1f74b1; padding: 0; margin: 0;}
.qa p{line-height: 20px;}
.linka{background: #12c30f; padding: 5px 15px; color:#fff; border-radius: 5px;}
.linkb{background: #66708a; padding:5px 15px; color:#fff; border-radius: 5px;}
</style>
</head>
<body>
    <div class="container">
    	<h1 style="border-bottom: 2pt solid #cfcfcf;">NHỮNG CÂU HỎI THƯỜNG GẶP</h1>
    	<div class="qa"><h2 id="hdsdsm">Hướng dẫn sử dụng</h2>
    		<p>Chi tiết thông tin mới nhất, hãy nhấp vào tài liệu <a class="linka" target="_blank" href="<?=base_url('uploads/guide/HDSD_Saleman.pdf')?>"><b>HDSD của salesman</b></a> hoặc <a class="linkb" target="_blank" href="<?=base_url('uploads/guide/HDSD_Saleadmin.pdf')?>"><b>HDSD của sales admin</b></a>.</p>
    	</div>
    	<div class="qa"><h2 id="stock">Tồn kho</h2>
			<p>Tồn kho theo từng Batch number ở mỗi Warehouse được thể hiện trong danh sách SKU. Tại đây, chúng ta sẽ thấy tồn kho dạng A/B, A chính là số lượng tồn kho của item theo từng Batch, và B là số lượng tồn kho tổng cộng của item. Hơn nữa, tồn kho cần phải hiểu là số lượng hàng còn trong kho có thể bán. Tuy nhiên, chúng ta cũng cần lưu ý là, <b>số lượng thực sự có thể bán = số lượng tồn - số lượng đang đặt</b>.</p>
		</div>
		<div class="qa"><h2 id="sale-price">Giá bán (Sales price)</h2>
			<p>Trên Tablet, chúng ta sẽ thấy 2 giá trị giá bán khác nhau. Trong danh sách SKU, giá bán thể hiện là giá bán lẻ. Tuy nhiên, ứng với mỗi khách hàng, chúng ta có thể có một mức giá khác nhau, và được gọi tắt là <i>giá bán theo khách hàng</i>. Giá này sẽ được thấy khi thực hiện bán hàng, nghĩa là xác định cụ thể khách hàng nào và mua SKU nào. Và cũng cần phải lưu ý là, <b>giá bán này là giá bán trước thuế, trước chiết khấu, trước khuyến mãi</b>.</p>
		</div>
		<div class="qa"><h2 id="sale-discount">Chiết khấu</h2>
			<p>Chiết khấu là giá trị giảm trên giá bán, có thể là % như 5%, hoặc là giá trị trực tiếp như 1.000đ, hoặc vừa % vừa giá trị. Chiết khấu này có thể là theo hợp đồng với khách hàng, hoặc là chương trình Marketing đưa ra. Nhưng hiện tại, trên phạm vi của Tablet, chiết khấu thể hiện chính là <b>theo hơp động và áp dụng cho tất cả SKU</b>. Như vậy, nếu theo hợp đồng với một khách hàng, đồng thời có chiết khấu cho nhóm Foods, cho nhóm Beverage, thì sẽ không thấy khi thực hiện bán hàng. Và dĩ nhiên, chiết khấu theo chương trình Marketing không được thấy trên Tablet.</p>
			<p>Do đó, chiết khấu trên Tablet chỉ mang tính tham khảo, phần giá trị chính xác sẽ được xử lý ở phần mềm ERP-AX, là hệ thống quản lý và kiểm soát dữ liệu cài đặt và thực hiện bán xử lý quy trình bán hàng.</p>
		</div>
		<div class="qa"><h2 id="structure">Cấu trúc chức năng</h2>
			<p style="text-align:center"><img src="<?=base_url('uploads/guide/SaleTablet_Brainstorm.png')?>"></p>
		</div>
		<div class="qa"><h2 id="importsku">Chọn Item (SKU) từ excel file</h2>
			<p>Xem và download <a class="linka" target="_blank" href="<?=base_url('uploads/guide/sku_selecting.xlsx')?>">mẫu import sku theo danh sách trong file excel</a> thay vì chọn trực tiếp từng mặt hàng. Và chú ý, file excel này phải có phần đuôi là <b>.xlsx</b>, tức là file từ phiên bản Excel 2007. </p>
		</div>
    </div>
	<?php include('menu.php'); ?>
</body>
</html>
<link href="<?=base_url('asset/css/mobile.css')?>" rel="stylesheet" type="text/css"/>