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
            <h4 class="modal-title" id="myModalLabel">修改机器人</h4>
        </div>
        <div class="modal-body">

            <div class="col-xs-11">
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'payModalForm',
                    'action'=>['robot/edit'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <input type="hidden" name="id" value="<?=$model->id?>">
                <?php echo $form->field($model,'name')?>
                <?php echo $form->field($model,'gold')?>
                <?php echo $form->field($model,'diamond')?>
                <?php echo $form->field($model,'fish_gold')?>
                <?php echo $form->field($model,'robot_win_rate')?>
                <?php echo $form->field($model,'vip_grade')->dropDownList(\common\models\Robot::GetVip())?>
                <?php echo $form->field($model,'experience_grade')->dropDownList(\common\models\Robot::GetEx())?>
                <?php echo $form->field($model,'status')->dropDownList(\common\models\Robot::$status_option)?>
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
        //clickTimeSelect($('#IDIDID'));
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
                }
            });
        });
    })
</script>

