
<table border="1px" width="100%">
        <tr>
            <th>序号</th>
            <th>房间号</th>
            <th>人数</th>
            <th>倍率</th>
            <th>用户名</th>
            <th>用户ID</th>
            <th>实实金币数量</th>
            <th>实实钻石数量</th>
            <th>实实宝石数量</th>
            <th>命中率</th>
            <th>预警值</th>
            <th>状态</th>
        </tr>
        <tbody id="result"></tbody>
    </table>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.js"></script>
<script type="text/javascript">
        $(function(){

            window.setInterval(function(){
                
                $.getJSON('/monitoring/get',function(rows){
                    var html = '';
                    $(rows).each(function(){
    html+='<tr><td>'+this.id+'</td><td>'+this.room+'</td><td>'+this.num+'</td><td>'+this.bei+'%</td><td>'+this.name+'</td><td>'+this.name_id+'</td><td>'+this.gold+'</td><td>'+this.zuanshi+'</td></tr>';


  //html+='<tr><th>序号</th><th>房间号</th><th>人数</th><th>倍率</th><th>用户名</th><th>用户ID</th><th>实实金币数量</th><th>实实钻石数量</th><th>实实宝石数量</th><th>实实宝石数量</th> <th>命中率</th><th>预警值</th><th>状态</th> </tr>';
                    });

                    $('#result').html(html);
                });
                console.debug(11);
            },1)
            
        });

</script>