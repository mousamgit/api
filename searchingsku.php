<html>
  <head>
    <title>Search Sku</title>
   
    <?php include 'header.php'; ?>
    <script src="./js/searchingsku.js" ></script>

  </head>
  <body>
  <div id="app">
        <form @submit.prevent="searchSKU">
            <label for="skuInput">Enter SKU:</label>
            <input v-model="sku" type="text" id="skuInput" name="skuInput" required>
            <button type="submit">Search</button>
        </form>

        <div v-for="(result, index) in results" :key="index">
            <div class="row pro-container"><div class="col-md-1"><img :src="result.image" ></div><div class="col-md-9"><h3>{{ result.sku }}</h3><div class="pro-name">{{ result.title }}</div></div></div>
        </div>
    </div>
</body>
<script>
const callmyapp = myapp.mount('#app');
</script>
</html>