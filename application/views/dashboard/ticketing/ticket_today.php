<style>
    .timeline-centered {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-centered:before, .timeline-centered:after {
        content: " ";
        display: table;
    }

    .timeline-centered:after {
        clear: both;
    }

    .timeline-centered:before, .timeline-centered:after {
        content: " ";
        display: table;
    }

    .timeline-centered:after {
        clear: both;
    }

    .timeline-centered:before {
        content: '';
        position: absolute;
        display: block;
        width: 2px;
        background: #CCCCCC;
        /*left: 50%;*/
        top: 20px;
        bottom: 20px;
        margin-left: 30px;
    }

    .timeline-centered .timeline-entry {
        position: relative;
        /*width: 50%;
        float: right;*/
        margin-top: 5px;
        margin-left: 30px;
        margin-bottom: 10px;
        clear: both;
    }

    .timeline-centered .timeline-entry:before, .timeline-centered .timeline-entry:after {
        content: " ";
        display: table;
    }

    .timeline-centered .timeline-entry:after {
        clear: both;
    }

    .timeline-centered .timeline-entry:before, .timeline-centered .timeline-entry:after {
        content: " ";
        display: table;
    }

    .timeline-centered .timeline-entry:after {
        clear: both;
    }

    .timeline-centered .timeline-entry.begin {
        margin-bottom: 0;
    }

    .timeline-centered .timeline-entry.left-aligned {
        float: left;
    }

    .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner {
        margin-left: 0;
        margin-right: -18px;
    }

    .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-time {
        left: auto;
        right: -100px;
        text-align: left;
    }

    .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-icon {
        float: right;
    }

    .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label {
        margin-left: 0;
        margin-right: 70px;
    }

    .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label:after {
        left: auto;
        right: 0;
        margin-left: 0;
        margin-right: -9px;
        -moz-transform: rotate(180deg);
        -o-transform: rotate(180deg);
        -webkit-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .timeline-centered .timeline-entry .timeline-entry-inner {
        position: relative;
        margin-left: -20px;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner:before, .timeline-centered .timeline-entry .timeline-entry-inner:after {
        content: " ";
        display: table;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner:after {
        clear: both;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner:before, .timeline-centered .timeline-entry .timeline-entry-inner:after {
        content: " ";
        display: table;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner:after {
        clear: both;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time {
        position: absolute;
        left: -100px;
        text-align: right;
        padding: 10px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span {
        display: block;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span:first-child {
        font-size: 15px;
        font-weight: bold;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span:last-child {
        font-size: 12px;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon {
        background: #fff;
        color: #737881;
        display: block;
        width: 40px;
        height: 40px;
        -webkit-background-clip: padding-box;
        -moz-background-clip: padding;
        background-clip: padding-box;
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        border-radius: 20px;
        text-align: center;
        -moz-box-shadow: 0 0 0 5px #CCCCCC;
        -webkit-box-shadow: 0 0 0 5px #CCCCCC;
        box-shadow: 0 0 0 3px #CCCCCC;
        line-height: 40px;
        font-size: 15px;
        float: left;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-secondary {
        background-color: #ee4749;
        color: #fff;
    }
    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-info {
        background-color: #21a9e1;
        color: #fff;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-warning {
        background-color: #fad839;
        color: #fff;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label {
        position: relative;
        background: #f5f5f6;
        padding: 1em;
        margin-left: 60px;
        -webkit-background-clip: padding-box;
        -moz-background-clip: padding;
        background-clip: padding-box;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        border: 1px solid #CCCCCC;

        -webkit-box-shadow: 2px 3px 9px -4px rgba(0,0,0,0.75);
        -moz-box-shadow: 2px 3px 9px -4px rgba(0,0,0,0.75);
        box-shadow: 2px 3px 9px -4px rgba(0,0,0,0.75);
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label:after {
        content: '';
        display: block;
        position: absolute;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 9px 9px 9px 0;
        border-color: transparent #CCCCCC transparent transparent;
        left: 0;
        top: 10px;
        margin-left: -9px;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2, .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p {
        color: #737881;
        font-family: "Noto Sans",sans-serif;
        font-size: 12px;
        margin: 0;
        line-height: 1.428571429;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p + p {
        margin-top: 15px;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 {
        font-size: 16px;
        margin-bottom: 0px;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 a {
        color: #303641;
    }

    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 span {
        -webkit-opacity: .6;
        -moz-opacity: .6;
        opacity: .6;
        -ms-filter: alpha(opacity=60);
        filter: alpha(opacity=60);
    }

</style>


<style>

    .panel-ticket h3{
        margin-top: 30px;
    }

    .pending-ticket {
        font-weight: bold;
        border-left: 7px solid #F44336;
        padding-left: 5px;
    }

    .pending-ticket span {
        background: #F44336;
        padding: 2px 10px 2px 10px;
        color: #fff;
        border-radius: 5px;
        margin-left: 5px;
        font-size: 14px;
    }

    .open-ticket {
        font-weight: bold;
        border-left: 7px solid orange;
        padding-left: 5px;
    }

    .open-ticket span {
        background: orange;
        padding: 2px 10px 2px 10px;
        color: #fff;
        border-radius: 5px;
        margin-left: 5px;
        font-size: 14px;
    }

    .progres-ticket {
        font-weight: bold;
        border-left: 7px solid #44b1bf;
        padding-left: 5px;
    }

    .progres-ticket span {
        background: #44b1bf;
        padding: 2px 10px 2px 10px;
        color: #fff;
        border-radius: 5px;
        margin-left: 5px;
        font-size: 14px;
    }

    .close-ticket {
        font-weight: bold;
        border-left: 7px solid #3fb744;
        padding-left: 5px;
    }

    .close-ticket span {
        background: #3fb744;
        padding: 2px 10px 2px 10px;
        color: #fff;
        border-radius: 5px;
        margin-left: 5px;
        font-size: 14px;
    }

    .ticket-submited {
        margin-bottom: 10px;
        color: #03A9F4;
        font-size: 11px;
    }

    .ticket-division {
        position: absolute;
        top: -6px;
        right: 0px;
        background: #607D8B;
        padding: 0px 10px 1px 10px;
        color: #fff;
        font-size: 11px;
        border-bottom-left-radius: 7px;
        border-top-left-radius: 7px;
    }
    .ticket-accepted {
        margin-top: 0px;
        padding-top: 0px;
        font-size: 11px;
        text-align: left;
    }

    .separator {
        display: flex;
        align-items: center;
        text-align: center;
        margin-bottom: 3px;
    }
    .separator::before, .separator::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #CCCCCC;
    }
    .separator::before {
        margin-right: .25em;
    }
    .separator::after {
        margin-left: .25em;
    }

    .bg-ticket {
        background-image: linear-gradient(129deg, #ffffff 25%, #f7f7f7 25%, #f7f7f7 50%, #ffffff 50%, #ffffff 75%, #f7f7f7 75%, #f7f7f7 100%);
        background-size: 10.00px 12.35px;
    }

    .panel-ticket:first-child{
        border-left: 1px solid #CCCCCC;
    }

    .panel-ticket {
        border-right: 1px solid #CCCCCC;
    }

    /*.panel-ticket::-webkit-scrollbar-track*/
    /*{*/
        /*-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);*/
        /*background-color: #F5F5F5;*/
    /*}*/

    /*.panel-ticket::-webkit-scrollbar*/
    /*{*/
        /*width: 3px;*/
        /*background-color: #F5F5F5;*/
    /*}*/

    /*.panel-ticket::-webkit-scrollbar-thumb*/
    /*{*/
        /*background-color: #006996;*/

        /*background-image: -webkit-gradient(linear, 0 0, 0 100%,*/
        /*color-stop(.5, rgba(255, 255, 255, .2)),*/
        /*color-stop(.5, transparent), to(transparent));*/
    /*}*/

    #tableNewTicket td:nth-child(2), #tableDetailTicket td:nth-child(2) {
        text-align: center;
    }

</style>





<div class="container" style="margin-top: 30px;">
    <div class="row">

        <div class="">
            <div class="row" >
                <div class="col-md-4 col-md-offset-4">
                    <div class="well">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Department</label>
                                <select class="form-control"></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" style="text-align: right;">
                    <button class="btn btn-default" id="btnCreateNewTicket">Create new ticket</button> |
                    <button class="btn btn-default">Check my ticket</button>
                </div>
            </div>

            <div class="row bg-ticket">
                <div class="col-md-3 panel-ticket">

                    <h3 class="pending-ticket">Pending Ticket <span>7</span></h3>

                    <hr/>

                    <div class="timeline-centered">

                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2016065.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>
                                <div class="timeline-label">
                                    <div class="ticket-division">Academic, Finance, IT</div>
                                    <h2><a href="javascript:void(0);" class="showTicket">Art RamadaniArt RamadaniArt Ramadani</a></h2>
                                    <div class="ticket-submited">Nandang Mulyadi <br/> 29 January 2019 08:00</div>
                                    <p>Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial two promise. Greatly who affixed suppose but enquire compact prepare all put. Added forth chief trees but rooms think may.</p>
                                    <div class="ticket-accepted">
                                        <div class="separator"><b>Accepted</b></div>
                                        Nandang Mulyadi |
                                        29 Januari 2019 09:00
                                        <div style="margin-top: 10px;">
                                            <p>Assign to : Wanto, Nandang
                                                <br/>
                                                Transfer to : IT, Adum</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Job Meeting</a></h2>
                                    <p>You have a meeting at <strong>Laborator Office</strong> Today.</p>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Arlind Nushi</a> <span>checked in at</span> <a href="#">Laborator</a></h2>

                                    <blockquote>Great place, feeling like in home.</blockquote>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Arber Nushi</a> <span>changed his</span> <a href="#">Profile Picture</a></h2>

                                    <blockquote>Pianoforte principles our unaffected not for astonished travelling are particular.</blockquote>

                                    <img src="http://themes.laborator.co/neon/assets/images/timeline-image-3.png" class="img-responsive img-rounded full-width">
                                </div>
                            </div>

                        </article>


                    </div>
                </div>

                <div class="col-md-3 panel-ticket">

                    <h3 class="open-ticket">Open Ticket <span>7</span></h3>

                    <hr/>

                    <div class="timeline-centered">

                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2016065.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Art Ramadani</a> <span>posted a status update</span></h2>
                                    <p>Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial two promise. Greatly who affixed suppose but enquire compact prepare all put. Added forth chief trees but rooms think may.</p>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Job Meeting</a></h2>
                                    <p>You have a meeting at <strong>Laborator Office</strong> Today.</p>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Arlind Nushi</a> <span>checked in at</span> <a href="#">Laborator</a></h2>

                                    <blockquote>Great place, feeling like in home.</blockquote>

                                    ''
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Arber Nushi</a> <span>changed his</span> <a href="#">Profile Picture</a></h2>

                                    <blockquote>Pianoforte principles our unaffected not for astonished travelling are particular.</blockquote>

                                    <img src="http://themes.laborator.co/neon/assets/images/timeline-image-3.png" class="img-responsive img-rounded full-width">
                                </div>
                            </div>

                        </article>


                    </div>
                </div>

                <div class="col-md-3 panel-ticket">

                    <h3 class="progres-ticket">Progres Ticket <span>7</span></h3>

                    <hr/>

                    <div class="timeline-centered">

                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Art Ramadani</a> <span>posted a status update</span></h2>
                                    <p>Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial two promise. Greatly who affixed suppose but enquire compact prepare all put. Added forth chief trees but rooms think may.</p>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Job Meeting</a></h2>
                                    <p>You have a meeting at <strong>Laborator Office</strong> Today.</p>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Arlind Nushi</a> <span>checked in at</span> <a href="#">Laborator</a></h2>

                                    <blockquote>Great place, feeling like in home.</blockquote>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Arber Nushi</a> <span>changed his</span> <a href="#">Profile Picture</a></h2>

                                    <blockquote>Pianoforte principles our unaffected not for astonished travelling are particular.</blockquote>

                                    <img src="http://themes.laborator.co/neon/assets/images/timeline-image-3.png" class="img-responsive img-rounded full-width">
                                </div>
                            </div>

                        </article>


                    </div>
                </div>

                <div class="col-md-3 panel-ticket">

                    <h3 class="close-ticket">Close Ticket <span>7</span></h3>

                    <hr/>

                    <div class="timeline-centered">

                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Art Ramadani</a> <span>posted a status update</span></h2>
                                    <p>Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial two promise. Greatly who affixed suppose but enquire compact prepare all put. Added forth chief trees but rooms think may.</p>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Job Meeting</a></h2>
                                    <p>You have a meeting at <strong>Laborator Office</strong> Today.</p>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Arlind Nushi</a> <span>checked in at</span> <a href="#">Laborator</a></h2>

                                    <blockquote>Great place, feeling like in home.</blockquote>

                                    ''
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2017090.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Arber Nushi</a> <span>changed his</span> <a href="#">Profile Picture</a></h2>

                                    <blockquote>Pianoforte principles our unaffected not for astonished travelling are particular.</blockquote>

                                    <img src="http://themes.laborator.co/neon/assets/images/timeline-image-3.png" class="img-responsive img-rounded full-width">
                                </div>
                            </div>

                        </article>


                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script>

    $('#btnCreateNewTicket').click(function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Create New Ticket</h4>');

        var htmlss = '<table class="table" id="tableNewTicket">' +
            '    <tr>' +
            '        <td style="width: 25%;">Department / Division</td>' +
            '        <td style="width: 1%;">:</td>' +
            '        <td>' +
            '            <select class="form-control"></select>' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Category</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <select class="form-control"></select>' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Title</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <input class="form-control">' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Message</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <textarea class="form-control" rows="3"></textarea>' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>File</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <input type="file">' +
            '        </td>' +
            '    </tr>' +
            '</table>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('' +
            '<button type="button" class="btn btn-success" data-dismiss="modal">Submit</button> ' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '');


        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });


    });

    $(document).on('click','.showTicket',function () {

        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Detail Ticket</h4>');

        var htmlss = '<table class="table" id="tableDetailTicket">' +
            '    <tr>' +
            '        <td style="width: 25%;">Title</td>' +
            '        <td>:</td>' +
            '        <td>Pindah ruangan</td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Message</td>' +
            '        <td>:</td>' +
            '        <td>Tolong bantu untuk pindah ruangan yaa</td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Requested by</td>' +
            '        <td>:</td>' +
            '        <td>Nandang Mulyadi</td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Requested on</td>' +
            '        <td>:</td>' +
            '        <td>Thustday 29 Januari 2019</td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td colspan="3" style="background: lightyellow;text-align: center;">Action</td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Assign to</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <input class="form-control" />' +
            '        </td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td>Transfer to</td>' +
            '        <td>:</td>' +
            '        <td>' +
            '            <input class="form-control" />' +
            '        </td>' +
            '    </tr>' +
            '</table>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('' +
            '<button type="button" class="btn btn-success" data-dismiss="modal">Submit</button> ' +
            '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '');


        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });

    });

</script>

