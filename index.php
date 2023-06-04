<?php
// header('Content-Type: text/json;charset=utf-8');
/**
 * @author: 簡單
 * @link: https://log.37o.cc
 * @date: 2023年06月04日 12:31:11
 * @msg: 展示根目录结构及文件
 */

function getDirFile($path)
{
    if (!($file_handler = opendir($path)))
        return;
    $fileNTimes = array();
    while (false !== ($file = readdir($file_handler))) {
        if ($file == '.' || $file == '..' || $file == 'index.php')
            continue;
        $fileNTimes[filemtime($path . '/' . $file)] = $file;
    }
    krsort($fileNTimes);
    foreach ($fileNTimes as $mtime => $file) {
        $file_path = "$path/$file";
        $rel_path = str_replace($_SERVER['DOCUMENT_ROOT'] . "/", "", $file_path);
        echo '<ul>' . "\n";
        if (is_dir($file_path)) {
            echo '<li>' . getFile_html($rel_path, $file) . '</li>' . "\n";
            getDirFile($file_path);
        } else {
            echo '<li>' . getFile_html($rel_path, $file) . getTime_html($mtime, $file_path) . '</li>' . "\n";
        }
        echo '</ul>' . "\n";
    }
}

function getTime_html($time, $file_path)
{
    $filesize = filesize($file_path);
    $formatted_size = formatFileSize($filesize);
    return '<a style="font-size:10px;color:grey"> ' . date('(Y-m-d H:i:s)', $time) . ' - Size: ' . $formatted_size . '</a>' . "\n";
}

function getFile_html($rel_path, $file)
{
    return '<a href="' . $rel_path . '" target="_blank">' . $file . '</a>' . "\n";
}

function formatFileSize($size)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $i = 0;
    while ($size >= 1024 && $i < 4) {
        $size /= 1024;
        $i++;
    }
    return round($size, 2) . ' ' . $units[$i];
}

$path = 'framework';
getDirFile($path);
