<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập SaleTablet</title>
    <link href="<?=base_url('asset/css/signin.css')?>" rel="stylesheet"/>
</head>
<body>
    <div class="container">
        <h4 class="error center"><?=isset($error)?$error : ''?></h4> 
        <!--<div style="padding: 10px; text-align: center; background: #18b70d; color:#fff; border-radius: 5px;">Từ ngày 28/8/2017, Central ngưng chạy thử nghiệm và chuyển sang dùng bản chính thức như đã thông báo trong email. Cảm ơn.</div>-->
        <form class="form-signin" action="<?=site_url('user/login')?>" method="POST">
            <h2 class="form-signin-heading text-center">ĐĂNG NHẬP</h2>
            <input type="email" name="email" value="<?=(isset($email)?$email:'')?>" class="form-control" placeholder="Email của bạn" required autofocus >
            <input type="password" name="password" value="<?=(isset($pass)?$pass:'')?>" class="form-control" placeholder="Mật khẩu" required/>
            <div class="checkbox"><label><input type="checkbox" name="remember"/>Nhớ cho lần đăng nhập sau</label></div>
            <input class="btn btn-lg btn-primary btn-block" type="submit" name="Login" value="XÁC NHẬN"/>
        </form>
    </div> <!-- /container -->
</body>
</html>
