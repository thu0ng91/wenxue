<?php 
$this->beginContent('/layouts/common'); 
$cols=  Column::allCols();
?>
<style>
    .font-bold{
        font-weight: bold
    }
    .user-module{
        padding: 0
    }
    .user-module h1{
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 0;
        padding-bottom: 0;
        padding-left: 20px;
    }
    .user-achiever{
        padding: 10px 20px;
        border-top: 1px solid #e6e6e6;
        border-bottom: 1px solid #e6e6e6;
        display: block;
        min-height: 54px;
        line-height: 34px;
    }
    .user-navbar .navbar{
        margin-bottom: -2px;
        padding-bottom: 0;
        border-top: 0
    }
    
    .navbar-user .navbar-nav>.active>a, .navbar-user .navbar-nav>.active>a:focus, .navbar-user .navbar-nav>.active>a:hover,.navbar-user .navbar-nav>li>a:hover{
        color: #fff;
        background-color: #93ba5f;
    }
    
    
    .side-following {
        border-bottom: 1px solid #eee;
        margin-bottom: 15px;
        float: left;
        width: 100%;
        padding: 15px 0 0 15px
    }
    .side-following .item {
        text-decoration: none;
        padding: 2px 30px 8px 0;
        float: left
    }
    .side-following .item+.item {
        padding-left: 20px;
        border-left: 1px solid #eee;
    }
    .side-following .item strong {
        font-size: 16px;
        font-weight: 700;
        color: #666;
    }
    .side-following .item label {
        font-size: 13px;
        font-weight: 400;
        vertical-align: 1px;
        color: #666;
        cursor: pointer;
    }
    .side-module{
        border-bottom: 1px solid #f2f2f2;
        padding: 10px 0 10px 15px;
    }
    .side-module .side-module-header{
        font-weight:700
    }
    .side-module:last-child{
        border: none
    }
    .user-following{
       padding-left: 0;
       padding-right: 0; 
       padding-top: 0
    }
    .user-following .row{
        text-align:center
    }
</style>
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link('首页', zmf::config('baseurl')); ?></li>
                <?php foreach ($cols as $colid=>$colTitle){?>
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link($colTitle, array('showcase/index','cid'=>$colid)); ?></li>
                <?php }?>
                <li<?php echo $this->selectNav == 'authorForum' ? ' class="active"' : ''; ?>><?php echo CHtml::link('作者专区', array('posts/index','type'=>'author')); ?></li>
                <li<?php echo $this->selectNav == 'readerForum' ? ' class="active"' : ''; ?>><?php echo CHtml::link('读者专区', array('posts/index','type'=>'reader')); ?></li>
            </ul>
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
                        <img class="media-object" src="<?php echo $this->toUserInfo['avatar'];?>" alt="<?php echo $this->toUserInfo['truename'];?>">                        
                    </div>
                    <div class="media-body">
                        <p>所在地 </p>
                        <p>姓别 <i class="fa fa-mars"></i> </p>
                        <p>简介</p>
                    </div>
                </div>

            </div>
            <div class="user-achiever">
                <span class="color-grey">获得 <i class="fa fa-thumbs-up"></i> 0赞同</span>
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
                                <?php if($this->authorLogin){?>
                                <li><?php echo CHtml::link('新增作品',array('author/createBook'));?></li>
                                <li><?php echo CHtml::link('进入作者中心',array('author/view','id'=>$this->userInfo['authorId']));?></li>
                                <?php }else{?>
                                <li><?php echo CHtml::link('登录作者中心',array('user/authorAuth'));?></li>
                                <?php }?>
                            </ul>
                        </div>
                        <?php }else{?>
                        <?php echo CHtml::link('成为作者',array('user/author'),array('class'=>'btn btn-primary'));?>                        
                        <?php }?>                        
                    </div>                
                    <?php }else{?>
                    <div class="btn-group" role="group">
                        <?php echo CHtml::link('<i class="fa fa-star-o"></i> 关注','javascript:;',array('class'=>'btn btn-'.($this->favorited ? 'danger' :'default'),'action'=>'favorite','action-data'=>$this->toUserInfo['id'],'action-type'=>'user'));?>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div class="user-navbar">
                <div class="navbar navbar-user" role="navigation">                    
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li<?php echo $this->selectNav == 'index' ? ' class="active"' : ''; ?>><?php echo CHtml::link('首页', array('user/index','id'=>$this->toUserInfo['id'])); ?></li>
                            <li<?php echo $this->selectNav == 'comment' ? ' class="active"' : ''; ?>><?php echo CHtml::link('点评', array('user/comment','id'=>$this->toUserInfo['id'])); ?></li>       
                            <li<?php echo $this->selectNav == 'favorite' ? ' class="active"' : ''; ?>><?php echo CHtml::link('收藏', array('user/favorite','id'=>$this->toUserInfo['id'])); ?></li>
                            <?php if($this->toUserInfo['id']==$this->uid){?>
                            <li<?php echo $this->selectNav == 'notice' ? ' class="active"' : ''; ?>><?php echo CHtml::link('消息', array('user/notice')); ?></li>
                            <li<?php echo $this->selectNav == 'setting' ? ' class="active"' : ''; ?>><?php echo CHtml::link('设置', array('user/setting')); ?></li>
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
            </div>
        </div>        
        <?php $this->renderPartial('/common/copyright');?>
    </div>
</div>
<div class="footer-bg" id="footer-bg"></div>
<?php $this->endContent(); ?>