import API from "@/services"
import { ElMessage } from "element-plus"
import { ref } from "vue"

function useDeleteVaccination() {
  const isLoadingDeleteVaccination = ref(false)
  const deleteVaccination = async (id: number) => {
    try {
      isLoadingDeleteVaccination.value = true
      const response = await API.delete("vaccination/" + id)
      if (response.data.success) {
        ElMessage.success({
          message: "Xóa lịch sử tiêm chủng thành công",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Xóa lịch sử tiêm chủng không thành công",
          type: "error"
        })
      }
    } catch (e) {
      ElMessage.error({
        message: "Xóa lịch sử tiêm chủng không thành công",
        type: "error"
      })
      isLoadingDeleteVaccination.value = false
    } finally {
      isLoadingDeleteVaccination.value = false
    }
  }

  return {
    isLoadingDeleteVaccination,
    deleteVaccination
  }
}

export default useDeleteVaccination
