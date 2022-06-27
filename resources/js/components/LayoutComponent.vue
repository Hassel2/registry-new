<template>
  <div>
    <div class="Licenses">
      <input v-model="$root.search" />
      <tree-component></tree-component>
    </div>
    <div class="Content">Content</div>
  </div>
</template>

<script>
import TreeComponent from './TreeComponent.vue';

export default {
  components: {
    TreeComponent
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
