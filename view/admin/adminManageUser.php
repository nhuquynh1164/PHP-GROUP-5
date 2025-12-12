<?php
include_once('./model/user.classes.php');
$User = new User();

$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Delete user
if(isset($_GET['delete_user'])) {
    $User->deleteUser($_GET['delete_user']);
}

/* =======================
   ⭐ Hàm che mật khẩu (mask)
   ======================= */
function maskPassword($password) {
    $len = strlen($password);
    if ($len <= 3) return "***";
    return substr($password, 0, 3) . str_repeat("*", $len - 3);
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
.user-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
}

.user-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.search-input {
    height: 38px;
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 0 12px;
    min-width: 250px;
}

/* BUTTONS */
.btn {
    padding: 7px 16px;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    font-size: 14px;
    border: none;
}

.btn-search { background: #4ea3ff; }
.btn-reset { background: #909090; }

.btn-search:hover,
.btn-reset:hover {
    opacity: .85;
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

/* ACTION BUTTONS */
.btn-edit {
    background: #4ea3ff;
    color: #fff;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration:none;
}

.btn-delete {
    background: #ff4c4c;
    color: #fff;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration:none;
}
.btn-edit:hover, .btn-delete:hover { opacity: .9; }

/* Pagination */
.pagination {
    margin-top: 18px;
    text-align: center;
}

.pagination .page-number {
    display: inline-block;
    padding: 8px 12px;
    background: #eee;
    margin: 0 4px;
    border-radius: 6px;
    cursor: pointer;
}

.pagination .active {
    background: #4ea3ff;
    color: #fff;
}
</style>

<div class="table-wrapper">

    <div class="user-topbar">
        <h2 style="margin:0; color:#c59b51;">Table User</h2>

        <!-- ⭐ Search + Buttons + Add New -->
        <div class="user-actions">
            <input type="text" 
                   id="search-user" 
                   class="search-input" 
                   placeholder="Tìm theo tên / email / sđt / địa chỉ / ID"
                   value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">

            <button class="btn btn-search" onclick="searchUser()">Search</button>
            <button class="btn btn-reset" onclick="resetSearch()">Reset</button>

            <a href="?quanly=admin&action=insertUser" 
               class="btn-edit" 
               style="background:#3ecf63;">+ Add New</a>
        </div>
    </div>

    <?php
        $userPerPage = 5;

        if(isset($_GET['keyword']) && $_GET['keyword'] !== "") {

            $keyword = $_GET['keyword'];
            $searchResult = $User->searchUser($keyword, $page, $userPerPage);

            $countUsers = $searchResult['countTotalUser']; 
            $userList  = $searchResult['data'];

            $countPage = ceil($countUsers / $userPerPage);

        } else {
            $countUsers = $User->getCountUsers();
            $countPage = ceil($countUsers / $userPerPage);
            $start = ($page - 1) * $userPerPage;
            $userList = $User->getUsersLimit($start, $userPerPage);
        }
    ?>

    <table class="modern-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Tài khoản</th>
                <th>Mật khẩu</th>
                <th>Role</th>
                <th>Điểm</th>
                <th style="text-align:center;">Action</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach($userList as $user): ?>
            <tr>
                <td><?= $user['id_user'] ?></td>
                <td><?= $user['user_name'] ?></td>
                <td><?= $user['user_email'] ?></td>
                <td><?= $user['user_phone'] ?></td>
                <td><?= $user['user_address'] ?></td>
                <td><?= $user['accountName_user'] ?></td>
                <td><?= maskPassword($user['user_password']) ?></td>
                <td><?= $user['user_role'] == '1' ? 'Admin' : 'User' ?></td>
                <td><?= $user['point_available'] ?></td>

                <td style="text-align:center;">
                    <a class="btn-edit" 
                       href="?quanly=admin&action=changeUser&id_user=<?= $user['id_user'] ?>">✏</a>

                    <a class="btn-delete"
                       onclick="return confirm('Xóa user này?')"
                       href="?quanly=admin&action=manageUser&delete_user=<?= $user['id_user'] ?>">🗑</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php for($i = 1; $i <= $countPage; $i++): ?>
            <a href="?quanly=admin&action=manageUser&page=<?= $i ?>&keyword=<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>"
               class="page-number <?= $i == $page ? 'active' : '' ?>">
               <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

</div>

<script>
function searchUser() {
    let keyword = document.getElementById("search-user").value;
    window.location.href = "?quanly=admin&action=manageUser&keyword=" + keyword;
}

function resetSearch() {
    window.location.href = "?quanly=admin&action=manageUser";
}
</script>
