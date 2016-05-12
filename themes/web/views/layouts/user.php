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
        width: 100%
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
</style>
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link('首页', zmf::config('baseurl')); ?></li>
                <?php foreach ($cols as $colid=>$colTitle){?>
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link($colTitle, array('book/index')); ?></li>
                <?php }?>
                <li<?php echo $this->selectNav == 'zazhi' ? ' class="active"' : ''; ?>><?php echo CHtml::link('作者专区', array('author/index')); ?></li>                   
                <li<?php echo $this->selectNav == 'about' ? ' class="active"' : ''; ?>><?php echo CHtml::link('读者专区', array('readers/index')); ?></li>
            </ul>
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
                        <a href="#">
                            <img class="media-object" src="<?php echo $this->toUserInfo['avatar'];?>" alt="<?php echo $this->toUserInfo['truename'];?>">
                        </a>
                    </div>
                    <div class="media-body">
                        <p>所在地 </p>
                        <p>姓别 <i class="fa fa-mars"></i> </p>
                        <p>简介</p>
                    </div>
                </div>

            </div>
            <div class="user-achiever">
                <span class="">获得 <i class="fa fa-thumbs-up"></i> 0赞同</span>
                <span class="pull-right btn btn-default">完善资料</span>
            </div>
            <div class="user-navbar">
                <div class="navbar navbar-user" role="navigation">                    
                    <div class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li><?php echo CHtml::link('首页', zmf::config('baseurl')); ?></li>
                            <li<?php echo $this->selectNav == 'comment' ? ' class="active"' : ''; ?>><?php echo CHtml::link('点评', array('user/comment','id'=>$this->toUserInfo['id'])); ?></li>       
                            <li<?php echo $this->selectNav == 'favorite' ? ' class="active"' : ''; ?>><?php echo CHtml::link('收藏', array('user/favorite','id'=>$this->toUserInfo['id'])); ?></li>
                            <li<?php echo $this->selectNav == 'notice' ? ' class="active"' : ''; ?>><?php echo CHtml::link('消息', array('user/notice','id'=>$this->toUserInfo['id'])); ?></li>
                            <li<?php echo $this->selectNav == 'setting' ? ' class="active"' : ''; ?>><?php echo CHtml::link('设置', array('user/setting')); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $content; ?>
    </div>
    <div class="aside-part module">        
        <div class="side-following">
            <a class="item" href="#">
                <span>关注了</span><br>
                <strong>3</strong>
                <label> 人</label>
            </a>
            <a class="item" href="#">
                <span>关注者</span><br>
                <strong>0</strong>
                <label> 人</label>
            </a>
        </div>
        
    </div>
</div>
<?php $this->endContent(); ?>