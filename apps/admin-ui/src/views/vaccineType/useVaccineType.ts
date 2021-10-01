import API from "@/services"
import { ref } from "vue"

type vaccineType = {
  id: number
  name: string
  country: string
  created_at: string
  updated_at: string
}

function useVaccineType() {
  const dataVaccineType = ref<vaccineType[]>([])
  const isLoading = ref(false)
  const isLoadingSearchVaccineType = ref(false)

  const getListVaccineType = async () => {
    try {
      isLoading.value = true
      const response = await API.get("vaccine_type")
      if (response.data.success) {
        dataVaccineType.value = response.data.data
      }
    } catch (e) {
      isLoading.value = false
      console.log(e)
    } finally {
      isLoading.value = false
    }
  }

  const getVaccineTypeSearch = async (name: string) => {
    try {
      isLoadingSearchVaccineType.value = true
      const response = await API.get("vaccine_type?name=" + name)
      if (response.data.success) {
        dataVaccineType.value = response.data.data
      }
    } catch (e) {
      isLoadingSearchVaccineType.value = false
      console.log(e)
    } finally {
      isLoadingSearchVaccineType.value = false
    }
  }

  return {
    getListVaccineType,
    dataVaccineType,
    isLoading,
    getVaccineTypeSearch,
    isLoadingSearchVaccineType
  }
}
export default useVaccineType
