<?php
// Start session only if not already active
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Fix include path to the model and add defensive checks
$modelPath = __DIR__ . '/../../model/authen.classes.php';
if (file_exists($modelPath)) {
    include_once($modelPath);
    if (class_exists('Authen')) {
        $Authen = new Authen();
    } else {
        $Authen = null;
        $message = "Internal error: authentication module not available.";
    }
} else {
    $Authen = null;
    $message = "Internal error: model file not found.";
}

// ================= HANDLE REGISTER =================
if (isset($_POST["submit_register"])) {

    if (
        empty($_POST["fullname"]) ||
        empty($_POST["userName"]) ||
        empty($_POST["password"]) ||
        empty($_POST["phone"]) ||
        empty($_POST["email"]) ||
        empty($_POST["address"])
    ) {
        $message = "Vui lòng nhập đầy đủ thông tin.";
    } else {

        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Email không đúng định dạng.";
        } elseif (preg_match('/\s/', $email)) {
            $message = "Email không được chứa khoảng trắng.";
        } elseif (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W]/', $password)
        ) {
            $message = "Mật khẩu phải tối thiểu 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.";
        } else {

            /*
            $captcha_input = $_POST['captcha_code'] ?? '';
            $captcha_session = $_SESSION['captcha_code'] ?? '';

            if (empty($captcha_input)) {
                $message = "Vui lòng nhập mã xác nhận.";
            }
            elseif (strtolower($captcha_input) !== strtolower($captcha_session)) {
                $message = "Mã xác nhận không đúng. Vui lòng thử lại.";
                unset($_SESSION['captcha_code']);
            }
            else {
            */

                if ($Authen) {
                    $message = $Authen->register(
                        $_POST["fullname"],
                        $_POST["userName"],
                        $password,
                        $_POST["phone"],
                        $_POST["address"],
                        $email
                    );
                } else {
                    $message = "Internal error: cannot process registration.";
                }

            /*
                unset($_SESSION['captcha_code']);
            }
            */
        }
    }
}
?>
<div class="sign-up-wrapper">
  <div class="sign-up-container">
    <div class="sign-up-title"><i class="fa-solid fa-user-plus"></i> Sign Up</div>
    <p class="message-error"><?php echo (isset($message) && $message) ? $message : '' ?></p>
    <form action="" method="post">
      <div class="user-details">
        <div class="input-box form-group">
          <span class="details">Username</span>
          <input id="user" name="userName" type="text" class="form-control" placeholder="Enter your username" />
        </div>

        <div class="input-box form-group">
          <span class="details">Password</span>
          <input name="password"
              type="password"
              class="form-control"
              placeholder="Enter your password"
              required
              minlength="8"
              pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$" />
        </div>

        <div class="input-box form-group">
          <span class="details">Fullname</span>
          <input name="fullname" type="text" class="form-control" placeholder="Enter your fullname" />
        </div>

        <div class="input-box form-group">
          <span class="details">Phone</span>
          <input name="phone" type="text" class="form-control" placeholder="Enter your phone" />
        </div>

        <div class="input-box form-group">
          <span class="details">Email</span>
          <input name="email" type="text" class="form-control" placeholder="Enter your email" />
        </div>

        <div class="input-box form-group">
          <span class="details">Address</span>
          <input name="address" type="text" class="form-control" placeholder="Enter your address" />
        </div>
      </div>

      <?php /* ?>
      <div class="captcha-container" style="margin: 20px 0;">
        <div class="input-box form-group">
          <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 10px;">
            <img id="captcha-image" src="../public/captcha.php" alt="CAPTCHA">
            <button type="button" onclick="refreshCaptcha()">Tải lại</button>
          </div>
          <input id="captcha" name="captcha_code" type="text" class="form-control">
        </div>
      </div>
      <?php */ ?>

      <div class="container-btn-sm">
        <div class="sign-up-btn">
          <input id="register" name="submit_register" type="submit" value="Sign Up">
        </div>
      </div>
    </form>
  </div>
</div>
<script>
/*
function refreshCaptcha() {
    document.getElementById('captcha-image').src =
        '../public/captcha.php?' + Math.random();
    document.getElementById('captcha').value = '';
}
*/
</script>