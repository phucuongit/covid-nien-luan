<script lang="ts">
import { defineComponent, computed, ref } from "vue"
import { Icon } from "@iconify/vue"
import useHome from "./hooks/useHome"
import Dashboard from "./Dashboard/index.vue"
import moment from "moment"
export default defineComponent({
  components: {
    Icon,
    Dashboard
  },
  setup() {
    const textSearch = ref()
    const { isLoadingSearch, search, dataSearch } = useHome()

    const handleSearch = async () => {
      if (textSearch.value) {
        await search(textSearch.value)
        window.scrollTo(0, document.body.scrollHeight)
      }
    }

    const handleMoveToSearch = () => {
      window.scrollTo(0, document.body.scrollHeight)
    }
    return {
      handleSearch,
      isLoadingSearch,
      dataSearch,
      textSearch,
      handleMoveToSearch
    }
  },
  methods: {
    formatBirthday(date: Date) {
      return moment(date).format("DD-MM-YYYY")
    },
    formatDate(date: Date) {
      return moment(date).format("HH:MM, DD-MM-YYYY")
    }
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
                <Icon
                  icon="carbon:manage-protection"
                  color="white"
                  width="25px"
                />
                <b class="pl-15">Quản lý xét nghiệm & tiêm phòng Covid-19</b>
              </div>
            </el-col>
            <el-col :md="6" :sm="12" :xs="24">
              <div class="header-right" @click="handleMoveToSearch">
                <Icon
                  icon="fluent:search-shield-20-filled"
                  color="white"
                  width="25px"
                />
                <b style="color: #fff">Tra cứu</b>
              </div>
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
              <Dashboard />

              <div class="search pt-20">
                <h3 class="text-11385e mb-0">
                  Tra cứu kết quả xét nghiệm & tiêm chủng
                </h3>
                <el-row>
                  <el-col :md="12" :sm="12" :xs="24">
                    <el-input
                      placeholder="Tìm kiếm theo CMND, bảo hiểm, số điện thoại..."
                      prefix-icon="el-icon-search"
                      v-model="textSearch"
                      v-on:keyup="checkEmptyText"
                    >
                      <template #append>
                        <el-button
                          type="primary"
                          icon="el-icon-search"
                          class="btn-search"
                          @click="handleSearch"
                          :loading="isLoadingSearch"
                          :disabled="isLoadingSearch"
                        >
                        </el-button>
                      </template>
                    </el-input>
                  </el-col>
                </el-row>
              </div>

              <el-row :gutter="20" v-if="dataSearch">
                <el-col :md="8" :sm="12" :xs="24">
                  <el-card shadow="hover">
                    <template #header>
                      <b>Thông tin</b>
                    </template>
                    <el-form label-position="left" label-width="100px">
                      <el-form-item label="Họ tên:">
                        {{ dataSearch?.fullname }}
                      </el-form-item>
                      <el-form-item label="Ngày sinh:">
                        {{ formatBirthday(dataSearch?.birthday) }}
                      </el-form-item>
                      <el-form-item label="Giới tính:">
                        {{ dataSearch?.gender ? "Nam" : "Nữ" }}
                      </el-form-item>
                      <el-form-item label="Địa chỉ:">
                        {{ dataSearch?.address }} -
                        {{ dataSearch?.address_full?.village?.name }} -
                        {{ dataSearch?.address_full?.district?.name }} -
                        {{ dataSearch?.address_full?.province?.name }}
                      </el-form-item>
                    </el-form>
                  </el-card>
                </el-col>

                <el-col :md="8" :sm="12" :xs="24">
                  <el-card shadow="hover">
                    <template #header>
                      <b>Lịch sử xét nghiệm</b>
                    </template>

                    <el-table
                      :data="dataSearch?.result_tests"
                      style="width: 100%"
                    >
                      <el-table-column label="Thời gian">
                        <template #default="scope">
                          {{ formatDate(scope?.row?.created_at) }}
                        </template>
                      </el-table-column>
                      <el-table-column label="Kết quả">
                        <template #default="scope">
                          <span v-if="scope?.row?.status == 'negative'"
                            >Âm tính</span
                          >
                          <span v-else class="text-red">Dương tính</span>
                        </template>
                      </el-table-column>
                    </el-table>
                  </el-card>
                </el-col>

                <el-col :md="8" :sm="12" :xs="24">
                  <el-card shadow="hover">
                    <template #header>
                      <b>Lịch sử tiêm chủng</b>
                    </template>

                    <el-table
                      :data="dataSearch?.vaccinations"
                      style="width: 100%"
                    >
                      <el-table-column label="Thời gian">
                        <template #default="scope">
                          {{ formatDate(scope?.row?.created_at) }}
                        </template>
                      </el-table-column>
                      <el-table-column label="Loại vắc-xin">
                        <template #default="scope">
                          {{ scope?.row?.vaccine_type?.name }}
                        </template>
                      </el-table-column>
                    </el-table>
                  </el-card>
                </el-col>
              </el-row>
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

<style lang="scss" scoped>
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
