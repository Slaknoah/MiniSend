<template>
  <div class="mx-2 mb-2">
    <b-row>

      <b-col
        cols="12"
        sm="6"
        class="d-flex align-items-center justify-content-center justify-content-sm-start"
      >
        <span v-if="dataMeta" class="text-muted">{{ dataMeta.from }} - {{ dataMeta.to }} из {{ resourcesMeta.total }}</span>
      </b-col>
      <!-- Pagination -->
      <b-col
        cols="12"
        sm="6"
        class="d-flex align-items-center justify-content-center justify-content-sm-end"
      >

        <b-pagination
          :value="currentPage"
          @input="(val) => $emit('update:currentPage', val)"
          :total-rows="resourcesMeta.total"
          :per-page="itemsPerPage"
          first-number
          last-number
          class="mb-0 mt-1 mt-sm-0"
          prev-class="prev-item"
          next-class="next-item"
        >
          <template #prev-text>
            <feather-icon
              icon="ChevronLeftIcon"
              size="18"
            />
          </template>
          <template #next-text>
            <feather-icon
              icon="ChevronRightIcon"
              size="18"
            />
          </template>
        </b-pagination>

      </b-col>

    </b-row>
  </div>
</template>

<script>
export default {
  props: {
    dataMeta: {
      type: Object,
      required: false
    },
    resourcesMeta: {
      type: Object,
      required: true
    },
    currentPage: {
      type: Number,
      required: true
    },
    itemsPerPage: {
      type: Number,
      required: true
    }
  }
}
</script>