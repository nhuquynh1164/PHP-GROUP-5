<?php
// BẮT BUỘC: khởi động session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Tạo chuỗi CAPTCHA (chữ + số, tránh ký tự dễ nhầm)
$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
$captcha_length = 6;
$captcha_code = '';

for ($i = 0; $i < $captcha_length; $i++) {
    $captcha_code .= $characters[random_int(0, strlen($characters) - 1)];
}

// Lưu captcha vào session
$_SESSION['captcha_code'] = $captcha_code;

// Kích thước ảnh
$width  = 150;
$height = 50;

// Tạo ảnh
$image = imagecreatetruecolor($width, $height);

// Màu sắc
$bg_color    = imagecolorallocate($image, 245, 245, 245);
$text_color  = imagecolorallocate($image, 30, 30, 30);
$noise_color = imagecolorallocate($image, 180, 180, 180);

// Nền
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Nhiễu: chấm
for ($i = 0; $i < 150; $i++) {
    imagefilledellipse(
        $image,
        random_int(0, $width),
        random_int(0, $height),
        2,
        2,
        $noise_color
    );
}

// Nhiễu: đường
for ($i = 0; $i < 5; $i++) {
    imageline(
        $image,
        random_int(0, $width),
        random_int(0, $height),
        random_int(0, $width),
        random_int(0, $height),
        $noise_color
    );
}

// Vẽ chữ CAPTCHA
$font_size = 5;
$x = 15;
$y = 18;

for ($i = 0; $i < strlen($captcha_code); $i++) {
    imagestring(
        $image,
        $font_size,
        $x,
        random_int(10, 20),
        $captcha_code[$i],
        $text_color
    );
    $x += 22;
}

// Header xuất ảnh
header("Content-Type: image/png");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

// Xuất ảnh
imagepng($image);
exit;