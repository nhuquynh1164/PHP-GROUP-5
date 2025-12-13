<?php
require_once __DIR__ . '/../../model/user.classes.php';
$User = new User();

$page  = $_GET['page'] ?? 1;
$limit = 5;
$start = ($page - 1) * $limit;

/* ===== LẤY GIÁ TRỊ FILTER ===== */
$keyword = $_GET['keyword'] ?? '';
$day     = $_GET['day'] ?? ''; // ✅ CHỈ 1 NGÀY

/* ================= DELETE USER ================= */
if (isset($_GET['delete_user'])) {
    $User->deleteUser($_GET['delete_user']);
    header("Location: ?quanly=admin&action=manageUser");
    exit;
}

/* ================= IMPORT CSV ================= */
if (isset($_POST['import_user'])) {
    if (!empty($_FILES['csv_file']['tmp_name'])) {
        $User->importUserFromCSV($_FILES['csv_file']['tmp_name']);
        header("Location: ?quanly=admin&action=manageUser");
        exit;
    }
}

/* ================= LOAD DATA ================= */
if ($keyword || $day) {
    // ✅ lọc theo keyword + 1 ngày
    $userList = $User->filterUsers($keyword, $day, null, $start, $limit);
} else {
    $userList = $User->getUsersLimit($start, $limit);
}
?>

<style>
.table-wrapper{
    background:#fff;
    padding:22px;
    border-radius:14px;
    box-shadow:0 6px 20px rgba(0,0,0,.06)
}
.user-topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px
}
.user-actions{
    display:flex;
    gap:8px;
    align-items:center;
    flex-wrap:wrap
}
.user-actions input{
    padding:7px 10px;
    border-radius:6px;
    border:1px solid #ccc
}
.btn{
    padding:7px 14px;
    border-radius:8px;
    color:#fff;
    border:none;
    cursor:pointer;
    text-decoration:none
}
.btn-search{background:#4ea3ff}
.btn-reset{background:#999}
.btn-add{background:#2ecc71}
.btn-export{background:#f39c12}
.btn-import{background:#8e44ad}
table{width:100%;border-collapse:collapse}
th,td{padding:12px;border-bottom:1px solid #eee}
thead{background:#f7f7f7}
tbody tr:hover{background:#fafafa}
.action-icons a{margin-right:8px}
</style>

<div class="table-wrapper">

<div class="user-topbar">
    <h2 style="color:#c59b51">Table User</h2>

    <div class="user-actions">
        <!-- SEARCH TEXT -->
        <input id="kw" placeholder="Search name / email / phone"
               value="<?= htmlspecialchars($keyword) ?>">

        <!-- FILTER 1 DAY -->
        <input type="date" id="day" value="<?= $day ?>">

        <button class="btn btn-search" onclick="search()">Search</button>
        <button class="btn btn-reset" onclick="reset()">Reset</button>

        <a href="./view/admin/exportUsers.php"
           class="btn btn-export"
           target="_blank">Export CSV</a>

        <form method="POST" enctype="multipart/form-data" style="display:inline">
            <label class="btn btn-import">
                Import CSV
                <input type="file" name="csv_file" hidden onchange="this.form.submit()">
            </label>
            <input type="hidden" name="import_user">
        </form>

        <a href="?quanly=admin&action=insertUser" class="btn btn-add">+ Add</a>
    </div>
</div>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Role</th>
    <th>Created At</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php foreach ($userList as $u): ?>
<tr>
    <td><?= $u['id_user'] ?></td>
    <td><?= $u['user_name'] ?></td>
    <td><?= $u['user_email'] ?></td>
    <td><?= $u['user_phone'] ?></td>
    <td><?= $u['user_role'] == 1 ? 'Admin' : 'User' ?></td>
    <td><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>

    <td class="action-icons">
        <a href="?quanly=admin&action=detailUser&id_user=<?= $u['id_user'] ?>">👁</a>
        <a href="?quanly=admin&action=changeUser&id_user=<?= $u['id_user'] ?>">✏</a>
        <a onclick="return confirm('Xóa user này?')"
           href="?quanly=admin&action=manageUser&delete_user=<?= $u['id_user'] ?>">🗑</a>
    </td>
</tr>
<?php endforeach ?>
</tbody>
</table>

</div>

<script>
function search(){
    let url = '?quanly=admin&action=manageUser';

    if(kw.value.trim()){
        url += '&keyword=' + encodeURIComponent(kw.value);
    }

    if(day.value){
        url += '&day=' + day.value;
    }

    location.href = url;
}

function reset(){
    location.href='?quanly=admin&action=manageUser';
}
</script>

