<template>
  <div>
    <div class="Licenses">
      <div class = "container">
        <search-component></search-component>
        <tree-component ref = 'tree'></tree-component>
      </div>
    </div>
    <div class="Content">Content</div>
  </div>
</template>

<script>
import SearchComponent from './SearchComponent.vue';
import TreeComponent from './TreeComponent.vue';

export default {
  components: {
    TreeComponent,
    SearchComponent,
  },
};
</script>

<style scoped>
.Licenses {
  height: 600px;
  width: 23%;
  float: left;
  border: 3px solid;
}

.Content {
  height: 600px;
  width: 77%;
  float: left;
  border: 3px solid;
}
</style>
