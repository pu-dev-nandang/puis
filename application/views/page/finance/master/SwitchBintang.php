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
                  <div class="thumbnail" style="border: 1px solid #3cba8b;border-radius: 5px;">
                    <div style="padding: 15px;">
                        <h3 class="header-blue">Log Switch Bintang</h3>
                    </div>
                    <div>
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
                  <div class="thumbnail" style="border: 1px solid #435fe7;border-radius: 5px;">
                    <div style="padding: 15px;">
                        <h3 class="header-blue">Student Tuition Fee </h3>
                    </div>
                    <div>
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Semester</th>
                            <th>Payment Type</th>
                            <th>Invoice</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 pagetuitionFeeStudent_After" style = "margin-bottom:10px;">
                  <div class="thumbnail" style="border: 1px solid #e74343;border-radius: 5px;">
                    <div style="padding: 15px;">
                        <h3 class="header-blue">Student Tuition Fee After </h3>
                    </div>
                    <div>
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Semester</th>
                            <th>Payment Type</th>
                            <th>Invoice</th>
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
  let Pay_Cond = '';
  let oLoadloG;
  console.log(dataDecodeToken);

  const JSGenerate = {
    LoadDefault : async() => {
      dataChange = [];
      await JSGenerate.LoadBintangOption();
      JSGenerate.LoadloG();
      JSGenerate.LoadStudentTuitionFee();
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
          $('#FrmOptionBintang').append(
              '<option value = "'+OPBintang[i].Pay_Cond+'">'+bintangHtml(OPBintang[i].Pay_Cond)+'</option>'
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
              },
              {
                'targets': 1,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                  let html = '';
                  let dt = full[1];
                  html = dt['No_Ref']+' / '+dt['FormulirCode']+'<br/>'+'<span style = "color : green;">'+dt['Name']+'</span>';
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

    },
    LoadStudentTuitionFee_After : async() => {

    },

  };

  $(document).ready(function(e){
    JSGenerate.LoadDefault();
  })
</script>