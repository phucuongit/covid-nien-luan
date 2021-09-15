import { reactive } from "vue"

interface SideBarType {
  key: number
  link: string
  icon: string
  label: string
  children: SideBarType[]
}

export default function useConfigSideBar() {
  const sidebar = reactive<SideBarType[]>([
    {
      key: 1,
      link: "ResultTest",
      icon: "carbon:dashboard",
      label: "Thống kê",
      children: []
    },
    {
      key: 2,
      link: "ResultTest",
      icon: "carbon:id-management",
      label: "Kiểm tra covid",
      children: []
    },
    {
      key: 3,
      link: "Vaccination",
      icon: "lucide:history",
      label: "Thêm lịch sử tiêm",
      children: []
    },
    {
      key: 4,
      link: "Vaccine",
      icon: "ic:twotone-vaccines",
      label: "Vắc-xin",
      children: []
    },
    {
      key: 5,
      icon: "ant-design:setting-twotone",
      link: "",
      label: "Cài đặt",
      children: [
        {
          key: 1,
          link: "ResultTest",
          icon: "carbon:user-avatar-filled-alt",
          label: "Tài khoản",
          children: []
        },
        {
          key: 2,
          link: "Users",
          icon: "fa-solid:users",
          label: "Người dùng",
          children: []
        }
      ]
    }
  ])
  return { sidebar }
}
