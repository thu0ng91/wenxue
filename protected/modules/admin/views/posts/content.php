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
<?php if($from=='order'){?>
<?php 
$form=$this->beginWidget('CActiveForm', array(
'id'=>'filter-form',
'method'=>'get',
'enableAjaxValidation'=>false,
)); ?>
<table>
    <tr>        
        <th>用户名</th>
        <th>订单号</th>
        <th>时间(起)</th>
        <th>时间(止)</th>
        <th>时间类型</th>
        <th>&nbsp;</th>
    </tr>
    <tr>
        
        <td ><input type="text" name="truename" class="form-control" value="<?php echo $_GET['truename'];?>"/></td>        
        <td ><input type="text" name="orderId" class="form-control" value="<?php echo $_GET['orderId'];?>"/></td>
        <td >
        <?php 
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
               'name' => 'start',
               'language' => 'zh-cn',
               'value' =>$_GET['start'],
               'options' => array(
                   'showAnim' => 'fadeIn',
               ),
               'htmlOptions' => array(
                   'class' => 'form-control',
               ),
           ));
        ?>    
        </td>
        <td >
        <?php 
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
               'name' => 'end',
               'language' => 'zh-cn',
               'value' =>$_GET['end'], 
               'options' => array(
                   'showAnim' => 'fadeIn',
               ),
               'htmlOptions' => array(
                   'class' => 'form-control',
               ),
           ));
        ?>
        </td>
        <td><?php echo CHtml::dropDownList('timeType', $_GET['timeType'], array('expiredTime'=>'过期时间','cTime'=>'创建时间','paidTime'=>'支付时间','updateTime'=>'更新时间'),array('class'=>'form-control','empty'=>'--请选择--'));?></td>
        <td><?php echo CHtml::submitButton('搜索',array('class'=>'btn btn-default')); ?></td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<?php }?>
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
        <?php if($field=='uid'){?>
        <td><?php echo $post[$field]>0 ? CHtml::link(Users::getUsername($post[$field]),array('users/view','id'=>$post['uid'])) : '匿名者 ';?></td>
        <?php }elseif($field=='aid'){?>
        <td><?php echo CHtml::link($post->authorInfo->authorName,array('authors/view','id'=>$post->aid));?></td>
        <?php }elseif($field=='bid'){?>
        <td><?php echo CHtml::link($post->bookInfo->title,array('books/view','id'=>$post->bid));?></td>
        <?php }elseif($field=='ip'){?>
        <td><?php echo long2ip($post[$field]);?></td>
        <?php }elseif($field=='bookStatus'){?>
        <td><span class="label label-<?php echo $post['bookStatus']==Books::STATUS_PUBLISHED ? "success" : "info";?>"><?php echo Books::exStatus($post['bookStatus']);?></span></td>
        <?php }elseif($field=='classify' && $from=='link'){?>
        <td><span class="label label-info"><?php echo Link::exTypes($post[$field]);?></span></td>
        <?php }elseif($field=='classify' && $from=='posts'){?>
        <td><span class="label label-info"><?php echo Posts::exClassify($post[$field]);?></span></td>        
        <?php }elseif($field=='url'){?>
        <td><?php echo CHtml::link(zmf::subStr($post[$field],10),$post[$field]);?></td>
        <?php }elseif(in_array($field,array('hot','new'))){?>
        <td><?php echo zmf::yesOrNo($post[$field]);?></td>
        <?php }elseif(in_array($field,array('expiredTime'))){?>        
        <td><?php echo $post[$field]>0 ? zmf::time($post[$field]):'';?></td>
        <?php }elseif(in_array($field,array('paidTime','cTime','updateTime'))){?>
        <td title="<?php echo $post[$field]>0 ? zmf::time($post[$field]) : '';?>"><?php echo $post[$field]>0 ? zmf::formatTime($post[$field]):'';?></td>
        <?php }elseif($field=='code' && $from=='siteInfo'){?>
        <td><?php echo SiteInfo::exTypes($post[$field]);?></td>
        <?php }else{?>
        <td><?php echo $post[$field];?></td>
        <?php }?>
        <?php }?>
        <td>
            <?php if($post['status']!=Posts::STATUS_PASSED && in_array($from, array('posts','chapters'))){?>
                <?php echo CHtml::link('查看敏感词',array('stayCheck','id'=>$post['id']));?>
                <?php echo CHtml::link('编辑',array('update','id'=>$post['id']));?>
                <?php echo CHtml::link('删除',array('delete','id'=>$post['id']));?>
            <?php }else{?>  
            <?php if($from=='books'){?>
                <?php echo CHtml::link('预览',array('/book/view','id'=>$post['id']),array('target'=>'_blank'));?>
            <?php }elseif($from=='chapters'){?>
                <?php echo CHtml::link('预览',array('/book/chapter','cid'=>$post['id']),array('target'=>'_blank'));?>
            <?php }?>
                <?php if($from=='reports'){?>
                <?php echo CHtml::link('详情',array('view','id'=>$post['id']));?>
                <?php }else{?>
                <?php echo CHtml::link('编辑',array('update','id'=>$post['id']));?>
                <?php }?>
                <?php if($from=='books'){?>
                <?php echo CHtml::link('章节',array('chapters/index','bid'=>$post['id']));?>                
                <?php }elseif($from=='showcases'){?>
                <?php echo CHtml::link('文章',array('showcaseLink/index','sid'=>$post['id']));?>
                <?php }elseif($from=='column'){?>
                <?php echo CHtml::link('预览',array('/showcase/index','cid'=>$post['id']),array('target'=>'_blank'));?>
                <?php }?>
                <?php echo CHtml::link('删除',array('delete','id'=>$post['id']));?>
            <?php }?>
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