<div>
    <?php


    //$sql_pro = "SELECT * FROM tbl_sanpham  DESC LIMIT $begin,2";
    $sortOption = isset($_GET['sort-by']) ? $_GET['sort-by'] : '';

    // Khởi tạo một biến để lưu điều kiện ORDER BY
    $orderBy = '';

    // Kiểm tra nếu $sortOption không trống để xác định điều kiện ORDER BY
    $sortOption = isset($_GET['sort-by']) ? $_GET['sort-by'] : '';

    // Khởi tạo một biến để lưu điều kiện ORDER BY
    $orderBy = '';

    // Kiểm tra nếu $sortOption không trống để xác định điều kiện ORDER BY
    if (!empty($sortOption)) {
        switch ($sortOption) {
            case 'price-ascending':
                $orderBy = 'ORDER BY tbl_sanpham.giasp ASC';
                break;
            case 'price-descending':
                $orderBy = 'ORDER BY tbl_sanpham.giasp DESC';
                break;
            case 'title-ascending':
                $orderBy = 'ORDER BY tbl_sanpham.tensanpham ASC';
                break;
            case 'title-descending':
                $orderBy = 'ORDER BY tbl_sanpham.tensanpham DESC';
                break;
            case 'best-selling':
                $orderBy = 'ORDER BY total_quantity DESC'; // Sắp xếp theo số lượng đã bán giảm dần
                break;
            case 'quantity-descending':
                $orderBy = 'ORDER BY tbl_sanpham.soluong DESC';
                break;
            case 'created-descending':
                $orderBy = 'ORDER BY tbl_sanpham.id_sanpham DESC';
                break;
                // Thêm các trường hợp sắp xếp khác nếu cần
            default:
                $orderBy = 'ORDER BY tbl_sanpham.created_at DESC'; // Mặc định sắp xếp theo ngày tạo mới nhất
        }
    }

    $items_per_page = 36;

    // Trang hiện tại (mặc định là trang 1)
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Tính vị trí bắt đầu của kết quả
    $offset = ($current_page - 1) * $items_per_page;

    // Truy vấn SQL với LIMIT
    $sql_pro = "SELECT tbl_sanpham.*, SUM(tbl_cart_details.soluongmua) AS total_quantity 
                FROM tbl_sanpham 
                LEFT JOIN tbl_cart_details ON tbl_sanpham.id_sanpham = tbl_cart_details.id_sanpham
                WHERE tbl_sanpham.id_sanpham AND tbl_sanpham.tinhtrang = 1 
                GROUP BY tbl_sanpham.id_sanpham $orderBy 
                LIMIT $offset, $items_per_page";


    $query_pro = mysqli_query($mysqli, $sql_pro);

    if (!$query_pro) {
        die("Lỗi truy vấn: " . mysqli_error($mysqli));
    }

    //get ten danh muc
    // $sql_cate = "SELECT * FROM tbl_danhmuc WHERE tbl_danhmuc.id_danhmuc = '$_GET[id]' LIMIT 1";
    // $query_cate = mysqli_query($mysqli,$sql_cate);
    // $row_title = mysqli_fetch_array($query_cate);

    ?>

    <div>
        <div>
            <select id="sort-by" class="list-sort-by" onchange="sortProducts()">
                <option value="created-descending" <?php echo ($sortOption == 'created-descending') ? 'selected' : ''; ?>>Mới nhất</option>
                <option value="price-ascending" <?php echo ($sortOption == 'price-ascending') ? 'selected' : ''; ?>>Giá: Tăng dần</option>
                <option value="price-descending" <?php echo ($sortOption == 'price-descending') ? 'selected' : ''; ?>>Giá: Giảm dần</option>
                <option value="title-ascending" <?php echo ($sortOption == 'title-ascending') ? 'selected' : ''; ?>>Tên: A-Z</option>
                <option value="title-descending" <?php echo ($sortOption == 'title-descending') ? 'selected' : ''; ?>>Tên: Z-A</option>
                <option value="best-selling" <?php echo ($sortOption == 'best-selling') ? 'selected' : ''; ?>>Bán chạy nhất</option>
            </select>
        </div>

    </div>

</div>
<div class="maincontent">

    <?php
    $giaspkm = 0;
    while ($row_pro = mysqli_fetch_array($query_pro)) {
        if ($row_pro['km'] > 0) {
            $giaspkm = $row_pro['giasp'] - ($row_pro['giasp'] * ($row_pro['km'] / 100));
        };
        $soluongcon = 0;
        $soluongcon = $row_pro['soluong'] - $row_pro['total_quantity'];
    ?>

        <ul>

            <div class="maincontent-item">
                <div class="maincontent-top">
                    <div class="maiconten-top1">
                        <a href="index.php?quanly=chitiet&idsanpham=<?php echo $row_pro['id_sanpham'] ?>" class="maincontent-img">
                            <img src="./admincp/modules/quanlysp/uploads/<?php echo $row_pro['hinhanh'] ?>">
                        </a>
                        <button type="submit" title='chi tiet' class="muangay" name="chitiet">
                            <a href="index.php?quanly=chitiet&idsanpham=<?php echo $row_pro['id_sanpham'] ?>">
                                Xem ngay
                            </a>
                        </button>
                    </div>
                </div>
                <div class="maincontent-info">
                    <a href="index.php?quanly=chitiet&idsanpham=<?php echo $row_pro['id_sanpham'] ?>" class="maincontent-name">
                        <?php echo $row_pro['tensanpham'] ?></a>
                    <a href="index.php?quanly=chitiet&idsanpham=<?php echo $row_pro['id_sanpham'] ?>" class="maincontent-gia">
                        <?php if ($row_pro['km'] > 0) {
                            echo '<div class="khuyenmais">' . -number_format($row_pro['km']) . '%' . '</div>';
                            echo number_format($giaspkm) . 'đ';
                        } else {
                            echo number_format($row_pro['giasp']) . 'đ';
                        } ?>
                        <span class="pro-price-del">
                            <?php
                            if ($row_pro['km'] > 0) {
                                echo '<span class="original-price">' . number_format($row_pro['giasp']) . 'đ</span>';
                            }
                            ?>
                        </span>
                    </a>
                </div>
            </div>

        </ul>
    <?php
    }
    ?>
</div>
<div class="pagination">
    <?php
    // Kiểm tra nếu có sản phẩm
    if (isset($total_pages) && $total_pages > 0) {
        for ($i = 1; $i <= $total_pages; $i++) {
            // Kiểm tra trang hiện tại để hiển thị class active
            $active_class = ($current_page == $i) ? 'active' : '';
            // Giữ trạng thái được chọn của danh mục khi chuyển trang
            $category_param = (isset($_GET['category'])) ? '&category=' . $_GET['category'] : '';
            echo '<a href="index.php?quanly=shopall&trang=' . $i . $category_param . '" class="page-link ' . $active_class . '">' . $i . '</a>';
        }
    } else {
        // Nếu không có sản phẩm, set $total_pages bằng 1
        $total_pages = 1;
        echo '<a href="index.php?quanly=shopall&trang=1" class="page-link active">1</a>';
    }
    ?>
</div>
<script>
    // Lấy danh sách tất cả các phần tử có class 'maincontent-name'
    function sortProducts() {
        var sortByDropdown = document.getElementById('sort-by');
        var selectedOption = sortByDropdown.value;
        // Chuyển hướng URL với tham số sắp xếp mới
        window.location.href = 'index.php?quanly=shopall&trang=1&sort-by=' + selectedOption;
    }
</script>