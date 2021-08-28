<template>
  <div class="aside">
    <el-menu
      default-active="1"
      class="el-menu-vertical-demo aside-menu bg-11385e"
      @open="handleOpen"
      @close="handleClose"
      :collapse="isCollapse"
    >
      <div class="aside-menu-icon pd-10 pd-25 text-right">
        <i class="el-icon-d-arrow-right" @click="changeState"></i>
      </div>

      <el-menu-item index="1">
        <router-link :to="{ name: 'AdminHome' }">
          <i class="el-icon-s-home"></i>
          <span>Trang chủ</span>
        </router-link>
      </el-menu-item>

      <el-menu-item index="2">
        <router-link :to="{ name: 'PopulateManager' }">
          <i class="el-icon-s-management"></i>
          <span>Quản lý dân cư</span>
        </router-link>
      </el-menu-item>

      <el-menu-item index="3">
        <router-link :to="{ name: 'PopulateManager' }">
          <i class="el-icon-document-add"></i>
          <span>Thêm lịch sử tiêm</span>
        </router-link>
      </el-menu-item>

      <el-menu-item index="4">
        <router-link :to="{ name: 'PopulateManager' }">
          <i class="el-icon-tickets"></i>
          <span>Thống kê</span>
        </router-link>
      </el-menu-item>

      <el-menu-item index="5">
        <router-link :to="{ name: 'Vaccine' }">
          <i class="el-icon-s-order"></i>
          <span>Vắc-xin</span>
        </router-link>
      </el-menu-item>

      <el-submenu index="6">
        <template #title>
          <div>
            <i class="el-icon-s-tools"></i>
            <span>Cài đặt</span>
          </div>
        </template>
        <el-menu-item-group>
          <el-menu-item index="6-1">
            <router-link :to="{ name: 'PopulateManager' }">
              <i class="el-icon-s-custom"></i>
              <span>Tài khoản</span>
            </router-link>
          </el-menu-item>

          <el-menu-item index="6-2">
            <router-link :to="{ name: 'PopulateManager' }">
              <i class="el-icon-s-custom"></i>
              <span>Người dùng</span>
            </router-link>
          </el-menu-item>
        </el-menu-item-group>
      </el-submenu>
    </el-menu>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue"

export default defineComponent({
  setup() {
    const isCollapse = ref(false)
    const handleOpen = (key, keyPath) => {
      console.log(key, keyPath)
    }
    const handleClose = (key, keyPath) => {
      console.log(key, keyPath)
    }

    const changeState = () => {
      isCollapse.value = !isCollapse.value
    }
    return {
      changeState,
      isCollapse,
      handleOpen,
      handleClose
    }
  }
})
</script>

<style scoped>
.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: 200px;
  min-height: 400px;
  text-align: left;
}
</style>
