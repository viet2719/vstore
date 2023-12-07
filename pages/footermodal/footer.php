<div class="line">
    <div class="site-animation wrapper-heading-home wrapper-heading-home-instagram">
        <div class="wrapper-animation2">
            <span class="text-animation-2">streetwear brand limited</span>
        </div>

    </div>

    <footer class="main-footer">
        <div class="line">

            <div class="one">

                <div class="one-1">
                    <h4>HỆ THỐNG CỬA HÀNG V STORE </h4><br>
                    <div class="one12">
                        <?php
                        $sql_chinhanh = "SELECT * FROM tbl_chinhanh ORDER BY id_chinhanh ASC";
                        $query_chinhanh = mysqli_query($mysqli, $sql_chinhanh);
                        while ($row_chinhanh = mysqli_fetch_array($query_chinhanh)) {
                        ?>
                            <p>
                                <?php echo $row_chinhanh['chinhanh'] ?> <br>

                            </p>
                        <?php } ?>
                    </div>
                </div>
                <?php
                $sql_tenchinhsach = "SELECT * FROM tbl_tenchinhsach ORDER BY id_tenchinhsach ASC";
                $query_tenchinhsach = mysqli_query($mysqli, $sql_tenchinhsach);

                ?>
                <div class="one-1">
                    <H4>CHÍNH SÁCH</H4><br><br>
                    <div class="one12">
                        <ul class="one13">
                            <?php
                            while ($row_tenchinhsach = mysqli_fetch_array($query_tenchinhsach)) {
                            ?>
                                <!-- <li><a href="#" title="Chính sách sử dụng website"> Chính sách sử dụng website </a> </li><br> -->
                                <li><a href="./index.php?quanly=chinhsach&id=<?php echo $row_tenchinhsach['id_tenchinhsach'] ?>"><?php echo $row_tenchinhsach['tenchinhsach'] ?></a></li><br>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="one-1">
                    <H4>THÔNG TIN LIÊN HỆ </H4><br><br>
                    <div>
                        <?php
                        $sql_lienhe = "SELECT * FROM tbl_lienhe ORDER BY id_lienhe ASC";
                        $query_lienhe = mysqli_query($mysqli, $sql_lienhe);
                        while ($row_lienhe = mysqli_fetch_array($query_lienhe)) {
                        ?>
                            <ul class="one12">
                                <li><?php echo $row_lienhe['lienhe'] ?></li>

                            </ul>
                        <?php } ?>
                    </div>
                </div>
                <div class="one-1">
                    <div class="one-footer">
                        <H4>FOLLOW US ON SOCIAL MEDIA</H4><br><br>
                        <a href="https://www.facebook.com/hadesvietnam/">
                            <img src="https://png.pngtree.com/png-clipart/20190215/ourlarge/pngtree-facebook-icon-png-image_548407.jpg">
                        </a>
                        <a href="https://www.instagram.com/accounts/login/">
                            <img src="https://cdn.icon-icons.com/icons2/2518/PNG/512/brand_instagram_icon_151534.png">
                        </a>
                        <div class="one-footer1">
                            <a href="http://online.gov.vn/Home/WebDetails/78935?AspxAutoDetectCookieSupport=1">
                                <img src="http://online.gov.vn/Content/EndUser/LogoCCDVSaleNoti/logoSaleNoti.png">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</footer>