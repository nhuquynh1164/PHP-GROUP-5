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

    if(isset($_POST["submit_register"]) && $_POST["submit_register"] ) {
      if( (isset($_POST["fullname"]) && $_POST["fullname"]) && (isset($_POST["userName"]) && $_POST["userName"])
      && (isset($_POST["password"]) && $_POST["password"]) && (isset($_POST["phone"]) && $_POST["phone"]) 
      && (isset($_POST["email"]) && $_POST["email"]) && (isset($_POST["address"]) && $_POST["address"]) ) {
          
          // Verify CAPTCHA
          $captcha_input = $_POST['captcha_code'] ?? '';
          $captcha_session = $_SESSION['captcha_code'] ?? '';
          
          if (empty($captcha_input)) {
              $message = "Vui lòng nhập mã xác nhận";
          } elseif (strtolower($captcha_input) !== strtolower($captcha_session)) {
              $message = "Mã xác nhận không đúng. Vui lòng thử lại.";
              // Xóa mã cũ để tạo mã mới
              unset($_SESSION['captcha_code']);
          } else {
              // CAPTCHA đúng, tiến hành đăng ký
              if ($Authen) {
                  $fullName = $_POST["fullname"];
                  $userName  = $_POST["userName"];
                  $password  = $_POST["password"];
                  $phone  = $_POST["phone"];
                  $email  = $_POST["email"];
                  $address  = $_POST["address"];
                  $message = $Authen->register($fullName,$userName,$password,$phone,$address,$email);
              } else {
                  $message = isset($message) ? $message : "Internal error: cannot process registration.";
              }
              // Xóa mã captcha sau khi sử dụng
              unset($_SESSION['captcha_code']);
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
          <div class="form-message"></div>
        </div>

        <div class="input-box form-group">
          <span class="details">Password</span>
          <!-- Change input type to password -->
          <input id="user" name="password" type="password" class="form-control" placeholder="Enter your password" />
          <div class="form-message"></div>
        </div>

        <div class="input-box form-group">
          <span class="details">Fullname</span>
          <input id="user" name="fullname" type="text" class="form-control" placeholder="Enter your fullname" />
          <div class="form-message"></div>
        </div>

        <div class="input-box form-group">
          <span class="details">Phone</span>
          <input id="user" name="phone" type="text" class="form-control" placeholder="Enter your phone" />
          <div class="form-message"></div>
        </div>

        <div class="input-box form-group">
          <span class="details">Email</span>
          <input id="user" name="email" type="text" class="form-control" placeholder="Enter your email" />
          <div class="form-message"></div>
        </div>

        <div class="input-box form-group">
          <span class="details">Address</span>
          <input id="user" name="address" type="text" class="form-control" placeholder="Enter your address" />
          <div class="form-message"></div>
        </div>
      </div>
      <!-- Gender -->
      
      <!-- CAPTCHA -->
      <div class="captcha-container" style="margin: 20px 0;">
        <div class="input-box form-group">
          <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 10px;">
            <img id="captcha-image" src="../public/captcha.php" alt="CAPTCHA" style="border: 1px solid #ccc; border-radius: 5px;">
            <button type="button" onclick="refreshCaptcha()" style="padding: 8px 12px; cursor: pointer; background: #4070f4; color: white; border: none; border-radius: 5px;" title="Tải lại mã">
              <i class="fa-solid fa-rotate-right"></i> Tải lại
            </button>
          </div>
          <span class="details">Nhập mã xác nhận</span>
          <input id="captcha" name="captcha_code" type="text" class="form-control" placeholder="Nhập mã từ hình trên" required />
          <div class="form-message"></div>
        </div>
      </div>
      
        <div class="container-btn-sm">
          <div class="sign-up-btn">
            <input id="register" name='submit_register' type="submit" value="Sign Up">
          </div>
        </div>

</div>

<!-- CAPTCHA Script -->
<script>
function refreshCaptcha() {
    document.getElementById('captcha-image').src = '../public/captcha.php?' + Math.random();
    document.getElementById('captcha').value = '';
}
</script>
