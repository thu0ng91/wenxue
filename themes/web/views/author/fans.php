<div class="author-content-holder">
    <div class="module-header">追随者</div>
    <div class="row author-following">
        
        <?php if(!empty($posts)){foreach($posts as $k=>$post){?>
        <a href="<?php echo Yii::app()->createUrl('user/index',array('id'=>$post['id']));?>">
            <div class="col-xs-2 col-sm-2">
                <img src="<?php echo $post['avatar'];?>" class="img-responsive"/>
                <p class="no-wrap"><?php echo $post['truename'];?></p>
            </div>
        </a>
        <?php echo ($k+1)%6==0 ? '<div class="clearfix"></div>' : '';?>
        <?php }}else{?>
        <p class="help-block text-center">暂无关注者，赶紧成为第一位关注者吧</p>
        <?php }?>
    </div>
</div>