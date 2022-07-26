<template>
  <div class="container-fluid">
    <div class="row">
      <button class="col-md-auto btn btn-warning m-1" @click="isShowBar()">
        <i class="bi bi-arrow-bar-down" v-if="!ShowBar"></i>
        <i class="bi bi-arrow-bar-up" v-if="ShowBar"></i>
      </button>
      <input placeholder="ООО 'Зеленоглазое такси'" class="col m-1" v-model="$root.search" />
      <button class="col-md-auto btn btn-warning m-1" @click="StartSearch($root.search)">
        <i class="bi bi-search-heart"></i>
      </button>
    </div>

    <transition name="fade">
      <div class="row" v-show="ShowBar">
      <span class="col text-muted"> {{  Search  }}  </span>
      <button class="col-md-auto btn btn-warning btn-sm me-1" 
        v-if="addArrows" @click="ScrollUp()" title="Выбрать предыдущий элемент">
        <i class="bi bi-arrow-up-short"></i>
      </button>
      <button class="col-md-auto btn btn-warning btn-sm me-1" 
        v-if="addArrows" @click="ScrollDown()" title="Выбрать следующий элемент">
        <i class="bi bi-arrow-down-short"></i>
      </button>
      <button class="col-md-auto btn btn-warning btn-sm me-1" 
        v-if="addArrows" @click="DropSearch()" title="Очистить поиск">
        <i class="bi bi-trash-fill"></i>
      </button>
    </div>
    </transition>
  </div>
</template>

<script>

export default {
  data() {
    return {
      Search: "Введите значение для поиска",
      ShowBar: false,
      SearchCompanies: [],
      SearchCompaniesValue: 0,
      addArrows: false
    }
  },

  methods: {
    DropSearch(){

    },

    ScrollDown(){
      if(this.SearchCompaniesValue != this.SearchCompanies.length - 1) this.SearchCompaniesValue ++
      else this.SearchCompaniesValue = 0

      this.$parent.$refs.tree.$refs.menu_0.$el.querySelector(`[id="${this.SearchCompanies[this.SearchCompaniesValue]}"]`).scrollIntoView()
    },

    ScrollUp(){
      if(this.SearchCompaniesValue != 0) this.SearchCompaniesValue --
      else this.SearchCompaniesValue = this.SearchCompanies.length - 1

      this.$parent.$refs.tree.$refs.menu_0.$el.querySelector(`[id="${this.SearchCompanies[this.SearchCompaniesValue]}"]`).scrollIntoView()
    },

    isShowBar(){
      this.ShowBar = !this.ShowBar
    },

    StartSearch(searchStr){
      this.ShowBar = true
      if (searchStr == null || searchStr.length == 0)
      {
        this.Search =  "Введите значение для поиска"
        this.addArrows = false
      }
      else if (searchStr.length < 3)
      {
        this.Search =  "Ошибка заполнения"
        this.addArrows = false
      }
      else {
        this.Search = "Выполняется поиск..."
        this.GetSearch(searchStr)
        this.addArrows = true
      }
    },

    GetSearch(searchStr) {
      let parent = this.$parent.$refs.tree.$refs.menu_0
      if (!parent.showChildren) parent.GetChildren(0)

      axios.get(`/api/search=${searchStr}`)
        .then(res => {
          let companies = res.data.data
          console.log(companies)
          this.SearchCompanies = []
          this.SearchCompaniesValue = 0
          for (let i = 0; i < companies.length; i++) {
            this.SearchCompanies.push(companies[i][0])
            this.OpenNode(parent, companies[i], parent.$refs[`menu_${companies[i][companies[i].length - 1]}`])
          }
          console.log(this.SearchCompanies)
          this.Search = `Найдено ${companies.length} элементов`
        }
      )
    },

    OpenNode(parent, companies, currentRef) {
      console.log(currentRef);
      if (currentRef == null) return
      if (!currentRef[0].showChildren && companies.length - 1 == 1) currentRef[0].GetChildren(companies[companies.length - 1])

      if (companies.length - 1 > 0) {
        setTimeout(() => {
          companies.pop()
          this.OpenNode(parent, companies, currentRef[0].$refs[`menu_${companies[companies.length - 1]}`])
        }, 1000)
      }
      else currentRef[0].isLight = true
    }
  },
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
  opacity: 0;
}
</style>
