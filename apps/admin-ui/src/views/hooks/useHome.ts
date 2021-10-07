import API from "@/services"
import { ref } from "vue"
function useHome() {
  const dataSearch = ref()
  const isLoadingSearch = ref(false)
  const search = async (text: string) => {
    try {
      isLoadingSearch.value = true
      const response = await API.get("look_up/" + text)
      if (response.data.success) {
        dataSearch.value = response.data.data
      }
    } catch (e) {
      console.log(e)
      isLoadingSearch.value = false
    } finally {
      isLoadingSearch.value = false
    }
  }

  return {
    dataSearch,
    search,
    isLoadingSearch
  }
}

export default useHome
