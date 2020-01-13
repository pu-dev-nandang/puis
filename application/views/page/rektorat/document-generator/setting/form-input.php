<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Query SQL Document</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>SQL</label>
                    <textarea class="form-control" id = "SQL" rows="5" name="SQL" placeholder="SQL"></textarea>
                </div>
                <div class="form-group">
                    <label>Parameter</label>
                    <table class="table" id="tblDom">
                        <tr>
                            <td>
                                <select class="form-control Connector">
                                    <option value="Where" selected>Where</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control Inputfield" />
                            </td>
                            <td> 
                                <button class="btn btn-default btnAdding">Add</button> 
                            </td>
                        </tr>
                    </table>
                </div>
            </div>   
        </div>
    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-primary" id="Run">Run</button>
        <button class="btn btn-success" id="btnSave" disabled>Save</button>
    </div>
</div>
<script type="text/javascript">
    
</script>