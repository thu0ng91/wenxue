<?php if(Yii::app()->user->hasFlash('fixedTipDialog')){?>
<div class="fixedDialog" id="fixedDialog">
    <?php echo Yii::app()->user->getFlash('fixedTipDialog');?>
</div>
<?php } if (!$this->uid) { ?>
<ul class="nav navbar-nav navbar-right">
    <li><?php echo CHtml::link('登录', array('site/login')); ?></li>
    <li><?php echo CHtml::link('注册', array('site/reg')); ?></li>
</ul>
<?php } else {?>
<ul class="nav navbar-nav navbar-right">
    <?php if(!$this->userInfo['authorId']){?>
    <li class="color-warning"><?php echo GroupPowers::link('createAuthor',$this->userInfo,'<i class="fa fa-exclamation-circle"></i> 成为作者', array('user/author'), array('role' => 'menuitem')); ?></li>
    <?php }?>
    <li><?php echo CHtml::link('<i class="fa fa-bell-o unread-bell"></i><span class="top-nav-count" id="top-nav-count">100</span>', array('user/notice'), array('role' => 'menuitem','title'=>'提醒')); ?></li>
    <li><?php echo CHtml::link('<i class="fa fa-tasks"></i>', 'javascript:;', array('role' => 'menuitem','action'=>'float','action-data'=> Posts::encode($this->uid,'tasks'),'title'=>'任务')); ?></li>
    <li><?php echo CHtml::link('<i class="fa fa-magic"></i>', array('user/props'), array('role' => 'menuitem','title'=>'背包')); ?></li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->userInfo['truename']; ?> <span class="caret"></span></a>               
        <ul class="dropdown-menu">
            <li><?php echo CHtml::link('个人中心', array('user/index'), array('role' => 'menuitem')); ?></li>
            <?php if($this->userInfo['authorId']>0){?>
            <li role="separator" class="divider"></li>
            <li><?php if(Authors::checkLogin($this->userInfo, $this->userInfo['authorId'])){echo CHtml::link('作者中心', array('author/view','id'=>$this->userInfo['authorId']), array('role' => 'menuitem'));}else{echo CHtml::link('作者中心', array('user/authorAuth'), array('role' => 'menuitem'));}?></li>
            <?php }?>
            <?php if ($this->userInfo['isAdmin']) { ?>
            <li role="separator" class="divider"></li>
            <li><?php echo CHtml::link('管理中心', array('admin/index/index'), array('role' => 'menuitem')); ?></li>      
            <li role="separator" class="divider"></li>
            <?php } ?>
            <li><?php echo CHtml::link('退出', array('site/logout'), array('role' => 'menuitem')); ?></li>
        </ul>
    </li>
</ul>
<?php } ?>
<style>
    .fixedDialog{
        position: fixed;
        top: 40%;
        left: 50%;
        background: #d9534f;
        padding: 15px;
        z-index: 999;
        border-radius:5px;
        box-shadow: 3px 3px 3px #e4e4e4;
        color:#fff
    }
    .float-holder{
        position: absolute;
        right: 0;
        top: 0;
        width: 360px;
        z-index: 999;
        display:none;
        box-sizing: border-box
    }
    .float-holder .float-triangle{
        position: absolute; 
        background: url('<?php echo zmf::config('baseurl');?>common/images/tri_up.png') no-repeat center;
        width: 14px;
        height: 8px;
        z-index:1000
    }
    .float-holder .float-content{
        width: 100%;
        height: 100%;
        display: block;
        margin-top: 7px;        
    }
    .float-holder .float-content .module .module-body{
        padding:15px 10px 10px;
    }
    .float-holder .float-content .media .media-body p{
        line-height:22px;
    }
    .float-holder .float-footer{
        background:#fff;
        width:100%;
        height:36px;
        border-top: 1px solid #DDDDDD;
        box-sizing: border-box
    }
    .float-holder .float-footer ul{
        padding:0;
        margin:0
    }
    .float-holder .float-footer ul li{        
        text-align:center;
        width:50%;
        float:left;
        list-style:none;
        line-height: 36px;
        border-right:1px solid #DDDDDD;
    }
    .float-holder .float-footer ul li:last-child{
        border-right:none
    }
    .float-holder .float-footer ul li a{
        width:100%;
        height:100%;
        display:block;
        color:#DDDDDD
    }
    .float-holder .float-footer ul li:hover{
        background:#DDDDDD;        
    }
    .float-holder .float-footer ul li:hover a{
        color:#999;
        text-decoration:none
    }
</style>
<div class="float-holder" id='float-holder'>
    <div class="float-triangle" id='float-triangle'></div>
    <div class="float-content">
        <div class="module">
            <div class="module-header">任务列表</div>
            <div class="module-body" id='float-content'>
                <span class="color-grey text-center">正在努力加载……</span>
            </div>
            <div class="float-footer">
                <ul>
                    <li><a href="">查看全部</a></li>
                    <li><a href="javascript:;" onclick="$('#float-holder').hide()">关闭</a></li>
                </ul>
            </div>
        </div>        
    </div>    
</div>