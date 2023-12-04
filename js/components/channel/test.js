// AddChannel.js
export default {
    props: ['isModalOpen'],
    emits: ['update:isModalOpen'],
    data() {
        return {
            showModal: false,
            channelName: '',
            attributes: [{ attribute_name: '', output_label: '' }],
            // Add new data properties for editing
            isEditing: false,
            editIndex: null,
        };
    },
    methods: {

        addAttribute() {
            this.attributes.push({ name: '', type: '' });
        },
        removeAttribute(index) {
            this.attributes.splice(index, 1);
        },
        editAttribute(index) {
            // Set the attribute data for editing
            this.attribute_name = this.attributes[index].attribute_name;
            this.output_label = this.attributes[index].output_label;
            // Set the editing state and index
            this.isEditing = true;
            this.editIndex = index;
        },
        async submitForm() {
            try {
                const response = await fetch('save_channel.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: this.name,
                        type: this.type,
                        attributes: this.attributes,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    // Channel and attributes saved successfully
                    console.log('Channel and attributes saved successfully!');
                    location.reload();
                } else {
                    // Handle the error
                    console.error('Error saving channel and attributes:', data.error);
                }
            } catch (error) {
                console.error('Error saving channel and attributes:', error);
            }

            // Close the modal after form submission
            $('#addChannelModal').modal('hide');
        },
        // Add a method to reset the form and editing state
        resetForm() {
            this.channelName = '';
            this.attributes = [{ attribute_name: '', output_label: '' }];
            this.isEditing = false;
            this.editIndex = null;
        },
    },
    template: `
    <div class="container mt-5">   
     <div class="row">
    <div class="col-md-9">
      <h2 class="mb-4">Channel List</h2>
    </div>
    <div class="col-md-3 text-end">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChannelModal">
        Add Channel
      </button>
    </div>
  </div>
    
    <div class="modal fade" id="addChannelModal" tabindex="-1" aria-labelledby="addChannelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addChannelModalLabel">Add Channel</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="submitForm">
              <div class="row">
               <div class="col-md-6">
               <label for="channelName" class="form-label">Channel Name:</label>
               <input type="text" id="channelName" v-model="name" class="form-control" required>
              </div> 
              <div class="col-md-6">
               <label for="channelType" class="form-label">Channel Type:</label>
               <input type="text" id="channelType" v-model="type" class="form-control" required>
              </div>
              
              </div>
              <div class="attributes-section mt-3">
               <h5>Attributes</h5>
               <div v-for="(attribute, index) in attributes" :key="index" class="mb-2">
              <div class="row">
              <div class="col-md-4">
                    <input type="text" v-model="attribute.attribute_name" placeholder="Attribute Name" class="form-control">   
              </div>
              <div class="col-md-4">
               <input type="text" v-model="attribute.output_label" placeholder="Output Label" class="form-control">
              </div>
              <div class="col-md-4">
              <button type="button" @click.prevent="removeAttribute(index)" class="btn btn-danger">-</button> 
              <button type="button" @click.prevent="addAttribute" class="btn btn-success ml-1">+</button>
              </div>
              </div>
               </div>
                </div>
        
                <button type="submit" class="btn btn-primary mt-3">Add Channel</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    


      

      <div v-if="showModal" class="modal">
        <div class="modal-content">
          <span @click="showModal = false" class="close-button">&times;</span>
          
         
        </div>
      </div>
    </div>
  `,
};
