import API from "@/services"
import { ElMessage } from "element-plus"
import { ref } from "vue"

type resultTestType = {
  status: string
  user_id: number
  create_by: number
}

function useAddUpdateResultTest() {
  const dataSearchUser = ref([])
  const isLoadingSearch = ref(false)
  const isLoadingAddUpdateResultTest = ref(false)
  const dataAddUpdateResultTest = ref()
  const searchUser = async (str: string) => {
    try {
      isLoadingSearch.value = true
      const response = await API.get("user?search=" + str)
      if (response.data.success) {
        dataSearchUser.value = response.data.data.users
      }
    } catch (e) {
      console.log(e)
      isLoadingSearch.value = false
    } finally {
      isLoadingSearch.value = false
    }
  }

  const addResultTest = async (params: resultTestType) => {
    try {
      isLoadingAddUpdateResultTest.value = true
      const response = await API.post("result_test", params)
      if (response.data.success) {
        console.log(response.data)
        ElMessage.success({
          message: "Thêm kết quả xét nghiệm thành công",
          type: "success"
        })
      }
    } catch (e) {
      console.log(e)
      ElMessage.error({
        message: "Thêm kết quả xét nghiệm không thành công",
        type: "error"
      })
      isLoadingAddUpdateResultTest.value = false
    } finally {
      isLoadingAddUpdateResultTest.value = false
    }
  }

  const updateResultTest = async (id: number, params: resultTestType) => {
    try {
      isLoadingAddUpdateResultTest.value = true
      const response = await API.patch("result_test/" + id, params)
      if (response.data.success) {
        console.log(response.data)
        ElMessage.success({
          message: "Cập nhật kết quả xét nghiệm thành công",
          type: "success"
        })
      }
    } catch (e) {
      console.log(e)
      isLoadingAddUpdateResultTest.value = false
      ElMessage.error({
        message: "Cập nhật kết quả xét nghiệm không thành công",
        type: "error"
      })
    } finally {
      isLoadingAddUpdateResultTest.value = false
    }
  }

  return {
    isLoadingSearch,
    dataSearchUser,
    searchUser,
    isLoadingAddUpdateResultTest,
    dataAddUpdateResultTest,
    addResultTest,
    updateResultTest
  }
}

export default useAddUpdateResultTest
