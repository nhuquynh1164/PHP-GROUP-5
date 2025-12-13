<?php
    include_once('./model/category.classes.php');
    $Category = new Category();

    // Lấy trang
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    // Xóa danh mục
   if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if ($category->hasChildCategory($id)) {
        echo "<script>
            alert('Không thể xóa danh mục vì còn danh mục con!');
            window.location.href='admin.php?quanly=danhmuc';
        </script>";
    } else {
        $category->deleteCategoryById($id);
        echo "<script>
            alert('Xóa danh mục thành công');
            window.location.href='admin.php?quanly=danhmuc';
        </script>";
    }
}


    // LẤY TOÀN BỘ CATEGORY → map ID => NAME
    $allCategories = $Category->getCategorys();
    $categoryMap = [];
    foreach ($allCategories as $cat) {
        $categoryMap[$cat['id_category']] = $cat['name_category'];
    }
?>

<div class="btn-insert">
    <a href="?quanly=admin&action=insertCategory" class="btn">Thêm + </a>
</div>

<div class="search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="search" id="search-category"
           placeholder="Nhập tên hoặc ID Danh mục để tìm kiếm"/>
</div>

<table>
    <thead>
        <tr>
            <td>ID Danh mục</td>
            <td>Image</td>
            <td>Tên</td>
            <td>Vị trí</td>
            <td>Danh mục cha</td>
            <td>Tùy chỉnh</td>
        </tr>
    </thead>

    <tbody id="result">
        <?php
            $CategoryPerPage = 5;
            $countCategorys = $Category->getCountCategory();
            $countPage = ceil($countCategorys / $CategoryPerPage);
            $start = ($page -1) * $CategoryPerPage;
            $categoryList = $Category->getCategorysLimit($start,$CategoryPerPage);

            foreach($categoryList as $row_category ) {
                $parentId = $row_category['parent_id'];
                $parentName = ($parentId == 0)
                    ? 'Danh mục gốc'
                    : ($categoryMap[$parentId] ?? 'Không xác định');
        ?>
        <tr>
            <td><?php echo $row_category['id_category'] ?></td>

            <td style="width: 10%;">
                <img src="<?php echo $row_category['img_category'] ?>"
                     width="100%" height="120" style="object-fit:cover;">
            </td>

            <td><?php echo $row_category['name_category'] ?></td>

            <td><?php echo $row_category['order'] ?></td>

            <td>
                <?php echo $parentName ?>
            </td>

            <td>
                <a href="?quanly=admin&action=changeCategory&id_danhmuc=<?php echo $row_category['id_category'] ?>"
                   class="status pending">Sửa</a>

                <a href="?quanly=admin&action=manageCategory&delete_danhmuc=<?php echo $row_category['id_category'] ?>"
                   class="status return"
                   onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<div class="pagination">
<?php
    for($i = 1; $i <= $countPage; $i++) {
?>
    <div class="item">
        <input
    type="radio"
    name="nav"
    id="input-<?php echo $i ?>"
    class="input-page"
    <?php echo $i == $page ? 'checked': '' ?>
    onclick="window.location='?quanly=admin&action=manageCategory&page=<?php echo $i ?>'"
/>

        <label for="input-<?php echo $i ?>"
               class="button button-<?php echo $i ?>">
            <?php echo $i ?>
        </label>
    </div>
<?php } ?>
</div>
