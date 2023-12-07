<?php
session_start();
include('../../admincp/config/config.php');

// Kiểm tra nếu có đăng nhập thì sử dụng id_khachhang, ngược lại sử dụng id_khachhang_temp
if (isset($_SESSION['id_khachhang'])) {
    $id_khachhang = $_SESSION['id_khachhang'];
} else {
    // Tạo id_khachhang_temp ngẫu nhiên để sử dụng cho trường hợp không đăng nhập
    $id_khachhang_temp = rand(100000, 999999);
    $_SESSION['id_khachhang'] = $id_khachhang_temp;
    $id_khachhang = $id_khachhang_temp;
}

$code_order = rand(0, 9999);

date_default_timezone_set('Asia/Ho_Chi_Minh');

// Lấy thời gian hiện tại của Việt Nam
$update_time = date('Y-m-d H:i:s');

if (isset($_POST['payment_method']) && is_array($_POST['payment_method']) && count($_POST['payment_method']) > 0) {
    $payment_method = implode(", ", $_POST['payment_method']);
} else {
    // Xử lý khi không chọn hình thức thanh toán
    echo "Vui lòng chọn hình thức thanh toán.";
    exit();
}

// Lấy thông tin từ form
$hoTen = mysqli_real_escape_string($mysqli, $_POST['hoTen']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$soDienThoai = mysqli_real_escape_string($mysqli, $_POST['soDienThoai']);
$diaChi = mysqli_real_escape_string($mysqli, $_POST['diaChi']);
$ghichu = mysqli_real_escape_string($mysqli, $_POST['fnote']);
// Thêm chi tiết đơn hàng vào bảng tbl_cart_details
if (isset($_SESSION['id_khachhang'])) {
    $id_khachhang = $_SESSION['id_khachhang'];

    // Kiểm tra xem có giỏ hàng trong tbl_giohang không
    $sql_check_giohang = "SELECT * FROM tbl_giohang WHERE id_khachhang = '$id_khachhang'";
    $result_check_giohang = mysqli_query($mysqli, $sql_check_giohang);

    if ($result_check_giohang) {
        // Nếu có giỏ hàng, sử dụng dữ liệu từ tbl_giohang
        while ($row = mysqli_fetch_assoc($result_check_giohang)) {
            $id_sanpham = $row['id_sanpham'];
            $soluong = $row['soluong'];
            $size = $row['size'];

            // Thực hiện kiểm tra số lượng còn lại tương tự như trước
            $check_soluong_query = "SELECT (soluongsize - soluongdaban) AS soluongconlai 
                                    FROM size_soluong 
                                    WHERE id_sanpham = '$id_sanpham' AND id_size = '$size'";
            $check_soluong_result = mysqli_query($mysqli, $check_soluong_query);

            if ($check_soluong_result) {
                $row = mysqli_fetch_assoc($check_soluong_result);
                $soluongconlai = $row['soluongconlai'];

                if ($soluongconlai >= $soluong) {
                    // Thêm chi tiết đơn hàng vào bảng tbl_cart_details
                    $insert_order_details = "INSERT INTO tbl_cart_details (id_sanpham, code_cart, soluongmua, size, ngaymua) 
                                            VALUES ('$id_sanpham', '$code_order', '$soluong', '$size', '$update_time')";
                    mysqli_query($mysqli, $insert_order_details);

                    // Cập nhật số lượng đã bán trong bảng size_soluong
                    $update_soluongdaban_query = "UPDATE size_soluong 
                                                SET soluongdaban = soluongdaban + $soluong 
                                                WHERE id_sanpham = '$id_sanpham' AND id_size = '$size'";
                    mysqli_query($mysqli, $update_soluongdaban_query);
                } else {
                    // Không đủ số lượng
                    echo "<script>alert('Không đủ số lượng cho sản phẩm.');</script>";
                    echo "<script>window.location.href='../../index.php?quanly=giohang';</script>";
                    exit();
                }
            } else {
                // Xử lý khi truy vấn không thành công
                echo "Đã xảy ra lỗi khi kiểm tra số lượng còn lại. Vui lòng thử lại sau.";
                exit();
            }
        }
    }
    // Nếu không có id_khachhang, sử dụng dữ liệu từ $_SESSION['cart']
    foreach ($_SESSION['cart'] as $key => $value) {
        $id_sanpham = $value['id'];
        $soluong = $value['soluong'];
        $size = $value['size'];

        // Kiểm tra số lượng còn lại
        $check_soluong_query = "SELECT (soluongsize - soluongdaban) AS soluongconlai 
                                FROM size_soluong 
                                WHERE id_sanpham = '$id_sanpham' AND id_size = '$size'";
        $check_soluong_result = mysqli_query($mysqli, $check_soluong_query);

        if ($check_soluong_result) {
            $row = mysqli_fetch_assoc($check_soluong_result);
            $soluongconlai = $row['soluongconlai'];

            if ($soluongconlai >= $soluong) {
                // Thêm chi tiết đơn hàng vào bảng tbl_cart_details
                $insert_order_details = "INSERT INTO tbl_cart_details (id_sanpham, code_cart, soluongmua, size, ngaymua) 
                                        VALUES ('$id_sanpham', '$code_order', '$soluong', '$size', '$update_time')";
                $result_insert_order = mysqli_query($mysqli, $insert_order_details);

                if (!$result_insert_order) {
                    // Handle the case where insertion failed
                    echo "Đã xảy ra lỗi khi thêm chi tiết đơn hàng. Vui lòng thử lại sau.";
                }

                // Cập nhật số lượng đã bán trong bảng size_soluong
                $update_soluongdaban_query = "UPDATE size_soluong 
                                            SET soluongdaban = soluongdaban + $soluong 
                                            WHERE id_sanpham = '$id_sanpham' AND id_size = '$size'";
                mysqli_query($mysqli, $update_soluongdaban_query);
            } else {
                // Không đủ số lượng
                echo "<script>alert('Không đủ số lượng cho sản phẩm.');</script>";
                echo "<script>window.location.href='../../index.php?quanly=giohang';</script>";
            }
        } else {
            // Xử lý khi truy vấn không thành công
            echo "Đã xảy ra lỗi khi kiểm tra số lượng còn lại. Vui lòng thử lại sau.";

        }
   unset($_SESSION['id_khachhang']);
    }

    // Thêm thông tin đơn hàng vào bảng tbl_donhang
    $insert_order = "INSERT INTO tbl_donhang ( id_khachhang_temp, code_cart, email, tenkhachhang, dienthoai, diachi, payment_method,ghichu, ngaymua,cart_status)
                    VALUES ('" . $id_khachhang . "','" . $code_order . "','" . $email . "','" . $hoTen . "','" . $soDienThoai . "','" . $diaChi . "','" . $payment_method . "','" . $ghichu . "','" . $update_time . "',0)";
    mysqli_query($mysqli, $insert_order);

    // Kiểm tra xem có id_khachhang hay không
    if (isset($_SESSION['id_khachhang'])) {
        $id_khachhang = $_SESSION['id_khachhang'];

        // Thực hiện xóa dữ liệu từ bảng tbl_giohang
        $delete_giohang_query = "DELETE FROM tbl_giohang WHERE id_khachhang = '$id_khachhang'";
        mysqli_query($mysqli, $delete_giohang_query);
    }
    // Thanh toán thành công, xóa giỏ hàng`
    unset($_SESSION['cart']);
    header('Location:../../index.php?quanly=ketqua');
}
?>
