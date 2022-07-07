<template>
  <div>
    <div class="container">
      <tree-menu
        ref="menu"
        :name="tree.name"
        :amount="tree.amount"
        :nodes="tree.nodes"
        :message="tree.message"
        :id="-1"
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
        id: -1,
        nodes: [ ],
        message: "root",
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
            let Currentname = {name: companies[i].name, amount: companies[i].amount , id: companies[i].id, message: res.data.message, nodes: []}
            this.tree.nodes.push(Currentname)
          }
        })
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
  width: clamp(40%, 300px);
  height: 560px;
  overflow: auto;
}
</style>
