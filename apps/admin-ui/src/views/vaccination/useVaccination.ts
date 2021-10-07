import API from "@/services"
import { ref } from "vue"
import useVaccineType from "../vaccineType/useVaccineType"

type vaccineType = {
  id: number
  name: string
  country: string
  created_at: string
  updated_at: string
}

function useVaccination() {
  const vaccinationList = ref()
  const isLoadingVaccination = ref(false)
  const isLoadingSearch = ref(false)
  const statusSearchVaccination = ref(false)
  const getVaccinationList = async (page: number) => {
    try {
      statusSearchVaccination.value = false
      isLoadingVaccination.value = true
      const response = await API.get("vaccination?page=" + page)
      if (response.data.success) {
        vaccinationList.value = response.data.data
      }
    } catch (e) {
      console.log(e)
      isLoadingVaccination.value = false
    } finally {
      isLoadingVaccination.value = false
    }
  }

  const searchVaccineType = async (text: string, page: number) => {
    try {
      statusSearchVaccination.value = true
      isLoadingSearch.value = true
      isLoadingVaccination.value = true
      const response = await API.get(
        "vaccination?search=" + text + "&page=" + page
      )
      if (response.data.success) {
        vaccinationList.value = response.data.data
      }
    } catch (e) {
      console.log(e)
      isLoadingSearch.value = false
      isLoadingVaccination.value = false
    } finally {
      isLoadingSearch.value = false
      isLoadingVaccination.value = false
    }
  }

  return {
    isLoadingVaccination,
    getVaccinationList,
    vaccinationList,
    searchVaccineType,
    isLoadingSearch,
    statusSearchVaccination
  }
}

export default useVaccination
