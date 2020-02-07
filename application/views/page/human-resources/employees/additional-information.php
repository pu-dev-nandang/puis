<style type="text/css">
    #form-employee .tabulasi-emp > ul > li.active > a{background:#428bca;color:#fff;border:1px solid #428bca;}
    #form-employee .cursor{cursor: pointer;}
    #form-employee .cursor-disable{cursor: no-drop;}
</style>
<div id="form-employee">

    <div class="row">
        <div class="col-sm-12" style="margin-bottom:20px">
            <a class="btn btn-warning" href="<?=site_url('human-resources/employees')?>"><i class="fa fa-angle-double-left"></i> Back to list</a>
        </div>
        <div class="col-sm-12">
            <div class="tabulasi-emp">
              <ul class="nav nav-tabs" role="tablist">
                <li><a href="<?=site_url('human-resources/employees/edit-employees/'.$NIP)?>" >Personal Data</a></li>
                <li class="active"><a class="cursor">Additional Information</a></li>
              </ul>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-edit"></i> Please fill up this form with correctly data</h4>
                </div>
                <div class="panel-body">
                    <form id="form-additional" action="" method="post" autocomplete="off">
                        <div class="form-group">
                            <label></label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>