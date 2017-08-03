<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
?>
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
                    'action'=>['day-task/edit'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <?php echo $form->field($model,'name')->dropDownList(\backend\models\DayForm::$option)?>
               
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
       
       $("#shop-name").change(function(){
         alert(111);
         var thisVal = $(this).val();
         var tempText = $('#shop-name').find('option[value='+ thisVal +']');
         var input_name = 'DayTask[fish_number]['+ thisVal+']';
         alert(thisVal);
         //tempText.remove();
         var html = '';
         html+= '<div class="form-group field-redeemcode-end_time">';
         html+= '<label class="col-lg-3 control-label" for="redeemcode-end_time">'+ tempText.text() + '</label>';
         html+= '<div class="col-lg-9">';
         html+= '<input type="text" id="redeemcode-end_time" class="form-control" name="'+input_name+'>';
         html+= '<span class="help-block m-b-none"></span></div></div>';
         $('#payModalForm').append(html);
         });
         /*$("#payModalForm").onclick(function () {
         alert(1);
         })*/
    })
</script>
