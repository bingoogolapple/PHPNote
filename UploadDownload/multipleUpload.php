<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>多文件上传</title>
</head>
<body>
<form action="multipleUploadAction.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="176942">
    文件1：<input type="file" name="file1" accept="image/jpeg,image/png"/><br/>
    文件2：<input type="file" name="file2"/><br/>
    文件3：<input type="file" name="bgafiles[]"/><br/>
    文件4：<input type="file" name="bgafiles[]"/><br/>
    文件5：<input type="file" name="bgafiles[]" multiple="multiple"/><br/>
    <input type="submit" value="上传文件">
</form>
</body>
</html>