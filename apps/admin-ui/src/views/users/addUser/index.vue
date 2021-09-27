<script>
import { defineComponent, inject, watch, ref } from "vue"
import * as yup from "yup"
import { useForm, useField } from "vee-validate"
import userType from "../useUsers"
import useAddUser from "./useAddUser.ts"
import useGetAddress from "../useGetAddress.ts"

const AddUser = defineComponent({
  name: "AddUser",
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
      type: Boolean,
      default: false
    }
  },
  setup(props) {
    const addUserSchema = yup.object({
      fullname: yup.string().required("Họ tên là bắt buộc!"),
      identity_card: yup
        .string()
        .required("Chứng minh nhân dân là bắt buộc!")
        .matches("^[0-9]{9}$|^[0-9]{12}$", "CMND/CCCD không hợp lệ"),
      birthday: yup.string().required("Ngày sinh là bắt buộc!"),
      social_insurance: yup.string().required("Bảo hiểm y tế là bắt buộc!"),
      gender: yup.number().required("Giới tính là bắt buộc!"),
      avatar: yup.string(),
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

    const closeAddUserModal = inject("closeAddUserModal")
    const getListUsers = inject("getListUsers")
    const currentPage = inject("currentPage")
    const setMode = inject("setMode")

    const { isLoadingAddUser, createUser, updateUser } = useAddUser()
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
    watch(props, () => {
      isShow.value = props.isVisible
      isMode.value = props.mode
      if (
        isMode.value == "update" &&
        props.selectUser[0]?.role.name == "user"
      ) {
        user.value = props.selectUser[0]
        idUserSelect.value = user.value.id
        fullname.value = user.value.fullname
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
        avatar.value = user.value.avatar
        phone.value = user.value.phone

        role_id.value = 2
      }
    })

    const { handleSubmit, errors, resetForm } = useForm({
      validationSchema: addUserSchema
    })

    // const error_identity_card = ref('');
    const onSubmitAdd = handleSubmit(async (values) => {
      if (values) {
        // if (identity_card.value.length == 9 || identity_card.value.length == 12) {
        if (isMode.value == "update") {
          await updateUser(idUserSelect.value, values)
        } else {
          await createUser(values)
        }
        getListUsers(currentPage.value)
        cancelForm()
      }
      // } else {
      //   error_identity_card.value = "CMND/CCCD không hợp lệ"
      // }
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
      setMode("")
      resetForm()
      closeAddUserModal()
    }

    const { value: fullname } = useField("fullname")
    const { value: identity_card } = useField("identity_card")
    const { value: birthday } = useField("birthday")
    const { value: social_insurance } = useField("social_insurance")
    const { value: gender } = useField("gender")
    const { value: avatar } = useField("avatar")
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
      identity_card,
      birthday,
      gender,
      avatar,
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
      isMode
      // error_identity_card
    }
  }
})

export default AddUser
</script>

<template>
  <el-dialog
    title="Người dùng"
    v-model="isShow"
    width="74%"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <el-form label-position="left" label-width="120px">
      <el-row :gutter="30">
        <el-col :md="8" :sm="12" :xs="24">
          <el-form-item label="Họ tên:">
            <el-input v-model="fullname"></el-input>
            <div class="text-red">{{ errors.fullname }}</div>
          </el-form-item>
        </el-col>

        <el-col :md="8" :sm="12" :xs="24">
          <el-form-item label="Số điện thoại:">
            <el-input v-model="phone"></el-input>
            <div class="text-red">{{ errors.phone }}</div>
          </el-form-item>
        </el-col>

        <el-col :md="8" :sm="12" :xs="24">
          <el-form-item label="CMND / CCCD:">
            <el-input v-model="identity_card"></el-input>
            <div class="text-red">{{ errors.identity_card }}</div>
          </el-form-item>
        </el-col>

        <el-col :md="8" :sm="12" :xs="24">
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

        <el-col :md="8" :sm="12" :xs="24">
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

        <el-col :md="8" :sm="12" :xs="24">
          <el-form-item label="Số bảo hiểm:">
            <el-input v-model="social_insurance"></el-input>
            <div class="text-red">{{ errors.social_insurance }}</div>
          </el-form-item>
        </el-col>

        <el-col :md="8" :sm="12" :xs="24">
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

        <el-col :md="8" :sm="12" :xs="24">
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

        <el-col :md="8" :sm="12" :xs="24">
          <el-form-item label="Vai trò:">
            <el-select
              style="width: 100%"
              placeholder="Chọn vai trò người dùng"
              v-model="role_id"
            >
              <!-- <el-option label="Admin" :value="1"></el-option> -->
              <el-option label="User" :value="2"></el-option>
            </el-select>
            <div class="text-red">{{ errors.role_id }}</div>
          </el-form-item>
        </el-col>

        <el-col :md="8" :sm="12" :xs="24">
          <el-form-item label="Địa chỉ:">
            <el-input v-model="address"></el-input>
            <div class="text-red">{{ errors.address }}</div>
          </el-form-item>
        </el-col>

        <el-col :md="8" :sm="12" :xs="24">
          <el-form-item label="Giới tính:">
            <el-radio-group v-model="gender">
              <el-radio :label="1">Nam</el-radio>
              <el-radio :label="0">Nữ</el-radio>
            </el-radio-group>
            <div class="text-red">{{ errors.gender }}</div>
          </el-form-item>
        </el-col>

        <el-col :md="8" :sm="12" :xs="24">
          <el-form-item label="Ảnh đại diện:"> </el-form-item>
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
</style>
