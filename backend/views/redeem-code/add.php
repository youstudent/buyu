<?php
/**
 * @link http://www.lrdouble.com/
 * @copyright Copyright (c) 2017 Double Software LLC
 * @license http://www.lrdouble.com/license/
 */
use kartik\datetime\DateTimePicker;
use yii\web\View;

?>
<!--<link type="text/css" href="jquery-ui-1.8.16.custom.css"
	rel="stylesheet" />-->
<!-- Modal -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">发布兑换码</h4>
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
                <?php echo $form->field($model,'name')?>
                <?php echo $form->field($model,'number')?>
                <?php echo $form->field($model,'time')->textInput(['id'=>'IDIDID'])?>
                <?php echo $form->field($model,'give_type',['inline'=>true])->checkboxList(\common\models\RedeemCode::$give,['style'=>'margin-left: 113px;'])?>
               
                <?php echo $form->field($model,'type')->dropDownList(\common\models\RedeemCode::$type) ?>
            <?php
            $arr=\common\models\RedeemCode::$give;
            //var_dump($arr);
           /* $arr = [
                ['name'=> 'gold', 'value'=> '', 'label' => '金币'],
                ['name'=> 'diamond', 'value'=> '', 'label' => '砖石'],
                ['name'=> 'fishGold', 'value'=> '', 'label' => '鱼币'],
                ['name'=> 'one', 'value'=> '', 'label' => 'one'],
                ['name'=> 'tow', 'value'=> '', 'label' => 'two'],
            ];*/
            ?>
                <?php /*foreach($arr as $index=> $val): */?><!--
                    <div class="form-group field-redeemcode-<?php /* echo $index */?>" style="display: none">
                        <label class="col-lg-3 control-label" for="redeemcode-<?php /* echo $index */?>"><?php /* echo $index */?></label>
                        <div class="col-lg-9">
                            <input type="text" id="redeemcode-<?php /* echo $index*/?>>" class="form-control" name="RedeemCode[<?php /*echo $index*/?>">
                            <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                --><?php /*endforeach; */?>
<!--                --><?php //echo $form->field($model,'gold')?>
<!--                --><?php //echo $form->field($model,'diamond')?>
<!--                --><?php //echo $form->field($model,'fishGold')?>
<!--                --><?php //echo $form->field($model,'one')?>
<!--                --><?php //echo $form->field($model,'tow')?>
<!--                --><?php //echo $form->field($model,'three')?>
<!--                --><?php //echo $form->field($model,'four')?>
<!--                --><?php //echo $form->field($model,'five')?>
<!--                --><?php //echo $form->field($model,'six')?>
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
        
        $('.chk').on('click', function(){
            var that = this;
            var input =$('.field-redeemcode-'+ that.data('name'));
            $('.chk').val()  ? input.hide() : input.show();
            
        });
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
        var  checkbox_input =  $('#redeemcode-give_type').find('.checkbox-inline');
        checkbox_input.click(function(){
        	var _this = $(this);    
        	var input_text = _this.text();
        	var input_name = 'RedeemCode['+ _this.find('input').val()+']';
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
        });
        
        //时间插件
	     /*$("#time").datetimepicker({
			changeYear : true,
			changeMonth : true,
			showSecond : true,
			timeFormat : 'hh:mm:ss',
			dateFormat : 'yy-mm-dd',
			stepHour : 1,
			stepMinute : 1,
			stepSecond : 1
		});*/
        
    })
    
    
  
</script>
