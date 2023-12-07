<?php
    $sql_sua_sp = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$_GET[idsanpham]' LIMIT 1";
    $query_sua_sp = mysqli_query($mysqli,$sql_sua_sp);

?>
<div class="quanlymenu">
            <h3>Sửa sản phẩm</h3>
            <form method="POST" action="./modules/quanlysp/xuly.php?idsanpham=<?php echo $_GET['idsanpham']?>" enctype="multipart/form-data"> <!-- muon gui hinh anh qua post pha them enctype -->

<table class='them_menu'>


     
<?php
    while($row = mysqli_fetch_array($query_sua_sp)){
?>  
<tr>
            <td class="them_menu1">Tên Sản Phẩm</td>
            <td class="them_menu2"><input type="text" value="<?php

            
            echo $row['tensanpham'] ?>" name="tensanpham"></td>
        </tr>
        <tr>
            <td class="them_menu1">Mã sp</td>
            <td class="them_menu2"><input type="text" value="<?php
 echo $row['masp'] ?>" name="masp"></td>
        </tr>
        <tr>
            <td class="them_menu1">Khuyến mãi %</td>
            <td class="them_menu2"><input type="number" value="<?php echo $row['km'] ?>" name="km"></td>
        </tr>
        <tr>
            <td class="them_menu1">Giá sp</td>
            <td class="them_menu2"><input type="number" value="<?php echo $row['giasp'] ?>" name="giasp"></td>
        </tr>
        <tr>
            <td class="them_menu1">Giá gốc Khuyến mãi</td>
            <td class="them_menu2"><input type="number" value="<?php echo $row['giagockm'] ?>" name="giagockm"></td>
      
        </tr>
        <tr>
    <td class="them_menu1">Kích Thước</td>
    <td class="them_menu2">
        <?php
        $sql_kichthuoc = "SELECT size.id_size, size.ten_size, size_soluong.soluongsize FROM size LEFT JOIN size_soluong ON size.id_size = size_soluong.id_size AND size_soluong.id_sanpham = '" . $_GET['idsanpham'] . "'";
        $query_kichthuoc = mysqli_query($mysqli, $sql_kichthuoc);

        // Mảng để theo dõi các kích thước đã được hiển thị
        $displayedSizes = [];

        if ($query_kichthuoc) {
            while ($row_kichthuoc = mysqli_fetch_assoc($query_kichthuoc)) {
                $idSize = $row_kichthuoc['id_size'];
                $tenSize = $row_kichthuoc['ten_size'];
                $soluongsize = $row_kichthuoc['soluongsize'];
            
                // Hiển thị checkbox và số lượng tương ứng
                echo '<div class="form-check form-check-inline">';
                echo '<input class="form-check-input kichthuoc-checkbox" type="checkbox" name="kichthuoc[]" value="' . $idSize . '" data-initial-quantity="' . $soluongsize . '"';
                if ($soluongsize > 0) {
                    echo ' checked';
                }
                echo '>';
                echo '<label class="form-check-label">' . $tenSize . ' (Số lượng: ' . ($soluongsize ? $soluongsize : 0) . ')</label>';
                echo '</div>';
            
            
                
            }
        } else {
            die("Không thể lấy dữ liệu từ cơ sở dữ liệu.");
        }
        ?>
    </td>
</tr>




<!-- Đoạn mã HTML -->
<tr class="soluong-inputs">
    <td class="them_menu1">Số Lượng (theo kích thước)</td>
    <td class="them_menu2" id="soluong-inputs">
        <!-- Các trường input sẽ được hiển thị ra đây -->
    

<!-- và các input khác với các tên tương ứng -->

    </td>
</tr>

<tr>
    <td class="them_menu1">Tổng số lượng</td>
    <td class="them_menu2">
        <input type="number" id="tong-soluong" name="tongsoluong" value="0" readonly>
    </td>
</tr>


<script>
$(document).ready(function () {
    var initialQuantities = {};

    // Ẩn input ban đầu
    $('.soluong-input').hide();

    // Lấy số lượng ban đầu và hiển thị trên trang khi checkbox được tích
    $('.kichthuoc-checkbox').each(function () {
        var kichthuocId = $(this).val();
        var kichthuocName = $(this).siblings('label').text(); // Lấy tên của kích thước
        var initialQuantity = parseInt($(this).data('initial-quantity')) || 0;
        console.log('kichthuocId:', kichthuocId); // Ghi giá trị của kichthuocId vào console
            console.log('kichthuocName:', kichthuocName);
        // Lưu trữ số lượng ban đầu vào đối tượng initialQuantities
        initialQuantities[kichthuocId] = initialQuantity;

        // Hiển thị input khi checkbox được tích
        $(this).change(function () {
        if ($(this).prop('checked')) {
            var inputHtml = '<div class="form-group">';
inputHtml += '<label for="soluong-' + kichthuocId + '">Số lượng cho size ' + kichthuocName + ':</label>';
inputHtml += '<input type="number" class="form-control soluong-input" id="soluong-' + kichthuocId + '" name="soluong[' + kichthuocId + ']" value="' + initialQuantities[kichthuocId] + '" required>';
inputHtml += '</div>';
$('#soluong-inputs').append(inputHtml);

        } else {
            // Ẩn input khi checkbox không được tích
            $('#soluong-' + kichthuocId).parent().remove();
        }
        });

        // Kiểm tra trạng thái ban đầu của checkbox
        if ($(this).prop('checked')) {
            $(this).trigger('change');
        }
    });


    // Sự kiện khi người dùng tích vào checkbox
// Sự kiện khi người dùng tích vào checkbox
$(document).on('change', '.kichthuoc-checkbox', function () {
    var kichthuocId = $(this).val();
    var isChecked = $(this).prop('checked');
    var inputField = $('#soluong-' + kichthuocId);

    if (isChecked) {
        // Nếu checkbox được chọn, hiển thị số lượng có sẵn
        inputField.val(initialQuantities[kichthuocId]);
        inputField.closest('.form-group').show();
    } else {
        // Nếu checkbox bị huỷ chọn, ẩn số lượng và thiết lập giá trị là 0
        inputField.val(0);
        inputField.closest('.form-group').hide();
    }

    // Cập nhật tổng số lượng khi có sự thay đổi
    updateTotalQuantity();
});


    // Sự kiện khi người dùng nhập số
    $(document).on('input', '.soluong-input', function () {
        updateTotalQuantity();
    });

    // Hàm cập nhật tổng số lượng
    function updateTotalQuantity() {
        var totalQuantity = 0;
        $('.soluong-input').each(function () {
            var soluong = parseInt($(this).val()) || 0;
            totalQuantity += soluong;
        });
        $('#tong-soluong').val(totalQuantity);
    }

    // Gọi hàm cập nhật tổng số lượng khi trang tải
    updateTotalQuantity();
});

</script>

       
        <tr>
            <td class="them_menu1">Hình ảnh</td>
            <td class="them_menu2">
                <input type="file"  name="hinhanh">
                <img src="modules/quanlysp/uploads/<?php echo $row['hinhanh']?>" width=100px >
            </td>
        </tr>

        <tr>
            <td class="them_menu1">Tóm tắt</td>
            <td class="them_menu2"><textarea rows="5" name="tomtat"><?php echo $row['tomtat'] ?> </textarea></td>
        </tr>
        <!-- <tr>
            <td>Nội dung</td>
            <td><textarea rows="5"  name="noidung"><?php echo $row['noidung'] ?></textarea></td>
        </tr> -->
        <tr>
            <td class="them_menu1">Danh muc san pham</td>
            <td class="them_menu2">
                <select name="danhmuc">
                    <?php
                        $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
                        $query_danhmuc = mysqli_query($mysqli,$sql_danhmuc);
                        while($row_danhmuc = mysqli_fetch_array($query_danhmuc)){
                            if ($row_danhmuc['id_danhmuc']==$row['id_danhmuc']){
                    ?>
                    <option selected value="<?php echo $row_danhmuc['id_danhmuc'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
                    <?php
                        }else{
                    ?>
                    <option  value="<?php echo $row_danhmuc['id_danhmuc'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>

                    <?php
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="them_menu1">Tình trạng</td>
            <td class="them_menu1">
                <select name="tinhtrang">
                    <?php
                        if($row['tinhtrang']==1){
                    ?>
                    <option value="1" selected>Còn hàng</option>
                    <option value="0">Hết</option>
                    <?php
                        }else{
                    ?>
                    <option value="1" >Còn hàng</option>
                    <option value="0" selected>Hết</option>
                    <?php
                        }
                    ?>
                </select>
            </td>
        </tr>
        <?php
    }
?>
        <tr class="them_menu3">
            
            <td colspan ='2'><input type="submit" name='suasanpham' value='Sửa sản phẩm'></td>
        </tr>
   

</table></div>