<script>
import { defineComponent, ref } from "vue"
import useVaccination from "./useVaccination.ts"
import moment from "moment"
export default defineComponent({
  setup() {
    const {
      isLoadingVaccination,
      getVaccinationList,
      vaccinationList,
      totalPage
    } = useVaccination()
    const currentPage = ref(1)
    getVaccinationList(currentPage.value)

    const handleChangePage = (page) => {
      currentPage.value = page
      getVaccinationList(currentPage.value)
    }
    const multipleSelection = ref([])
    const handleSelectionChange = (value) => {
      multipleSelection.value = value
    }
    return {
      vaccinationList,
      isLoadingVaccination,
      totalPage,
      currentPage,
      handleChangePage,
      handleSelectionChange,
      multipleSelection
    }
  },
  methods: {
    formatDate(date) {
      return moment(date).format("hh:mm:ss a, DD/MM/YYYY")
    }
  }
})
</script>

<template>
  <div id="vaccine-list">
    <h3 class="mr-0">Danh sách tiêm chủng vắc-xin</h3>
    <el-row class="row-bg mb-10">
      <el-col :span="12">
        <div class="grid-content">
          <!-- <el-input
            placeholder="Tìm kiếm..."
            prefix-icon="el-icon-search"
            v-model="textSearch"
            v-on:keyup.enter="handleSearch"
          >
          </el-input> -->
        </div>
      </el-col>
      <el-col :span="12">
        <div class="grid-content text-right pt-10">
          <el-button size="small" type="primary" class="text-white">
            <i class="el-icon-plus"></i>
            Thêm
          </el-button>

          <el-button
            size="small"
            type="primary"
            class="text-white"
            v-if="multipleSelection.length == 1"
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
      :data="vaccinationList"
      style="width: 100%"
      max-height="480"
      stripe
      border
      v-loading="isLoadingVaccination"
      element-loading-text="Loading..."
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.5)"
    >
      <el-table-column fixed type="selection" width="55"> </el-table-column>
      <el-table-column
        label="Họ tên"
        width="250"
        property="user_id"
      ></el-table-column>
      <el-table-column
        label="Người tạo"
        width="250"
        property="create_by"
      ></el-table-column>
      <el-table-column
        label="Vắc-xin"
        width="240"
        property="vaccine_type_id"
      ></el-table-column>
      <el-table-column
        label="Thời gian tạo"
        width="250"
        property="created_at"
      ></el-table-column>
      <el-table-column
        label="Cập nhật lần cuối"
        width="250"
        property="updated_at"
      >
        <template #default="scope">{{
          formatDate(scope.row.update_at)
        }}</template>
      </el-table-column>
      <!-- <el-table-column
        label="Cập nhật lần cuối"
        width="305"
      >
      <template #default="scope">{{ formatDate(scope.row.updated_at) }}</template>
      </el-table-column> -->
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
  </div>
</template>

<style scoped>
.el-table {
  --el-table-header-background-color: #11385e;
  --el-table-header-font-color: #fff;
}
</style>
