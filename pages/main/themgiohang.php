<?php
	session_start();
	include('../../admincp/config/config.php');
	//them so luong
	
    if (isset($_GET['cong'])) {
        $id = $_GET['cong'];
        $size = $_GET['size']; // Lấy thông tin về size từ URL
    
        if (isset($_SESSION['id_khachhang'])) {
            // Nếu có id_khachhang, sử dụng dữ liệu từ tbl_giohang
            $id_khachhang = $_SESSION['id_khachhang'];
            $sql_check_giohang = "SELECT * FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id' AND size = '$size'";
            $result_check_giohang = mysqli_query($mysqli, $sql_check_giohang);
    
            if ($result_check_giohang && mysqli_num_rows($result_check_giohang) > 0) {
                $sql_update_giohang = "UPDATE tbl_giohang SET soluong = soluong + 1 WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id' AND size = '$size'";
                mysqli_query($mysqli, $sql_update_giohang);
            }
        } elseif (isset($_SESSION['cart'])) {
            // Sử dụng dữ liệu từ $_SESSION['cart']
            foreach ($_SESSION['cart'] as &$cart_item) {
                if ($cart_item['id'] == $id && $cart_item['size'] == $size) {
                    $cart_item['soluong'] += 1;
                    if ($cart_item['soluong'] > 9) {
                        $cart_item['soluong'] = 9;
                    }
                }
            }
        }
    
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    
    if (isset($_GET['tru'])) {
        $id = $_GET['tru'];
        $size = $_GET['size']; // Lấy thông tin về size từ URL
    
        if (isset($_SESSION['id_khachhang'])) {
            // Nếu có id_khachhang, sử dụng dữ liệu từ tbl_giohang
            $id_khachhang = $_SESSION['id_khachhang'];
            $sql_check_giohang = "SELECT * FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id' AND size = '$size'";
            $result_check_giohang = mysqli_query($mysqli, $sql_check_giohang);
    
            if ($result_check_giohang && mysqli_num_rows($result_check_giohang) > 0) {
                $sql_update_giohang = "UPDATE tbl_giohang SET soluong = soluong - 1 WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id' AND size = '$size'";
                mysqli_query($mysqli, $sql_update_giohang);
            }
        } elseif (isset($_SESSION['cart'])) {
            // Sử dụng dữ liệu từ $_SESSION['cart']
            foreach ($_SESSION['cart'] as &$cart_item) {
                if ($cart_item['id'] == $id && $cart_item['size'] == $size) {
                    $cart_item['soluong'] -= 1;
                    if ($cart_item['soluong'] < 1) {
                        $cart_item['soluong'] = 1;
                    }
                }
            }
        }
    
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    
  // Hàm xóa sản phẩm từ giỏ hàng
// Hàm xóa sản phẩm từ giỏ hàng
function removeProductFromCart($id, $cartData, $id_khachhang = null) {
    foreach ($cartData as $key => $cart_item) {
        // Kiểm tra nếu có id_khachhang và sản phẩm thuộc về khách hàng đó
        if ($id_khachhang && isset($cart_item['id_khachhang']) && $cart_item['id_khachhang'] == $id_khachhang) {
            unset($cartData[$key]);
            break;
        } elseif (!$id_khachhang && $cart_item['id'] == $id) {
            // Kiểm tra nếu không có id_khachhang và sản phẩm không thuộc về khách hàng nào cụ thể
            unset($cartData[$key]);
            break;
        }
    }

    return array_values($cartData);
}

// Xóa sản phẩm từ giỏ hàng
if (isset($_GET['xoa'])) {
    $id = $_GET['xoa'];

    if (isset($_SESSION['id_khachhang'])) {
        // Nếu có id_khachhang, sử dụng dữ liệu từ tbl_giohang
        $id_khachhang = $_SESSION['id_khachhang'];

        if (isset($_SESSION['cart'])) {
            // Sử dụng dữ liệu từ $_SESSION['cart']
            $_SESSION['cart'] = removeProductFromCart($id, $_SESSION['cart'], $id_khachhang);
        }

        // Xóa sản phẩm từ tbl_giohang
        $sql_delete_giohang = "DELETE FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id'";
        mysqli_query($mysqli, $sql_delete_giohang);
    } elseif (isset($_SESSION['cart'])) {
        // Sử dụng dữ liệu từ $_SESSION['cart']
        $_SESSION['cart'] = removeProductFromCart($id, $_SESSION['cart']);
    }

    // Chuyển hướng trở lại trang giỏ hàng
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}


// Xóa sản phẩm từ giỏ hàng trong và chuyển hướng về trang chính
if (isset($_GET['xoa1'])) {
    $id = $_GET['xoa1'];

    if (isset($_SESSION['id_khachhang'])) {
        // Nếu có id_khachhang, sử dụng dữ liệu từ tbl_giohang
        $id_khachhang = $_SESSION['id_khachhang'];
        $sql_check_giohang = "SELECT * FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id'";
        $result_check_giohang = mysqli_query($mysqli, $sql_check_giohang);

        if ($result_check_giohang && mysqli_num_rows($result_check_giohang) > 0) {
            $sql_delete_giohang = "DELETE FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id'";
            mysqli_query($mysqli, $sql_delete_giohang);
        }
    } elseif (isset($_SESSION['cart'])) {
        // Sử dụng dữ liệu từ $_SESSION['cart']
        $_SESSION['cart'] = removeProductFromCart($id, $_SESSION['cart']);
    }

    // Chuyển hướng về trang chính
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

// Xóa toàn bộ giỏ hàng và chuyển hướng trở lại trang giỏ hàng
if (isset($_GET['xoatatca']) && $_GET['xoatatca'] == 1) {
    if (isset($_SESSION['id_khachhang'])) {
        // Nếu có id_khachhang, xóa giỏ hàng từ tbl_giohang
        $id_khachhang = $_SESSION['id_khachhang'];
        $sql_delete_giohang = "DELETE FROM tbl_giohang WHERE id_khachhang = '$id_khachhang'";
        mysqli_query($mysqli, $sql_delete_giohang);
    } elseif (isset($_SESSION['cart'])) {
        // Nếu không có id_khachhang, xóa giỏ hàng từ $_SESSION['cart']
        unset($_SESSION['cart']);
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}


	//them sapham vao gio hang
// Kiểm tra xem người dùng đã đăng nhập hay chưa

if (isset($_POST['themgiohang'])) {
    $id = isset($_GET['idsanpham']) ? $_GET['idsanpham'] : null;

    if (isset($_POST['soluong']) && isset($_POST['kichthuoc'])) {
        $soluong = $_POST['soluong'];
        $size = $_POST['kichthuoc'];

        $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$id' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        $row = mysqli_fetch_array($query);

        if ($row) {
            $giasp = ($row['km'] > 0) ? $row['giasp'] - ($row['giasp'] * ($row['km'] / 100)) : $row['giasp'];

            $new_product = array(
                'tensanpham' => $row['tensanpham'],
                'id' => $id,
                'soluong' => $soluong,
                'giasp' => $giasp,
                'hinhanh' => $row['hinhanh'],
                'masp' => $row['masp'],
                'size' => $size
            );

            // Check if the user is logged in
            if (isset($_SESSION['id_khachhang'])) {
                $id_khachhang = $_SESSION['id_khachhang'];

                // If there is a session cart, transfer data to tbl_giohang
            

                // Continue processing for the current product (added from the form)
                $sql_check_giohang = "SELECT * FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id' AND size = '$size'";
                $query_check_giohang = mysqli_query($mysqli, $sql_check_giohang);
                $num_rows = mysqli_num_rows($query_check_giohang);

                if ($num_rows > 0) {
                    // If the product exists, update the quantity
                    $sql_update_giohang = "UPDATE tbl_giohang SET soluong = soluong + $soluong WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id' AND size = '$size'";
                    mysqli_query($mysqli, $sql_update_giohang);
                } else {
                    // If the product doesn't exist, insert a new record
                    $sql_insert_giohang = "INSERT INTO tbl_giohang (id_khachhang, id_sanpham, soluong, size) VALUES ('$id_khachhang', '$id', '$soluong', '$size')";
                    mysqli_query($mysqli, $sql_insert_giohang);
                }
            } else {
                // If the user is not logged in, store the product in the session
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    $found = false;

                    foreach ($_SESSION['cart'] as &$cart_item) {
                        if ($cart_item['id'] == $id && $cart_item['size'] == $size) {
                            $cart_item['soluong'] += $soluong;
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $_SESSION['cart'][] = $new_product;
                    }
                } else {
                    $_SESSION['cart'][] = $new_product;
                }
            }
        } else {
            echo "Không tìm thấy sản phẩm với ID: " . $id;
        }
 
        // Redirect back to the previous page
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        
    } else {
        echo "Không có giá trị soluong hoặc kichthuoc được gửi từ form.";
    }
}



	
if (isset($_POST['muangay'])) {
    $id = isset($_GET['idsanpham']) ? $_GET['idsanpham'] : null;

    if (isset($_POST['soluong']) && isset($_POST['kichthuoc'])) {
        $soluong = $_POST['soluong'];
        $size = $_POST['kichthuoc'];

        $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$id' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        $row = mysqli_fetch_array($query);

        if ($row) {
            $giasp = ($row['km'] > 0) ? $row['giasp'] - ($row['giasp'] * ($row['km'] / 100)) : $row['giasp'];

            $new_product = array(
                'tensanpham' => $row['tensanpham'],
                'id' => $id,
                'soluong' => $soluong,
                'giasp' => $giasp,
                'hinhanh' => $row['hinhanh'],
                'masp' => $row['masp'],
                'size' => $size
            );

            // Check if the user is logged in
            if (isset($_SESSION['id_khachhang'])) {
                $id_khachhang = $_SESSION['id_khachhang'];

                // If there is a session cart, transfer data to tbl_giohang
              

                // Continue processing for the current product (added from the form)
                $sql_check_giohang = "SELECT * FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id' AND size = '$size'";
                $query_check_giohang = mysqli_query($mysqli, $sql_check_giohang);
                $num_rows = mysqli_num_rows($query_check_giohang);

                if ($num_rows > 0) {
                    // If the product exists, update the quantity
                    $sql_update_giohang = "UPDATE tbl_giohang SET soluong = soluong + $soluong WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id' AND size = '$size'";
                    mysqli_query($mysqli, $sql_update_giohang);
                } else {
                    // If the product doesn't exist, insert a new record
                    $sql_insert_giohang = "INSERT INTO tbl_giohang (id_khachhang, id_sanpham, soluong, size) VALUES ('$id_khachhang', '$id', '$soluong', '$size')";
                    mysqli_query($mysqli, $sql_insert_giohang);
                }
            } else {
                // If the user is not logged in, store the product in the session
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    $found = false;

                    foreach ($_SESSION['cart'] as &$cart_item) {
                        if ($cart_item['id'] == $id && $cart_item['size'] == $size) {
                            $cart_item['soluong'] += $soluong;
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $_SESSION['cart'][] = $new_product;
                    }
                } else {
                    $_SESSION['cart'][] = $new_product;
                }
            }
        } else {
            echo "Không tìm thấy sản phẩm với ID: " . $id;
        }

        // Redirect back to the previous page
        header('Location:../../index.php?quanly=xulythanhtoan');
    } else {
        echo "Không có giá trị soluong hoặc kichthuoc được gửi từ form.";
    }
}

	
	

if(isset($_GET['reorder'])) {
    $orderCode = $_GET['reorder'];


    // Thực hiện truy vấn SQL để lấy thông tin sản phẩm dựa trên mã đơn hàng
    $sql_reorder = "SELECT cd.*, sp.tensanpham, sp.masp, sp.giasp, sp.km, sp.giagockm, sp.soluong, sp.hinhanh, sp.tomtat, sp.tinhtrang, sp.id_danhmuc
                FROM tbl_cart_details cd
                INNER JOIN tbl_sanpham sp ON cd.id_sanpham = sp.id_sanpham
                WHERE cd.code_cart = '$orderCode'";

    $query_reorder = mysqli_query($mysqli, $sql_reorder);

    if (!$query_reorder) {
        die('Query failed: ' . mysqli_error($mysqli));
    }

    if (mysqli_num_rows($query_reorder) > 0) {
        while ($reorderItem = mysqli_fetch_assoc($query_reorder)) {
            $id_khachhang = isset($_SESSION['id_khachhang']) ? $_SESSION['id_khachhang'] : null;
            $id_sanpham = $reorderItem['id_sanpham'];
            $soluong = $reorderItem['soluongmua'];
            $size = $reorderItem['size'];

            // Check if the product exists in tbl_giohang for this user
            $sql_check_giohang = "SELECT * FROM tbl_giohang WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id_sanpham' AND size = '$size'";
            $query_check_giohang = mysqli_query($mysqli, $sql_check_giohang);
            $num_rows = mysqli_num_rows($query_check_giohang);

            if ($num_rows > 0) {
                // If the product exists, update the quantity
                $sql_update_giohang = "UPDATE tbl_giohang SET soluong = soluong + $soluong WHERE id_khachhang = '$id_khachhang' AND id_sanpham = '$id_sanpham' AND size = '$size'";
                $result_update_giohang = mysqli_query($mysqli, $sql_update_giohang);
            
                if (!$result_update_giohang) {
                    die('Update giohang failed: ' . mysqli_error($mysqli));
                }
            } else {
                // If the product doesn't exist, insert a new record
                $sql_insert_giohang = "INSERT INTO tbl_giohang (id_khachhang, id_sanpham, soluong, size) VALUES ('$id_khachhang', '$id_sanpham', '$soluong', '$size')";
                $result_insert_giohang = mysqli_query($mysqli, $sql_insert_giohang);
            
                if (!$result_insert_giohang) {
                    die('Insert giohang failed: ' . mysqli_error($mysqli));
                }
            }
        }

        // Hiển thị thông báo hoặc chuyển hướng người dùng đến trang giỏ hàng
        header('Location:../../index.php?quanly=giohang');
        exit;
    } else {
        echo "Không có sản phẩm nào để reorder từ đơn hàng có mã: $orderCode";
    }
}

?>

    
    

