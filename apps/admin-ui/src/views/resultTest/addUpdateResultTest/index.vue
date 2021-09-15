<script>
import { useForm, useField } from "vee-validate"
import { defineComponent, watch, ref, inject } from "vue"
import * as yup from "yup"
import moment from "moment"

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
    const textSearchUserByName = ref("")
    const userSelected = ref()
    watch(props, () => {
      visible.value = props.isVisible
      mode.value = props.isMode
      if (props.multipleSelection[0] && mode.value == "update") {
        user_id.value = props.multipleSelection[0].user.id
        userSelected.value = props.multipleSelection[0]
        console.log(userSelected.value)
      }
    })

    const handleSelectionChange = (values) => {
      console.log(values)
    }

    const onSubmitAddUpdate = handleSubmit(async (values) => {
      if (values) {
        if (mode.value == "add") {
          console.log("add")
        }
      }
    })

    const closeAddUpdateModal = inject("handleChangeVisibleAddUpdate")
    const setMode = inject("setMode")
    const cancelForm = () => {
      closeAddUpdateModal()
      setMode("")
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
      textSearchUserByName,
      handleSelectionChange,
      userSelected
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
    width="70%"
    v-model="visible"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <el-row :gutter="20">
      <el-col :span="8">
        <el-form label-position="left" label-width="140px">
          <el-form-item label="Người xét nghiệm">
            <el-input v-model="user_id" disabled></el-input>
            <div class="text-red">{{ errors.user_id }}</div>
          </el-form-item>
          <el-form-item label="Kết quả">
            <el-select v-model="status" placeholder="Chọn...">
              <el-option label="Âm tính" value="negative"></el-option>
              <el-option label="Dương tính" value="positive"></el-option>
            </el-select>
          </el-form-item>
        </el-form>
      </el-col>

      <el-col :span="16">
        <el-row :gutter="20" class="mb-10" v-if="mode == 'add'">
          <el-col :span="12">
            <el-input
              placeholder="Tìm kiếm theo CMND..."
              prefix-icon="el-icon-search"
              size="medium"
              v-model="textSearchUser"
            >
            </el-input>
          </el-col>
          <el-col :span="12">
            <el-input
              placeholder="Tìm kiếm theo tên..."
              prefix-icon="el-icon-search"
              size="medium"
              v-model="textSearchUserByName"
            >
            </el-input>
          </el-col>
        </el-row>

        <el-table
          ref="multipleTable"
          @selection-change="handleSelectionChange"
          style="width: 100%"
          max-height="480"
          stripe
          border
          element-loading-text="Loading..."
          element-loading-spinner="el-icon-loading"
          element-loading-background="rgba(0, 0, 0, 0.5)"
        >
          <!-- <el-table-column fixed type="selection" width="55"> </el-table-column>
          <el-table-column label="Họ tên" width="200" property="user.fullname"></el-table-column>
          <el-table-column label="CMND" width="120" property="user.identity_card"></el-table-column>
          <el-table-column label="Số bảo hiểm" width="120" property="user.social_insurance"></el-table-column>
          <el-table-column label="Số điện thoại" width="120" property="user.phone"></el-table-column>

          <el-table-column label="Ngày sinh" width="120">
            <template #default="scope">
              {{ formatDate(scope.row.user.birthday) }}
            </template>
          </el-table-column>

          <el-table-column label="Giới tính" width="80">
            <template #default="scope">
              {{ scope.row.user.gender ? 'Nam' : 'Nữ' }}
            </template>
          </el-table-column> -->
        </el-table>
      </el-col>
    </el-row>

    <template #footer>
      <span class="dialog-footer">
        <el-button
          type="primary"
          @click="onSubmitAddUpdate"
          :loading="isLoadingAddUpdate"
          class="btn-11385e"
        >
          {{ mode == "add" ? "Thêm" : "Cập nhật" }}
        </el-button>
        <el-button @click="cancelForm">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>
