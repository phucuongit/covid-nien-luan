<script>
import { defineComponent, provide, ref } from "vue"
import useResultTest from "./useResultTest"
import moment from "moment"
import AddUpdateResultTest from "./addUpdateResultTest"
import DeleteResultTest from "./deleteResultTest"

export default defineComponent({
  components: {
    AddUpdateResultTest,
    DeleteResultTest
  },
  setup() {
    const {
      isLoadingResultTestList,
      resultTestList,
      getResultTestList,
      totalPage,
      searchResultTest,
      isLoadingSearch,
      filterResultTest
    } = useResultTest()

    const currentPage = ref(1)
    const multipleSelection = ref([])
    const textSearch = ref("")
    const mode = ref("")
    const isVisibleAddUpdate = ref(false)
    const isVisibleDelete = ref(false)
    const filter = ref()
    getResultTestList(currentPage.value)

    const handleSelectionChange = (value) => {
      multipleSelection.value = value
    }

    const handleChangePage = (value) => {
      currentPage.value = value
      getResultTestList(currentPage.value)
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

    const handleSearch = () => {
      filter.value = ""
      searchResultTest(textSearch.value)
    }

    const handleVisibleDelete = () => {
      isVisibleDelete.value = !isVisibleDelete.value
    }

    const filterState = () => {
      textSearch.value = ""
      filterResultTest(filter.value)
    }

    const checkEmptyText = (value) => {
      if (textSearch.value == "") {
        getResultTestList(currentPage.value)
      }
    }

    provide("closeDeleteResultTest", handleVisibleDelete)
    provide("getResultTestList", getResultTestList)
    provide("currentPage", currentPage)
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
      isVisibleAddUpdate,
      isVisibleDelete,
      handleVisibleDelete,
      isLoadingSearch,
      filter,
      filterState,
      checkEmptyText
    }
  },
  methods: {
    formatDateHour(date) {
      return moment(date).format("hh:mm, DD/MM/YYYY")
    },
    formatDate(date) {
      return moment(date).format("DD/MM/YYYY")
    }
  }
})
</script>

<template>
  <h3 class="mt-0">Danh sách kết quả xét nghiệm</h3>
  <el-row class="row-bg mb-10" :gutter="30">
    <el-col :md="7" :sm="12" :xs="24" class="pt-5">
      <div class="grid-content">
        <el-input
          size="small"
          placeholder="Tìm kiếm..."
          prefix-icon="el-icon-search"
          v-model="textSearch"
          v-on:keyup="checkEmptyText"
        >
          <template #append>
            <el-button
              size="small"
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
      </div>
    </el-col>
    <el-col :md="5" :sm="12" :xs="24" class="pt-5">
      <el-select
        size="small"
        v-model="filter"
        @change="filterState"
        placeholder="Lọc theo kết quả..."
        style="width: 100%"
      >
        <el-option value="positive" label="Dương tính"></el-option>
        <el-option value="negative" label="Âm tính"></el-option>
      </el-select>
    </el-col>
    <el-col :md="12" :sm="24" :xs="24">
      <div class="grid-content text-right">
        <el-button
          size="small"
          type="primary"
          class="text-white mt-5"
          @click="handlechangeVisibleAdd"
        >
          <i class="el-icon-plus"></i>
          Thêm
        </el-button>

        <el-button
          size="small"
          type="primary"
          class="text-white mt-5"
          v-if="multipleSelection.length == 1"
          @click="handlechangeVisibleUpdate"
        >
          <i class="el-icon-edit"></i>
          Sửa
        </el-button>

        <el-button
          size="small"
          type="danger"
          class="text-white mt-5"
          v-if="multipleSelection.length > 0"
          @click="handleVisibleDelete"
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
        <span v-if="scope.row.status == 'positive'" class="text-red"
          >Dương tính</span
        >
        <span v-else>Âm tính</span>
        <!-- {{ scope.row.status == "negative" ? "Âm tính" : "Dương tính" }} -->
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

  <DeleteResultTest
    :isVisible="isVisibleDelete"
    :multipleSelection="multipleSelection"
  ></DeleteResultTest>
</template>

<style scoped>
.el-table {
  --el-table-header-background-color: #11385e;
  --el-table-header-font-color: #fff;
}
</style>
