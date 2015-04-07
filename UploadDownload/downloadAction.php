<?php

//在服务器响应浏览器的请求时，告诉浏览器以编码格式为UTF-8的编码显示该内容
header("Content-type: text/html; charset=utf-8");

$filename = $_GET['filename'];
download2($filename);

function download1($filepath) {
    //通过这句代码客户端浏览器就能知道服务端返回的文件形式
    Header("Content-type: application/octet-stream");
    //告诉客户端浏览器返回的文件大小是按照字节进行计算的
    Header("Accept-Ranges: bytes");
    //告诉浏览器返回的文件大小
    header("Accept-Length:" . filesize($filepath));
    //告诉浏览器返回的文件的名称
    header("Content-Disposition: attachment; filename=" . basename($filepath));

    readfile($filepath);
}

function download2($filepath) {
    $fileSize = filesize($filepath);
    //通过这句代码客户端浏览器就能知道服务端返回的文件形式
    Header("Content-type: application/octet-stream");
    //告诉客户端浏览器返回的文件大小是按照字节进行计算的
    Header("Accept-Ranges: bytes");
    //告诉浏览器返回的文件大小
    header("Accept-Length:" . $fileSize);
    //告诉浏览器返回的文件的名称
    header("Content-Disposition: attachment; filename=" . basename($filepath));

    //向客户端返回数据
    //设置大小输出
    $buffer = 1024;
    //为了下载安全，我们最好做一个文件字节读取计数器
    $writedCount = 0;
    //判断文件指针是否到了文件结束的位置(读取文件是否结束)
    $fp = fopen($filepath, "r");
    while (!feof($fp) && ($fileSize - $writedCount) > 0) {
        $file_data = fread($fp, $buffer);
        //统计读取多少个字节数
        $writedCount += $buffer;
        //把部分数据返回给浏览器
        echo $file_data;
    }
    //关闭文件
    fclose($fp);
}