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
        <el-footer>Footer</el-footer>
      </el-container>
    </el-container>
  </div>
</template>
