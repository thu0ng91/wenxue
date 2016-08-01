<?php 
$this->beginContent('/layouts/common'); 
?>
<div class="author-header">
    <div class="author-bg-container" style="background-image: url(<?php echo zmf::getThumbnailUrl($this->authorInfo['skinUrl']);?>);"></div>
    <div class="author-avatar">
        <img src="<?php echo $this->authorInfo['avatar'];?>"/>
    </div>
    <h1><?php echo $this->authorInfo['authorName'];?></h1>
    <?php if($this->userInfo['authorId']!=$this->authorInfo['id']){if($this->favorited){?>
    <p><?php echo CHtml::link('<i class="fa fa-check"></i> 已关注','javascript:;',array('class'=>'btn btn-default btn-small','action'=>'favorite','action-data'=>$this->authorInfo['id'],'action-type'=>'author'));?></p>
    <?php }else{?>
    <p><?php echo CHtml::link('<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'btn btn-danger btn-small','action'=>'favorite','action-data'=>$this->authorInfo['id'],'action-type'=>'author'));?></p>
    <?php }}?>
    <?php if($this->uid && $this->userInfo['authorId']==$this->authorInfo['id']){?>
    <div class="author-setting" onclick="$('#author-fixe-navs').toggle();"><i class="fa fa-cog"></i></div>
    <div class="author-fixe-navs" id="author-fixe-navs">
        <i class="fa fa-caret-right"></i>
        <?php if($this->adminLogin){?>
        <?php echo CHtml::link('<i class="fa fa-plus"></i> 新作品',array('author/createBook'),array('class'=>'item'.($this->selectNav == 'createBook' ? ' active' : '')));?>
        <?php echo CHtml::link('<i class="fa fa-clipboard"></i> 草稿箱',array('author/drafts'),array('class'=>'item'.($this->selectNav == 'drafts' ? ' active' : '')));?>
        <?php echo CHtml::link('<i class="fa fa-edit"></i> 编辑资料',array('author/setting','type'=>'info'),array('class'=>'item'.($this->selectNav == 'updateinfo' ? ' active' : '')));?>
        <?php echo CHtml::link('<i class="fa fa-lock"></i> 修改密码',array('author/setting','type'=>'passwd'),array('class'=>'item'.($this->selectNav == 'updatepasswd' ? ' active' : '')));?>
        <?php echo CHtml::link('<i class="fa fa-user"></i> 修改头像',array('author/setting','type'=>'avatar'),array('class'=>'item'.($this->selectNav == 'updateavatar' ? ' active' : '')));?>
        <?php echo CHtml::link('<i class="fa fa-tachometer"></i> 设置皮肤',array('author/setting','type'=>'skin'),array('class'=>'item'.($this->selectNav == 'updateskin' ? ' active' : '')));?>
        <?php echo CHtml::link('<i class="fa fa-sign-out"></i> 退出管理',array('author/logout'),array('class'=>'item'));?>
        <?php }else{?>
        <?php echo CHtml::link('<i class="fa fa-cog"></i> 登录管理',array('user/authorAuth'),array('class'=>'item'));?>
        <?php }?>
    </div>
    <?php }?>
</div>
<div class="clearfix"></div>
<div class="author-content ui-container header-hack">
    <div class="ui-row-flex author-content-nav" style="clear:both">
        <div class="ui-col<?php echo $this->selectNav == 'index' ? ' active' : ''; ?> ui-border-r" data-href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$this->authorInfo['id']));?>">作品</div>
        <div class="ui-col<?php echo $this->selectNav == 'fans' ? ' active' : ''; ?>" data-href="<?php echo Yii::app()->createUrl('author/fans',array('id'=>$this->authorInfo['id']));?>">追随者</div>
    </div>
    <?php echo $content; ?>    
</div> 
<?php $this->renderPartial('/layouts/_nav');?>
<?php $this->endContent(); ?>