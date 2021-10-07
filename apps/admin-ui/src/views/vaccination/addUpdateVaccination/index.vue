<script>
import { defineComponent, watch, ref, inject } from "vue"
import useAddUpdateVaccination from "./useAddUpdateVaccination.ts"
import useVaccineType from "../../vaccineType/useVaccineType.ts"
import * as yup from "yup"
import { useForm, useField } from "vee-validate"
import { useStore } from "vuex"
import useUploadImage from "../../uploadImages/useUploadImage.ts"
import useBaseUrl from "@/services/baseUrl.ts"

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
    const {
      isLoadingAddVaccination,
      addVaccination,
      updateVaccination,
      vaccinationNewId,
      searchUser,
      dataSearchUser
    } = useAddUpdateVaccination()
    const { getListVaccineType, dataVaccineType } = useVaccineType()
    const { uploadImage, updateImage } = useUploadImage()
    const store = useStore()
    const { BASE_URL } = useBaseUrl()
    const closeModalAddUpdate = inject("handleVisibleAddUpdate")
    const setMode = inject("setMode")
    const getVaccinationList = inject("getVaccinationList")
    const currentPage = inject("currentPage")

    const visible = ref(false)
    const mode = ref("")
    const fullname = ref()
    const vaccinationID = ref(0)
    const vaccinationImagePreview = ref([2])
    const vaccinationImageUploadBefore = ref()
    const vaccinationImageUploadAfter = ref()
    const vaccinationImageBeforeId = ref(0)
    const vaccinationImageAfterId = ref(0)
    const vaccinationImageUploadError = ref()

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

        vaccinationImagePreview.value = [2]
        props.selectVaccineType[0].images.map((image) => {
          if (image.type == "vaccination_before") {
            vaccinationImageBeforeId.value = image.id
            vaccinationImagePreview.value.splice(0, 1, BASE_URL + image.url)
          }
          if (image.type == "vaccination_after") {
            vaccinationImagePreview.value.splice(1, 1, BASE_URL + image.url)
            vaccinationImageAfterId.value = image.id
          }
        })
      }
    })

    const onSubmitAddUpdate = handleSubmit(async (values) => {
      if (values) {
        console.log(values)
        if (mode.value == "add") {
          if (
            vaccinationImageUploadBefore.value &&
            vaccinationImageUploadAfter.value
          ) {
            await addVaccination(values)
            await handleUploadVaccinationImage("add")
            cancelForm()
            getVaccinationList(currentPage.value)
          } else {
            vaccinationImageUploadError.value = "Ảnh xác nhận tiêm là bắt buộc!"
          }
        } else {
          await handleUploadVaccinationImage("")
          await updateVaccination(vaccinationID.value, values)
          cancelForm()
          getVaccinationList(currentPage.value)
        }
      }
    })

    const cancelForm = () => {
      setMode("")
      closeModalAddUpdate()
      resetForm()
      fullname.value = ""

      vaccinationImagePreview.value = [2]
      vaccinationImageUploadBefore.value = ""
      vaccinationImageUploadAfter.value = ""
      vaccinationImageUploadError.value = ""
    }

    const querySelectUser = async (queryString, cb) => {
      if (queryString != "") {
        await searchUser(queryString)
        cb(dataSearchUser.value)
        console.log(dataSearchUser.value)
      }
    }

    const handleSelectUser = (item) => {
      fullname.value = item.fullname
      user_id.value = item.id
    }

    const handleChangeImageBefore = (image) => {
      if (image) {
        vaccinationImageUploadBefore.value = image.target.files[0]
        vaccinationImagePreview.value.splice(
          0,
          1,
          URL.createObjectURL(image.target.files[0])
        )
      }
    }

    const handleChangeImageAfter = (image) => {
      if (image) {
        vaccinationImageUploadAfter.value = image.target.files[0]
        vaccinationImagePreview.value.splice(
          1,
          1,
          URL.createObjectURL(image.target.files[0])
        )
      }
    }

    const handleUploadVaccinationImage = async (act) => {
      const formDataBefore = new FormData()
      formDataBefore.append("images[]", vaccinationImageUploadBefore.value)
      formDataBefore.append("imageable_type", "vaccination")
      formDataBefore.append("type", "vaccination_before")

      const formDataAfter = new FormData()
      formDataAfter.append("images[]", vaccinationImageUploadAfter.value)
      formDataAfter.append("imageable_type", "vaccination")
      formDataAfter.append("type", "vaccination_after")

      if (act == "add") {
        if (vaccinationNewId?.value > 0) {
          formDataBefore.append("imageable_id", vaccinationNewId?.value)
          formDataAfter.append("imageable_id", vaccinationNewId?.value)
        }
        if (vaccinationImageUploadBefore.value) {
          await uploadImage(formDataBefore)
        }

        if (vaccinationImageUploadAfter.value) {
          await uploadImage(formDataAfter)
        }
      } else {
        formDataBefore.append("imageable_id", vaccinationID?.value)
        formDataAfter.append("imageable_id", vaccinationID?.value)
        if (vaccinationImageUploadBefore.value) {
          if (vaccinationImageBeforeId.value) {
            await updateImage(vaccinationImageBeforeId.value, formDataBefore)
          } else {
            await uploadImage(formDataBefore)
          }
        }

        if (vaccinationImageUploadAfter.value) {
          if (vaccinationImageAfterId.value) {
            await updateImage(vaccinationImageAfterId.value, formDataAfter)
          } else {
            await uploadImage(formDataAfter)
          }
        }
      }
    }

    return {
      vaccine_type_id,
      errors,
      visible,
      onSubmitAddUpdate,
      isLoadingAddVaccination,
      mode,
      cancelForm,
      fullname,
      handleSelectUser,
      querySelectUser,
      dataSearchUser,
      dataVaccineType,
      handleChangeImageBefore,
      handleChangeImageAfter,
      vaccinationImagePreview,
      vaccinationImageUploadError
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
      <el-form-item label="Họ & Tên:">
        <el-autocomplete
          style="width: 100%"
          v-model="fullname"
          :fetch-suggestions="querySelectUser"
          popper-class="my-autocomplete"
          placeholder="Tìm kiếm người dùng..."
          :debounce="1500"
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

      <el-form-item label="Ảnh xác nhận (trước):">
        <el-button
          type="primary"
          size="mini"
          @click="$refs.vaccinationImageBefore.click()"
          >Chọn ảnh</el-button
        >

        <input
          type="file"
          ref="vaccinationImageBefore"
          style="display: none"
          @change="handleChangeImageBefore"
        />
        <div class="vaccination-image">
          <el-image
            fit="cover"
            :src="vaccinationImagePreview[0]"
            :preview-src-list="vaccinationImagePreview"
          >
            <template #error>
              <div class="image-slot">
                <i class="el-icon-picture-outline"></i>
              </div>
            </template>
          </el-image>
        </div>
      </el-form-item>

      <el-form-item label="Ảnh xác nhận (sau):">
        <el-button
          type="primary"
          size="mini"
          @click="$refs.vaccinationImageAfter.click()"
          >Chọn ảnh</el-button
        >

        <input
          type="file"
          ref="vaccinationImageAfter"
          style="display: none"
          @change="handleChangeImageAfter"
        />
        <div class="vaccination-image">
          <el-image
            fit="cover"
            :src="vaccinationImagePreview[1]"
            :preview-src-list="vaccinationImagePreview"
          >
            <template #error>
              <div class="image-slot">
                <i class="el-icon-picture-outline"></i>
              </div>
            </template>
          </el-image>
          <div class="text-red">{{ vaccinationImageUploadError }}</div>
        </div>
      </el-form-item>
    </el-form>
    <template #footer>
      <span class="dialog-footer">
        <el-button
          type="primary"
          @click="onSubmitAddUpdate"
          :loading="isLoadingAddVaccination"
          :disabled="isLoadingAddVaccination"
          class="btn-11385e"
        >
          {{ mode == "add" ? "Thêm" : "Cập nhật" }}
        </el-button>
        <el-button @click="cancelForm">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<style scoped>
.vaccination-image .el-image {
  width: 150px;
  height: 80px;
  border: 1px solid #ddd;
}
</style>
