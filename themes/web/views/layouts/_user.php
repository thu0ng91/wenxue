<?php if (!$this->uid) { ?>
<ul class="nav navbar-nav navbar-right">
    <li><?php echo CHtml::link('登录', array('site/login')); ?></li>
    <li><?php echo CHtml::link('注册', array('site/reg')); ?></li>
</ul>
<?php } else {?>
<ul class="nav navbar-nav navbar-right">
    <?php if(!$this->userInfo['authorId']){?>
    <li class="color-warning"><?php echo CHtml::link('<i class="fa fa-exclamation-circle"></i> 成为作者', array('user/author'), array('role' => 'menuitem')); ?></li>
    <?php }?>
    <li><?php echo CHtml::link('<i class="fa fa-bell-o unread-bell"></i><span class="top-nav-count" id="top-nav-count">100</span>', array('user/notice'), array('role' => 'menuitem')); ?></li>
    <li><?php echo CHtml::link('<i class="fa fa-tasks"></i>', 'javascript:;', array('role' => 'menuitem','action'=>'float','action-data'=> Posts::encode($this->uid,'tasks'))); ?></li>
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
        background: url('http://192.168.1.123/wenxue/common/images/tri_up.png') no-repeat center;
        width: 14px;
        height: 8px;
        z-index:1000
    }
    .float-holder .float-content{
        background: #fff;
        width: 100%;
        height: 100%;
        display: block;
        border: 1px solid #DDDDDD;
        margin-top: 7px;
        padding: 15px;
    }
</style>
<div class="float-holder" id='float-holder'>
    <div class="float-triangle" id='float-triangle'></div>
    <div class="float-content" id='float-content'>
        <span class="color-grey text-center">正在努力加载……</span>
    </div>
</div>