<template>
  <div class="tree-menu">
    <div :style="indent"
    :class="label.includes($root.search) && $root.search != '' ? 'text-primary' : ''"
    @click="toggleChildren">
      <!-- <span v-if="this.$props.nodes">[{{ showChildren ? '-' : '+' }}]</span>  -->
      <span v-if="this.$props.nodes">
        <i :class="showChildren || $root.search != '' ? 'bi bi-caret-down-fill' : 'bi bi-caret-right-fill'"></i>
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