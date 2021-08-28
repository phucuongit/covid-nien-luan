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
      <el-aside width="200px">
        <SideBar />
      </el-aside>
      <el-container>
        <el-header class="header text-right">Header</el-header>
        <el-main>
          <router-view></router-view>
        </el-main>
        <el-footer>Footer</el-footer>
      </el-container>
    </el-container>
  </div>
</template>
