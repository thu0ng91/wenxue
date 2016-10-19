<div class="module">
    <div class="module-header">消息通知</div>
    <?php if(!empty($posts)){?>
    <div class="module-body">
        <ul class="ui-list">
        <?php foreach ($posts as $row): ?> 
            <li class="ui-border-t" id="notice-<?php echo $row['id'];?>">
                <?php if($row['avatar']){?>
                <div class="ui-list-img">
                    <span style="background-image:url(<?php echo $row['avatar'];?>)"></span>
                </div>
                <?php }?>
                <div class="ui-list-info">
                    <p class="ui-nowrap-multi <?php echo $row['new'] ? 'title' : '';?>"><?php echo $row['content']; ?></p>
                    <p class="help-block">
                        <?php echo CHtml::link($row['truename'],array('user/index','id'=>$row['authorid']));?>                    
                        <?php echo zmf::formatTime($row['cTime']);?>
                        <span class="pull-right">                
                            <?php echo CHtml::link('删除','javascript:;',array('action'=>'delContent','data-type'=>'notice','data-id'=>  $row['id'],'data-confirm'=>1,'data-target'=>'notice-'.$row['id'],'title'=>'删除此消息'));?>
                        </span>
                    </p>
                </div>
            </li>
        <?php endforeach; ?>     
        </ul>
        <?php $this->renderPartial('/common/pager', array('pages' => $pages)); ?>
    </div>
    <?php }else{?>
    <div class="module-body padding-body">
        <p class="help-block text-center">暂无消息</p> 
    </div>
    <?php }?>
</div>