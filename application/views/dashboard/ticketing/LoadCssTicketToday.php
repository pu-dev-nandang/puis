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

    .ticket-number {
        position: absolute;
        top: 45px;
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