<?php

/*
 * Главная страница приложения.
 */
 
 
?>
<div id="content"></div>

<script type="text/javascript">
     
Ext.application({
    name: '<?=$appName?>', 
    
    /**
     * Хранилище для валют в общем гриде.
    */
    storeGrid: null,
    
    /**
     * Старт приложения.
     */
    launch: function() {
       var self = this;         
        
       self.storeGrid = Ext.create('Ext.data.Store', { 
            fields:['CharCode', 'Name', 'Nominal', 'Value', 'ID'],
            autoLoad:true, 
            proxy: {
                type: 'ajax',
                url: 'index.php?r=api/CurrencyActualList',
                reader: {
                    type: 'json',
                    root: 'items',
                    totalProperty:'total'
                }
            }
        }); 
        
        /**
         * Таблица с валютами.
         */
        var grid = Ext.create('Ext.grid.Panel', {
                    title: self.name, 
                    store: self.storeGrid,
                    listeners: {
                         selectionchange: function(sm, selections) {
                               grid.down('#gridbtnremove').setDisabled(selections.length === 0);                                
                          }
                    },
                    columns: [
                        {header: 'Код',  dataIndex: 'CharCode'},
                        {header: 'Название', dataIndex: 'Name', flex:1},
                        {header: 'Номинал', dataIndex: 'Nominal'},                        
                        {header: 'Курс', dataIndex: 'Value'} 
                    ],
                    dockedItems: [{
                        xtype: 'toolbar',
                        items: [{ 
                            text: 'Добавить валюту', 
                            handler: function(){
                                self.addValute.apply(self, arguments);
                            }  
                        },{ 
                            text: 'Убрать валюту', 
                            id:'gridbtnremove',
                            disabled: true,
                            handler: function(){
                                self.removeValute.apply(self, [grid]);
                            }  
                        },{ 
                            text: 'Обновить текущий список', 
                            handler: function() {
                                self.updateValuties.apply(self, arguments);
                            }
                        }]
                    },{ 
                        xtype: 'pagingtoolbar',
                        store: self.storeGrid,   
                        dock: 'bottom',
                        displayInfo: true
                    }],
                    height: 300,
                    width: 600  
        });
        
        /**
         * Выводим основной контейнер отображения.
         */
        Ext.create('Ext.container.Viewport', {                    
            layout:'border',
            items: [ 
                grid
         ]}); 
    },
    
    /**
    * Обновить текущие валюты.
    */
    updateValuties:function() {
        var self = this;
        self.storeGrid.load({
            params: {update:1}
        });
    },
    
    /**
    * Удаляем валюту
    */
    removeValute:function(grid) {  
         var self = this,
             selection = grid.getView().getSelectionModel().getSelection()[0];
             if(!selection) {
                 return ;
             } 
             Ext.Ajax.request({
                 url: 'index.php?r=api/CurrencyRemove',
                 params:{
                     id:selection.data.ID
                 },
                 success: function(response){
                     var data = Ext.JSON.decode(response.responseText);
                     if(data.result) {                           
                        self.storeGrid.remove(selection);  
                     }                            
                 }
            });
    },
    
    /**
    * Добавить валюту.
    */
    addValute:function() {
        var store,selModel,win,self;
        self = this;
        
        store = Ext.create('Ext.data.Store', { 
            fields:['CharCode', 'Name', 'Nominal', 'Value', 'ID'],
            autoLoad:false, 
            proxy: {
                type: 'ajax',
                url: 'index.php?r=api/CurrencyList',
                reader: {
                    type: 'json',
                    root: 'items',
                    totalProperty:'total'
                }
            }
        });
        
        selModel = Ext.create('Ext.selection.CheckboxModel',{
            listeners: {
                    selectionchange: function(sm, selections) {
                       win.down('#wingridbtnsave').setDisabled(selections.length === 0); 
                    }
           }
        });
        
        win = Ext.create('Ext.window.Window', {
            title: 'Добавить валюту',
            height: 250,
            width: 400,
            layout: 'fit', 
            listeners: {
               afterrender:function() {
                   store.load();
               }  
            },
            items: { 
                xtype: 'grid',
                selModel: selModel,
                
                border: false,
                columns: [{header: 'Код',  dataIndex: 'CharCode'},
                          {header: 'Название', dataIndex: 'Name', flex:1}
                ],       
                store: store  
            },
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'bottom',
                items: [{ 
                   text: 'Сохранить', 
                   id:'wingridbtnsave',
                   disabled: true,
                   handler: function() {
                       var items = selModel.selected.items,
                            params = '';
                       for(var k in items) { 
                           params += 'id[]=' + items[k].data.ID + '&';
                       }
                       Ext.Ajax.request({
                        url: 'index.php?r=api/CurrencyAdd',
                        params:params.substr(0, params.length - 1),
                        success: function(response){
                            var data = Ext.JSON.decode(response.responseText);
                            if(data.result) {
                                self.storeGrid.load();
                                win.close();
                            }                            
                        }
                    });
         
                   }
                } ]
           }]
        }).show();
    }
});

</script>