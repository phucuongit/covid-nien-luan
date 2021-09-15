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

function useAddUpdateUser() {
  const isLoadingAddUpdateUser = ref(false)
  const createUser = async (params: userType, cb: () => any) => {
    try {
      isLoadingAddUpdateUser.value = true
      console.log(params)
      const response = await API.post("user", params)
      if (response.data.success) {
        ElMessage.success({
          message: "Thêm loại người dùng thành công!",
          type: "success"
        })
        cb()
      }
    } catch (ex) {
      console.log(ex)
      isLoadingAddUpdateUser.value = false
      ElMessage.error({
        message: "Thêm người dùng chưa được thực hiện",
        type: "error"
      })
    } finally {
      isLoadingAddUpdateUser.value = false
    }
  }

  const updateUser = async (id: number, params: userType, cb: () => any) => {
    try {
      isLoadingAddUpdateUser.value = true
      const response = await API.put("user/" + id, params)
      if (response.data.success) {
        console.log(response.data.data)
        ElMessage.success({
          message: "Cập nhật thành công",
          type: "success"
        })
        cb()
      }
    } catch (e) {
      console.log(e)
      isLoadingAddUpdateUser.value = false
    } finally {
      isLoadingAddUpdateUser.value = false
    }
  }

  return {
    isLoadingAddUpdateUser,
    createUser,
    updateUser
  }
}

export default useAddUpdateUser
