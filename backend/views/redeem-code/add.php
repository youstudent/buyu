<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
use kartik\datetime\DateTimePicker;
?>
<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">发布兑换码</h4>
        </div>
        <div class="modal-body">
            <div class="col-xs-12">
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'payModalForm',
                    'action'=>['redeem-code/add'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <?php echo $form->field($model,'name')?>
                <?php echo $form->field($model,'number')?>
                <?php echo $form->field($model,'end_time')?>
                <?php echo $form->field($model,'give_type',['inline'=>true])->checkboxList(\common\models\RedeemCode::$give)?>
                <?php echo $form->field($model,'type')->dropDownList(\common\models\RedeemCode::$type)?>
                <?php echo $form->field($model,'gold')?>
                <?php echo $form->field($model,'diamond')?>
                <?php echo $form->field($model,'fishGold')?>
                <?php echo $form->field($model,'one')?>
                <?php echo $form->field($model,'tow')?>
                <?php echo $form->field($model,'three')?>
                <?php echo $form->field($model,'four')?>
                <?php echo $form->field($model,'five')?>
                <?php echo $form->field($model,'six')?>
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
    })
</script>
