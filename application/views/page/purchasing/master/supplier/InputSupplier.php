<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">
	<div class="com-md-12">
		<div class="fancy-collapse-panel">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a href="javascript:void(0)" class="pageAnchor" page = "FormInput" data-toggle="collapse" status = "0" action = "add" CDID = "">Form Input
                            </a>
                        </h4>
                    </div>
                    <div id="FormInput" class="collapse">
                        <div class="panel-body" id = "pageFormInput">
                            
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a href="javascript:void(0)" class="pageAnchor" page = "DataIntable" data-toggle="collapse" status = "0" action = "add" CDID = "">Data
                            </a>
                        </h4>
                    </div>
                    <div id="DataIntable" class="collapse">
                        <div class="panel-body" id = "pageDataIntable">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<script type="text/javascript">
	window.CountColapses = 0;
	window.CountColapses2 = 0;
	$(document).ready(function() {
		pageAnchor();	
	}); // exit document Function

	function pageAnchor()
	{
		$(".pageAnchor").click(function()
		{
			var page = $(this).attr('page');
			$(this).attr('data-target','#'+page);
			if (page == 'FormInput') {
				CountColapses = (CountColapses == 0) ? CountColapses = 1 : CountColapses = 0 ;
				var status = $(this).attr('status');
				if(status == 0)
				{
					if(CountColapses == 1) {
					 LoadPageSupplier(page);
					}
					else
					{
					 $("#page"+page).empty();
					}
				}
				
			}
			else
			{
				CountColapses2 = (CountColapses2 == 0) ? CountColapses2 = 1 : CountColapses2 = 0 ;
				if(CountColapses2 == 1) {
				 LoadPageSupplier(page);
				}
				else
				{
				 $("#page"+page).empty();
				}
			}
		})


	}

	function LoadPageSupplier(page)
	{
		loading_page("#page"+page);
		var url = base_url_js+'purchasing/page/supplier/'+page;
		var data = {
			action : 'add',
		}
		var token = jwt_encode(data,"UAP)(*");
		$.post(url,{token: token},function (resultJson) {
		    var response = jQuery.parseJSON(resultJson);
		    var html = response.html;
		    var jsonPass = response.jsonPass;
		    $("#page"+page).html(html);
		}); // exit spost
	}
</script>