<?php
/**
 * @filename GroupController.php 
 * @Description
 * @author 阿年飞少 <ph7pal@qq.com> 
 * @link http://www.newsoul.cn 
 * @copyright Copyright©2016 阿年飞少 
 * @datetime 2016-09-02 11:20:35 */
$this->renderPartial('_nav');
?>
<table class="table table-hover table-bordered">
    <tr>
        <th class="text-right">角色</th>
        <?php foreach($groups as $group){?>
        <th colspan="4" class="text-center" style="border-right: 1px solid #CCC"><?php echo $group['title'];?><?php echo CHtml::link('<i class="fa fa-edit"></i>',array('group/update','id'=>$group['id']));?></th>
        <?php }?>
    </tr>
    <tr>
        <td class="text-right"></td>
        <?php foreach($groups as $group){?>
        <td colspan="4" class="text-center" style="border-right: 1px solid #CCC"><img src="<?php echo $group['faceImg'];?>" alt="团队头像" id="user-avatar" style="width: 120px;height: 120px;" class="img-circle"></td>
        <?php }?>
    </tr>
    <tr>
        <td class="text-right">描述</td>
        <?php foreach($groups as $group){?>
        <td colspan="4" style="border-right: 1px solid #CCC"><?php echo $group['desc'];?></td>
        <?php }?>
    </tr>
    <tr>
        <td class="text-right">初始化积分</td>
        <?php foreach($groups as $group){?>
        <td colspan="4" class="text-center" style="border-right: 1px solid #CCC"><?php echo $group['initScore'];?></td>
        <?php }?>
    </tr>
    <tr>
        <td class="text-right">初始化经验</td>
        <?php foreach($groups as $group){?>
        <td colspan="4" class="text-center" style="border-right: 1px solid #CCC"><?php echo $group['initExp'];?></td>
        <?php }?>
    </tr>
    <tr>
        <td class="text-right">数据</td>
        <?php foreach($groups as $group){?>
        <td colspan="4" class="text-center" style="border-right: 1px solid #CCC">任务：<?php echo $group['tasks'];?> / 成员：<?php echo $group['members'];?></td>
        <?php }?>
    </tr>
    <tr class="success">
        <td class="text-right"><b>权限</b></td>
        <?php foreach($groups as $group){?>
        <td class="text-center"><b>数量/天</b></td>
        <td class="text-center"><b>积分/次</b></td>
        <td class="text-center"><b>经验/次</b></td>
        <td style="width: 10px;border-right: 1px solid #CCC"></td>
        <?php }?>
    </tr>
    <?php foreach ($powersType as $type=>$title): ?> 
    <tr>
        <td class="text-right"><?php echo $title;?></td>
        <?php foreach($groups as $group){?>
        <td class="text-center"><?php $_value=$powersArr[$group['id']][$type]['value'];echo $_value;?></td>
        <td class="text-center"><?php $_score=$powersArr[$group['id']][$type]['score'];echo $_score;?></td>
        <td class="text-center"><?php $_exp=$powersArr[$group['id']][$type]['exp'];echo $_exp;?></td>
        <td style="border-right: 1px solid #CCC"><?php echo isset($powersArr[$group['id']][$type]) ? CHtml::link('<i class="fa fa-edit"></i>',array('groupPowers/update','id'=>$powersArr[$group['id']][$type]['id'])) : CHtml::link('<i class="fa fa-edit"></i>',array('groupPowers/create','gid'=>$group['id']));?></td>
        <?php }?>
    </tr>
    
    <?php endforeach; ?>
    <tr>
        <td class="text-right"><?php echo CHtml::link('<i class="fa fa-plus"></i> 新增权限',array('groupPowers/create'));?></td>
        <?php foreach($groups as $group){?>
        <td></td>
        <td></td>
        <td></td>
        <td style="border-right: 1px solid #CCC"></td>
        <?php }?>
    </tr>
    <tr style="border-top: 1px solid #CCC">
        <td class="text-right">管理赠品</td>
        <?php foreach($groups as $group){?>
        <td colspan="4" class="text-center" style="border-right: 1px solid #CCC"><?php echo CHtml::link('管理',array('groupGifts/index','id'=>$group['id']));?></td>
        <?php }?>
    </tr>
</table>
