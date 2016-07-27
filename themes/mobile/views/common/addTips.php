<div class="form-group">
    <?php echo CHtml::textArea('content-'.$type.'-'.$keyid,'',array('class'=>'form-control comment-textarea','action'=>'comment','rows'=>2,'maxLength'=>255,'placeholder'=>'撰写评论（内容不短于20字）'));?>
</div>
<div class="form-group">
    <div class="clearfix">
        <div id="add-tips-score">
            <label class="radio-inline">
                <input type="radio" name="score" value="1"> 很差
            </label>
            <label class="radio-inline">
                <input type="radio" name="score" value="2"> 较差
            </label>
            <label class="radio-inline">
                <input type="radio" name="score" value="3"> 还行
            </label>
            <label class="radio-inline">
                <input type="radio" name="score" value="4" checked="checked"> 推荐
            </label>
            <label class="radio-inline">
                <input type="radio" name="score" value="5"> 力荐
            </label>
        </div>        
    </div>
</div>
<div class="form-group">
    <?php echo CHtml::link('评论','javascript:;',array('class'=>'btn btn-success','action'=>'add-tips','action-data'=>$keyid,'action-type'=>$type));?>
</div>
<div class="clearfix"></div>