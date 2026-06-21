<?php

$db = mysqli_connect(
    'localhost',
    'lkh2026',
    'q1w2e3r4!',
    'lkh2026'
);

if (!$db) {
    die('DB 연결 실패 : ' . mysqli_connect_error());
}

mysqli_set_charset($db, 'utf8');