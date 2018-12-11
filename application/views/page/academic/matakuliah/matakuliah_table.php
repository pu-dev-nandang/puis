
<div>
    <table id="tableMK2" class="table table-striped table-bordered table-hover table-tabletools table-responsive">
        <thead>
        <tr>
            <th style="width: 50px;">No</th>
            <th style="width: 100px">Code</th>
            <th>Name (Indonesian)</th>
            <th>Name (English)</th>
            <th>Study Programme</th>
            <th style="width: 50px;">Type</th>
        </tr>
        </thead>
        <tbody>
        <?php $no=1; foreach ($data_mk as $item_mk) {
            $TypeMK = '<span class="label label-default">Mandiri</span>';
            if($item_mk['TypeMK']=='2'){
                $TypeMK = '<span class="label label-primary"><b>MKDU</b></span>';
            } else if($item_mk['TypeMK']=='3'){
                    $TypeMK = '<span class="label label-warning"><b>MKU</b></span>';
                }
            ?>
            <tr>
                <td class="td-center">
                    <div><?php echo $no++; ?></div>
                </td>
                <td>
                    <div><?php echo $item_mk['MKCode']; ?></div>
                </td>
                <td>
                    <div>
                        <a href="javascript:void(0)" data-id="<?php echo $item_mk['mkID']; ?>" class="btn-mk-action" ><b><?php echo $item_mk['Name']; ?></b></a>
                    </div>
                </td>
                <td>
                    <div>
                        <i><?php echo $item_mk['NameEng']; ?></i>
                    </div>
                </td>

<!--                <td>--><?php //echo $item_mk['Code'].' | '.$item_mk['NameProdiEng']; ?><!--</td>-->
                <td><?php echo $item_mk['NameProdiEng']; ?></td>
                <td><?php echo $TypeMK; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        var table = $('#tableMK2').DataTable({
            'iDisplayLength' : 10,
            "sDom": "<'row'<'dataTables_header clearfix'<'col-md-3'l><'col-md-9'Tf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>", // T is new
            "oTableTools": {
                "aButtons": [
                    {
                        "sExtends" : "xls",
                        "sButtonText" : '<i class="fa fa-download" aria-hidden="true"></i> Excel',
                    },
                    {
                        "sExtends" : "pdf",
                        "sButtonText" : '<i class="fa fa-download" aria-hidden="true"></i> PDF',
                        "sPdfOrientation" : "landscape",
                        "sPdfMessage" : "Daftar Seluruh Mata Kuliah"
                    }

                ],
                "sSwfPath": "../assets/template/plugins/datatables/tabletools/swf/copy_csv_xls_pdf.swf"
            },
            initComplete: function () {
                this.api().columns().every( function () {
                    var column = this;
                    var select = $('<select class="form-control filter-prodi"><option selected disabled>--- Select Programme Study ---</option><option value="">All</option></select>')
                        .appendTo( $('.dataTables_header .col-md-9') )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
                    column.data().unique().sort().each( function ( d, j ) {
                        var f = d.split('div');
                        if(f.length<=1){
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } else {
                            select.remove();
                            // select.addClass('hide');
                        }
                    } );
                } );
            }

        });
    });
</script>


