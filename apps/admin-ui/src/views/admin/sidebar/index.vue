<script>
import { defineComponent, ref } from "vue"
import useConfigSideBar from "../useSideBar.ts"
import SideBarItem from "./components/SideBarItem.vue"
import { Icon } from "@iconify/vue"
export default defineComponent({
  components: {
    SideBarItem,
    Icon
  },
  setup() {
    const isOpenSidebar = ref(false)
    const { sidebar } = useConfigSideBar()
    const handleChangeOpenSideBar = () => {
      isOpenSidebar.value = !isOpenSidebar.value
    }
    return {
      isOpenSidebar,
      handleChangeOpenSideBar,
      sidebar
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
      default-active="1"
      class="aside-menu bg-11385e el-menu-vertical-demo"
      :collapse="isOpenSidebar"
    >
      <SideBarItem v-for="item in sidebar" :key="item.key" :item="item" />
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
