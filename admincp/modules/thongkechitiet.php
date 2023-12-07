

<?php
// Kiểm tra xem dữ liệu đã được gửi từ form hay không
if(isset($_POST['subdays']) && isset($_POST['now'])){
    // Chuyển đổi giá trị ngày sang định dạng Y-m-d
    $subdays = date("Y-m-d", strtotime($_POST['subdays']));
    $now = date("Y-m-d", strtotime($_POST['now']));
} else {
    // Nếu không có dữ liệu được gửi từ form, đặt giá trị mặc định là ngày hôm nay
    $subdays = date("Y-m-d");
    $now = date("Y-m-d");
}

// Hiển thị ngày theo định dạng mong muốn
$subdaysHienThi = date("d - m - Y", strtotime($subdays));
$nowHienThi = date("d - m - Y", strtotime($now));
?>

<?php

// Ngày bắt đầu và ngày kết thúc (được nhận từ người dùng hoặc từ các biến khác)


// Truy vấn SQL với điều kiện ngày từ ngày bắt đầu đến ngày kết thúc và nhóm theo tên sản phẩm
$sql_lietke_sp = "SELECT tbl_sanpham.*, tbl_danhmuc.*, SUM(tbl_cart_details.soluongmua) AS total_quantity
                  FROM tbl_sanpham
                  LEFT JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
                  LEFT JOIN tbl_cart_details ON tbl_sanpham.id_sanpham = tbl_cart_details.id_sanpham
                  WHERE DATE(tbl_cart_details.ngaymua) BETWEEN '$subdays' AND '$now' 
                  GROUP BY tbl_sanpham.tensanpham
                  ORDER BY tbl_sanpham.id_sanpham DESC";

$query_lietke_sp = mysqli_query($mysqli, $sql_lietke_sp);
?>

<div class="quanlymenu">
                           
   <div class="row" style="margin-top: 20px;">
	<div class="col-md-12 table-responsive">
        
        <h3>Liệt kê sản phẩm đã bán từ ngày <?php echo date("d - m - Y", strtotime($subdays)); ?> đến ngày <?php echo date("d - m - Y", strtotime($now)); ?></h3>
      
        <form class="form-inline mt-3" action="" method="POST">
    <div class="form-group mr-2">
        <label for="subdays" class="mr-2">Ngày bắt đầu:</label>
        <input type="date" class="form-control" name="subdays" required
               value="<?php echo date("Y-m-d", strtotime($subdays)); ?>">
    </div>

    <div class="form-group mr-2">
        <label for="now" class="mr-2">Ngày kết thúc:</label>
        <input type="date" class="form-control" name="now" required
               value="<?php echo date("Y-m-d", strtotime($now)); ?>">
    </div>

    <button type="submit" class="btn btn-success">Tìm kiếm</button>
</form>





        <?php
// Tổng số lượng, doanh thu và lợi nhuận
$totalQuantity = 0;
$totalRevenue = 0;
$totalProfit = 0;

// Duyệt qua kết quả truy vấn và tính tổng số lượng, doanh thu và lợi nhuận
while ($row = mysqli_fetch_array($query_lietke_sp)) {
    // Xử lý dữ liệu của từng sản phẩm ở đây (nếu cần)

    // Tính toán số lượng, doanh thu và lợi nhuận cho sản phẩm hiện tại
    $soluongcon = $row['soluong'] - $row['total_quantity'];
    $giakhuyenmai = $row['km'];
    $giaban = $row['giasp'] - ($row['giasp'] * $giakhuyenmai/100);
    $doanhthu = $row['total_quantity'] * $giaban;
    $lai = $doanhthu - ($row['total_quantity'] * $row['giagockm']);

    // Cập nhật tổng số lượng, doanh thu và lợi nhuận
    $totalQuantity += $row['total_quantity'];
    $totalRevenue += $doanhthu;
    $totalProfit += $lai;
}

// Hiển thị tổng số lượng, doanh thu và lợi nhuận
?>
<?php if (isset($subdays) && isset($now)) { ?>
    <form action="modules/xuat_excel.php" method="post">
        <input type="hidden" name="subdays" value="<?php echo $subdays; ?>">
        <input type="hidden" name="now" value="<?php echo $now; ?>">
        <button type="submit" class="btn btn-success">Xuất Excel</button>
    </form>
<?php } ?>
<div class="container mt-5">
    <div class="alert alert-info">
        <strong>Từ ngày <?php echo date("d - m - Y", strtotime($subdays)); ?> đến ngày <?php echo date("d - m - Y", strtotime($now)); ?></strong><br>
        
        <strong>Tổng số lượng đã bán: <?php echo($totalQuantity); ?> </strong><br>
<strong>Tổng doanh thu: <?php echo number_format($totalRevenue, 0, '.', ','); ?> VNĐ</strong><br>
<strong>Tổng lợi nhuận: <?php echo number_format($totalProfit, 0, '.', ','); ?> VNĐ</strong><br>

    </div>
</div>


    <table class="table table-bordered table-hover" style="margin-top: 20px;">
    <thead>
    <tr style="text-align: center;">
        <th>STT</th>
        <th>Thời gian</th>
        <th>Tên</th>
        <th>Hình ảnh</th>
        <th>Số lượng đã bán</th>
        <th>Danh mục</th>
        <th>Mã sản phẩm</th>
    </tr>
    </thead>
    <?php
    // Hiển thị dữ liệu của từng sản phẩm
    $i = 0;
    $query_lietke_sp->data_seek(0); // Di chuyển con trỏ dữ liệu về đầu để bắt đầu lại vòng lặp

    while ($row = mysqli_fetch_array($query_lietke_sp)) {
        // ... (các xử lý dữ liệu của từng sản phẩm và hiển thị ở đây)
        $i++;
        ?>
        <tr style="text-align: center;">
            <td><?php echo $i; ?></td>
            <td><?php echo date("d - m - Y", strtotime($subdays)); ?> đến ngày <?php echo date("d - m - Y", strtotime($now)); ?></td>
            <td><?php echo $row['tensanpham']; ?></td>
            <td><img style="width:100px;max-height:100px" src="modules/quanlysp/uploads/<?php echo $row['hinhanh']; ?>"></td>
            <td><?php if ($row['total_quantity'] == 0) { echo "0"; } else { echo $row['total_quantity']; } ?></td>
            <td><?php echo $row['tendanhmuc']; ?></td>
            <td><?php echo $row['masp']; ?></td>
        </tr>
    <?php } ?>
</table>
