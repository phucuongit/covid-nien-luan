import API from "@/services"
import { ElMessage } from "element-plus"
import { ref } from "vue"

function useDeleteResultTest() {
  const isLoadingDelete = ref(false)
  const deleteResultTest = async (id: number) => {
    try {
      isLoadingDelete.value = true
      const response = await API.delete("result_test/" + id)
      if (response.data.success) {
        ElMessage.success({
          message: "Xóa kết quả xét nghiệm thành công",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Kết quả xét nghiệm chưa được xóa",
          type: "error"
        })
      }
    } catch (e) {
      console.log(e)
      isLoadingDelete.value = false
      ElMessage.error({
        message: "Kết quả xét nghiệm chưa được xóa",
        type: "error"
      })
    } finally {
      isLoadingDelete.value = false
    }
  }

  return {
    deleteResultTest,
    isLoadingDelete
  }
}

export default useDeleteResultTest
