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
            <h4 class="modal-title" id="myModalLabel">查看奖品</h4>
        </div>
        <div class="modal-body">

            <div class="col-xs-11">
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'payModalForm',
                    'action'=>['redeem-code/add'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <?php echo $form->field($model,'from_fishing')->dropDownList(\common\models\SignBoard::$fishing,['multiple'=>true])?>
                <?php foreach ($data as $key=>$value):?>
                    <label class="col-lg-3 control-label" for="redeemcode-diamond"><?php echo $key?></label>
                    <div class="col-lg-9"><input type="text" id="redeemcode-diamond" class="form-control" name="RedeemCode[diamond]" value="<?php echo $value?>" readonly=""><span class="help-block m-b-none"></span></div>
                <?php endforeach;?>
                
               <!-- <?php /*echo $form->field($model,'give_type')->checkboxList(\common\models\RedeemCode::$give)*/?>
                <?php /*echo $form->field($model,'gold')->textInput(['readonly'=>true])*/?>
                <?php /*echo $form->field($model,'diamond')->textInput(['readonly'=>true])*/?>
                <?php /*echo $form->field($model,'fishGold')->textInput(['readonly'=>true])*/?>
                <?php /*echo $form->field($model,'one')->textInput(['readonly'=>true])*/?>
                <?php /*echo $form->field($model,'tow')->textInput(['readonly'=>true])*/?>
                <?php /*echo $form->field($model,'three')->textInput(['readonly'=>true])*/?>
                <?php /*echo $form->field($model,'four')->textInput(['readonly'=>true])*/?>
                <?php /*echo $form->field($model,'five')->textInput(['readonly'=>true])*/?>
                --><?php /*echo $form->field($model,'six')->textInput(['readonly'=>true])*/?>
            <?php \yii\bootstrap\ActiveForm::end()?>
            </div>
        </div>
        <div class="modal-footer">
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
