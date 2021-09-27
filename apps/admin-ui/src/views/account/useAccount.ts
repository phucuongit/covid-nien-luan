import API from "@/services"
import { ElMessage } from "element-plus"
import { ref } from "vue"
import { useStore } from "vuex"

export type accountType = {
  password: null
  identify_card: string
  social_insurance: string
  fullname: string
  birthday: null
  gender: number
  username: string
  address: string
  phone: string
  province_id: number
  district_id: number
  village_id: number
  avatar: string
}

function useAccount() {
  const store = useStore()
  const isLoadingUpdateAccount = ref(false)
  const getAccount = async () => {
    try {
      const response = await API.get("profile")
      if (response.data.success) {
        store.dispatch("setUser", response.data.data)
      }
    } catch (e) {
      console.log(e)
    }
  }

  const updateAccount = async (params: accountType) => {
    try {
      isLoadingUpdateAccount.value = true
      const response = await API.put("profile/update", params)
      if (response.data.success) {
        ElMessage.success({
          message: "Cập nhật thông tin thành thông",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Cập nhật thông tin không thành thông",
          type: "error"
        })
      }
    } catch (e) {
      console.log(e)
      isLoadingUpdateAccount.value = false
      ElMessage.error({
        message: "Cập nhật thông tin không thành thông",
        type: "error"
      })
    } finally {
      isLoadingUpdateAccount.value = false
    }
  }
  return {
    getAccount,
    updateAccount,
    isLoadingUpdateAccount
  }
}

export default useAccount
