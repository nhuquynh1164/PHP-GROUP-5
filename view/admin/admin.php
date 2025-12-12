<?php
ob_start();
include_once('./model/db.classes.php');
include_once('./model/user.classes.php');
include_once('./model/bill.classes.php');
include_once('./model/comment.classes.php');

$User = new User();
$Bill = new Bill();
$Comment = new Comment();

// countUser
$countUsers = $User->countUsers();
$countAdmins = $User->countAdmins();

// countBill
$countBills = $Bill->getCountBills();
$countBillsDontAcp = $Bill->getCountBillsDontAcp();

// countComment
$countCmtDontAcp = $Comment->countCommmentsDontAccept();
?>

<div class="container">
    <div class="navigation">
        <ul>
            <li><img src="./assets/images/lld.png" alt="" class="logo"></li>

            <li><a href="?quanly=admin&action=overview">
                <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                <span class="title">Tổng quan</span></a></li>

            <li><a href="?quanly=admin&action=manageCategory">
                <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                <span class="title">Quản lý Danh mục</span></a></li>

            <li><a href="?quanly=admin&action=manageProduct">
                <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                <span class="title">Quản lý sản phẩm</span></a></li>

            <li><a href="?quanly=admin&action=manageCart">
                <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                <span class="title">Quản lý Đơn hàng</span></a></li>

            <li><a href="?quanly=admin&action=manageComment">
                <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                <span class="title">Quản lý Bình luận</span></a></li>

            <li><a href="?quanly=admin&action=manageUser">
                <span class="icon"><ion-icon name="people-outline"></ion-icon></span>
                <span class="title">Quản lý User</span></a></li>

            <li><a href="?quanly=admin&action=manageSlider">
                <span class="icon"><ion-icon name="chatbubble-outline"></ion-icon></span>
                <span class="title">Quản lý Slider</span></a></li>

            <li><a href="index.php">
                <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
                <span class="title">Đăng xuất</span></a></li>
        </ul>
    </div>

    <div class="main">

        <div class="topbar">
            <div class="toggle"><ion-icon name="menu-outline"></ion-icon></div>
            <div class="user"><img src="assets/imgs/customer01.jpg" alt=""></div>
        </div>

        <!-- CARDS -->
        <div class="cardBox">
            <div class="card">
                <div>
                    <div class="numbers"><?= $countBillsDontAcp ?></div>
                    <div class="cardName">Đơn hàng <b class="titleAdmin_fxl">chưa duyệt</b></div>
                </div>
                <div class="iconBx"><ion-icon name="eye-outline"></ion-icon></div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers"><?= $countBills ?></div>
                    <div class="cardName">Tổng đơn hàng</div>
                </div>
                <div class="iconBx"><ion-icon name="chatbubbles-outline"></ion-icon></div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers"><?= $countUsers ?></div>
                    <div class="cardName">Số tài khoản <b class="titleAdmin_fxl">Users</b></div>
                </div>
                <div class="iconBx"><ion-icon name="cart-outline"></ion-icon></div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers"><?= $countAdmins ?></div>
                    <div class="cardName">Số tài khoản <b class="titleAdmin_fxl">Admin</b></div>
                </div>
                <div class="iconBx"><ion-icon name="cart-outline"></ion-icon></div>
            </div>

            <div class="card">
                <div>
                    <div class="numbers"><?= $countCmtDontAcp ?></div>
                    <div class="cardName">Bình luận <b class="titleAdmin_fxl">chưa duyệt</b></div>
                </div>
                <div class="iconBx"><ion-icon name="chatbubbles-outline"></ion-icon></div>
            </div>
        </div>

        <!-- CONTENT WRAPPER -->
        <div class="details">
            <div class="recentOrders">

                <?php  
                /* ============================
                   ❗ ẨN TIÊU ĐỀ "Thông tin chi tiết"
                   Ở TRANG LIST (manageUser, manageProduct,...)
                ============================ */
                $hideTitle = ['manageUser','manageComment','manageCategory','manageProduct','manageCart','manageSlider'];

                if (!isset($_GET['action']) || !in_array($_GET['action'], $hideTitle)) {
                    echo '<div class="cardHeader"><h2>Thông tin chi tiết</h2></div>';
                }
                ?>

                <?php
                    if(isset($_GET['action']) && $_GET['action']) {
                        switch($_GET['action']) {

                            case 'overview': include_once('adminOverview.php'); break;
                            case 'manageUser': include_once('adminManageUser.php'); break;
                            case 'manageSlider': include_once('adminManageSlider.php'); break;
                            case 'manageProduct': include_once('adminManageProduct.php'); break;
                            case 'manageCart': include_once('adminManageCart.php'); break;
                            case 'manageCategory': include_once('adminManageCategory.php'); break;
                            case 'manageComment': include_once('adminManageComment.php'); break;

                            case 'detailComment': include_once('detailComment.php'); break;

                            case 'detailCart': include_once('adminDetailCart.php'); break;
                            case 'detailProduct': include_once('adminDetailProduct.php'); break;

                            case 'changeUser': include_once('adminChangeUser.php'); break;
                            case 'changeSlider': include_once('adminChangeSlider.php'); break;
                            case 'changeCategory': include_once('adminChangeCategory.php'); break;
                            case 'changeProduct': include_once('adminChangeProduct.php'); break;
                            case 'changeDetailProduct': include_once('adminChangeDetailProduct.php'); break;
                            case 'changeCart': include_once('adminChangeCart.php'); break;

                            case 'insertProduct': include_once('adminInsertProduct.php'); break;
                            case 'insertDetailProduct': include_once('adminInsertDetailProduct.php'); break;
                            case 'insertUser': include_once('adminInsertUser.php'); break;
                            case 'insertSlider': include_once('adminInsertSlider.php'); break;
                            case 'insertCategory': include_once('adminInsertCategory.php'); break;
                        }
                    } else {
                        include_once('adminOverview.php');
                    }
                ?>

            </div>
        </div>
    </div>
</div>
