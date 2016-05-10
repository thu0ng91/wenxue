<?php
/**
 * @filename tags.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-1-5  15:01:54 
 */
?>
<div class="main-part">
    <div class="tags-page">
        <div class="panel-group" role="tablist">
            <?php foreach ($posts as $post){$id=  zmf::randMykeys(6, 2);?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="<?php echo $id;?>-heading">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" href="#<?php echo $id;?>-group" aria-expanded="false" aria-controls="collapseListGroup1">
                            <?php echo $post['title'];?>
                        </a>
                    </h4>
                </div>
                <div id="<?php echo $id;?>-group" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?php echo $id;?>-heading" aria-expanded="false" style="height: 0px;">
                    <?php if(!empty($post['posts'])){?>
                    <div class="list-group">
                        <?php foreach($post['posts'] as $_post){?>
                        <?php echo CHtml::link($_post['title'],array('posts/view','id'=>$_post['id']),array('class'=>'list-group-item'));?>
                        <?php }?>
                        <?php echo CHtml::link('更多文章 <i class="fa fa-angle-double-right"></i>',array('index/index','tagid'=>$post['id']),array('class'=>'list-group-item tags-more-posts'));?>
                    </div>
                    <?php }else{?>
                    <div class="panel-body">
                        <p class="help-block">暂无文章！</p>
                    </div>
                    <?php }?>
                </div>
            </div>
            <?php }?>
        </div>
        <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
    </div>
</div>