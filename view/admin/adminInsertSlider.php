<?php
include_once('./model/slider.classes.php');
$Slider = new Slider();

if (isset($_POST["submit_insertSlider"])) {
    if (!empty($_FILES['image']['name']) && isset($_POST["order"])) {
        $image = $_FILES['image'];
        $order = $_POST["order"];

        // ✅ GỌI ĐÚNG HÀM
        $Slider->insertSliderSimple($image, $order);
    }
}
?>



<div class="form">
    <form action="" method="POST" class="form container" enctype="multipart/form-data">
        <h3 class="heading">Thêm Slider</h3>
        <div class="spacer"></div>

        <div class="form-group">
            <label for="image" class="form-label">Ảnh Slider</label>
            <input id="image" name="image" type="file" class="form-control">
        </div>

        <div class="form-group">
            <label for="order" class="form-label">Vị trí ưu tiên</label>
            <input id="order" name="order" type="number" placeholder="1, 2, 3..." class="form-control">
        </div>

        <input class="form-submit" type="submit" value="Lưu" name="submit_insertSlider">
    </form>
</div>
