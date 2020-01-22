<!--<h1>Dashboard</h1>-->

<!--<legend>Data Session</legend>-->
<!--<pre>-->
<!--    --><?php //print_r($this->session->all_userdata()); ?>
<!--</pre>-->
<!-- <pre><?php print_r($this->session->userdata('menu_admission_grouping')) ?></pre> -->
<h3>Time date on the server : <span style="color: #3F51B5;font-weight:bold;"><?= date("d F Y H:i:s"); ?></span></h3>
<div class="col-md-12" style="text-align: center;margin-top: 50px;">
    <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMmU2MTQ0YjYtNDY5MS00NzAxLTgxNDUtNWIzZGQ0MGRhY2MyIiwidCI6IjIwMjhkZjEyLTk0ZTYtNGFjNi1hNTA3LTQzZWQwMzI5YzhlZCIsImMiOjEwfQ%3D%3D" frameborder="0" allowFullScreen="true"></iframe>
</div>


<script>
    $(document).ready(function () {

        $('.fixed-header').addClass('sidebar-closed');

        $('.list-group-item').removeClass('active-left-menu');
        $('.collapse').removeClass('in');
    });
</script>