<?php
// view/admin/detailComment.php

// Nếu không có id_comment thì báo lỗi nhẹ nhàng
if (!isset($_GET['id_comment']) || empty($_GET['id_comment'])) {
    echo '<div style="padding:20px;background:#fff;border-radius:8px;">';
    echo '<h3>Không tìm thấy bình luận.</h3>';
    echo '<a href="?quanly=admin&action=manageComment" class="btn">Quay lại</a>';
    echo '</div>';
    return;
}

include_once('./model/comment.classes.php');
$Comment = new Comment();

$id_comment = (int) $_GET['id_comment'];

// Lấy chi tiết (hàm trong comment.classes.php đã JOIN user + product)
$detail = $Comment->getCommentById($id_comment);

if (!$detail) {
    echo '<div style="padding:20px;background:#fff;border-radius:8px;">';
    echo '<h3>Bình luận không tồn tại.</h3>';
    echo '<a href="?quanly=admin&action=manageComment" class="btn">Quay lại</a>';
    echo '</div>';
    return;
}
?>

<style>
.detail-card {
    background: #fff;
    padding: 22px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    max-width: 900px;
    margin: 10px 0;
    font-family: "Lato", Arial, sans-serif;
}
.detail-row {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
    align-items: center;
}
.detail-label {
    min-width: 160px;
    color: #7b6b4a;
    font-weight: 700;
}
.detail-value {
    flex: 1;
    color: #222;
    background: #fbfbfb;
    padding: 10px 12px;
    border-radius: 8px;
}
.small-meta {
    color: #666;
    font-size: 13px;
}
.back-btn {
    display: inline-block;
    margin-top: 18px;
    padding: 8px 14px;
    background: #007bff;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
}
.status-badge {
    display:inline-block;
    padding:6px 10px;
    border-radius:8px;
    font-weight:700;
}
.status-approved { background:#e6ffef; color:#0a8a47; }
.status-pending  { background:#fff2f0; color:#b2302f; }
</style>

<div class="detail-card">
    <h2 style="margin-top:0;color:#c89b5d;">Chi tiết bình luận</h2>

    <div class="detail-row">
        <div class="detail-label">ID Comment:</div>
        <div class="detail-value"><?= htmlspecialchars($detail['id_comment']) ?></div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Người bình luận:</div>
        <div class="detail-value"><?= htmlspecialchars($detail['user_name'] ?? '—') ?></div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Sản phẩm:</div>
        <div class="detail-value"><?= htmlspecialchars($detail['title_product'] ?? '—') ?></div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Nội dung:</div>
        <div class="detail-value"><?= nl2br(htmlspecialchars($detail['comment_content'])) ?></div>
    </div>

    <div class="detail-row">
        <div class="detail-label">Trạng thái:</div>
        <div class="detail-value small-meta">
            <?php if((int)$detail['accept'] === 1): ?>
                <span class="status-badge status-approved">Đã duyệt</span>
            <?php else: ?>
                <span class="status-badge status-pending">Chưa duyệt</span>
            <?php endif; ?>
        </div>
    </div>

    <a class="back-btn" href="?quanly=admin&action=manageComment">← Quay lại</a>
</div>
