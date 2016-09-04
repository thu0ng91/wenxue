<style>
    .groups-holder{
        margin-top: 100px;
        height: 400px;        
        margin-left: -15px;
        margin-right: -15px;
    }
    .groups-holder .thumbnail{
        position: relative;
        width: 300px;
        float: left;
        margin:0 15px;
        display: inline-block
    }
    .groups-holder .thumbnail:hover{
        border-color: #93ba5f
    }
    .groups-holder .thumbnail img{
        width: 272px;
        height: 204px;
        margin-top: 10px
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
</style>
<div class="container groups-container">
    <div class="groups-holder" id="groups-holder">
        <?php foreach($groups as $group){?>        
        <div class="thumbnail" data-id="<?php echo $group['id'];?>">
            <i class="fa fa-check fixed-icon"></i>
            <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDI0MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxkZWZzLz48cmVjdCB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjkzIiB5PSIxMDAiIHN0eWxlPSJmaWxsOiNBQUFBQUE7Zm9udC13ZWlnaHQ6Ym9sZDtmb250LWZhbWlseTpBcmlhbCwgSGVsdmV0aWNhLCBPcGVuIFNhbnMsIHNhbnMtc2VyaWYsIG1vbm9zcGFjZTtmb250LXNpemU6MTFwdDtkb21pbmFudC1iYXNlbGluZTpjZW50cmFsIj4yNDJ4MjAwPC90ZXh0PjwvZz48L3N2Zz4=" alt="...">
            <div class="caption">
                <p class="title"><?php echo $group['title'];?></p>
                <p><?php echo $group['desc'];?></p>                    
            </div>
        </div>        
        <?php }?>
        <input type="hidden" id="selected-groupid" name="selected-groupid"/>
    </div>
    <p class="text-center"><button type="button" class="btn btn-success displayNone" id="selected-groupid-btn">确认角色</button></p>
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