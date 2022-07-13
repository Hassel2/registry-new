<template>
  <div class = "row">
      <input placeholder="ООО 'Зеленоглазое такси'" class = "col" v-model="$root.search"/>
      <button class="col-md-auto btn btn-warning">
        <i class="bi bi-search-heart" @click="GetSearch($root.search)"></i>
      </button>
  </div>
</template>

<script>
import { assertOptionalCallExpression } from '@babel/types';

export default {
    methods: {
        GetSearch(searchStr){
        axios.get(`/api/search=${searchStr}`)
          .then(res => {
            console.log(res.data.data);
            let companies = res.data.data.companies
            let LicenseAreas = res.data.data.licenseAreas
            
            this.$parent.$refs.tree.tree = {
                name: "Недропользователи",
                amount: "-",
                id: -1,
                nodes: [ ],
                message: "root",
            }

            for(let i = 0; i < companies.length; i++){
                let Currentname = {name: companies[i].name, amount: companies[i].amount, id: companies[i].id, message: "companies", nodes: []}
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