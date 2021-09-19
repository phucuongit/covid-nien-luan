<script>
import { defineComponent, provide, ref } from "vue"
import useUsers from "./useUsers.ts"
import AddUser from "./addUser/index.vue"
import AddAdmin from "./addAdmin/index.vue"
import DeleteUser from "./deleteUser/index.vue"
import DetailUser from "./detailUser/index.vue"
import moment from "moment"

export default defineComponent({
  components: {
    AddUser,
    DeleteUser,
    AddAdmin,
    DetailUser
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

    const isVisibleAddUser = ref(false)
    const isVisibleAddAdmin = ref(false)
    const isVisibleDelete = ref(false)
    const isVisibleDetailUser = ref(false)
    const mode = ref()

    const currentPage = ref(1)
    const handleChangePage = (page) => {
      currentPage.value = page
      getListUsers(currentPage.value)
    }

    const setMode = (value) => {
      mode.value = value
    }

    const changeDetailUser = () => {
      isVisibleDetailUser.value = !isVisibleDetailUser.value
    }

    const changeAddUser = () => {
      isVisibleAddUser.value = !isVisibleAddUser.value
    }

    const changeAddAdmin = () => {
      isVisibleAddAdmin.value = !isVisibleAddAdmin.value
    }

    const changeUpdate = () => {
      setMode("update")
      if (multipleSelection.value[0].role.name == "admin") {
        changeAddAdmin()
      } else {
        changeAddUser()
      }
    }

    const changeDelete = () => {
      isVisibleDelete.value = !isVisibleDelete.value
    }

    provide("currentPage", currentPage)
    provide("getListUsers", getListUsers)
    provide("closeAddUserModal", changeAddUser)
    provide("closeAddAdminModal", changeAddAdmin)
    provide("closeUserDelete", changeDelete)
    provide("setMode", setMode)
    provide("closeDetailUser", changeDetailUser)

    return {
      data,
      loadingListUser,
      multipleSelection,
      handleSelectionChange,
      handleSearch,
      handleChangePage,
      textSearch,
      isVisibleDetailUser,
      isVisibleAddAdmin,
      isVisibleAddUser,
      isVisibleDelete,
      totalPage,
      currentPage,
      changeAddUser,
      changeAddAdmin,
      changeUpdate,
      changeDelete,
      changeDetailUser,
      mode
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
            @click="changeDetailUser"
            v-if="multipleSelection.length == 1"
          >
            <i class="el-icon-view"> </i>
            Xem
          </el-button>
          <el-button
            size="small"
            type="primary"
            class="text-white"
            @click="changeAddUser"
          >
            <i class="el-icon-plus"></i>
            Thêm User
          </el-button>

          <el-button
            size="small"
            type="primary"
            class="text-white"
            @click="changeAddAdmin"
          >
            <i class="el-icon-plus"></i>
            Thêm Admin
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
            @click="changeDelete"
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
      max-height="400"
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

      <el-table-column
        property="identity_card"
        label="CMND / CCCD"
        width="180"
      ></el-table-column>

      <el-table-column
        property="phone"
        label="Số điện thoại"
        width="180"
      ></el-table-column>
      <el-table-column
        property="social_insurance"
        label="Bảo hiểm y tế"
        width="150"
      ></el-table-column>
      <el-table-column label="Giới tính" width="120">
        <template #default="scope">
          {{ scope.row.gender ? "Nam" : "Nữ" }}
        </template>
      </el-table-column>
      <el-table-column label="Ngày sinh" width="200">
        <template #default="scope">
          {{ formatDate(scope.row.birthday) }}
        </template>
      </el-table-column>
      <el-table-column label="Địa chỉ" width="200">
        <template #default="scope">
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

  <DetailUser :isVisible="isVisibleDetailUser" :selectUser="multipleSelection">
  </DetailUser>

  <AddUser
    :isVisible="isVisibleAddUser"
    :mode="mode"
    :selectUser="multipleSelection"
  >
  </AddUser>
  <AddAdmin
    :isVisible="isVisibleAddAdmin"
    :mode="mode"
    :selectUser="multipleSelection"
  >
  </AddAdmin>
  <DeleteUser :isVisible="isVisibleDelete" :selectUser="multipleSelection">
  </DeleteUser>
</template>

<style scoped>
.el-table {
  --el-table-header-background-color: #11385e;
  --el-table-header-font-color: #fff;
}
</style>
