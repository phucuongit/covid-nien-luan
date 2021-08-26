import { ref } from "vue"
import { ElMessage } from "element-plus"
import router from "@/router"
import instance from "@/services"
function useLogin() {
  const isLoading = ref(false)

  const login = async (params: any) => {
    try {
      isLoading.value = true

      const response = await instance.post("login", ...params)

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
