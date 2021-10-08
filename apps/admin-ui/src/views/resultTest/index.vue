<script lang="ts">
import { defineComponent, provide, ref } from "vue"
import useResultTest from "./useResultTest"
import moment from "moment"
import AddUpdateResultTest from "./addUpdateResultTest/index.vue"
import DeleteResultTest from "./deleteResultTest/index.vue"
import useBaseUrl from "../../services/baseUrl"

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
      statusSearchResultTest,
      searchResultTest,
      isLoadingSearch
    } = useResultTest()

    const currentPage = ref(1)
    const multipleSelection = ref([])
    const textSearch = ref("")
    const mode = ref("")
    const isVisibleAddUpdate = ref(false)
    const isVisibleDelete = ref(false)
    const { BASE_URL, BASE_RESULT_TEST } = useBaseUrl()
    getResultTestList(currentPage.value)

    const handleSelectionChange = (value: any) => {
      multipleSelection.value = value
    }

    const handleChangePage = (page: number) => {
      currentPage.value = page
      if (statusSearchResultTest.value) {
        searchResultTest(textSearch.value, currentPage.value)
      } else {
        getResultTestList(currentPage.value)
      }
    }

    const setMode = (value: string) => {
      mode.value = value
    }

    const handleChangeVisibleAddUpdate = () => {
      isVisibleAddUpdate.value = !isVisibleAddUpdate.value
    }

    const handlechangeVisible = (modeValue: "add" | "update") => {
      setMode(modeValue)
      handleChangeVisibleAddUpdate()
    }

    const handleSearch = () => {
      currentPage.value = 1
      if (textSearch.value == "") {
        getResultTestList(currentPage.value)
      } else {
        searchResultTest(textSearch.value, currentPage.value)
      }
    }

    const handleVisibleDelete = () => {
      isVisibleDelete.value = !isVisibleDelete.value
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
      currentPage,
      handleChangePage,
      handleSearch,
      textSearch,
      setMode,
      mode,
      handlechangeVisible,
      isVisibleAddUpdate,
      isVisibleDelete,
      handleVisibleDelete,
      isLoadingSearch,
      BASE_URL,
      BASE_RESULT_TEST
    }
  },
  methods: {
    formatDateHour(date: Date) {
      return moment(date).format("HH:MM, DD-MM-YYYY")
    },
    formatDate(date: Date) {
      return moment(date).format("DD-MM-YYYY")
    }
  }
})
</script>

<template>
  <h3 class="mt-0">Danh sách kết quả xét nghiệm</h3>
  <el-row class="row-bg mb-10" :gutter="30">
    <el-col :md="9" :sm="12" :xs="24" class="pt-5">
      <div class="grid-content">
        <el-input
          size="small"
          placeholder="Tìm kiếm theo họ tên, ngày..."
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
    <el-col :md="15" :sm="24" :xs="24">
      <div class="grid-content text-right">
        <el-button
          size="small"
          type="primary"
          class="text-white mt-5"
          @click="handlechangeVisible('add')"
        >
          <i class="el-icon-plus"></i>
          Thêm
        </el-button>

        <el-button
          size="small"
          type="primary"
          class="text-white mt-5"
          v-if="multipleSelection.length == 1"
          @click="handlechangeVisible('update')"
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
    :data="resultTestList?.result_tests"
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
      </template>
    </el-table-column>

    <el-table-column label="Hình ảnh" width="130">
      <template #default="scope">
        <div class="table-img" v-if="scope.row.images[0]?.url">
          <el-image
            v-for="img in scope.row.images"
            :key="img.id"
            fit="cover"
            :src="BASE_URL + img?.url"
            :preview-src-list="[
              BASE_URL + scope.row.images[0]?.url,
              BASE_URL + scope.row.images[1]?.url
            ]"
          >
            <template #error>
              <div class="image-slot">
                <i class="el-icon-picture-outline"></i>
              </div>
            </template>
          </el-image>
        </div>
        <div class="table-img" v-else>
          <el-image
            fit="cover"
            :src="BASE_RESULT_TEST"
            :preview-src-list="[BASE_RESULT_TEST]"
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

    <el-table-column label="Ngày sinh">
      <template #default="scope">
        {{ formatDate(scope.row?.user?.birthday) }}
      </template>
    </el-table-column>

    <el-table-column label="Giới tính" width="80">
      <template #default="scope">
        {{ scope.row?.user?.gender ? "Nam" : "Nữ" }}
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
    :pager-count="4"
    small
    :page-size="resultTestList?.meta?.per_page"
    @current-change="handleChangePage"
    :total="resultTestList?.meta?.total"
  >
  </el-pagination>
  <el-backtop style="background: #11385e; color: #fff" :bottom="70" />

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

.table-img .el-image {
  width: 40px;
  height: 25px;
  border: 1px solid #ddd;
  margin-right: 5px;
}
</style>
