<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>多文件上传</title>
</head>
<body>
<form action="uploadAction.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="176942">
    文件1：<input type="file" name="myFile1" accept="image/jpeg,image/png"/><br/>

    <input type="submit" value="上传文件">
</form>
</body>
</html>