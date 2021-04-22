<template>
  <div class="pb-5">
    <h1 class="mb-2">Recipient activity</h1>
    <b-card
      v-if="recipient.id"
    >
      <div
        class="d-flex justify-content-start align-items-center"
      >
        <!-- avatar -->
        <b-avatar
          :src="recipient.avatar"
          size="100"
          class="mr-3"
        />
        <!--/ avatar -->
        <div class="profile-user-info">
          <h1 class="mb-0">
            {{ recipient.name }}
          </h1>
          <div class="text-muted">{{ recipient.email }}</div>
        </div>
      </div>
    </b-card>

    <email-list :recipient="recipient"></email-list>
  </div>
</template>

<script>
import { ref, useContext, useFetch, watch } from '@nuxtjs/composition-api';

export default {
  middleware: "auth",
  layout: "Main",
  setup() {
    const { route, $api } = useContext();
    const recipient = ref({});

    const fetchRecipient = async () => {
      const { data } = await $api.recipients.show(route.value.params.recipient);
      recipient.value = data;
    }

    useFetch(function() {
      fetchRecipient();
    }.bind(fetchRecipient))

    watch(route, () => {
      fetchRecipient();
    })
    return {
      recipient
    }
  }
}
</script>