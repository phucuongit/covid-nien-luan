import API from "@/services"
import { ref } from "vue"
function useHome() {
  const dataSearch = ref()
  const isLoadingSearch = ref(false)
  const errorSearch = ref()
  const search = async (text: string) => {
    try {
      errorSearch.value = ""
      isLoadingSearch.value = true
      const response = await API.get("look_up/" + text)
      if (response.data.success) {
        dataSearch.value = response.data.data
      }
    } catch (e: any) {
      dataSearch.value = ""
      isLoadingSearch.value = false
      errorSearch.value = "Không tìm thấy kết quả"
    } finally {
      isLoadingSearch.value = false
    }
  }

  return {
    dataSearch,
    search,
    isLoadingSearch,
    errorSearch
  }
}

export default useHome
