<?php
// Ajusta los datos de conexión a tu caso
$pdo = new PDO("mysql:host=localhost;dbname=hieribal;charset=utf8mb4", "root", "");

$usuarios = $pdo->query("SELECT id_usuario, password FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $u) {
    $pwd = $u['password'];

    // Si parece plano (no empieza por $2y$ ni $argon2)
    if (!preg_match('/^\$2y\$|\$argon2/', $pwd)) {
        $hash = password_hash($pwd, PASSWORD_DEFAULT);
        $st = $pdo->prepare("UPDATE usuarios SET password = :h WHERE id_usuario = :id");
        $st->execute([':h' => $hash, ':id' => $u['id_usuario']]);
        echo "✅ Usuario {$u['id_usuario']} migrado\n";
    }
}
