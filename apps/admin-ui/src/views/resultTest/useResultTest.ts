import API from "@/services"
import { ref } from "vue"

function useResultTest() {
  const isLoadingResultTestList = ref(false)
  const resultTestList = ref()
  const totalPage = ref(1)
  const gettResultTestList = async (page: number) => {
    try {
      isLoadingResultTestList.value = true
      const response = await API.get("result_test?page=" + page)
      if (response.data.success) {
        resultTestList.value = response.data.data.result_tests
        totalPage.value = response.data.data.meta.last_page
      }
    } catch (e) {
      console.log(e)
      isLoadingResultTestList.value = false
    } finally {
      isLoadingResultTestList.value = false
    }
  }

  return {
    isLoadingResultTestList,
    resultTestList,
    gettResultTestList,
    totalPage
  }
}

export default useResultTest
