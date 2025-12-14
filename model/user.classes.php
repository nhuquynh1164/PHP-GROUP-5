<?php
require_once __DIR__ . '/db.classes.php';

class User extends DB {


    /* =======================
       GET USER
    ======================= */
    public function getUsers() {
        $sql = "SELECT * FROM user ORDER BY id_user DESC";
        return $this->connect()->query($sql)->fetchAll();
    }

    // ✅ SỬA CHUẨN
    public function getUserById($id_user) {
        $sql = "SELECT * FROM user WHERE id_user = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserId($id) {
        $sql = "SELECT * FROM user WHERE id_user = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function getUsersLimit($start,$count) {
        $sql = "SELECT * FROM user ORDER BY id_user DESC LIMIT $start,$count";
        return $this->connect()->query($sql)->fetchAll();
    }

    public function countUsers() {
        return $this->connect()->query("SELECT * FROM user WHERE user_role = 0")->rowCount();
    }

    public function countAdmins() {
        return $this->connect()->query("SELECT * FROM user WHERE user_role = 1")->rowCount();
    }

    public function getCountUsers() {
        return $this->connect()->query("SELECT * FROM user")->rowCount();
    }

    //LỌC THEO NGÀY TẠO TÀI KHOẢN
public function filterUsers($keyword = '', $day = '', $to_date = null, $start = 0, $limit = 5)
{
    // ⚠️ ĐÚNG TÊN BẢNG
    $sql = "SELECT * FROM user WHERE 1=1";
    $params = [];

    /* ===== SEARCH KEYWORD ===== */
    if (!empty($keyword)) {
        $sql .= " AND (
            user_name LIKE :kw
            OR user_email LIKE :kw
            OR user_phone LIKE :kw
        )";
        $params[':kw'] = '%' . $keyword . '%';
    }

    /* ===== FILTER 1 DAY ===== */
    if (!empty($day)) {
        $sql .= " AND DATE(created_at) = :day";
        $params[':day'] = $day;
    }

    /* ===== ORDER + LIMIT (KHÔNG BIND LIMIT) ===== */
    $start = (int)$start;
    $limit = (int)$limit;
    $sql .= " ORDER BY created_at DESC LIMIT $start, $limit";

    // ⚠️ DÙNG connect()
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    /* =======================
       INSERT USER
    ======================= */
    public function insertUser($fullName,$userName,$password,$phone,$address,$email,$role) {
    $sql = "INSERT INTO user
        (user_name, user_phone, user_address, user_email,
         accountName_user, user_password, user_role)
        VALUES (?,?,?,?,?,?,?)";

    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([
        $fullName,
        $phone,
        $address,
        $email,
        $userName,
        $password, // plaintext
        $role
    ]);
}

    /* =======================
       UPDATE USER
    ======================= */
    public function updateUser($fullName,$userName,$password,$phone,$address,$email,$role,$id_user) {
        $sql = "UPDATE user SET
                user_name=?,
                user_phone=?,
                user_address=?,
                user_email=?,
                accountName_user=?,
                user_password=?,
                user_role=?
                WHERE id_user=?";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$fullName,$phone,$address,$email,$userName,$password,$role,$id_user]);

        header("Location:index.php?quanly=admin&action=manageUser");
    }

    /* =======================
       DELETE USER
    ======================= */
    public function deleteUser($id) {
        $stmt = $this->connect()->prepare("DELETE FROM user WHERE id_user=?");
        $stmt->execute([$id]);
    }

    /* =======================
       SEARCH USER
    ======================= */
    public function searchUser($keyword, $page, $limit) {

        $start = ($page - 1) * $limit;
        $kw = "%$keyword%";

        $sql = "SELECT * FROM user
                WHERE user_name LIKE :kw
                   OR user_email LIKE :kw
                   OR user_phone LIKE :kw
                   OR user_address LIKE :kw
                   OR id_user LIKE :kw";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':kw', $kw);
        $stmt->execute();
        $count = $stmt->rowCount();

        $sql .= " LIMIT $start,$limit";
        $stmt2 = $this->connect()->prepare($sql);
        $stmt2->bindParam(':kw', $kw);
        $stmt2->execute();

        return [
            'countTotalUser' => $count,
            'data' => $stmt2->fetchAll()
        ];
    }

    /* =======================
       EXPORT USER
    ======================= */
    public function exportUsers() {
        $sql = "SELECT id_user,user_name,user_email,user_phone,user_role,created_at FROM user";
        return $this->connect()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =======================
       IMPORT USER
    ======================= */
    public function importUserFromCSV($fileTmp) {

        $file = fopen($fileTmp, 'r');
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            $sql = "INSERT INTO user
                    (user_name,user_email,user_phone,user_role,created_at)
                    VALUES (?,?,?,?,?)";

            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                $row[5]
            ]);
        }
        fclose($file);
    }
}
