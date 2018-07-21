<link href="<?php echo base_url('assets/custom/jquery-ui.css'); ?>" rel="stylesheet" type="text/css" />
<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Log Virtual Account</h4>
            </div>
            <div class="widget-content">
                <!-- <div class = 'row'> -->
                	<div id= "loadtableMenu"></div>
                <!-- </div> -->
                <!-- -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        loadTableEvent(loadDataEvent);
    });

   function loadTableEvent(callback)
   {
       // Some code
       // console.log('test');
       $("#loadtableMenu").empty();
       var table = '<table class="table table-striped table-bordered table-hover table-checkable datatable" id ="EventTbl">'+
       '<thead>'+
           '<tr>'+
               '<th style="width: 106px;">No</th>'+
               '<th style="width: 106px;">Filename</th>'+
               '<th style="width: 106px;">Download</th>'+
           '</tr>'+
       '</thead>'+
       '<tbody>'+
       '</tbody>'+
       '</table>';
       //$("#loadtableNow").empty();
       $("#loadtableMenu").html(table);

       /*if (typeof callback === 'function') { 
           callback(); 
       }*/
       callback();
   }

   function loadDataEvent()
   {
       var url = base_url_js+'finance/listfile_va';
   // loading_page('#loadtableNow');
       $.post(url,function (data_json) {
           var response = jQuery.parseJSON(data_json);
           // console.log(response);
           // $("#loadingProcess").remove();
           var no = 1;
           for (var i = 0; i < response.length; i++) {
                var btn_download = '<span data-smt="'+response[i]['ID']+'" class="btn btn-xs btn-download" path = "'+response[i]['Path']+'" filename = "'+response[i]['Filename']+'"><i class="fa fa-cloud-download"></i> Download</span>';
               $("#EventTbl tbody").append(
                   '<tr>'+
                       '<td>'+no+'</td>'+
                       '<td>'+response[i]['Filename']+'</td>'+
                       '<td>'+btn_download+'</td>'+
                   '</tr>' 
                   );
               no++;
           }
       }).done(function() {
           LoaddataTableStandard('#EventTbl');
       })
   }

   $(document).on('click','.btn-download', function () {
     var path = $(this).attr('path');
     var Filename = $(this).attr('filename');
     var url = base_url_js+'download_anypath';
     data = {
       path : path,
       Filename : Filename
     }
     var token = jwt_encode(data,"UAP)(*");
     submit(url, 'POST', [
         { name: 'token', value: token },
     ]);
   });

   function submit(action, method, values) {
       var form = $('<form/>', {
           action: action,
           method: method
       });
       $.each(values, function() {
           form.append($('<input/>', {
               type: 'hidden',
               name: this.name,
               value: this.value
           }));    
       });
       form.attr('target', '_blank');
       form.appendTo('body').submit();
   }
</script>
