<div class="row">
	<div class="col-md-9">
		<div class="panel panel-default">
		    <div class="panel-heading">
		        <h4 class="panel-title">Form</h4>
		    </div>
		    <div class="panel-body" style="min-height: 100px;" id ="pageForm">
		    	<div class="row">
		    		<div class="col-md-12">
		    			<div class="form-group">
		    				<label>SQL</label>
		    				<textarea class="form-control Input" name = "Query" id = "SQL" rows=" 5"></textarea>
		    				<p><b>Example :</b></p>
		    				<p style="color: red;">
		    					SELECT mk.NameEng as MataKuliahNameEng, cd.TotalSKS AS Credit,14 as Sesi FROM db_academic.schedule s
	                              LEFT JOIN db_academic.schedule_details_course sdc ON (sdc.ScheduleID = s.ID)
	                              LEFT JOIN db_academic.mata_kuliah mk ON (mk.ID = sdc.MKID)
	                              LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc.CDID)
	                              WHERE s.SemesterID = ? AND s.Coordinator = ? GROUP BY s.ID
	                              UNION ALL
	                              SELECT mk2.NameEng as MataKuliahNameEng, cd.TotalSKS AS Credit,14 as Sesi FROM db_academic.schedule_details_course sdc2
	                              LEFT JOIN db_academic.schedule s2 ON (s2.ID = sdc2.ScheduleID)
	                              LEFT JOIN db_academic.mata_kuliah mk2 ON (mk2.ID = sdc2.MKID)
	                              LEFT JOIN db_academic.curriculum_details cd ON (cd.ID = sdc2.CDID)
	                              LEFT JOIN db_academic.schedule_team_teaching stt ON (sdc2.ScheduleID = stt.ScheduleID)
	                              WHERE s2.SemesterID = ? AND stt.NIP = ? GROUP BY s2.ID
		    				</p>
		    				<p style="color: blue;">* (?) -> Penyisipan Parameter dalam query </p>
		    			</div>
		    			<div class="form-group">
		    				<label>Params</label>
		    				<input class="form-control Input" id = "Params" name="Params"></input>
		    				<p><b>Example :</b></p>
		    				<p style="color: red;">
		    					["#SemesterID","$NIP","#SemesterID","$NIP"]
		    				</p>
		    				<p style="color: blue;">* (#) -> Parameter yang diambil berdasarkan pilihan user </p>
		    				<p style="color: blue;">* ($) -> Parameter yang diambil berdasarkan session user </p>
		    			</div>
		    		</div>
		    	</div>
		    	<div id = "ParamsResult"></div>
		    	<div class="row" style="margin-top: 10px;">
		    		<div class="col-md-12" id = "TBLQuery">
		    			
		    		</div>
		    	</div>
		    </div>
		    <div class="panel-footer" style="text-align: right;">
		    	<button class="btn btn-primary" id ="BtnRun">Run</button>
		        <button class="btn btn-success" id="btnSave" action = "add" data-id="" disabled>Save</button>
		    </div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
		    <div class="panel-heading">
		        <h4 class="panel-title">List</h4>
		    </div>
		    <div class="panel-body" style="min-height: 100px;" id = "pageTable">
		        <div class="row">
		        	<div class="col-md-12">
		        		<div class="table-responsive">
		        			<table class = "table table-striped" id = "TblList">
		        				<thead>
		        					<tr>
		        						<th>Name</th>
		        						<th>Action</th>
		        					</tr>
		        				</thead>
		        				<tbody></tbody>
		        			</table>
		        		</div>
		        	</div>
		        </div>
		    </div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var App_query = {
		Loaded : function(){
			$('.Input').val('');
		},

		SetDomParams : function(dt){
			var selector = $('#ParamsResult');
			var html = '';
			for(key in dt){
				var str = key.replace("__", "#");
				var strKey = key.replace("__", "");
				var arr = dt[key];
				var htmlOP = App_query.SelectAPIOPByParams(arr,strKey);
				html += '<div class = "row" style = "margin-top:5px;">'+
							'<div class = "col-md-12">'+
								'<div class = "thumbnail">'+
									'<div style = "padding:10px;">'+
										'<label>Parameter : '+str+'</label>'+
										htmlOP+
									'</div>'+
								'</div>'+
							'</div>'+
						'</div>';				
			}

			selector.html(html);
			console.log(dt);
		},

		SelectAPIOPByParams : function(data,paramsChoose){
			var html =  '<select class = "form-control Input" name="'+paramsChoose+'" key = "user">';
			for (var i = 0; i < data.length; i++) {
			   var selected = (data[i].Selected == 1) ? 'selected'  : ''; 
			   html +=  '<option value = "'+data[i].ID+'" '+selected+' >'+data[i].Value+'</option>';
			}

			html  += '</select>';

			return html;
		},

		RunQuery : function(selector,action="add",ID=""){
			var data = {};
			$('.Input').not('div').each(function(){
		        var field = $(this).attr('name');
		        data[field] = $(this).val();
		    })

			var dataform = {
			    action : action,
			    data : data,
			    ID : ID,
			};
			loading_button2(selector);
			var url = base_url_js+"it/__request-document-generator/__sqlQueryLanguange";
			var token = jwt_encode(dataform,'UAP)(*');
			$.post(url,{token:token},function (resultJson) {

			}).done(function(response) {
				var st = response['status'];
				if (st == 2) {
					App_query.SetDomParams(response['data']);
				}
				else if(st==0){
					toastr.error(JSON.stringify(response['callback']));
				}
				else
				{
					// exceute query
				}
				end_loading_button2(selector,'Run');				
			}).fail(function() {
				end_loading_button2(selector,'Run');
			});

		},


	};

	$(document).ready(function(e){
		App_query.Loaded();
	})

	$(document).off('click', '#BtnRun').on('click', '#BtnRun',function(e) {
	   var itsme = $(this);
	   App_query.RunQuery(itsme);
	})
</script>