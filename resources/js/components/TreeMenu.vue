<template>
  <div class="tree-menu">
    <div :style="indent"
    :class="label.includes($root.search) && $root.search != '' ? 'text-primary' : ''"
    @click="toggleChildren">
      <!-- <span v-if="this.$props.nodes">[{{ showChildren ? '-' : '+' }}]</span>  -->
      <span v-if="this.$props.nodes">
        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" 
        :class="showChildren || $root.search != '' ? 'bi bi-caret-right-fill' : 'bi bi-caret-down-fill'" viewBox="0 0 16 16">
          <path :d="showChildren || $root.search != ''
          ? 'M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'
          : 'm12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z'"/>
        </svg>
      </span>

      {{ label }}
    </div>
    <tree-menu v-if="isOpen($root.search)" v-for="node in nodes" :nodes="node.nodes" :label="node.label":depth="depth + 1"></tree-menu>
  </div>
</template>

<script>

export default { 
  props: [ 'label', 'nodes', 'depth'],

  data() {
    return { 
      showChildren: false
    }
  },

  name: 'tree-menu',

  computed: {
    indent() {
      return { transform: `translate(${this.depth * 15}px)` }
    }
  },

  methods: {
    toggleChildren() {
      this.showChildren = !this.showChildren;
    },

    isOpen(search){
      if((search == '' || search == null) && !this.showChildren){
        return false
      }
      else{
        //this.toggleChildren()
        return true
      }
    }
  },
}
</script>