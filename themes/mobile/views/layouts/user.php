<?php $this->beginContent('/layouts/common'); ?>
<section class="ui-container header-hack">
    <div class="user-header">
        <div class="user-avatar-holder">
            <img class="lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo zmf::getThumbnailUrl($this->toUserInfo['avatar'], 'a120', 'avatar');?>" alt="<?php echo $this->toUserInfo['truename'];?>">
            <?php //echo Users::userSex($this->toUserInfo['sex']);?>
        </div>        
        <h1><?php echo $this->toUserInfo['truename'];?></h1>
        <p>
            <a class="item" href="<?php echo Yii::app()->createUrl('user/follow',array('id'=>$this->toUserInfo['id']));?>">关注：<?php echo $this->toUserInfo['favord'];?></a>
            | 
            <a class="item" href="<?php echo Yii::app()->createUrl('user/follow',array('id'=>$this->toUserInfo['id'],'type'=>'fans'));?>">被关注：<?php echo $this->toUserInfo['favors'];?></a>
        </p>
        <?php if($this->toUserInfo['id']!=$this->uid){?>      
        <p>
            <?php if($this->favorited){?>
            <?php echo CHtml::link('<i class="fa fa-star"></i> 已关注','javascript:;',array('class'=>'btn btn-default','action'=>'favorite','action-data'=>$this->toUserInfo['id'],'action-type'=>'user'));?>
            <?php }else{?>
            <?php echo CHtml::link('<i class="fa fa-star-o"></i> 关注','javascript:;',array('class'=>'btn btn-danger','action'=>'favorite','action-data'=>$this->toUserInfo['id'],'action-type'=>'user'));?>
            <?php }?>
        </p>
        <?php }?>
        
        <div class="ui-row-flex ui-border-tb">
            <div class="ui-col<?php echo $this->selectNav == 'index' ? ' active' : ''; ?>" data-href="<?php echo Yii::app()->createUrl('user/index',array('id'=>$this->toUserInfo['id']));?>">首页</div>
            <div class="ui-col<?php echo $this->selectNav == 'comment' ? ' active' : ''; ?>" data-href="<?php echo Yii::app()->createUrl('user/comment',array('id'=>$this->toUserInfo['id']));?>">点评</div>
            <div class="ui-col<?php echo $this->selectNav == 'favorite' ? ' active' : ''; ?>" data-href="<?php echo Yii::app()->createUrl('user/favorite',array('id'=>$this->toUserInfo['id']));?>">收藏</div>
        </div>
    </div>
<?php echo $content; ?>
</section>
<?php $this->renderPartial('/layouts/_nav');?>
<?php $this->endContent(); ?>