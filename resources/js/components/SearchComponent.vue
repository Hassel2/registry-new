<template>
  <div>
      <input v-model="$root.search"/>
      <button class="btn btn-warning">
        <i class="bi bi-search-heart" @click="GetSearch($root.search)"></i>
      </button>
  </div>
</template>

<script>
export default {
    methods: {
        GetSearch(searchStr){
        axios.get(`/api/search=${searchStr}`)
          .then(res => {
            // console.log(res.data.data);
            let companies = res.data.data.companies
            let LicenseAreas = res.data.data.LicenseAreas
            
            this.$parent.$refs.tree.tree = {
                name: "Недропользователи",
                id: -1,
                nodes: [ ],
                message: "root",
            }
            //console.log(this.$parent.$refs.tree.tree);

            for(let i = 0; i < companies.length; i++){
                let Currentname = {name: companies[i].name, id: companies[i].id, message: "companies", nodes: []}
                this.$parent.$refs.tree.tree.nodes.push(Currentname)
            }

            for(let i = 0; i < LicenseAreas.length; i++){
                let Currentname = {name: LicenseAreas[i].name, id: LicenseAreas[i].id, message: "licenseAreas", nodes: []}
                this.$parent.$refs.tree.tree.nodes.push(Currentname)
            }

          })
        }
    },
}
</script>

<style>

</style>