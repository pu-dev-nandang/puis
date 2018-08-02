<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>Podomoro University</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('images/icon/favicon.png'); ?>">


    <?php echo $include; ?>

</head>

<body>

<div class="col-md-6 col-md-offset-3" style="text-align: center;margin-top: 70px;">
    <div class="well">
        <img src="<?php echo base_url('images/icon/userfalse.png'); ?>" style="max-width: 90px;">
        <h3>User not yet setting, please contact your administrator</h3>
        <hr/>
        <button class="btn btn-lg btn-danger btnActionLogOut">Sign Out</button>
    </div>
</div>

</body>
</html>