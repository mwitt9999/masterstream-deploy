$('#deployments-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: '/history/all',
    columns: [
        {data: 'version', name: 'version'},
        {data: 'sha', name: 'sha'},
        {data: 'type', name: 'type'},
        {data: 'user', name: 'user'},
        {data: 'created_at', name: 'created_at'},
    ]
});

$('#servers-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: '/history/all',
    columns: [
        {data: 'name', name: 'name'},
        {data: 'ip', name: 'ip'},
        {data: 'created_at', name: 'created_at'},
    ]
});

//Datatables debugger
// (function() {
//     var url = '//debug.datatables.net/bookmarklet/DT_Debug.js';
//     if (typeof DT_Debug != 'undefined') {
//         if (DT_Debug.instance !== null) {
//             DT_Debug.close();
//         } else {
//             new DT_Debug();
//         }
//     } else {
//         var n = document.createElement('script');
//         n.setAttribute('language', 'JavaScript');
//         n.setAttribute('src', url + '?rand=' + new Date().getTime());
//         document.body.appendChild(n);
//     }
// })();
