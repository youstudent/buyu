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
            <h4 class="modal-title" id="myModalLabel">添加鱼任务</h4>
        </div>
        <div class="modal-body">

            <div class="col-xs-11">
            <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'payModalForm',
                    'action'=>['sign-board/add'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <?php echo $form->field($model,'type')->dropDownList(\backend\models\Redpacket::$option)?>
                <div>
                    <div  id="type1">
                        <?php echo $form->field($model,'fishing_id')->dropDownList(\common\helps\players::getFishing(1))?>
                    </div>
                    <div style="display: none" id="type2">
                        <?php echo $form->field($model,'fishing_id')->dropDownList(\common\helps\players::getFishing(2),['name'=>''])?>
                    </div>
                    <div style="display: none" id="type3">
                        <?php echo $form->field($model,'fishing_id')->dropDownList(\common\helps\players::getFishing(3),['name'=>''])?>
                    </div>
                    <div style="display: none" id="type4">
                        <?php echo $form->field($model,'fishing_id')->dropDownList(\common\helps\players::getFishing(4),['name'=>''])?>
                    </div>
                    <div style="display: none" id="type5">
                        <?php echo $form->field($model,'fishing_id')->dropDownList(\common\helps\players::getFishing(5),['name'=>''])?>
                    </div>
                </div>
    
                <?php echo $form->field($model,'from')->dropDownList(\backend\models\Redpacket::$option)?>
                <div>
               
                <div id="from1">
                    <?php echo $form->field($model,'from_fishing[]')->dropDownList(\common\helps\players::getFishing(1),['multiple'=>true])?>
                </div>
                <div id="from2" style="display: none">
                    <?php echo $form->field($model,'from_fishing[]')->dropDownList(\common\helps\players::getFishing(2),['multiple'=>true,'name'=>''])?>
                </div>
                <div id="from3" style="display: none">
                    <?php echo $form->field($model,'from_fishing[]')->dropDownList(\common\helps\players::getFishing(3),['multiple'=>true,'name'=>''])?>
                </div>
                <div id="from4" style="display: none">
                    <?php echo $form->field($model,'from_fishing[]')->dropDownList(\common\helps\players::getFishing(4),['multiple'=>true,'name'=>''])?>
                </div>
                <div id="from5" style="display: none">
                    <?php echo $form->field($model,'from_fishing[]')->dropDownList(\common\helps\players::getFishing(5),['multiple'=>true,'name'=>''])?>
                </div>
                </div>
                
                <?php echo $form->field($model,'number')?>
                <?php echo $form->field($model,'probability')?>
                <?php echo $form->field($model,'give_number',['inline'=>true])->checkboxList(\common\models\SignBoard::$give,['style'=>'margin-left: 113px;'])?>
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

        $("#signboard-type").change(function(){
            var thisVal = $(this).val();
            var tempText = $('#signboard-type').find('option[value='+ thisVal +']');

            $("#type" + thisVal).attr("style","").siblings().attr('style', 'display: none');

            $("#type" + thisVal).find('select').attr("name","SignBoard[fishing_id]");
            $("#type" + thisVal).siblings().find('select').attr('name', '')
        });


        $("#signboard-from").change(function(){
            var thisVal = $(this).val();
            console.log(thisVal)
            var tempText = $('#signboard-from').find('option[value='+ thisVal +']');

            $("#from" + thisVal).attr("style","").siblings().attr('style', 'display: none');

            $("#from" + thisVal).find('select').attr("name","SignBoard[from_fishing][]");
            $("#from" + thisVal).siblings().find('select').attr('name', '')
        });
        
        
        //checkbox选中添加对应输入框
        var  checkbox_input =  $('#signboard-give_number').find('.checkbox-inline');
        checkbox_input.click(function(){
            var _this = $(this);
            var input_text = _this.text();
            var input_name = 'SignBoard['+ _this.find('input').val()+']';
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
        /*$("#signboard-number").change(function(){
            var thisVal = $(this).val()
            var tempText = $('#signboard-number').find('option[value='+ thisVal +']');
            tempText.remove();
            var html = '';
            html+= '<div class="form-group field-redeemcode-end_time">';
            html+= '<label class="col-lg-3 control-label" for="redeemcode-end_time">'+ tempText.text() + '</label>';
            html+= '<div class="col-lg-9">';
            html+= '<input type="text" id="redeemcode-end_time" class="form-control">';
            html+= '<span class="help-block m-b-none"><a href="javascript:;" id="a">点我</a></span></div></div>';
            $('#payModalForm').append(html);
        });
        $("#a").onclick(function () {
            alert(1);
        })*/
        
     


//        checkbox_input.click(function(){
//            var _this = $(this);
//            var input_text = _this.text();
//            var input_name = 'Notice['+ _this.find('input').val()+']';
//            var input_id = _this.find('input').val();
//            var html = '';
//            if(_this.find('input').is(':checked')){
//                $('#'+input_id).remove();
//                html+= '<div class="form-group field-redeemcode-end_time" id="'+input_id+'">';
//                html+= '<label class="col-lg-3 control-label" for="redeemcode-end_time">'+ input_text + '</label>';
//                html+= '<div class="col-lg-9">';
//                html+= '<input type="text" id="redeemcode-end_time" class="form-control" name="'+input_name+'">';
//                html+= '<span class="help-block m-b-none"></span></div></div>';
//                $('#payModalForm').append(html);
//            }else{
//                $('#'+input_id).remove();
//            }
//        })
    })
</script>
