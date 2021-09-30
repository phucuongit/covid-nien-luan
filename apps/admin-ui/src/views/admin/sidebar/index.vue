<script lang="ts">
import {
  defineComponent,
  ref,
  onMounted,
  getTransitionRawChildren,
  watch
} from "vue"
import useConfigSideBar from "../useSideBar"
import SideBarItem from "./components/SideBarItem.vue"
import { Icon } from "@iconify/vue"
import router from "../../../router"

export default defineComponent({
  components: {
    SideBarItem,
    Icon
  },
  setup() {
    const isOpenSidebar = ref(false)
    const numberDefault = ref("1")
    const currentUrl = ref()
    const { sidebar } = useConfigSideBar()
    const handleChangeOpenSideBar = () => {
      isOpenSidebar.value = !isOpenSidebar.value
    }
    onMounted(() => {
      getNumberDefault()
    })

    const getNumberDefault = () => {
      currentUrl.value = router.currentRoute.value.name
      sidebar.map((item) => {
        if (item?.children) {
          item?.children.map((childItem) => {
            if (childItem.link == currentUrl.value) {
              numberDefault.value = item.key + "-" + childItem.key
              return
            }
          })
        }
        if (item.link == currentUrl.value) {
          numberDefault.value = item.key
          return
        }
      })
    }

    watch(router.currentRoute, () => {
      getNumberDefault()
    })

    return {
      isOpenSidebar,
      handleChangeOpenSideBar,
      sidebar,
      numberDefault
    }
  }
})
</script>

<template>
  <div class="aside">
    <div class="icon-change-open-sidebar text-right pr-20">
      <Icon
        icon="bx:bx-chevrons-right"
        color="white"
        width="30"
        @click="handleChangeOpenSideBar"
      />
    </div>
    <el-menu
      :default-active="numberDefault"
      class="aside-menu bg-11385e el-menu-vertical-demo"
      :collapse="isOpenSidebar"
    >
      <SideBarItem v-for="item in sidebar" :key="item?.key" :item="item" />
    </el-menu>
  </div>
</template>

<style scoped>
.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 400px;
  text-align: left;
}
</style>
