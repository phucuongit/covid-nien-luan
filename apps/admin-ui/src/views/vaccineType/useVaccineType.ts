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
  const data = ref<vaccineType[]>([])
  const isLoading = ref(false)

  const getListVaccineType = async () => {
    try {
      isLoading.value = true
      const response = await API.get("vaccine_type")
      if (response.data.success) {
        data.value = response.data.data
        console.log(data.value)
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
      const response = await API.get("vaccine_type?name=" + name)
      if (response.data.success) {
        console.log(response.data.data)
        data.value = response.data.data
      }
    } catch (e) {
      console.log(e)
    }
  }

  const getVaccineTypeSearchID = async (id: number) => {
    try {
      const response = await API.get("vaccine_type?id=" + id)
      if (response.data.success) {
        return response.data.data
      }
    } catch (e) {
      console.log(e)
    }
  }

  return {
    getListVaccineType,
    data,
    isLoading,
    getVaccineTypeSearch,
    getVaccineTypeSearchID
  }
}

export default useVaccineType
