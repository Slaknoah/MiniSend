<template>
  <app>
    <layout-horizontal>
      <router-view />

      <template #navbar="{ toggleVerticalMenuActive }">
        <app-navbar-horizontal-layout-brand />
        <navbar :toggle-vertical-menu-active="toggleVerticalMenuActive" />
      </template>
    </layout-horizontal>
  </app>
</template>

<script>
import LayoutHorizontal from '@core/layouts/layout-horizontal/LayoutHorizontal.vue'
import AppNavbarHorizontalLayoutBrand from '@core/layouts/components/app-navbar/AppNavbarHorizontalLayoutBrand.vue'
import Navbar from '@/components/navbar/Navbar.vue'

export default {
  components: {
    LayoutHorizontal,
    AppNavbarHorizontalLayoutBrand,
    Navbar
  }
}
</script>