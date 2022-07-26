<template>
  <div>
    <div class="container">
      <tree-menu
        :ref="`menu_${tree.id}`"
        :name="tree.name"
        :amount="tree.amount"
        :nodes="tree.nodes"
        :id="tree.id"
        :depth="0">
      </tree-menu>
    </div>
  </div>
</template>

<script>
import TreeMenu from "./TreeMenu";

export default {
  data() {
    return {
      tree: {
        name: "Недропользователи", 
        amount: "-", 
        nodes: [ ], 
        id: 0,
        light: false
      },
    };
  },

  mounted(){
    this.GetCompanies()
  },

  methods: {
    GetCompanies(){
      axios.get('/api/rootCompany')
        .then(res => {
          let companies = res.data.data

          for(let i = 0; i < companies.length; i++){
            let Currentname = {
              name: companies[i].name, 
              amount: companies[i].amount, 
              nodes: [], 
              id: companies[i].id,
              light: false
            }
            this.tree.nodes.push(Currentname)
          }
        })

      this.$refs.menu_0.showChildren = true
    },
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
  height: 550px;
  overflow: auto;
}
</style>
