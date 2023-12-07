<?php
include('../../config/config.php');

if(isset($_GET['code']) && isset($_GET['status'])){
    $code_cart = $_GET['code'];
    $status = $_GET['status'];

    // Chuẩn bị câu lệnh SQL với prepared statement
    if($status == 'moi'){
        $new_status = 0; // Trạng thái "Mới"
    } elseif($status == 'danggiao'){
        $new_status = 2; // Trạng thái "Đang giao"
    }

    $sql_update = "UPDATE tbl_hoanhang SET status_lh = ? WHERE code_cart = ?";
    
    // Tạo prepared statement
    $stmt = mysqli_prepare($mysqli, $sql_update);
    
    // Bind tham số
    mysqli_stmt_bind_param($stmt, "ss", $new_status, $code_cart);
    
    // Thực hiện câu lệnh
    if(mysqli_stmt_execute($stmt)){
        // Thành công, chuyển hướng về trang ban đầu
        header('Location: ../../index.php?action=quanlyhoanhang&query=lietke');
    } else {
        // Lỗi
        echo "Lỗi: " . mysqli_error($mysqli);
    }

    // Đóng statement
    mysqli_stmt_close($stmt);
}	 else {
    $id = $_GET['idcart'];

    // Xóa dữ liệu từ tbl_cart và tbl_cart_details sử dụng DELETE JOIN
    $sql_xoa = "DELETE FROM tbl_hoanhang WHERE code_cart = '".$id."'";


    mysqli_query($mysqli, $sql_xoa);

    header('Location: ../../index.php?action=quanlyhoanhang&query=lietke');
}
?>
