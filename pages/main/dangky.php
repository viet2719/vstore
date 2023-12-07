<?php

if (isset($_POST['dangky'])) {
    $tenkhachhang = $_POST['hoten'];
    $diachi = $_POST['diachi'];
    $dienthoai = $_POST['dienthoai'];
    $email = $_POST['email'];
    $matkhau = md5($_POST['matkhau']);
    $id_role = $_POST['role_id'];

    // Lấy giá trị của các ô select cho tỉnh, huyện, xã
    $provinceId = isset($_POST['province']) ? intval($_POST['province']) : null;
    $districtId = isset($_POST['district']) ? intval($_POST['district']) : null;
    $wardId = isset($_POST['ward']) ? intval($_POST['ward']) : null;

    // Kiểm tra xem tỉnh, huyện, xã có tồn tại không
    $provinceResult = mysqli_query($mysqli, "SELECT name FROM province WHERE province_id = $provinceId");
    $districtResult = mysqli_query($mysqli, "SELECT name FROM district WHERE district_id =  $districtId");
    $wardResult = mysqli_query($mysqli, "SELECT name FROM wards WHERE wards_id = $wardId");

    $provinceName = ($provinceRow = mysqli_fetch_assoc($provinceResult)) ? $provinceRow['name'] : null;
    $districtName = ($districtRow = mysqli_fetch_assoc($districtResult)) ? $districtRow['name'] : null;
    $wardName = ($wardRow = mysqli_fetch_assoc($wardResult)) ? $wardRow['name'] : null;

    // Kiểm tra xem dữ liệu tỉnh, huyện, xã có tồn tại không
    if (!$provinceName || !$districtName || !$wardName) {
        echo 'Dữ liệu tỉnh, huyện, xã không hợp lệ.';
        exit;
    }

    // Gộp tên tỉnh, huyện, xã để lưu vào cột diachi trong csdl
    $fullAddress = "$wardName, $districtName, $provinceName";

    $sql_dangky = mysqli_query(
        $mysqli,
        "INSERT INTO tbl_khackhang(tenkhachhang, diachi, dienthoai, email, matkhau, role_id) 
        VALUES ('$tenkhachhang','$fullAddress','$dienthoai','$email','$matkhau',4)"
    );

    if ($sql_dangky) {
        echo '<h3>Bạn đã đăng ký thành công</h3>';
        $_SESSION['dangky'] = $tenkhachhang;
        $_SESSION['id_khachhang'] = mysqli_insert_id($mysqli); // Lưu id khách hàng để thanh toán
        header('Location:index.php?quanly=giohang');
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $id_khachhang = $_SESSION['id_khachhang'];

            // Chuyển dữ liệu từ session cart vào tbl_giohang
            foreach ($_SESSION['cart'] as $cart_item) {
                $cart_id = $cart_item['id'];
                $cart_size = $cart_item['size'];
                $cart_quantity = $cart_item['soluong'];

                // Kiểm tra xem sản phẩm đã tồn tại trong tbl_giohang của người dùng hay chưa
                $sql_check_giohang = "SELECT * FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$cart_id' AND size = '$cart_size'";
                $query_check_giohang = mysqli_query($mysqli, $sql_check_giohang);
                $num_rows = mysqli_num_rows($query_check_giohang);

                if ($num_rows > 0) {
                    // Nếu sản phẩm đã tồn tại, cập nhật số lượng
                    $sql_update_giohang = "UPDATE tbl_giohang SET soluong = soluong + $cart_quantity WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$cart_id' AND size = '$cart_size'";
                    mysqli_query($mysqli, $sql_update_giohang);
                } else {
                    // Nếu sản phẩm chưa tồn tại, thêm mới vào tbl_giohang
                    $sql_insert_giohang = "INSERT INTO tbl_giohang (id_khachhang, id_sanpham, soluong, size) VALUES ('$id_khachhang', '$cart_id', '$cart_quantity', '$cart_size')";
                    mysqli_query($mysqli, $sql_insert_giohang);
                }
            }

            // Sau khi chuyển dữ liệu, xoá session cart
            unset($_SESSION['cart']);
        }
    } else {
        echo 'Có lỗi xảy ra: ' . mysqli_error($mysqli);
    }
}

?>

<form action="#" method="POST">
    <div class="login-form">
        <div class="login-container">
            <a href="./index.php" class="header-zz">
                <div class="logo-wrapper">
                    <!-- <img src="./assets/img/logo.webp" alt="logo"> -->
                </div>
            </a>
            <h2> Đăng kí </h2>
            <input type="text" placeholder="Họ tên" name="hoten"><br>

            <input type="text" placeholder="Số điện thoại" name="dienthoai"><br>
            <input type="text" placeholder="Email" name="email"><br>
            <input type="text" placeholder="Địa chỉ nhận hàng" name="diachi"><br>
            <!-- Thêm các ô select cho tỉnh, huyện, xã -->
            <div class="select-container">
                <select name="province" id="province">
                    <option value="" selected disabled>Chọn tỉnh/thành phố</option>
                    <!-- Các option sẽ được tạo bằng JavaScript -->
                </select>

                <select name="district" id="district" disabled>
                    <option value="" selected disabled>Chọn quận/huyện</option>
                </select>

                <select name="ward" id="ward" disabled>
                    <option value="" selected disabled>Chọn xã/phường</option>
                </select>
            </div>
            <input type="password" placeholder="Mật Khẩu" name="matkhau"><br>
            <input type="password" placeholder="Nhập lại mật khẩu" name="nhaplaimatkhau"><br>



            <div id="password-error" style="color: red;"></div>

            <button type="submit" name="dangky">Đăng kí</button>

            <div class="links">
                <a href="./index.php?quanly=dangnhap">
                    <p>← Quay lại đăng nhập</p>
                </a>
            </div>
        </div>
    </div>
