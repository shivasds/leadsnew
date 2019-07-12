$(document).ready(createUploader);
	
	
    function createUploader(){ 
    	var button = $('#upload');           
        var uploader = new qq.FileUploaderBasic({
            button: document.getElementById('file-uploader'),
            action: base_url+'upload.php',
            allowedExtensions: ['jpg', 'gif', 'png', 'jpeg'],
            onSubmit: function(id, fileName) {
			
				interval = window.setInterval(function(){
					var text = button.text();
					
				}, 200);
                
                $.blockUI({ 
                    css: { 
                        border: 'none', 
                        padding: '5px', 
                        backgroundColor: '#000', 
                        '-webkit-border-radius': '5px', 
                        '-moz-border-radius': '5px', 
                        opacity: .5, 
                        color: '#fff' 
                    },
                    message :lbl_uploading_image
                }); 
			},
            onComplete: function(id, fileName, responseJSON){
            	
				window.clearInterval(interval);
				
            	if(responseJSON['success'])
            	{
            		load_original(responseJSON['filename']);
					}},
                debug: true
            });           
    }
        
    function load_original(filename){
		$("#image_holder").append('<li class="img_li" id="imgli_'+filename+'"><img src="'+base_url+'assets/uploads/property/thumb2/'+filename+'" width="150"><span class="text-margin2"><a href="javascript:void(0);" class="del_img" onclick="deleteimg(this)"><i class="fa fa-trash-o"></i></a></span><input type="hidden" id="img_'+filename+'" name="property_image[]" value="'+filename+'"><div><input type="checkbox" id="is_floor_paln_'+filename+'" name="is_floor_plan_typ[]" value="'+filename+'"><span>'+lbl_floor_plan+'</span><br>Main image:<input type="radio" name="main_image" value="'+filename+'" ></div></li>');
    	$('#image_holder input[type="radio"]:last').attr("checked", true);
		setTimeout($.unblockUI, 1000); 
	}

	