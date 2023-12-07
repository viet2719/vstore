<?php

    include('../../config/config.php'); //truyền dữ liệu về mysql

    //gọi tên danh mục avf thứ tự từ bên trang thêm sang
    $tenchinhsach = $_POST['tenchinhsach'];

    $hinhanh = $_FILES['hinhanh']['name'];
    $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
    $hinhanh = time().'_'.$hinhanh;
    
    $noidung = $_POST['noidung'];
    $tinhtrang = $_POST['tinhtrang'];
    $id_tenchinhsach = $_POST['id_tenchinhsach'];
    //su li hinh anh
    
    
    //bắt đầu truyền về mysq/
    if(isset($_POST['themchinhsach'])){
        //them
        $sql_them = "INSERT INTO tbl_chinhsach(tenchinhsach,noidung,tinhtrang,id_tenchinhsach) VALUE('".$tenchinhsach ."','".$noidung."','".$tinhtrang."','".$id_tenchinhsach."')";
        mysqli_query($mysqli,$sql_them);
        move_uploaded_file($hinhanh_tmp,'uploads/'.$hinhanh);
        header('location:../../index.php?action=quanlychinhsach&query=them'); //quay lại trang them.php
    }
    
    elseif(isset($_POST['suachinhsach'])){
        //sua
        if(!empty($_FILES['hinhanh']['name'])){
            move_uploaded_file($hinhanh_tmp,'uploads/'.$hinhanh);
            
            $sql_update = "UPDATE tbl_chinhsach SET tenchinhsach='".$tenchinhsach."',noidung='".$noidung."',tinhtrang='".$tinhtrang."',id_tenchinhsach='".$id_tenchinhsach."' WHERE id_chinhsach='$_GET[idchinhsach]'";
            // update xong mowis sua hinh anh
            $sql = "SELECT * FROM tbl_chinhsach WHERE id_chinhsach = '$_GET[idchinhsach]' LIMIT 1";
            $query = mysqli_query($mysqli,$sql);
            while($row = mysqli_fetch_array($query)){
                unlink('./uploads/'.$row['hinhanh']);
            }
        }else{
            $sql_update = "UPDATE tbl_chinhsach SET tenchinhsach='".$tenchinhsach."',noidung='".$noidung."',tinhtrang='".$tinhtrang."',id_tenchinhsach='".$id_tenchinhsach."' WHERE id_chinhsach='$_GET[idchinhsach]'";

        }
        mysqli_query($mysqli,$sql_update);
        header('location:../../index.php?action=quanlychinhsach&query=lietke'); //quay lại trang them.php
    }
    else{
        //xoa
        $id=$_GET['idchinhsach'];
        $sql = "SELECT * FROM tbl_chinhsach WHERE id_chinhsach = '$id' LIMIT 1";
        $query = mysqli_query($mysqli,$sql);
        while($row = mysqli_fetch_array($query)){
            unlink('uploads/'.$row['hinhanh']);
        }
        $sql_xoa = "DELETE FROM tbl_chinhsach WHERE id_chinhsach='".$id."'";
        mysqli_query($mysqli,$sql_xoa);
        header('location:../../index.php?action=quanlychinhsach&query=lietke');
    }
?>