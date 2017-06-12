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
            <h4 class="modal-title" id="myModalLabel">修改密码</h4>
        </div>
        <div class="modal-body">

            <div class="col-xs-11">
                <?php $form = \yii\bootstrap\ActiveForm::begin([
                    'id'=>'editPassword',
                    'action'=>['site/edit-pass'],
                    'options'=>['class'=>'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-9\">{input}<span class=\"help-block m-b-none\"></span></div>",
                        'labelOptions'  => ['class'=>'col-lg-3 control-label'],
                    ],
                ])?>
                <div class="form-group field-agency-name">
                    <label class="col-lg-3 control-label" for="agency-name">手机号</label><div class="col-lg-9">
                        <input type="text" id="agency-name" class="form-control" name="" value="<?=$model->phone?>" readonly=""><span class="help-block m-b-none"></span></div>
                </div>

                <?php echo $form->field($model,'used_password')->passwordInput([])?>
                <?php echo $form->field($model,'new_password')->passwordInput([])?>
                <?php echo $form->field($model,'repeat_password')->passwordInput([])?>

                <?php \yii\bootstrap\ActiveForm::end()?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times">&nbsp;</i>取消</button>
            <button type="button" id="editPasswordSubmit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;确认修改</button>
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
        $("#editPasswordSubmit").click(function () {
            var  form   = $("#editPassword");
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
                                confirmButtonText: "关闭",
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
                            confirmButtonText: "关闭",
                            closeOnConfirm: false,
                        })
                    }
                },
            });
        });
    })
</script>
