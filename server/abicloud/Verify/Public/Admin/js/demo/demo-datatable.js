// Demo datatables
// ----------------------------------- 


(function(window, document, $, undefined){

  $(function(){

    //
    // Zero configuration
    // 
    //  var dataUrl = "/wechatshangjia/Admin/Xktv/lists_ajax";
      var xktvtable =  $('#Xktvlist').dataTable(
          {
              "processing": true,
              "serverSide": true,
              "ajax":{"url":"/wechatshangjia/Admin/Xktv/lists_ajax","type":"POST"},
              //"ajax": "scripts/server_processing.php"
              oLanguage: {
                  sSearch:      '搜索所有内容:',
                  sLengthMenu:  '_MENU_ records per page',
                  info:         'Showing page _PAGE_ of _PAGES_',
                  zeroRecords:  'Nothing found - sorry',
                  infoEmpty:    'No records available',
                  infoFiltered: '(filtered from _MAX_ total records)'
              }
          }
      );
      var gifttable =  $('#gifttable').dataTable(
          {
              "processing": true,
              "serverSide": true,
              "ajax":{"url":"/wechatshangjia/Admin/Gift/lists_ajax","type":"POST"},
              //"ajax": "scripts/server_processing.php"
              oLanguage: {
                  sSearch:      '搜索所有内容:',
                  sLengthMenu:  '_MENU_ records per page',
                  info:         'Showing page _PAGE_ of _PAGES_',
                  zeroRecords:  'Nothing found - sorry',
                  infoEmpty:    'No records available',
                  infoFiltered: '(filtered from _MAX_ total records)'
              }
          }
      );

      var giftordertable = $('#giftordertable').dataTable(
          {
              "processing": true,
              "serverSide": true,
              "ajax":{"url":"/wechatshangjia/Admin/GiftOrder/lists_ajax","type":"POST"},
              //"ajax": "scripts/server_processing.php"
              oLanguage: {
                  sSearch:      '搜索所有内容:',
                  sLengthMenu:  '_MENU_ records per page',
                  info:         'Showing page _PAGE_ of _PAGES_',
                  zeroRecords:  'Nothing found - sorry',
                  infoEmpty:    'No records available',
                  infoFiltered: '(filtered from _MAX_ total records)'
              }
          }
      );

      var ktvordertable = $('#ktvordertable').dataTable(
          {
              "processing": true,
              "serverSide": true,
              "ajax":{"url":"/wechatshangjia/Admin/KtvOrder/lists_ajax","type":"POST"},
              //"ajax": "scripts/server_processing.php"
              oLanguage: {
                  sSearch:      '搜索所有内容:',
                  sLengthMenu:  '_MENU_ records per page',
                  info:         'Showing page _PAGE_ of _PAGES_',
                  zeroRecords:  'Nothing found - sorry',
                  infoEmpty:    'No records available',
                  infoFiltered: '(filtered from _MAX_ total records)'
              }
          }
      );

      var PlatformUsertable = $('#PlatformUsertable').dataTable(
          {
              "processing": true,
              "serverSide": true,
              "ajax":{"url":"/wechatshangjia/Admin/PlatformUser/lists_ajax","type":"POST"},
              //"ajax": "scripts/server_processing.php"
              oLanguage: {
                  sSearch:      '搜索所有内容:',
                  sLengthMenu:  '_MENU_ records per page',
                  info:         'Showing page _PAGE_ of _PAGES_',
                  zeroRecords:  'Nothing found - sorry',
                  infoEmpty:    'No records available',
                  infoFiltered: '(filtered from _MAX_ total records)'
              }
          }
      );
      //$('#Xktvlist tbody')
      //    .on( 'mouseover', 'td', function () {
      //        var colIdx = table.cell(this).index().column;
      //
      //        if ( colIdx !== lastIdx ) {
      //            $( table.cells().nodes() ).removeClass( 'highlight' );
      //            $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
      //        }
      //    } )
      //    .on( 'mouseleave', function () {
      //        $( table.cells().nodes() ).removeClass( 'highlight' );
      //    } );

    $('#datatable1').dataTable({
        'paging':   true,  // Table pagination
        'ordering': true,  // Column ordering 
        'info':     true,  // Bottom left status text
        // Text translation options
        // Note the required keywords between underscores (e.g _MENU_)
        oLanguage: {
            sSearch:      'Search all columns:',
            sLengthMenu:  '_MENU_ records per page',
            info:         'Showing page _PAGE_ of _PAGES_',
            zeroRecords:  'Nothing found - sorry',
            infoEmpty:    'No records available',
            infoFiltered: '(filtered from _MAX_ total records)'
        }
    });


    // 
    // Filtering by Columns
    // 

    var dtInstance2 = $('#datatable2').dataTable({
        'paging':   true,  // Table pagination
        'ordering': true,  // Column ordering 
        'info':     true,  // Bottom left status text
        // Text translation options
        // Note the required keywords between underscores (e.g _MENU_)
        oLanguage: {
            sSearch:      'Search all columns:',
            sLengthMenu:  '_MENU_ records per page',
            info:         'Showing page _PAGE_ of _PAGES_',
            zeroRecords:  'Nothing found - sorry',
            infoEmpty:    'No records available',
            infoFiltered: '(filtered from _MAX_ total records)'
        }
    });
    var inputSearchClass = 'datatable_input_col_search';
    var columnInputs = $('tfoot .'+inputSearchClass);

    // On input keyup trigger filtering
    columnInputs
      .keyup(function () {
          dtInstance2.fnFilter(this.value, columnInputs.index(this));
      });


    // 
    // Column Visibilty Extension
    // 

    $('#datatable3').dataTable({
        'paging':   true,  // Table pagination
        'ordering': true,  // Column ordering 
        'info':     true,  // Bottom left status text
        // Text translation options
        // Note the required keywords between underscores (e.g _MENU_)
        oLanguage: {
            sSearch:      'Search all columns:',
            sLengthMenu:  '_MENU_ records per page',
            info:         'Showing page _PAGE_ of _PAGES_',
            zeroRecords:  'Nothing found - sorry',
            infoEmpty:    'No records available',
            infoFiltered: '(filtered from _MAX_ total records)'
        },
        // set columns options
        'aoColumns': [
            {'bVisible':false},
            {'bVisible':true},
            {'bVisible':true},
            {'bVisible':true},
            {'bVisible':true}
        ],
        sDom:      'C<"clear">lfrtip',
        colVis: {
            order: 'alfa',
            'buttonText': 'Show/Hide Columns'
        }
    });

    // 
    // AJAX
    // 

    $('#datatable4').dataTable({
        'paging':   true,  // Table pagination
        'ordering': true,  // Column ordering 
        'info':     true,  // Bottom left status text
        sAjaxSource: '../server/datatable.json',
        aoColumns: [
          { mData: 'engine' },
          { mData: 'browser' },
          { mData: 'platform' },
          { mData: 'version' },
          { mData: 'grade' }
        ]
    });
  });

})(window, document, window.jQuery);
