<?php
session_start();

// Eliminar variables de sesión relacionadas con rate limiting
$ip = $_SERVER['REMOTE_ADDR'];
$lockoutKey = 'login_lockout_' . $ip;
$attemptKey = 'login_attempts_' . $ip;

unset($_SESSION[$lockoutKey]);
unset($_SESSION[$attemptKey]);

echo "<h2>Bloqueo de IP reseteado</h2>";
echo "<p>El bloqueo de rate limiting para tu IP ha sido eliminado.</p>";
echo "<p>Ahora puedes intentar hacer login nuevamente.</p>";
echo "<br><a href='admin_login.php'>Ir al login</a>";
?>
