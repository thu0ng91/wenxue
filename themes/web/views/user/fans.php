<div class="module user-following">
    <div class="module-header"><?php echo $label;?></div>
    <div class="row module-body">
        <?php foreach($posts as $k=>$post){?>
        <a href="<?php echo Yii::app()->createUrl('user/index',array('id'=>$post['id']));?>">
            <div class="col-xs-2 col-sm-2">
                <img src="<?php echo $post['avatar'];?>" class="img-responsive"/>
                <p class="no-wrap"><?php echo $post['truename'];?></p>
            </div>
        </a>
        <?php echo ($k+1)%6==0 ? '<div class="clearfix"></div>' : '';?>
        <?php }?>
    </div>
</div>