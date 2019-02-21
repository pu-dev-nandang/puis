<div class = 'row'>
	<!--=== Calendar ===-->
	<div class="col-md-12">
		<div class="widget">
			<div class="row">
			  <div class="col-md-3"></div>
			  <div class="col-md-3"></div>
			  <div class="col-md-3"></div>
			  <div class="col-md-3" align="right">
			    <b>Status : </b><br>
			    <i class="fa fa-circle" style="color:#6ba5c1;"></i> Non Use || 
			    <i class="fa fa-circle" style="color:#e98180;"></i> Used
			  </div>
			</div>
			<div class="widget-header">
					<h4 id = 'schdate'><i class="icon-calendar"></i> Schedule Date : <?php echo date('Y-m-d') ?></h4>
			</div>
			<div class="widget-content">
				<div class = "row">	
					<div class="col-xs-3">
						<div id="datetimepicker1" class="input-group input-append date datetimepicker">
								<input data-format="yyyy-MM-dd" class="form-control" id="datetime_deadline1" type="	text" readonly="" value="<?php echo date('Y-m-d') ?>">
								<span class="input-group-addon add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar" class="icon-calendar"></i></span>
						</div>
					</div>
					<div class="col-xs-3">
					<button class="btn btn-success" id = "search"><span class="glyphicon glyphicon-search"></span> Search</button>
					</div>
				</div>
				<div class="row" style="margin-top: 10px;">
					<div class="col-md-3">
						<button id = "PreviousDate" class="btn btn-success dateSearch" date = "<?php echo date('Y-m-d') ?>"> << Previous</button>
					</div>
					<div class="col-md-3 col-md-offset-6" align="right">
						<button id = "NextDate" class="btn btn-success dateSearch" date = "<?php echo date('Y-m-d') ?>"> Next >></button>
					</div>
				</div>
				<br>
				<!-- <div class = "row">	 -->
					<div id="schedule"></div>
				<!-- </div> -->
			</div>
		</div> <!-- /.widget box -->
	</div> <!-- /.col-md-6 -->
	<!-- /Calendar -->
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#datetimepicker1').datetimepicker({
			format: 'yyyy-MM-dd',autoclose: true, minView: 2,pickTime: false,
		});

		$('#datetime_deadline1').prop('readonly',true);
		var date =	$("#datetime_deadline1").val();
		loadScheduleEquipment(date);

		$("#search").click(function(){
			var date =	$("#datetime_deadline1").val();
			loadScheduleEquipment(date);
		})

		$("#NextDate").click(function(){
			var date =	$(this).attr('date');
			loadScheduleEquipment(date);
		})

		$("#PreviousDate").click(function(){
			var date =	$(this).attr('date');
			loadScheduleEquipment(date);
		})

		function loadScheduleEquipment(date)
		{
			loading_page("#schedule");
			var url = base_url_js+'vreservation/loadScheduleEquipment';
			var data = {
			               date : date,
			           };
			var token = jwt_encode(data,'UAP)(*');
			$.post(url,{token : token},function (data_json) {
                var response = jQuery.parseJSON(data_json);
                var html = MakeHtml(response)
                $("#schedule").html(html);
                $(".panel-red").hover();
                $(".panel-blue").hover();
                $(".panel-orange").hover();
                $("#PreviousDate").attr('date',response['datePrev']);
                $("#NextDate").attr('date',response['dateNext']);
                $("#schdate").html('<i class="icon-calendar"></i>'+date);
                $("#datetime_deadline1").val(date);
            }).done(function() {
                
            })	
		}

		function MakeHtml(data)
		{
			var html = '<div class="row" style="margin-left: 0px;margin-right: 0px; margin-top: -12px">'+
						 '<div class="table-responsive table-area" style="overflow-x:auto;">'+
							'<table class="table table-bordered table2">'+
								'<thead>'+
							    	'<tr>'+
								    	'<th width="15%">'+
								    		'Equipment'+
								    	'</th>';

			var arrHours = data['arrHours'];
			var t_data = data['data'];
			// console.log(t_data);
			for (var i = 0; i < arrHours.length; i= i + 2) {
				html += '<th colspan="2" style="text-align: left;">'+arrHours[i]+'</th>';
			}

			html += '</tr></thead>';
			html += '<tbody>';

			var t = '';
			for (var i = 0; i < t_data.length; i++) {
				var ID = t_data[i]['ID'];
				t += '<tr>';
				t += '<td>'+t_data[i]['Name']+'</td>';
				var used = t_data[i]['Qty0'] - t_data[i]['Qty'] ;
				var attrchkuse = (t_data[i]['Qty'] != t_data[i]['Qty0']) ? 'class = "panel-red" style = "background-color : #e98180" title = "'+t_data[i]['Start']+' - '+t_data[i]['End']+' Used : '+used+'" ' : 'class = "panel-blue pointer" title = "'+t_data[i]['Start']+' - '+t_data[i]['End']+'"';
				t += '<td '+attrchkuse+'>'+t_data[i]['Qty']+'</td>';
				for (var j= i+1; j < t_data.length; j++) {
					var ID2 = t_data[j]['ID'];
					if (ID == ID2) {
						used = t_data[j]['Qty0'] - t_data[j]['Qty'];
						attrchkuse = (t_data[j]['Qty'] != t_data[j]['Qty0']) ? 'class = "panel-red" style = "background-color : #e98180" title = "'+t_data[j]['Start']+' - '+t_data[j]['End']+' Used : '+used+'" ' : 'class = "panel-blue pointer" title = "'+t_data[j]['Start']+' - '+t_data[j]['End']+'"';
						t += '<td '+attrchkuse+'>'+t_data[j]['Qty']+'</td>';
					}
					else
					{
						i = j-1;
						break;
					}

					i = j;
				}

				t += '</tr>';
			}
			html += t;
			html += '</tbody></table>';
			html += '</div></div>';

			return html;
		}
	});
</script>	