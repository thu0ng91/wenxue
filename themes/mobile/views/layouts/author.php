<?php 
$this->beginContent('/layouts/common'); 
?>
<style>
    
    .author-header{
        text-align: center;
        position: relative;
        width: 100%;
        height: 240px;
    }
    .author-bg-container{
        width: 100%;
        height: 100%;
        background-size:cover;
        -webkit-filter: blur(20px);
        -moz-filter: blur(20px);
        -ms-filter: blur(20px);
        position: absolute;
        left: 0;
        top: 0;
        z-index: -1;
        
    }
    .author-header .author-avatar{
        width: 64px;
        height: 64px;        
        border-radius: 200px;
        padding-top: 60px;
        margin: 0 auto
    }
    .author-header .author-avatar img{
        width: 64px;
        height: 64px;
        border-radius: 200px;
        border: 1px solid #fff
    }
    .author-header h1{
        color: #fff;
        font-weight: 700;        
        margin-top: 10px
    }    
    .author-header .author-setting{
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 18px;
        color: #fff
    }
    .author-header .author-fixe-navs{
        position: absolute;
        right: 45px;
        top: 10px;
        width: 100px;
        min-height: 40px;
        background: #fff;
        padding: 5px 10px;
        box-sizing: border-box;
        border-radius: 5px;
        border: 1px solid #F2f2f2;
        box-shadow: 3px 3px 3px #333;  
        display: none
    }
    .author-header .author-fixe-navs .fa-caret-right{
        position: absolute;
        top: 15px;
        right: -6px;
        color: #fff;
        font-size: 18px;
    }
    .author-header .author-fixe-navs a{
        display: block;
        text-align: left;
        line-height: 36px;
        border-bottom: 1px solid #F2f2f2
    }
    .author-header .author-fixe-navs a:last-child{
        border: none
    }
    .author-content .author-content-nav{
        background: #fff;
        height: 48px;
        line-height: 48px;
        text-align: center;
        color: #ccc;
        margin-bottom: 15px;
    }
    .author-content .author-content-nav .active{
        color: #93ba5f
    }
</style>   
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