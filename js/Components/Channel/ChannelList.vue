<!-- ChannelsList.vue -->
<template>
  <div>
    <h2>Channels List</h2>
    <table class="table">
      <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Status</th>
        <th>Last Time Proceed</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="channel in channels" :key="channel.id">
        <td>{{ channel.id }}</td>
        <td>{{ channel.name }}</td>
        <td>{{ channel.type }}</td>
        <td>{{ channel.status === 1 ? 'Active' : 'Inactive' }}</td>
        <td>{{ channel.last_time_proceed }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: ['channels'],
};
</script>

<style scoped>
/* Add your component-specific styles here */
</style>
