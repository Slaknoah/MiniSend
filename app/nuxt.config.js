const path = require("path");

export default {
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: false,

  server: {
    port: 4000,
  },

  // Target: https://go.nuxtjs.dev/config-target
  target: 'static',

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'Minisend - by Slaknoah',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap' }
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '@core/scss/core.scss',
    '@/assets/scss/style.scss',
    '@core/assets/fonts/feather/iconfont.css'
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    { src: '@/plugins/minisendAPI.js' },

    { src: '@/plugins/global-components.js' },

    { src: '@/plugins/vue-toastification.js' },

    { src: '@/plugins/vue-select.js' },

    { src: '@/plugins/filters.js' },
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    '@nuxtjs/composition-api/module'
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/bootstrap
    'bootstrap-vue/nuxt',
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/auth-next'
  ],

  // Nuxt auth module
  auth: {
    redirect: {
      login: '/login',
      home: '/',
    },
    strategies: {
      'laravelSanctum': {
        provider: 'laravel/sanctum',
        url: process.env.API_BASE_URL,
        endpoints: {
          login: {
            url: '/login'
          },
          user: {
            url: '/api/v1/user'
          },
          logout: {
            url: '/logout'
          }
        }
      }
    }
  },

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    baseURL: process.env.API_BASE_URL,
    credentials: true
  },

  publicRuntimeConfig: {
    baseURL: process.env.BASE_URL,
    apiBaseURL: process.env.API_BASE_URL,
    resourcesPerPage: 10
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    extend( config ) {
      config.resolve.alias['@themeConfig'] = path.resolve(__dirname, 'themeConfig.js');
      config.resolve.alias['@core'] = path.resolve(__dirname, '@core');
      config.resolve.alias['@validations'] = path.resolve(__dirname, 'components/utils/validations/validations.js');
    }
  }
}
