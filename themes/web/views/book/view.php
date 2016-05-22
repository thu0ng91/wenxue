<div class="container">
    <div class="main-part">
        <div class="module book-info">
            <h1><?php echo $info['title']; ?></h1>
            <div class="media">
                <div class="media-left">
                    <img class="media-object" src="<?php echo $info['faceImg']; ?>" alt="<?php echo $info['title']; ?>">                    
                </div>
                <div class="media-body book-detail">
                    <p>作者：<?php echo CHtml::link($authorInfo['authorName'], array('author/view', 'id' => $info['aid'])); ?></p>
                    <p>分类：<?php echo $info['title']; ?></p>
                    <p>收藏：<?php echo $info['favorites']; ?></p>
                    <p>点击：<?php echo $info['hits']; ?></p>
                    <p>总字：<?php echo $info['words']; ?></p>
                    <p>状态：<?php echo $info['bookStatus']; ?></p>
                    <div class="btn-group" role="group">
                        <?php echo CHtml::link('立即阅读',array('book/chapter','cid'=>''),array('class'=>'btn btn-default btn-xs'));?>
                        <?php echo CHtml::link('收藏','javascript:;',array('class'=>'btn btn-'.($this->favorited ? 'danger' :'default').' btn-xs','action'=>'favorite','action-data'=>$info['id'],'action-type'=>'book'));?>
                        <button type="button" class="btn btn-default btn-xs">分享</button>
                    </div>
                </div>
                <div class="media-right">
                    <div class="book-starInfo">
                        <p>初心创文评分</p>
                        <?php if($info['scorer']>0){?>
                        <div class="media">
                            <div class="media-left">
                                <p class="book-star-num"><?php echo $info['score'];?></p>
                            </div>
                            <div class="media-body">
                                <p><?php echo Books::starCss($info['score']);?></p>
                                <p><?php echo $info['scorer'];?>人评价</p>                                
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
            </div>
            <div class="module-header">内容简介</div>
            <div class="module-body">
                <p><?php echo $info['content']; ?></p>
            </div>  
            <div class="module-header">目录</div>
            <div class="module-body book-chapters">
                <div class="list-group">
                <?php foreach ($chapters as $chapter){?>
                <?php echo CHtml::link($chapter['title'],array('book/chapter','cid'=>$chapter['id']),array('class'=>'list-group-item'));?>
                <?php }?>
                </div>
            </div>
        </div>
        <div class="module">
            <div class="module-header">点评</div>
            <div class="module-body">
                <div id="more-content">
                    <?php if(!empty($tips)){?>
                    <?php foreach ($tips as $tip){?> 
                    <?php $this->renderPartial('/book/_tip',array('data'=>$tip));?>
                    <?php }?>
                    <?php }else{?>
                    <p class="help-block">还没人写过点评，快来抢沙发吧</p>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="aside-part">
        <div class="module">
            <div class="module-header">关于作者</div>
            <div class="module-body">
                <div class="media">
                    <div class="media-left">
                        <img class="media-object" src="<?php echo $authorInfo['avatar']; ?>" alt="<?php echo $authorInfo['authorName']; ?>">                    
                    </div>
                    <div class="media-body">
                        <p><?php echo CHtml::link($authorInfo['authorName'], array('author/view', 'id' => $authorInfo['id'])); ?></p>
                        <p><?php echo $authorInfo['content'];?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php if(!empty($otherTops)){?>
        <div class="module">
            <div class="module-header">他的其他作品</div>
            <div class="module-body">
                <?php foreach ($otherTops as $top){?>
                <p><?php echo CHtml::link($top['title'],array('book/view','id'=>$top['id']));?></p>
                <?php }?>
            </div>
        </div>
        <?php }?>
    </div>
</div>