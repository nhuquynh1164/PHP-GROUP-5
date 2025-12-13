<?php
ob_start();
include_once('./model/db.classes.php');
include_once('./model/user.classes.php');
include_once('./model/bill.classes.php');
include_once('./model/comment.classes.php');
include_once('./model/product.classes.php');

$User    = new User();
$Bill    = new Bill();
$Comment = new Comment();
$Product = new Product();

// ===== DASHBOARD =====
$countUsers     = $User->countUsers();
$countAdmins    = $User->countAdmins();
$countBills     = $Bill->getCountBills();
$countBillsDontAcp = $Bill->getCountBillsDontAcp();
$countCmtDontAcp  = $Comment->countCommmentsDontAccept();
$totalOrders    = $Bill->getTotalOrders();
$totalRevenue   = $Bill->getTotalRevenue();
$totalProducts  = $Product->getCountProducts();
?>

<div class="container">
    <!-- SIDEBAR -->
    <div class="navigation">
        <ul>
            <li><img src="./assets/images/lld.png" class="logo"></li>
            <li><a href="?quanly=admin&action=overview"><span class="icon"><ion-icon name="home-outline"></ion-icon></span><span class="title">Tổng quan</span></a></li>
            <li><a href="?quanly=admin&action=manageCategory"><span class="icon"><ion-icon name="layers-outline"></ion-icon></span><span class="title">Danh mục</span></a></li>
            <li><a href="?quanly=admin&action=manageProduct"><span class="icon"><ion-icon name="cube-outline"></ion-icon></span><span class="title">Sản phẩm</span></a></li>
            <li><a href="?quanly=admin&action=manageCart"><span class="icon"><ion-icon name="receipt-outline"></ion-icon></span><span class="title">Đơn hàng</span></a></li>
            <li><a href="?quanly=admin&action=manageComment"><span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span><span class="title">Bình luận</span></a></li>
            <li><a href="?quanly=admin&action=manageUser"><span class="icon"><ion-icon name="people-outline"></ion-icon></span><span class="title">User</span></a></li>
            <li><a href="?quanly=admin&action=manageSlider"><span class="icon"><ion-icon name="images-outline"></ion-icon></span><span class="title">Slider</span></a></li>
            <li><a href="index.php"><span class="icon"><ion-icon name="log-out-outline"></ion-icon></span><span class="title">Đăng xuất</span></a></li>
        </ul>
    </div>

    <!-- MAIN -->
    <div class="main">
        <!-- TOPBAR -->
        <div class="topbar">
            <div class="toggle"><ion-icon name="menu-outline"></ion-icon></div>
            <div class="user"><img src="assets/imgs/customer01.jpg"></div>
        </div>

        <!-- DASHBOARD CARDS -->
        <div class="cardBox">
            <div class="card"><div><div class="numbers"><?= $countBillsDontAcp ?></div><div class="cardName">Đơn chưa duyệt</div></div><div class="iconBx"><ion-icon name="alert-circle-outline"></ion-icon></div></div>
            <div class="card"><div><div class="numbers"><?= $countBills ?></div><div class="cardName">Tổng đơn hàng</div></div><div class="iconBx"><ion-icon name="receipt-outline"></ion-icon></div></div>
            <div class="card"><div><div class="numbers"><?= $countUsers ?></div><div class="cardName">Tài khoản User</div></div><div class="iconBx"><ion-icon name="people-outline"></ion-icon></div></div>
            <div class="card"><div><div class="numbers"><?= $countAdmins ?></div><div class="cardName">Tài khoản Admin</div></div><div class="iconBx"><ion-icon name="shield-outline"></ion-icon></div></div>
        </div>

        <div class="cardBox">
            <div class="card"><div><div class="numbers"><?= $totalOrders ?></div><div class="cardName">Tổng đơn</div></div><div class="iconBx"><ion-icon name="file-tray-full-outline"></ion-icon></div></div>
            <div class="card"><div><div class="numbers"><?= $totalProducts ?></div><div class="cardName">Tổng sản phẩm</div></div><div class="iconBx"><ion-icon name="cube-outline"></ion-icon></div></div>
            <div class="card"><div><div class="numbers"><?= number_format($totalRevenue,0,',','.') ?>đ</div><div class="cardName">Tổng doanh thu</div></div><div class="iconBx"><ion-icon name="cash-outline"></ion-icon></div></div>
        </div>

        <!-- CONTENT -->
        <div class="details">
            <div class="recentOrders">
                <?php
                // Các action không hiển thị header chung
                $hideTitle = [
                    'manageUser','manageComment','manageCategory','manageProduct','manageCart','manageSlider',
                    'detailUser','detailComment','detailCart','detailProduct',
                    'insertUser','insertProduct','insertCategory','insertSlider',
                    'changeUser','changeProduct','changeCategory','changeSlider'
                ];

                if (!isset($_GET['action']) || !in_array($_GET['action'], $hideTitle)) {
                    echo '<div class="cardHeader"><h2>Thông tin chi tiết</h2></div>';
                }

                $action = $_GET['action'] ?? 'overview';
                switch ($action) {
                    case 'overview': include 'adminOverview.php'; break;
                    case 'manageUser': include 'adminManageUser.php'; break;
                    case 'manageProduct': include 'adminManageProduct.php'; break;
                    case 'manageCart': include 'adminManageCart.php'; break;
                    case 'manageCategory': include 'adminManageCategory.php'; break;
                    case 'manageComment': include 'adminManageComment.php'; break;
                    case 'manageSlider': include 'adminManageSlider.php'; break;
                    case 'detailUser': include 'adminDetailUser.php'; break;
                    case 'detailComment': include 'adminDetailComment.php'; break;
                    case 'detailCart': include 'adminDetailCart.php'; break;
                    case 'detailProduct': include 'adminDetailProduct.php'; break;

                    // Thêm mới
                    case 'insertUser': include 'adminInsertUser.php'; break;
                    case 'insertProduct': include 'adminInsertProduct.php'; break;
                    case 'insertCategory': include 'adminInsertCategory.php'; break;
                    case 'insertSlider': include 'adminInsertSlider.php'; break;

                    // Chỉnh sửa
                    case 'changeUser': include 'adminChangeUser.php'; break;
                    case 'changeProduct': include 'adminChangeProduct.php'; break;
                    case 'changeCategory': include 'adminChangeCategory.php'; break;
                    case 'changeSlider': include 'adminChangeSlider.php'; break;

                    default: include 'adminOverview.php'; break;
                }
                ?>
            </div>
        </div>
    </div>
</div>