</form>

<script>
    // JavaScript để tạo option cho các ô select tỉnh, huyện, xã
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy các phần tử select
        var provinceSelect = document.getElementById('province');
        var districtSelect = document.getElementById('district');
        var wardSelect = document.getElementById('ward');
        var diachiInput = document.getElementsByName('diachi')[0];

        // Lấy danh sách tỉnh khi trang web được tải
        var xhrProvince = new XMLHttpRequest();
        xhrProvince.onreadystatechange = function() {
            if (xhrProvince.readyState === XMLHttpRequest.DONE) {
                if (xhrProvince.status === 200) {
                    var provinces = JSON.parse(xhrProvince.responseText);
                    updateSelect(provinceSelect, provinces);
                    provinceSelect.disabled = false;
                } else {
                    console.error('Lỗi khi lấy danh sách tỉnh');
                }
            }
        };

        xhrProvince.open('GET', './pages/main/get_locations.php', true);
        xhrProvince.send();

        // Khi chọn tỉnh, load danh sách quận/huyện tương ứng
        provinceSelect.addEventListener('change', function(event) {
            var provinceId = event.target.value;
            console.log('Selected Province ID:', provinceId);
            // Không có giá trị chọn (disabled)
            if (!provinceId) {
                districtSelect.innerHTML = '<option value="" selected disabled>Chọn quận/huyện</option>';
                wardSelect.innerHTML = '<option value="" selected disabled>Chọn xã/phường</option>';
                districtSelect.disabled = true;
                wardSelect.disabled = true;
                return;
            }

            // Gửi yêu cầu AJAX để lấy danh sách quận/huyện
            var xhrDistrict = new XMLHttpRequest();
            xhrDistrict.onreadystatechange = function() {
                if (xhrDistrict.readyState === XMLHttpRequest.DONE) {
                    if (xhrDistrict.status === 200) {
                        var districts = JSON.parse(xhrDistrict.responseText);
                        updateSelect(districtSelect, districts);
                        districtSelect.disabled = false;
                        // Update diachi input when province is selected
                        updateDiachiInput();
                    } else {
                        console.error('Lỗi khi lấy danh sách quận/huyện:', xhrDistrict.status, xhrDistrict.statusText);
                    }
                }
            };

            xhrDistrict.open('GET', './pages/main/get_locations.php?province_id=' + provinceId, true);
            xhrDistrict.send();
        });

        // Khi chọn quận/huyện, load danh sách xã/phường tương ứng
        districtSelect.addEventListener('change', function() {
            var districtId = this.value;

            // Không có giá trị chọn (disabled)
            if (!districtId) {
                wardSelect.innerHTML = '<option value="" selected disabled>Chọn xã/phường</option>';
                wardSelect.disabled = true;
                return;
            }

            // Gửi yêu cầu AJAX để lấy danh sách xã/phường
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var wards = JSON.parse(xhr.responseText);
                        updateSelect(wardSelect, wards);
                        wardSelect.disabled = false;
                        // Update diachi input when district is selected
                        updateDiachiInput();
                    } else {
                        console.error('Lỗi khi lấy danh sách xã/phường');
                    }
                }
            };

            xhr.open('GET', 'pages/main/get_locations.php?district_id=' + districtId, true);
            xhr.send();
        });

        // Khi chọn xã/phường, update diachi input
        wardSelect.addEventListener('change', function() {
            updateDiachiInput();
        });

        // Hàm cập nhật option cho select
        function updateSelect(selectElement, options) {
            selectElement.innerHTML = '<option value="" selected disabled>Chọn</option>';

            if (Array.isArray(options)) {
                options.forEach(function(option) {
                    var optionElement = document.createElement('option');
                    optionElement.value = option.id;
                    optionElement.textContent = option.name;
                    selectElement.appendChild(optionElement);
                });
            } else if (options instanceof Object) {
                // Xử lý trường hợp options là một đối tượng (tỉnh)
                var optionElement = document.createElement('option');
                optionElement.value = options.id;
                optionElement.textContent = options.name;
                selectElement.appendChild(optionElement);
            } else {
                console.error('Dữ liệu không hợp lệ');
            }
        }

        // Hàm cập nhật giá trị cho input diachi
        function updateDiachiInput() {
            var selectedProvince = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';
            var selectedDistrict = districtSelect.options[districtSelect.selectedIndex]?.text || '';
            var selectedWard = wardSelect.options[wardSelect.selectedIndex]?.text || '';

            var fullAddress = selectedWard + ', ' + selectedDistrict + ', ' + selectedProvince;

            diachiInput.value = fullAddress;
        }
    });
</script>