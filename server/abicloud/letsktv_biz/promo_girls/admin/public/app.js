$(document).ready(function(){
    $('#datatable1').dataTable({
        'paging'    : true,
        'ordering'  : true,
        'info'      : false,
        "autoWidth" : false, 
        "searching" : true, 
        oLanguage   : {
            sLengthMenu : '_MENU_ 条每页',
            sInfo       : '当前 _PAGE_ 页，共 _PAGES_ 页',
            sEmptyTable : '没有数据。', 
            oPaginate   : {
              sFirst        : "第一页", 
              sLast         : "最后一页", 
              sPrevious     : "前一页", 
              sNext         : "后一页"
            }
        }
    });
    $('body').on('click', '.m_ajax', function(){
        var a = $(this),
            status = $(this).attr('data-status'),
            btn = $(this).parent().parent().parent().find('.m_style'),
            url = $(this).attr('data-url') + status,
            clas = 'm_style mb-xs btn btn-';
        $.get(url, function(data){
            console.dir(data);
            if(typeof data == 'object') {
                if(data.status == 1) {
                    btn.attr('class', clas + data.data.style).html(data.data.btn);
                    a.attr('data-status', data.data.status).html(data.data.a);
                } else {
                    alert('error 0');
                }
            } else {
                alert('error 1');
            }
        }, 'json');
        return false;
    });
});