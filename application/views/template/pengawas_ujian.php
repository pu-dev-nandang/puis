<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!--    <meta http-equiv="X-UA-Compatible" content="ie=edge">-->
    <title>Invigilator Page | Podomoro University</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('images/icon/favicon.png'); ?>">

    <?php echo $include; ?>

    <style>
        body {
            font-family: 'Noto Sans', sans-serif;
            background: #607d8b;
        }
    </style>

</head>

<body>

<!--<pre>-->
<!--    --><?php //print_r($this->session->all_userdata()); ?>
<!--</pre>-->

<?= $content; ?>


<div class="modal fade" id="GlobalModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>