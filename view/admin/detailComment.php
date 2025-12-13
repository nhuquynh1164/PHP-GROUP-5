<?php
include_once('./model/comment.classes.php');
$Comment = new Comment();

if (!isset($_GET['id_comment'])) {
    echo "<p>Không tìm thấy bình luận</p>";
    return;
}

$id_comment = $_GET['id_comment'];
$comment = $Comment->getCommentById($id_comment);

if (!$comment) {
    echo "<p>Dữ liệu không tồn tại</p>";
    return;
}

$createdAt = !empty($comment['created_at'])
    ? date('d/m/Y H:i', strtotime($comment['created_at']))
    : 'Không xác định';
?>

<style>
.detail-box {
    background: #fff;
    padding: 22px;
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
}
.detail-box h3 {
    margin-bottom: 15px;
    color: #c59b51;
}
.detail-row {
    margin-bottom: 10px;
}
.detail-label {
    font-weight: 600;
    display: inline-block;
    width: 140px;
}
.comment-content {
    margin-top: 15px;
    background: #f9f9f9;
    padding: 14px;
    border-radius: 8px;
}
.back-btn {
    margin-top: 20px;
    display: inline-block;
    background: #909090;
    color: #fff;
    padding: 8px 18px;
    border-radius: 8px;
    text-decoration: none;
}
</style>

<div class="detail-box">
    <h3>Chi tiết bình luận</h3>

    <div class="detail-row">
        <span class="detail-label">ID bình luận:</span>
        <?= $comment['id_comment'] ?>
    </div>

    <div class="detail-row">
        <span class="detail-label">Người dùng:</span>
        <?= $comment['user_name'] ?>
    </div>

    <div class="detail-row">
        <span class="detail-label">Sản phẩm:</span>
        <?= $comment['title_product'] ?>
    </div>

    <div class="detail-row">
        <span class="detail-label">Ngày tạo:</span>
        <?= $createdAt ?>
    </div>

    <h4 style="margin-top:20px;">Nội dung bình luận</h4>
    <div class="comment-content">
        <?= nl2br($comment['comment_content']) ?>
    </div>

    <a class="back-btn" href="?quanly=admin&action=manageComment">
        ← Quay lại danh sách
    </a>
</div>
