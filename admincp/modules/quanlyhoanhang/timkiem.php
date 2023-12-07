<?php
if(isset($_POST['timKiem'])) {
    $timKiem = $_POST['timKiem'];
    

    // Thực hiện kết nối CSDL và truy vấn tìm kiếm theo tên hoặc mã đơn hàng
    $sql_lietke_dh = "SELECT * FROM tbl_hoanhang, tbl_khackhang 
    WHERE tbl_hoanhang.id_khachhang = tbl_khackhang.id_khachhang 
    AND (tbl_hoanhang.code_cart LIKE '%$timKiem%' OR tbl_khackhang.tenkhachhang LIKE '%$timKiem%')
    ORDER BY tbl_hoanhang.id DESC ";
    $query_lietke_dh = mysqli_query($mysqli, $sql_lietke_dh);
    // ... Tiếp tục xử lý dữ liệu và hiển thị kết quả
} else {
    // Nếu không nhận được dữ liệu từ trường 'timKiem', bạn có thể thực hiện các hành động mặc định hoặc hiển thị thông báo
    echo "Không có dữ liệu tìm kiếm được gửi đi.";
}
?>
 <div class="quanlymenu" style="margin-top: 20px;">
	<div class="col-md-12 table-responsive">
		<h3 class="the_h">Liệt Kê Đơn Đổi Trả</h3>
<h3>Tìm kiếm : <?php echo $timKiem; ?> </h3>

<table class="table table-bordered table-hover" style="margin-top: 20px;">
    <thead>
<tr class="header_lietke">
<th>STT</th>
    <th>Mã đơn hàng</th>
    <th>Tên khách hàng</th>
    <th>Số điện thoại</th>
    <th>Ngày gửi</th>
    <th>Nội dung</th>
    <th>Tình trạng</th>
  	<th>Quản lý</th>
    <th>Thao tác</th>

  </tr>
  <?php
  $i = 0;
  while($row = mysqli_fetch_array($query_lietke_dh)){
  	$i++;
  ?>
  <tr>
  	<td><?php echo $i ?></td>
    <td><?php echo $row['code_cart'] ?></td>
    <td><?php echo $row['tenkhachhang'] ?></td>
    <td><?php echo $row['dienthoai'] ?></td>
    <td><?php echo $row['ngay_gui'] ?></td>
    <td><?php echo $row['noidung'] ?></td>

    
    <td>
    <?php if($row['status_lh'] == 1){
        echo '<a href="modules/quanlyhoanhang/xuly.php?code='.$row['code_cart'].'&status=moi"><button class="btn btn-primary">Đơn hàng mới</button></a>';
    } elseif($row['status_lh'] == 0){
        echo '<a href="modules/quanlyhoanhang/xuly.php?code=' . $row['code_cart'] . '&status=danggiao"><button class="btn btn-warning">Đang giao</button></a>';
    } else {
        echo '<button class="btn btn-secondary">Đã xác nhận</button>';
    }
    ?>
    </td>
   	<td>
   		<a href="index.php? action=quanlydonhang&query=xemdonhang&code=<?php echo $row['code_cart'] ?>">Xem đơn hàng</a> 
   	</td>
     <td>
      <a href="modules/quanlyhoanhang/xuly.php?idcart=<?php  echo $row['code_cart']; ?>"><button  class="btn btn-danger">Xóa</button></a>
     </td>
  </tr>
<?php
} 
?>
   </tr>
  </thead>
</table>

</div>