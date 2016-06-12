<?php $this->renderPartial('_nav');?>
<table class="table table-striped">    
    <tr><td style="width: 120px">待审核帖子</td><td><?php echo CHtml::link($siteinfo['posts'],array('posts/index','type'=>'stayCheck'));?></td></tr>
    <tr><td>待审核评论</td><td><?php echo CHtml::link($siteinfo['comments'],array('comments/index','type'=>'stayCheck'));?></td></tr>
    <tr><td>待审核小说</td><td><?php echo CHtml::link($siteinfo['books'],array('books/index','type'=>'stayCheck'));?></td></tr>
    <tr><td>待审核章节</td><td><?php echo CHtml::link($siteinfo['chapters'],array('chapters/index','type'=>'stayCheck'));?></td></tr>
    <tr><td>待审核点评</td><td><?php echo CHtml::link($siteinfo['tips'],array('tips/index','type'=>'stayCheck'));?></td></tr>
</table>
<table class="table table-hover table-striped">	
    <tr><td style="width: 120px">服务器软件：</td><td><?php echo $siteinfo['serverOS']; ?>-<?php echo $siteinfo['serverSoft']; ?>  PHP-<?php echo $siteinfo['PHPVersion']; ?></td></tr>
    <tr><td>数据库版本：</td><td><?php echo $siteinfo['mysqlVersion'];?>（<?php echo $siteinfo['dbsize'];?>）</td></tr>
    <tr><td>上传许可：</td><td><?php echo $siteinfo['fileupload'];?></td></tr>
    <tr><td>主机名：</td><td><?php echo $siteinfo['serverUri'];?></td></tr>
    <tr><td>最大执行时间：</td><td><?php echo $siteinfo['maxExcuteTime'];?></td></tr>
    <tr><td>最大执行内存：</td><td><?php echo $siteinfo['maxExcuteMemory'];?></td></tr>
    <tr><td>当前使用内存：</td><td><?php echo $siteinfo['excuteUseMemory'];?></td></tr>
</table>