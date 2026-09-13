<?php
require 'bootstrap.php';
$repo = new App\Repositories\MvpRepository();
$pdo = App\Core\Database::connection();

$tokens = $pdo->query('SELECT t.id, t.token, t.is_active, t.expires_at, rt.table_number, r.approval_status, r.name FROM qr_tokens t INNER JOIN restaurant_tables rt ON rt.id = t.restaurant_table_id INNER JOIN branches b ON b.id = rt.branch_id INNER JOIN restaurants r ON r.id = b.restaurant_id')->fetchAll();
echo "TOKENS IN DB:\n";
print_r($tokens);

if (!empty($tokens)) {
    $firstToken = $tokens[0]['token'];
    echo "\nTesting qrContext with token: $firstToken\n";
    $ctx = $repo->qrContext($firstToken);
    print_r($ctx);
}
