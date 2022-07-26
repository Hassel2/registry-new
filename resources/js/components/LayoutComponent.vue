<template>
  <div class="container-fluid">
    <div class="row">
      <div :class="ShowContent ? 'col-sm-3' : 'col'" class="border border-warning rounded shadow m-1">
        <search-component></search-component>
        <tree-component ref='tree'></tree-component>
      </div>
      <div class="col-md-auto border border-warning rounded my-1" @click="isShowContent()">
        <i v-if="!ShowContent" class="bi bi-caret-left-fill"></i>
        <i v-else class="bi bi-caret-right-fill"></i>
      </div>
      <transition name="slide-fade">
        <div v-show="ShowContent" class="col border border-warning rounded shadow m-1">
          <content-component></content-component>
        </div>
      </transition>
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

  data() {
    return {
      ShowContent: true
    }
  },

  methods: {
    isShowContent() {
      this.ShowContent = !this.ShowContent
    }
  }
};
</script>

<style scoped>
.slide-fade-enter-active {
  transition: all .3s ease;
}

.slide-fade-leave-active {
  transition: all .0s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>
