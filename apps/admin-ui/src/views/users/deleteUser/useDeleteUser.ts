import { ref } from "vue"
import API from "@/services"
import { ElMessage } from "element-plus"

function useDeleteUser() {
  const isLoadingDeleteUser = ref(false)
  const deleteUser = async (id: number) => {
    try {
      isLoadingDeleteUser.value = true
      const response = await API.delete("user/" + id)
      if (response.data.success) {
        ElMessage.success({
          message: "Xóa người dùng thành công!",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Người dùng chưa được xóa!",
          type: "error"
        })
      }
    } catch (e) {
      isLoadingDeleteUser.value = false
      console.log(e)
      ElMessage.error({
        message: "Người dùng chưa được xóa!",
        type: "error"
      })
    } finally {
      isLoadingDeleteUser.value = false
    }
  }

  return {
    isLoadingDeleteUser,
    deleteUser
  }
}

export default useDeleteUser
