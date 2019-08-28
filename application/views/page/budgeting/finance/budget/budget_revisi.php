<div class="tabbable tabbable-custom tabbable-full-width btn-read menuEBRevisi">
    <ul class="nav nav-tabs" style="margin-left: 10px;margin-right: 10px;">
        <li class="active">
            <a href="javascript:void(0)" class="pageAnchorBrevisi" page = "Revisi">Revisi</a>
        </li>
        <li class="">
            <a href="javascript:void(0)" class="pageAnchorBrevisi" page = "Mutasi">Mutasi</a>
        </li>
    </ul>
    <div style="padding-top: 30px;border-top: 1px solid #cccccc">
        <div id = "pageBudgetRevisi"></div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		LoadPageBudgetRevisi('Revisi');
	    loadingEnd(500);
	}); // exit document Function

	$(document).off('click', '.pageAnchorBrevisi').on('click', '.pageAnchorBrevisi',function(e) {
	    var Page = $(this).attr('page');
	    $(".menuEBRevisi li").removeClass('active');
	    $(this).parent().addClass('active');
	    $("#pageBudgetRevisi").empty();
	    LoadPageBudgetRevisi(Page);
	});

	function LoadPageBudgetRevisi(page)
	{
		loadingStart();
		loading_page("#pageBudgetRevisi");
		var url = base_url_js+'budgeting/EntryBudget/budget_revisi/'+page;
		$.post(url,function (resultJson) {
		    var response = jQuery.parseJSON(resultJson);
		    var html = response.html;
		    var jsonPass = response.jsonPass;
		    setTimeout(function () {
		        $("#pageBudgetRevisi").empty();
		        $("#pageBudgetRevisi").html(html);
		    },1000);
		    
		}); // exit spost
	}


	function load_table_activated_period_years_Brevisi()
	{
		var def = jQuery.Deferred();
		var url = base_url_js+'budgeting/table_all/cfg_dateperiod/1';
		$.post(url,function (resultJson) {
			
		}).done(function(resultJson) {
			var response = jQuery.parseJSON(resultJson);
			def.resolve(response);
		}).fail(function() {
		  toastr.info('No Result Data');
		  def.reject();  
		}).always(function() {
		                
		});	
		return def.promise();
	}
</script>