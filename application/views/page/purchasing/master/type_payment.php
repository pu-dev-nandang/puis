<style type="text/css">
  /* FANCY COLLAPSE PANEL STYLES */
  .fancy-collapse-panel .panel-default > .panel-heading {
  padding: 0;

  }
  .fancy-collapse-panel .panel-heading a {
  padding: 12px 35px 12px 15px;
  display: inline-block;
  width: 100%;
  background-color: #EE556C;
  color: #ffffff;
  position: relative;
  text-decoration: none;
  }
  .fancy-collapse-panel .panel-heading a:after {
  font-family: "FontAwesome";
  content: "\f147";
  position: absolute;
  right: 20px;
  font-size: 20px;
  font-weight: 400;
  top: 50%;
  line-height: 1;
  margin-top: -10px;
  }

  .fancy-collapse-panel .panel-heading a.collapsed:after {
  content: "\f196";
  }
</style>
<style type="text/css">
  #datatablesServer thead th,#datatablesServer tfoot td {

      text-align: center;
      background: #20485A;
      color: #FFFFFF;

  }

  #datatablesServer>thead>tr>th, #datatablesServer>tbody>tr>th, #datatablesServer>tfoot>tr>th, #datatablesServer>thead>tr>td, #datatablesServer>tbody>tr>td, #datatablesServer>tfoot>tr>td {
      border: 1px solid #b7b7b7
  }
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/custom/jquery.maskMoney.js"></script>
<div style="padding-top: 30px;border-top: 1px solid #cccccc">
    <div class="row">

  <div class="col-md-3 panel-admin" style="border-right: ">
    <form action="<?php echo base_url().'purchasing/SaveFormType' ?>" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Input Type Payment</h4>
            </div>
            <div class="panel-body">
                <input class="hide" id="formID"/>
                <div class="form-group">
                    <label>Type</label>
                    <input class="form-control" id="formType" name="Type"/>
                </div>
            </div>
        </div>

            <div class="panel-footer" style="text-align: right;">
               <input type="submit" name="btnSaveFormType" class="btn btn-success"> 
               <!-- <button class="btn btn-success" id="btnSaveFormType">Save</button> -->
            </div>
        
</form>
</div>
    
  

    <div class="col-md-9">
        <!-- <div class="row"> -->
            <!-- <div class="col-md-9"> -->
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="width: 1%;">No</th>
                        <th>Type</th>
                        <th style="width: 5%;"><i class="fa fa-cog"></i></th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php 
                      for($i=0;$i<count($data);$i++){ ?>
                        <tr>
                          <td>
                            <?php echo ($i+1) ?>
                          </td>
                          <td>
                            <?php echo ($data[$i]['Name']) ?>
                          </td>
                           <td>
                            <button type="button" class="btn btn-danger btn-delete btn-delete-item"> <i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                          </td>
                          
                        </tr>
                      <?php } ?>
                    </tbody>
                </table>
            <!-- </div> -->
        <!-- </div> -->
    </div>

</div>
</div>
