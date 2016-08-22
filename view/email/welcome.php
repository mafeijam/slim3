<!DOCTYPE html>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <title>歡迎成為會員</title>
   </head>
   <body>
      <h1>歡迎成為會員</h1>

      <p>請點擊連結進行驗證</p>

      <?php $link = 'http://slim3.dev/active/' . $code; ?>
      <a href="<?=$link ?>"><?=$link ?></a>
   </body>
</html>