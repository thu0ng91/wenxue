<div class="container">
    <div class="main-part">
        <div class="module book-info">
            <h1><?php echo $info['title']; ?></h1>
            <div class="media">
                <div class="media-left">
                    <a href="<?php echo Yii::app()->createUrl('book/view',array('id'=>$info['id']));?>">
                        <img class="media-object lazy w78" src="<?php echo zmf::lazyImg(); ?>" data-original="<?php echo $info['faceImg']; ?>" alt="<?php echo $info['title']; ?>"> 
                    </a>
                </div>
                <div class="media-body book-detail">
                    <p>作者：<?php echo CHtml::link($authorInfo['authorName'], array('author/view', 'id' => $info['aid'])); ?></p>
                    <p>分类：<?php echo CHtml::link($colInfo['title'],array('book/index','colid'=>$colInfo['id']),array('target'=>'_blank'));?></p>
                    <p>收藏：<?php echo $info['favorites']; ?></p>
                    <p>点击：<?php echo $info['hits']; ?></p>
                    <p>总字：<?php echo $info['words']; ?></p>
                    <p>状态：<?php echo Books::exStatus($info['bookStatus']); ?></p>
                    <p>
                        <?php echo CHtml::link('立即阅读',array('book/chapter','cid'=>$chapters[0]['id']),array('class'=>'btn btn-danger btn-xs'));?>
                        <?php if($this->favorited){?>
                        <?php echo GroupPowers::link('favoriteBook',$this->userInfo,'<i class="fa fa-heart"></i> 已收藏','javascript:;',array('class'=>'btn btn-default btn-xs','action'=>'favorite','action-data'=>$info['id'],'action-type'=>'book'));?>
                        <?php }else{?>
                        <?php echo GroupPowers::link('favoriteBook',$this->userInfo,'<i class="fa fa-heart-o"></i> 收藏','javascript:;',array('class'=>'btn btn-danger btn-xs','action'=>'favorite','action-data'=>$info['id'],'action-type'=>'book'));?>
                        <?php }?>
                        <?php echo $info['bookStatus']!=Books::STATUS_FINISHED ? GroupPowers::link('dapipi',$this->userInfo,'<i class="fa fa-rocket"></i> 催更','javascript:;',array('class'=>'btn btn-success btn-xs','action'=>'dapipi','action-data'=>$info['id'])) : '';?>
                    </p>
                    <p class="color-grey" style="margin-top:10px"><?php echo CHtml::link('<i class="fa fa-exclamation-triangle"></i> 举报','javascript:;',array('action'=>'report','action-type'=>'book','action-id'=>$info['id'],'action-title'=>$info['title']));?></p>
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
            </div>
            <?php if(!empty($myActivity)){?>
            <div class="module-header">参与活动</div>
            <div class="module-body padding-body">
                <?php foreach ($myActivity as $ac){?>
                <p><?php echo CHtml::link($ac['title'],array('activity/view','id'=>$ac['id']),array('target'=>'_blank','class'=>'color-warning'));?></p>
                <?php }?>
            </div>
            <?php }?>
            <div class="module-header">内容简介</div>
            <div class="module-body">
                <p><?php echo nl2br($info['content']); ?></p>
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
        <?php if(!empty($props)){?>
        <div class="module" id="props-holder">
            <div class="module-header">赞赏榜</div>
            <div class="module-body">
                <?php $this->renderPartial('/common/props',array('props'=>$props,'keyid'=>$info['id']));?>
            </div>
        </div>
        <?php }?>
        <div class="module">
            <div class="module-header">书评</div>
            <div class="module-body" id="chapter-tips-holder">                
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
    <div class="aside-part">
        <div class="module">
            <div class="module-header">关于作者</div>
            <div class="module-body">
                <div class="media">
                    <div class="media-left">
                        <a href="<?php echo Yii::app()->createUrl('author/view',array('id'=>$authorInfo['id']));?>">
                            <img class="media-object lazy w78" src="<?php echo zmf::lazyImg(); ?>" data-original="<?php echo $authorInfo['avatar']; ?>" alt="<?php echo $authorInfo['authorName']; ?>">
                        </a>                        
                    </div>
                    <div class="media-body">
                        <p class="title ui-nowrap"><?php echo CHtml::link($authorInfo['authorName'], array('author/view', 'id' => $authorInfo['id'])); ?></p>
                        <p class="color-grey"><?php echo $authorInfo['content'];?></p>
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
        <?php if(!empty($otherBooks)){?>
        <div class="module">
            <div class="module-header">其他「<?php echo $colInfo['title'];?>」作品</div>
            <div class="module-body">
                <?php foreach ($otherBooks as $top){?>
                <p><?php echo CHtml::link($top['title'],array('book/view','id'=>$top['id']));?></p>
                <?php }?>
            </div>
        </div>
        <?php }?>
        <div class="module">
            <div class="module-header">手机上阅读</div>
            <div class="module-body">
                <img class="media-object lazy" style="width: 258px;height: 258px;opacity: .6" src="<?php echo zmf::lazyImg(); ?>" data-original="<?php echo $qrcode; ?>" alt="<?php echo $info['title']; ?>的二维码" title="<?php echo $info['title']; ?>">
            </div>
        </div>
    </div>
</div>