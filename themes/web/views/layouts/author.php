<?php 
$this->beginContent('/layouts/common'); 
?>
<style>
    .change-skin{
        background: url(<?php echo zmf::config('baseurl').'common/images/skin.gif';?>) no-repeat center
    }
</style>
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <?php $this->renderPartial('/layouts/_nav');?>
            <?php $this->renderPartial('/layouts/_user');?>
        </div>
    </div> 
</div>

<div class="container">
    <div class="author-bg-container" style="background-image: url(<?php echo zmf::getThumbnailUrl($this->authorInfo['skinUrl']);?>);">
        <?php if($this->adminLogin){?>
        <a href="<?php echo Yii::app()->createUrl('author/setting',array('type'=>'skin'));?>" title="更好皮肤">
            <div class="change-skin"></div>
        </a>
        <?php }?>
        <div class="author-header">
            <div class="author-avatar-fixed">
                <img src="<?php echo $this->authorInfo['avatar'];?>"/>
            </div>
            <h1><?php echo $this->authorInfo['authorName'];?></h1>
            <?php if(GroupPowers::checkAction($this->userInfo, 'favoriteAuthor')){?>
            <?php if($this->userInfo['authorId']!=$this->authorInfo['id']){if($this->favorited){?>
            <p><?php echo CHtml::link('<i class="fa fa-check"></i> 已关注','javascript:;',array('class'=>'btn btn-default btn-small','action'=>'favorite','action-data'=>$this->authorInfo['id'],'action-type'=>'author'));?></p>
            <?php }else{?>
            <p><?php echo CHtml::link('<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'btn btn-danger btn-small','action'=>'favorite','action-data'=>$this->authorInfo['id'],'action-type'=>'author'));?></p>
            <?php }}?>         
            <?php }?>
        </div>
    </div>
    <div class="author-content module author-module">
        <div class="main-part ">
            <?php echo $content; ?>
        </div>
        <div class="aside-part">
            <div class="author-side-info">
                <p><span class="info-label">入住</span><span class="txt"><?php echo zmf::time($this->authorInfo['cTime'],'Y-m-d');?></span></p>
                <p><span class="info-label">简介</span><span class="txt"><?php echo $this->authorInfo['content']!='' ? $this->authorInfo['content'] : '未设置';?></span></p>
                <?php $this->renderPartial('/common/share');?>
                <p><span class="color-grey"><?php echo CHtml::link('<i class="fa fa-exclamation-triangle"></i> 举报','javascript:;',array('action'=>'report','action-type'=>'author','action-id'=>$this->authorInfo['id'],'action-title'=>  $this->authorInfo['authorName']));?></span></p>
            </div>
            <div class="author-side-num">
                <a class="item" href="<?php echo Yii::app()->createUrl('author/fans',array('id'=>$this->authorInfo['id']));?>">
                    <span>追随者</span><br>
                    <strong><?php echo $this->authorInfo['favors'];?></strong>
                    <label> 人</label>
                </a>
                <a class="item" href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$this->authorInfo['id']));?>">
                    <span>作品数</span><br>
                    <strong><?php echo $this->authorInfo['posts'];?></strong>
                </a>
                <a class="item" href="javascript:;">
                    <span>热度</span><br>
                    <strong><?php echo $this->authorInfo['score'];?></strong>
                </a>
            </div>
            <div class="author-side-navbar">
                <?php echo CHtml::link('<i class="fa fa-list"></i> 作品<span></span>',array('author/view','id'=>$this->authorInfo['id']),array('class'=>'item'.($this->selectNav == 'index' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-star"></i> 追随者',array('author/fans','id'=>$this->authorInfo['id']),array('class'=>'item'.($this->selectNav == 'fans' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-comments"></i> 作者专区',array('posts/index','type'=>'author','aid'=>$this->authorInfo['id']),array('class'=>'item'));?>
                <?php if($this->adminLogin){?>
                <?php echo CHtml::link('<i class="fa fa-plus"></i> 新作品',array('author/createBook'),array('class'=>'item'.($this->selectNav == 'createBook' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-file"></i> 草稿箱',array('author/drafts'),array('class'=>'item'.($this->selectNav == 'drafts' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-edit"></i> 编辑资料',array('author/setting','type'=>'info'),array('class'=>'item'.($this->selectNav == 'updateinfo' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-lock"></i> 修改密码',array('author/setting','type'=>'passwd'),array('class'=>'item'.($this->selectNav == 'updatepasswd' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-cog"></i> 修改头像',array('author/setting','type'=>'avatar'),array('class'=>'item'.($this->selectNav == 'updateavatar' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-cog"></i> 设置皮肤',array('author/setting','type'=>'skin'),array('class'=>'item'.($this->selectNav == 'updateskin' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-sign-out"></i> 退出管理',array('author/logout'),array('class'=>'item'));?>
                <?php }elseif($this->uid && $this->userInfo['authorId']==$this->authorInfo['id']){?>
                <?php echo CHtml::link('<i class="fa fa-cog"></i> 进入作者管理中心',array('user/authorAuth'),array('class'=>'item'));?>
                <?php }?>
            </div>
        </div>
    </div>
    <?php $this->renderPartial('/common/copyright');?>
</div>
<div class="footer-bg" id="footer-bg"></div>
<?php $this->endContent(); ?>