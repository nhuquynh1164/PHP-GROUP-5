<?php
include_once(__DIR__ . '/../../model/user.classes.php');
$User = new User();

$id_user = $_GET['id_user'] ?? 0;
$user = $User->getUserById($id_user);

if (!$user) {
    echo '<p style="color:red">User không tồn tại</p>';
    return;
}

function maskPassword($pass){
    return substr($pass, 0, 3) . '********';
}
?>


<style>
.detail-box{
    background:#fff;
    padding:26px;
    border-radius:14px;
    box-shadow:0 6px 20px rgba(0,0,0,.08);
    max-width:700px
}
.detail-row{
    display:flex;
    padding:10px 0;
    border-bottom:1px solid #eee
}
.detail-row label{
    width:180px;
    font-weight:600;
    color:#555
}
.role-admin{color:#e74c3c;font-weight:600}
.role-user{color:#2ecc71;font-weight:600}
.back-btn{
    display:inline-block;
    margin-top:18px;
    padding:9px 18px;
    background:#999;
    color:#fff;
    border-radius:8px;
    text-decoration:none
}
</style>

<div class="detail-box">
<h2 style="color:#c59b51">Chi tiết User</h2>

<div class="detail-row"><label>ID</label><span><?= $user['id_user'] ?></span></div>
<div class="detail-row"><label>Tên</label><span><?= $user['user_name'] ?></span></div>
<div class="detail-row"><label>Email</label><span><?= $user['user_email'] ?></span></div>
<div class="detail-row"><label>SĐT</label><span><?= $user['user_phone'] ?></span></div>
<div class="detail-row"><label>Địa chỉ</label><span><?= $user['user_address'] ?? '—' ?></span></div>
<div class="detail-row"><label>Mật khẩu</label><span><?= maskPassword($user['user_password']) ?></span></div>
<div class="detail-row">
    <label>Role</label>
    <span class="<?= $user['user_role']==1?'role-admin':'role-user' ?>">
        <?= $user['user_role']==1?'Admin':'User' ?>
    </span>
</div>
<div class="detail-row"><label>Điểm</label><span><?= $user['user_point'] ?? 0 ?></span></div>
<div class="detail-row"><label>Ngày tạo</label><span><?= $user['created_at'] ?></span></div>

<a href="?quanly=admin&action=manageUser" class="back-btn">⬅ Quay lại</a>
</div>
