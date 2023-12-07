<?php
$sql_pro = "SELECT * FROM tbl_sanpham,tbl_danhmuc WHERE tbl_sanpham.id_danhmuc=tbl_danhmuc.id_danhmuc AND tbl_sanpham.tensanpham ";
$query_pro = mysqli_query($mysqli, $sql_pro);

$sql = "SELECT ten_size FROM size WHERE id_size = ?";
$result = mysqli_prepare($mysqli, $sql);
?>

<div class="modals">
    <div>
        <input type="checkbox" class="check-timkiem-css" name="check-giohang" id="check-giohang">
        <label for="check-giohang" class="search-them-modal "></label>
        <div class="search_modal">
            <label for="check-giohang" class="search_modal-icon-btn ti-close"></label>
            <div class="search_modal-header">
                <p>Giỏ hàng</p>
            </div>

            <div class="cart-items">
    <?php
    // Khởi tạo biến tổng tiền và tổng số lượng sản phẩm
    $tongtien = 0;
    $soluongsanpham = 0;

    // Kiểm tra xem có id_khachhang không
    if (isset($_SESSION['id_khachhang'])) {
        $id_khachhang = $_SESSION['id_khachhang'];

        // Truy vấn bảng tbl_giohang để lấy thông tin sản phẩm dựa trên id_khachhang
        $sql_giohang = "SELECT giohang.*, sanpham.tensanpham, sanpham.giasp, sanpham.hinhanh, size.ten_size
                        FROM tbl_giohang giohang
                        JOIN tbl_sanpham sanpham ON giohang.id_sanpham = sanpham.id_sanpham
                        JOIN size ON giohang.size = size.id_size
                        WHERE giohang.id_khachhang = $id_khachhang";

        $query_giohang = mysqli_query($mysqli, $sql_giohang);

        // Hiển thị thông tin sản phẩm trong giỏ hàng
        while ($cart_item = mysqli_fetch_assoc($query_giohang)) {
            $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
            $tongtien += $thanhtien;
            $soluongsanpham += $cart_item['soluong'];

            // Lấy thông tin sản phẩm từ giỏ hàng
            $product_id = $cart_item['id_sanpham']; // Sử dụng id_sanpham từ bảng tbl_giohang
            $product_name = $cart_item['tensanpham'];
            $product_price = $cart_item['giasp'];
            $product_image = './admincp/modules/quanlysp/uploads/' . $cart_item['hinhanh'];

            // Hiển thị tên size từ bảng size
            $product_size = $cart_item['ten_size'];

            // Hiển thị sản phẩm
            ?>
            <div id="product_<?php echo $product_id; ?>" class="cart-item">
                <div class="cart-item-image">
                    <img src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>">
                </div>
                <div class="item-details">
                    <p class="item-name">
                        <a href="index.php?quanly=chitiet&idsanpham=<?php echo $product_id; ?>">
                            <?php echo $product_name; ?>
                        </a>
                    </p>
                    <p class="item-size">Kích thước:
                        <?php echo $product_size; ?>
                    </p>
                    <div class="item-quantity">Số lượng:
                        <span class="item-quantity-value">
                            <?php echo $cart_item['soluong']; ?>
                        </span>
                    </div>
                    <p class="item-price">Giá:
                        <?php echo number_format($product_price) . '₫'; ?>
                    </p>
                    <div class="item-details clearfix">
                        <p class="color_reds">
                            <a href="#" onclick="deleteProduct(<?php echo $product_id; ?>)">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        }
    } elseif (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Nếu không có id_khachhang, sử dụng dữ liệu từ $_SESSION['cart']
        foreach ($_SESSION['cart'] as $cart_item) {
            $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
            $tongtien += $thanhtien;
            $soluongsanpham += $cart_item['soluong'];

            // Lấy thông tin sản phẩm từ giỏ hàng
            $product_id = $cart_item['id'];
            $product_name = $cart_item['tensanpham'];
            $product_price = $cart_item['giasp'];
            $product_image = './admincp/modules/quanlysp/uploads/' . $cart_item['hinhanh'];

            // Thực hiện truy vấn để lấy tên size
            mysqli_stmt_bind_param($result, "i", $cart_item['size']);
            mysqli_stmt_execute($result);
            $size_result = mysqli_stmt_get_result($result);

            // Lấy tên size hoặc hiển thị thông báo nếu không thành công
            if ($size_result && mysqli_num_rows($size_result) > 0) {
                $row = mysqli_fetch_assoc($size_result);
                $product_size = $row['ten_size'];
            } else {
                $product_size = "Kích thước không hợp lệ";
            }

            // Hiển thị sản phẩm
            ?>
            <div id="product_<?php echo $product_id; ?>" class="cart-item">
                <div class="cart-item-image">
                    <img src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>">
                </div>
                <div class="item-details">
                    <p class="item-name">
                        <a href="index.php?quanly=chitiet&idsanpham=<?php echo $product_id; ?>">
                            <?php echo $product_name; ?>
                        </a>
                    </p>
                    <p class="item-size">Kích thước:
                        <?php echo $product_size; ?>
                    </p>
                    <div class="item-quantity">Số lượng:
                        <span class="item-quantity-value">
                            <?php echo $cart_item['soluong']; ?>
                        </span>
                    </div>
                    <p class="item-price">Giá:
                        <?php echo number_format($product_price) . '₫'; ?>
                    </p>
                    <div class="item-details clearfix">
                        <p class="color_reds">
                            <a href="#" onclick="deleteProduct(<?php echo $product_id; ?>)">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "Giỏ hàng trống";
    }
    ?>
</div>
<div class="line">
    <p class="tongtien_a">Tổng tiền: <span id="tongtien_c">
            <?php echo number_format($tongtien); ?>₫
        </span></p>
</div>


            <div class="button-row">
                <a href="index.php?quanly=giohang">
                    <button class="view-cart-button">Xem giỏ hàng</button>
                </a>
                <?php
                // Kiểm tra xem người dùng đã đăng nhập và giỏ hàng có sản phẩm không
                if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                    ?>


                    <a href="index.php?quanly=xulythanhtoan">
                        <button class="checkout-button">Thanh toán</button>
                    </a>
                    <?php
                } else { ?>
                    <a href="index.php?quanly=giohang">
                        <button class="checkout-button">Thanh toán</button>
                    </a>
                    <?php
                }
                ?>
            
            </div>
        </div>
    </div>
