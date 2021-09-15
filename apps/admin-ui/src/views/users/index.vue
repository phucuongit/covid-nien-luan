<script>
import { defineComponent, provide, ref } from "vue"
import useUsers from "./useUsers.ts"
import AddUpdateUser from "./addUpdateUser/index.vue"
import moment from "moment"

export default defineComponent({
  components: {
    AddUpdateUser
  },
  setup() {
    const {
      data,
      loadingListUser,
      getListUsers,
      getListUsersSearch,
      totalPage
    } = useUsers()
    const multipleSelection = ref([])
    const handleSelectionChange = (value) => {
      multipleSelection.value = value
    }
    const textSearch = ref("")
    const handleSearch = () => {
      getListUsersSearch(textSearch.value)
    }
    getListUsers(1)

    const isVisibleAddUpdate = ref(false)
    const handleVisibleAddUpdate = () => {
      isVisibleAddUpdate.value = !isVisibleAddUpdate.value
    }

    const mode = ref("")
    const setMode = (str) => {
      mode.value = str
    }
    const currentPage = ref(1)
    const handleChangePage = (page) => {
      currentPage.value = page
      getListUsers(currentPage.value)
    }

    const changeAdd = () => {
      setMode("add")
      handleVisibleAddUpdate()
    }

    const changeUpdate = () => {
      setMode("update")
      handleVisibleAddUpdate()
    }

    provide("setMode", setMode)
    provide("currentPage", currentPage)
    provide("getListUsers", getListUsers)
    provide("closeAddUpdateUserModal", handleVisibleAddUpdate)

    return {
      data,
      loadingListUser,
      multipleSelection,
      handleSelectionChange,
      handleSearch,
      textSearch,
      handleVisibleAddUpdate,
      isVisibleAddUpdate,
      setMode,
      mode,
      totalPage,
      currentPage,
      handleChangePage,
      changeAdd,
      changeUpdate
    }
  },
  methods: {
    formatDate(date) {
      return moment(date).format("DD/MM/YYYY")
    }
  }
})
</script>

<template>
  <div id="users-list">
    <h3 class="mr-0">Danh sách các loại vắc-xin</h3>
    <el-row class="row-bg mb-10">
      <el-col :span="12">
        <div class="grid-content">
          <el-input
            placeholder="Tìm kiếm theo tên..."
            prefix-icon="el-icon-search"
            v-model="textSearch"
            v-on:keypress.enter="handleSearch"
          >
          </el-input>
        </div>
      </el-col>
      <el-col :span="12">
        <div class="grid-content text-right pt-10">
          <el-button
            size="small"
            type="primary"
            class="text-white"
            @click="changeAdd"
          >
            <i class="el-icon-plus"></i>
            Thêm
          </el-button>

          <el-button
            size="small"
            type="primary"
            class="text-white"
            v-if="multipleSelection.length == 1"
            @click="changeUpdate"
          >
            <i class="el-icon-edit"></i>
            Sửa
          </el-button>

          <el-button
            size="small"
            type="danger"
            class="text-white"
            v-if="multipleSelection.length > 0"
          >
            <i class="el-icon-delete"></i>
            Xóa
          </el-button>
        </div>
      </el-col>
    </el-row>

    <el-table
      :data="data"
      ref="multipleTable"
      style="width: 100%"
      max-height="480"
      stripe
      border
      @selection-change="handleSelectionChange"
      v-loading="loadingListUser"
      element-loading-text="Loading..."
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.5)"
    >
      <el-table-column fixed type="selection" width="40"> </el-table-column>
      <el-table-column
        property="fullname"
        label="Họ tên"
        width="200"
      ></el-table-column>
      <el-table-column label="Tên đăng nhập" width="200">
        <template #default="scope">
          {{ scope.row.role.name == "admin" ? scope.row.username : "" }}
        </template>
      </el-table-column>
      <el-table-column
        property="identity_card"
        label="CMND"
        width="150"
      ></el-table-column>
      <el-table-column
        property="phone"
        label="Số điện thoại"
        width="150"
      ></el-table-column>
      <el-table-column
        property="social_insurance"
        label="Bảo hiểm y tế"
        width="150"
      ></el-table-column>
      <el-table-column label="Giới tính" width="80">
        <template #default="scope">
          {{ scope.row.gender ? "Nam" : "Nữ" }}
        </template>
      </el-table-column>
      <el-table-column label="Ngày sinh" width="150">
        <template #default="scope">
          {{ formatDate(scope.row.birthday) }}
        </template>
      </el-table-column>
      <el-table-column label="Địa chỉ" width="500">
        <template #default="scope">
          {{ scope.row.address }} - {{ scope.row.address_full.village.name }} -
          {{ scope.row.address_full.district.name }} -
          {{ scope.row.address_full.province.name }}
        </template>
      </el-table-column>
    </el-table>

    <el-pagination
      background
      class="text-center mt-20"
      layout="prev, pager, next"
      :total="totalPage * 10"
      :current-page="currentPage"
      @current-change="handleChangePage"
    >
    </el-pagination>
  </div>

  <AddUpdateUser
    :mode="mode"
    :isVisible="isVisibleAddUpdate"
    :selectUser="multipleSelection"
  >
  </AddUpdateUser>
</template>

<style scoped>
.el-table {
  --el-table-header-background-color: #11385e;
  --el-table-header-font-color: #fff;
}
</style>
