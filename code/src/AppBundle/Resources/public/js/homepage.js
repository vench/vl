Ext.onReady(function () {
    Ext.QuickTips.init();

    var pageSize = 10;
    var store = new Ext.data.JsonStore({
        root: 'items',
        totalProperty: 'total',
        idProperty: 'threadid',
        remoteSort: true,
        fields: [
            'u_id', 'u_username', 'u_email', 'e_title', 'r_title'
        ],
        proxy: new Ext.data.ScriptTagProxy({
            url: '/grid.json'
        })
    });
    store.setDefaultSort('u_id', 'desc');
 
    var grid = new Ext.grid.GridPanel({
        store: store,
        columns: [
            {
                header: '#',
                width: 40,
                sortable: true,
                dataIndex: 'u_id'
            },
            {
                id: 'username',
                header: 'ФИО',
                width: 'flex',
                sortable: true,
                dataIndex: 'u_username'
            },
            {
                header: 'E-mail',
                width: 120,
                sortable: true,
                dataIndex: 'u_email',
                hidden: false
            },
            {
                header: 'Образование',
                width: 120,
                sortable: true,
                dataIndex: 'e_title'
            },
            {
                id: 'regions',
                header: 'Регионы',
                width: 180,
                sortable: true,
                dataIndex: 'r_title'
            }
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: pageSize,
            store: store,
            displayInfo: true,
            displayMsg: 'Показано записей {0} - {1} из {2}',
            emptyMsg: "Нет записей"
        }),
        stripeRows: true,
        autoExpandColumn: 'username',
        height: '400',
        width: '100%',
        title: 'Список пользователей',
        stateful: true,
        stateId: 'grid'
    });


    grid.render('user-grid');
    store.load({params: {start: 0, limit: pageSize}});
}); 