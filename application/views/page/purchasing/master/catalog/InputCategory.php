<div class="row" style="margin-right: 0px;margin-left: 0px;margin-top: 10px">
	<div class="com-md-12">
		<div class="fancy-collapse-panel">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a href="javascript:void(0)" class="pageAnchor" page = "FormInputCategory" data-toggle="collapse" status = "0">Form Input
                            </a>
                        </h4>
                    </div>
                    <div id="FormInputCategory" class="collapse">
                        <div class="panel-body" id = "pageFormInputCategory">
                            
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a href="javascript:void(0)" class="pageAnchor" page = "DataIntableCategory" data-toggle="collapse" status = "0">Data
                            </a>
                        </h4>
                    </div>
                    <div id="DataIntableCategory" class="collapse">
                        <div class="panel-body" id = "pageDataIntableCategory">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<script type="text/javascript">
	window.CountColapses_ = 0;
	window.CountColapses_2 = 0;
	$(document).ready(function() {
		pageAnchor();	
	}); // exit document Function

	function pageAnchor()
	{
		$(".pageAnchor").click(function()
		{
			var page = $(this).attr('page');
			$(this).attr('data-target','#'+page);
			if (page == 'FormInputCategory') {
				CountColapses_ = (CountColapses_ == 0) ? CountColapses_ = 1 : CountColapses_ = 0 ;
				if(CountColapses_ == 1) {
				 LoadPageCatalogCategory(page);
				}
				else
				{
				 $("#page"+page).empty();
				}
			}
			else
			{
				CountColapses_2 = (CountColapses_2 == 0) ? CountColapses_2 = 1 : CountColapses_2 = 0 ;
				if(CountColapses_2 == 1) {
				 LoadPageCatalogCategory(page);
				}
				else
				{
				 $("#page"+page).empty();
				}
			}
		})

	}

	function LoadPageCatalogCategory(page)
	{
		loading_page("#page"+page);
		var url = base_url_js+'purchasing/page/catalog/'+page;
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