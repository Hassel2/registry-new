<template>
  <div class="tree-menu">
    <div :style="indent"
    @click="GetChildren($props.id)">
      <!-- <span v-if="this.$props.nodes">
        <i :class="showChildren || $root.search != '' ? 'bi bi-caret-down-fill' : 'bi bi-caret-right-fill'"></i>
      </span> -->

      {{ company }}
    </div>
    <tree-menu v-if="showChildren" 
    v-for="node in nodes" 
    :nodes="node.nodes" 
    :company="node.company" 
    :id ="node.id"
    :depth="depth + 1"></tree-menu>
  </div>
</template>

<script>

export default { 
  props: ['company', 'nodes', 'id', 'depth'],

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
    GetChildren(id) {
      if(!this.showChildren){
        axios.get(`/api/rootCompany${id}/childs`)
        .then(res => {
          let companies = res.data.data

          for(let i = 0; i < companies.length; i++){
            let CurrentCompany = {company: companies[i].company, id: companies[i].id, nodes: []}
            this.nodes.push(CurrentCompany)
          }

        })
      }
      
      else{
        this.nodes.length = 0
      }

      this.showChildren = !this.showChildren;
    },

  },
}
</script>