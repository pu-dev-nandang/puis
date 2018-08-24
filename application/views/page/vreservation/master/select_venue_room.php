<style type="text/css">
  .setfont
  {
    font-size: 12px;
  }
  
</style>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <div class="widget box">
            <div class="widget-header">
                <h4 class="header"><i class="icon-reorder"></i>Select Venue Room</h4>
            </div>
            <div class="widget-content">
                <!--  -->
                  <div id = "pageData"></div>
                <!-- end widget -->
            </div>
            <hr/>
        </div>
    </div>
    <div class="col-md-2"></div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    loadTableJS(loadDataTable);
  }); // exit document Function

  function loadTableJS(callback)
  {
      // Some code
      // console.log('test');
      $("#pageData").empty();
      var table = '<div class = "col-md-12"><div class="table-responsive"> <table id="example" class="table table-bordered display select" cellspacing="0" width="100%">'+
                       '<thead>'+
                          '<tr>'+
                             '<th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>'+
                             '<th>Room</th>'+
                          '</tr>'+
                       '</thead>'+
                  '</table></div></div><button class = "btn btn-primary btn-edit" id = "submit">Save</button>';
      //$("#loadtableNow").empty();
      $("#pageData").html(table);

      /*if (typeof callback === 'function') { 
          callback(); 
      }*/
      callback();
  }

  function loadDataTable()
  {
      var url = base_url_js+'vreservation/master/getRoomItem';
      var table = $('#example').DataTable({
            'ajax': {
               'url': url
            },
            'columnDefs': [{
               'targets': 0,
               'searchable': false,
               'orderable': false,
               'className': 'dt-body-center',
               'render': function (data, type, full, meta){
                   // console.log(full)
                   // return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                   if(full[2] == 0)
                   {
                    return '<input type="checkbox" name="id[]" value="' + full[0] + '">';
                   }
                   else
                   {
                    return '<input type="checkbox" name="id[]" value="' + full[0] + '" checked>';
                   }
                   
               }
            }],
            'order': [[1, 'asc']]
         });

         // Handle click on "Select all" control
         $('#example-select-all').on('click', function(){
            // Get all rows with search applied
            var rows = table.rows({ 'search': 'applied' }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
         });

         // Handle click on checkbox to set state of "Select all" control
         $('#example tbody').on('change', 'input[type="checkbox"]', function(){
            // If checkbox is not checked
            if(!this.checked){
               var el = $('#example-select-all').get(0);
               // If "Select all" control is checked and has 'indeterminate' property
               if(el && el.checked && ('indeterminate' in el)){
                  // Set visual state of "Select all" control
                  // as 'indeterminate'
                  el.indeterminate = true;
               }
            }
         });

         // Handle form submission event
         // $('#frm-example').on('submit', function(e){
         //    var form = this;

         //    // Iterate over all checkboxes in the table
         //    table.$('input[type="checkbox"]').each(function(){
         //       // If checkbox doesn't exist in DOM
         //       if(!$.contains(document, this)){
         //          // If checkbox is checked
         //          if(this.checked){
         //             // Create a hidden element
         //             $(form).append(
         //                $('<input>')
         //                   .attr('type', 'hidden')
         //                   .attr('name', this.name)
         //                   .val(this.value)
         //             );
         //          }
         //       }
         //    });
         // });

         $("#submit").click(function(){
              loading_button('#submit');
              var checkboxArr = [];
              table.$('input[type="checkbox"]').each(function(){
                if(this.checked){
                   checkboxArr.push(this.value);
                }
                 
              });

             var url = base_url_js+'vreservation/master/submit_select_venue_room';
             var data = {
                         checkboxArr : checkboxArr,
                        };
             var token = jwt_encode(data,"UAP)(*");
              $.post(url,{token:token},function (data_json) {
                 $('#submit').prop('disabled',false).html('Save');
             }).done(function() {
               loadTableJS(loadDataTable);
             }).fail(function() {
               toastr.error('The Database connection error, please try again', 'Failed!!');
             }).always(function() {
              // $('#ModalbtnSaveForm').prop('disabled',false).html('Save');
              $('#btnSaveClassroom').prop('disabled',false).html('Save');

             });

         }) // exit click function
  }

</script>