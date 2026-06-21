<?php

require_once __DIR__ . '/../includes/auth.php';
ensure_session_started();

session_unset();

session_destroy();

header('Location: /coffee/index.php');
exit;
