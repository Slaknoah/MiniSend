<template>
  <div
    id="app"
    class="h-100"
    :class="[skinClasses]"
  >
    <slot></slot>
  </div>
</template>

<script>

import { $themeColors, $themeBreakpoints, $themeConfig } from '@themeConfig'
import { provideToast } from 'vue-toastification/composition'
import { watch } from '@nuxtjs/composition-api'
import useAppConfig from '@core/app-config/useAppConfig'
import { useContext, onMounted } from '@nuxtjs/composition-api'

import { useWindowSize, useCssVar } from '@vueuse/core'

export default {
  beforeCreate() {
    if( process.client ) {
      // Set colors in theme
      const colors = ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'light', 'dark']

      // eslint-disable-next-line no-plusplus
      for (let i = 0, len = colors.length; i < len; i++) {
        $themeColors[colors[i]] = useCssVar(`--${colors[i]}`, document.documentElement).value.trim()
      }

      // Set Theme Breakpoints
      const breakpoints = ['xs', 'sm', 'md', 'lg', 'xl']

      // eslint-disable-next-line no-plusplus
      for (let i = 0, len = breakpoints.length; i < len; i++) {
        $themeBreakpoints[breakpoints[i]] = Number(useCssVar(`--breakpoint-${breakpoints[i]}`, document.documentElement).value.slice(0, -2))
      }
    }
  },
  setup() {
    const { store } = useContext();

    const { skin, skinClasses } = useAppConfig( store )

    onMounted( () => {
      if( localStorage.getItem('vuexy-skin') ) {
        skin.value = localStorage.getItem('vuexy-skin')
      }
    } );

    // If skin is dark when initialized => Add class to body
    if (skin.value === 'dark') document.body.classList.add('dark-layout')

    // Provide toast for Composition API usage
    // This for those apps/components which uses composition API
    // Demos will still use Options API for ease
    provideToast({
      hideProgressBar: true,
      closeOnClick: false,
      closeButton: false,
      icon: false,
      timeout: 3000,
      transition: 'Vue-Toastification__fade',
    })

    // Set Window Width in store
    store.commit('app/UPDATE_WINDOW_WIDTH', window.innerWidth)
    const { width: windowWidth } = useWindowSize()
    watch(windowWidth, val => {
      store.commit('app/UPDATE_WINDOW_WIDTH', val)
    })

    return {
      skinClasses,
    }
  }
}
</script>