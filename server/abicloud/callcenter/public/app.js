$(document).ready(function(){
    $('#confirmOrder').click(function(){
        if(confirm('确定要更改订单状态吗？')) {
            $.post('index.php?m=order', {id:$(this).attr('data-id'), type:'confirm'}, function(data) {
               if(data.status == 1) {
                   history.back();
               } else {
                   alert(data.error);
               }
            }, 'json');
        }
        return false;
    });
    $('#cancelOrder').click(function(){
        if(confirm('确定要更改订单状态吗？')) {
            $.post('index.php?m=order', {id:$(this).attr('data-id'), type:'cancel'}, function(data) {
               if(data.status == 1) {
                   history.back();
               } else {
                   alert(data.error);
               }
            }, 'json');
        }
        return false;
    });
    $('#jumptouser').change(function(){
        window.location.href = $(this).val();
    });
    if($("#operator_ajax").length > 0) {
        getNewOrder();
        orderby = 'asc';
    }
    $('#datatable1').dataTable({
        'paging':   true,  // Table pagination
        'ordering': true,  // Column ordering 
        'info':     false,  // Bottom left status text
        "order": [[ 0, orderby ]], 
        "autoWidth": false, 
        "searching": false, 
        // Text translation options
        // Note the required keywords between underscores (e.g _MENU_)
        oLanguage: {
            sLengthMenu:  '_MENU_ 条每页',
            sInfo:         '当前 _PAGE_ 页，共 _PAGES_ 页',
            sEmptyTable: '没有数据。', 
            oPaginate: {
              sFirst: "第一页", 
              sLast: "最后一页", 
              sPrevious: "前一页", 
              sNext: "后一页"
            }
        }
    });
    $('body').on('click', '.fa-pencil-square-ooo', function(){
        var order_id = $(this).parent().parent().find('td:first-child').html();
        $.get('index.php?m=orders&a=sjb&ajax=get_detail&order_id=' + order_id, function(data){
            if(typeof data == 'object') {
                if(data.data.status == 1) {
                    $('#myModal #d_mobile, #myModal #d_phone, #myModal #d_starttime, #myModal #d_endtime, #myModal #d_name').prop('readonly', false);
                    $('#myModal #d_mobile').val(data.data.mobile);
                    $('#myModal #d_phone').val(data.data.phone);
                    $('#myModal #d_starttime').val(data.data.starttime);
                    $('#myModal #d_endtime').val(data.data.endtime);
                    $('#myModal #d_mobile, #myModal #d_phone, #myModal #d_starttime, #myModal #d_endtime, #myModal #d_name').prop('readonly', true);
                    
                    $('#myModal #detail').val(data.data.detail).prop('readonly', true);
                    $('#myModal #detail_submit').prop('disabled', true);
                }
                if(data.data.status == 0) {
                    $('#myModal #d_mobile, #myModal #d_phone, #myModal #d_starttime, #myModal #d_endtime, #myModal #d_name').prop('readonly', false);
                    $('#myModal #d_mobile').val(data.data.mobile);
                    $('#myModal #d_phone').val(data.data.phone);
                    $('#myModal #d_starttime').val(data.data.starttime);
                    $('#myModal #d_endtime').val(data.data.endtime);
                    $('#myModal #d_mobile, #myModal #d_phone, #myModal #d_starttime, #myModal #d_endtime, #myModal #d_name').prop('readonly', true);
                    
                    $('#order_id').val(order_id);
                    $('#myModal #detail').prop('readonly', false).val('');
                    $('#myModal #detail_submit').prop('disabled', false);
                }
            }
        }, 'json');
    });
    $('#detail_submit').click(function(){
        var order_id = $('#order_id').val();
        var detail = $('#detail').val();
        $.post('index.php?m=orders&a=sjb&ajax=add_detail&order_id=' + order_id, {'detail': detail}, function(data){
            console.dir(data);
            if(typeof data == 'object') {
                if(data.status == 1) {
                    
                }
                if(data.status == 0) {
                    
                }
                $('#myModal').trigger('click');
                window.location.reload();
            }
        }, 'json');
    });
});
function getNewOrder() {
    $.get('index.php?m=orders&ajax', function(data){
        if(typeof data == 'object') {
            var t = $('#datatable1').DataTable();
            t.clear().draw();
            $.each(data, function(i, item){
                t.row.add( [
                    item.id, 
                    item.time, 
                    item.name, 
                    item.display_name, 
                    item.starttime, 
                    item.lasts, 
                    item.status, 
                    item.link
                ] ).draw( true );
            });
        } else {
            console.dir(0);
        }
    }, 'json');
    setTimeout(function(){
        getNewOrder();
    }, 30000);
}