export default {
    data() {
        return {
            attributes: [],
            columns: [],
            channel_id: 0,
            heads: [],
            currentPage: 1,
            itemsPerPage: 25,
        };
    },
    mounted() {
        this.fetchAttributes();
    },
    methods: {
        async fetchAttributes() {
            try {
                const response = await fetch('attribute_list_detail.php?page=' + this.currentPage);

                const data = await response.json();

                this.heads = data.heads;
                this.columns = data.columns;
            } catch (error) {
                console.error('Error fetching attributes:', error);
            }
        },
        nextPage() {
            this.currentPage++;
            this.fetchAttributes();
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchAttributes();
            }
        },
    },
    template: `
    <div class="container mt-3 text-end">
      <a class="btn btn-success" :href="'/pim/channel_attribute_export.php?channel_id='+channel_id">
        <i class="fas fa-file-export"></i> Export
      </a>

      <!-- Attribute List Table -->
      <table class="table mt-3">
        <thead>
          <tr>
            <th>S.N</th>
            <th v-for="(head, index) in heads" :key="index">{{ head }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(column, index) in columns" :key="index">
            <td>{{index+1}}</td>
            <td v-for="(head, hindex) in heads" :key="hindex">                                  
              <span v-if="head.includes('image')">
                <img :src="column[head]" alt="Image" width="100" height="100">
              </span>
              <span v-else>
                {{ column[head] }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination controls -->
      <div>
        
            <div class="btn-group" role="group" aria-label="Pagination">
             <button class="btn btn-primary" @click="prevPage">Prev</button>
               <button class="btn btn-success ml-2 mr-2">Page {{ currentPage }}</button>
              <button class="btn btn-primary" @click="nextPage">Next</button>
            </div>
</div>

      
    </div>
  `,
};
