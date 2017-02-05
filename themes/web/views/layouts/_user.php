<?php if(Yii::app()->user->hasFlash('fixedTipDialog')){?>
<div class="fixedDialog" id="fixedDialog">
    <?php echo Yii::app()->user->getFlash('fixedTipDialog');?>
</div>
<?php } if (!$this->uid) { ?>
<ul class="nav navbar-nav navbar-right">
    <?php if(in_array($from, array('user','author'))){?>
    <li><?php echo CHtml::link('<i class="fa fa-bell-o unread-bell"></i><span class="nav-title">搜索</span>', array('search/do'), array('role' => 'menuitem','title'=>'搜索')); ?></li>
    <?php }?>
    <li><?php echo CHtml::link('登录', array('site/login')); ?></li>
    <li><?php echo CHtml::link('注册', array('site/reg')); ?></li>
</ul>
<?php } else {?>
<ul class="nav navbar-nav navbar-right">
    <?php if(in_array($from, array('user','author'))){?>
    <li><?php echo CHtml::link('<i class="fa fa-search"></i><span class="nav-title">搜索</span>', array('search/do'), array('role' => 'menuitem','title'=>'搜索')); ?></li>
    <?php }?>
    <?php if(!$this->userInfo['authorId']){if(GroupPowers::checkAction($this->userInfo, 'createAuthor')){?>
    <li class="color-warning"><?php echo GroupPowers::link('createAuthor',$this->userInfo,'<i class="fa fa-exclamation-circle"></i> 成为作者', array('user/author'), array('role' => 'menuitem')); ?></li>
    <?php }}?>    
    <li><?php echo CHtml::link('<i class="fa fa-bell-o unread-bell"></i><span class="top-nav-count" id="top-nav-count">100</span><span class="nav-title">提醒</span>', array('user/notice'), array('role' => 'menuitem','title'=>'提醒')); ?></li>
    <li><?php echo CHtml::link('<i class="fa fa-tasks"></i><span class="nav-title">任务</span>', 'javascript:;', array('role' => 'menuitem','action'=>'float','action-data'=> Posts::encode($this->uid,'tasks'),'title'=>'任务')); ?></li>
    <li><?php echo CHtml::link('<i class="fa fa-magic"></i><span class="nav-title">背包</span>', array('user/props'), array('role' => 'menuitem','title'=>'背包')); ?></li>
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
            <li><?php echo CHtml::link('管理中心', array('admin/index/index'), array('role' => 'menuitem','target'=>'_blank')); ?></li>      
            <li role="separator" class="divider"></li>
            <?php } ?>
            <li><?php echo CHtml::link('退出', array('site/logout'), array('role' => 'menuitem')); ?></li>
        </ul>
    </li>
</ul>
<?php } ?>
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
                    <li><a href="javascript:;" id="float-link">查看全部</a></li>
                    <li><a href="javascript:;" onclick="$('#float-holder').hide()">关闭</a></li>
                </ul>
            </div>
        </div>        
    </div>    
</div>