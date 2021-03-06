<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
$i=1;

?>
<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">修改VIP管理</h4>
        </div>
        <div class="modal-body">

            <div class="col-xs-11">
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'payModalForm',
                    'action'=>['vip-update/edit'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <input type="hidden" name="id" value="<?=$model->id?>">
                <?php echo $form->field($model,'viplevel')->textInput(['readonly'=>true])?>
                <?php echo $form->field($model,'vipex')->textInput(['readonly'=>\common\helps\players::EditPermission()])?>
                <?php echo $form->field($model,'killrate')->textInput(['readonly'=>\common\helps\players::EditPermission()])?>
                <?php echo $form->field($model,'almsnum')?>
                <?php echo $form->field($model,'almsrate')?>
                <div id="a1">
                <?php echo $form->field($model,'gift',['inline'=>true])->checkboxList(\common\helps\getgift::getGift(),['style'=>'margin-left: 113px;'])?>
                <?php foreach ($data as $k=>$v):?>
                    <div class="form-group field-type-<?php  echo $k ?>" id=<?php echo $k?>>
                        <label class="col-lg-3 control-label" for="type-<?php  echo $k ?>"><?php echo \common\helps\getgift::getGift()[$k] ?></label>
                        <div class="col-lg-9">
                            <input type="text" id="type-<?php echo $k?>>" class="form-control" name="Vipinfo[type][<?php echo $k?>]" value="<?php echo $v?>">
                            <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                <?php endforeach;?>
                </div>
                <div id="a2">
                <?php echo $form->field($model,'gifts',['inline'=>true])->checkboxList(\common\helps\getgift::getGifts(),['style'=>'margin-left: 113px;'])?>
                <?php foreach ($datas as $k=>$v):?>
                    <div class="form-group field-give_upgrade-<?php  echo $k ?>" id=<?php echo $k?>>
                        <label class="col-lg-3 control-label" for="give_upgrade-<?php  echo $k ?>"><?php echo \common\helps\getgift::getGifts()[$k] ?></label>
                        <div class="col-lg-9">
                            <input type="text" id="give_upgrade-<?php echo $k?>>" class="form-control" name="Vipinfo[types][<?php echo $k?>]" value="<?php echo $v?>">
                            <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                <?php endforeach;?>
                </div>
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



        //checkbox选中添加对应输入框
        var  checkbox_input =  $('#vipinfo-gift').find('.checkbox-inline');
        checkbox_input.click(function(){
            var _this = $(this);
            var input_text = _this.text();
            var input_name = 'Vipinfo[type]['+ _this.find('input').val()+']';
            var input_id = _this.find('input').val();
            var html = '';
            if(_this.find('input').is(':checked')){
                $('#'+input_id).remove();
                html+= '<div class="form-group field-type-gold" id="'+input_id+'">';
                html+= '<label class="col-lg-3 control-label" for="'+input_id+'">'+ input_text + '</label>';
                html+= '<div class="col-lg-9">';
                html+= '<input type="text" id="'+input_id+'" class="form-control" name="'+input_name+'">';
                html+= '<span class="help-block m-b-none"></span></div></div>';
                $('#a1').append(html);
            }else{
                $('#'+input_id).remove();
            }
            
        });
        //checkbox选中添加对应输入框
        var  checkbox_input2 =  $('#vipinfo-gifts').find('.checkbox-inline');
        checkbox_input2.click(function(){
            var _this = $(this);
            var input_text = _this.text();
            var input_name = 'Vipinfo[types]['+ _this.find('input').val()+']';
            var input_id = _this.find('input').val();
            var html = '';
            if(_this.find('input').is(':checked')){
                $('#'+input_id).remove();
                html+= '<div class="form-group field-type-gold" id="'+input_id+'">';
                html+= '<label class="col-lg-3 control-label" for="'+input_id+'">'+ input_text + '</label>';
                html+= '<div class="col-lg-9">';
                html+= '<input type="text" id="'+input_id+'" class="form-control" name="'+input_name+'">';
                html+= '<span class="help-block m-b-none"></span></div></div>';
                $('#a2').append(html);
            }else{
                $('#'+input_id).remove();
            }
        })
    })
    
    
    
</script>
