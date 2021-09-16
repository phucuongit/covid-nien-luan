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
  const createUser = async (params: userType) => {
    try {
      isLoadingAddUser.value = true
      const response = await API.post("user", params)
      if (response.data.success) {
        console.log(response.data.data)
        ElMessage.success({
          message: "Thêm người dùng thành công!",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Xin lỗi! Vắc-xin chưa được thêm! Vui lòng thử lại",
          type: "error"
        })
      }
    } catch (e) {
      isLoadingAddUser.value = false
      console.log(e)
      ElMessage.error({
        message: "Thêm người dùng chưa được thực hiện. ",
        type: "error"
      })
    } finally {
      isLoadingAddUser.value = false
    }
  }

  return {
    isLoadingAddUser,
    createUser
  }
}

export default useAddUser
