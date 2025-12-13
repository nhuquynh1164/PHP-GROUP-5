<?php 
class Comment extends DB {

    // ⭐ Lấy danh sách comment đầy đủ (JOIN User + Product)
   public function getCommentsFull() {
    $sql = "
        SELECT 
            c.id_comment,
            c.comment_content,
            c.created_at,
            u.user_name,
            p.title_product,
            cat.name_category
        FROM comment c
        JOIN user u ON c.id_user = u.id_user
        JOIN product p ON c.id_product = p.id_product
        JOIN category cat ON p.id_category = cat.id_category
        ORDER BY c.created_at DESC
    ";
    $stmt = $this->connect()->query($sql);
    return $stmt->fetchAll();
}


    public function getCommments() {
        $sql = "SELECT * FROM comment";
        $stmt = $this->connect()->query($sql);
        return $stmt->fetchAll();
    }

    public function countCommmentsDontAccept() {
        $sql = "SELECT * FROM comment WHERE accept = 0";
        $stmt = $this->connect()->query($sql);
        return $stmt->rowCount();
    }

    public function getCommmentsOfProduct($id_product) {
        $sql = "SELECT * FROM comment WHERE id_product = $id_product AND accept = 1";
        $stmt = $this->connect()->query($sql);
        return $stmt->fetchAll();
    }

    public function insertComment($comment, $id_product, $id_user) {
        $sql = "INSERT INTO comment (comment_content, accept, id_product, id_user)
                VALUES (?, '0', ?, ?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$comment, $id_product, $id_user]);
    }

    public function deleteComment($id) {
        $sql = "DELETE FROM comment WHERE id_comment = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id]);
    }

   
  public function getCommentOrderProduct($id_comment) {
    $sql = "
        SELECT 
            p.title_product,
            1 AS quantity,
            p.size_product AS size,
            p.price_product AS price,
            p.price_product AS total_price
        FROM comment c
        JOIN product p ON c.id_product = p.id_product
        WHERE c.id_comment = ?
        LIMIT 1
    ";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id_comment]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function searchComments($keyword) {
    $sql = "
        SELECT 
            c.id_comment,
            c.comment_content,
            c.created_at,
            u.user_name,
            p.title_product,
            cat.name_category
        FROM comment c
        JOIN user u ON c.id_user = u.id_user
        JOIN product p ON c.id_product = p.id_product
        JOIN category cat ON p.id_category = cat.id_category
        WHERE c.comment_content LIKE ?
           OR u.user_name LIKE ?
           OR p.title_product LIKE ?
           OR cat.name_category LIKE ?
           OR c.id_comment = ?
        ORDER BY c.created_at DESC
    ";

    $stmt = $this->connect()->prepare($sql);
    $key = "%".$keyword."%";
    $stmt->execute([$key, $key, $key, $key, $keyword]);

    return $stmt->fetchAll();
}


    public function getCommentById($id_comment) {
    $sql = "
        SELECT 
            c.id_comment,
            c.comment_content,
            c.created_at,
            u.user_name,
            p.title_product
        FROM comment c
        JOIN user u ON c.id_user = u.id_user
        JOIN product p ON c.id_product = p.id_product
        WHERE c.id_comment = ?
        LIMIT 1
    ";
    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([$id_comment]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
?>
