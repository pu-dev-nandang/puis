<style>
    #pinBoot {
        position: relative;
        max-width: 100%;
        width: 100%;
    }
    #panel-ticket img {
        width: 100%;
        max-width: 100%;
        height: auto;
        margin-bottom: 10px;
        border-radius: 10px;

    }
    .white-panel {
        position: absolute;
        background: #ffffff;
        border: 1px solid #b5b3b3;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.3);
        padding: 10px;
        border-radius: 10px;
    }
    .white-panel .white-panel-detail {
        border-top: 1px solid #CCCCCC;
    }

    .white-panel-detail .col-md-9 {
        padding-left: 0px !important;
    }

    .white-panel-detail h5 {
        border-left: 7px solid orange;
        padding-left: 7px;
        font-weight: bold;
    }

    .white-panel .white-panel-profile {
        /*max-width: 30px !important;*/
        border-radius: 10px !important;
        /*margin-right: 15px !important;*/
    }
    /*
    stylize any heading tags withing white-panel below
    */
    /*.white-panel .row {*/
        /*border-bottom-length: 50px;*/
        /*border-bottom-color: #efefef;*/
        /*border-bottom-width: 1px;*/
        /*border-bottom-style: solid;*/
    /*}*/

    .white-panel h1 {
        font-size: 1em;
    }
    .white-panel h1 a {
        color: #A92733;
    }
    .white-panel:hover {
        box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.5);
        margin-top: -5px;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }
</style>

