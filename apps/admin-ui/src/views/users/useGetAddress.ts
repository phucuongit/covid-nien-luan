import { ref } from "vue"
import API from "@/services"

type provinceType = {
  id: number
  name: string
  gso_id: string
}

type districtType = {
  id: number
  name: string
  gso_id: string
  province_id: number
}

type villageType = {
  id: number
  name: string
  gso_id: string
  district_id: number
}

function useGetAddress() {
  const provinceList = ref<provinceType[]>([])
  const districtList = ref<districtType[]>([])
  const villageList = ref<villageType[]>([])
  const getProvinceList = async () => {
    try {
      const response = await API.get("address/province")
      if (response.data.success) {
        provinceList.value = response.data.data
      }
    } catch (e) {
      console.log(e)
    }
  }

  const getDistrictList = async (province_id: number) => {
    try {
      const response = await API.get(
        "address/district?province_id=" + province_id
      )
      if (response.data.success) {
        districtList.value = response.data.data
      }
    } catch (e) {
      console.log(e)
    }
  }

  const getVillageList = async (district_id: number) => {
    try {
      const response = await API.get(
        "address/village?district_id=" + district_id
      )
      if (response.data.success) {
        villageList.value = response.data.data
      }
    } catch (e) {
      console.log(e)
    }
  }

  return {
    provinceList,
    getProvinceList,
    districtList,
    getDistrictList,
    villageList,
    getVillageList
  }
}

export default useGetAddress
