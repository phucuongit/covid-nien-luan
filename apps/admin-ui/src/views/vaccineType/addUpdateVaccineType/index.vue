<script>
import { defineComponent, inject, watch, ref } from "vue"
import * as yup from "yup"
import { useForm, useField } from "vee-validate"
import useAddUpdateVaccineType from "./useAddUpdateVaccineType.ts"
const AddVaccineType = defineComponent({
  name: "UpdateVaccineType",
  props: {
    isVisible: {
      type: Boolean,
      default: false
    },
    selectVaccineType: {
      type: Object,
      default: () => ({
        id: null,
        name: null,
        country: null,
        created_at: null,
        updated_at: null
      })
    },
    mode: {
      type: String,
      default: ""
    }
  },
  setup(props) {
    const { updateVaccineType, createVaccineType, isLoadingAddUpdate } =
      useAddUpdateVaccineType()
    const updateVaccineTypeSchema = yup.object({
      name: yup.string().required("Tên đăng nhập là bắt buộc"),
      country: yup.string().required("Mật khẩu là bắt buộc")
    })
    const { handleSubmit, errors, resetForm } = useForm({
      validationSchema: updateVaccineTypeSchema
    })

    const setMode = inject("setMode")
    const isShow = ref()
    const id = ref()
    const valueMode = ref()
    watch(props, () => {
      isShow.value = props.isVisible
      valueMode.value = props.mode
      // if (props.selectVaccineType[0] && valueMode.value == "update") {
      //   name.value = props.selectVaccineType[0].name
      //   country.value = props.selectVaccineType[0].country
      //   id.value = props.selectVaccineType[0].id
      // }
    })

    const closeVaccineTypeUpdate = inject("closeVaccineTypeUpdate")
    const getListVaccineType = inject("getListVaccineType")
    const cancelForm = () => {
      resetForm()
      closeVaccineTypeUpdate()
      setMode("")
    }
    const onSubmitUpdate = handleSubmit(async (values) => {
      if (values) {
        if (valueMode.value == "add") {
          createVaccineType(values, getListVaccineType)
        } else {
          updateVaccineType(values, id.value, getListVaccineType)
        }
        console.log(values)
        cancelForm()
      }
    })
    const { value: name } = useField("name")
    const { value: country } = useField("country")

    return {
      errors,
      name,
      country,
      onSubmitUpdate,
      isShow,
      cancelForm,
      isLoadingAddUpdate,
      valueMode
    }
  }
})

export default AddVaccineType
</script>

<template>
  <el-dialog
    title="Loại vắc-xin"
    v-model="isShow"
    width="30%"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <el-form label-position="left" label-width="120px">
      <el-form-item label="Tên vắc-xin">
        <el-input v-model="name"></el-input>
        <div class="text-red">{{ errors.name }}</div>
      </el-form-item>
      <el-form-item label="Nước sản xuất">
        <el-input v-model="country"></el-input>
        <div class="text-red">{{ errors.country }}</div>
      </el-form-item>
    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button
          type="primary"
          @click="onSubmitUpdate"
          :loading="isLoadingAddUpdate"
          class="btn-11385e"
        >
          {{ valueMode == "add" ? "Thêm" : "Cập nhật" }}
        </el-button>
        <el-button @click="cancelForm">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>
