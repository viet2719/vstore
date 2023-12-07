<?php
include('../../admincp/config/config.php');

if (isset($_GET['province_id'])) {
    $provinceId = intval($_GET['province_id']);

    // Truy vấn để lấy danh sách quận/huyện dựa trên tỉnh/thành phố được chọn
    $districtQuery = "SELECT district_id as id, name FROM district WHERE province_id = $provinceId";
    $districtResult = mysqli_query($mysqli, $districtQuery);

    // Kiểm tra xem có kết quả hay không
    if ($districtResult) {
        $districts = [];

        // Lặp qua kết quả và thêm vào mảng districts
        while ($districtRow = mysqli_fetch_assoc($districtResult)) {
            $districts[] = $districtRow;
        }

        // Trả về dữ liệu dưới dạng JSON
        echo json_encode($districts);
    } else {
        // Trả về lỗi nếu có vấn đề khi thực hiện truy vấn
        http_response_code(500);
        echo json_encode(['error' => 'Lỗi khi truy vấn dữ liệu quận/huyện.']);
    }
}elseif (isset($_GET['district_id'])) {
    $districtId = intval($_GET['district_id']);

    // Truy vấn để lấy danh sách xã/phường dựa trên quận/huyện được chọn
    $wardQuery = "SELECT wards_id as id, name FROM wards WHERE district_id = $districtId";
    $wardResult = mysqli_query($mysqli, $wardQuery);

    // Kiểm tra xem có kết quả hay không
    if ($wardResult) {
        $wards = [];

        // Lặp qua kết quả và thêm vào mảng wards
        while ($wardRow = mysqli_fetch_assoc($wardResult)) {
            $wards[] = $wardRow;
        }

        // Trả về dữ liệu dưới dạng JSON
        echo json_encode($wards);
    } else {
        // Trả về lỗi nếu có vấn đề khi thực hiện truy vấn
        http_response_code(500);
        echo json_encode(['error' => 'Lỗi khi truy vấn dữ liệu xã/phường.']);
    }
}
 else {
    // Nếu không có tham số province_id, lấy danh sách tỉnh/thành phố
    $provinceQuery = "SELECT province_id as id, name FROM province";
    $provinceResult = mysqli_query($mysqli, $provinceQuery);

    // Kiểm tra xem có kết quả hay không
    if ($provinceResult) {
        $provinces = [];

        // Lặp qua kết quả và thêm vào mảng provinces
        while ($provinceRow = mysqli_fetch_assoc($provinceResult)) {
            $provinces[] = $provinceRow;
        }

        // Trả về dữ liệu dưới dạng JSON
        echo json_encode($provinces);
    } else {
        // Trả về lỗi nếu có vấn đề khi thực hiện truy vấn
        http_response_code(500);
        echo json_encode(['error' => 'Lỗi khi truy vấn dữ liệu tỉnh/thành phố.']);
    }
}

// Đóng kết nối CSDL
mysqli_close($mysqli);?>
