<?php
ob_start();
if (isset($_POST['dangnhap'])) {
    $email = $_POST['email'];
    $matkhau = md5($_POST['password']);
    $sql = "SELECT * FROM tbl_khackhang WHERE email='" . $email . "' AND matkhau='" . $matkhau . "' LIMIT 1";
    $row = mysqli_query($mysqli, $sql);
    $count = mysqli_num_rows($row);
    if ($count > 0) {
        $row_data = mysqli_fetch_array($row);
        $_SESSION['dangky'] = $row_data['tenkhachhang'];
        $_SESSION['id_khachhang'] = $row_data['id_khachhang']; // Lấy id khach hang de thanh toan
        $_SESSION['role_id'] = $row_data['role_id'];
        $_SESSION['email'] = $row_data['email'];

        // Kiểm tra xem có session cart không
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $id_khachhang = $_SESSION['id_khachhang'];

            // Chuyển dữ liệu từ session cart vào tbl_giohang
            foreach ($_SESSION['cart'] as $cart_item) {
                $cart_id = $cart_item['id'];
                $cart_size = $cart_item['size'];
                $cart_quantity = $cart_item['soluong'];

                // Kiểm tra xem sản phẩm đã tồn tại trong tbl_giohang của người dùng hay chưa
                $sql_check_giohang = "SELECT * FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$cart_id' AND size = '$cart_size'";
                $query_check_giohang = mysqli_query($mysqli, $sql_check_giohang);
                $num_rows = mysqli_num_rows($query_check_giohang);

                if ($num_rows > 0) {
                    // Nếu sản phẩm đã tồn tại, cập nhật số lượng
                    $sql_update_giohang = "UPDATE tbl_giohang SET soluong = soluong + $cart_quantity WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$cart_id' AND size = '$cart_size'";
                    mysqli_query($mysqli, $sql_update_giohang);
                } else {
                    // Nếu sản phẩm chưa tồn tại, thêm mới vào tbl_giohang
                    $sql_insert_giohang = "INSERT INTO tbl_giohang (id_khachhang, id_sanpham, soluong, size) VALUES ('$id_khachhang', '$cart_id', '$cart_quantity', '$cart_size')";
                    mysqli_query($mysqli, $sql_insert_giohang);
                }
            }

            // Sau khi chuyển dữ liệu, xoá session cart
            unset($_SESSION['cart']);
        }

        if ($_SESSION['role_id'] == 4) {
            header("Location: index.php");
        } else {
            // Nếu role_id không phải là 4, hiển thị thông báo và nút chọn
            echo '<script>
                    var isAdmin = confirm("Đăng nhập thành công! Bạn muốn vào trang Admin không?");
                    if (isAdmin) {
                        window.location.href = "admincp/index.php";
                    } else {
                        window.location.href = "index.php";
                    }
                </script>';
        }
        exit();
    } else {
        echo '<script>alert("Tài khoản hoặc Mật khẩu không đúng, vui lòng nhập lại.");</script>';
    }
}
?>

<script>
    function validateForm() {
        var email = document.forms["loginForm"]["email"].value;
        var password = document.forms["loginForm"]["password"].value;
        if (email == "" || password == "") {
            alert("Vui lòng nhập đầy đủ thông tin tài khoản và mật khẩu.");
            return false;
        }
    }
</script>

<form name="loginForm" action="" method="POST" onsubmit="return validateForm()">
    <div class="login-form">
        <div class="login-container">

            <h2> Đăng nhập </h2>
            <a>Email <span style="color:red">*</span></a><br>
            <input type="text" name="email"><br>
            <a>Password<span style="color:red">*</span></a><br>
            <input type="password" name="password"><br>
            <button name="dangnhap">Đăng nhập</button>
            <i>hoặc </i>
            <div class="links">
                <a href="./index.php">← Quay lại trang chủ</a>
                <a href="./index.php?quanly=dangky">Đăng ký →</a>
            </div>
            <?php
            // Hiển thị thông báo lỗi nếu có
            if (isset($error_message)) {
                echo '<p style="color: red;">' . $error_message . '</p>';
            }
            ?>
        </div>
    </div>
</form>
<div class="clear"></div>
</div>