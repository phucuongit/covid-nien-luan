<script>
import { defineComponent, onMounted, ref, watch } from "vue"
import API from "../../services"
import router from "@/router"
import SideBar from "./sidebar"
import { useStore } from "vuex"
import useBaseUrl from "@/services/baseUrl.ts"
import { ElMessageBox } from "element-plus"
import { Icon } from "@iconify/vue"
export default defineComponent({
  components: {
    SideBar,
    Icon
  },
  setup() {
    const store = useStore()
    const dataUser = ref()
    const avatar = ref([])
    const { BASE_URL } = useBaseUrl()
    onMounted(async () => {
      try {
        const response = await API.get("profile") // Call api get account login
        if (response.data.success) {
          store.dispatch("setUser", response.data.data)
          dataUser.value = response.data.data
        }
      } catch (e) {
        console.log(e)
        router.push("/admin/login")
        localStorage.setItem("token", "")
      }
    })

    watch(store.state, () => {
      avatar.value = []
      if (store.state.user?.images[0]?.url) {
        avatar.value.push(BASE_URL + store.state.user.images[0].url)
      }
    })

    const handleLogOut = () => {
      ElMessageBox({
        type: "warning",
        title: "Thông báo",
        message: "Bạn muốn đăng xuất?",
        showCancelButton: true,
        cancelButtonText: "Hủy",
        confirmButtonText: "Đăng xuất"
      }).then(async () => {
        localStorage.setItem("token", "")
        router.push("/admin/login")
      })
    }
    return {
      dataUser,
      avatar,
      handleLogOut
    }
  },
  computed: {
    key() {
      return this.$route.path
    }
  }
})
</script>

<template>
  <div class="admin">
    <el-container>
      <SideBar />
      <el-container class="section-right">
        <el-header class="header text-right">
          <el-popover placement="bottom-start" :width="250" trigger="hover">
            <el-row :gutter="30">
              <el-col :span="6">
                <el-image
                  style="width: 55px; height: 55px"
                  fit="cover"
                  :src="avatar[0]"
                >
                  <template #error>
                    <div class="image-slot">
                      <i class="el-icon-picture-outline"></i>
                    </div>
                  </template>
                </el-image>
              </el-col>
              <el-col :span="18" class="mt-10">
                <b>{{ dataUser?.fullname }}</b>
                <div>{{ dataUser?.username }}</div>
              </el-col>
            </el-row>

            <ul class="header-ul">
              <li>
                <router-link :to="{ name: 'Account' }">
                  <Icon
                    icon="ic:sharp-account-circle"
                    color="#11385e"
                    width="20"
                    height="17"
                  />
                  <span>Tài khoản</span>
                </router-link>
              </li>
              <li @click="handleLogOut">
                <Icon icon="ls:logout" color="#11385e" width="20" height="12" />
                Đăng xuất
              </li>
            </ul>
            <template #reference>
              <span class="header-avt">
                <el-image
                  style="width: 45px; height: 45px"
                  fit="cover"
                  :src="avatar[0]"
                  :preview-src-list="avatar"
                >
                  <template #error>
                    <div class="image-slot">
                      <i class="el-icon-picture-outline"></i>
                    </div>
                  </template>
                </el-image>
              </span>
            </template>
          </el-popover>
        </el-header>
        <el-main>
          <router-view :key="key" />
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

<style scoped>
.el-image {
  border: 1px solid #ddd;
  border-radius: 50%;
}

.admin .header {
  border-bottom: 1px solid #11385e;
  height: 55px;
  padding: 5px 10px;
  position: sticky;
  top: 0;
  z-index: 100;
  background: #f2f2f2;
}

.header-ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.header-ul li {
  padding: 10px;
  cursor: pointer;
  color: black;
}

.header-ul li a {
  display: flex;
  align-items: center;
  width: 100%;
  height: 100%;
  color: black;
}

.header-ul li a:active {
  color: black;
}

.header-ul li:hover {
  color: #11385e;
  background: #f4f4f4;
}
</style>
