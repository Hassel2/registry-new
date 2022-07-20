<template>
  <div class = "container">
    <div class = "row">
      <input placeholder="ООО 'Зеленоглазое такси'" class = "col" v-model="$root.search"/>
      <button class="col-md-auto btn btn-warning">
        <i class="bi bi-search-heart" @click="GetSearch($root.search)"></i>
      </button>
    </div>

    <!-- <div class = "row" v-if="isSearch">
      <input placeholder="1 из 3" class = "col" v-model="$root.search"/>
      <button class="col-md-auto btn btn-warning btn-sm m-1">
        <i class="bi bi-arrow-up-short"></i>
      </button>
      <button class="col-md-auto btn btn-warning btn-sm m-1">
        <i class="bi bi-arrow-down-short"></i>
      </button>
    </div>

    <div class = "row" v-else>
      <span class="text-danger">Ошибка заполнения</span>
    </div> -->
  </div>
</template>

<script>
import { assertOptionalCallExpression } from '@babel/types';
import TreeMenuVue from './TreeMenu.vue';

export default {
    data(){
      return{
        isSearch: false
      }
    },

    methods: {
      GetSearch(searchStr){
        let parent = this.$parent.$refs.tree.$refs.menu_0
        if(!parent.showChildren) parent.GetChildren(0, '-')
        
        axios.get(`/api/search=${searchStr}`)
          .then(res => {
            let companies = res.data.data
            console.log(parent.$refs['menu_66'])
            for(let i = 0; i < companies.length; i++){              
              this.OpenNode(parent, companies[i], parent.$refs[`menu_${companies[i][companies[i].length-1]}`])
            }
          })
      },

      OpenNode(parent, companies, currentRef){
        console.log(companies)
        // console.log(currentRef)
        
        if(currentRef == null) return
        if(!currentRef[0].showChildren) currentRef[0].GetChildren(companies[companies.length-1], 'companies')
        currentRef[0].isLight = true
        
        setTimeout( () => { 
          if(companies.length - 1 > 0) {
            companies.pop()
            console.log(companies)
            // console.log(companies[companies.length - 1])
            this.OpenNode(parent, companies, currentRef[0].$refs[`menu_${companies[companies.length - 1]}`])
          }
        }, 4000)
        
      }
    },
}
</script>

<style>

</style>