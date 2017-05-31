 

 

 

Vue.component('user-grid', {
    template: '#grid-template',
    props: {
        list: [],
        columns: [],
        filterKey: String,
    },

    components: {
         
    },

    data: function () {
        var sortOrders = {};
        this.columns.forEach(function (column) { 
            sortOrders[column.key] = 1;
        });

        return {
            gdata: [],
            total: 0, 
            sortKey: '',
            sortOrders: sortOrders
        }
    },

    methods: {
        sortBy: function (key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },
        columnActive: function(column) {
            column.active = !column.active;
        }
    },

    events: {
        
    }, 
    
    created: function() {
        this.$http.get('/grid.json?limit=100', function(response){
             
            this.gdata = response.items;
            this.total = response.total;
            
        }.bind(this), function(response){
            console.log(response);
        }.bind(this));
    }
});

// bootstrap the demo
var demo = new Vue({
    el: '#user_grid',

    data: { 
        gridColumns: [
            {key: 'u_id', title: '#', active: true}, 
            {key: 'u_username', title: 'ФИО', active: true},
            {key: 'u_email', title: 'E-mail', active: false},
            {key: 'e_title', title: 'Образование', active: true},
            {key: 'r_title', title: 'Регионы', active: true}]
    }
});