
export default {
    props: ['listDetails', 'showFilters','filters','primary_table'],
    data() {
        return {
            channelAttribute: [],
            indexCheck: 0,
            columns: [],
            attribute_values: [],
            showAttribute: 0,
            showAttFilter: 1,
            indexVal: -1,
            showAttributeMid: 0,
            op_show_value: 'AND',
            selectedValues:[],
            showManualValidationMessage:0,
            filter_name:'',
            showInput:0,
            editForm:-1,
            filter_no:0,
            filterList:[],
            deletedId:[],
            showFilterValidation:false
        };
    },
    mounted() {
    },
    computed: {
        sortedAttributeValues() {
            const selected = this.selectedValues.slice();
            const unselected = this.attribute_values.filter(value => !selected.includes(value));

            return selected.concat(unselected);
        }
    },

    methods: {
        async deleteFilter(listDetId, listId, indexVal) {

            try {
                if (indexVal == 0) {
                    this.indexVal = listDetId;
                } else {
                    this.indexVal = -1;
                }

                const response = await fetch('./crud/delete_list_filter.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        productId: listId,
                        productDetId: listDetId,
                        indexVal: this.indexVal,
                        table_name:this.primary_table
                    }),
                });
                const data = await response.json();
                if (data.success) {
                    this.initializeData()
                    this.$emit('filters-updated');
                    this.$emit('form-updated');
                    this.showFilter=true;
                } else {
                    console.error('Error deleting filter:', data.error);
                }

            } catch (error) {
                console.error('Error deleting filter:', error);
            }
        },
        async  controlFilters() {
            this.showFilterValidation=false;
            this.showInput=1;
            const dataToSend = {
                filter_no: this.filter_no,
                table_name: this.primary_table
            };

            try {
                const response = await fetch('./control_user_filters.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                });

                if (!response.ok) {
                    throw new Error('Failed to update database');
                }
                this.initializeData()
                this.$emit('filters-updated');
                this.getFilterDetails(this.filter_no)
            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        handleInput(){
            this.showInput=1;
        },
        async updateStatus(value) {
            if(value==0)
            {
                // Display a confirmation dialog
                const confirmed = window.confirm(`Are you sure you want to remove `+this.filter_name);

                if (confirmed) {
                    try {
                        const response = await fetch('update_filter_status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                value: value,
                                filter_name: this.filter_name,
                                list_details: this.listDetails,
                                filter_no: this.filter_no,
                                table_name:this.primary_table,
                                deletedId: this.deletedId
                            }),
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.initializeData()
                            this.$emit('filters-updated');

                            this.showInput = 0;
                            this.filter_name = '';
                            this.filter_no = 0;
                            this.showInput = 0;
                        } else {
                            console.error('Error updating status:', data.error);
                        }
                    } catch (error) {
                        console.error('Error updating status:', error);
                    }
                }
            }
            else{

                if(value==1 && this.filter_name =='') {
                    this.showFilterValidation = true;
                    return;
                }
                try {
                    const response = await fetch('update_filter_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            value: value,
                            filter_name: this.filter_name,
                            list_details: this.listDetails,
                            filter_no: this.filter_no,
                            table_name:this.primary_table,
                            deletedId: this.deletedId
                        }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.initializeData()
                        this.$emit('filters-updated');
                        if (value == 1) {
                            this.filter_no=data.filter_no;
                            this.controlFilters()
                        } else {
                            // localStorage.removeItem('deletedId');
                            this.showInput = 0;
                            this.filter_name = '';
                            this.filter_no = 0;
                            this.showInput = 0;
                        }

                    } else {
                        console.error('Error updating status:', data.error);
                    }
                } catch (error) {
                    console.error('Error updating status:', error);
                }
            }
        },
        //v2
        editFilter(listDet,index)
        {
            let attribute_condition_value = listDet.attribute_condition.slice(1, -1).split(',');
            attribute_condition_value = attribute_condition_value.map(function(value) {
                value = value.trim();

                // Remove leading and trailing double quotes
                if (value.startsWith('"')) {
                    value = value.substring(1);
                }
                if (value.endsWith('"')) {
                    value = value.substring(0, value.length - 1);
                }

                return value;
            });
            this.selectedValues=[]

            attribute_condition_value.forEach(function(value) {
                this.selectedValues.push(value);
            }, this);

            this.showAttFilter =0;
            this.editForm=index;
            this.channelAttribute = [{
                id: listDet.id,
                attribute_name: listDet.attribute_name,
                data_type: listDet.data_type_value,
                filter_type: listDet.filter_type,
                attribute: listDet.attribute_name +','+listDet.data_type_value,
                attribute_condition: listDet.attribute_condition,
                attribute_current: '',
                rangeFrom: listDet.range_from,
                rangeTo:listDet.range_to,
                operator: listDet.op_value,
                condition_type: 'abc',
                previous_row: [],
                type:'edit'
            }];

        },



        addChannelCondition(op_value, condition_type, previous_row) {
            this.showInput=1;
            this.op_show_value = op_value;
            this.showAttributeMid = previous_row['id'];
            this.selectedValues=[];
            this.channelAttribute = [{
                id: 0,
                attribute_name: '',
                data_type: '',
                attribute: '',
                attribute_condition: 'IS NOT NULL',
                operator: op_value,
                condition_type: condition_type,
                previous_row: previous_row,
                type:'insert'

            }];
            this.showAttribute = 1;
            this.showAttFilter = 1;

        },

        async  getFilterDetails(filter_no) {
            const dataToSend = {
                filter_no: filter_no
            };

            try {
                const response = await fetch('./users/get_user_filter_details.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                })

                if (!response.ok) {
                    throw new Error('Failed to fetch filter details');
                }
                const data = await response.json();
                this.filter_name = data;

            } catch (error) {
                console.error('Error updating database:', error);
            }
        },
        getEmptyPrinted(value) {
            if (value == 'IS NOT NULL') {
                return 'IS NOT EMPTY';
            }
            else if(value == 'IS NULL')
            {
                return 'IS EMPTY';
            }
            return value;
        },

        initializeData() {
            this.showFilterValidation=false;
            this.channelAttribute = [],
                this.indexCheck = 0,
                this.columns = [],
                this.attribute_values = [],
                this.showAttribute = 0,
                this.showAttFilter = 1,
                this.indexVal = -1,
                this.showAttributeMid = 0
        },
        handleForm()
        {
            this.showAttFilter=1;
            this.showFilterValidation=false;
            this.channelAttribute = [];
            this.indexCheck = 0
            this.$emit('filters-updated')
            this.showAttributeMid = 0
        }


    },
    template: `
    <div class="flex-row vcenter right-slider-header"><span class="sub-heading">FILTERS</span> </div> 
    <div class=" test right-menu filters background-secondary-bg">
                             <select class="card" v-model="filter_no" @change="controlFilters"  v-if="listDetails.length==0">
                                <option value="0"  selected><a class="btn" >All Filter   <i class="fa-solid fa-caret-down"></i></a> </option>
                                <template v-for="(fvalue, fkey) in filters">
                                   <option :value="fvalue.id"><a class="btn" >{{ fvalue['filter_name'] }}   </a> </option>
                                </template>
                             </select>                           
     
<i  v-if="listDetails.length==0" class="fa fa-chevron-down" aria-hidden="true"></i>
        <input  class="card" v-if="listDetails.length>0" @keyup="showFilterValidation=false" type="text" v-model="filter_name"  class="form-control" :class="{ 'err-box': showFilterValidation }" placeholder="Name your filter" required>     
       
        <a class="card add-condition" v-if="listDetails.length==0 && channelAttribute.length==0"  @click="addChannelCondition('AND','normal',[])">New Condition<i class="fa fa-plus"></i></a>
 
                            <div class="form-group selected-filters">
                            
                                <div v-if="listDetails.length>0" v-for="(listDet,index) in listDetails" class="filter-condition">
                                    <span class="" v-if="showAttFilter==1">
                                        <span v-if="listDet.op_value == 'OR' && listDet.id != listDetails[0].id">
                                            <a class="btn white-btn" @click="addChannelCondition('AND','middle',listDetails[index-1])" data-test-id="and">
                                                <strong>AND</strong>
                                            </a>
                                        </span>
                                        <div v-if="listDet.op_value== 'OR' && listDet.id != listDetails[0].id && showAttributeMid==listDetails[index-1].id">
                                         
                                            <list-filter-form :primary_table="primary_table" :showAttributeFirst="0" :selectedValues="selectedValues" :listDetailValue="listDetails[index]" :channelAttribute="channelAttribute" @form-updated="handleForm"></list-filter-form>
                                        </div>
                                        <center v-if="listDet.op_value == 'OR'"><span class="value text-ellipsis" v-if="(listDet.attribute_name !='' && index !=0)">---------- {{listDet.op_value}} ----------</span>
                                    </span></center>
                                
                                    <div class="filter-clauses card position-relative" v-if="showAttFilter==1" @click="editFilter(listDet,index)">
                                                <div class="flex-grow-1">
                                                        <span class="alternative emphasis filter-field ">{{listDet.attribute_name}} </span> 
                                                        <span class="text-default mt-5" v-if="listDet.filter_type !=''">&nbsp;{{ getEmptyPrinted(listDet.filter_type) }}</span>
                                                        <span class="text-default" v-if="listDet.range_to !=''">&nbsp; {{listDet.range_from}} to {{listDet.range_to}}</span>
                                                        <span class="text-default" v-if="listDet.attribute_condition !='' && listDet.attribute_condition != listDet.filter_type">&nbsp; {{getEmptyPrinted(listDet.attribute_condition)}} </span>
                                                </div>
                                                <div class="delete-icon position-absolute end-0" data-test-id="delete">
                                                    <a @click="deleteFilter(listDet.id,listDet.list_id,index)">
                                                        <i class="fa fa-times animation-mode" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                    </div>
                                    <div class="editForm" v-if="showAttFilter==0 && editForm===index">
                                        <list-filter-form :primary_table="primary_table" :showAttributeFirst="1" :selectedValues="selectedValues"  :listDetailValue="listDetails[index]" :channelAttribute="channelAttribute" @form-updated="handleForm"></list-filter-form>
                                    </div>
                                </div>
                                <div class="form-group filter-clauses">
                                            <fieldset v-if="(listDetails.length>0 && (showAttributeMid == listDetails[listDetails.length-1].id)) || (listDetails.length==0)">
                                                <template v-if="listDetails.length==0"> 
                                                    <list-filter-form :primary_table="primary_table" :showAttributeFirst="0" :selectedValues="selectedValues"  :listDetailValue="[]" :channelAttribute="channelAttribute" @form-updated="handleForm"></list-filter-form>
                                                </template>
                                                <template v-else>
                                                    <list-filter-form :primary_table="primary_table" :showAttributeFirst="0" :selectedValues="selectedValues" :listDetailValue="listDetails[0]" :channelAttribute="channelAttribute" @form-updated="handleForm"></list-filter-form>
                                                </template>
                                            </fieldset>
                                            <div class="operators" v-if="listDetails.length>0 && channelAttribute.length==0">
                                                <a class="btn white-btn" @click="addChannelCondition('AND','normal',listDetails[listDetails.length - 1])" data-test-id="and">
                                                    <strong>AND</strong>
                                                </a>
                                                <a class="btn white-btn" @click="addChannelCondition('OR','group',listDetails[listDetails.length - 1])" data-test-id="or">
                                                    <strong>OR</strong>
                                                </a>
                                            </div>

                                        </div>

                            </div>
                    
                            </div>
</div>
<div class="submit-form" v-if="listDetails.length>0">     
       <template v-if="filter_no ==0">          
           <a class="btn btn-primary mt-3"  @click="updateStatus(1)">Create</a>         
           <a class="btn btn-primary mt-3" @click="updateStatus(-1)">Clear</a>
       </template>
       <template v-else>          
                <a class="btn btn-primary mt-3" @click="updateStatus(1)">Update </a>
                <a class="btn btn-primary mt-3" @click="updateStatus(-1)">Clear</a>
                <a class="btn btn-danger mt-3" @click="updateStatus(0)">Delete</a>
       </template>
</div>
<div class="submit-form" v-if="listDetails.length==0 && filter_no != 0">
  <a class="btn btn-primary mt-3" @click="updateStatus(-1)">Clear</a>
</div>
  `,
};