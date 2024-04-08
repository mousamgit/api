const myapp = Vue.createApp({
    data(){
        return{

        }
    },

    mounted() {
        // Add a click event listener to the document
        // document.addEventListener('click', this.handleClickOutside);
      },
    methods: {     

        handleClickOutside(event) {


            const formElement = this.$el.querySelector('td');
            // Check if the clicked element is not inside the form
            if (formElement && !formElement.contains(event.target)) {
              console.log('out');
            }

          },
    },
    watch: {
        // filtertitle() {
        //     // Watch for changes in filterindex and call updatetitle
        //     console.log('updatetitle', this.filtertitle, this.filterindex);
        //     this.filterarray[this.filterindex][0] = this.filtertitle;
        // },

    },
});
