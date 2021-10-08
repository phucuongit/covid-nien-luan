<script>
import { useForm, useField } from "vee-validate"
import { defineComponent, watch, ref, inject } from "vue"
import * as yup from "yup"
import moment from "moment"
import useAddUpdateResultTest from "./useAddUpdateResultTest.ts"
import { useStore } from "vuex"
import useUploadImage from "../../uploadImages/useUploadImage.ts"
import useBaseUrl from "@/services/baseUrl.ts"

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
      updateResultTest,
      newResultTestId
    } = useAddUpdateResultTest()

    const addUpdateResultTestSchema = yup.object({
      user_id: yup.number().required("Người được xét nghiệm là bắt buộc"),
      status: yup.string().required("Kết quả xét nghiệm là bắt buộc"),
      create_by: yup.number()
    })

    const store = useStore()
    const { BASE_URL } = useBaseUrl()
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
    const { uploadImage, updateImage } = useUploadImage()

    const visible = ref()
    const mode = ref("")
    const textSearchUser = ref("")
    const idResultTest = ref()
    const fullname = ref()
    const resultImageUploadBefore = ref()
    const resultImageUploadAfter = ref()
    const resultImagePreview = ref([2])
    const resultImageUploadError = ref()
    const resultImageBeforeId = ref(0)
    const resultImageAfterId = ref(0)
    const resultTest = ref()

    watch(props, () => {
      visible.value = props.isVisible
      mode.value = props.isMode
      if (props.multipleSelection[0] && mode.value == "update") {
        resultTest.value = props.multipleSelection[0]
        idResultTest.value = resultTest.value?.id

        user_id.value = resultTest.value?.user.id
        status.value = resultTest.value?.status
        create_by.value = resultTest.value?.user_create_by.id
        fullname.value = resultTest.value?.user.fullname

        resultImagePreview.value = [2]
        resultTest.value?.images.map((image) => {
          if (image.type == "result_test_before") {
            resultImageBeforeId.value = image.id
            resultImagePreview.value.splice(0, 1, BASE_URL + image.url)
          }
          if (image.type == "result_test_after") {
            resultImagePreview.value.splice(1, 1, BASE_URL + image.url)
            resultImageAfterId.value = image.id
          }
        })
      } else {
        fullname.value = ""
      }
    })
    const onSubmitAddUpdate = handleSubmit(async (values) => {
      if (values) {
        if (mode.value == "add") {
          if (resultImageUploadBefore.value) {
            await addResultTest(values)
            await handleUploadResultImage("add")
            cancelForm()
            getResultTestList(currentPage.value)
          } else {
            resultImageUploadError.value = "Ảnh kết quả là bắt buộc!"
          }
        } else {
          await handleUploadResultImage("")
          await updateResultTest(idResultTest.value, values)
          cancelForm()
          getResultTestList(currentPage.value)
        }
      }
    })
    const cancelForm = () => {
      closeAddUpdateModal()
      setMode("")
      resetForm()
      resultImageUploadBefore.value = ""
      resultImageUploadAfter.value = ""
      resultImagePreview.value = [2]
      resultImageUploadError.value = ""
    }
    const querySearch = async (queryString, cb) => {
      if (queryString != "") {
        await searchUser(queryString)
        cb(dataSearchUser.value)
      }
    }
    const handleSelect = (item) => {
      fullname.value = item.fullname
      user_id.value = item.id
      create_by.value = store.state.user.id
    }

    const handleChangeImageBefore = (image) => {
      if (image) {
        resultImageUploadBefore.value = image.target.files[0]
        resultImagePreview.value.splice(
          0,
          1,
          URL.createObjectURL(image.target.files[0])
        )
      }
    }

    const handleChangeImageAfter = (image) => {
      if (image) {
        resultImageUploadAfter.value = image.target.files[0]
        resultImagePreview.value.splice(
          1,
          1,
          URL.createObjectURL(image.target.files[0])
        )
      }
    }

    const handleUploadResultImage = async (act) => {
      const formDataBefore = new FormData()
      formDataBefore.append("images[]", resultImageUploadBefore.value)
      formDataBefore.append("imageable_type", "result_test")
      formDataBefore.append("type", "result_test_before")

      const formDataAfter = new FormData()
      formDataAfter.append("images[]", resultImageUploadAfter.value)
      formDataAfter.append("imageable_type", "result_test")
      formDataAfter.append("type", "result_test_after")

      if (act == "add") {
        if (newResultTestId?.value > 0) {
          formDataBefore.append("imageable_id", newResultTestId?.value)
          formDataAfter.append("imageable_id", newResultTestId?.value)
        }
        if (resultImageUploadBefore.value) {
          await uploadImage(formDataBefore)
        }

        if (resultImageUploadAfter.value) {
          await uploadImage(formDataAfter)
        }
      } else {
        formDataBefore.append("imageable_id", idResultTest?.value)
        formDataAfter.append("imageable_id", idResultTest?.value)
        if (resultImageUploadBefore.value) {
          if (resultImageBeforeId.value) {
            await updateImage(resultImageBeforeId.value, formDataBefore)
          } else {
            await uploadImage(formDataBefore)
          }
        }

        if (resultImageUploadAfter.value) {
          if (resultImageAfterId.value) {
            await updateImage(resultImageAfterId.value, formDataAfter)
          } else {
            await uploadImage(formDataAfter)
          }
        }
      }
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
      fullname,
      resultImagePreview,
      handleChangeImageBefore,
      handleChangeImageAfter,
      resultImageUploadError
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
    <el-form label-position="left" label-width="110px">
      <el-form-item label="Họ & Tên:">
        <el-autocomplete
          style="width: 100%"
          v-model="fullname"
          :fetch-suggestions="querySearch"
          popper-class="my-autocomplete"
          placeholder="Tìm kiếm người dùng..."
          :debounce="1500"
          @select="handleSelect"
          :disabled="mode == 'update' ? true : false"
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

      <el-form-item label="Ảnh mặt trước:">
        <el-button
          type="primary"
          size="mini"
          @click="$refs.resultTestImageBefore.click()"
          >Chọn ảnh</el-button
        >

        <input
          type="file"
          ref="resultTestImageBefore"
          style="display: none"
          @change="handleChangeImageBefore"
        />
        <div class="result-test-image">
          <el-image
            fit="cover"
            :src="resultImagePreview[0]"
            :preview-src-list="resultImagePreview"
          >
            <template #error>
              <div class="image-slot">
                <i class="el-icon-picture-outline"></i>
              </div>
            </template>
          </el-image>
        </div>
      </el-form-item>

      <el-form-item label="Ảnh mặt sau:">
        <el-button
          type="primary"
          size="mini"
          @click="$refs.resultTestImageAfter.click()"
          >Chọn ảnh</el-button
        >
        <input
          type="file"
          ref="resultTestImageAfter"
          style="display: none"
          @change="handleChangeImageAfter"
        />
        <div class="result-test-image">
          <el-image
            fit="cover"
            :src="resultImagePreview[1]"
            :preview-src-list="resultImagePreview"
          >
            <template #error>
              <div class="image-slot">
                <i class="el-icon-picture-outline"></i>
              </div>
            </template>
          </el-image>
        </div>
        <div class="text-red">{{ resultImageUploadError }}</div>
      </el-form-item>
    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button
          type="primary"
          @click="onSubmitAddUpdate"
          :loading="isLoadingAddUpdateResultTest"
          :disabled="isLoadingAddUpdateResultTest"
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
.result-test-image .el-image {
  width: 150px;
  height: 80px;
  border: 1px solid #ddd;
}
</style>
