<script>
import { defineComponent, watch, ref, inject } from "vue"
import useAddUpdateVaccination from "./useAddUpdateVaccination.ts"
import useUsers from "../../users/useUsers.ts"
import useVaccineType from "../../vaccineType/useVaccineType.ts"
import * as yup from "yup"
import { useForm, useField } from "vee-validate"
import { useStore } from "vuex"

export default defineComponent({
  name: "AddUpdateVaccination",
  props: {
    isVisible: {
      type: Boolean,
      default: false
    },
    isMode: {
      type: String,
      default: ""
    },
    selectVaccineType: {
      type: Object,
      default: null
    }
  },
  setup(props) {
    const { isLoadingAddUpdate, addVaccination, updateVaccination } =
      useAddUpdateVaccination()
    const { getListUsersSearch, data } = useUsers()
    const { getListVaccineType, dataVaccineType } = useVaccineType()
    const store = useStore()

    const closeModalAddUpdate = inject("handleVisibleAddUpdate")
    const setMode = inject("setMode")
    const getVaccinationList = inject("getVaccinationList")
    const currentPage = inject("currentPage")

    const visible = ref(false)
    const mode = ref("")
    const fullname = ref()
    const vaccinationID = ref(0)

    const vaccinationSchemal = yup.object({
      user_id: yup.number().required("Người được tiêm là bắt buộc"),
      create_by: yup.number(),
      vaccine_type_id: yup.number().required("Loại vắc-xin là bắt buộc")
    })

    const { handleSubmit, errors, resetForm } = useForm({
      validationSchema: vaccinationSchemal
    })

    const { value: user_id } = useField("user_id")
    const { value: create_by } = useField("create_by")
    const { value: vaccine_type_id } = useField("vaccine_type_id")

    getListVaccineType()

    watch(props, () => {
      visible.value = props.isVisible
      mode.value = props.isMode
      create_by.value = store.state.user.id
      if (mode.value == "update" && props.selectVaccineType[0]) {
        create_by.value = props.selectVaccineType[0].user_create_by.id
        vaccine_type_id.value = props.selectVaccineType[0].vaccine_type.id
        user_id.value = props.selectVaccineType[0].user.id
        fullname.value = props.selectVaccineType[0].user.fullname
        vaccinationID.value = props.selectVaccineType[0].id
      }
    })

    const onSubmitAddUpdate = handleSubmit(async (values) => {
      if (values) {
        console.log(values)
        if (mode.value == "add") {
          await addVaccination(values)
        } else {
          console.log("update: " + vaccinationID.value)
          await updateVaccination(vaccinationID.value, values)
        }
        getVaccinationList(currentPage.value)
        cancelForm()
      }
    })

    const cancelForm = () => {
      setMode("")
      closeModalAddUpdate()
      resetForm()
      fullname.value = ""
    }

    const querySelectUser = (queryString, cb) => {
      getListUsersSearch(queryString)
      cb(data.value)
    }

    const handleSelectUser = (item) => {
      fullname.value = item.fullname
      user_id.value = item.id
    }

    return {
      vaccine_type_id,
      errors,
      visible,
      onSubmitAddUpdate,
      isLoadingAddUpdate,
      mode,
      cancelForm,
      fullname,
      handleSelectUser,
      querySelectUser,
      data,
      dataVaccineType
    }
  }
})
</script>

<template>
  <el-dialog
    title="Lịch sử tiêm chủng vắc-xin"
    v-model="visible"
    width="30%"
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
          :fetch-suggestions="querySelectUser"
          popper-class="my-autocomplete"
          placeholder="Tìm kiếm người dùng..."
          @select="handleSelectUser"
        >
          <template #default="{ item }">
            <div class="value">{{ item.fullname }}</div>
          </template>
        </el-autocomplete>
        <div class="text-red">{{ errors.user_id }}</div>
      </el-form-item>

      <el-form-item label="Loại vắc-xin:">
        <el-select
          v-model="vaccine_type_id"
          style="width: 100%"
          placeholder="Chọn..."
        >
          <el-option
            v-for="item in dataVaccineType"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          >
          </el-option>
        </el-select>
        <div class="text-red">{{ errors.vaccine_type_id }}</div>
      </el-form-item>
    </el-form>
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
