
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
      channelAttribute:[],
      columns: [],
      attributeType:[],
      attribute_values:[],
      indexCheck:0,
      thisRow:'Default'
    };
  },
  mounted() {
    // Fetch data when the component is mounted
    this.fetchChannels();
    this.fetchAllColumns();

    this.channelName = null;
    this.attributeType = [{"att_type":"Default"},{"att_type":"Computational"}];
    this.channelAttribute.push({
      id:0,
      attribute_name:'',
      data_type: '',
      attribute:'',
      attribute_condition:''
    });

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

    },
    removeAttributeRow(index,attribute_id) {
      if (this.newAttribute.length > 1) {
        // Remove the row at the given index

        if(attribute_id ==0)
        {
          this.newAttribute.splice(index, 1);

        }
        else
        {
        this.deleteAttribute(attribute_id,'delete_channel_attributes.php').then((value) => {
         if(value == 1)
         {
           this.newAttribute.splice(index, 1);
         }
        });

        }
      }

    },
    async fetchChannelAttributeFilter(channel_id){
      try {
        const response = await fetch(`get-attribute_filter_channelwise.php?channelId=${channel_id}`);
        const data = await response.json();

        this.channelIdGlobal = channel_id;

        if(data.length>0)
        {


          this.channelAttribute=data;
        }
        console.log('hello',this.channelAttribute)

      } catch (error) {
        console.error('Error fetching attribute filter:', error);
      }
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
      this.fetchChannelAttributeFilter(this.channelId)
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
            channelId:this.channelId,
            attribute:this.channelAttribute
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
          location.reload();
        } else {
          console.error('Error saving channel:', data.error);
        }

      } catch (error) {
        console.error('Error saving channel:', error);
      }
    },
    addChannelCondition() {
      this.channelAttribute.push({
        id:0,
        attribute_name:'',
        data_type: '',
        attribute:'',
        attribute_condition:''
      });
    },

    removeChannelCondition(index,filter_id) {
      if (this.channelAttribute.length > 1) {
          if(filter_id ==0)
          {
            this.channelAttribute.splice(index, 1);

          }
          else
          {
            this.deleteAttribute(filter_id,'delete_attribute_filters.php').then((value) => {
              if(value == 1)
              {
                this.channelAttribute.splice(index, 1);
              }
            });

          }
      }
    },
    resetForm() {
      this.channelName = '';
      this.channelId = null;
      location.reload();
    },
    handleChangeAttribute(index) {
      this.attribute_values = [];
      this.channelAttribute[index].filter_type = '';
      this.channelAttribute[index].attribute_condition = '';
      // Get the selected value from the attribute dropdown
      const selectedValue = this.channelAttribute[index].attribute;

      // Split the selected value based on the comma
      const [att_name, d_type] = selectedValue.split(',');

      // Update the attribute_name and data_type properties separately
      this.channelAttribute[index].attribute_name = att_name;
      this.channelAttribute[index].data_type = d_type;
      console.log(this.channelAttribute)
    },
    async getAttributeValue(index,attributeName,attributeCondition){
        try {
          if(attributeCondition.length>2) {
            // Make an AJAX request to your PHP file to fetch attributes
            const response = await fetch('fetch_attribute_values.php?attribute_name=' + attributeName + '&attribute_condition=' + attributeCondition);

            // Parse the JSON response
            const data = await response.json();


            this.attribute_values = data;
            this.indexCheck = index;

          }

        } catch (error) {
          console.error('Error fetching values:', error);
        }
    },
    // Method to handle selecting a value from autocomplete suggestions
    selectAutocompleteValue(index, selectedValue) {
      this.channelAttribute[index].attribute_condition = selectedValue;
      this.attribute_values = [];
    },

    async deleteChannel(channel) {
      try {
        // Display a confirmation dialog
        const confirmed = window.confirm(`Are you sure you want to delete the channel "${channel.name}" and its linked attributes?`);

        if (confirmed) {
          const response = await fetch('delete_channel.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              channelName: channel.name,
              channelId: channel.id
            }),
          });

          const data = await response.json();

          if (data.success) {
            console.log('Channel with its attributes deleted successfully!');
            this.resetForm();
            // Reload the page after successful deletion
            location.reload();
          } else {
            console.error('Error deleting channel:', data.error);
          }
        } else {
          // User canceled, do nothing or provide feedback
          console.log('Deletion canceled by the user.');
        }
      } catch (error) {
        console.error('Error deleting channel:', error);
      }
    },
    async deleteAttribute(id,delete_url) {

      try {
        // Display a confirmation dialog
        const confirmed = window.confirm(`Are you sure you want to delete the attributes?`);

        if (confirmed) {
          const response = await fetch(delete_url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              id: id
            }),
          });

          const data = await response.json();

          if (data.success) {
            return '1';
            console.log('Attributes deleted successfully!');
          } else {
            console.error('Error deleting attribute:', data.error);
          }
        } else {
          console.log('Deletion canceled by the user.');
        }
      } catch (error) {
        console.error('Error deleting channel:', error);
      }
    }

  },
  template: `

<div class="container mt-300">
    <div class="row">
    <div class="container mt-5">
    <div class="modal fade" id="addChannelModal" tabindex="-1" aria-labelledby="addChannelModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullpage">
        <div class="modal-content" style="height: 100vh;">
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
        <div class="col-12 mt-15">
        <div class="row">
            <div class="col-md-2"> <h2 class="no-margin">Channels</h2></div>
            
        </div>    
            <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search by name">
            </div>
        </div>

        <div class="col-md-3">
            <select class="form-control">
                <option>All</option>
                <option>CSV</option>
                <option>XML</option>
            </select>
        </div>

        <div class="col-md-5 d-flex justify-content-end">
            <button class="btn btn-primary btn-block" @click="editChannel(channel={})" data-bs-toggle="modal" data-bs-target="#addChannelModal">Add Channel</button>
        </div>
    </div>
            <!-- Bootstrap Table -->
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox"></th>
                        <th scope="col">NAME</th>
                        <th scope="col">TYPE</th>
                        <th scope="col">STATUS</th>
                        <th scope="col">LAST TIME PROCESSED</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                   
                    <tr v-for="channel in channels" :key="channel.id">
                        <td><input type="checkbox"></td>
                        <td><a :href="'/channel_details.php?id=' + channel.id">{{channel.name}}</a></td>
                        <td>{{channel.type}}</td>
                        <td><span class="">Active</span></td>
                        <td>{{channel.last_time_proceed}}</td>
                        <td> <a class="btn btn-danger" @click="deleteChannel(channel)">
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
