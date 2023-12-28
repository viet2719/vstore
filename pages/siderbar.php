<?php
ob_start();
$sql_danhmuc = "SELECT * FROM `tbl_danhmuc` ORDER BY `tbl_danhmuc`.`thutu` ASC";
$query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
?>
<div class="sidebar">

    <ul>
        <li><a href="index.php?quanly=shopall"></i>SHOP ALL</a></li>
        <li>
            <a href="index.php?quanly=sale" class="my-custom-link">

                <span class="badge badge-danger badge-pill text-uppercase">Sale</span>
            </a>
        </li>

        <?php
        while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
        ?>
            <li><a href="index.php?quanly=danhmuc&id=<?php echo $row_danhmuc['id_danhmuc'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></a></li>
        <?php
        }
        ?>



        <li><a href="index.php?quanly=donhang"><i class="bi bi-bag"></i> Đơn hàng</a></li>
        <?php
        // Kiểm tra xem người dùng đã đăng nhập chưa và role_id có khác 4 hay không
        if (isset($_SESSION['role_id']) && $_SESSION['role_id'] != 4) {
            echo '<li><a href="admincp/index.php"><i class="bi bi-person-circle"></i> ADMIN</a></li>';
        }
        ?>

    </ul>
</div>