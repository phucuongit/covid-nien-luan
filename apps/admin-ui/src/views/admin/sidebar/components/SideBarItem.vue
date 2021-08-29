<script>
import { defineComponent, computed } from "vue"
import { Icon } from "@iconify/vue"
const SideBarItem = defineComponent({
  name: "SideBarItem",
  components: {
    Icon
  },
  props: {
    item: {
      type: Object,
      default: () => ({
        key: null,
        label: null,
        children: [],
        icon: ""
      })
    }
  },
  setup(props) {
    const checkChildrenEmpty = computed(() => {
      return props.item.children.length === 0
    })
    return { checkChildrenEmpty }
  }
})

export default SideBarItem
</script>

<template>
  <template v-if="checkChildrenEmpty">
    <el-menu-item class="text-white bg-11385e" :index="item.key">
      <router-link
        class="text-white aside-a"
        :to="{ name: item.link ? item.link : '' }"
      >
        <Icon :icon="item.icon" color="#369" />
        <span>{{ item.label }} </span>
      </router-link>
    </el-menu-item>
  </template>

  <template v-else>
    <el-submenu class="text-white" :index="item.key">
      <template #title>
        <Icon :icon="item.icon" color="#369" />
        <span class="text-white">
          <router-link
            class="text-white aside-a"
            :to="{ name: item.link ? item.link : '' }"
          >
            <span>{{ item.label }} </span>
          </router-link>
        </span>
      </template>

      <el-menu-item-group class="bg-11385e">
        <el-menu-item
          v-for="submenu in item.children"
          :key="submenu.key"
          :index="`${item.key}-${submenu.key}`"
          class="text-white"
        >
          <router-link
            class="text-white aside-a"
            :to="{ name: submenu.link ? submenu.link : '' }"
          >
            <Icon :icon="submenu.icon" color="#369" />
            <span>{{ submenu.label }} </span>
          </router-link>
        </el-menu-item>
      </el-menu-item-group>
    </el-submenu>
  </template>
</template>
