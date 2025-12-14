<?php
include_once('./model/detailProduct.classes.php');
$DetailProduct = new DetailProduct();

/* ====== LẤY ID CHI TIẾT ====== */
if (!isset($_GET['id_chitietSP'])) {
    // Không có ID → quay về dashboard
    header("Location:index.php?quanly=admin");
    exit;
}
$id_chitiet = $_GET['id_chitietSP'];

/* ====== XỬ LÝ SUBMIT ====== */
if (isset($_POST['submit_changeDetailProduct'])) {
    $size = $_POST['size'];
    $price = $_POST['price'];
    $id_sanpham = $_POST['id_sanpham'];

    $DetailProduct->updateDetailProduct(
        $size,
        $price,
        $id_chitiet,
        $id_sanpham
    );
    exit;
}

/* ====== LẤY DATA ====== */
$detailProduct = $DetailProduct->getDetailProductById($id_chitiet);
if (!$detailProduct) {
    header("Location:index.php?quanly=admin");
    exit;
}

$row_detail = $detailProduct[0];
?>

<div class="form">
<form method="POST">
    <h3>Thông tin chi tiết sản phẩm</h3>

    <div class="form-group">
        <label>Giá</label>
        <input type="text" name="price"
               value="<?php echo $row_detail['detail_price'] ?>">
    </div>

    <div class="form-group">
        <label>Size</label>
        <input type="text" name="size"
               value="<?php echo $row_detail['detail_size'] ?>">
    </div>

    <div class="form-group">
        <label>Đã bán</label>
        <input type="text"
               value="<?php echo $row_detail['sold'] ?>" disabled>
    </div>

    <div class="form-group">
        <label>Tồn kho</label>
        <input type="text"
               value="<?php echo $row_detail['inventory'] ?>" disabled>
    </div>

    <div class="form-group">
        <label>Còn lại</label>
        <input type="text"
               value="<?php echo $row_detail['inventory'] - $row_detail['sold'] ?>" disabled>
    </div>

    <input type="hidden" name="id_sanpham"
           value="<?php echo $row_detail['id_product'] ?>">

    <button type="submit" name="submit_changeDetailProduct">
        Lưu
    </button>
</form>
</div>
