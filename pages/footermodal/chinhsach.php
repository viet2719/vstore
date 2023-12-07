                   <?php
                        $sql_pro = "SELECT * FROM tbl_chinhsach WHERE tbl_chinhsach.id_tenchinhsach = '$_GET[id]' ORDER BY id_chinhsach DESC LIMIT 1"; //lấy tất cả sản phẩm dựa vào id 
                        $query_pro = mysqli_query($mysqli,$sql_pro);
                   ?>
                   <div class="blog blog_chinhsach">
                        <?php
                            while($row_pro = mysqli_fetch_array($query_pro)){
                        ?>
                        <h2><?php echo $row_pro['tenchinhsach']?></h2>
                        <!-- <p>Với mong muốn ngày càng hoàn thiện tổ chức bộ máy chuyên nghiệp đểphục vụ khách hàng ngày một tốt hơn chúng tôi đưa ra những chính sách điều khoản nhất định tại HADES nhằm đảm bảo lợi ích và quyền lợi của khách hàng khi tham gia website của chúng tôi<br>Điều khoản sử dụng:</p> -->
                        <p class="blog_chinhsach">
                            <?php echo $row_pro['noidung']?>
                        </p>
                        <?php
                            }
                        ?>
                    </div>