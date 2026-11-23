<?php
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// landing page
header("Location: index.php");
exit;
?>