<script lang="ts">
import { defineComponent } from "vue"
import Dashboard from "./Dashboard/index.vue"
import { Icon } from "@iconify/vue"
export default defineComponent({
  components: {
    Dashboard,
    Icon
  }
})
</script>

<template>
  <div class="home">
    <el-container>
      <el-header class="bg-11385e">
        <div class="header container">
          <el-row :gutter="20">
            <el-col :md="18" :sm="12" :xs="24">
              <div class="header-left">
                <router-link :to="{ name: 'Home' }">
                  <span title="Trang chủ">
                    <Icon icon="fa-solid:home" color="white" width="25" />
                  </span>
                </router-link>
                <b class="pl-15">Quản lý xét nghiệm & tiêm phòng Covid-19</b>
              </div>
            </el-col>
            <el-col :md="6" :sm="12" :xs="24">
              <router-link :to="{ name: 'Search' }">
                <div class="header-right" title="Tìm kiếm">
                  <Icon
                    icon="fluent:search-shield-20-filled"
                    color="white"
                    width="25px"
                  />
                  <b style="color: #fff">Tra cứu</b>
                </div>
              </router-link>
            </el-col>
          </el-row>
        </div>
      </el-header>
      <el-main>
        <div class="main">
          <el-row v-if="isLoading">
            <el-skeleton :rows="6" animated />
          </el-row>
          <div v-else>
            <div class="container">
              <router-view />
              <Dashboard />
            </div>
          </div>
        </div>
      </el-main>
      <el-footer class="pd-0">
        <div class="text-center pt-15">
          @2021 - nhóm 1 - Website quản lý xét nghiệm và tiêm phòng Covid-19
        </div>
      </el-footer>
    </el-container>
  </div>
</template>

<style scoped lang="scss">
$blueColor: #11385e;
.home {
  .el-header {
    position: sticky;
    top: 0;
    z-index: 100;
    height: auto;
    box-shadow: 0px 10px 8px 2px #fafafa;
  }
  .main {
    min-height: 650px;
    .el-card__header {
      b {
        color: #fff;
      }
    }

    .el-col {
      margin-top: 20px;
    }
  }
  .header {
    padding: 18px 0px;

    .header-left,
    .header-right {
      color: white;
      font-size: 18px;
      font-weight: bold;
      display: flex;
      align-items: center;
    }
    .header-right {
      justify-content: end;
      cursor: pointer;
    }
  }

  .el-footer {
    border-top: 1px solid #ddd;
    --el-footer-height: auto;
    position: sticky;
    bottom: 0px;
    background: #fff;
    z-index: 10;
    padding: 0px 10px 10px;
    color: $blueColor;
    font-weight: bold;
  }
}

:global(.el-card__header) {
  background: $blueColor;
}

:global(.el-input-group__append) {
  background-color: $blueColor !important;
}
</style>
