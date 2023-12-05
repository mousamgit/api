// attributeList.js
export default {
    data() {
        return {
            attributes: [], // Populate this with your actual attribute data
            columns: [], // Populate this with your actual attribute data
            channel_id:0
        };
    },
    mounted() {
        // Fetch data when the component is mounted
        this.fetchAttributes();
    },
    methods: {
        async fetchAttributes() {
            try {
                // Make an AJAX request to your PHP file to fetch attributes
                const response = await fetch('attribute_data_fetch.php');

                // Parse the JSON response
                const data = await response.json();

                // Update the attributes data
                this.attributes = data;
                this.channel_id = data[0]['channel_id'];

                console.log(this.channel_id);
            } catch (error) {
                console.error('Error fetching attributes:', error);
            }
        },

        editAttribute(attribute) {
            console.log('button_pressed')

        },


    },
    template: `
        <div class="container mt-3 text-end ">
            <!-- Add Attribute Button -->
<!--            <button type="button" @click="editAttribute(attributes={})" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttributeModal">-->
<!--                Add Attribute-->
<!--            </button>-->

            <a class="btn btn-success" :href="'/pim/channel_attribute_export.php?channel_id='+channel_id">
            <i class="fas fa-file-export"></i> Export
            </a>
            

            <!-- Attribute List Table -->
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Attribute Name</th>
                        <th>Output Label</th>
                        <th>Formatting</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(attribute, index) in attributes" :key="index">
                        <td>{{ attribute.id }}</td>
                        <td>{{ attribute.attribute_name }}</td>
                        <td>{{ attribute.output_label }}</td>
                        <td>{{ attribute.formatting }}</td>
                        <td>
                            <!-- Add buttons for edit, delete, and other actions as needed -->
                            <a class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
                            <!-- Add more buttons/actions here -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    `,
};
