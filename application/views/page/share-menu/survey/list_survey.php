

<div class="row">
    <div class="col-md-12">
        <button class="btn btn-success pull-right" id="btnAddSurvey">Add Survey</button>
    </div>
</div>

<div class=""></div>


<script>

    $('#btnAddSurvey').click(function () {

    });

    function updateSurvey() {
        $('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title">Simple Search</h4>');

        var htmlss = '';

        $('#GlobalModal .modal-body').html(htmlss);

        $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

        $('#GlobalModal').on('shown.bs.modal', function () {
            $('#formSimpleSearch').focus();
        });

        $('#GlobalModal').modal({
            'show' : true,
            'backdrop' : 'static'
        });
    }

</script>