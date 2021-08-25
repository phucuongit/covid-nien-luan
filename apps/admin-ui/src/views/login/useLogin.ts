import { ref } from "vue"
import { ElMessage } from "element-plus"
import axios from "axios"
import router from "@/router"
function useLogin() {
  const isLoading = ref(false)

  const login = async (params: any) => {
    try {
      isLoading.value = true
      const response = await axios.post(
        "https://api-nienluan.sharenows.com/api/v1/auth/login",
        params
      )

      localStorage.setItem("token", response.data.data.token)

      router.push({ name: "Admin" })
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
