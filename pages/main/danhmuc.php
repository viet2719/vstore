
<div>


<?php
$soSanPhamTrenTrang = 10;

// Lấy trang hiện tại từ tham số trên URL
$trangHienTai = isset($_GET['trang']) ? $_GET['trang'] : 1;

// Tính vị trí bắt đầu của sản phẩm trên trang hiện tại
$viTriBatDau = ($trangHienTai - 1) * $soSanPhamTrenTrang;

// Truy vấn SQL để lấy danh sách sản phẩm theo trang
$sql_pro = "SELECT * FROM tbl_sanpham WHERE tbl_sanpham.id_danhmuc = '$_GET[id]' AND tinhtrang=1 ORDER BY id_sanpham DESC LIMIT $viTriBatDau, $soSanPhamTrenTrang";
$query_pro = mysqli_query($mysqli, $sql_pro);
$sql_cate = "SELECT * FROM tbl_danhmuc WHERE tbl_danhmuc.id_danhmuc = '$_GET[id]' LIMIT 1";
$query_cate = mysqli_query($mysqli,$sql_cate);
$row_title = mysqli_fetch_array($query_cate);
// Truy vấn SQL để lấy tổng số sản phẩm
$sql_trang = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_sanpham WHERE tbl_sanpham.id_danhmuc = '$_GET[id]'");
$row_trang = mysqli_fetch_assoc($sql_trang);
$tongSoSanPham = $row_trang['total'];

// Tính tổng số trang
$tongSoTrang = ceil($tongSoSanPham / $soSanPhamTrenTrang);
    
?>

        <div class="headline">
                <h3><?php echo $row_title['tendanhmuc'] ?></h3>
        </div>
        <div class="home-sort">
            <span class="filter-sort">Trang: <?php echo $trangHienTai ?></span>
                
            </div>
        </div>
        <div class="maincontent">
             
            <?php
                    $giaspkm=0;
                    while($row_pro = mysqli_fetch_array($query_pro)){
                        if ($row_pro['km']>0){$giaspkm=$row_pro['giasp']-($row_pro['giasp']*($row_pro['km']/100));};
            ?>
            
                <ul>
                    
                    <div class="maincontent-item">
                        <div class="maincontent-top">

                           
                            <div class="maiconten-top1">
                                
                                <a href="index.php?quanly=chitiet&idsanpham=<?php echo $row_pro['id_sanpham'] ?>" class="maincontent-img">
                                    <img src="./admincp/modules/quanlysp/uploads/<?php echo $row_pro['hinhanh'] ?>">
                                </a>
                                <button type  ="submit" title = 'chi tiet' class="muangay"  name="chitiet"><a href="index.php?quanly=chitiet&idsanpham=<?php echo $row_pro['id_sanpham'] ?>">Xem ngay</a></button>
                              
                            </div>
                        </div>
                        <div class="maincontent-info">
                            <a href="index.php?quanly=chitiet&idsanpham=<?php echo $row_pro['id_sanpham'] ?>" class="maincontent-name"><?php echo $row_pro['tensanpham'] ?></a>
                            <a href="index.php?quanly=chitiet&idsanpham=<?php echo $row_pro['id_sanpham'] ?>" class="maincontent-gia"><?php if($row_pro['km']>0){
                                echo '<div class="khuyenmais">' . - number_format($row_pro['km']).'%'. '</div>';
                                echo number_format($giaspkm).'đ'; }else {echo number_format($row_pro['giasp']).'đ';} ?>
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
    for ($i = 1; $i <= $tongSoTrang; $i++) {
        // Kiểm tra trang hiện tại để hiển thị class active
        $activeClass = ($i == $trangHienTai) ? 'active' : '';

        echo '<a href="index.php?quanly=danhmuc&id=' . $_GET['id'] . '&trang=' . $i . '" class="page-link ' . $activeClass . '">' . $i . '</a>';
    }
    ?>
</div>
