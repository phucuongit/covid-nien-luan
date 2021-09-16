<script>
import { defineComponent, inject, watch, ref, provide } from "vue"
import * as yup from "yup"
import { useForm, useField } from "vee-validate"
import userType from "../useUsers"
import useAddUser from "./useAddUser.ts"
import useGetAddress from "../useGetAddress.ts"

const AddUpdateUser = defineComponent({
  name: "AddUser",
  props: {
    isVisible: {
      type: Boolean,
      default: false
    },
    selectUser: {
      type: Object,
      default: userType
    }
  },
  setup(props) {
    const addUpdateUserSchema = yup.object({
      fullname: yup.string().required("Họ tên là bắt buộc!"),
      // username: yup.string().required("Tên đăng nhập là bắt buộc!"),
      // password: yup.string().required("Mật khẩu là bắt buộc!"),
      identity_card: yup
        .string()
        .required("Chứng minh nhân dân là bắt buộc!")
        .matches("^[0-9]{9}$", "CMND/CCCD không hợp lệ"),
      birthday: yup.date().required("Ngày sinh là bắt buộc!"),
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

    const closeAddUpdateUserModal = inject("closeAddUpdateUserModal")
    const getListUsers = inject("getListUsers")
    const currentPage = inject("currentPage")

    const { isLoadingAddUpdateUser, createUser, updateUser } = useAddUser()
    const {
      provinceList,
      getProvinceList,
      getDistrictList,
      districtList,
      getVillageList,
      villageList
    } = useGetAddress()

    getProvinceList()

    const isLoadingAddUpdate = ref(false)
    const isShow = ref()
    // const idUserSelect = ref(0)
    watch(props, () => {
      isShow.value = props.isVisible
      // idUserSelect.value = props.selectUser[0].id
      // fullname.value = props.selectUser[0].fullname
      // identity_card.value = props.selectUser[0].identity_card
      // birthday.value = props.selectUser[0].birthday
      // social_insurance.value = props.selectUser[0].social_insurance
      // gender.value = props.selectUser[0].gender
      // address.value = props.selectUser[0].address
      // avatar.value = props.selectUser[0].avatar
      // phone.value = props.selectUser[0].phone
      // role_id.value = props.selectUser[0].role_id
      // village_id.value = props.selectUser[0].village_id
    })

    const { handleSubmit, errors, resetForm } = useForm({
      validationSchema: addUpdateUserSchema
    })

    const onSubmitAddUpdate = handleSubmit(async (values) => {
      if (values) {
        createUser(values)
        getListUsers(currentPage.value)
        cancelForm()
      }
    })

    const handleChangeProvince = () => {
      getDistrictList(province_id.value)
    }

    const handleChangeDistrict = () => {
      getVillageList(district_id.value)
    }

    const cancelForm = () => {
      resetForm()
      closeAddUpdateUserModal()
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
      isLoadingAddUpdate,
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
      onSubmitAddUpdate,
      isLoadingAddUpdateUser,
      provinceList,
      handleChangeProvince,
      handleChangeDistrict,
      districtList,
      villageList
    }
  }
})

export default AddUpdateUser
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
        <el-col :span="8">
          <el-form-item label="Họ tên:">
            <el-input v-model="fullname"></el-input>
            <div class="text-red">{{ errors.fullname }}</div>
          </el-form-item>
        </el-col>

        <el-col :span="8">
          <el-form-item label="Số điện thoại:">
            <el-input v-model="phone"></el-input>
            <div class="text-red">{{ errors.phone }}</div>
          </el-form-item>
        </el-col>

        <el-col :span="8">
          <el-form-item label="CMND:">
            <el-input v-model="identity_card"></el-input>
            <div class="text-red">{{ errors.identity_card }}</div>
          </el-form-item>
        </el-col>

        <el-col :span="8">
          <el-form-item label="Tỉnh / TP:">
            <el-select
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

        <!-- <el-col :span="8">
          <el-form-item label="Tên đăng nhập:">
            <el-input v-model="username"></el-input>
            <div class="text-red">{{ errors.username }}</div>
          </el-form-item>
        </el-col> -->

        <el-col :span="8">
          <el-form-item label="Huyện / Phường:">
            <el-select
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

        <!-- <el-col :span="8">
          <el-form-item label="Mật khẩu:">
            <el-input v-model="password" show-password></el-input>
            <div class="text-red">{{ errors.password }}</div>
          </el-form-item>
        </el-col> -->

        <el-col :span="8">
          <el-form-item label="Số bảo hiểm:">
            <el-input v-model="social_insurance"></el-input>
            <div class="text-red">{{ errors.social_insurance }}</div>
          </el-form-item>
        </el-col>

        <el-col :span="8">
          <el-form-item label="Xã / Thị trấn:">
            <el-select placeholder="Chọn xã / Thị trấn..." v-model="village_id">
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

        <el-col :span="8">
          <el-form-item label="Ngày sinh:">
            <el-date-picker
              value-format="YYYY-MM-DD"
              type="date"
              placeholder="Pick a date"
              v-model="birthday"
            ></el-date-picker>
            <div class="text-red">{{ errors.birthday }}</div>
          </el-form-item>
        </el-col>

        <el-col :span="8">
          <el-form-item label="Vai trò:">
            <el-select placeholder="Chọn vai trò người dùng" v-model="role_id">
              <el-option label="Admin" :value="1"></el-option>
              <el-option label="User" :value="2"></el-option>
            </el-select>
            <div class="text-red">{{ errors.role_id }}</div>
          </el-form-item>
        </el-col>

        <el-col :span="8">
          <el-form-item label="Địa chỉ:">
            <el-input v-model="address"></el-input>
            <div class="text-red">{{ errors.address }}</div>
          </el-form-item>
        </el-col>

        <el-col :span="8">
          <el-form-item label="Giới tính:">
            <el-radio-group v-model="gender">
              <el-radio :label="1">Nam</el-radio>
              <el-radio :label="0">Nữ</el-radio>
            </el-radio-group>
            <div class="text-red">{{ errors.gender }}</div>
          </el-form-item>
        </el-col>

        <el-col :span="8">
          <el-form-item label="Ảnh đại diện:"> </el-form-item>
        </el-col>
      </el-row>
    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button
          type="primary"
          @click="onSubmitAddUpdate"
          :loading="isLoadingAddUpdateUser"
          class="btn-11385e"
        >
          Add
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
