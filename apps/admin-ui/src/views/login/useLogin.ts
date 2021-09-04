import { ref } from "vue"
import { ElMessage } from "element-plus"
import router from "@/router"
import API from "@/services"

type Prop = {
  username: string | undefined
  password: string | undefined
}

function useLogin() {
  const isLoading = ref(false)
  const login = async (params: Prop) => {
    try {
      isLoading.value = true

      const response = await API.post("auth/login", params)

      if (response.data.success) {
        localStorage.setItem("token", response.data.data.token)
        router.push({ name: "Admin" })
      } else {
        ElMessage.error({
          message: "Tên đăng nhập hoặc mật khẩu sai",
          type: "error"
        })
      }
    } catch (e) {
      ElMessage.error({
        message: "Tên đăng nhập hoặc mật khẩu sai",
        type: "error"
      })
    } finally {
      isLoading.value = false
    }
  }
  return {
    isLoading,
    login
  }
}

export default useLogin
