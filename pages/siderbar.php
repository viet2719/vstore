<?php
ob_start();
$sql_danhmuc = "SELECT * FROM `tbl_danhmuc` ORDER BY `tbl_danhmuc`.`thutu` ASC";
$query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
?>

<div class="sidebar">
    <div class="toggle-sidebar-btn" onclick="toggleSidebar()">
        <span class="open-icon">&#9776;</span>

    </div>

    <ul>
        <li><a href="index.php?quanly=shopall"><i class="ti-hand-point-right"></i>SHOP ALL</a></li>
        <li>
            <a href="index.php?quanly=sale" class="my-custom-link">

                <span class="badge badge-danger badge-pill text-uppercase">Sale</span>
            </a>
        </li>

        <?php
        while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
        ?>
            <li><a href="index.php?quanly=danhmuc&id=<?php echo $row_danhmuc['id_danhmuc'] ?>"><i class="ti-hand-point-right"></i><?php echo $row_danhmuc['tendanhmuc'] ?></a></li>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.querySelector('.sidebar');
        var toggleBtn = document.querySelector('.toggle-sidebar-btn');
        var isSidebarExpanded = false;
        var timer;

        if (sidebar && toggleBtn) {
            toggleBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Ngừng sự kiện click lan ra ngoài nút toggle-sidebar-btn
                isSidebarExpanded = true;
                sidebar.classList.add('expanded');
                timer = setTimeout(function() {
                    sidebar.classList.remove('expanded');
                    isSidebarExpanded = false;
                }, 5000);
            });

            sidebar.addEventListener('mouseleave', function() {
                if (isSidebarExpanded) {
                    clearTimeout(timer); // Hủy bỏ timer nếu chuột rời khỏi sidebar trong khoảng thời gian 500ms
                    isSidebarExpanded = false;
                } else {
                    sidebar.classList.remove('expanded');
                }
            });

            sidebar.addEventListener('click', function(event) {
                event.stopPropagation(); // Ngừng sự kiện click lan ra ngoài sidebar
            });

            document.body.addEventListener('click', function() {
                if (isSidebarExpanded) {
                    clearTimeout(timer); // Hủy bỏ timer nếu bạn click ngoài sidebar để đóng sidebar trước khi 500ms
                    sidebar.classList.remove('expanded');
                    isSidebarExpanded = false;
                }
            });
        }
    });
</script>