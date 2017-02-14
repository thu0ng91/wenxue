<?php $this->beginContent('/layouts/common'); ?>
<div class="top-header">                
    <nav class="navbar navbar-default">
        <div class="container-fluid">
        <div class="navbar-header">                        
            <?php echo CHtml::link('管理中心',array('index/index'),array('class'=>'navbar-brand'));?>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">                  
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $this->userInfo['truename'];?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><?php echo CHtml::link('站点首页',  zmf::config('baseurl'),array('role'=>'menuitem','target'=>'_blank'));?></li>
                        <li class="divider"></li>
                        <li><?php echo CHtml::link('退出',array('/site/logout'),array('role'=>'menuitem'));?></li>
                    </ul>
                </li>
            </ul>
        </div>
        </div>
    </nav>
</div>
<div class="settings-side-box" id="settings-side-box">        
    <?php echo CHtml::link('<h1 class="side-header">管理中心</h1>',array('index/index'));?>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <?php $navs=  AdminCommon::navbar();?>
        <?php foreach($navs as $nav){if(!$nav['seconds']){?>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">                    
                <?php echo CHtml::link('<h4 class="panel-title">'.$nav['title'].'<span class="pull-right"><i class="fa fa-angle-right"></i></span></h4>',$nav['url'],array('class'=>'collapsed'));?>
            </div>
        </div>
        <?php }else{$_id= zmf::randMykeys(6, 2);$_active=false;foreach($nav['seconds'] as $v){if($v['active']){$_active=true;break;}}?>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $_id;?>" aria-expanded="false" aria-controls="<?php echo $_id;?>" class="collapsed" target="main">
                    <h4 class="panel-title">
                        <?php echo $nav['title'];?>
                        <span class="pull-right"><i class="fa fa-angle-down"></i></span>
                    </h4>
                </a>
            </div>
            <div id="<?php echo $_id;?>" class="panel-collapse collapse <?php echo $_active ? 'in' : '';?>" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false">
                <div class="panel-body">
                    <div class="list-group">
                        <?php foreach($nav['seconds'] as $v){?>
                        <?php echo CHtml::link($v['title'],$v['url'],array('class'=>'list-group-item'.($v['active'] ? ' active' : '')));?>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>            
        <?php }}?>
    </div>
    <a href="javascript:;" class="side-toggle hidden" id="side-toggle"></a>
</div>
<div class="settings-main-box">
    <?php if(!empty($this->breadcrumbs)){?>
    <ol class="breadcrumb">
        <?php foreach($this->breadcrumbs as $k=>$v){?>
        <li><?php echo is_array($v) ? CHtml::link($k,$v):$v;?></li>
        <?php }?>
    </ol>
    <?php }?>     
    <?php echo $content; ?>
</div>
   
<?php $this->endContent(); ?>