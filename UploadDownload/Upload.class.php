<?php

class Upload {
    private $fileName;
    private $maxSize;
    private $allowMime;
    private $allowExt;
    private $uploadPath;
    private $imgFlag;
    private $fileInfo;
    private $error;
    private $ext;

    /**
     * @param string $fileName
     * @param string $uploadPath
     * @param bool $imgFlag
     * @param int $maxSize
     * @param array $allowExt
     * @param array $allowMime
     */
    public function __construct($fileName = 'myFile', $uploadPath = 'uploads', $imgFlag = true, $maxSize = 5242880, $allowExt = array('jpeg', 'jpg', 'png', 'gif'), $allowMime = array('image/jpeg', 'image/png', 'image/gif')) {
        $this->fileName = $fileName;
        $this->uploadPath = $uploadPath;
        $this->imgFlag = $imgFlag;
        $this->maxSize = $maxSize;
        $this->allowExt = $allowExt;
        $this->allowMime = $allowMime;
        if(isset($_FILES[$this->fileName])) {
            $this->fileInfo = $_FILES[$this->fileName];
        }
    }

    public function uploadFile() {
        if ($this->checkError() && $this->checkSize() && $this->checkExt() && $this->checkMime() && $this->checkIsImage() && $this->checkIsHttpPost()) {
            $this->checkUploadPath();
            $this->uniName = $this->getUniName();
            $this->destination = $this->uploadPath . DIRECTORY_SEPARATOR . $this->uniName . '.' . $this->ext;
            if (move_uploaded_file($this->fileInfo['tmp_name'], $this->destination)) {
                return $this->destination;
            } else {
                $this->error = '文件移动失败';
                $this->showError();
            }
        } else {
            $this->showError();
        }
    }

    /**
     * 检测是否有错
     * @return bool
     */
    private function checkError() {
        if(!is_null($this->fileInfo)) {
            if ($this->fileInfo['error'] != UPLOAD_ERR_OK) {
                switch ($this->fileInfo['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $this->error = '超过了PHP配置文件中upload_max_filesize选项的值';
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $this->error = '超过了表单MAX_FILE_SIZE限制的大小';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $this->error = '文件部分被上传';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $this->error = '没有选择上传文件';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $this->error = '没有找到临时目录';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $this->error = '文件不可写';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $this->error = '由于PHP的扩展程序中断文件上传';
                        break;
                }
                return false;
            } else {
                return true;
            }
        } else {
            $this->error = '文件上传出错';
            return false;
        }
    }

    /**
     * 检测文件大小
     * @return bool
     */
    private function checkSize() {
        if ($this->fileInfo['size'] > $this->maxSize) {
            $this->error = "上传文件过大";
            return false;
        }
        return true;
    }

    /**
     * 检测文件扩展名
     * @return bool
     */
    private function checkExt() {
        $this->ext = strtolower(pathinfo($this->fileInfo['name'], PATHINFO_EXTENSION));
        if (!in_array($this->ext, $this->allowExt)) {
            $this->error = '不允许的扩展名';
            return false;
        }
        return true;
    }

    /**
     * 检测文件类型
     * @return bool
     */
    private function checkMime() {
        if (!in_array($this->fileInfo['type'], $this->allowMime)) {
            $this->error = '不允许的文件类型';
            return false;
        }
        return true;
    }

    /**
     * 检测是否是真实图片
     * @return bool
     */
    private function checkIsImage() {
        if ($this->imgFlag) {
            if (!@getimagesize($this->fileInfo['tmp_name'])) {
                $this->error = '不是真实图片';
                return false;
            }
        }
        return true;
    }

    /**
     * 检测是否通过HTTP POST方式上传上来的
     * @return bool
     */
    private function checkIsHttpPost() {
        if (!is_uploaded_file($this->fileInfo['tmp_name'])) {
            $this->error = '文件不是通过HTTP POST方式上传的';
            return false;
        }
        return true;
    }

    /**
     * 显示错误
     */
    private function showError() {
        exit('<span style="color: red">' . $this->error . '<span/>');
    }

    /**
     * 检测目录，不存在则创建
     */
    private function checkUploadPath() {
        if (!file_exists($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    /**
     * 产生唯一字符串
     * @return string
     */
    private function getUniName() {
        return md5(uniqid(microtime(true), true));
    }
}