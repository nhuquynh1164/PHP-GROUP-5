<?php
require_once './model/user.classes.php';

$User = new User();

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=users.csv');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

fputcsv($output, ['ID','Name','Email','Phone','Role','Created At']);

$users = $User->getUsers();
foreach ($users as $u) {
    fputcsv($output, [
        $u['id_user'],
        $u['user_name'],
        $u['user_email'],
        $u['user_phone'],
        $u['user_role'] == 1 ? 'Admin' : 'User',
        $u['created_at']
    ]);
}

fclose($output);
exit;
