<script>
import { defineComponent, ref, provide } from "vue"
import useVaccineType from "./useVaccineType"
import DeleteVaccineType from "./deleteVaccineType/index.vue"
import AddUpdateVaccineType from "./addUpdateVaccineType"
import moment from "moment"

export default defineComponent({
  components: {
    DeleteVaccineType,
    AddUpdateVaccineType
  },
  setup() {
    const { getListVaccineType, data, isLoading, getVaccineTypeSearch } =
      useVaccineType()
    getListVaccineType()

    const multipleSelection = ref([])
    const handleSelectionChange = (value) => {
      multipleSelection.value = value
    }

    const isVisibleDelete = ref(false)
    const handleChangeVisibleDelete = () => {
      isVisibleDelete.value = !isVisibleDelete.value
    }

    const isVisibleUpdate = ref(false)
    const handleChangeVisibleUpdate = () => {
      isVisibleUpdate.value = !isVisibleUpdate.value
    }

    const mode = ref("")
    const setMode = (action) => {
      mode.value = action
    }

    const textSearch = ref("")
    const handleSearch = () => {
      getVaccineTypeSearch(textSearch.value)
      console.log(textSearch.value)
    }

    const handleCheckSearch = () => {
      if (textSearch.value == "") {
        getListVaccineType()
      }
    }

    provide("closeVaccineTypeDelete", handleChangeVisibleDelete)
    provide("closeVaccineTypeUpdate", handleChangeVisibleUpdate)
    provide("handleChangeVisibleUpdate", handleChangeVisibleUpdate)
    provide("getListVaccineType", getListVaccineType)
    provide("setMode", setMode)

    return {
      data,
      isLoading,
      isVisibleDelete,
      isVisibleUpdate,
      multipleSelection,
      handleSelectionChange,
      handleChangeVisibleDelete,
      handleChangeVisibleUpdate,
      setMode,
      mode,
      textSearch,
      handleSearch,
      handleCheckSearch
    }
  },
  methods: {
    formatDate(date) {
      return moment(date).format("hh:mm - DD/MM/YYYY")
    }
  }
})
</script>

<template>
  <div id="vaccine-list">
    <h3 class="mr-0">Danh sách các loại vắc-xin</h3>
    <el-row class="row-bg mb-10">
      <el-col :span="12">
        <div class="grid-content">
          <el-input
            placeholder="Tìm kiếm theo tên vắc-xin..."
            prefix-icon="el-icon-search"
            v-model="textSearch"
            v-on:keyup.enter="handleSearch"
            v-on:keyup="handleCheckSearch"
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
            @click="
              () => {
                handleChangeVisibleUpdate()
                setMode('add')
              }
            "
          >
            <i class="el-icon-plus"></i>
            Thêm
          </el-button>

          <el-button
            size="small"
            type="primary"
            class="text-white"
            @click="
              () => {
                handleChangeVisibleUpdate()
                setMode('update')
              }
            "
            v-if="multipleSelection.length == 1"
          >
            <i class="el-icon-edit"></i>
            Sửa
          </el-button>

          <el-button
            size="small"
            type="danger"
            class="text-white"
            @click="handleChangeVisibleDelete"
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
      :data="data"
      style="width: 100%"
      stripe
      border
      @selection-change="handleSelectionChange"
      v-loading="isLoading"
      element-loading-text="Loading..."
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.5)"
    >
      <el-table-column fixed type="selection" width="55"> </el-table-column>
      <el-table-column label="Tên vắc-xin" property="name"> </el-table-column>
      <el-table-column property="country" label="Nước sản xuất">
      </el-table-column>
      <el-table-column label="Thời gian thêm">
        <template #default="scope">{{
          formatDate(scope.row.created_at)
        }}</template>
      </el-table-column>

      <el-table-column label="Cập nhật lần cuối">
        <template #default="scope">{{
          formatDate(scope.row.updated_at)
        }}</template>
      </el-table-column>
    </el-table>
  </div>

  <DeleteVaccineType
    :isVisible="isVisibleDelete"
    :selectVaccineType="multipleSelection"
  ></DeleteVaccineType>

  <AddUpdateVaccineType
    :isVisible="isVisibleUpdate"
    :selectVaccineType="multipleSelection"
    :mode="mode"
  ></AddUpdateVaccineType>
</template>

<style scoped>
.el-table {
  --el-table-header-background-color: #11385e;
  --el-table-header-font-color: #fff;
}
</style>
