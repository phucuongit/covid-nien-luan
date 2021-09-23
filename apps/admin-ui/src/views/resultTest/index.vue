<script>
import { defineComponent, provide, ref } from "vue"
import useResultTest from "./useResultTest"
import moment from "moment"
import AddUpdateResultTest from "./addUpdateResultTest"

export default defineComponent({
  components: {
    AddUpdateResultTest
  },
  setup() {
    const {
      isLoadingResultTestList,
      resultTestList,
      gettResultTestList,
      totalPage
    } = useResultTest()
    const currentPage = ref(1)
    const multipleSelection = ref([])
    const textSearch = ref("")
    const mode = ref("")
    const isVisibleAddUpdate = ref(false)
    gettResultTestList(currentPage.value)

    const handleSelectionChange = (value) => {
      multipleSelection.value = value
    }

    const handleChangePage = (value) => {
      currentPage.value = value
      gettResultTestList(currentPage.value)
    }

    const handleSearch = () => {
      console.log("Chưa làm tìm kiếm: " + textSearch.value)
    }

    const setMode = (value) => {
      mode.value = value
    }

    const handleChangeVisibleAddUpdate = () => {
      isVisibleAddUpdate.value = !isVisibleAddUpdate.value
    }

    const handlechangeVisibleAdd = () => {
      setMode("add")
      handleChangeVisibleAddUpdate()
    }

    const handlechangeVisibleUpdate = () => {
      setMode("update")
      handleChangeVisibleAddUpdate()
    }

    provide("handleChangeVisibleAddUpdate", handleChangeVisibleAddUpdate)
    provide("setMode", setMode)

    return {
      isLoadingResultTestList,
      resultTestList,
      multipleSelection,
      handleSelectionChange,
      totalPage,
      currentPage,
      handleChangePage,
      handleSearch,
      textSearch,
      setMode,
      mode,
      handlechangeVisibleAdd,
      handlechangeVisibleUpdate,
      isVisibleAddUpdate
    }
  },
  methods: {
    formatDateHour(date) {
      return moment(date).format("hh:mm:ss a, DD/MM/YYYY")
    },
    formatDate(date) {
      return moment(date).format("DD/MM/YYYY")
    }
  }
})
</script>

<template>
  <h3>Danh sách kết quả xét nghiệm</h3>
  <el-row class="row-bg mb-10">
    <el-col :span="12">
      <div class="grid-content">
        <el-input
          placeholder="Tìm kiếm..."
          prefix-icon="el-icon-search"
          v-model="textSearch"
          v-on:keyup.enter="handleSearch"
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
          @click="handlechangeVisibleAdd"
        >
          <i class="el-icon-plus"></i>
          Thêm
        </el-button>

        <el-button
          size="small"
          type="primary"
          class="text-white"
          v-if="multipleSelection.length == 1"
          @click="handlechangeVisibleUpdate"
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
    ref="multipleTable"
    @selection-change="handleSelectionChange"
    :data="resultTestList"
    style="width: 100%"
    stripe
    border
    v-loading="isLoadingResultTestList"
    element-loading-text="Loading..."
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.5)"
  >
    <el-table-column fixed type="selection" width="55"> </el-table-column>
    <el-table-column label="Thời gian">
      <template #default="scope">
        {{ formatDateHour(scope.row.created_at) }}
      </template>
    </el-table-column>
    <el-table-column label="Họ tên" property="user.fullname"></el-table-column>
    <el-table-column label="Kết quả">
      <template #default="scope">
        {{ scope.row.status == "negative" ? "Âm tính" : "Dương tính" }}
      </template>
    </el-table-column>

    <el-table-column
      label="Số điện thoại"
      property="user.phone"
    ></el-table-column>

    <el-table-column label="Ngày sinh">
      <template #default="scope">
        {{ formatDate(scope.row.user.birthday) }}
      </template>
    </el-table-column>

    <el-table-column label="Giới tính" width="80">
      <template #default="scope">
        {{ scope.row.user.gender ? "Nam" : "Nữ" }}
      </template>
    </el-table-column>

    <el-table-column
      label="Người tạo"
      property="user_create_by.fullname"
    ></el-table-column>
  </el-table>

  <el-pagination
    class="text-center mt-20"
    background
    layout="prev, pager, next"
    :current-page="currentPage"
    @current-change="handleChangePage"
    :total="totalPage * 10"
  >
  </el-pagination>

  <AddUpdateResultTest
    :isVisible="isVisibleAddUpdate"
    :isMode="mode"
    :multipleSelection="multipleSelection"
  >
  </AddUpdateResultTest>
</template>

<style scoped>
.el-table {
  --el-table-header-background-color: #11385e;
  --el-table-header-font-color: #fff;
}
</style>
