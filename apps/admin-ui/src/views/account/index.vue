<script>
import { defineComponent, ref, watch } from "vue"
import useAccount from "./useAccount.ts"
import * as yup from "yup"
import { useForm, useField } from "vee-validate"
import useGetAddress from "../users/useGetAddress.ts"
import { useStore } from "vuex"
import useUploadImage from "../uploadImages/useUploadImage.ts"
import useBaseUrl from "@/services/baseUrl.ts"
import { ElMessageBox } from "element-plus"
export default defineComponent({
  setup() {
    const accountSchema = yup.object({
      fullname: yup.string().required("Họ tên là bắt buộc!"),
      username: yup.string().required("Tên đăng nhập là bắt buộc!").min(6),
      password: yup.string(),
      identity_card: yup
        .string()
        .required("Chứng minh nhân dân là bắt buộc!")
        .matches("^[0-9]{9}$|^[0-9]{12}$", "CMND/CCCD không hợp lệ"),
      birthday: yup.date().required("Ngày sinh là bắt buộc!"),
      social_insurance: yup.string().required("Bảo hiểm y tế là bắt buộc!"),
      gender: yup.number().required("Giới tính là bắt buộc!"),
      phone: yup
        .string()
        .required("Số điện thoại là bắt buộc!")
        .matches("^0[1-9]{9}$", "Số điện thoại không hợp lệ"),
      province_id: yup.number().required("Tỉnh / TP là bắt buộc"),
      district_id: yup.number().required("Huyện / Phường là bắt buộc"),
      village_id: yup.number().required("Xã là bắt buộc!"),
      address: yup.string().required("Địa chỉ là bắt buộc!")
    })
    const { handleSubmit, errors } = useForm({
      validationSchema: accountSchema
    })

    const store = useStore()
    const { BASE_URL } = useBaseUrl() // domain name
    const { getAccount, isLoadingUpdateAccount, updateAccount } = useAccount()
    const {
      uploadImage,
      deleteImage,
      isLoadingRemoveImage,
      isLoadingUpdateImage,
      updateImage,
      percent
    } = useUploadImage()
    const {
      getProvinceList,
      getDistrictList,
      getVillageList,
      provinceList,
      districtList,
      villageList
    } = useGetAddress()

    getProvinceList()
    getAccount()
    const textFullname = ref() // Hiển thị tên, tránh TH edit bên form làm nó thay đổi
    const user = ref(store.state.user)
    const checkErrorPassword = ref("")
    const checkErrorAvatar = ref()
    const avatar = ref()
    const avatarUpload = ref()
    const imageListShow = ref([]) // Show hình ảnh lớn
    const images = ref()
    const imageListShowPreUpload = ref([]) // Xem hình ảnh gốc trước khi upload
    const imageListPreUpload = ref([]) // Hiển thị hình ảnh trước khi upload
    const uploadListImages = ref() // Danh sách sẽ upload
    const numbersProgress = ref(0) // Check để hiển thị progress bar

    watch(store.state, () => {
      user.value = store.state.user
      imageListShow.value = []
      images.value = user.value.images
      user.value.images.map((image) => {
        if (image) {
          if (image.type == "avatar") {
            avatar.value = image
          }
          imageListShow.value.push(BASE_URL + image.url)
        } else {
          avatar.value = ""
        }
      })

      textFullname.value = user?.value?.fullname
      fullname.value = user?.value?.fullname
      username.value = user?.value?.username
      password.value = user?.value?.password
      identity_card.value = user?.value?.identity_card
      birthday.value = user?.value?.birthday
      social_insurance.value = user?.value?.social_insurance
      gender.value = user?.value?.gender
      address.value = user?.value?.address
      phone.value = user?.value?.phone
      province_id.value = user?.value?.address_full?.province?.id
      getDistrictList(province_id.value)
      district_id.value = user?.value?.address_full?.district?.id
      getVillageList(district_id.value)
      village_id.value = user?.value?.address_full?.village?.id
    })

    const accountImages = (values) => {
      uploadListImages.value = values.target.files
      imageListPreUpload.value = []
      for (let i = 0; i < values.target.files.length; i++) {
        let fileReader = new FileReader()
        fileReader.readAsDataURL(values.target.files[i])
        fileReader.addEventListener("load", () => {
          imageListPreUpload.value.push({
            id: i + 1,
            name: values.target.files[i].name,
            url: fileReader.result
          })
          imageListShowPreUpload.value.push(fileReader.result)
        })
      }
      percent.value = 0
    }
    const handleUploadListImages = async () => {
      for (let i = 0; i < uploadListImages?.value?.length; i++) {
        const dataForm = new FormData()
        dataForm.append("images[]", uploadListImages.value[i])
        dataForm.append("imageable_id", user?.value?.id)
        dataForm.append("imageable_type", "user")
        dataForm.append("type", "")
        numbersProgress.value = i + 1
        await uploadImage(dataForm)
        if (i != uploadListImages?.value?.length - 1) {
          percent.value = 0
        }
      }
      uploadListImages.value = []
      numbersProgress.value = 0
      imageListPreUpload.value = []
      getAccount()
    }

    const handleChangeProvince = () => {
      getDistrictList(province_id.value)
      district_id.value = ref()
      village_id.value = ref()
    }
    const handleChangeDistrict = () => {
      getVillageList(district_id.value)
      village_id.value = ref()
    }
    const cancelForm = () => {
      checkErrorPassword.value = ""
      getAccount()
    }
    const onSubmitAccount = handleSubmit(async (values) => {
      if (values) {
        if (password?.value?.length > 0 && password?.value?.length < 6) {
          checkErrorPassword.value = "Mật khẩu phải ít nhất 6 kí tự"
        } else {
          if (password.value == undefined) {
            delete values.password
          }
          await updateAccount(values)
          getAccount()
        }
      }
    })
    const checkPassword = () => {
      if (password?.value?.length > 0 && password?.value?.length < 6) {
        checkErrorPassword.value = "Mật khẩu phải ít nhất 6 kí tự"
      } else {
        checkErrorPassword.value = ""
      }
    }
    const addAvatar = async (event) => {
      avatarUpload.value = event.target.files[0]
      const dataForm = new FormData()
      dataForm.append("images[]", avatarUpload.value)
      dataForm.append("imageable_id", user?.value?.id)
      dataForm.append("imageable_type", "user")
      dataForm.append("type", "avatar")

      if (avatar.value) {
        await updateImage(avatar?.value?.id, dataForm)
      } else {
        if (avatarUpload.value.name != "") {
          await uploadImage(dataForm)
        }
      }
      await getAccount()
      checkErrorAvatar.value = ""
    }
    const removeAvatar = async () => {
      await ElMessageBox({
        type: "warning",
        title: "Thông báo",
        message: "Bạn muốn xóa ảnh đại diện?",
        confirmButtonText: "Xóa",
        showCancelButton: true,
        cancelButtonText: "Hủy"
      }).then(async () => {
        if (avatar.value) {
          await deleteImage(avatar.value.id)
          await getAccount()
          avatar.value = ""
        } else {
          checkErrorAvatar.value = "Chưa có ảnh đại diện"
        }
      })
    }
    const getIdImage = async (id) => {
      if (id > 0) {
        await ElMessageBox({
          type: "warning",
          title: "Thông báo",
          message: "Bạn muốn xóa ảnh này?",
          confirmButtonText: "Xóa",
          showCancelButton: true,
          cancelButtonText: "Hủy"
        }).then(async () => {
          await deleteImage(id)
          if (id == avatar?.value?.id) {
            avatar.value = ""
          }
          await getAccount()
        })
      }
    }

    const { value: fullname } = useField("fullname")
    const { value: username } = useField("username")
    const { value: password } = useField("password")
    const { value: identity_card } = useField("identity_card")
    const { value: birthday } = useField("birthday")
    const { value: social_insurance } = useField("social_insurance")
    const { value: gender } = useField("gender")
    const { value: address } = useField("address")
    const { value: phone } = useField("phone")
    const { value: village_id } = useField("village_id")
    const { value: province_id } = useField("province_id")
    const { value: district_id } = useField("district_id")

    const format = (percentage) => {
      return percentage === 100 ? "Uploaded" : `${percentage}%`
    }

    return {
      isLoadingUpdateAccount,
      errors,
      cancelForm,
      textFullname,
      fullname,
      username,
      password,
      identity_card,
      birthday,
      social_insurance,
      gender,
      avatar,
      address,
      phone,
      village_id,
      province_id,
      district_id,
      provinceList,
      districtList,
      villageList,
      handleChangeProvince,
      handleChangeDistrict,
      checkErrorPassword,
      onSubmitAccount,
      checkPassword,
      addAvatar,
      removeAvatar,
      checkErrorAvatar,
      isLoadingRemoveImage,
      isLoadingUpdateImage,
      imageListShow,
      accountImages,
      imageListPreUpload,
      BASE_URL,
      images,
      getIdImage,
      percent,
      format,
      numbersProgress,
      handleUploadListImages,
      imageListShowPreUpload
    }
  }
})
</script>

