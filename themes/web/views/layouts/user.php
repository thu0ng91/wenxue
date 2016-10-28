<?php 
$this->beginContent('/layouts/common'); 
?>
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <?php echo CHtml::link(zmf::config('sitename'),  zmf::config('baseurl'),array('class'=>'navbar-brand'));?>
        <div class="navbar-collapse collapse">
            <?php $this->renderPartial('/layouts/_nav');?>
            <?php $this->renderPartial('/layouts/_user');?>
        </div>
    </div> 
</div>

<div class="container">
    <div class="main-part">
        <div class="module user-module">
            <h1><?php echo $this->toUserInfo['truename'];?></h1>
            <div class="module-body">
                <div class="media">
                    <div class="media-left">
                        <img class="media-object lazy" src="<?php echo zmf::lazyImg();?>" data-original="<?php echo zmf::getThumbnailUrl($this->toUserInfo['avatar'], 'a120', 'avatar');?>" alt="<?php echo $this->toUserInfo['truename'];?>">  
                        <div class="fixed-btn"><?php echo CHtml::link('更改头像',array('user/setting','action'=>'skin'));?></div>
                    </div>
                    <div class="media-body">
                        <?php if($this->toUserInfo['id']==$this->uid){?> 
                        <p><span class="uinfo-label">用户组</span><span class="uinfo-txt"><?php echo $this->toUserInfo['groupName'];?></span></p>
                        <?php }?>
                        <?php if($this->toUserInfo['levelTitle']){?>
                        <p><span class="uinfo-label">等级</span><span class="uinfo-txt"><?php echo $this->toUserInfo['levelTitle'];?></span></p>
                        <?php }?>
                        <p><span class="uinfo-label">性别</span><span class="uinfo-txt"><?php echo Users::userSex($this->toUserInfo['sex']);?></span></p>                        
                        <p><span class="uinfo-label">简介</span><span class="uinfo-txt"><?php echo $this->toUserInfo['content']!='' ? CHtml::encode(nl2br($this->toUserInfo['content'])) : '未设置';?></span></p>
                    </div>
                </div>
            </div>
            <div class="user-achiever">
                <div class="pull-right">
                    <?php if($this->toUserInfo['id']==$this->uid){?>                
                    <div class="btn-group" role="group">
                        <?php echo CHtml::link('完善资料',array('user/setting'),array('class'=>'btn btn-default'));?>
                        <?php if($this->userInfo['authorId']>0){?>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                作者中心
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php if($this->adminLogin){?>
                                <li><?php echo GroupPowers::link('addBook',$this->userInfo,'新增作品',array('author/createBook'));?></li>
                                <li><?php echo CHtml::link('进入作者中心',array('author/view','id'=>$this->userInfo['authorId']));?></li>
                                <?php }else{?>
                                <li><?php echo CHtml::link('登录作者中心',array('user/authorAuth'));?></li>
                                <?php }?>
                            </ul>
                        </div>
                        <?php }else{?>
                        <?php echo GroupPowers::link('createAuthor',$this->userInfo,'成为作者',array('user/author'),array('class'=>'btn btn-primary'));?>                        
                        <?php }?>                        
                    </div>                
                    <?php }elseif($this->uid){if(GroupPowers::checkAction($this->userInfo, 'favoriteUser')){?>                    
                    <div class="btn-group" role="group">
                        <?php if($this->favorited){?>
                        <?php echo CHtml::link('<i class="fa fa-star"></i> 已关注','javascript:;',array('class'=>'btn btn-default','action'=>'favorite','action-data'=>$this->toUserInfo['id'],'action-type'=>'user'));?>
                        <?php }else{?>
                        <?php echo CHtml::link('<i class="fa fa-star-o"></i> 关注','javascript:;',array('class'=>'btn btn-danger','action'=>'favorite','action-data'=>$this->toUserInfo['id'],'action-type'=>'user'));?>
                        <?php }?>
                    </div>
                    <?php }}?>
                </div>
            </div>
            <div class="user-navbar">
                <div class="navbar navbar-user" role="navigation">                    
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li<?php echo $this->selectNav == 'index' ? ' class="active"' : ''; ?>><?php echo CHtml::link('首页', array('user/index','id'=>$this->toUserInfo['id'])); ?></li>
                            <li<?php echo $this->selectNav == 'threads' ? ' class="active"' : ''; ?>><?php echo CHtml::link('帖子', array('user/threads','id'=>$this->toUserInfo['id'])); ?></li>
                            <li<?php echo $this->selectNav == 'comment' ? ' class="active"' : ''; ?>><?php echo CHtml::link('点评', array('user/comment','id'=>$this->toUserInfo['id'])); ?></li>
                            <?php if($this->toUserInfo['id']==$this->uid){?>
                            <li<?php echo $this->selectNav == 'favorite' ? ' class="active"' : ''; ?>><?php echo CHtml::link('收藏', array('user/favorite','id'=>$this->toUserInfo['id'])); ?></li>
                            <li<?php echo $this->selectNav == 'orders' ? ' class="active"' : ''; ?>><?php echo CHtml::link('订单', array('user/orders')); ?></li>
                            <li<?php echo $this->selectNav == 'props' ? ' class="active"' : ''; ?>><?php echo CHtml::link('背包', array('user/props')); ?></li>
                            <li<?php echo $this->selectNav == 'notice' ? ' class="active"' : ''; ?>><?php echo CHtml::link('消息', array('user/notice')); ?></li>
                            <li role="presentation" class="dropdown <?php echo $this->selectNav == 'setting' ? 'active' : ''; ?>">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    设置 <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><?php echo CHtml::link('基本资料', array('user/setting')); ?></li>
                                    <li><?php echo CHtml::link('修改密码', array('user/setting','action'=>'passwd')); ?></li>
                                    <li><?php echo CHtml::link('修改头像', array('user/setting','action'=>'skin')); ?></li>
                                </ul>
                            </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $content; ?>        
    </div>
    <div class="aside-part">
        <div class="module">
            <div class="side-following">
                <a class="item" href="<?php echo Yii::app()->createUrl('user/follow',array('id'=>$this->toUserInfo['id']));?>">
                    <span>关注了</span><br>
                    <strong><?php echo $this->toUserInfo['favord'];?></strong>
                    <label> 人</label>
                </a>
                <a class="item" href="<?php echo Yii::app()->createUrl('user/follow',array('id'=>$this->toUserInfo['id'],'type'=>'fans'));?>">
                    <span>关注者</span><br>
                    <strong><?php echo $this->toUserInfo['favors'];?></strong>
                    <label> 人</label>
                </a>
            </div>
            <div class="side-module">
                <div class="side-module-header">关注了<?php echo $this->toUserInfo['favorAuthors']>0 ? CHtml::link($this->toUserInfo['favorAuthors'].'个作者',array('user/follow','id'=>$this->toUserInfo['id'],'type'=>'authors')) : ' 0个作者';?></div>
            </div>
            <div class="side-module">
                <span class="color-grey">主页被访问<?php echo $this->toUserInfo['hits'];?>次</span>
                <span class="color-grey"><?php echo CHtml::link('<i class="fa fa-exclamation-triangle"></i> 举报','javascript:;',array('action'=>'report','action-type'=>'user','action-id'=>$this->toUserInfo['id'],'action-title'=>  $this->toUserInfo['truename']));?></span>
            </div>
        </div>        
    </div>
    <div class="clearfix"></div>
    <?php $this->renderPartial('/common/copyright');?>
</div>
<div class="footer-bg" id="footer-bg"></div>
<?php $this->endContent(); ?>