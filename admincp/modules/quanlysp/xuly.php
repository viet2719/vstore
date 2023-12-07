<?php 
include('../../config/config.php');
//gọi tên danh mục avf thứ tự từ bên trang thêm sang
$tensize= $_POST['tenkichthuoc'];
$thutu = $_POST['thutu'];
//bắt đầu truyền về mysq/
if(isset($_POST['themkichthuoc'])){
    //them
    $sql_them = "INSERT INTO size(ten_size,thutu) VALUE('".$tensize."','".$thutu."')";
    mysqli_query($mysqli,$sql_them);
    header('location:../../index.php?action=quanlysp&query=kichthuoc'); //quay lại trang them.php

}else{
    //xóa
    $id=$_GET['idsize'];
    $sql_xoa = "DELETE FROM size WHERE id_size='".$id."'";
    mysqli_query($mysqli,$sql_xoa);
    header('location:../../index.php?action=quanlysp&query=kichthuoc');
}

?>
<?php

if (isset($_POST['themsanpham'])) {
    // Lấy dữ liệu từ form
    $tensanpham = $_POST['tensanpham'];
    $masp = $_POST['masp'];
    $giasp = $_POST['giasp'];
    $km = $_POST['km'];
    $giagockm = $_POST['giagockm'];
    $tomtat = $_POST['tomtat'];
    $tinhtrang = $_POST['tinhtrang'];
    $danhmuc = $_POST['danhmuc'];
    $kichthuoc = $_POST['kichthuoc'];
    $soluongTotal = $_POST['tongsoluong']; // Tổng số lượng sản phẩm
    $soluongsize = $_POST['soluong'];

    // Kiểm tra hình ảnh được tải lên
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
        $hinhanh = time() . '_' . $_FILES['hinhanh']['name'];
        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
        move_uploaded_file($hinhanh_tmp, 'uploads/' . $hinhanh);
    } else {
        $hinhanh = ''; // Đặt giá trị mặc định nếu không có hình ảnh hoặc có lỗi khi tải lên
    }

    // Thêm thông tin chung về sản phẩm vào bảng sản phẩm
    $sql_them_sanpham = "INSERT INTO tbl_sanpham(tensanpham, masp, giasp, km, giagockm,soluong, hinhanh, tomtat, tinhtrang, id_danhmuc) VALUES('$tensanpham', '$masp', '$giasp', '$km', '$giagockm','$soluongTotal' ,'$hinhanh', '$tomtat', '$tinhtrang', '$danhmuc')";
    $result_them_sanpham = $mysqli->query($sql_them_sanpham);

    // Lấy ID của sản phẩm vừa được thêm
    $idSanPham = $mysqli->insert_id;

    // Thêm thông tin về kích thước và số lượng vào bảng size_soluong
    for ($i = 0; $i < count($kichthuoc); $i++) {
        $size = $kichthuoc[$i];
        $soLuong = $soluongsize[$i];

        // Sử dụng $idSanPham và $soluongTotal khi thêm thông tin size và số lượng tương ứng
        // Ví dụ: $soLuong / $soluongTotal để tính tỷ lệ số lượng cho kích thước hiện tại
        $tyLeSoLuong = $soLuong / $soluongTotal;
        $soLuongPerSize = round($tyLeSoLuong * $soluongTotal); // Số lượng cho kích thước hiện tại

        $sql_them_size_soluong = "INSERT INTO size_soluong(id_sanpham, id_size, soluongsize) VALUES('$idSanPham', '$size', '$soLuongPerSize')";
        $result_them_size_soluong = $mysqli->query($sql_them_size_soluong);
    }

    // Kiểm tra và xử lý kết quả thêm sản phẩm và size_soluong tùy theo yêu cầu của bạn

    // Chuyển hướng hoặc thông báo tùy theo yêu cầu của bạn
    header('location:../../index.php?action=quanlysp&query=lieke'); // Quay lại trang quản lý sản phẩm

}
elseif(isset($_POST['suasanpham'])){
    // Lấy dữ liệu từ form
    $idSanPham = $_GET['idsanpham']; // Lấy ID sản phẩm cần sửa từ URL
    $tensanpham = $_POST['tensanpham'];
    $masp = $_POST['masp'];
    $giasp = $_POST['giasp'];
    $km = $_POST['km'];
    $giagockm = $_POST['giagockm'];

    $tomtat = $_POST['tomtat'];
    $tinhtrang = $_POST['tinhtrang'];
    $danhmuc = $_POST['danhmuc'];
    $kichthuoc = $_POST['kichthuoc'];
    $soluongTotal = $_POST['tongsoluong']; // Tổng số lượng sản phẩm
    $soluongsize = $_POST['soluong'];
    // Kiểm tra xem có file hình ảnh mới được tải lên không
    var_dump($_POST['soluong']);
    if(!empty($_FILES['hinhanh']['name'])){
        $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
        $hinhanh = time() . '_' . $_FILES['hinhanh']['name'];
        move_uploaded_file($hinhanh_tmp, 'uploads/'.$hinhanh);

        // Xóa hình ảnh cũ
        $sql_get_old_image = "SELECT hinhanh FROM tbl_sanpham WHERE id_sanpham = $idSanPham";
        $result_old_image = $mysqli->query($sql_get_old_image);
        if ($result_old_image->num_rows > 0) {
            $row_old_image = $result_old_image->fetch_assoc();
            $oldImage = $row_old_image['hinhanh'];
            if (!empty($oldImage)) {
                unlink('./uploads/'.$oldImage);
            }
        }
    } else {
        // Nếu không có ảnh mới, giữ nguyên ảnh cũ
        $sql_get_old_image = "SELECT hinhanh FROM tbl_sanpham WHERE id_sanpham = $idSanPham";
        $result_old_image = $mysqli->query($sql_get_old_image);
        if ($result_old_image->num_rows > 0) {
            $row_old_image = $result_old_image->fetch_assoc();
            $hinhanh = $row_old_image['hinhanh'];
        }
    }

    // Cập nhật thông tin sản phẩm vào database
    $sql_update = "UPDATE tbl_sanpham SET tensanpham='".$tensanpham."', masp='".$masp."', giasp='".$giasp."', km='".$km."', giagockm='".$giagockm."', soluong='".$soluongTotal."', hinhanh='".$hinhanh."', tomtat='".$tomtat."', tinhtrang='".$tinhtrang."', id_danhmuc='".$danhmuc."' WHERE id_sanpham='$idSanPham'";
    mysqli_query($mysqli, $sql_update);
      $sql_xoa_size_soluong = "DELETE FROM size_soluong WHERE id_sanpham = $idSanPham";
    mysqli_query($mysqli, $sql_xoa_size_soluong);

    // Thêm thông tin mới về kích thước và số lượng của sản phẩm vào bảng `size_soluong`
  // Lấy tổng số lượng từ form
$soluongTotal = $_POST['tongsoluong'];

// Tiếp tục xử lý các dữ liệu khác từ form

// Xóa dữ liệu cũ trong bảng size_soluong liên quan đến sản phẩm hiện tại
$sql_xoa_size_soluong = "DELETE FROM size_soluong WHERE id_sanpham = $idSanPham";
$mysqli->query($sql_xoa_size_soluong);

// Lặp qua các kích thước và số lượng tương ứng từ form để tính tỷ lệ và lưu vào database
$soluongsize = $_POST['soluong']; // Nhận dữ liệu từ form

// Lặp qua mảng $soluongsize để thêm dữ liệu vào cơ sở dữ liệu
foreach ($soluongsize as $id_size => $soluong) {
    // Chèn dữ liệu vào cơ sở dữ liệu
    $sql_them_size_soluong = "INSERT INTO size_soluong(id_sanpham, id_size, soluongsize) VALUES('$idSanPham', '$id_size', '$soluong')";
    $result_them_size_soluong = $mysqli->query($sql_them_size_soluong);

}

// Tiếp tục xử lý các thông tin khác và chuyển hướng hoặc thông báo thành công tùy theo yêu cầu của bạn

      header('location:../../index.php?action=quanlysp&query=lieke'); // Quay lại trang quản lý sản phẩm
}
else{
    //xoa
    $id=$_GET['idsanpham'];
    $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham = '$id' LIMIT 1";
    $query = mysqli_query($mysqli,$sql);
    $sql_xoa_size_soluong = "DELETE FROM size_soluong WHERE id_sanpham='".$id."'";
    mysqli_query($mysqli, $sql_xoa_size_soluong);
    while($row = mysqli_fetch_array($query)){
        unlink('uploads/'.$row['hinhanh']);
    }
    $sql_xoa = "DELETE FROM tbl_sanpham WHERE id_sanpham='".$id."'";
    mysqli_query($mysqli,$sql_xoa);
    header('location:../../index.php?action=quanlysp&query=lieke');
}



//truyền dữ liệu về mysql



?>