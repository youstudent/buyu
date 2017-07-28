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
            <h4 class="modal-title" id="myModalLabel">添加每日签到</h4>
        </div>
        <div class="modal-body">

            <div class="col-xs-11">
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'payModalForm',
                    'action'=>['day/add'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <?php echo $form->field($model,'jewel_num')->dropDownList([1=>'首次',2=>'循环'])?>
                <?php echo $form->field($model,'give_type',['inline'=>true])->radioList(['1'=>'是','0'=>'否'])?>
                <?php echo $form->field($model,'type',['inline'=>true])->checkboxList(\common\models\Day::$give)?>
            <?php \yii\bootstrap\ActiveForm::end()?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i><?=Yii::t('app','but_close')?></button>
            <button type="button" id="payModalSubmit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;<?=Yii::t('app','but_submit_add')?></button>
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
        clickTimeSelect($('#IDIDID'));
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
        var  checkbox_input =  $('#day-type').find('.checkbox-inline');
        checkbox_input.click(function(){
            var _this = $(this);
            var input_text = _this.text();
            var input_name = 'Day['+ _this.find('input').val()+']';
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
