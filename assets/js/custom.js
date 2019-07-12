$(function(){    
    $('#privilege-frm .sbmt').on('click', function(){
        $(".se-pre-con").show();
        $.ajax({
            type:"POST",
            url: BASE_URL+"admin/post_module_permission",
            data:$('#privilege-frm').serialize(),
            success:function(res) {
                $(".se-pre-con").hide('slow');
                var data = JSON.parse(res);
               if(data.type == 1) {
                    $('.errMsg').html('<p class="alert alert-success">'+data.msg+'</p>');
                    setTimeout(function(){
                        location.reload();
                   },1500);
               }
               else
                $('.errMsg').html('<p class="alert alert-danger">'+data.msg+'</p>');

            }
        });
        return false;
    });
});
function permissionModal(id) {
    if(id) {
        $(".se-pre-con").show();
        $html = '';
        $.ajax({
            type:"POST",
            url: BASE_URL+"admin/permission_lists",
            data:{id:id},
            success:function(data) {   
                var result = JSON.parse(data);
                $.each(result.prntModules, function(key, val){
                    if(jQuery.inArray(val.id, result.userAccess) !== -1)
                        var pchk = 'checked';
                    else
                        var pchk = '';
                    $html +='<fieldset><legend><label class="pm-list">'+
                        '<input type="checkbox" name="access[]" value="'+val.id+'" '+pchk+'>'+val.module+'</label>';
                    if(result.chldModules.length)
                        $html +='<div>';
                    $.each(result.chldModules, function(key, cval){
                        if(val.id === cval.parentId){
                            if(jQuery.inArray(cval.id, result.userAccess) !== -1)
                                var chk = 'checked';
                            else
                                var chk = '';
                            $html +='<label class="m-list">'+
                                '<input type="checkbox" name="access[]" value="'+cval.id+'" '+chk+'>'+cval.module+
                            '</label>';
                        } 
                    });   
                    if(result.chldModules.length)
                        $html +='</div>';                   
                    $html +='</fieldset></legend>';
                });
                $("#modalPermission .userId").val(id);
                $(".permission-lists").html($html);
                $(".se-pre-con").hide("slow");
            }
        });
    }
}