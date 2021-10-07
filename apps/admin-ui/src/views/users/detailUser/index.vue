<script>
import { defineComponent, ref, watch, inject } from "vue"
import userType from "../useUsers"
import useBaseUrl from "@/services/baseUrl.ts"

const AddUser = defineComponent({
  name: "DetailUser",
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
    const visible = ref()
    const user = ref()
    const imagePreview = ref([])
    const { BASE_URL } = useBaseUrl()
    watch(props, () => {
      visible.value = props.isVisible
      if (props.selectUser[0]) {
        user.value = props.selectUser[0]
        imagePreview.value.push(BASE_URL + user.value?.images[0]?.url)
      }
    })

    const closeDetailUser = inject("closeDetailUser")
    const cancelForm = () => {
      closeDetailUser()
      user.value = ""
    }

    return {
      visible,
      cancelForm,
      user,
      BASE_URL,
      imagePreview
    }
  }
})

export default AddUser
</script>

<template>
  <el-dialog
    title="Thông tin chi tiết"
    v-model="visible"
    width="80%"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <el-form label-position="left" label-width="120px">
      <el-row :gutter="30">
        <el-col :md="5" :sm="5" :xs="24">
          <div class="user-details-avt text-center">
            <el-image
              fit="cover"
              :src="BASE_URL + user?.images[0]?.url"
              :preview-src-list="imagePreview"
            >
            </el-image>
          </div>
          <div class="text-center pt-10">
            {{ user?.fullname }}
          </div>
        </el-col>

        <el-col :md="19" :sm="19" :xs="24">
          <el-row :gutter="30">
            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Họ tên:">
                <el-input v-model="user.fullname" disabled></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24" v-if="user.role.name == 'admin'">
              <el-form-item label="Tên đăng nhập:">
                <el-input v-model="user.username" disabled></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Số điện thoại:">
                <el-input v-model="user.phone" disabled></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="CMND / CCCD:">
                <el-input v-model="user.identity_card" disabled></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Tỉnh / TP:">
                <el-input
                  v-model="user.address_full.province.name"
                  disabled
                ></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Huyện / Phường:">
                <el-input
                  v-model="user.address_full.district.name"
                  disabled
                ></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Số bảo hiểm:">
                <el-input v-model="user.social_insurance" disabled></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Xã / Thị trấn:">
                <el-input
                  v-model="user.address_full.village.name"
                  disabled
                ></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Ngày sinh:">
                <el-date-picker
                  disabled
                  style="width: 100%"
                  value-format="YYYY-MM-DD"
                  type="date"
                  placeholder="Pick a date"
                  v-model="user.birthday"
                ></el-date-picker>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Vai trò:">
                <el-input v-model="user.role.name" disabled></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Địa chỉ:">
                <el-input v-model="user.address" disabled></el-input>
              </el-form-item>
            </el-col>

            <el-col :md="12" :sm="12" :xs="24">
              <el-form-item label="Giới tính:">
                <el-radio-group v-model="user.gender">
                  <el-radio :label="1" disabled>Nam</el-radio>
                  <el-radio :label="0" disabled>Nữ</el-radio>
                </el-radio-group>
              </el-form-item>
            </el-col>
          </el-row>
        </el-col>
      </el-row>
    </el-form>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="cancelForm">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<style scoped>
.user-details-avt .el-image {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  border: 1px solid #ddd;
}
</style>
