<div class="container">
    <div class="goods-header" id="goods-header">
        <div class="goods-classify" id="goods-classify">
            <?php foreach ($navbars as $navbar){?>
            <?php echo CHtml::link($navbar['title'].'<i class="fa fa-angle-right pull-right"></i>','javascript:;',array('class'=>'goods-nav-item','data-id'=>$navbar['id']));?>  
            <?php }?>
        </div>
        <div id="goods-classify-holder">
            <?php foreach ($navbars as $navbar){$seconds=$navbar['items'];?>
            <div class="goods-classify-holder" id="classify-holder-<?php echo $navbar['id'];?>">
                <?php foreach($seconds as $second){$thirds=$second['items'];?>
                <div class="items-holder">
                    <span class="items-label"><?php echo CHtml::link($second['title'],array('shop/all','id'=>$second['id']));?></span>     
                    <div class="items">
                        <?php foreach($thirds as $third){?>
                        <?php echo CHtml::link($third['title'],array('shop/all','id'=>$third['id']),array('class'=>'item'));?>
                        <?php }?>   
                    </div>
                </div>
                <?php }?>
            </div>
            <?php }?>
        </div>
        <div class="goods-carousel">
            <?php $this->renderPartial('/site/login-carousel');?>
        </div>
    </div>    
    <?php if(!empty($posts)){?>
    <div class="clearfix"></div>
    <div class="module" style="margin-top:20px">
        <div class="module-header">推荐</div>
        <div class="module-body goods-container">
            <?php foreach ($posts as $post){?>
            <?php $this->renderPartial('_item',array('data'=>$post));?>
            <?php }?>
        </div>
    </div>
    <?php }?>
</div>
<script>
    $(document).ready(function(){
        $('#goods-classify>a').mouseover(function(){
            $('#goods-classify>a').each(function(){
                $(this).removeClass('active');
            });
            $('#goods-classify-holder>div').each(function(){
                $(this).hide();
            })
            var dom=$(this);
            dom.addClass('active');
            var _id=dom.attr('data-id');
            $('#classify-holder-'+_id).show();
        });
        $('#goods-header').mouseleave(function(){
            $('#goods-classify>a').each(function(){
                $(this).removeClass('active');
            });
            $('#goods-classify-holder>div').each(function(){
                $(this).hide();
            })
        })
    })
</script>