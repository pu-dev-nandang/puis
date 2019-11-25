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

    #tableNewTicket td:nth-child(2), #tableDetailTicket td:nth-child(2) {
        text-align: center;
    }

</style>

<style>
    #tracking {
        margin-bottom:1rem
    }
    [class*=tracking-status-] p {
        margin:0;
        font-size:1.1rem;
        color:#fff;
        text-transform:uppercase;
        text-align:center
    }
    .tracking-list {
        border:1px solid #e5e5e5
    }
    .tracking-item {
        border-left:1px solid #e5e5e5;
        position:relative;
        padding:2rem 1.5rem .5rem 2.5rem;
        font-size: 12px;
        /*font-size:.9rem;*/

        margin-left:3rem;
        min-height:5rem
    }
    .tracking-item:last-child {
        padding-bottom:4rem
    }
    .tracking-item .tracking-date {
        margin-bottom:.5rem
    }
    .tracking-item .tracking-date span {
        color:#888;
        font-size:85%;
        padding-left:.4rem
    }
    .tracking-item .tracking-content {
        padding:.5rem .8rem;
        background-color:#f4f4f4;
        border-radius:.5rem
    }
    .tracking-item .tracking-content span {
        display:block;
        color:#888;
        font-size:85%
    }
    .tracking-item .tracking-icon {
        line-height:2.6rem;
        position:absolute;
        left:-1.3rem;
        width:2.6rem;
        height:2.6rem;
        text-align:center;
        border-radius:50%;
        font-size:1.1rem;
        background-color:#fff;
        color:#fff
    }
    .tracking-item .tracking-icon.status-intransit {
        color:#e5e5e5;
        border:1px solid #e5e5e5;
        font-size:.6rem
    }
    @media(min-width:992px) {
        .tracking-item {
            margin-left:11rem
        }
        .tracking-item .tracking-date {
            position:absolute;
            left:-10rem;
            width:7.5rem;
            text-align:right
        }
        .tracking-item .tracking-date span {
            display:block
        }
        .tracking-item .tracking-content {
            padding:0;
            background-color:transparent
        }
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
                                    <div class="ticket-division">Academic</div>
                                    <h2><a href="javascript:void(0);" class="showTicket">Art RamadaniArt RamadaniArt Ramadani</a></h2>
                                    <div class="ticket-submited">Nandang Mulyadi | 29 Jan 2019 08:00</div>
                                    <p>Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial two promise. Greatly who affixed suppose but enquire compact prepare all put. Added forth chief trees but rooms think may.</p>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2016064.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <div class="ticket-division">IT</div>
                                    <h2><a href="javascript:void(0);" class="showTicket">Job Meeting</a></h2>
                                    <div class="ticket-submited">Nandang Mulyadi | 29 Feb 2019 08:00</div>

                                    <p>You have a meeting at Laborator Office Today.</p>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2114002.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">

                                    <div class="ticket-division">Finance</div>
                                    <h2><a href="javascript:void(0);" class="showTicket">Job Meeting Lantai basah</a></h2>
                                    <div class="ticket-submited">Nandang Mulyadi | 29 Feb 2019 08:00</div>

                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

                                    <div style="margin-top: 5px;">
                                        <img src="https://i.pinimg.com/originals/36/ab/81/36ab81cd8d63cf7c4a08f39403698c77.jpg" style="max-width: 150px;">
                                    </div>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2014047.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">

                                    <div class="ticket-division">General Affair</div>
                                    <h2><a href="javascript:void(0);" class="showTicket">Arber Nushi changed his Profile Picture</a></h2>
                                    <div class="ticket-submited">Nandang Mulyadi | 29 Feb 2019 08:00</div>

                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>

                                    <div style="margin-top: 5px;">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4os91YBiXqwxbfL0abTIcQOy7jDSEsjIGwLUILG6T5gWjVGeqSQ&s" style="max-width: 150px;"  class="img-responsive img-rounded full-width">
                                    </div>

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
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/1014081.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <div class="ticket-division">Academic</div>
                                    <h2><a href="javascript:void(0);" class="showTicket">Art RamadaniArt RamadaniArt Ramadani</a></h2>
                                    <div class="ticket-submited">Nandang Mulyadi | 29 Jan 2019 08:00</div>
                                    <p>Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial two promise. Greatly who affixed suppose but enquire compact prepare all put. Added forth chief trees but rooms think may.</p>
                                    <div class="ticket-accepted">
                                        <div class="separator"><b>Accepted</b></div>
                                        Nandang Mulyadi |
                                        29 Januari 2019 09:00
                                        <div style="margin-top: 10px;">
                                            <p>
                                                From : Academic
                                                <br/>
                                                Assign to : Wanto, Nandang
                                                <br/>
                                                Transfer to : IT, Adum</p>
                                        </div>
                                        <div style="text-align: center;margin-top: 10px;">
                                            <a href="javascript:void(0);" class="showReadMoreTicket">Read more <i class="fa fa-angle-double-right"></i></a>
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

                                    <div class="ticket-accepted">
                                        <div class="separator"><b>Accepted</b></div>
                                        Nandang Mulyadi |
                                        29 Januari 2019 09:00
                                        <div style="margin-top: 10px;">
                                            <p>
                                                From : Academic
                                                <br/>
                                                Assign to : Wanto, Nandang
                                                <br/>
                                                Transfer to : IT, Adum</p>
                                        </div>
                                        <div style="text-align: center;margin-top: 10px;">
                                            <a href="javascript:void(0);" class="showReadMoreTicket">Read more <i class="fa fa-angle-double-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/2018018.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
                                </div>

                                <div class="timeline-label">
                                    <h2><a href="#">Arlind Nushi</a> <span>checked in at</span> <a href="#">Laborator</a></h2>

                                    <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, rem</p>

                                    <div class="ticket-accepted">
                                        <div class="separator"><b>Accepted</b></div>
                                        Nandang Mulyadi |
                                        29 Januari 2019 09:00
                                        <div style="margin-top: 10px;">
                                            <p>
                                                From : Academic
                                                <br/>
                                                Assign to : Wanto, Nandang
                                                <br/>
                                                Transfer to : IT, Adum</p>
                                        </div>
                                        <div style="text-align: center;margin-top: 10px;">
                                            <a href="javascript:void(0);" class="showReadMoreTicket">Read more <i class="fa fa-angle-double-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </article>


                        <article class="timeline-entry">

                            <div class="timeline-entry-inner">

                                <div class="timeline-icon">
                                    <img data-src="http://localhost:8080/siak3/uploads/employees/1016011.JPG" style="margin-top: -3px;" class="img-circle img-fitter" width="57">
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

    $(document).on('click','.showReadMoreTicket',function () {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Read More Ticket</h4>');

        var htmlss = '<div class="row">' +
            '' +
            '        <div class="col-md-12 col-lg-12">' +
            '            <div id="tracking-pre"></div>' +
            '            <div id="tracking">' +
            '' +
            '                <div class="thumbnail" style="border-radius: 0px;border-bottom: none;padding: 15px;">' +
            '                    <h3 style="margin-top: 0px;margin-bottom: 3px;"><b>Lorem Ipsum is simply dummy text of the printing</b></h3>' +
            '                    <div style="margin-bottom: 10px;color: cornflowerblue;">Nandang Mulyadi | 19 Januari 2019 09:00</div>' +
            '                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letrase' +
            '                </div>' +
            '' +
            '                <div class="tracking-list">' +
            '                    <div class="tracking-item">' +
            '                        <div class="tracking-icon status-intransit">' +
            '                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">' +
            '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
            '                            </svg>' +
            '                            <!-- <i class="fas fa-circle"></i> -->' +
            '                        </div>' +
            '                        <div class="tracking-date">Aug 10, 2018<span>05:01 PM</span></div>' +
            '                        <div class="tracking-content">DESTROYEDPER SHIPPER INSTRUCTION<span>KUALA LUMPUR (LOGISTICS HUB), MALAYSIA, MALAYSIA </span></div>' +
            '                    </div>' +
            '                    <div class="tracking-item">' +
            '                        <div class="tracking-icon status-intransit">' +
            '                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">' +
            '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
            '                            </svg>' +
            '                            <!-- <i class="fas fa-circle"></i> -->' +
            '                        </div>' +
            '                        <div class="tracking-date">Aug 10, 2018<span>11:19 AM</span></div>' +
            '                        <div class="tracking-content">SHIPMENT DELAYSHIPPER INSTRUCTION TO DESTROY<span>SHENZHEN, CHINA, PEOPLE\'S REPUBLIC</span></div>' +
            '                    </div>' +
            '                    <div class="tracking-item">' +
            '                        <div class="tracking-icon status-intransit">' +
            '                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">' +
            '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
            '                            </svg>' +
            '                            <!-- <i class="fas fa-circle"></i> -->' +
            '                        </div>' +
            '                        <div class="tracking-date">Jul 27, 2018<span>04:08 PM</span></div>' +
            '                        <div class="tracking-content">DELIVERY ADVICERequest Instruction from ORIGIN<span>KUALA LUMPUR (LOGISTICS HUB), MALAYSIA, MALAYSIA</span></div>' +
            '                    </div>' +
            '                    <div class="tracking-item">' +
            '                        <div class="tracking-icon status-intransit">' +
            '                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">' +
            '                                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>' +
            '                            </svg>' +
            '                            <!-- <i class="fas fa-circle"></i> -->' +
            '                        </div>' +
            '                        <div class="tracking-date">Jul 20, 2018<span>05:25 PM</span></div>' +
            '                        <div class="tracking-content">Delivery InfoCLOSED-OFFICE/HOUSE CLOSED<span>KUALA LUMPUR (LOGISTICS HUB), MALAYSIA, MALAYSIA</span></div>' +
            '                    </div>' +
            '' +
            '                </div>' +
            '            </div>' +
            '        </div>' +
            '    </div>';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '');


        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    });

</script>

