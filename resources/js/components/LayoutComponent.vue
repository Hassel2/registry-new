<template>
  <div>
    <div class="Licenses border border-warning rounded shadow m-1">
      <search-component></search-component>
      <tree-component ref = 'tree'></tree-component>
    </div>
    <div class="Content border border-warning rounded shadow m-1">
      <content-component></content-component>
    </div>
  </div>
</template>

<script>
import SearchComponent from './SearchComponent.vue';
import TreeComponent from './TreeComponent.vue';
import ContentComponent from './ContentComponent.vue';


export default {
  components: {
    TreeComponent,
    SearchComponent,
    ContentComponent,
  },
};
</script>

<style scoped>
.Licenses {
  height: 640px;
  width: 22%;
  float: left;
}

.Content {
  height: 640px;
  width: 76.5%;
  float: left;
}
</style>
