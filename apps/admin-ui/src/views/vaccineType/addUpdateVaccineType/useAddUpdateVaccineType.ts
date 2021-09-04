import API from "@/services"
import { ref } from "vue"
import { ElMessage } from "element-plus"
import { number } from "yup"

type vaccineType = {
  name: string
  country: string
}

function useAddUpdateVaccineType() {
  const isLoadingAddUpdate = ref(false)

  const updateVaccineType = async (
    params: vaccineType,
    id: number,
    cb: () => any
  ) => {
    try {
      isLoadingAddUpdate.value = true
      const response = await API.patch("vaccine_type/" + id, params)
      if (response.data.success) {
        console.log(response.data.data)
        ElMessage.success({
          message: "Cập nhật loại vắc-xin thành công!",
          type: "success"
        })
        cb()

        console.log(response)
      } else {
        ElMessage.error({
          message: "Xin lỗi! Vắc-xin chưa được cập nhật! Vui lòng thử lại",
          type: "error"
        })
      }
    } catch (e) {
      isLoadingAddUpdate.value = false
      console.log(e)
      ElMessage.error({
        message: "Xin lỗi! Vắc-xin chưa được cập nhật! Vui lòng thử lại",
        type: "error"
      })
    } finally {
      isLoadingAddUpdate.value = false
    }
  }

  const createVaccineType = async (params: vaccineType, cb: () => any) => {
    try {
      isLoadingAddUpdate.value = true
      const response = await API.post("vaccine_type", params)
      if (response.data.success) {
        console.log(response.data.data)
        ElMessage.success({
          message: "Thêm loại vắc-xin thành công!",
          type: "success"
        })
        cb()
      } else {
        ElMessage.error({
          message: "Xin lỗi! Vắc-xin chưa được thêm! Vui lòng thử lại",
          type: "error"
        })
      }
    } catch (e) {
      isLoadingAddUpdate.value = false
      console.log(e)
      ElMessage.error({
        message: "Xin lỗi! Vắc-xin chưa được thêm! Vui lòng thử lại",
        type: "error"
      })
    } finally {
      isLoadingAddUpdate.value = false
    }
  }
  return {
    isLoadingAddUpdate,
    updateVaccineType,
    createVaccineType
  }
}

export default useAddUpdateVaccineType
