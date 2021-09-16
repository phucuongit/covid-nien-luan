import API from "@/services"
import { ElMessage } from "element-plus"

export default function useDeleteVaccineType() {
  const deleteVaccineType = async (id: number, cb: () => any) => {
    try {
      const response = await API.delete("vaccine_type/" + id)
      if (response.data.success) {
        console.log("delete")
        ElMessage.success({
          message: "Vắc-xin đã được xóa!",
          type: "success"
        })
        cb()
      } else {
        ElMessage.error({
          message: "Vắc-xin chưa được xóa! Vui lòng thử lại",
          type: "error"
        })
      }
    } catch (e) {
      console.log(e)
      ElMessage.error({
        message: "Vắc-xin chưa được xóa! Vui lòng thử lại",
        type: "error"
      })
    }
  }

  return {
    deleteVaccineType
  }
}
