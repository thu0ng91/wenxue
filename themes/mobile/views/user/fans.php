<div class="module user-following">
    <div class="module-header"><?php echo $label;?></div>
    <div class="row module-body">
        <?php if(!empty($posts)){?>
        <?php foreach($posts as $k=>$post){?>
        <a href="<?php echo Yii::app()->createUrl('user/index',array('id'=>$post['id']));?>">
            <div class="col-xs-2 col-sm-2">
                <img src="<?php echo $post['avatar'];?>" class="img-responsive"/>
                <p class="no-wrap"><?php echo $post['truename'];?></p>
            </div>
        </a>
        <?php echo ($k+1)%6==0 ? '<div class="clearfix"></div>' : '';?>
        <?php }?>
        <?php $this->renderPartial('/common/pager',array('pages'=>$pages));?>
        <?php }else{?>
        <p class="color-grey text-center">哎哟，这里空空如也~</p>
        <?php }?>
    </div>
</div>