<script lang="ts">
import { defineComponent, provide, ref } from "vue"
import useVaccination from "./useVaccination"
import moment from "moment"
import AddUpdateVaccination from "./addUpdateVaccination/index.vue"
import DeleteVaccination from "./deleteVaccination/index.vue"
import useBaseUrl from "../../services/baseUrl"

export default defineComponent({
  components: {
    AddUpdateVaccination,
    DeleteVaccination
  },
  setup() {
    const {
      isLoadingVaccination,
      getVaccinationList,
      vaccinationList,
      isLoadingSearch,
      searchVaccineType,
      statusSearchVaccination
    } = useVaccination()

    const currentPage = ref(1)
    const isVisibleAddUpdate = ref(false)
    const isVisibleDelete = ref(false)
    const mode = ref("")
    const { BASE_URL } = useBaseUrl()
    getVaccinationList(currentPage.value)

    const multipleSelection = ref([])
    const textSearch = ref("")

    const handleChangePage = (page: number) => {
      currentPage.value = page
      if (statusSearchVaccination.value) {
        searchVaccineType(textSearch.value, currentPage.value)
      } else {
        getVaccinationList(currentPage.value)
      }
    }

    const handleSelectionChange = (value: any) => {
      multipleSelection.value = value
    }

    const handleSearch = () => {
      currentPage.value = 1
      if (textSearch.value == "") {
        getVaccinationList(currentPage.value)
      } else {
        searchVaccineType(textSearch.value, currentPage.value)
      }
    }

    const setMode = (act: string) => {
      mode.value = act
    }

    const handleVisibleAddUpdate = () => {
      isVisibleAddUpdate.value = !isVisibleAddUpdate.value
    }
    const handleChangeVisible = (modeValue: "add" | "update") => {
      handleVisibleAddUpdate()
      setMode(modeValue)
    }

    const handleVisisbleDelete = () => {
      isVisibleDelete.value = !isVisibleDelete.value
    }

    provide("handleVisibleAddUpdate", handleVisibleAddUpdate)
    provide("handleVisisbleDelete", handleVisisbleDelete)
    provide("setMode", setMode)
    provide("getVaccinationList", getVaccinationList)
    provide("currentPage", currentPage)

    return {
      vaccinationList,
      isLoadingVaccination,
      currentPage,
      handleChangePage,
      handleSelectionChange,
      multipleSelection,
      textSearch,
      isLoadingSearch,
      handleSearch,
      isVisibleAddUpdate,
      handleChangeVisible,
      mode,
      handleVisisbleDelete,
      isVisibleDelete,
      BASE_URL
    }
  },
  methods: {
    formatDate(date: Date) {
      return moment(date).format("HH:mm - DD-MM-YYYY")
    }
  }
})
</script>

<template>
  <div id="vaccine-list">
    <h3 class="mr-0 mt-0">Danh sách tiêm chủng vắc-xin</h3>
    <el-row class="row-bg mb-10" :gutter="30">
      <el-col :md="9" :sm="12" :xs="24" class="pt-5">
        <div class="grid-content">
          <el-input
            size="small"
            placeholder="Tìm kiếm theo họ tên, vắc-xin, ngày..."
            prefix-icon="el-icon-search"
            v-model="textSearch"
            v-on:keyup.enter="handleSearch"
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
      <el-col :md="15" :sm="12" :xs="24">
        <div class="grid-content text-right">
          <el-button
            size="small"
            type="primary"
            class="text-white mt-5"
            @click="handleChangeVisible('add')"
          >
            <i class="el-icon-plus"></i>
            Thêm
          </el-button>

          <el-button
            size="small"
            type="primary"
            class="text-white mt-5"
            v-if="multipleSelection.length == 1"
            @click="handleChangeVisible('update')"
          >
            <i class="el-icon-edit"></i>
            Sửa
          </el-button>

          <el-button
            size="small"
            type="danger"
            class="text-white mt-5"
            v-if="multipleSelection.length > 0"
            @click="handleVisisbleDelete"
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
      :data="vaccinationList?.vaccinations"
      style="width: 100%"
      stripe
      border
      v-loading="isLoadingVaccination"
      element-loading-text="Loading..."
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.5)"
    >
      <el-table-column fixed type="selection" width="55"> </el-table-column>
      <el-table-column label="Thời gian tạo">
        <template #default="scope">
          {{ formatDate(scope.row.created_at) }}
        </template>
      </el-table-column>
      <el-table-column
        label="Họ tên"
        property="user.fullname"
      ></el-table-column>
      <el-table-column
        label="Vắc-xin"
        property="vaccine_type.name"
      ></el-table-column>

      <el-table-column label="Hình ảnh" width="130">
        <template #default="scope">
          <div class="table-img">
            <el-image
              v-for="img in scope.row.images"
              :key="img.id"
              fit="cover"
              :src="BASE_URL + img?.url"
              :preview-src-list="[BASE_URL + img?.url]"
            >
              <template #error>
                <div class="image-slot">
                  <i class="el-icon-picture-outline"></i>
                </div>
              </template>
            </el-image>
          </div>
        </template>
      </el-table-column>

      <el-table-column
        label="Số điện thoại"
        property="user.phone"
      ></el-table-column>
      <el-table-column
        label="Người tạo"
        property="user_create_by.fullname"
      ></el-table-column>
    </el-table>
    <el-pagination
      class="text-center mt-20"
      background
      layout="prev, pager, next"
      :pager-count="4"
      small
      :page-size="vaccinationList?.meta?.per_page"
      :current-page="currentPage"
      @current-change="handleChangePage"
      :total="vaccinationList?.meta?.total"
    >
    </el-pagination>
    <el-backtop style="background: #11385e; color: #fff" :bottom="70" />
  </div>

  <addUpdateVaccination
    :isVisible="isVisibleAddUpdate"
    :isMode="mode"
    :selectVaccineType="multipleSelection"
  >
  </addUpdateVaccination>

  <DeleteVaccination
    :isVisible="isVisibleDelete"
    :selectVaccination="multipleSelection"
  ></DeleteVaccination>
</template>

<style scoped>
.el-table {
  --el-table-header-background-color: #11385e;
  --el-table-header-font-color: #fff;
}

.table-img .el-image {
  width: 40px;
  height: 25px;
  border: 1px solid #ddd;
  margin-right: 5px;
}
</style>
