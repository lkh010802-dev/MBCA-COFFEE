<?php

require_once __DIR__ . '/../config/app.php';

function image_url($path, string $subdirectory = ''): string
{
    $path = trim(str_replace('\\', '/', (string)$path));

    if ($path === '') {
        return APP_BASE_URL . '/assets/images/cat.png';
    }

    if (preg_match('#^(?:https?:)?//#i', $path) || strpos($path, 'data:') === 0) {
        return $path;
    }

    if (strpos($path, '/assets/') === 0) {
        return APP_BASE_URL . $path;
    }

    if (strpos($path, '/') === 0) {
        return $path;
    }

    $path = ltrim($path, './');

    if (strpos($path, 'assets/') === 0) {
        return APP_BASE_URL . '/' . $path;
    }

    if (strpos($path, 'coffee/') === 0) {
        return '/' . $path;
    }

    if (strpos($path, 'menu/') === 0 || strpos($path, 'event/') === 0) {
        return APP_BASE_URL . '/assets/images/' . $path;
    }

    $subdirectory = trim($subdirectory, '/');
    $prefix = $subdirectory === '' ? '' : $subdirectory . '/';

    return APP_BASE_URL . '/assets/images/' . $prefix . $path;
}

function store_uploaded_image(?array $file, string $subdirectory, string $existingPath = ''): string
{
    if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $existingPath;
    }

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('이미지 업로드 중 오류가 발생했습니다.');
    }

    if (($file['size'] ?? 0) > MAX_IMAGE_UPLOAD_SIZE) {
        throw new RuntimeException('이미지는 5MB 이하만 업로드할 수 있습니다.');
    }

    $allowedDirectories = ['menu', 'event'];
    if (!in_array($subdirectory, $allowedDirectories, true)) {
        throw new RuntimeException('허용되지 않은 이미지 저장 경로입니다.');
    }

    $temporaryPath = (string)($file['tmp_name'] ?? '');
    $imageInfo = @getimagesize($temporaryPath);
    $mimeType = $imageInfo['mime'] ?? '';
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    if (!isset($extensions[$mimeType])) {
        throw new RuntimeException('JPG, PNG, WEBP, GIF 이미지만 업로드할 수 있습니다.');
    }

    $uploadDirectory = IMAGE_ROOT . '/' . $subdirectory;
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
        throw new RuntimeException('이미지 저장 폴더를 만들 수 없습니다.');
    }

    $fileName = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
    $destination = $uploadDirectory . '/' . $fileName;

    if (!move_uploaded_file($temporaryPath, $destination)) {
        throw new RuntimeException('업로드한 이미지를 저장하지 못했습니다.');
    }

    return APP_BASE_URL . '/assets/images/' . $subdirectory . '/' . $fileName;
}
