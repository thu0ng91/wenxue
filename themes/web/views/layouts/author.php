<?php 
$this->beginContent('/layouts/common'); 
$cols=  Column::allCols();
?>
<style>
    .author-module{
        display:block;
        height:100%;
        padding:0
    }
    .author-module .main-part{
        border-right:1px solid #e6e6e6
    }
    .author-module .aside-part{
        width:315px
    }
    .author-bg-container{
        width: 960px;
        height: 300px;
        background: url(<?php echo zmf::config('baseurl');?>common/temp/011.jpg) no-repeat center;
        position:relative
    }
    .author-header{
        width:100px;
        height:200px;
        position:absolute;
        top:50%;
        left:50%;
        margin-top:-100px;
        margin-left:-50px;
    }
    .author-avatar-fixed{
        width: 100px;
        height: 100px;
        margin: 0 auto;
        padding:4px;
        background:rgba(255,255,255,.3);
        border-radius:200px;
    }
    .author-avatar-fixed img{
        border-radius: 200px;
        width: 100%;
        height: 100%;
    }
    .author-header{
        text-align:center
    }
    .author-header h1{
        text-aligin:center;
        font-size:24px;
        font-weight:700;
        color:#fff;
        padding-top:0;
        margin-top:15px;
        margin-bottom:0;
        padding-bottom:10px
    }
    .author-content{
        margin-top:25px;
        float:left
    }
    .author-side-info{
        padding:15px 10px 0 10px;
        border-bottom:1px solid #e6e6e6
    }
    .author-side-info .info-label{
        float:left;
        min-width:40px;
        font-weight:700
    }
    .author-side-info .txt{
        color:#999
    }
    .author-side-num {
        border-bottom: 1px solid #eee;
        margin-bottom: 0;
        float: left;
        width: 100%;
        padding:0 0 0 10px
    }
    .author-side-num .item {
        text-decoration: none;
        padding: 15px 30px 8px 0;
        float: left
    }
    .author-side-num .item+.item {
        padding-left: 20px;
        border-left: 1px solid #eee;
    }
    .author-side-num .item strong {
        font-size: 16px;
        font-weight: 700;
        color: #666;
    }
    .author-side-num .item label {
        font-size: 13px;
        font-weight: 400;
        vertical-align: 1px;
        color: #666;
        cursor: pointer;
    }
    
    
    .author-side-navbar{
        clear:both
    }
    .author-side-navbar a{
        display:block;
        width:100%;
        padding:10px 0 10px 10px;
    }
    .author-side-navbar .item+.item {
        border-top: 1px solid #eee;
    }
    .author-side-navbar .active{
        color:#fff;
        background:#93ba5f
    }
    .author-side-navbar .fa{
        margin-right:6px
    }
    
    .author-content-holder{
        padding:10px 0 10px 15px
    }
    .author-content-holder .media{
        border-bottom:1px solid #e6e6e6;
        margin-bottom:10px;
        padding-bottom:10px;
        margin-top:0;
        padding-right:10px;
    }
    .author-content-holder .media img{
        width:90px;
        height:127px;
    }
    .author-content-holder .media .right-actions{
        float:right;
        display:inline-block
    }
    .author-following{
        padding-top:15px;
    }
    .author-following a{
        text-align:center
    }
    .create-book-form{
        padding-right:15px;
    }
    .create-book-form .thumbnail{
        border:none;
        padding:0
    }
    .create-book-form .thumbnail img{
        width:120px;
        height:160px;
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
    <div class="author-bg-container">
        <div class="author-header">
            <div class="author-avatar-fixed">
                <img src="<?php echo $this->authorInfo['avatar'];?>"/>
            </div>
            <h1><?php echo $this->authorInfo['authorName'];?></h1>
            <p><?php echo CHtml::link('<i class="fa fa-plus"></i> 关注','javascript:;',array('class'=>'btn btn-'.($this->favorited ? 'danger' :'default').' btn-small','action'=>'favorite','action-data'=>$this->authorInfo['id'],'action-type'=>'author'));?></p>
        </div>
    </div>
    <div class="author-content module author-module">
        <div class="main-part ">
            <?php echo $content; ?>
        </div>
        <div class="aside-part">
            <div class="author-side-info">
                <p><span class="info-label">性别</span><span class="txt">男</span></p>
                <p><span class="info-label">入住</span><span class="txt"><?php echo zmf::time($this->authorInfo['cTime'],'Y-m-d');?></span></p>
                <p><span class="info-label">简介</span><span class="txt"><?php echo $this->authorInfo['content'];?></span></p>
            </div>
            <div class="author-side-num">
                <a class="item" href="<?php echo Yii::app()->createUrl('author/fans',array('id'=>$this->authorInfo['id']));?>">
                    <span>追随者</span><br>
                    <strong><?php echo $this->authorInfo['favors'];?></strong>
                    <label> 人</label>
                </a>
                <a class="item" href="#">
                    <span>作品数</span><br>
                    <strong><?php echo $this->authorInfo['posts'];?></strong>
                </a>
                <a class="item" href="#">
                    <span>热度</span><br>
                    <strong><?php echo $this->authorInfo['score'];?></strong>
                </a>
            </div>
            <div class="author-side-navbar">
                <?php echo CHtml::link('<i class="fa fa-list"></i> 作品',array('author/view','id'=>$this->authorInfo['id']),array('class'=>'item'.($this->selectNav == 'index' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-star"></i> 追随者',array('author/fans','id'=>$this->authorInfo['id']),array('class'=>'item'.($this->selectNav == 'fans' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-comments"></i> 作者专区',array('posts/index','type'=>'author','aid'=>$this->authorInfo['id']),array('class'=>'item'));?>
                <?php if($this->adminLogin){?>
                <?php echo CHtml::link('<i class="fa fa-plus"></i> 新作品',array('author/createBook'),array('class'=>'item'.($this->selectNav == 'createBook' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-file"></i> 草稿箱',array('author/drafts'),array('class'=>'item'.($this->selectNav == 'drafts' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-edit"></i> 编辑资料',array('author/setting','type'=>'info'),array('class'=>'item'.($this->selectNav == 'updateinfo' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-cog"></i> 设置皮肤',array('author/setting','type'=>'skin'),array('class'=>'item'.($this->selectNav == 'updateskin' ? ' active' : '')));?>
                <?php echo CHtml::link('<i class="fa fa-sign-out"></i> 退出管理',array('author/logout'),array('class'=>'item'));?>
                <?php }elseif($this->uid && $this->userInfo['authorId']==$this->authorInfo['id']){?>
                <?php echo CHtml::link('<i class="fa fa-cog"></i> 进入作者管理中心',array('user/authorAuth'),array('class'=>'item'));?>
                <?php }?>
            </div>
        </div>
    </div>    
</div>
<div class="footer-bg" id="footer-bg"></div>
<?php $this->endContent(); ?>