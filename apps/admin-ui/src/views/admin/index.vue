<script>
import { defineComponent } from "vue"
import SideBar from "./sidebar"
export default defineComponent({
  components: {
    SideBar
  }
})
</script>

<template>
  <div class="admin">
    <el-container>
      <SideBar />
      <el-container>
        <el-header class="header text-right">Header</el-header>
        <el-main>
          <keep-alive>
            <router-view />
          </keep-alive>
        </el-main>
        <el-footer class="pd-0">
          <div class="text-center pt-15">
            @2021 - nhóm 1 - Website quản lý tiêm chủng vắc-xin
          </div>
        </el-footer>
      </el-container>
    </el-container>
  </div>
</template>

<style scoped>
.el-footer {
  border-top: 1px solid #ddd;
  --el-footer-height: 50px;
}
</style>
