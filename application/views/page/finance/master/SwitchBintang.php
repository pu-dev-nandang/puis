<style type="text/css">
  h3.header-blue{
      margin-top: 0px;
      border-left: 7px solid #2196F3;
      padding-left: 10px;
      font-weight: bold;
  }
</style>
<div class="row">
    <div class="col-xs-12" >
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Switch Bintang Payment</h4>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3" style = "margin-bottom:10px;">
                  <a href="<?php echo base_url().'finance/master/mahasiswa'?>" class = "btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                </div>
                <div class="col-md-6">
                  <div style="background: lightyellow; border: 1px solid #ccc;padding: 15px;color: #f44336;margin-bottom: 20px;">
                    <div>
                      <b>NPM : <?php echo $NPM ?> , Name : <?php echo $Name ?> , Prodi : <?php echo $Prodiname ?></b>
                      <div id = "showBintang"></div>
                    </div>
                    <div style="margin-top: 10px;">
                      <div class="form-group">
                        <label>Choose Bintang</label>
                        <select class="form-control" id = "FrmOptionBintang" style="color:red;"></select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top: 10px;">
                <div class="col-md-4 pageLogSwitchBintang" style = "margin-bottom:10px;">
                  <div class="thumbnail" style="border: 1px solid #3cba8b;border-radius: 5px;min-height: 250px;">
                    <div style="padding: 15px;">
                        <h3 class="header-blue">Log Switch Bintang</h3>
                    </div>
                    <div style = "padding-bottom: 50px;">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Data Updated</th>
                            <th>By & At</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 pagetuitionFeeStudent" style = "margin-bottom:10px;">
                  <div class="thumbnail" style="border: 1px solid #435fe7;border-radius: 5px;min-height: 250px;">
                    <div style="padding: 15px;">
                        <h3 class="header-blue">Student Tuition Fee </h3>
                    </div>
                    <div style = "padding-bottom: 50px;">
                      <table class="table">
                        <thead>
                          <tr>
                            <th style = "text-align:center;">Semester</th>
                            <th style = "text-align:center;">Payment Type</th>
                            <th style = "text-align:center;">Invoice</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 pagetuitionFeeStudent_After" style = "margin-bottom:10px;">
                  <div class="thumbnail" style="border: 1px solid #e74343;border-radius: 5px;min-height: 250px;">
                    <div style="padding: 15px;">
                        <h3 class="header-blue">Student Tuition Fee After </h3>
                    </div>
                    <div style = "padding-bottom: 50px;">
                      <table class="table">
                        <thead>
                          <tr>
                            <th style = "text-align:center;">Semester</th>
                            <th style = "text-align:center;">Payment Type</th>
                            <th style = "text-align:center;">Invoice</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-success btnSaveData" style="width: 100%;">Submit</button>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  const dataTokenUrl = "<?php echo $tokenURL ?>";
  const getUrl = window.location.href;
  const dataDecodeToken = jwt_decode(dataTokenUrl);
  let dataChange = [];
  let dataExist = [];
  let Pay_Cond = '';
  let oLoadloG;
  let oLoadTuitionFee;
  let oLoadTuitionAfter;
  console.log(dataDecodeToken);

  const JSGenerate = {
    LoadDefault : async() => {
      dataChange = [];
      dataExist = [];
      await JSGenerate.LoadBintangOption();
      JSGenerate.LoadloG();
      JSGenerate.LoadStudentTuitionFee();
      JSGenerate.LoadStudentTuitionFee_After(1);
    },
    LoadBintangOption : async() => {
      const data = {
        action : 'LoadBintangOption',
        data : {
          NPM : dataDecodeToken['NPM'],
          ProdiID : dataDecodeToken['ProdiID'],
          Classof : dataDecodeToken['Classof'],
        }
      }

      var token = jwt_encode(data,'UAP)(*');

      try {
        const ajax = await AjaxSubmitFormPromises(getUrl,token);
        Pay_Cond = ajax['auth_std']['Pay_Cond'];
        const OPBintang = ajax['OptionBintang'];
        $('#FrmOptionBintang').empty();
        for (var i = 0; i < OPBintang.length; i++) {
          const bintangHtml = (JmlBintang) => {
            let str = '';
            for (var j = 1; j <= JmlBintang; j++) {
              str += '*';
            }
            return str;
          };

          const selectedOP = (OPBintang[i].Pay_Cond == Pay_Cond) ? 'selected' : '';
          $('#FrmOptionBintang').append(
              '<option value = "'+OPBintang[i].Pay_Cond+'" '+selectedOP+' >'+bintangHtml(OPBintang[i].Pay_Cond)+'</option>'
            );
        }

        $('#showBintang').html('<b>Payment Bintang : '+setBintangFinance(Pay_Cond)+'</b>');
      }
      catch(err){
        toastr.info('something wrong LoadBintangOption');
      }
    },
    LoadloG : async() =>{
      var table = $('.pageLogSwitchBintang').find('.table').DataTable({
          "fixedHeader": true,
          "processing": true,
          "destroy": true,
          "serverSide": false,
          "lengthMenu": [
              [10,25],
              [10,25]
          ],
          "iDisplayLength": 10,
          "ordering": false,
          "language": {
              "searchPlaceholder": "Search...",
          },
          "ajax": {
              url: getUrl, // json datasource
              ordering: false,
              type: "post", // method  , by default get
              data: function(token) {
                  // Read values
                  const data = {
                    action : 'LoadloG',
                    data : {
                      NPM : dataDecodeToken['NPM'],
                    }
                  }
                  token.token = jwt_encode(data,'UAP)(*');
              },
              error: function() { // error handling
                  $('.pageLogSwitchBintang').find(".table-grid-error").html("");
                  $('.pageLogSwitchBintang').find("#table-grid").append(
                      '<tbody class="tableRefund-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                  );
                  $('.pageLogSwitchBintang').find("#table-grid_processing").css("display", "none");
              }
          },
          'columnDefs': [
              {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                  let html = '<label>From</label> '+setBintangFinance(full[2])+' <label>to</label> '+setBintangFinance(full[3]);
                  return html;
                 }
              },
              {
                'targets': 1,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                  let html = moment(full[5]).format('DD MMM YYYY hh:mm:ss') + '<br/>'+'<span style = "color : blue;">'+full[7]+'</span>';
                  return html;
                 }
              },
          ],
          'createdRow': function(row, data, dataIndex) {
              
          },
          dom: 'l<"toolbar">frtip',
          "initComplete": function(settings, json) {

          }
      });

      oLoadloG = table;
      
    },
    LoadStudentTuitionFee : async() => {
      var table = $('.pagetuitionFeeStudent').find('.table').DataTable({
          "fixedHeader": true,
          "processing": true,
          "destroy": true,
          "serverSide": false,
          "lengthMenu": [
              [10,25],
              [10,25]
          ],
          "iDisplayLength": 10,
          "ordering": false,
          "language": {
              "searchPlaceholder": "Search...",
          },
          "ajax": {
              url: getUrl, // json datasource
              ordering: false,
              type: "post", // method  , by default get
              data: function(token) {
                  // Read values
                  const data = {
                    action : 'LoadTuitionFeeMhs',
                    data : {
                      NPM : dataDecodeToken['NPM'],
                    }
                  }
                  token.token = jwt_encode(data,'UAP)(*');
              },
              error: function() { // error handling
                  $('.pagetuitionFeeStudent').find(".table-grid-error").html("");
                  $('.pagetuitionFeeStudent').find("#table-grid").append(
                      '<tbody class="tableRefund-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                  );
                  $('.pagetuitionFeeStudent').find("#table-grid_processing").css("display", "none");
              }
          },
          'columnDefs': [
              {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                  let html = full['Semester'];
                  return html;
                 }
              },
              {
                'targets': 1,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                  let html = full['PaymentType'];
                  return html;
                 }
              },
              {
                'targets': 2,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                  let html =formatRupiah(full['Invoice']);
                  return html;
                 }
              },
          ],
          'createdRow': function(row, data, dataIndex) {
              $(row).find('td').each(function(e){
                $(this).attr('style','text-align:center;')
              })
          },
          dom: 'l<"toolbar">frtip',
          "initComplete": function(settings, json) {
            dataExist = json.data;
          }
      });

      oLoadTuitionFee = table;

    },
    LoadStudentTuitionFee_After : async(defaultparam = 0) => {
      var table = $('.pagetuitionFeeStudent_After').find('.table').DataTable({
          "fixedHeader": true,
          "processing": true,
          "destroy": true,
          "serverSide": false,
          "lengthMenu": [
              [10,25],
              [10,25]
          ],
          "iDisplayLength": 10,
          "ordering": false,
          "language": {
              "searchPlaceholder": "Search...",
          },
          "ajax": {
              url: getUrl, // json datasource
              ordering: false,
              type: "post", // method  , by default get
              data: function(token) {
                  // Read values
                  const data = {
                    action : 'LoadTuitionFeeAfter',
                    data : {
                      NPM : dataDecodeToken['NPM'],
                      Classof :dataDecodeToken['Classof'],
                      ProdiID : dataDecodeToken['ProdiID'],
                      ProdiName : dataDecodeToken['Prodiname'],
                      Pay_Cond : $('#FrmOptionBintang option:selected').val(),
                    }
                  }
                  token.token = jwt_encode(data,'UAP)(*');
              },
              error: function() { // error handling
                  $('.pagetuitionFeeStudent').find(".table-grid-error").html("");
                  $('.pagetuitionFeeStudent').find("#table-grid").append(
                      '<tbody class="tableRefund-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>'
                  );
                  $('.pagetuitionFeeStudent').find("#table-grid_processing").css("display", "none");
              }
          },
          'columnDefs': [
              {
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                  let html = full['Semester'];
                  return html;
                 }
              },
              {
                'targets': 1,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                  let html = full['PaymentType'];
                  return html;
                 }
              },
              {
                'targets': 2,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                  let html =formatRupiah(full['Invoice']);
                  return html;
                 }
              },
          ],
          'createdRow': function(row, data, dataIndex) {
              $(row).find('td').each(function(e){
                $(this).attr('style','text-align:center;')
              })
          },
          dom: 'l<"toolbar">frtip',
          "initComplete": function(settings, json) {
            const result = json['result'];
            //console.log(result)
            if (result.status == 1) {
              dataChange = result.callback; 
            }
            else
            {
              if (defaultparam == 0) {
                 toastr.info(result.msg);
              }
              dataChange = [];
            }
          }
      });

      oLoadTuitionAfter = table;
    },

    tuitionFeeUpdate : async(selector) => {
      // console.log(dataChange);
      // console.log(dataExist);
      if (dataChange.length == 0) {
        toastr.info('Tidak ada data perubahan');
        return;
      }
    
      loading_button2(selector);
      const data = {
        action : 'tuitionFeeUpdate',
        data : {
          NPM : dataDecodeToken['NPM'],
          Pay_Cond : $('#FrmOptionBintang option:selected').val(),
          Classof : dataDecodeToken['Classof'],
          dataExist : dataExist,
          dataChange : dataChange,
        }
      }

      var token = jwt_encode(data,'UAP)(*');
      try{
        const ajax  = await AjaxSubmitFormPromises(getUrl,token);
        if (ajax == 1) {
          end_loading_button2(selector,'Submit');
          JSGenerate.LoadDefault();
          toastr.success('Success');
        }
      } 
      catch(err){
        toastr.info('something wrong');
      }

    }
  };

  $(document).ready(function(e){
    JSGenerate.LoadDefault();
  })

  $(document).on('change','#FrmOptionBintang',function(e){
    JSGenerate.LoadStudentTuitionFee_After();
  })

  $(document).on('click','.btnSaveData',function(e){
    const itsme = $(this);
    JSGenerate.tuitionFeeUpdate(itsme);
  })
</script>