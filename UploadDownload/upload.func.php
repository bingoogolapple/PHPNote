<?php
require_once 'common.func.php';

// 构建上传文件信息
function getFiles() {
    $i = 0;
    foreach ($_FILES as $file) {
        if (is_string($file['name'])) {
            $files[$i] = $file;
            $i++;
        } elseif (is_array($file['name'])) {
            foreach ($file['name'] as $key => $value) {
                $files[$i]['name'] = $file['name'][$key];
                $files[$i]['type'] = $file['type'][$key];
                $files[$i]['tmp_name'] = $file['tmp_name'][$key];
                $files[$i]['error'] = $file['error'][$key];
                $files[$i]['size'] = $file['size'][$key];
                $i++;
            }
        }
    }
    return $files;
}

function uploadMutipleFile($fileInfo, $uploadPath = 'uploads', $allowExt = array('jpeg', 'jpg', 'png'), $isCheckImage = false, $maxSize = 2097152) {
    // 判断错误号
    $uploadFilename = $fileInfo['name'];
    if ($fileInfo['error'] == UPLOAD_ERR_OK) {
        // 检测上传文件的大小是否符合规范
        if ($fileInfo['size'] > $maxSize) {
            $result['msg'] = $uploadFilename.' ==> 上传文件过大';
        }

        // 检测上传文件的类型
        if (!is_array($allowExt)) {
            $result['msg'] = $uploadFilename.' ==> 允许的扩展名参数必须为数组';
        }
        $ext = getExt($uploadFilename);
        if (!in_array($ext, $allowExt)) {
            $result['msg'] = $uploadFilename.' ==> 非法文件类型';
        }

        // 检测图片是否为真实的图片类型
        if ($isCheckImage) {
            if (!getimagesize($fileInfo['tmp_name'])) {
                $result['msg'] = $uploadFilename.' ==> 不是真实地图片类型';
            }
        }

        // 检测文件是否通过HTTP POST方式上传上来
        if (!is_uploaded_file($fileInfo['tmp_name'])) {
            $result['msg'] = $uploadFilename.' ==> 文件不是通过HTTP POST方式上传上来的';
        }

        if(isset($result)) {
            return $result;
        }

        // 检测上传目录是否存在
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
            chmod($uploadPath, 0777);
        }

        $uniName = getUniName() . '.' . $ext;
        $destination = $uploadPath . DIRECTORY_SEPARATOR . $uniName;

        if (!@move_uploaded_file($fileInfo['tmp_name'], $destination)) {
            $result['msg'] = $uploadFilename.' ==> 文件移动失败';
        } else {
            $result['msg'] = $uploadFilename.' ==> 文件上传成功';
            $result['destination'] = $destination;
        }
    } else {
        switch ($fileInfo['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $result['msg'] = $uploadFilename.' ==> 文件上传超过了PHP配置文件中upload_max_filesize选项的值';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $result['msg'] = $uploadFilename.' ==> 超过了表单max_file_size限制的大小';
                break;
            case UPLOAD_ERR_PARTIAL:
                $result['msg'] = $uploadFilename.' ==> 文件部分被上传';
                break;
            case UPLOAD_ERR_NO_FILE:
                $result['msg'] = $uploadFilename.' ==> 没有选择上传文件';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $result['msg'] = $uploadFilename.' ==> 没有找到临时目录';
                break;
            case UPLOAD_ERR_CANT_WRITE:
            case UPLOAD_ERR_EXTENSION:
                $result['msg'] = $uploadFilename.' ==> 系统错误';
                break;
        }
    }
    return $result;
}