<template>
  <div>
    <div class="container">
      <tree-menu
        ref="menu"
        :company="tree.company"
        :nodes="tree.nodes"
        :id="0"
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
        company: "Недропользователи",
        id: -1,
        nodes: [ ],
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
            let CurrentCompany = {company: companies[i].company, id: companies[i].id, nodes: []}
            this.tree.nodes.push(CurrentCompany)
          }
        })
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
