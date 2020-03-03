<style>
    ul.timeline {
        list-style-type: none;
        position: relative;
    }
    ul.timeline:before {
        content: ' ';
        background: #d4d9df;
        display: inline-block;
        position: absolute;
        left: 29px;
        width: 2px;
        height: 100%;
        z-index: 400;
    }
    ul.timeline > li {
        margin: 20px 0;
        padding-left: 20px;
    }
    ul.timeline > li.lecturer:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #22c0e8;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }


    ul.timeline > li.student:before {
        content: ' ';
        background: white;
        display: inline-block;
        position: absolute;
        border-radius: 50%;
        border: 3px solid #ff9800;
        left: 20px;
        width: 20px;
        height: 20px;
        z-index: 400;
    }

    .h4-comment {
        padding-bottom: 3px;
        border-bottom: 1px solid #ccc;
        margin-top: 0px;
    }

    .tb-lecturer {
        padding: 10px;
        border: 1px solid #b7b7b7;
    }
    .tb-student {
        padding: 10px;
        border: 1px solid #b7b7b7;
    }

    .quote {
        border-top: 1px solid #ccc;
        padding-top: 5px;
        margin-top: 6px;
        font-size: 16px;
        font-weight: bold;
        color: #9E9E9E;
        text-align: center;
    }

    .label-user {
        position: relative;
        left: 0px;
    }

    h4.heading-small {
        border-left: 10px solid #ff9800;
        padding-left: 10px;
        margin-bottom: 15px;
        /* font-weight: bold; */
    }
</style>


<div class="row" style="margin-top: 30px;">
    <div class="col-md-8 col-md-offset-2">
        <div class="thumbnail">
            <div class="row">
                <div class="col-xs-12">
                    <a href="<?php echo base_url('student-life/tracer-alumni/forum'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left margin-right"></i> Back to list topic</a>

                    <div class="well" id="divViewTopic" style="margin-top: 10px;">
                        <h4 class="heading-small" style="margin-top: 0px;"><span id="viewTitle"></span><br/><small id="viewCreate"></small></h4>
                        <p id="viewDescription"></p>
                        <input id="formTopicID" class="hide" readonly/>
                        <input id="formCommentID" class="hide" readonly/>
                    </div>
                    <div style="text-align: right;">
                        <button class="btn btn-sm btn-success" id="btnGoToComment">Add Comment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="viewDiv">
    <div class="col-md-8 col-md-offset-2">
        <div class="thumbnail">
            <div class="row">
                <div class="col-xs-12">
                    <h4>Comments</h4>
                    <ul class="timeline" id="rowComment"></ul>

                    <hr/>
                    <div class="well" id="divComment">
                        <div style="text-align: center;">
                            <h4 style="margin-top: 0px;">Add new comment</h4>
                        </div>
                        <div class="form-group">
                            <span id="viewQuote"></span>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="formComment" rows="5" placeholder="Write your comment . . ." required></textarea>
                        </div>
                        <div class="form-group" style="text-align: right;">
                            <button class="btn btn-success" id="btnActionComment">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // deklarasi rest setting
    const CustomPost = <?php echo json_encode($customPost) ?>;
</script>
<script type="text/javascript" src="<?php echo base_url();?>js/student-life/tracer-alumni/App_forum_alumni_detail.js"></script>
<script type="text/javascript">
    let AppThis = new App_forum_alumni_detail();
    let DataID = "<?php echo $ForumID ?>";
    $(document).ready(function(e){
        AppThis.LoadDefault();
    })

    $(document).off('click','#btnGoToComment').on('click','#btnGoToComment',function(e){
        $("html, body").animate({ scrollTop: $(document).height() }, 1000,function () {
            $('#formComment').focus();
        });
    })

    $(document).off('click','.btnQuote').on('click','.btnQuote',function(e){
        let token = $(this).attr('token');
        let de = jwt_decode(token);
        $('#btnActionComment').attr('Forum_CommentID',de['Forum_CommentID']);
        $("html, body").animate({ scrollTop: $(document).height() }, 1000,function () {
            $('#formComment').focus();
        });
    })

    $(document).off('click','#btnActionComment').on('click','#btnActionComment',function(e){
        let itsme = $(this);
        let Forum_CommentID = itsme.attr('Forum_CommentID'); // for parent
        AppThis.submit_action_comment(itsme,Forum_CommentID);
    })

    $(document).off('click','#btnGoToComment').on('click','#btnGoToComment',function(e){
        $("html, body").animate({ scrollTop: $(document).height() }, 1000,function () {
            $('#formComment').focus();
        });
    })

    $(document).off('click','.btnQuote').on('click','.btnQuote',function(e){
        let token = $(this).attr('token');
        let de = jwt_decode(token);
        $('#btnActionComment').attr('Forum_CommentID',de['Forum_CommentID']);
        $("html, body").animate({ scrollTop: $(document).height() }, 1000,function () {
            $('#formComment').focus();
        });
    })

    $(document).off('click','#btnActionComment').on('click','#btnActionComment',function(e){
        let itsme = $(this);
        let Forum_CommentID = itsme.attr('Forum_CommentID'); // for parent
        AppThis.submit_action_comment(itsme,Forum_CommentID);
    })
</script>
