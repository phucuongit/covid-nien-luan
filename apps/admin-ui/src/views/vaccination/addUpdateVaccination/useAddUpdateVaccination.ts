import API from "@/services"
import { ElMessage } from "element-plus"
import { ref } from "vue"

type vaccinationType = {
  user_id: number
  create_by: number
  vaccine_type_id: number
}

function useAddUpdateVaccination() {
  const isLoadingAddVaccination = ref(false)
  const vaccinationNewId = ref(0)
  const dataSearchUser = ref()
  const addVaccination = async (params: vaccinationType) => {
    try {
      isLoadingAddVaccination.value = true
      const response = await API.post("vaccination", params)
      if (response.data.success) {
        vaccinationNewId.value = response.data.data.id
        ElMessage.success({
          message: "Thêm lịch sử tiêm thành công",
          type: "success"
        })
      } else {
        ElMessage.success({
          message: "Thêm lịch sử tiêm thành công",
          type: "success"
        })
      }
    } catch (e) {
      console.log(e)
      isLoadingAddVaccination.value = false
      ElMessage.error({
        message: "Lịch sử tiêm chưa được thêm",
        type: "error"
      })
    } finally {
      isLoadingAddVaccination.value = false
    }
  }

  const updateVaccination = async (id: number, params: vaccinationType) => {
    try {
      isLoadingAddVaccination.value = true
      const response = await API.patch("vaccination/" + id, params)
      if (response.data.success) {
        ElMessage.success({
          message: "Cập nhật lịch sử tiêm thành công",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Lịch sử tiêm chưa được cập nhật",
          type: "error"
        })
      }
    } catch (e) {
      console.log(e)
      isLoadingAddVaccination.value = false
      ElMessage.error({
        message: "Lịch sử tiêm chưa được cập nhật",
        type: "error"
      })
    } finally {
      isLoadingAddVaccination.value = false
    }
  }

  const searchUser = async (str: string) => {
    try {
      const response = await API.get("user?search=" + str)
      if (response.data.success) {
        dataSearchUser.value = response.data.data.users
      }
    } catch (e) {
      console.log(e)
    }
  }

  return {
    addVaccination,
    isLoadingAddVaccination,
    updateVaccination,
    vaccinationNewId,
    dataSearchUser,
    searchUser
  }
}

export default useAddUpdateVaccination
