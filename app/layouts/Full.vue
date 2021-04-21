<template>
  <app>
    <layout-full>
      <router-view />
    </layout-full>
  </app>
</template>

<script>
import LayoutFull from '@core/layouts/layout-full/LayoutFull.vue'

export default {
  components: {
    LayoutFull
  }
}
</script>