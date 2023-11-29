<!-- AddChannel.vue -->
<template>
  <div>
    <button @click="openAddChannelModal">Add Channel</button>

    <div v-if="isAddChannelModalOpen" class="modal">
      <div class="modal-content">
        <span @click="closeAddChannelModal" class="close-button">&times;</span>
        <h2>Add Channel</h2>

        <form @submit.prevent="addChannel">
          <label for="channelName">Channel Name:</label>
          <input type="text" id="channelName" v-model="newChannelName" required>

          <!-- Add other form fields as needed -->
          <div class="attributes-section">
            <h2>Attributes</h2>
            <div v-for="(attribute, index) in attributes" :key="index" class="attribute-item">
              <input type="text" v-model="attribute.name" placeholder="Attribute Name">
              <input type="text" v-model="attribute.type" placeholder="Attribute Type">
              <button @click="removeAttribute(index)">-</button>
            </div>
          </div>
          <button @click="addAttribute">+</button>

          <button type="submit">Add Channel</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isAddChannelModalOpen: false,
      newChannelName: '',
      attributes: [],
    };
  },
  methods: {
    openAddChannelModal() {
      this.isAddChannelModalOpen = true;
    },
    closeAddChannelModal() {
      this.isAddChannelModalOpen = false;
    },
    addChannel() {
      // Implement logic to add a new channel
      console.log('Adding channel:', this.newChannelName, this.attributes);
      // Reset form fields after adding channel
      this.newChannelName = '';
      this.attributes = [];
      this.closeAddChannelModal();
    },
    addAttribute() {
      // Implement logic to add a new attribute
      this.attributes.push({ name: '', type: '' });
    },
    removeAttribute(index) {
      // Implement logic to remove an attribute
      this.attributes.splice(index, 1);
    },
  },
};
</script>

<style scoped>
/* Add your component-specific styles here */
</style>
