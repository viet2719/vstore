<?php
ob_start();
if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
    // Xóa toàn bộ session
    session_unset();
    session_destroy();
}
// Khi khách hàng thêm sản phẩm vào giỏ hàng
// Khi họ đăng nhập, bạn có thể
// Khi họ đăng nhập, bạn có thể truy cập $_SESSION['cart'] để lấy thông tin giỏ hàng của họ
?>
<div>
    <div class="header header1">
        <a href="index.php">
            <div class="logo-header pd-28">V STORE</div>
        </a>
        <div class="account-links pd-28">
            <?php
            if (isset($_SESSION['dangky'])) {
            ?>
                <a href="./index.php?quanly=thongtintaikhoann&id=<?php echo $_SESSION['id_khachhang'] ?>" id="login"><?php echo $_SESSION['dangky']; ?></a>
                /
                <a href="#" id="logout">Đăng xuất</a>

                <script>
                    document.getElementById('logout').addEventListener('click', function() {
                        var confirmLogout = confirm('Bạn muốn đăng xuất không?');
                        if (confirmLogout) {
                            window.location.href = "index.php?dangxuat=1";
                        }
                    });
                </script>
            <?php
            } else {
            ?>
                <a href="./index.php?quanly=dangnhap" id="login">Đăng nhập</a>
                /
                <a href="./index.php?quanly=dangky" id="regist">Đăng ký</a>
            <?php
            }
            ?>
        </div>
        <label for="check-timkiem">
            <span class="ti-search icon-header"></span>
        </label>
        <label class="giohang" for="check-giohang">
            Giỏ Hàng
            <i class="ti-shopping-cart"> <span class="search-box"></span>
                <?php
                if (isset($_SESSION['id_khachhang'])) {
                    // Nếu có id_khachhang, sử dụng dữ liệu từ tbl_giohang
                    $id_khachhang = $_SESSION['id_khachhang'];
                    $total_quantity = 0;
                    // Truy vấn bảng tbl_giohang để lấy tổng số lượng sản phẩm dựa trên id_khachhang
                    $sql_count_giohang = "SELECT SUM(soluong) AS total_quantity FROM tbl_giohang WHERE id_khachhang = '$id_khachhang'";
                    $result_count_giohang = mysqli_query($mysqli, $sql_count_giohang);
                    if ($result_count_giohang) {
                        $row_count_giohang = mysqli_fetch_assoc($result_count_giohang);
                        $total_quantity = $row_count_giohang['total_quantity'];
                        // Kiểm tra nếu giỏ hàng rỗng thì echo ra chuỗi rỗng
                        echo $total_quantity !== null ? "($total_quantity)" : "";
                    }
                } elseif (isset($_SESSION['cart'])) {
                    // Sử dụng dữ liệu từ $_SESSION['cart']
                    $soluongsanpham = 0;
                    foreach ($_SESSION['cart'] as $cart_item) {
                        $soluongsanpham += $cart_item['soluong'];
                    }
                    echo "($soluongsanpham)";
                }
                ?>
            </i>
        </label>
    </div>

</div>