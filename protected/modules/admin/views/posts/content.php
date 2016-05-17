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
        <?php foreach($selectArr as $field){if($field=='id')continue;?>
        <th><?php echo $model->getAttributeLabel($field);?></th>
        <?php }?>
        <th>操作</th>
    </tr>
    <?php foreach($posts as $post){?>
    <tr>
        <?php foreach($selectArr as $field){if($field=='id')continue;?>
        <?php if($field=='uid'){?>
        <td><?php echo CHtml::link(Users::getUsername($post[$field]),array('users/view','id'=>$post['uid']));?></td>
        <?php }elseif($field=='classify' && $from=='link'){?>
        <td><span class="label label-info"><?php echo Link::exTypes($post[$field]);?></span></td>
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
            <?php echo CHtml::link('编辑',array('update','id'=>$post['id']));?>
            <?php if($from=='books'){?>
            <?php echo CHtml::link('章节',array('chapters','id'=>$post['id']));?>
            <?php }elseif($from=='showcases'){?>
            <?php echo CHtml::link('添加文章',array('showcaseLink/create','sid'=>$post['id']));?>
            <?php }?>
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
            } else {
                echo CHtml::link('新增', array('create'), array('class' => 'btn btn-primary'));
                if (in_array($from, array('packagePower', 'packageDate', 'packageType'))) {
                    echo CHtml::link('排序', array('order'), array('class' => 'btn btn-info'));
                }
                echo CHtml::link('管理', array('admin'), array('class' => 'btn btn-default'));
            }
            ?>
        </div>
    </div>
    <div class="col-xs-10 col-sm-10">
        <?php if(!empty($posts)){$this->renderPartial('/common/pager',array('pages'=>$pages));}?>        
    </div>    
</div>