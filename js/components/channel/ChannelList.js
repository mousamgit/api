
export default {
  props: ['isModalOpen', 'channelName'],
  data() {
    return {
      channels: [],
      isAddChannelModalOpen: false,
      isEditModalOpen: false,
      channelName: null,
      channelId:0,
      channelIdGlobal:0,
      showModal: false,
      newAttribute: [],
      columns: [],
      attributeType:[]
    };
  },
  mounted() {
    // Fetch data when the component is mounted
    this.fetchChannels();
    this.fetchAllColumns();

    this.channelName = null;
    this.attributeType = [{"att_type":"Default"},{"att_type":"Computational"}];

  },
  methods: {
    async editAttribute(channel_id) {
      try {
        const response = await fetch(`get-attribute_channelwise.php?channelId=${channel_id}`);
        const data = await response.json();

        this.channelIdGlobal = channel_id;

        if(data==null)
        {
          this.newAttribute.push({
            id:0,
            channel_id:this.channelIdGlobal,
            attribute_name: '',
            attribute_type: 'Default',
            filter_logic: '',
            output_label: '',
          });
        }
        else
        {
          this.newAttribute=data;
        }


        console.log(this.newAttribute)

      } catch (error) {
        console.error('Error fetching attribute:', error);
      }
    },

    async fetchChannels() {
      try {
        // Make an AJAX request to your PHP file
        const response = await fetch('channel_data_fetch.php');
        // Parse the JSON response
        const data = await response.json();

        // Update the channels data
        this.channels = data;
      } catch (error) {
        console.error('Error fetching channels:', error);
      }
    },
    async fetchAllColumns() {
      try {
        // Make an AJAX request to your PHP file to fetch attributes
        const response = await fetch('fetch_all_pim_columns.php');

        // Parse the JSON response
        const data = await response.json();

        // Update the attributes data
        this.columns = data;

        console.log(this.columns,'column_list');
      } catch (error) {
        console.error('Error fetching attributes:', error);
      }
    },
    addAttributeRow() {
      // Add an empty row to the attributes array
      this.newAttribute.push({
        id:0,
        channel_id:this.channelIdGlobal,
        attribute_name: '',
        attribute_type: 'Default',
        filter_logic: '',
        output_label: '',
      });
      console.log(this.newAttribute)
    },
    removeAttributeRow(index) {
      // Remove the row at the given index
      this.newAttribute.splice(index, 1);
    },
    editChannel(channel) {
      this.channelName=null;
      // Emit an event with the channelName
      this.$emit('add-channel', channel);
      console.log('channel',channel.name)
      this.channelName = channel.name
      this.channelId = channel?channel.id:0;
      if(channel.id == undefined)
      {
        this.channelId =0;
      }

      // Open the edit modal
      this.isEditModalOpen = true;
    },
    async submitAttributeForm() {
        const response = await fetch('save_attribute.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(this.newAttribute),
        });

        const data = await response.json();


        if (data.success) {
          console.log('Channel saved successfully!');

          // Close the modal after form submission
          this.showModal = false;
          // Reset the form and editing state
          this.resetForm();
        } else {
          console.error('Error saving attribute:', data.error);
          this.resetForm();
        }

    },

    async submitForm() {
      try {
        const response = await fetch('save_channel.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            channelName: this.channelName,
            channelId:this.channelId
          }),
        });

        const data = await response.json();

        if (data.success) {
          console.log('Channel saved successfully!');
          // Emit an event to save the channel
          this.$emit('save-channel', {
            name: this.channelName,
          });
          // Close the modal after form submission
          this.showModal = false;
          // Reset the form and editing state
          this.resetForm();
        } else {
          console.error('Error saving channel:', data.error);
        }
      } catch (error) {
        console.error('Error saving channel:', error);
      }
    },
    resetForm() {
      this.channelName = '';
      this.channelId = null;
      location.reload();
    },

  },
  template: `
<div class="container mt-5">
    <div class="row">
      <div class="col-md-9">
        <h2 class="mb-4">Channel List</h2>
      </div>
      <div class="col-md-3 text-end">
        <button type="button" @click="editChannel(channel={})" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChannelModal">
          Add Channel
        </button>
      </div>
    </div>

    <div class="modal fade" id="addChannelModal" tabindex="-1" aria-labelledby="addChannelModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addChannelModalLabel">Channels</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="row">
                <div class="col-md-12">
                  <label for="channelName" class="form-label">Channel Name:</label>
                  <!-- Use 'channelName' for v-model -->
                  <input type="hidden" id="channelId" v-model="channelId" class="form-control" >
                  <input type="text" id="channelName" v-model="channelName" class="form-control"  required>
                </div>
              </div>
              <button type="submit" class="btn btn-primary mt-3">Save Channel</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal" id="addAttributeModal" tabindex="-1" aria-labelledby="addAttributeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Attribute</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form for Adding Attributes -->
                            <form @submit.prevent="submitAttributeForm">
                            <div class="row mb-3">
                            <div class="col-md-2">
                             <div class="mb-3">
                                 <center><label for=""> Attribute Name</label> </center>          
                             </div>
                             </div>
                             <div class="col-md-2">
                             <div class="mb-3">
                                 <center><label for="">Output Label</label> </center>           
                             </div>
                             </div>  
                             <div class="col-md-3">
                             <div class="mb-3">
                                 <center><label for="">Attribute Type</label> </center>           
                             </div>
                             </div>
                             <div class="col-md-3">
                             <div class="mb-3">
                                 <center><label for="">Filter Logic</label> </center>           
                             </div>
                             </div>
                            </div>
                                
                                <div v-for="(attribute, index) in newAttribute" :key="index" class="row mb-3">
                                    <div class="col-md-2">
                                        <div class="mb-3"> 
                                         <select v-model="attribute.attribute_name" class="form-control" required>
                                            <option v-for="column in columns" :key="column.COLUMN_NAME" :value="column.COLUMN_NAME">{{ column.COLUMN_NAME }}</option>
                                          </select>                                        
                                          
                                         </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            
                                            <input type="text" v-model="attribute.output_label" placeholder="Output Label" class="form-control" required>
                                        </div>
                                    </div>
                                     <div class="col-md-3">
                                        <div class="mb-3">
                                           <select v-model="attribute.attribute_type" class="form-control" >
                                           <option v-for="att in attributeType" :key="att.att_type" :value="att.att_type">{{ att.att_type }}</option>
                                          </select> 
                                        </div>
                                    </div>
                                     <div class="col-md-3" >
                                        <div class="mb-3" v-if="attribute.attribute_type=='Computational'">
                                          <textarea v-model="attribute.filter_logic" class="form-control" placeholder="Enter your computational logic here...">
                                          </textarea> 
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                       <div class="mb-3">
                                       
                                        <button type="button" @click.prevent="removeAttributeRow(index)" class="btn btn-danger">-</button>
                                        <button type="button" @click.prevent="addAttributeRow" class="btn btn-success">+</button>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Save Attributes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    <div class="container mt-3">
      <table class="table mt-3">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Status</th>
            <th>Last Time Proceed</th>
            <th>Action</th>
          </tr>
        </thead>
        <!--  table body -->
        <tbody>
          <tr v-for="channel in channels" :key="channel.id">
            <td>{{ channel.id }}</td>
            <td>{{ channel.name }}</td>
            <td>{{ channel.type }}</td>
            <td>{{ channel.status }}</td>
            <td>{{ channel.last_time_proceed }}</td>
            <td>
            <a @click="editChannel(channel)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChannelModal">
            <i class="fas fa-edit"></i> Edit
            </a>
            <a class="btn btn-primary" @click="editAttribute(channel.id)" data-bs-toggle="modal" data-bs-target="#addAttributeModal">
            <i class="fas fa-edit"></i> Add Attributes</a>

           
            <a class="btn btn-success" :href="'/pim/channel_attribute.php?channel_id=' + channel.id">
            <i class="fas fa-file-export"></i> Attributes List
            </a>
            
            <a class="btn btn-success" :href="'/pim/channel_attribute_export.php?channel_id=' + channel.id">
            <i class="fas fa-file-export"></i> Export
            </a>
            
             <a class="btn btn-danger">
            <i class="fas fa-trash-alt"></i> Delete
            </a>
            </td>
          </tr>
        </tbody>
      </table>

    </div>
  `,

};