<div class="row" >
    <div class="container">

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="well">
                    <input class="form-control form-lg" placeholder="Search by title, no ticket, user">
                </div>
                <hr/>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <?php
                $noUrut = 10001;
                $char = "TIC2019";
                $kodeBarang = $char . sprintf("%04s", $noUrut);
                echo $kodeBarang;
                ?>
                <button class="btn btn-lg btn-success" id="btnCreateNewTicket">Create New Ticket</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" id="panel-ticket">
                <section id="pinBoot">

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2017090.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Nandang Mulyadi</b>
                                <br/>2017090 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 1</a></h5>
                            <img src="http://i.imgur.com/sDLIAZD.png" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in </p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2018018.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Alhadi Rahman PU 22</b>
                                <br/>2018018 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 2</a></h5>
                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2016065.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Novita Riani Br Ginting</b>
                                <br/>2016065 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 3</a></h5>

                            <img src="http://i.imgur.com/xOIMvAe.jpg" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                        </div>
                    </article>


                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/1016060.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Richa Deswita</b>
                                <br/>1016060 | SAS Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 4</a></h5>

                            <!--                        <img src="http://i.imgur.com/3gXW3L3.jpg" alt="">-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2014024.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Sandra Dewi</b>
                                <br/>2014024 | Finance Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 5</a></h5>
                            <img src="http://i.imgur.com/kFFpuKA.jpg" alt="">
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>
                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2017090.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Nandang Mulyadi</b>
                                <br/>2017090 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 1</a></h5>
                            <img src="http://i.imgur.com/sDLIAZD.png" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in </p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2018018.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Alhadi Rahman PU 22</b>
                                <br/>2018018 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 2</a></h5>
                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2016065.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Novita Riani Br Ginting</b>
                                <br/>2016065 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 3</a></h5>

                            <img src="http://i.imgur.com/xOIMvAe.jpg" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                        </div>
                    </article>


                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/1016060.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Richa Deswita</b>
                                <br/>1016060 | SAS Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 4</a></h5>

                            <!--                        <img src="http://i.imgur.com/3gXW3L3.jpg" alt="">-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2014024.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Sandra Dewi</b>
                                <br/>2014024 | Finance Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 5</a></h5>
                            <img src="http://i.imgur.com/kFFpuKA.jpg" alt="">
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>
                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2017090.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Nandang Mulyadi</b>
                                <br/>2017090 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 1</a></h5>
                            <img src="http://i.imgur.com/sDLIAZD.png" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in </p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2018018.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Alhadi Rahman PU 22</b>
                                <br/>2018018 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 2</a></h5>
                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2016065.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Novita Riani Br Ginting</b>
                                <br/>2016065 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 3</a></h5>

                            <img src="http://i.imgur.com/xOIMvAe.jpg" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                        </div>
                    </article>


                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/1016060.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Richa Deswita</b>
                                <br/>1016060 | SAS Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 4</a></h5>

                            <!--                        <img src="http://i.imgur.com/3gXW3L3.jpg" alt="">-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2014024.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Sandra Dewi</b>
                                <br/>2014024 | Finance Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 5</a></h5>
                            <img src="http://i.imgur.com/kFFpuKA.jpg" alt="">
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>
                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2017090.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Nandang Mulyadi</b>
                                <br/>2017090 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 1</a></h5>
                            <img src="http://i.imgur.com/sDLIAZD.png" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in </p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2018018.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Alhadi Rahman PU 22</b>
                                <br/>2018018 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 2</a></h5>
                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2016065.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Novita Riani Br Ginting</b>
                                <br/>2016065 | IT Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 3</a></h5>

                            <img src="http://i.imgur.com/xOIMvAe.jpg" alt="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                        </div>
                    </article>


                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/1016060.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Richa Deswita</b>
                                <br/>1016060 | SAS Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 4</a></h5>

                            <!--                        <img src="http://i.imgur.com/3gXW3L3.jpg" alt="">-->
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>

                    <article class="white-panel">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="white-panel-profile" src="http://localhost:8080/siak3/uploads/employees/2014024.JPG">
                            </div>
                            <div class="col-md-9">
                                <b>Sandra Dewi</b>
                                <br/>2014024 | Finance Department
                            </div>
                        </div>
                        <div class="white-panel-detail">
                            <h5><a href="#">Title 5</a></h5>
                            <img src="http://i.imgur.com/kFFpuKA.jpg" alt="">
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </article>



                </section>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('.fixed-header').addClass('sidebar-closed');
        $('#pinBoot').pinterest_grid({
            no_columns: 5,
            padding_x: 10,
            padding_y: 10,
            margin_bottom: 50,
            single_column_breakpoint: 700
        });

        var privateName = ID();
        var o = { 'public': 'foo' };
        o[privateName] = 'bar';

        console.log(o);
        console.log(privateName);
    });
    
    $('#btnCreateNewTicket').click(function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Create new ticket</h4>');

        var htmlss = '<div class="row">' +
            '    <div class="col-md-12">' +
            '        <div class="form-group">' +
            '            <div class="row">' +
            '                <div class="col-xs-5">' +
            '                    <label>Department</label>' +
            '                    <select class="form-control"></select>' +
            '                </div>' +
            '                <div class="col-xs-5">' +
            '                    <label>Kategory</label>' +
            '                    <select class="form-control"></select>' +
            '                </div>' +
            '            </div>' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Title</label>' +
            '            <input class="form-control">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Message</label>' +
            '            <textarea class="form-control" rows="3"></textarea>' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <label>Files</label>' +
            '            <input type="file">' +
            '        </div>' +
            '        <div class="form-group">' +
            '            <div style="text-align: right;">' +
            '                <button class="btn btn-success">Submit</button>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '</div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        // $('#GlobalModal').on('shown.bs.modal', function () {
        //     $('#formSimpleSearch').focus();
        // });

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });


    // Generate unique IDs for use as pseudo-private/protected names.
    // Similar in concept to
    // <http://wiki.ecmascript.org/doku.php?id=strawman:names>.
    //
    // The goals of this function are twofold:
    //
    // * Provide a way to generate a string guaranteed to be unique when compared
    //   to other strings generated by this function.
    // * Make the string complex enough that it is highly unlikely to be
    //   accidentally duplicated by hand (this is key if you're using `ID`
    //   as a private/protected name on an object).
    //
    // Use:
    //
    //     var privateName = ID();
    //     var o = { 'public': 'foo' };
    //     o[privateName] = 'bar';
    var ID = function () {
        // Math.random should be unique because of its seeding algorithm.
        // Convert it to base 36 (numbers + letters), and grab the first 9 characters
        // after the decimal.
        return 't-' + Math.random().toString(36).substr(2, 9);
    };

    (function ($, window, document, undefined) {
        var pluginName = 'pinterest_grid',
            defaults = {
                padding_x: 10,
                padding_y: 10,
                no_columns: 3,
                margin_bottom: 50,
                single_column_breakpoint: 700
            },
            columns,
            $article,
            article_width;

        function Plugin(element, options) {
            this.element = element;
            this.options = $.extend({}, defaults, options) ;
            this._defaults = defaults;
            this._name = pluginName;
            this.init();
        }

        Plugin.prototype.init = function () {
            var self = this,
                resize_finish;

            $(window).resize(function() {
                clearTimeout(resize_finish);
                resize_finish = setTimeout( function () {
                    self.make_layout_change(self);
                }, 11);
            });

            self.make_layout_change(self);

            setTimeout(function() {
                $(window).resize();
            }, 500);
        };

        Plugin.prototype.calculate = function (single_column_mode) {
            var self = this,
                tallest = 0,
                row = 0,
                $container = $(this.element),
                container_width = $container.width();
            $article = $(this.element).children();

            if(single_column_mode === true) {
                article_width = $container.width() - self.options.padding_x;
            } else {
                article_width = ($container.width() - self.options.padding_x * self.options.no_columns) / self.options.no_columns;
            }

            $article.each(function() {
                $(this).css('width', article_width);
            });

            columns = self.options.no_columns;

            $article.each(function(index) {
                var current_column,
                    left_out = 0,
                    top = 0,
                    $this = $(this),
                    prevAll = $this.prevAll(),
                    tallest = 0;

                if(single_column_mode === false) {
                    current_column = (index % columns);
                } else {
                    current_column = 0;
                }

                for(var t = 0; t < columns; t++) {
                    $this.removeClass('c'+t);
                }

                if(index % columns === 0) {
                    row++;
                }

                $this.addClass('c' + current_column);
                $this.addClass('r' + row);

                prevAll.each(function(index) {
                    if($(this).hasClass('c' + current_column)) {
                        top += $(this).outerHeight() + self.options.padding_y;
                    }
                });

                if(single_column_mode === true) {
                    left_out = 0;
                } else {
                    left_out = (index % columns) * (article_width + self.options.padding_x);
                }

                $this.css({
                    'left': left_out,
                    'top' : top
                });
            });

            this.tallest($container);
            $(window).resize();
        };

        Plugin.prototype.tallest = function (_container) {
            var column_heights = [],
                largest = 0;

            for(var z = 0; z < columns; z++) {
                var temp_height = 0;
                _container.find('.c'+z).each(function() {
                    temp_height += $(this).outerHeight();
                });
                column_heights[z] = temp_height;
            }

            largest = Math.max.apply(Math, column_heights);
            _container.css('height', largest + (this.options.padding_y + this.options.margin_bottom));
        };

        Plugin.prototype.make_layout_change = function (_self) {
            if($(window).width() < _self.options.single_column_breakpoint) {
                _self.calculate(true);
            } else {
                _self.calculate(false);
            }
        };

        $.fn[pluginName] = function (options) {
            return this.each(function () {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName,
                        new Plugin(this, options));
                }
            });
        }

    })(jQuery, window, document);
</script>