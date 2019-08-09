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

<style>
    h1 { font-size: 50px; }
    body { font: 20px Helvetica, sans-serif; color: #333; }
    article { display: block; text-align: left; width: 650px; margin: 0 auto; margin-top: 120px;}
    a { color: #dc8100; text-decoration: none; }
    a:hover { color: #333; text-decoration: none; }
</style>


<div class="col-md-6">

    <article>
        <img src="<?= base_url('images/logo_tr.png') ?>" style="max-height: 100px;">
        <hr/>
        <h1>We&rsquo;ll be back soon!</h1>
        <div>
            <p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at
                the moment. If you need to you can always <a href="mailto:it@podomorouniversity.ac.id">contact us</a>, otherwise we&rsquo;ll be back online shortly!</p>
            <p>&mdash; The Team</p>
        </div>
    </article>
</div>




<div class="">
    <div class="col-md-6" style="text-align: center;margin-top: 70px;">

        <div style="text-align: center">

<!--            <button class="btn btn-lg btn-success" id="btnStartGame">Start Game</button>-->
<!--            <hr/>-->
        </div>
        <canvas id="ruang" width="500" height="500"></canvas>


        <script type="text/javascript">

            // deklarasi
            var ruang = $("#ruang")[0];
            var ctx = ruang.getContext("2d");
            var lebar = $("#ruang").width();
            var tinggi = $("#ruang").height();

            var cw = 10;
            var tekan ;
            var makanan;
            var nilai;

            //membuat cell aray untuk membuat ular
            var array_ular;

            function init() {
                tekan = "right"; //default direction
                create_snake();
                create_makanan(); //membuat makanan untuk ular
                //nilai game
                nilai = 0;

                if (typeof game_loop != "undefined") clearInterval(game_loop);
                game_loop = setInterval(paint, 60);

            }


            init();
            $('#btnStartGame').click(function () {
                create_snake();
                $('#btnStartGame').prop('disabled',true);
            });

            // membuat ular
            function create_snake() {
                // menetapkan jumlah panjang awal ular
                var length = 5; //panjang ular default
                array_ular = [];
                for (var i = length - 1; i >= 0; i--) {
                    //membuat ular horizontal mulai dari arah kiri
                    array_ular.push({ x: i, y: 0 });
                }
            }

            //membuat makanan untuk ular
            function create_makanan() {
                makanan = {
                    x: Math.round(Math.random() * (lebar - cw) / cw),
                    y: Math.round(Math.random() * (tinggi - cw) / cw)
                };
            }

            //pengaturan
            function paint() {
                // warna background
                ctx.fillStyle = "#ecf0f1";
                ctx.fillRect(0, 0, lebar, tinggi);
                ctx.strokeStyle = "#2c3e50";
                ctx.strokeRect(0, 0, lebar, tinggi);

                //membuat pergerakan untuk ular
                var nx = array_ular[0].x;
                var ny = array_ular[0].y;
                if (tekan == "right") nx++;
                else if (tekan == "left") nx--;
                else if (tekan == "up") ny--;
                else if (tekan == "down") ny++;

                //memeriksa tabrakan
                if (
                    nx == -1 ||
                    nx == lebar / cw ||
                    ny == -1 ||
                    ny == tinggi / cw ||
                    cek_tabrakan(nx, ny, array_ular)
                ){

                    //restart game
                    init();
                    // $('#btnStartGame').prop('disabled',false);
                    return;
                }

                //cek jika ular kena makanan/memakan makanan
                if(nx == makanan.x && ny == makanan.y){

                    var ekor = { x: nx, y: ny };
                    nilai++;

                    //membuat makanan yang baru
                    create_makanan();

                } else {
                    var ekor = array_ular.pop();
                    ekor.x = nx;
                    ekor.y = ny;
                }

                array_ular.unshift(ekor);

                for (var i = 0; i < array_ular.length; i++) {
                    var c = array_ular[i];
                    paint_cell(c.x, c.y);
                }

                paint_cell(makanan.x, makanan.y);

                //membuat penilaian skor
                var nilai_text = "Score : " + nilai;
                ctx.fillText(nilai_text, 5, tinggi - 5);
            }

            function paint_cell(x, y) {
                ctx.fillStyle = "#083f88";
                ctx.fillRect(x * cw, y * cw, cw, cw);
                ctx.strokeStyle = "#ecf0f1";
                ctx.strokeRect(x * cw, y * cw, cw, cw);
            }

            function cek_tabrakan(x, y, array) {
                for (var i = 0; i < array.length; i++) {
                    if (array[i].x == x && array[i].y == y) return true;
                }
                return false;
            }

            //kontrol ular dengan keyboard
            $(document).keydown(function(e) {
                var key = e.which;
                if (key == "37" && tekan != "right") tekan = "left";
                else if (key == "38" && tekan != "down") tekan = "up";
                else if (key == "39" && tekan != "left") tekan = "right";
                else if (key == "40" && tekan != "up") tekan = "down";
            });

        </script>
    </div>
</div>



</body>
</html>