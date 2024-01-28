
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
      indexCheck:0
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
<style scoped>  /* Style for autocomplete suggestions */
  .autocomplete-suggestions {
    list-style-type: none;
    padding: 0;
    margin: 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    position: absolute;
    background-color: #fff;
    z-index: 1000;
    max-height: 150px;
    overflow-y: auto;
  }

  .autocomplete-suggestions li {
    padding: 8px 12px;
    cursor: pointer;
  }

  .autocomplete-suggestions li:hover {
    background-color: green;
  }
</style>
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
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
              <div class="container mt-3">
                <div class="border p-3 rounded">
                <fieldset>
                    <legend> Add Condition </legend>
                    <hr>
                    
                        <div v-for="(cAttribute, index) in channelAttribute" :key="index" class="channel-condition">
                        <div class="row mb-3">
                            <div class="col-md-5">
                            <label for="attribute" class="form-label">Attribute Name:</label>
                            <div class="mb-3"> 
                            <select v-model="cAttribute.attribute" class="form-control" @change="handleChangeAttribute(index)" required>
                            <option v-for="column in columns" :key="column.column_name" :value="column.column_name + ',' + column.data_type">
                            {{ column.column_name }}
                            </option>
                            </select>
                            </div>
                        </div>
                    <div class="col-md-5">
                    <label for="attribute" class="form-label">Attribute Condition:</label>
                    <div class="mb-3">
                  
                     <template v-if="cAttribute.data_type == 'varchar'">
                     <select v-model="cAttribute.filter_type" id="filter-type" class="form-select" >
                        <option value="includes">Includes</option>
                        <option value="=">equals</option>
                        <option value="IS NOT NULL">Is Not Empty</option>
                     </select>
                    <template v-if="cAttribute.filter_type == '=' || cAttribute.filter_type == 'includes'" >
                     <input type="text" v-model="cAttribute.attribute_condition" @keyup="getAttributeValue(index,cAttribute.attribute_name,cAttribute.attribute_condition)" class="form-control" placeholder="Enter condition" required>
                     <ul v-if="index == indexCheck && cAttribute.filter_type != 'includes'" class="autocomplete-suggestions">
                        <li v-for="(value, vindex) in attribute_values" :key="vindex" @click="selectAutocompleteValue(index, value)">
                         {{ value }}
                        </li>
                      </ul>
                    </template>
                    
                    </template>
                    
                    <template v-else>
                        <div class="row">
                    <select v-model="cAttribute.filter_type" id="filter-type" class="form-select">
                        <option value="between">Range</option>
                        <option value="IS NOT NULL">IS NOT EMPTY</option>
                     </select>
                    <template v-if="cAttribute.filter_type == 'between'">
                        <div class="col-md-6">
                        <input type="text" v-model="cAttribute.rangeFrom" class="form-control" placeholder="From" required>
                        </div>
                        <div class="col-md-6">
                        <input type="text" v-model="cAttribute.rangeTo" class="form-control" placeholder="To" required>
                        </div>
                        </div>
                     </template>
                    </div>
                    </div>
                    <div class="col-md-2">
                    <div class="mb-3">
                    
                    <button type="button" @click="addChannelCondition" class="btn btn-success" v-if="index === channelAttribute.length - 1">+</button>
                    <button type="button" @click="removeChannelCondition(index,cAttribute.id)" class="btn btn-danger" v-if="channelAttribute.length > 1">-</button>
                    </div>
                </div>
               </div>
            </div>
            </fieldset>   
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
                                            <option v-for="column in columns" :key="column.column_name" :value="column.column_name">{{ column.column_name }}</option>
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
                                       
                                        <button type="button" @click.prevent="addAttributeRow" class="btn btn-success" v-if="index === newAttribute.length - 1">+</button>
                                        <button type="button" @click.prevent="removeAttributeRow(index,attribute.id)"  v-if="newAttribute.length > 1"  class="btn btn-danger">-</button>
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

           
            <a class="btn btn-success" :href="'/channel_attribute.php?channel_id=' + channel.id">
            <i class="fas fa-file-export"></i> Attributes List
            </a>
            
            <a class="btn btn-success" :href="'/channel_attribute_export.php?channel_id=' + channel.id">
            <i class="fas fa-file-export"></i> Export
            </a>
            
             <a class="btn btn-danger" @click="deleteChannel(channel)">
            <i class="fas fa-trash-alt" ></i> Delete
            </a>
            </td>
          </tr>
        </tbody>
      </table>
     
    </div>
  `,

};
