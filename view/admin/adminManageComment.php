<?php
include_once('./model/comment.classes.php');
$Comment = new Comment();

// Delete
if(isset($_GET['delete_comment'])) {
    $Comment->deleteComment($_GET['delete_comment']);
}
?>

<style>
/* ======= TABLE WRAPPER ======= */
.table-wrapper {
    background: #fff;
    padding: 22px;
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    font-family: "Lato", Arial, sans-serif;
}

/* ======= TOP ACTION BAR ======= */
.comment-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
}

.comment-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.search-input {
    height: 38px;
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 0 12px;
}

/* ======= TABLE ======= */
table.modern-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
}

table.modern-table thead tr {
    background: #f7f7f7;
    border-bottom: 2px solid #e0e0e0;
}

table.modern-table th, 
table.modern-table td {
    padding: 14px 12px;
    text-align: left;
}

table.modern-table tbody tr {
    border-bottom: 1px solid #eee;
    transition: 0.25s;
}

table.modern-table tbody tr:hover {
    background: #fafafa;
}

/* ======= BUTTONS ======= */
.btn-search {
    background: #4ea3ff;
    color: white;
    padding: 7px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}

.btn-reset {
    background: #909090;
    color: white;
    padding: 7px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}

.status.info {
    background: #007bff;
    color: #fff;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
}

.status.return {
    background: #ff4c4c;
    color: #fff;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
}
</style>

<div class="table-wrapper">

    <div class="comment-topbar">
        <h2 style="margin:0; color:#c59b51;">Quản lý bình luận</h2>

        <div class="comment-actions">
            <input type="text" 
                   id="search-comment"
                   class="search-input"
                   placeholder="Tìm theo user / sản phẩm / nội dung / ID"
                   value="<?= $_GET['keyword'] ?? '' ?>">

            <button class="btn-search" onclick="searchComment()">Search</button>
            <button class="btn-reset" onclick="resetSearch()">Reset</button>
        </div>
    </div>

    <?php
        if(isset($_GET['keyword']) && $_GET['keyword'] !== "") {
            $commentList = $Comment->searchComments($_GET['keyword']);
        } else {
            $commentList = $Comment->getCommentsFull();
        }
    ?>

    <table class="modern-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Sản phẩm</th>
                <th>Nội dung</th>
                <th>Ngày comment</th>
                <th style="text-align:center;">Hành động</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($commentList as $c): ?>
            <tr>
                <td><?= $c['id_comment'] ?></td>
                <td><?= $c['user_name'] ?></td>
                <td><?= $c['title_product'] ?></td>
                <td><?= $c['comment_content'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>

                <td style="text-align:center;">
                    <a class="status info"
                       href="?quanly=admin&action=detailComment&id_comment=<?= $c['id_comment'] ?>">
                        Xem
                    </a>

                    <a class="status return"
                       onclick="return confirm('Xóa bình luận này?')"
                       href="?quanly=admin&action=manageComment&delete_comment=<?= $c['id_comment'] ?>">
                        Xóa
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script>
function searchComment() {
    let keyword = document.getElementById("search-comment").value;
    window.location.href = "?quanly=admin&action=manageComment&keyword=" + keyword;
}
function resetSearch() {
    window.location.href = "?quanly=admin&action=manageComment";
}
</script>
