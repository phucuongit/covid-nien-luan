<script>
import { useForm, useField } from "vee-validate"
import { defineComponent, watch, ref, inject } from "vue"
import * as yup from "yup"
import moment from "moment"
import useAddUpdateResultTest from "./useAddUpdateResultTest.ts"
import { useStore } from "vuex"

export default defineComponent({
  name: "AddUpdateResultTest",
  props: {
    isVisible: {
      type: Boolean,
      default: false
    },
    isMode: {
      type: String,
      default: ""
    },
    multipleSelection: {
      type: Object,
      default: () => {
        null
      }
    }
  },
  setup(props) {
    const {
      isLoadingSearch,
      dataSearchUser,
      searchUser,
      isLoadingAddUpdateResultTest,
      addResultTest,
      updateResultTest
    } = useAddUpdateResultTest()

    const addUpdateResultTestSchema = yup.object({
      user_id: yup.number().required("Người được xét nghiệm là bắt buộc"),
      status: yup.string().required("Kết quả xét nghiệm là bắt buộc"),
      create_by: yup.number()
    })

    const store = useStore()
    const { handleSubmit, errors, resetForm } = useForm({
      validationSchema: addUpdateResultTestSchema
    })

    const { value: user_id } = useField("user_id")
    const { value: status } = useField("status")
    const { value: create_by } = useField("create_by")

    const closeAddUpdateModal = inject("handleChangeVisibleAddUpdate")
    const setMode = inject("setMode")
    const currentPage = inject("currentPage")
    const getResultTestList = inject("getResultTestList")

    const visible = ref()
    const mode = ref("")
    const textSearchUser = ref("")
    const idResultTest = ref()
    const fullname = ref()
    watch(props, () => {
      visible.value = props.isVisible
      mode.value = props.isMode
      if (props.multipleSelection[0] && mode.value == "update") {
        idResultTest.value = props.multipleSelection[0].id

        user_id.value = props.multipleSelection[0].user.id
        status.value = props.multipleSelection[0].status
        create_by.value = props.multipleSelection[0].user_create_by.id
        fullname.value = props.multipleSelection[0].user.fullname
      }
    })

    const onSubmitAddUpdate = handleSubmit(async (values) => {
      if (values) {
        if (mode.value == "add") {
          await addResultTest(values)
        } else {
          await updateResultTest(idResultTest.value, values)
        }
        getResultTestList(currentPage.value)
        cancelForm()
      }
    })

    const cancelForm = () => {
      closeAddUpdateModal()
      setMode("")
      resetForm()
    }

    const querySearch = (queryString, cb) => {
      searchUser(queryString)
      cb(dataSearchUser.value)
    }

    const handleSelect = (item) => {
      fullname.value = item.fullname
      user_id.value = item.id
      create_by.value = store.state.user.id
    }

    return {
      user_id,
      status,
      errors,
      onSubmitAddUpdate,
      visible,
      cancelForm,
      mode,
      textSearchUser,
      isLoadingSearch,
      dataSearchUser,
      isLoadingAddUpdateResultTest,
      querySearch,
      handleSelect,
      fullname
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
  <el-dialog
    title="Kết quả xét nghiệm"
    width="35%"
    v-model="visible"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <el-form label-position="left" label-width="100px">
      <el-form-item label="Tên:">
        <el-autocomplete
          style="width: 100%"
          v-model="fullname"
          :fetch-suggestions="querySearch"
          popper-class="my-autocomplete"
          placeholder="Tìm kiếm người dùng..."
          @select="handleSelect"
        >
          <template #default="{ item }">
            <div class="value">{{ item.fullname }}</div>
          </template>
        </el-autocomplete>
        <div class="text-red">{{ errors.user_id }}</div>
      </el-form-item>

      <el-form-item label="Kết quả:">
        <el-select v-model="status" placeholder="Chọn..." style="width: 100%">
          <el-option label="Âm tính" value="negative"></el-option>
          <el-option label="Dương tính" value="positive"></el-option>
        </el-select>
        <div class="text-red">{{ errors.status }}</div>
      </el-form-item>
    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button
          type="primary"
          @click="onSubmitAddUpdate"
          :loading="isLoadingAddUpdateResultTest"
          class="btn-11385e"
        >
          {{ mode == "add" ? "Thêm" : "Cập nhật" }}
        </el-button>
        <el-button @click="cancelForm">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>
