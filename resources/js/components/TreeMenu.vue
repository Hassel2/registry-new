<template>
  <div class="tree-menu">
    <div 
      class = "name" 
      :style="indent"
      @click="GetChildren($props.id, $props.message)"
      v-bind:title="this.$props.name"
      @mouseover="isHovering = true" 
      @mouseleave="isHovering = false"
      :class="{hovering: isHovering, lightning: isLight}">
      <span v-if="amount != 0 & amount != null">
        <i class="text-warning" :class="showChildren ? 'bi bi-caret-down-fill' : 'bi bi-caret-right-fill'"></i>
      </span>
      <span>
        {{ name }}
      </span> 
    </div>
    <tree-menu 
      :ref="`menu_${node.id}`"
      v-if="showChildren" 
      v-for="node in nodes" 
      :name="node.name" 
      :amount="node.amount" 
      :nodes="node.nodes" 
      :message="node.message" 
      :id ="node.id" 
      :light="false"
      :depth="depth + 1">
    </tree-menu>
  </div>
</template>

<script>

export default { 
  props: ['name', 'amount', 'nodes', 'message', 'id', 'depth'],

  data() {
    return { 
      showChildren: false,
      isHovering: false,
      isLight: false,
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
              let Currentname = {
                name: companies[i].name, 
                amount: companies[i].amount, 
                nodes: [], 
                message: res.data.message, 
                id: companies[i].id,
                light: false
              }
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

<style scoped>
.name{
  white-space: nowrap;
}
.hovering{
  background-color: rgba(98, 197, 255, 0.5);
}
.lightning{
  background-color:coral
}
</style>