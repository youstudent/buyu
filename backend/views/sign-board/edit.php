<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */?>
<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">修改捕鱼任务奖励</h4>
        </div>
        <div class="modal-body">

            <div class="col-xs-11">
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'payModalForm',
                    'action'=>['sign-board/edit'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <input type="hidden" name="id" value="<?=$model->id?>">
                <?php echo $form->field($model,'type')->dropDownList(\common\helps\fish::$fishType)?>
                <div>
                    <div  id="type1" style="display: <?php echo $model->type!==1?'none':''?>">
                        <?php echo $form->field($model,'fishid')->dropDownList(\common\helps\players::getFishingss(1),['name'=>$model->type!==1?'':'Fishtask[fishid]'])?>
                    </div>
                    <div style="display: <?php echo $model->type!==2?'none':''?>" id="type2">
                        <?php echo $form->field($model,'fishid')->dropDownList(\common\helps\players::getFishingss(2),['name'=>$model->type!==2?'':'Fishtask[fishid]'])?>
                    </div>
                    <div style="display:<?php echo $model->type!==3?'none':''?>" id="type3">
                        <?php echo $form->field($model,'fishid')->dropDownList(\common\helps\players::getFishingss(3),['name'=>$model->type!==3?'':'Fishtask[fishid]'])?>
                    </div>
                    <div style="display:<?php echo $model->type!==4?'none':''?>" id="type4">
                        <?php echo $form->field($model,'fishid')->dropDownList(\common\helps\players::getFishingss(4),['name'=>$model->type!==4?'':'Fishtask[fishid]'])?>
                    </div>
                    <div style="display: <?php echo $model->type!==5?'none':''?>" id="type5">
                        <?php echo $form->field($model,'fishid')->dropDownList(\common\helps\players::getFishingss(5),['name'=>$model->type!==5?'':'Fishtask[fishid]'])?>
                    </div>
                </div>
    
                <?php echo $form->field($model,'from')->dropDownList(\common\helps\fish::$fishType)?>
                <div>
                    <div id="from1" style="display: <?php echo $model->from!==1?'none':''?>">
                        <?php echo $form->field($model,'fromfish')->dropDownList(\common\helps\players::getFishingss(1),['name'=>$model->from!==1?'':'Fishtask[fromfish]'])?>
                    </div>
                    <div id="from2" style="display: <?php echo $model->from!==2?'none':''?>">
                        <?php echo $form->field($model,'fromfish')->dropDownList(\common\helps\players::getFishingss(2),['name'=>$model->from!==2?'':'Fishtask[fromfish]'])?>
                    </div>
                    <div id="from3" style="display: <?php echo $model->from!==3?'none':''?>">
                        <?php echo $form->field($model,'fromfish')->dropDownList(\common\helps\players::getFishingss(3),['name'=>$model->from!==3?'1':'Fishtask[fromfish]'])?>
                    </div>
                    <div id="from4" style="display: <?php echo $model->from!==4?'none':''?>">
                        <?php echo $form->field($model,'fromfish')->dropDownList(\common\helps\players::getFishingss(4),['name'=>$model->from!==4?'':'Fishtask[fromfish]'])?>
                    </div>
                    <div id="from5" style="display: <?php echo $model->from!==5?'none':''?>">
                        <?php echo $form->field($model,'fromfish')->dropDownList(\common\helps\players::getFishingss(5),['name'=>$model->from!==5?'':'Fishtask[fromfish]'])?>
                    </div>
                </div>
                <?php echo $form->field($model,'fishnum')?>
                <?php echo $form->field($model,'rate')?>
                <?php echo $form->field($model,'gift',['inline'=>true])->checkboxList(\common\helps\getgift::getGift(),['style'=>'margin-left: 113px;'])?>
                <?php foreach ($data as $k=>$v):?>
                    <div class="form-group field-notice-<?php  echo $k ?>" id=<?php echo $k?>>
                        <label class="col-lg-3 control-label" for="notice-<?php  echo $k ?>"><?php echo \common\helps\getgift::getGift()[$k] ?></label>
                        <div class="col-lg-9">
                            <input type="text" id="notice-<?php echo $k?>>" class="form-control" name="Fishtask[types][<?php echo $k?>]" value="<?php echo $v?>">
                            <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                <?php endforeach;?>
            <?php \yii\bootstrap\ActiveForm::end()?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i><?=Yii::t('app','but_close')?></button>
            <button type="button" id="payModalSubmit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;确认修改</button>
        </div>
    </div>
</div>
<style>
    .modal-body{
        width: 100%;
        display: inline-block;
        padding-bottom: 0px;
    }
</style>
<script>
    $(document).ready(function () {
        //平台用户充值
        $("#payModalSubmit").click(function () {
            var  form   = $("#payModalForm");
            var  action = form.attr('action');
            var  data   = form.serialize();
            $.ajax({
                url:action,
                type:'POST',
                data:data,
                success:function (res) {
                    console.log(res);
                    if(res.code == 1)
                    {
                        swal({
                            title:res.message,
                            //text: "<?=Yii::t('app','swal_text_error')?>",
                            type: "success",
                            confirmButtonText: "<?=Yii::t('app','but_close')?>",
                            closeOnConfirm: false,
                        },
                        function(){
                            location.reload();
                        });
                    }else{
                        swal({
                            title:res.message,
                            //text: "<?=Yii::t('app','swal_text_error')?>",
                            type: "error",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "<?=Yii::t('app','but_close_ret')?>",
                            closeOnConfirm: false,
                        })
                    }
                },
            });
        });



        $("#fishtask-type").change(function(){
            var thisVal = $(this).val();
            var tempText = $('#fishtask-type').find('option[value='+ thisVal +']');

            $("#type" + thisVal).attr("style","").siblings().attr('style', 'display: none');

            $("#type" + thisVal).find('select').attr("name","Fishtask[fishid]");
            $("#type" + thisVal).siblings().find('select').attr('name', '')
        });
        
        $("#fishtask-from").change(function(){
            var thisVal = $(this).val();
            console.log(thisVal);
            var tempText = $('#fishtask-from').find('option[value='+ thisVal +']');

            $("#from" + thisVal).attr("style","").siblings().attr('style', 'display: none');

            $("#from" + thisVal).find('select').attr("name","Fishtask[fromfish]");
            $("#from" + thisVal).siblings().find('select').attr('name', '')
        });

        //checkbox选中添加对应输入框
        var  checkbox_input =  $('#fishtask-gift').find('.checkbox-inline');
        checkbox_input.click(function(){
            var _this = $(this);
            var input_text = _this.text();
            var input_name = 'Fishtask[types]['+ _this.find('input').val()+']';
            var input_id = _this.find('input').val();
            var html = '';
            if(_this.find('input').is(':checked')){
                $('#'+input_id).remove();
                html+= '<div class="form-group field-redeemcode-end_time" id="'+input_id+'">';
                html+= '<label class="col-lg-3 control-label" for="redeemcode-end_time">'+ input_text + '</label>';
                html+= '<div class="col-lg-9">';
                html+= '<input type="text" id="redeemcode-end_time" class="form-control" name="'+input_name+'">';
                html+= '<span class="help-block m-b-none"></span></div></div>';
                $('#payModalForm').append(html);
            }else{
                $('#'+input_id).remove();
            }
        })
    })
</script>
