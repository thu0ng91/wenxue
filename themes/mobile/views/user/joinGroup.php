<style>  
    .groups-container{
        padding: 15px;
        box-sizing: border-box;
        margin-top: 0;
        margin-bottom: 0;
    }
    .groups-holder .thumbnail{
        position: relative;
        width: 100%;     
        display: inline-block;
        background: #fff;
        margin-bottom: 15px;
        box-shadow:0 0 10px rgba(0, 0, 0, .3);
    }
    .groups-holder .thumbnail .img-holder{
        width: 100%;
        padding-top: 56.25%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }
    .groups-holder .thumbnail .fixed-icon{
        position: absolute;
        right: 50%;
        margin-right: -24px;
        top: 102px;
        margin-top: -24px;
        font-size: 48px;
        color:  #93ba5f;
        display: none;
        z-index: 999
    }
    .groups-holder .thumbnail .caption{
        padding: 5px 10px 15px
    }
    .groups-holder .thumbnail .caption .task-samples{
        border-left: 4px solid #efefef;
        padding-left: 5px;
    }    
    .groups-container .ui-btn-wrap{
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 15px;
        box-sizing: border-box;
        z-index: 999
    }
</style>
<div class="ui-container groups-container">
    <div class="groups-holder" id="groups-holder">
        <?php foreach($groups as $group){?>        
        <div class="thumbnail" data-id="<?php echo $group['id'];?>">
            <i class="fa fa-check fixed-icon"></i>
            <div class="img-holder" style="background-image:url(<?php echo $group['faceImg'];?>)"></div>
            <div class="caption">
                <p class="title"><?php echo $group['title'];?></p>
                <p class="color-grey"><?php echo $group['desc'];?></p> 
                <div class="task-samples">
                    <p class="ui-nowrap color-grey">成长路线成长路线成长路线</p>
                    <p class="ui-nowrap color-grey">成长路线成长路线成长路线</p>
                    <p class="ui-nowrap color-grey">成长路线成长路线成长路线</p>
                </div>
            </div>
        </div>        
        <?php }?>
        <input type="hidden" id="selected-groupid" name="selected-groupid"/>
    </div>
    <div class="ui-btn-wrap">
        <button type="button" class="ui-btn ui-btn-primary ui-btn-lg displayNone" id="selected-groupid-btn">确认角色</button>
    </div>
</div>
<div class="footer-bg" id="footer-bg"></div>
<script>
    $(document).ready(function(){
        $('#groups-holder .thumbnail').click(function(){
            var dom=$(this);
            var gid=dom.attr('data-id');
            if(!gid){
                return false;
            }
            $('#groups-holder .thumbnail').each(function(){
                $(this).children('.fixed-icon').hide();
            })
            dom.children('.fixed-icon').show();            
            $('#selected-groupid').val(gid);
            $('#selected-groupid-btn').show();
        });
        $('#selected-groupid-btn').click(function(){
            var gid=$('#selected-groupid').val();
            if(!gid){
                dialog({msg:'请选择你喜欢的角色'});
                return false;
            }
            if(confirm('选择角色后将不能修改，确定该角色吗？')){
                $.post(zmf.ajaxUrl, {action:'joinGroup',gid: gid, YII_CSRF_TOKEN: zmf.csrfToken}, function (result) {
                    ajaxReturn = true;
                    result = $.parseJSON(result);
                    if (result.status === 1) {
                        window.location.href = result.msg;
                    }else{
                        alert(result.msg);
                    }
                })
            }
        })
    })
</script>