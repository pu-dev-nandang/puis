<?php $message = $this->session->flashdata('message');
	if(!empty($message)){ ?>
	<script type="text/javascript">
	$(document).ready(function(){
		toastr.info("<?= $this->session->flashdata('message');?>",'Info!');
	});
	</script>
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/OrgChart/dist/css/jquery.orgchart.min.css')?>">
<script type="text/javascript" src="<?=base_url('assets/OrgChart/dist/js/jquery.orgchart.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/OrgChart/dist/js/html2canvas.min.js')?>"></script>
<script type="text/javascript">
	function updateNode(nodeID,parentID) {
		if(nodeID!=null && parentID!=null){		
			var data = {
	          NODE 	: nodeID,
	          PARENT : parentID,
	      	};
	      	var token = jwt_encode(data,'UAP)(*');
	      	$.ajax({
			    type : 'POST',
			    url : base_url_js+"human-resources/master-aphris/change-node",
			    data : {token:token},
			    dataType : 'json',
	            error : function(jqXHR){
	            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
		      	  	$("body #GlobalModal").modal("show");
			    },success : function(response){
	            	if(!jQuery.isEmptyObject(response)){
	            		toastr.info(response.message,'INFO!');
	            	}else{alert("Failed change node.");}
			    }
			});
		}
	}


	function filterNodes(keyWord) {
	    if(!keyWord.length) {
	      window.alert('Please type key word firstly.');
	      return;
	    } else {
	    	keyWord = keyWord.toLowerCase();
	      var $chart = $('body .orgchart');
	      // disalbe the expand/collapse feture
	      $chart.addClass('noncollapsable');
	      // distinguish the matched nodes and the unmatched nodes according to the given key word
	      $chart.find('.node').filter(function(index, node) {
	          return $(node).text().toLowerCase().indexOf(keyWord) > -1;
	        }).addClass('matched')
	        .closest('table').parents('table').find('tr:first').find('.node').addClass('retained');

	      // hide the unmatched nodes
	      $chart.find('.matched,.retained').each(function(index, node) {
	        $(node).removeClass('slide-up')
	          .closest('.nodes').removeClass('hidden')
	          .siblings('.lines').removeClass('hidden');
	        var $unmatched = $(node).closest('table').parent().siblings().find('.node:first:not(.matched,.retained)')
	          .closest('table').parent().addClass('hidden');
	        $unmatched.parent().prev().children().slice(1, $unmatched.length * 2 + 1).addClass('hidden');
	      });

	      // hide the redundant descendant nodes of the matched nodes
	      $chart.find('.matched').each(function(index, node) {
	        if (!$(node).closest('tr').siblings(':last').find('.matched').length) {
	          $(node).closest('tr').siblings().addClass('hidden');
	        }
	      });
	    }
  	}


	function clearFilterResult() {
		$('.orgchart').removeClass('noncollapsable')
		  .find('.node').removeClass('matched retained')
		  .end().find('.hidden').removeClass('hidden')
		  .end().find('.slide-up, .slide-left, .slide-right').removeClass('slide-up slide-right slide-left');
	}


	function fetchSTO() {
		var resultOBJ = [];
		$.ajax({
		    type : 'POST',
		    url : base_url_js+"human-resources/master-aphris/fetch-sto",
		    dataType : 'json',
		    async: false,
		    beforeSend :function(){
		    	loading_modal_show();
		    },error : function(jqXHR){
		    	loading_modal_hide();
            	$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '<h4 class="modal-title">Error Fetch Structure Organization</h4>');
                $('#GlobalModal .modal-body').html(jqXHR.responseText);
                $('#GlobalModal .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
                $('#GlobalModal').modal({
                    'show' : true,
                    'backdrop' : 'static'
                });
		    },success : function(response){
		    	loading_modal_hide();
				resultOBJ = response;
		    }
		});

		return resultOBJ;
	}
	$(document).ready(function(){
		var datascource = fetchSTO();
		if(!jQuery.isEmptyObject(datascource)){
			$('#chart-container').orgchart({
		      'data' : datascource,
		      'nodeContent': 'title',
		      'pan': true,
		      'zoom': true,
		      'responseTime': 1000,
		      'exportButton': false,
		      'draggable': true,
	      	  'exportFilename': 'PU-Stuctured-Organization'
		    });
		}else{}

	    $('#btn-filter-node').on('click', function() {
	      filterNodes($('#key-word').val());
	    });

	    $('#btn-cancel').on('click', function() {
	      clearFilterResult();
	    });

	    $('#key-word').on('keyup', function(event) {
	      if (event.which === 13) {
	        filterNodes(this.value);
	      } else if (event.which === 8 && this.value.length === 0) {
	        clearFilterResult();
	      }
	    });


	    $("#chart-container").on("click",".node",function(){
	    	var itsme = $(this);
	    	var idParent = itsme.data("parent");
	    	var idNode = itsme.find(".title").data("id");
	    	var title = itsme.find(".title").text();
	    	
	    	var data = {
	          ID : idNode,
	      	};
	      	var token = jwt_encode(data,'UAP)(*');
	      	$.ajax({
			    type : 'POST',
			    url : base_url_js+"human-resources/master-aphris/detail-sto",
			    data : {token:token},
			    dataType : 'html',
			    beforeSend :function(){loading_modal_show()},
	            error : function(jqXHR){
	            	loading_modal_hide();
	            	$("body #GlobalModal .modal-body").html(jqXHR.responseText);
		      	  	$("body #GlobalModal").modal("show");
			    },success : function(response){
	            	loading_modal_hide();
	            	$('#GlobalModal .modal-header').html('<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
		                '<h4 class="modal-title"><span>Detail of </span>'+title.toUpperCase()+'</h4>');
		            $('#GlobalModal .modal-dialog').css({width:'80%'});
		            $('#GlobalModal .modal-body').html(response);
		            $('#GlobalModal .modal-footer').remove();
		            $('#GlobalModal').modal({
		                'show' : true,
		                'backdrop' : 'static'
		            });
			    }
			});

	    	
	    });
	});
</script>

<style type="text/css">
	#chart-container {
	  font-family: Arial;
	  height: 500px;
	  border-radius: 5px;
	  overflow: auto;
	}
	#chart-container > .oc-export-btn{position: relative;right: 0;}
	.orgchart { background: #fff; }
	.orgchart .middle-level .title { background-color: #006699; }
    .orgchart .middle-level .content { border-color: #006699; }
    .orgchart .node.matched { background-color: rgba(238, 217, 54, 0.5); }
    .orgchart .node .edge { display: none; }
    .orgchart .node .content{ height: auto;min-height: 28px;max-height: 100px;overflow: auto;cursor: pointer !important;}

/*
 *  STYLE 6
 */

.orgchart .node .content::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	background-color: #F5F5F5;
}

.orgchart .node .content::-webkit-scrollbar
{
	width: 5px;
	background-color: #F5F5F5;
}

.orgchart .node .content::-webkit-scrollbar-thumb
{
	background-color: #0ae;
	
	background-image: -webkit-gradient(linear, 0 0, 0 100%,
	                   color-stop(.5, rgba(255, 255, 255, .2)),
					   color-stop(.5, transparent), to(transparent));
}


</style>


<div id="structur-organization">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<i class="fa fa-sitemap"></i> Structure Organization on PU
			</h4>
		</div>
		<div class="panel-heading">
			<div class="filtering">
				<div class="row">
					<div class="col-sm-1 col-md-1">
						<label>Search by node</label>
					</div>
					<div class="col-sm-2 col-md-2">
						<input type="text" name="nodekey" class="form-control" placeholder="Node name" id="key-word" >
					</div>
					<div class="col-sm-2 col-md-2">
						<button class="btn btn-success" type="button" id="btn-filter-node"><i class="fa fa-search"></i> Search</button>						
						<button class="btn btn-default" type="button" id="btn-cancel">Cancel</button>						
					</div>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div id="chart-container"></div>			
		</div>
	</div>
</div>
