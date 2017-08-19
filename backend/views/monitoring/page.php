<div class="row">
    <div class="col-sm-12 text-right text-center-xs">
        <?=\yii\widgets\LinkPager::widget([
            'pagination'=>$pages,
            'firstPageLabel' => '首页',
            'lastPageLabel' => '尾页',
            'nextPageLabel' => '下一页',
            'prevPageLabel' => '上一页',
            'options'   =>[
                'class'=>'pagination pagination-sm m-t-none m-b-none',
            ]
        ])?>
    </div>
</div>
