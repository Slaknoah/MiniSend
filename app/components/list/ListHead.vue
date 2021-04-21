<template>
  <div class="m-2">

    <b-row>

      <b-col
        cols="12"
        md="5"
        v-if="itemsPerPage"
        class="d-flex align-items-center  justify-content-start mb-1 mb-md-0">
        <label>Show:</label>
        <v-select
          :value="itemsPerPage"
          @input="(val) => $emit('update:itemsPerPage', val)"
          :options="perPageOptions"
          :clearable="false"
          class="per-page-selector d-inline-block mx-50">
        </v-select>

        <slot name="middle"></slot>
      </b-col>

      <b-col
        cols="12"
        md="7">
        <div class="d-flex align-items-center justify-content-end">
          <b-form-input
            :value="searchQuery"
            @input="(val) => $emit('update:searchQuery', val)"
            class="d-inline-block mr-1"
            placeholder="Search..."
          />
          <slot name="button">
              <b-button
                v-if="addResourceLink"
                variant="primary"
                :to="addResourceLink"
              >
                <span class="text-nowrap">Add</span>
              </b-button>
          </slot>
        </div>
      </b-col>

    </b-row>

  </div>
</template>

<script>
export default {
  props: {
    addResourceLink: {
      type: String,
      default: ''
    },
    searchQuery: {
      type: String,
      default: ''
    },
    itemsPerPage: {
      type: Number,
      required: false
    },
    perPageOptions: {
      type: Array,
      required: false
    }
  }
}
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>