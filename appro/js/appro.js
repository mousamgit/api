const myapp = Vue.createApp({
    data() {
        return {

            items: [],  
            itemarray: [],
            itemindex: 0,
            itemtotal:0,
            itemtitle: '',
            itemvalue: '',
            itemtype: '',
        };
    },
    methods: {

        additem() {
            // Create a new app instance for the rowfilter component
            const itemApp = Vue.createApp({});
            // Mount the rowitem component and push it to the items array
            this.items.push(itemApp.component('approitem'));
            console.log('item:'+this.items);
        
            itemApp.mount(); // Mount the component (this is required to create a new instance)

            this.itemtotal ++;
        },
    }
});

