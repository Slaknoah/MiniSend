<template>
  <p class="clearfix mb-0">
    <span class="float-md-left d-none d-md-inline-block mt-25">
      COPYRIGHT  © {{ new Date().getFullYear() }}
      <b-link
        class="ml-25"
        href="https://slaknoah.com"
        target="_blank"
      >Slaknoah</b-link>
      <span class="d-none d-sm-inline-block">, All rights Reserved</span>
    </span>

    <b-link
      href="https://documenter.getpostman.com/view/5709349/TzJycbCU"
      class=" float-md-right d-flex d-md-flex align-items-center"
      target="_blank"
    >
      <feather-icon
        icon="FileTextIcon"
        size="21"
        class="stroke-current mr-25"
      />
      Documentation
    </b-link>
  </p>
</template>

<script>
import { BLink } from 'bootstrap-vue'

export default {
  components: {
    BLink,
  },
}
</script>
