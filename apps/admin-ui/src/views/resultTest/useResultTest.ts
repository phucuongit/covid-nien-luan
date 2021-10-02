import API from "@/services"
import { ref } from "vue"

function useResultTest() {
  const isLoadingResultTestList = ref(false)
  const isLoadingSearch = ref(false)
  const resultTestList = ref()
  const totalPage = ref(1)
  const getResultTestList = async (page: number) => {
    try {
      isLoadingResultTestList.value = true
      const response = await API.get("result_test?page=" + page)
      if (response.data.success) {
        resultTestList.value = response.data.data.result_tests
        console.log("check")
        console.log(resultTestList.value)
        totalPage.value = response.data.data.meta.last_page
      }
    } catch (e) {
      console.log(e)
      isLoadingResultTestList.value = false
    } finally {
      isLoadingResultTestList.value = false
    }
  }

  const searchResultTest = async (searchText: string) => {
    try {
      isLoadingSearch.value = true
      isLoadingResultTestList.value = true
      const response = await API.get("result_test?search=" + searchText)
      if (response.data.success) {
        resultTestList.value = response.data.data.result_tests
        totalPage.value = response.data.data.meta.last_page
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

  const filterResultTest = async (searchText: string) => {
    try {
      isLoadingResultTestList.value = true
      const response = await API.get("result_test?search=" + searchText)
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
    getResultTestList,
    totalPage,
    searchResultTest,
    isLoadingSearch,
    filterResultTest
  }
}

export default useResultTest
