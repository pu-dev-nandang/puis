<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Request Document</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;" id ="pageRequestDocument">
        

    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-primary hide" id="Preview">Preview</button>
        <button class="btn btn-success" id="btnSave" disabled>Save</button>
    </div>
</div>
<script type="text/javascript">
	var settingTemplate = [];
	var App_input = {
		Loaded : function(){
			loading_page('#pageRequestDocument');
			var firstLoad = setInterval(function () {
	            var SelectMasterSurat = $('#MasterSurat').val();
	            if(SelectMasterSurat!='' && SelectMasterSurat!=null ){
	                /*
	                    LoadAction
	                */
	                App_input.LoadPageDefaultInput();
	                clearInterval(firstLoad);
	            }
	        },1000);
	        setTimeout(function () {
	            clearInterval(firstLoad);
	        },5000);
		},

		LoadPageDefaultInput : function(){
			if (typeof msgMasterDocument !== 'undefined') {
			    $('#pageRequestDocument').html('<p style="color:red;">'+msgMasterDocument+'</p>');
			    $('#Preview').addClass('hide');
			    settingTemplate = [];
			}
		},

		DomRequestDocument : function(IDMasterSurat,TokenData){
			$('#Preview').addClass('hide');
			var dt = jwt_decode(TokenData);
			// console.log(dt);
			var DocumentName = dt.DocumentName;
			var DocumentAlias = dt.DocumentAlias;
			var Config = jQuery.parseJSON(dt.Config);
			settingTemplate = Config;
			App_input.DomSetTemplate(DocumentName,DocumentAlias,TokenData);
			$('#Preview').removeClass('hide');
		},

		DomSetTemplate : function(DocumentName,DocumentAlias){
			var selectorPage = $('#pageRequestDocument');
			// defined page
			var html = 	'<div style = "padding:5px;">'+
                            '<h3><u><b>'+DocumentName+' / '+DocumentAlias+'</b></u></h3>'+
                        '</div>'+
						'<div class = "row">'+
							'<div class = "col-md-9" id = "Page_INPUT">'+
							'</div>'+
							'<div class = "col-md-3" id = "Page_Approval">'+
							'</div>'+
						'</div>';
			selectorPage.html(html);
			var selectorPage_INPUT = $('#Page_INPUT');
			App_input.DomSetPage_INPUT(selectorPage_INPUT,settingTemplate.INPUT);

			var selectorPage_Approval = $('#Page_Approval');
			App_input.DomSetPage_Approval(selectorPage_Approval,settingTemplate.SET.Signature);		
		},

		DomSetPage_INPUT : function(selector,dt){
			var html = '<div class = "thumbnail" style = "margin-top:5px;">'+
			                '<div class = "row">'+
			                    '<div class = "col-md-12">'+
			                        '<div style = "padding:15px;">'+
			                            '<h3><u><b>Input by Request</b></u></h3>'+
			                        '</div>';
			for (var i = 0; i < dt.length; i++) {
				html  +=   '<div class = "form-group">'+
				                '<label>'+dt[i].field+'</label>'+
				                '<input type = "text" class="form-control Input" name="'+dt[i].mapping+'" placeholder = "'+dt[i].value+'" />'+
				            '</div>';
			}

			html  += '</div></div></div>';
			selector.append(html);
		},

		DomSetPage_Approval : function(selector,dt){
			console.log(dt);
			var html = '<div class = "thumbnail" style = "margin-top:5px;">'+
			                '<div class = "row">'+
			                    '<div class = "col-md-12">'+
			                        '<div style = "padding:15px;">'+
			                            '<h3><u><b>Approval</b></u></h3>'+
			                        '</div>';
			/*  Check Need Approval or Not */
		},
	};

	$(document).ready(function(){
		App_input.Loaded();
	})
</script>