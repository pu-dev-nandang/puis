<style type="text/css">
	
	#bcPaint-container{
	  width: 100%;
	  height: 100%;
	}


	#bcPaint-header{
	  width: 100%;
	  height: 25px;
	}

	#bcPaint-palette{
	  text-align: center;
	}

	.bcPaint-palette-color{
	  width: 15px;
	  height: 15px;
	  display: inline-block;
	  cursor: pointer;
	  border-radius: 15px;
	  margin: 0 5px;
	}

	#bcPaint-palette > .selected{
	  border: 2px solid #bdbdbd;
	  margin-bottom: -2px;
	}

	#bcPaint-canvas-container{
	  width: 100%;
	  height: 200px;
	}

	#bcPaint-canvas-container > canvas{
	  border: 1px solid #696969;
	}

	#bcPaint-bottom{
	  width: 100%;
	  height: 25px;
	  text-align: center;
	}

	#bcPaint-bottom > button{
	  background-color: #3e4e5a;
	  color: #ffffff;
	  text-transform: uppercase;
	  border: 0;
	  font-size: 10px;
	  padding: 5px 10px;
	  margin-left: 10px;
	  margin-top: 10px;
	  outline: none;
	}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		$('body').on('click', '#btnResetCanvas', function(){
			$.fn.bcPaint.clearCanvas();
		});

		$('body').on('click', '#btnLockCanvas', function(){
			var itsme = $(this);
			var isopen = itsme.data("arial");
			isopen = $.trim(isopen);
			if(isopen == 'close'){
				$("#btnResetCanvas").prop("disabled",true);
				itsme.find("i.fa").toggleClass('fa-lock fa-unlock');
				itsme.find("span").text("Unlock");
				var imgData = $.fn.bcPaint.generate();
				$("#Signature-pic").val(imgData);
				$("body #bcPaint").empty().html('<img src="'+imgData+'">');
				itsme.data("arial","show");	
			}else{
				itsme.find("i.fa").toggleClass('fa-lock fa-spinner').addClass("fa-spin");
				itsme.find("span").text("loading");
				location.reload();
			}
		});

	});

	(function( $ ) {
		var isDragged		= false,
			startPoint		= { x:0, y:0 },
			templates 		= {
								container 		: $('<div id="bcPaint-container"></div>'),
								color 			: $('<div class="bcPaint-palette-color"></div>'),
								canvasContainer : $('<div id="bcPaint-canvas-container"></div>'),
								canvasPane 		: $('<canvas id="bcPaintCanvas"></canvas>'),
								bottom 			: $('<div id="bcPaint-bottom"></div>')
							},
			paintCanvas,
			paintContext;

		$.fn.bcPaint = function (options) {

			return this.each(function () {
				var rootElement 	= $(this),
					colorSet		= $.extend({}, $.fn.bcPaint.defaults, options),
					defaultColor	= (rootElement.val().length > 0) ? rootElement.val() : colorSet.defaultColor,
					container 		= templates.container.clone(),
					canvasContainer = templates.canvasContainer.clone(),
					canvasPane 		= templates.canvasPane.clone(),
					bottom 			= templates.bottom.clone(),
					color;

				// assembly pane
				rootElement.append(container);
				container.append(canvasContainer);
				container.append(bottom);
				canvasContainer.append(canvasPane);

				// assembly color palette
				$.each(colorSet.colors, function (i) {
	        		color = templates.color.clone();
					color.css('background-color', $.fn.bcPaint.toHex(colorSet.colors[i]));
					palette.append(color);
	    		});

				// set canvas pane width and height
				var bcCanvas = rootElement.find('canvas');
				var bcCanvasContainer = rootElement.find('#bcPaint-canvas-container');
				bcCanvas.attr('width', bcCanvasContainer.width());
				bcCanvas.attr('height', bcCanvasContainer.height());

				// get canvas pane context
				paintCanvas = document.getElementById('bcPaintCanvas');
				paintContext = paintCanvas.getContext('2d');

				// set color
				$.fn.bcPaint.setColor(defaultColor);

				// bind mouse actions
				paintCanvas.onmousedown = $.fn.bcPaint.onMouseDown;
				paintCanvas.onmouseup = $.fn.bcPaint.onMouseUp;
				paintCanvas.onmousemove = $.fn.bcPaint.onMouseMove;

				// bind touch actions
				paintCanvas.addEventListener('touchstart', function(e){
					$.fn.bcPaint.dispatchMouseEvent(e, 'mousedown');
				});
				paintCanvas.addEventListener('touchend', function(e){
	  				$.fn.bcPaint.dispatchMouseEvent(e, 'mouseup');
				});
				paintCanvas.addEventListener('touchmove', function(e){
					$.fn.bcPaint.dispatchMouseEvent(e, 'mousemove');
				});

				// Prevent scrolling on touch event
				document.body.addEventListener("touchstart", function (e) {
				  if (e.target == 'paintCanvas') {
				    e.preventDefault();
				  }
				}, false);
				document.body.addEventListener("touchend", function (e) {
				  if (e.target == 'paintCanvas') {
				    e.preventDefault();
				  }
				}, false);
				document.body.addEventListener("touchmove", function (e) {
				  if (e.target == 'paintCanvas') {
				    e.preventDefault();
				  }
				}, false);
			});
		}

		$.extend(true, $.fn.bcPaint, {

			dispatchMouseEvent : function(e, mouseAction){
				var touch = e.touches[0];
				if(touch == undefined){
					touch = { clientX : 0, clientY : 0 };
				}
				var mouseEvent = new MouseEvent(mouseAction, {
					clientX: touch.clientX,
					clientY: touch.clientY
				});
				paintCanvas.dispatchEvent(mouseEvent);
			},

			clearCanvas : function(){
				paintCanvas.width = paintCanvas.width;
			},

			onMouseDown : function(e){
				isDragged = true;
				// get mouse x and y coordinates
				startPoint.x = e.offsetX;
				startPoint.y = e.offsetY;
				// begin context path
				paintContext.beginPath();
				paintContext.moveTo(startPoint.x, startPoint.y);
			},

			onMouseUp : function() {
			    isDragged = false;
			},

			onMouseMove : function(e){
				if(isDragged){
					paintContext.lineTo(e.offsetX, e.offsetY);
					paintContext.stroke();
				}
			},

			setColor : function(color){
				paintContext.strokeStyle = $.fn.bcPaint.toHex(color);
			},

			export : function(){
				var imgData = paintCanvas.toDataURL('image/png');
				var windowOpen = window.open('about:blank', 'Image');
				windowOpen.document.write('<img src="' + imgData + '" alt="Exported Image"/>');
			},
			
			generate : function(){
				var imgData = paintCanvas.toDataURL('image/png');
				return imgData;
			},


			toHex : function(color) {
			    // check if color is standard hex value
			    if (color.match(/[0-9A-F]{6}|[0-9A-F]{3}$/i)) {
			        return (color.charAt(0) === "#") ? color : ("#" + color);
			    // check if color is RGB value -> convert to hex
			    } else if (color.match(/^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/)) {
			        var c = ([parseInt(RegExp.$1, 10), parseInt(RegExp.$2, 10), parseInt(RegExp.$3, 10)]),
			            pad = function (str) {
			                if (str.length < 2) {
			                    for (var i = 0, len = 2 - str.length; i < len; i++) {
			                        str = '0' + str;
			                    }
			                }
			                return str;
			            };
			        if (c.length === 3) {
			            var r = pad(c[0].toString(16)),
			                g = pad(c[1].toString(16)),
			                b = pad(c[2].toString(16));
			            return '#' + r + g + b;
			        }
			    // else do nothing
			    } else {
			        return false;
			    }
			}

		});

		$.fn.bcPaint.defaults = {
	        // default color
	        defaultColor : '000000',

	        // default color set
	        colors : [],
	    };

	})(jQuery);
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#form-employee .navigation-tabs ul > li").removeClass("active");
        $("#form-employee .navigation-tabs ul > li.nv-signature").addClass("active");
		$('#bcPaint').bcPaint();

		var _URL = window.URL || window.webkitURL;
		var maxSizeSG = 350;
		$("#chooseSignature").change(function(){
         	var w=0,h=0;
         	var img = new Image();
	        var objectUrl = _URL.createObjectURL(this.files[0]);
	        img.onload = function () {
	        	w=this.width;
	        	h=this.height;
	            if(w > maxSizeSG || h > maxSizeSG){
	            	alert("This file has a large size of dimension.");
	            	$('#chooseSignature,#Signature-pic').val('');
	            	$("#prev-signature").attr("src",'data:image/png;base64,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');
	            }
	            _URL.revokeObjectURL(objectUrl);
	        };
	        img.src = objectUrl;
         	var numFiles = this.files[0].size;
         	var typeFiles = this.files[0].type;
         	if(numFiles > 2000000 ){
         		$(this).val("");
         		alert("File image to large.");
         	}else if(typeFiles != "image/png"){
				$(this).val("");
         		alert("File type image is not allowed.");
         	}else{
	            if (this.files && this.files[0]) {
	                var reader = new FileReader();
	                reader.onload = function(e) {
	                 $("#Signature-pic").val(e.target.result);
	                 $('#prev-signature').attr('src', e.target.result);
	                }
	                reader.readAsDataURL(this.files[0]);
	            }
            }
        });

        $("#form-signature .btn-submit").click(function(){
            var itsme = $(this);
            var itsform = itsme.parent().parent().parent();
            var error = false;
            itsform.find(".required").each(function(){
                var value = $(this).val();
                if($.trim(value) == ''){
                    $(this).addClass("error");
                    $(this).parent().find(".text-message").text("Please fill this field");
                    error = false;
                }else{
                    error = true;
                    $(this).removeClass("error");
                    $(this).parent().find(".text-message").text("");
                }
            });
            
            var totalError = itsform.find(".error").length;
            if(error && totalError == 0 ){
                loading_modal_show();
                $("#form-signature")[0].submit();
            }else{
                alert("Please fill out the field.");
            }
        });

        var myData = fetchAdditionalData("<?=$NIP?>");
        if(!jQuery.isEmptyObject(myData)){
        	if(!jQuery.isEmptyObject(myData.Signature)){
        		$("#fetch-signature").removeClass("hidden");
        		$("body #load-my-signature").attr("src",base_url_js+"./uploads/signature/"+myData.Signature);
        	}
        }
    });
