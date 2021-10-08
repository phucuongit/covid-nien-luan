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
import moment from "moment"
export default defineComponent({
  setup() {
    const accountSchema = yup.object({
      fullname: yup.string().required("Họ tên là bắt buộc!"),
      password: yup.string(),
      identity_card: yup
        .string()
        .required("Chứng minh nhân dân là bắt buộc!")
        .matches("^[0-9]{9}$|^[0-9]{12}$", "CMND/CCCD không hợp lệ"),
      birthday: yup.date().required("Ngày sinh là bắt buộc!").nullable(),
      social_insurance: yup
        .string()
        .required("Bảo hiểm y tế là bắt buộc!")
        .matches("^[a-zA-Z0-9]{15}$", "Mã bảo hiểm phải 15 ký tự"),
      gender: yup.number().required("Giới tính là bắt buộc!"),
      phone: yup
        .string()
        .required("Số điện thoại là bắt buộc!")
        .matches("^0[1-9]{9}$", "Số điện thoại không hợp lệ")
        .nullable(),
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

    watch(store.state, async () => {
      user.value = store.state.user
      imageListShow.value = []
      user.value.images.map((image) => {
        if (image) {
          if (image.type == "avatar") {
            avatar.value = image
          }
          imageListShow.value.push(BASE_URL + image.url)
        }
      })

      console.log(user?.value)

      textFullname.value = user?.value?.fullname
      fullname.value = user?.value?.fullname
      password.value = user?.value?.password
      identity_card.value = user?.value?.identity_card
      birthday.value = moment(user?.value?.birthday).format("YYYY-MM-DD")
      social_insurance.value = user?.value?.social_insurance
      gender.value = user?.value?.gender
      address.value = user?.value?.address
      phone.value = user?.value?.phone
      province_id.value = user?.value?.address_full?.province?.id
      await getDistrictList(province_id.value)
      district_id.value = user?.value?.address_full?.district?.id
      await getVillageList(district_id.value)
      village_id.value = user?.value?.address_full?.village?.id
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
      checkErrorPassword.value = ""
      getAccount()
    }

    const onSubmitAccount = handleSubmit(async (values) => {
      if (values) {
        if (checkErrorPassword.value == "") {
          if (password.value == undefined) {
            delete values.password
          }
          delete values.phone
          delete values.identity_card
          delete values.social_insurance
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

    const { value: fullname } = useField("fullname")
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
      BASE_URL,
      percent,
      format
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
          class="mb-10"
        >
          Đổi ảnh
        </el-button>
        <el-button
          type="danger"
          size="mini"
          @click="removeAvatar"
          :loading="isLoadingRemoveImage"
          class="mb-10"
        >
          Xóa ảnh
        </el-button>
        <el-progress
          v-if="percent"
          :percentage="percent"
          :format="format"
        ></el-progress>
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
                <el-input v-model="phone" disabled></el-input>
                <div class="text-red">{{ errors.phone }}</div>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="24">
              <el-form-item label="CMND:">
                <el-input v-model="identity_card" disabled></el-input>
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
                <el-input v-model="social_insurance" disabled></el-input>
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
                  placeholder="Chọn ngày sinh"
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
</style>
