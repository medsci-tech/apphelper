<form method="post" action="http://upload.qiniu.com/"
 enctype="multipart/form-data">
  <input name="key" type="hidden" value="<?=$key?>">
  <input name="token" type="hidden" value="<?=$token?>">
  <input name="file" type="file" />
    <input type="submit" value="上传"/>
</form>
