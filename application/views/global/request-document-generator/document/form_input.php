<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Request Document</h4>
    </div>
    <div class="panel-body" style="min-height: 100px;" id ="pageRequestDocument">
        

    </div>
    <div class="panel-footer" style="text-align: right;">
        <button class="btn btn-primary hide" id="Preview">Preview</button>
        <button class="btn btn-success" id="btnSave" action = "add" data-id="">Save</button>
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
			    $('#btnSave').prop('disabled',true);
			    $('#btnSave').attr('action','add');
			    $('#btnSave').attr('data-id','');
			    settingTemplate = [];
			}
		},

		DomRequestDocument : function(IDMasterSurat,TokenData){
			$('#btnSave').attr('action','add');
			$('#btnSave').attr('data-id','');
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
				                '<textarea class="form-control Input" name="'+dt[i].mapping+'" placeholder = "'+dt[i].value+'" />'+
				            '</div>';
			}

			html  += '</div></div></div>';
			selector.append(html);
		},

		DomSetPage_Approval : function(selector,dt){
			loading_anytext(selector,'Loading approval');
			// console.log(dt);
			var html = '<div class = "thumbnail" style = "margin-top:5px;">'+
			                '<div class = "row">'+
			                    '<div class = "col-md-12">'+
			                        '<div style = "padding:15px;">'+
			                            '<h3><u><b>Approval</b></u></h3>'+
			                        '</div>';
			/*  Check Need Approval or Not */
			var bool = false;
			for (var i = 0; i < dt.length; i++) {
				var userChoose = dt[i].user;
				var selectChoose = dt[i].select;
				for (var j = 0; j < selectChoose.length; j++) {
					var ID = selectChoose[j].ID;
					if (ID == userChoose) {
						if (i == 0) {
							bool = true;
							html += '<ul>';
						}
						var textVerify = (dt[i].verify == 1) ? 'Approve by System' : 'Approve manual';
						html += '<li style = "margin-left : -20px;">Approval '+(i+1)+' : '+'<span style="color:green;">'+selectChoose[j].Value+'</span>'+'<br/>'+'<label>'+textVerify+'</label>'+'</li>';
						break;
					}
				}
				
				if (bool) {
					html += '</ul>';
				}

			}

			selector.html(html);
			
		},

		SubmitPreviewPDF : function(selector){
			// console.log(settingTemplate.INPUT);
			for (var i = 0; i < settingTemplate.INPUT.length; i++) {
				$('.Input').each(function(e){
					var nameattr= $(this).attr('name');
					if (settingTemplate.INPUT[i].mapping == nameattr  ) {
						settingTemplate.INPUT[i].value = $(this).val();
						return;
					}
				})
			}

			// console.log(settingTemplate);
			var url = base_url_js+"__request-document-generator/__previewbyUserRequest";
		    var data = {
		       settingTemplate : settingTemplate,
		       ID : $('#MasterSurat option:selected').val(),
		       DepartmentID : DepartmentID,
		    }
		    var token =  jwt_encode(data,'UAP)(*');
		    loading_button2(selector);
		    AjaxSubmitTemplate(url,token).then(function(response){
		    	if (response.status == 1) {
		    	    window.open(response.callback, '_blank');
		    	    $('#btnSave').prop('disabled',false);
		    	}
		    	else
		    	{
		    	    toastr.error('Something error,please try again');
		    	}
		    	end_loading_button2(selector,'Preview');
			}).fail(function(response){
		        toastr.error('Connection error,please try again');
		        end_loading_button2(selector,'Preview');
		    })
		},

		SaveData : function(selector,action,dataID=""){
			var url = base_url_js+"__request-document-generator/__savebyUserRequest";
		    var data = {
		       settingTemplate : settingTemplate,
		       ID : $('#MasterSurat option:selected').val(),
		       DepartmentID : DepartmentID,
		       action : action,
		       dataID : dataID,
		    }
		    var token =  jwt_encode(data,'UAP)(*');
		    loading_button2(selector);
		    AjaxSubmitTemplate(url,token).then(function(response){
		    	if (response == 1) {
		    		toastr.success('Saved');
		    	    $('#MasterSurat').trigger('change');
		    	}
		    	else
		    	{
		    	    toastr.error('Something error,please try again');
		    	}
		    	end_loading_button2(selector,'Save');
			}).fail(function(response){
		        toastr.error('Connection error,please try again');
		        end_loading_button2(selector,'Save');
		    })
		},
	};

	$(document).ready(function(){
		App_input.Loaded();
	})

	$(document).off('click', '#Preview').on('click', '#Preview',function(e) {
	   var itsme = $(this);
	   App_input.SubmitPreviewPDF(itsme);
	})

	$(document).off('click', '#btnSave').on('click', '#btnSave',function(e) {
	   var itsme = $(this);
	   var action = itsme.attr('action');
	   var dataID = itsme.attr('data-id');
	   App_input.SaveData(itsme,action,dataID);
	})
</script>