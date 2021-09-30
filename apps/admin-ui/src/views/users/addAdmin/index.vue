<script>
import { defineComponent, inject, watch, ref } from "vue"
import * as yup from "yup"
import { useForm, useField } from "vee-validate"
import userType from "../useUsers"
import useAddAdmin from "../addUser/useAddUser.ts"
import useGetAddress from "../useGetAddress.ts"
import useUploadImage from "../../uploadImages/useUploadImage.ts"
import useBaseUrl from "@/services/baseUrl.ts"

const AddUser = defineComponent({
  name: "AddAdmin",
  props: {
    isVisible: {
      type: Boolean,
      default: false
    },
    selectUser: {
      type: Object,
      default: userType
    },
    mode: {
      type: String,
      default: ""
    }
  },
  setup(props) {
    const addUserSchema = yup.object({
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
      address: yup.string().required("Địa chỉ là bắt buộc!"),
      role_id: yup.number().required("Vai trò người dùng là bắt buộc!")
    })

    const closeAddAdminModal = inject("closeAddAdminModal")
    const getListUsers = inject("getListUsers")
    const currentPage = inject("currentPage")
    const setMode = inject("setMode")

    const { isLoadingAddUser, createUser, updateUser, user_new_id } =
      useAddAdmin()
    const {
      provinceList,
      getProvinceList,
      getDistrictList,
      districtList,
      getVillageList,
      villageList
    } = useGetAddress()

    getProvinceList()

    const isLoadingAdd = ref(false)
    const isShow = ref()
    const isMode = ref()
    const user = ref()
    const idUserSelect = ref()
    const checkErrorPassword = ref()
    const avatar = ref()
    const avatarPreview = ref([])
    watch(props, () => {
      isShow.value = props.isVisible
      isMode.value = props.mode
      if (
        isMode.value == "update" &&
        props.selectUser[0]?.role.name == "admin"
      ) {
        user.value = props.selectUser[0]
        idUserSelect.value = user.value.id
        if (user?.value?.images[0]) {
          avatarPreview.value.push(BASE_URL + user?.value?.images[0].url)
        }

        // Truyền giá trị lên form
        fullname.value = user.value.fullname
        username.value = user.value.username
        identity_card.value = user.value.identity_card
        birthday.value = user.value.birthday
        social_insurance.value = user.value.social_insurance
        gender.value = user.value.gender
        province_id.value = user.value.address_full.province.id

        // Lấy huyện
        getDistrictList(province_id.value)
        district_id.value = user.value.address_full.district.id
        // Lấy xã
        getVillageList(district_id.value)
        village_id.value = user.value.address_full.village.id
        address.value = user.value.address
        phone.value = user.value.phone

        role_id.value = 1
      }
    })

    const { handleSubmit, errors, resetForm } = useForm({
      validationSchema: addUserSchema
    })

    const { uploadImage, updateImage } = useUploadImage()
    const { BASE_URL } = useBaseUrl()

    const onSubmitAdd = handleSubmit(async (values) => {
      if (values) {
        if (password?.value?.length > 0 && password?.value?.length < 6) {
          checkErrorPassword.value = "Mật khẩu phải ít nhất 6 kí tự"
        } else {
          if (isMode.value == "update") {
            if (password.value == undefined) {
              delete values.password
            }
            if (avatar?.value) {
              await handleUploadAvatar()
            }
            await updateUser(idUserSelect.value, values)
            getListUsers(currentPage.value)
          } else {
            await createUser(values)
            if (avatar.value) {
              await handleUploadAvatar()
            }
            getListUsers(currentPage.value)
          }
          cancelForm()
        }
      }
    })

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
      resetForm()
      closeAddAdminModal()
      setMode("")
      avatarPreview.value = []
      avatar.value = ""
      checkErrorPassword.value = ""
    }

    const addAvatar = (image) => {
      if (image.target.files[0]) {
        avatarPreview.value = []
        avatar.value = image.target.files[0]
        avatarPreview.value.push(URL.createObjectURL(image.target.files[0]))
      }
    }

    const handleUploadAvatar = () => {
      const formData = new FormData()
      formData.append("images[]", avatar.value)
      formData.append("imageable_type", "user")
      formData.append("type", "avatar")

      if (isMode.value == "add") {
        if (user_new_id.value > 0) {
          formData.append("imageable_id", user_new_id.value)
          if (avatar.value) {
            uploadImage(formData)
          }
        }
      } else {
        if (avatar.value != "") {
          formData.append("imageable_id", idUserSelect.value)
          if (user?.value?.images[0]) {
            updateImage(user?.value?.images[0]?.id, formData)
          } else {
            uploadImage(formData)
          }
        }
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
    const { value: role_id } = useField("role_id")
    const { value: village_id } = useField("village_id")
    const { value: province_id } = useField("province_id")
    const { value: district_id } = useField("district_id")

    return {
      isShow,
      cancelForm,
      errors,
      isLoadingAdd,
      fullname,
      username,
      password,
      identity_card,
      birthday,
      gender,
      address,
      phone,
      role_id,
      village_id,
      social_insurance,
      province_id,
      district_id,
      onSubmitAdd,
      isLoadingAddUser,
      provinceList,
      handleChangeProvince,
      handleChangeDistrict,
      districtList,
      villageList,
      isMode,
      checkErrorPassword,
      addAvatar,
      avatarPreview
    }
  }
})

export default AddUser
</script>

<template>
  <el-dialog
    title="Người quản trị"
    v-model="isShow"
    width="80%"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <el-form label-position="left" label-width="120px">
      <el-row :gutter="30">
        <el-col :md="5" :sm="5" :xs="24">
          <div class="admin-add-avt text-center">
            <el-image
              fit="cover"
              :src="avatarPreview[0]"
              :preview-src-list="avatarPreview"
            >
            </el-image>
          </div>
          <div class="text-center mt-10">
            <input
              type="file"
              @change="addAvatar"
              ref="userAddAvatarInput"
              style="display: none"
            />

            <el-button
              type="primary"
              size="mini"
              @click="$refs.userAddAvatarInput.click()"
              class="mb-10"
            >
              Đổi ảnh
            </el-button>
          </div>
        </el-col>

        <el-col :md="19" :sm="19" :xs="24">
          <el-row :gutter="30">
            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Họ tên:">
                <el-input v-model="fullname"></el-input>
                <div class="text-red">{{ errors.fullname }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Tên đăng nhập:">
                <el-input
                  v-model="username"
                  :disabled="isMode == 'update' ? true : false"
                ></el-input>
                <div class="text-red">{{ errors.username }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Mật khẩu:">
                <el-input
                  type="password"
                  show-password
                  v-model="password"
                ></el-input>
                <div class="text-red">
                  {{ errors.password }} {{ checkErrorPassword }}
                </div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Số điện thoại:">
                <el-input v-model="phone"></el-input>
                <div class="text-red">{{ errors.phone }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="CMND:">
                <el-input v-model="identity_card"></el-input>
                <div class="text-red">{{ errors.identity_card }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
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

            <el-col :md="12" :sm="12" :xs="24">
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

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Số bảo hiểm:">
                <el-input v-model="social_insurance"></el-input>
                <div class="text-red">{{ errors.social_insurance }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
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

            <el-col :md="12" :sm="12" :xs="24">
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

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Vai trò:">
                <el-select
                  style="width: 100%"
                  placeholder="Chọn vai trò người dùng"
                  v-model="role_id"
                >
                  <el-option label="Admin" :value="1"></el-option>
                </el-select>
                <div class="text-red">{{ errors.role_id }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Địa chỉ:">
                <el-input v-model="address"></el-input>
                <div class="text-red">{{ errors.address }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Giới tính:">
                <el-radio-group v-model="gender">
                  <el-radio :label="1">Nam</el-radio>
                  <el-radio :label="0">Nữ</el-radio>
                </el-radio-group>
                <div class="text-red">{{ errors.gender }}</div>
              </el-form-item>
            </el-col>
          </el-row>
        </el-col>
      </el-row>
    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button
          type="primary"
          @click="onSubmitAdd"
          :disabled="isLoadingAddUser"
          :loading="isLoadingAddUser"
          class="btn-11385e"
        >
          {{ isMode == "update" ? "Cập nhật" : "Thêm" }}
        </el-button>
        <el-button @click="cancelForm">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<style scoped>
.el-form--inline .el-form-item {
  margin-left: 15px;
}

.admin-add-avt .el-image {
  width: 150px;
  height: 150px;
  border: 1px solid #ddd;
  border-radius: 50%;
}
</style>