<template>
  <el-row :gutter="30">
    <el-col :lg="5" :md="6" :sm="8" :xs="24" class="text-center">
      <div class="account-left-avt">
        <el-image
          fit="cover"
          :src="BASE_URL + avatar?.url"
          :preview-src-list="imageListShow"
        >
        </el-image>

        <div class="text-red mt-10">{{ checkErrorAvatar }}</div>
      </div>
      <div class="account-left-info mt-10">
        <b>{{ textFullname }}</b>
      </div>
      <div class="account-left-btn mt-10">
        <input
          type="file"
          @change="addAvatar"
          ref="accountAvatarInput"
          style="display: none"
        />
        <el-button
          type="primary"
          size="mini"
          @click="$refs.accountAvatarInput.click()"
          :loading="isLoadingUpdateImage"
          class="mt-10"
        >
          Đổi ảnh
        </el-button>
        <el-button
          type="danger"
          size="mini"
          @click="removeAvatar"
          :loading="isLoadingRemoveImage"
          class="mt-10"
        >
          Xóa ảnh
        </el-button>
      </div>
    </el-col>
    <el-col :lg="1" :md="1" :sm="0" :xs="0"></el-col>
    <el-col :lg="18" :md="17" :sm="16" :xs="24">
      <div class="account-welcome">
        <h3 class="mb-30">Chào mừng quay lại, {{ textFullname }}!</h3>
      </div>
      <div class="account-right-info">
        <el-form label-position="left" label-width="120px">
          <el-row :gutter="30">
            <el-col :md="12" :sm="24">
              <el-form-item label="Họ tên:">
                <el-input v-model="fullname"></el-input>
                <div class="text-red">{{ errors.fullname }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Tên đăng nhập:">
                <el-input disabled v-model="username"></el-input>
                <div class="text-red">{{ errors.username }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Mật khẩu:">
                <el-input
                  type="password"
                  show-password
                  v-model="password"
                  v-on:keyup="checkPassword"
                ></el-input>
                <div class="text-red">
                  {{ errors.password }} {{ checkErrorPassword }}
                </div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Số điện thoại:">
                <el-input v-model="phone"></el-input>
                <div class="text-red">{{ errors.phone }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="CMND:">
                <el-input v-model="identity_card"></el-input>
                <div class="text-red">{{ errors.identity_card }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Tỉnh / TP:">
                <el-select
                  style="width: 100%"
                  placeholder="Chọn tỉnh / TP..."
                  v-model="province_id"
                  @change="handleChangeProvince"
                >
                  <el-option
                    v-for="province in provinceList"
                    :key="province.id"
                    :label="province.name"
                    :value="province.id"
                  >
                  </el-option>
                </el-select>
                <div class="text-red">{{ errors.province_id }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Huyện / Phường:">
                <el-select
                  style="width: 100%"
                  placeholder="Chọn phường..."
                  v-model="district_id"
                  @change="handleChangeDistrict"
                >
                  <el-option
                    v-for="district in districtList"
                    :key="district.id"
                    :label="district.name"
                    :value="district.id"
                  >
                  </el-option>
                </el-select>
                <div class="text-red">{{ errors.district_id }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Số bảo hiểm:">
                <el-input v-model="social_insurance"></el-input>
                <div class="text-red">{{ errors.social_insurance }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Xã / Thị trấn:">
                <el-select
                  style="width: 100%"
                  placeholder="Chọn xã / Thị trấn..."
                  v-model="village_id"
                >
                  <el-option
                    v-for="village in villageList"
                    :key="village.id"
                    :label="village.name"
                    :value="village.id"
                  >
                  </el-option>
                </el-select>
                <div class="text-red">{{ errors.village_id }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Ngày sinh:">
                <el-date-picker
                  style="width: 100%"
                  value-format="YYYY-MM-DD"
                  type="date"
                  placeholder="Pick a date"
                  v-model="birthday"
                ></el-date-picker>
                <div class="text-red">{{ errors.birthday }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Địa chỉ:">
                <el-input v-model="address"></el-input>
                <div class="text-red">{{ errors.address }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="Giới tính:">
                <el-radio-group v-model="gender">
                  <el-radio :label="1">Nam</el-radio>
                  <el-radio :label="0">Nữ</el-radio>
                </el-radio-group>
                <div class="text-red">{{ errors.gender }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="24" :sm="24">
              <el-form-item label="Hình ảnh:">
                <div class="account-image-list">
                  <span v-for="img in images" :key="img?.id" class="wrap-image">
                    <i
                      class="el-icon-delete icon-delete-image"
                      @click="getIdImage(img?.id)"
                      title="Xóa"
                    ></i>
                    <el-image
                      style="width: 100px; height: 100px"
                      fit="cover"
                      :src="BASE_URL + img?.url"
                      :preview-src-list="imageListShow"
                    >
                    </el-image>
                  </span>
                </div>
                <el-button
                  type="primary"
                  size="mini"
                  @click="$refs.uploadImageListInput.click()"
                  >Thêm ảnh</el-button
                >
                <el-button
                  type="primary"
                  size="mini"
                  @click="handleUploadListImages"
                  >Lưu ảnh</el-button
                >
                <input
                  type="file"
                  multiple
                  @change="accountImages"
                  ref="uploadImageListInput"
                  accept="image/*"
                  style="display: none"
                />
                <div class="img-preview mt-20">
                  <div
                    v-for="img in imageListPreUpload"
                    :key="img?.id"
                    class="img-preview-wrap"
                  >
                    <el-image
                      style="width: 35px; height: 35px"
                      fit="cover"
                      :src="img?.url"
                      :preview-src-list="imageListShowPreUpload"
                    >
                    </el-image>
                    <span style="margin-left: 10px">{{ img?.name }}</span>
                    <el-progress
                      v-if="numbersProgress == img?.id"
                      :percentage="percent"
                      :format="format"
                    ></el-progress>
                  </div>
                </div>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
        <div class="text-right">
          <el-button
            type="primary"
            :loading="isLoadingUpdateAccount"
            :disabled="isLoadingUpdateAccount"
            @click="onSubmitAccount"
          >
            Cập nhật
          </el-button>
          <el-button @click="cancelForm">Hủy</el-button>
        </div>
      </div>
    </el-col>
  </el-row>
</template>

<style scoped>
.account-left-avt .el-image {
  width: 180px;
  height: 180px;
  border-radius: 50%;
  border: 1px solid #ddd;
}
.icon-delete-image {
  position: absolute;
  right: 0px;
  z-index: 1;
  padding: 3px;
  background: #f0f0f0;
  color: red;
  cursor: pointer;
}
.icon-delete-image:hover {
  font-weight: bold;
}
.wrap-image {
  display: inline-block;
  margin-right: 5px;
  position: relative;
}
</style>
