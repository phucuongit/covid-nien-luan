<script>
import { useForm, useField } from "vee-validate"
import { defineComponent, watch, ref, inject } from "vue"
import * as yup from "yup"
import moment from "moment"
import useAddUpdateResultTest from "./useAddUpdateResultTest.ts"

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
      isLoadingAddUpdateResultTest
    } = useAddUpdateResultTest()
    const addUpdateResultTestSchema = yup.object({
      user_id: yup.number().required("Người kiểm tra là bắt buộc"),
      status: yup.string().required("Kết quả là bắt buộc")
    })

    const { handleSubmit, errors, resetForm } = useForm({
      validationSchema: addUpdateResultTestSchema
    })

    const { value: user_id } = useField("user_id")
    const { value: status } = useField("status")

    const visible = ref()
    const mode = ref("")
    const textSearchUser = ref("")
    watch(props, () => {
      visible.value = props.isVisible
      mode.value = props.isMode
      if (props.multipleSelection[0] && mode.value == "update") {
        user_id.value = props.multipleSelection[0].user.id
        status.value = props.multipleSelection[0].user.status
      }
    })

    const handleSelectionChange = (values) => {
      console.log(values)
    }

    const onSubmitAddUpdate = handleSubmit(async (values) => {
      if (values) {
        // if (mode.value == "add") {
        //   console.log("add") // Thêm id người tạo (account đăng nhập)
        // }
        console.log(values)
      }
    })

    const handleSeachUser = () => {
      searchUser(textSearchUser.value)
    }

    const closeAddUpdateModal = inject("handleChangeVisibleAddUpdate")
    const setMode = inject("setMode")
    const cancelForm = () => {
      closeAddUpdateModal()
      setMode("")
      resetForm()
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
      handleSelectionChange,
      isLoadingSearch,
      dataSearchUser,
      handleSeachUser,
      isLoadingAddUpdateResultTest
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
    width="30%"
    v-model="visible"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <el-input
      v-model="textSearchUser"
      placeholder="Tìm kiếm..."
      prefix-icon="el-icon-search"
      v-on:keyup.enter="handleSeachUser"
      class="mb-25"
    >
    </el-input>
    <el-form label-position="left" label-width="140px">
      <el-form-item label="Người xét nghiệm">
        <el-select
          v-model="user_id"
          placeholder="Chọn..."
          style="width: 100%"
          :loading="isLoadingSearch"
        >
          <el-option
            v-for="item in dataSearchUser"
            :key="item.id"
            :label="item.fullname"
            :value="item.id"
          >
          </el-option>
        </el-select>
      </el-form-item>

      <el-form-item label="Kết quả">
        <el-select v-model="status" placeholder="Chọn..." style="width: 100%">
          <el-option label="Âm tính" value="negative"></el-option>
          <el-option label="Dương tính" value="positive"></el-option>
        </el-select>
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
