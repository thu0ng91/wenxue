<?php

/**
 * @filename content.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2015 阿年飞少 
 * @datetime 2016-3-17  15:03:45 
 */
$this->renderPartial('/posts/_nav');
if(empty($posts)){?>
<div class="alert alert-info">
    <p>暂无内容！</p> 
</div>
<?php }else{?>
<table class="table table-striped">
    <tr>
        <?php foreach($selectArr as $_field){$field= zmf::formatField($_field);if(in_array($field, array('id','status')))continue;?>
        <th><?php echo $model->getAttributeLabel($field);?></th>
        <?php }?>
        <th style="width: 200px">操作</th>
    </tr>
    <?php foreach($posts as $post){?>
    <tr <?php if($post['status']!=Posts::STATUS_PASSED){?>class="danger"<?php }?>>
        <?php foreach($selectArr as $_field){$field= zmf::formatField($_field);if(in_array($field, array('id','status')))continue;?>
        <?php if($field=='position'){?>
        <td><?php echo Showcases::exPosition($post[$field]);?></td>
        <?php }elseif($field=='display'){?>
        <td><?php echo Showcases::exDisplay($post[$field]);?></td>
        <?php }else{?>
        <td><?php echo $post[$field].$field;?></td>
        <?php }?>
        <?php }?>
        <td>            
            <?php echo CHtml::link('编辑',array('update','id'=>$post['id']));?>                
            <?php echo CHtml::link('文章',array('showcaseLink/index','sid'=>$post['id']));?>                
            <?php echo CHtml::link('删除',array('delete','id'=>$post['id']));?>            
        </td>
    </tr>
    <?php }?>
</table>
<?php }?>
<div class="row">
    <div class="col-xs-2 col-sm-2">
        <div class="btn-group" role="group">
            <?php 
            if($from=='chapters') {
                echo CHtml::link('新增', array('chapters/create','bid'=>$bid), array('class' => 'btn btn-primary'));
            }elseif($from=='showcaseLink') {
                echo CHtml::link('新增', array('create','sid'=>$sid), array('class' => 'btn btn-primary'));
            }elseif($from=='reports') {  
                
            } else {
                echo CHtml::link('新增', array('create'), array('class' => 'btn btn-primary'));
                //echo CHtml::link('管理', array('admin'), array('class' => 'btn btn-default'));
            }
            ?>
        </div>
    </div>
    <div class="col-xs-10 col-sm-10">
        <?php if(!empty($posts)){$this->renderPartial('/common/pager',array('pages'=>$pages));}?>        
    </div>    
</div>