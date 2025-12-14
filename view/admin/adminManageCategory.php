<?php
include_once('./model/category.classes.php');
$Category = new Category();

/* ================== PHÂN TRANG ================== */
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$CategoryPerPage = 5;

/* ================== XÓA DANH MỤC ================== */
if (isset($_GET['delete_danhmuc'])) {
    $id_danhmuc = (int)$_GET['delete_danhmuc'];

    if ($Category->hasChildCategory($id_danhmuc)) {
        echo "<script>
            alert('Không thể xóa danh mục cha vì vẫn còn danh mục con!');
            window.location.href='?quanly=admin&action=manageCategory';
        </script>";
        exit;
    }

    $Category->deleteCategory($id_danhmuc);
    echo "<script>
        alert('Xóa danh mục thành công');
        window.location.href='?quanly=admin&action=manageCategory';
    </script>";
    exit;
}

/* ================== SEARCH / LOAD DATA ================== */
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {

    // CÓ TÌM KIẾM
    $result = $Category->searchCategory($search, $page, $CategoryPerPage);
    $categoryList   = $result['data'];
    $countCategorys = $result['countTotalCategory'];

} else {

    // KHÔNG TÌM KIẾM
    $countCategorys = $Category->getCountCategory();
    $start = ($page - 1) * $CategoryPerPage;
    $categoryList = $Category->getCategorysLimit($start, $CategoryPerPage);
}

$countPage = ceil($countCategorys / $CategoryPerPage);
?>

<div class="btn-insert">
    <a href="?quanly=admin&action=insertCategory" class="btn">Thêm +</a>
    <a href="?quanly=admin&action=manageCategory" class="btn">Reset</a>
</div>

<form method="get" class="search-box">
    <input type="hidden" name="quanly" value="admin">
    <input type="hidden" name="action" value="manageCategory">

    <i class="fa-solid fa-magnifying-glass"></i>
    <input
        type="text"
        name="search"
        value="<?php echo htmlspecialchars($search); ?>"
        placeholder="Nhập tên hoặc ID Danh mục để tìm kiếm"
    />
</form>

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

    <tbody>
        <?php foreach ($categoryList as $row_category) { ?>
        <tr>
            <td><?php echo $row_category['id_category']; ?></td>

            <td style="width:10%">
                <img src="<?php echo $row_category['img_category']; ?>" width="100%" height="120" style="object-fit:cover;">
            </td>

            <td><?php echo $row_category['name_category']; ?></td>
            <td><?php echo $row_category['order']; ?></td>

            <td>
                <?php
                    if ($row_category['parent_id'] == 0) {
                        echo 'Danh mục gốc';
                    } else {
                        echo $Category->getCategoryNameById($row_category['parent_id']);
                    }
                ?>
            </td>

            <td>
                <a href="?quanly=admin&action=changeCategory&id_danhmuc=<?php echo $row_category['id_category']; ?>" class="status pending">
                    Sửa
                </a>
                <a
                    href="?quanly=admin&action=manageCategory&delete_danhmuc=<?php echo $row_category['id_category']; ?>"
                    class="status return"
                    onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')"
                >
                    Xóa
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<div class="pagination">
<?php for ($i = 1; $i <= $countPage; $i++) { ?>
    <div class="item">
        <input
            type="radio"
            name="nav"
            id="input-<?php echo $i; ?>"
            class="input-page"
            <?php echo ($i == $page) ? 'checked' : ''; ?>
            onclick="window.location='?quanly=admin&action=manageCategory&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>'"
        />
        <label for="input-<?php echo $i; ?>" class="button button-<?php echo $i; ?>">
            <?php echo $i; ?>
        </label>
    </div>
<?php } ?>
</div>
