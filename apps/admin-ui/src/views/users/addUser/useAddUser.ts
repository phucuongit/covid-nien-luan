import { ref } from "vue"
import API from "@/services"
import { ElMessage } from "element-plus"

type userType = {
  id: number
  identify_card: string
  social_insurance: string
  fullname: string
  birthday: null
  gender: number
  username: string
  password: string
  address: string
  phone: string
  role_id: number
}

function useAddUser() {
  const isLoadingAddUser = ref(false)
  const user_new_id = ref(0)
  const errorCreate = ref()
  const createUser = async (params: userType) => {
    errorCreate.value = ""
    try {
      isLoadingAddUser.value = true
      const response = await API.post("user", params)
      console.log(response)
      if (response.data.success) {
        user_new_id.value = response.data.data.id
        console.log(response.data.data)
        ElMessage.success({
          message: "Thêm người dùng thành công!",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Thêm người dùng không thành công.",
          type: "error"
        })
      }
    } catch (error: any) {
      isLoadingAddUser.value = false

      errorCreate.value = error?.response?.data?.errors
      console.log(errorCreate.value)

      ElMessage.error({
        message: "Thêm người dùng không thành công",
        type: "error"
      })
    } finally {
      isLoadingAddUser.value = false
      setTimeout(function () {
        errorCreate.value = ""
      }, 5000)
    }
  }

  const updateUser = async (id: number, params: userType) => {
    errorCreate.value = ""
    try {
      isLoadingAddUser.value = true

      const response = await API.put("user/" + id, params)
      if (response.data.success) {
        console.log(response.data.data)
        ElMessage.success({
          message: "Cập nhật thành công",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Cập nhật không thành công",
          type: "error"
        })
      }
    } catch (error: any) {
      isLoadingAddUser.value = false
      errorCreate.value = error?.response?.data?.errors

      ElMessage.error({
        message: "Cập nhật người dùng không thành công",
        type: "error"
      })
    } finally {
      isLoadingAddUser.value = false
      setTimeout(function () {
        errorCreate.value = ""
      }, 5000)
    }
  }

  return {
    isLoadingAddUser,
    createUser,
    updateUser,
    user_new_id,
    errorCreate
  }
}

export default useAddUser
