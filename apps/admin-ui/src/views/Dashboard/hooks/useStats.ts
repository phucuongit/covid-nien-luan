import API from "@/services"
import { ref, onMounted } from "vue"

type InjectedVaccinateStats = {
  injected_first_time: number
  injected_second_time: number
  injected_total_time: number
  injected_lastest_7days: {
    date: string
    quantity: number
  }[]
}

const useStats = () => {
  const data = ref<InjectedVaccinateStats>({
    injected_first_time: 0,
    injected_second_time: 0,
    injected_total_time: 0,
    injected_lastest_7days: []
  })
  const isLoading = ref(false)

  const getData = async () => {
    try {
      isLoading.value = true
      const response = await API.get("statistic")
      if (response.data.success) {
        data.value = response.data.data
      }
    } catch (e) {
      isLoading.value = false
      console.log(e)
    } finally {
      isLoading.value = false
    }
  }

  onMounted(() => {
    getData()
  })

  return {
    getData,
    data,
    isLoading
  }
}

export default useStats
