import API from "@/services"
import { ref } from "vue"

function useResultTest() {
  const isLoadingResultTestList = ref(false)
  const isLoadingSearch = ref(false)
  const resultTestList = ref()
  const statusSearchResultTest = ref(false)
  const getResultTestList = async (page: number) => {
    try {
      statusSearchResultTest.value = false
      isLoadingResultTestList.value = true
      const response = await API.get("result_test?page=" + page)
      if (response.data.success) {
        resultTestList.value = response.data.data
      }
    } catch (e) {
      console.log(e)
      isLoadingResultTestList.value = false
    } finally {
      isLoadingResultTestList.value = false
    }
  }

  const searchResultTest = async (searchText: string, page: number) => {
    try {
      statusSearchResultTest.value = true
      isLoadingSearch.value = true
      isLoadingResultTestList.value = true
      const response = await API.get(
        "result_test?search=" + searchText + "&page=" + page
      )
      if (response.data.success) {
        resultTestList.value = response.data.data
      }
    } catch (e) {
      console.log(e)
      isLoadingSearch.value = false
      isLoadingResultTestList.value = false
    } finally {
      isLoadingSearch.value = false
      isLoadingResultTestList.value = false
    }
  }

  return {
    isLoadingResultTestList,
    resultTestList,
    getResultTestList,
    searchResultTest,
    isLoadingSearch,
    statusSearchResultTest
  }
}

export default useResultTest