</script>

<form id="form-signature" action="<?=base_url('profile/save-changes/'.$NIP)?>" method="post" autocomplete="off" enctype="multipart/form-data" style="margin:0px">
    <input type="hidden" name="NIP" value="<?=$NIP?>">
    <input class="form-control" name="action" type="hidden" value="signature" />
    <textarea class="required hidden" name="Signature" id="Signature-pic" ></textarea>
	<div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-bars"></i> 
            My Signature</h4>
        </div>
        <div class="panel-body">
        	<div class="row">
                <div class="col-sm-4 hidden" id="fetch-signature">
                	<div class="panel panel-default">
                		<div class="panel-heading">
                			<h4 class="panel-title">My signature image</h4>
                		</div>
                		<div class="panel-body text-center">
                			<img src="" id="load-my-signature" class="img-thumbnail" >
                		</div>
            		</div>
                </div>
                <div class="col-sm-5">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h4 class="panel-title">Draw your signature here</h4>
                    </div>
                    <div class="panel-body">
                    	<div class="row">
	                    	<div class="col-sm-8">
		                    	<div id="bcPaint"></div>
	                    	</div>
	                    	<div class="col-sm-4">
	                    		<div class="well center-block" style="width:100%"> 
		                    		<button type="button" id="btnResetCanvas" class="btn btn-primary btn-lg btn-block"><i class="fa fa-refresh"></i> Reset</button>
		                    		<button type="button" id="btnLockCanvas" class="btn btn-default btn-lg btn-block" data-arial="close"><i class="fa fa-lock"></i> <span>Lock</span></button>
	                    		</div>
	                    	</div>
                    	</div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-3">
                	<div class="panel panel-default">
                		<div class="panel-heading">
                			<h4 class="panel-title">Upload your signature image</h4>
                		</div>
                		<div class="panel-body">
                			<div class="row">
                				<div class="col-sm-6">
                					<div class="form-group">
				                        <label>Choose file</label>
				                        <p>
				                        	<img width="150px" height="150px" id="prev-signature" class="img-thumbnail preview" src="data:image/png;base64,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">
				                        </p>
				                        <input type="file" id="chooseSignature" accept="image/x-png">
				                    </div>
                				</div>
                				<div class="col-sm-6">
                					<div class="alert alert-info" style="padding:5px">
                						<h5>Note</h5>
                						<p>- Maximum dimension of this file is 300px X 300px</p>
                						<p>- Only PNG file are allowed</p>
                						<p>- Maximum file are 2Mb</p>
                					</div>
                				</div>
                			</div>
                			
                		</div>
                	</div>
                </div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-success btn-submit" type="button">Save changes</button>
        </div>
    </div>
</form>