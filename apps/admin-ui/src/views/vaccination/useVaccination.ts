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

type resType = {
  user: vaccineType
  data: {
    create_by: number
    created_at: string
    id: number
    updated_at: string
    user_id: number
    vaccine_type_id: number
  }
}

function useVaccination() {
  const vaccinationList = ref()
  const isLoadingVaccination = ref(false)
  const totalPage = ref(1)
  // const { getVaccineTypeSearchID } = useVaccineType();
  // const res: Array<resType> = [];

  const getVaccinationList = async (page: number) => {
    try {
      isLoadingVaccination.value = true
      const response = await API.get("vaccination?page=" + page)
      if (response.data.success) {
        totalPage.value = response.data.data.meta.last_page
        vaccinationList.value = response.data.data.vaccinations

        // for(const i in response.data.data.vaccinations) {
        //   test.value = await getVaccineTypeSearchID(response.data.data.vaccinations[i].id);
        //   const item = {
        //     user: test.value[0],
        //     data: response.data.data.vaccinations[i]
        //   }
        //   res.push(item);
        // }
      }
    } catch (e) {
      console.log(e)
      isLoadingVaccination.value = false
    } finally {
      isLoadingVaccination.value = false
    }
  }

  return {
    isLoadingVaccination,
    getVaccinationList,
    vaccinationList,
    totalPage
  }
}

export default useVaccination
