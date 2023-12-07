<?php
if(isset($_GET['code']) && isset($_GET['action'])){
    $code = $_GET['code'];
    $action = $_GET['action'];
    
    // Thực hiện kết nối CSDL
    // ...

    if ($action == 'huy') {
        // Truy vấn xóa đơn hàng nếu action là 'huy'
        $sql_update = "UPDATE tbl_donhang SET cart_status = 4 WHERE code_cart = '$code'";
        $query_update = mysqli_query($mysqli, $sql_update);

        // Kiểm tra truy vấn đã thành công hay không
        if($query_update){
            // Truy vấn thành công, chuyển hướng người dùng về trang danh sách đơn hàng hoặc trang khác
            header('Location:index.php?quanly=donhang');
        } else {
            // Truy vấn không thành công, xử lý lỗi hoặc hiển thị thông báo lỗi cho người dùng
            echo "Lỗi: " . mysqli_error($mysqli);
        }
    } elseif ($action == 'xuly') {
        // Truy vấn cập nhật trạng thái đơn hàng nếu action là 'xuly'
        $sql_update = "UPDATE tbl_donhang SET cart_status = 3 WHERE code_cart = '$code'";
        $query_update = mysqli_query($mysqli, $sql_update);

        // Kiểm tra truy vấn đã thành công hay không
        if($query_update){
            // Truy vấn thành công, chuyển hướng người dùng về trang danh sách đơn hàng hoặc trang khác
            header('Location:index.php?quanly=donhang');
        } else {
            // Truy vấn không thành công, xử lý lỗi hoặc hiển thị thông báo lỗi cho người dùng
            echo "Lỗi: " . mysqli_error($mysqli);
        }
    }
} else {
    // Nếu không có mã đơn hàng hoặc hành động, xử lý lỗi hoặc chuyển hướng người dùng đến trang khác
    echo "Lỗi: Mã đơn hàng hoặc hành động không hợp lệ.";
    // Hoặc chuyển hướng người dùng đến trang danh sách đơn hàng hoặc trang khác
    // header('Location:index.php?quanly=donhang');
}
?>
