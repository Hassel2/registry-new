<template>
  <div class="tree-menu">
    <div :style="indent"
    @click="GetChildren($props.id, $props.message)">
      <!-- <span v-if="this.$props.nodes">
        <i :class="showChildren || $root.search != '' ? 'bi bi-caret-down-fill' : 'bi bi-caret-right-fill'"></i>
      </span> -->

      {{ name }}
    </div>
    <tree-menu v-if="showChildren" 
    v-for="node in nodes" 
    :nodes="node.nodes" 
    :name="node.name" 
    :message="node.message"
    :id ="node.id"
    :depth="depth + 1"></tree-menu>
  </div>
</template>

<script>

export default { 
  props: ['name', 'nodes', 'id', 'message', 'depth'],

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
    GetChildren(id, message) {
      if(!this.showChildren){
        axios.get(`/api/rootCompany${id}/childs`)
        .then(res => {
          let companies = res.data.data

          if(message == "companies"){
            for(let i = 0; i < companies.length; i++){
              let Currentname = {name: companies[i].name, id: companies[i].id, message: res.data.message, nodes: []}
              this.nodes.push(Currentname)
            }
          }
        })
      }
      
      else{
        if(message != "root") this.nodes.length = []
      }

      this.showChildren = !this.showChildren;
    },

  },
}
</script>