<script>
import { defineComponent, onMounted, ref } from "vue"
import API from "../../services"
import router from "@/router"
import SideBar from "./sidebar"
import { useStore } from "vuex"
export default defineComponent({
  components: {
    SideBar
  },
  setup() {
    const store = useStore()
    onMounted(async () => {
      try {
        const response = await API.get("profile") // Call api get account login
        if (response.data.success) {
          store.dispatch("setUser", response.data.data)
        }
      } catch (e) {
        console.log(e)
        router.push("/admin/login")
        localStorage.setItem("token", "")
      }
    })
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
            @2021 - nhóm 1 - Website quản lý xét nghiệm và tiêm phòng Covid-19
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
