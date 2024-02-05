export default {
  data() {
    return {
      products: [],
      isAddProductModalOpen: false,
      isEditModalOpen: false,
      showModal: false,
      columns: [],
      productId:0,
      productName:'',
      productNameSearch:''
    };
  },
  mounted() {
    // Fetch data when the component is mounted
    this.fetchProducts();

  },

  methods: {

    async fetchProducts() {
      try {
        // Make an AJAX request to your PHP file
        const response = await fetch('fetch_product.php');
        // Parse the JSON response
        const data = await response.json();

        // Update the product data
        this.products = data;

      } catch (error) {
        console.error('Error fetching product:', error);
      }
    },
    async searchProducts(productName){
      try {
        const response = await fetch('search_product.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            productName: this.productNameSearch,
          }),
        });
        const data = await response.json();

        // Update the product data
        this.products = data;

      } catch (error) {
        console.error('Error fetching product:', error);
      }
    },


    async deleteProduct(product) {
      try {
        // Display a confirmation dialog
        const confirmed = window.confirm(`Are you sure you want to delete the product "${product.name}" and its linked filters?`);

        if (confirmed) {
          const response = await fetch('delete_product.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              productName: product.name,
              productId: product.id
            }),
          });

          const data = await response.json();

          if (data.success) {
            console.log('Product deleted successfully!');

            // Reload the page after successful deletion
            location.reload();
          } else {
            console.error('Error deleting product:', data.error);
          }
        } else {
          // User canceled, do nothing or provide feedback
          console.log('Deletion canceled by the user.');
        }
      } catch (error) {
        console.error('Error deleting product:', error);
      }
    },
    async submitForm() {
      try {
        const response = await fetch('save_product.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            productName: this.productName,
            productId:this.productId,
          }),
        });

        const data = await response.json();


        if (data.success == true) {

          location.reload();
        } else {
          console.error('Error saving channel:', data.error);
        }

      } catch (error) {
        console.error('Error saving channel:', error);
      }
    },


  },
  template: `

<div  style="padding-right: 48px;padding-bottom: 0px;padding-left: 48px;">
    <div class="row">
    <div class="container mt-5">
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-md modal-dialog-centered">
        <div class="modal-content" >
          <div class="modal-header">
            <h5 class="modal-title" id="addProductModalLabel">Product Lists</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="row">
                <div class="col-md-12">
                  <label for="productName" class="form-label">Product Name:</label>
                  <!-- Use 'productName' for v-model -->
                  <input type="hidden" id="productId" v-model="productId" class="form-control" >
                  <input type="text" id="productName" v-model="productName" class="form-control"  required>
                </div>
                </div>
                    
            <button type="submit" class="btn btn-primary mt-3">Save Product</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    </div>
        <div class="col-12 mt-15">
        <div class="row">
            <div class="col-md-2"> <h2 class="no-margin">Product List</h2></div>
            
        </div>    
            <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" v-model="productNameSearch" placeholder="Search by name" @keyup="searchProducts(productName)">
            </div>
        </div>

        <div class="col-md-3">
            <select class="form-control">
                <option>All</option>
                <option>Smart</option>
                <option>Static</option>
            </select>
        </div>

        <div class="col-md-5 d-flex justify-content-end">
            <button class="btn btn-primary btn-block"  data-bs-toggle="modal" data-bs-target="#addProductModal">Create a list</button>
        </div>
    </div>
            <!-- Bootstrap Table -->
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox"></th>
                        <th scope="col">NAME</th>
                        <th scope="col">TYPE</th>
                        <th scope="col">LAST MODIFIED</th>
                        <th scope="col">CREATED</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                   
                    <tr v-for="product in products" :key="product.id">
                        <td><input type="checkbox"></td>
                        <td><a :href="'/products/product_details.php?id=' + product.id">{{product.name}}</a></td>
                        <td>{{product.type}}</td>
                       
                        <td>{{product.updated_at}}</td>
                        <td>{{product.created_at}}</td>
                        <td> <a class="btn btn-danger" @click="deleteProduct(product)">
                                <i class="fas fa-trash-alt" ></i>
                             </a>
                        </td>
                    </tr>
                    <!-- Add other rows as needed -->
                </tbody>
            </table>

            <!-- Bootstrap Pagination -->
<!--            <nav aria-label="Page navigation">-->
<!--                <ul class="pagination mt-3">-->
<!--                    <li class="page-item disabled">-->
<!--                        <a class="page-link" href="#" aria-label="Previous">-->
<!--                            <span aria-hidden="true">&laquo;</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="page-item active"><span class="page-link">1</span></li>-->
<!--                    &lt;!&ndash; Add other pagination items as needed &ndash;&gt;-->
<!--                    <li class="page-item disabled">-->
<!--                        <a class="page-link" href="#" aria-label="Next">-->
<!--                            <span aria-hidden="true">&raquo;</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </nav>-->

            <!-- Bootstrap Dropdown for Results per page -->
<!--            <div class="ml-2">-->
<!--                <div class="dropdown">-->
<!--                    <button class="btn btn-secondary dropdown-toggle" type="button" id="resultsPerPageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                        Results per page-->
<!--                    </button>-->
<!--                    <div class="dropdown-menu" aria-labelledby="resultsPerPageDropdown">-->
<!--                        <a class="dropdown-item" href="#">25</a>-->
<!--                        &lt;!&ndash; Add other dropdown items as needed &ndash;&gt;-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
    
</div>
  `,

};
