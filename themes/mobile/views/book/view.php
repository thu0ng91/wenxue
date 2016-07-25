<div class="book-page">
    <h1><?php echo $info['title'];?></h1>
    <ul class="ui-list ui-border-b">    
        <li class="ui-border-t">
            <div class="ui-list-img">
                <img class="lazy w78" src="<?php echo zmf::lazyImg(); ?>" data-original="<?php echo $info['faceImg']; ?>" alt="<?php echo $info['title']; ?>">
            </div>
            <div class="ui-list-info">
                <p>作者：<?php echo CHtml::link($authorInfo['authorName'], array('author/view', 'id' => $info['aid'])); ?></p>
                <p>分类：<?php echo CHtml::link($colInfo['title'],array('book/index','colid'=>$colInfo['id']));?></p>
                <p>收藏：<?php echo $info['favorites']; ?></p>
                <p>点击：<?php echo $info['hits']; ?></p>
                <p>总字：<?php echo $info['words']; ?></p>
                <p>状态：<?php echo Books::exStatus($info['bookStatus']); ?></p>
            </div>
        </li>
    </ul>    
    <div class="module">
        <div class="module-header">初心创文评分</div>
        <div class="module-body padding-body">
            <?php if($info['scorer']>0){?>
            <div class="book-star-info">
                <p class="book-star-num"><?php echo $info['score'];?></p>
                <div class="book-star">
                    <p class="star-color"><?php echo Books::starCss($info['score']);?></p>
                    <p><?php echo CHtml::link($info['scorer'].'人评价','javascript:;',array('action'=>'scroll','action-target'=>'chapter-tips-holder'));?></p>
                </div>
            </div>
            <div class="book-star-detail">
                <?php echo Books::showScoreDetial($info);?>
            </div>
            <?php }else{?>
            <p class="color-grey">暂无评分</p>
            <?php }?>
        </div>
    </div>
    <div class="module">
        <div class="module-header">作品简介</div>
        <div class="module-body padding-body">
            <p><?php echo nl2br($info['content']); ?></p>
        </div>
    </div>
    <div class="module">
        <div class="module-header">目录</div>
        <div class="module-body book-chapters">
            <ul class="ui-list ui-list-text ui-border-t">
            <?php foreach ($chapters as $chapter){?>
                <li class="ui-border-t"><p><?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']),array('class'=>'list-group-item'));?></p></li>
            <?php }?>
            </ul>
        </div>
    </div>
</div>
<?php if(!empty($otherTops)){?>
<div class="module">
    <div class="module-header">其他作品</div>
    <div class="module-body padding-body">
        <?php foreach ($otherTops as $top){?>
        <p><?php echo CHtml::link($top['title'],array('book/view','id'=>$top['id']));?></p>
        <?php }?>
    </div>
</div>
<?php }?>
<div class="module">
    <div class="module-header">书评</div>
    <div class="module-body padding-body book-tips" id="chapter-tips-holder">                
        <?php if(!empty($tips)){?>
        <?php foreach ($tips as $tip){?> 
        <?php $this->renderPartial('/book/_tip',array('data'=>$tip));?>
        <?php }?>
        <?php }else{?>
        <p class="help-block">还没人写过点评，快来抢沙发吧</p>
        <?php }?>
    </div>
</div>