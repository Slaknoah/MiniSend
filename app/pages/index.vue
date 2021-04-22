<template>
  <div class="pb-5">
    <h1 class="mb-2">Activity</h1>

    <b-modal
      id="modal-success"
      ok-only
      ok-variant="success"
      ok-title="Done"
      modal-class="modal-success"
      centered
      v-model="showTokenResponseModal"
      title="Please copy and save token"
    >
      <b-card-text>
        {{ tokenResponse }}
      </b-card-text>
    </b-modal>

    <b-modal
      id="token-modal"
      ref="my-modal"
      title="Create new API token"
      ok-title="Create token"
      centered
      v-model="showTokenModal"
      cancel-variant="outline-secondary"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <p>Create an access token so you can send emails, authenticate your account and track data. Please copy the token once it’s created. If you don’t, it will be lost forever.</p>
      <form
        ref="tokenFormDOM"
        @submit.stop.prevent="handleSubmitTokenForm"
      >
        <b-form-group
          :state="nameState"
          label="Enter your token name"
          label-for="name-input"
          invalid-feedback="Token name is required"
        >
          <b-form-input
            id="name-input"
            v-model="tokenForm.token_name"
            :state="nameState"
            placeholder="e.g. Staging API"
            required
          />
        </b-form-group>
      </form>
    </b-modal>

    <email-list></email-list>

    <b-card class="mt-2">
      <div class="text-center py-2">
        <div v-if="tokens.length" class="mb-2">
          <b-button
            size="md"
            class="mr-1"
            variant="outline-primary"
            v-for="token in tokens"
            :key="token.id">
            {{ token.name | title }}
          </b-button>
        </div>

        <div v-else>
          <b-badge>
            <feather-icon
              icon="CodeIcon"
              size="64"
            />
          </b-badge>
          <h3>No API tokens to show</h3>
          <p>You have not created any API tokens yet</p>
        </div>
        <!-- button -->
        <b-button
          variant="primary"
          v-b-modal.token-modal
        >
          Generate new token
        </b-button>
      </div>
    </b-card>
  </div>
</template>

<script>
import { ref, useContext } from '@nuxtjs/composition-api'

export default {
  layout: 'Main',
  middleware: 'auth',
  setup() {
    const { $api } = useContext();

    // Token form
    const tokens = ref([]);
    const showTokenModal = ref(false);
    const showTokenResponseModal = ref(false);
    const tokenResponse = ref(null);

    const tokenForm = ref({
      token_name: ''
    })
    const nameState = ref(null);

    const fetchTokens = async () => {
      const response = await $api.tokens.index();
      tokens.value = response.tokens;
    }
    fetchTokens();

    const handleSubmitTokenForm = async () => {
      if (!tokenForm.value.token_name) {
        nameState.value = false;
        return
      }

      const response = await $api.tokens.create(tokenForm.value);
      tokenResponse.value = response.token;

      showTokenModal.value = false;
      showTokenResponseModal.value = true;
      fetchTokens();
    };

    const handleOk = bvModalEvt => {
      bvModalEvt.preventDefault();

      handleSubmitTokenForm()
    };

    const resetModal = () => {
      nameState.value = null;
      tokenForm.value.token_name = ''
    }

    return {
      // Tokens,
      tokens,
      tokenForm,
      nameState,
      handleOk,
      handleSubmitTokenForm,
      resetModal,
      showTokenModal,
      showTokenResponseModal,
      tokenResponse,
    }
  }
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
.date-col { min-width: 5em;}
.label-selector { width: 200px; }
</style>