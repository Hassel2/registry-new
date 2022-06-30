<template>
  <div>
    <div class="container">
      <tree-menu
        ref="menu"
        :label="tree.label"
        :nodes="tree.nodes"
        :depth="0"
      ></tree-menu>
    </div>
  </div>
</template>

<script>
import TreeMenu from "./TreeMenu";

export default {
  data() {
    return {
      // tree: {
      //   label: "root",
      //   nodes: [ ],
      // },
      tree: null
    };
  },

  mounted(){
    this.GetCompanies()
  },

  methods: {
    GetCompanies(){
      axios.get('/api/rootCompany')
        .then(res => {
          this.tree = res.data
      })
      console.log(this.tree);
    }
  },

  components: {
    TreeMenu
  },

};
</script>

<style scoped>
/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.container {
  width: clamp(40%, 300px);
  height: 560px;
  overflow: auto;
}
</style>