</div>
<script>
    function deleteProduct(productId) {
        // Gửi yêu cầu xóa bằng Ajax
        var xhr = new XMLHttpRequest();
        xhr.open('POST', './pages/main/themgiohang.php?xoa=' + productId, true);
        xhr.send();

        // Xử lý phản hồi từ server (nếu cần)
        xhr.onload = function () {
            if (xhr.status == 200) {
                // Xử lý phản hồi từ server nếu cần
                // Ẩn sản phẩm đã xóa
                updateUIAfterDelete(productId);
                // Cập nhật tổng tiền
                updateTotalPrice();
            } else {
                console.error('Lỗi khi xóa sản phẩm.');
                // Hiển thị thông báo lỗi cho người dùng
                alert('Đã xảy ra lỗi khi xóa sản phẩm. Vui lòng thử lại hoặc liên hệ hỗ trợ.');
            }
        };
    }

    function updateUIAfterDelete(productId) {
        // Ẩn sản phẩm có ID tương ứng
        var deletedProduct = document.getElementById('product_' + productId);
        if (deletedProduct) {
            deletedProduct.style.display = 'none';
        }
    }

    // Hàm cập nhật tổng tiền
    function updateTotalPrice() {
        var totalElement = document.getElementById('tongtien_c');
        if (totalElement) {
            var newTotal = calculateNewTotal();
            totalElement.innerHTML = numberFormat(newTotal) + '₫';
        }
    }

    // Hàm tính tổng tiền mới
    function calculateNewTotal() {
        var total = 0;
        var items = document.querySelectorAll('.cart-item');
        items.forEach(function (item) {
            var priceElement = item.querySelector('.item-price');
            var priceText = priceElement.textContent.trim().replace('Giá: ', '').replace('₫', '').replace(',', '');
            var quantityElement = item.querySelector('.item-quantity-value');
            var quantity = parseInt(quantityElement.textContent.trim());
            var subtotal = parseFloat(priceText) * quantity;

            // Kiểm tra nếu subtotal không phải là số hợp lệ (NaN), thì gán giá trị 0
            subtotal = isNaN(subtotal) ? 0 : subtotal;

            total += subtotal;
        });
        return total;
    }

    // Hàm định dạng số có dấu phẩy ngăn cách hàng nghìn
    function numberFormat(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>
