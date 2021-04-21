<template>
  <div class="pb-5">
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

    <b-modal
      centered
      v-model="isMailModalOpen"
      hide-footer
      :title="currentMail.subject"
    >
      <div class="">
        <h5 class="text-capitalize mb-75"> Subject: </h5>
        <b-card-text>{{ currentMail.subject }}</b-card-text>
      </div>

      <div class="mt-2">
        <h5 class="text-capitalize mb-75"> From: </h5>

        <div v-if="currentMail.sender_name">
          {{ currentMail.sender_name }}
        </div>
        <div class="text-muted" v-if="currentMail.sender_name">
          <small>{{ currentMail.sender_email }}</small>
        </div>
        <div v-else>
          {{ currentMail.sender_email }}
        </div>
      </div>

      <div class="mt-2">
        <h5 class="text-capitalize mb-75"> To: </h5>

        <div
          v-for="(recipient, index) of currentMail.recipients"
          :key="index"
        >
          <b-link v-if="recipient.name" >
            {{ recipient.name }}
          </b-link>
          <div class="text-muted" v-if="recipient.name">
            <small>{{ recipient.email }}</small>
          </div>
          <b-link v-else>
            {{ recipient.email }}
          </b-link>
        </div>
      </div>

      <div class="mt-2">
        <h5 class="text-capitalize mb-75"> Status: </h5>
        <div>
          <b-badge class="py-75 px-1" :variant="resolveMailStatusVariant(currentMail.status)">
            {{ currentMail.status.toUpperCase() }}
          </b-badge>
        </div>
      </div>

      <div class="mt-2" v-if="currentMail.cc && currentMail.cc.length">
        <h5 class="text-capitalize mb-75"> CC: </h5>
        <div
          v-for="(cc, index) of currentMail.cc"
          :key="index"
        >
          <div v-if="cc.name" >
            {{ cc.name }}
          </div>
          <div class="text-muted" v-if="cc.name">
            <small>{{ cc.email }}</small>
          </div>
          <div v-else>
            {{ cc.email }}
          </div>
        </div>
      </div>

      <div class="mt-2" v-if="currentMail.bcc && currentMail.bcc.length">
        <h5 class="text-capitalize mb-75"> BCC: </h5>
        <div
          v-for="(bcc, index) of currentMail.bcc"
          :key="index"
        >
          <div v-if="bcc.name" >
            {{ bcc.name }}
          </div>
          <div class="text-muted" v-if="bcc.name">
            <small>{{ bcc.email }}</small>
          </div>
          <div v-else>
            {{ bcc.email }}
          </div>
        </div>
      </div>

      <div class="mt-2 mb-3">
        <h5 class="text-capitalize mb-75"> HTML/Text Content: </h5>
        <b-card-text>Content tracking is not allowed at the moment</b-card-text>
      </div>
    </b-modal>

    <b-card
      no-body
    >
      <list-head
        :items-per-page.sync="itemsPerPage"
        :per-page-options="perPageOptions"
        add-resource-link="/new"
        :search-query.sync="searchQuery"
      >
        <template #button>
          <label>Status:</label>
          <v-select
            v-model="statusFilter"
            :options="statusOptions"
            :clearable="false"
            class="label-selector d-inline-block mx-50">
            <template #option="{ label, value }">
              <b-badge :variant="resolveMailStatusVariant(value)"> {{ label.toUpperCase() }}</b-badge>
            </template>

            <template #selected-option="{ label, value }">
              <b-badge :variant="resolveMailStatusVariant(value)"> {{ label.toUpperCase() }}</b-badge>
            </template>
          </v-select>
        </template>

        <template #middle>
          <b-link @click="fetchEmails">
            <feather-icon
              icon="RotateCwIcon"
              size="24"
              class="mx-1"
            />
          </b-link>
        </template>
      </list-head>

      <b-table
        responsive
        primary-key="id"
        :items="emails"
        empty-text="You haven't sent any email"
        show-empty
        no-local-sorting
        :busy="isLoading"
        :sort-by.sync="orderBy"
        :sort-desc.sync="isOrderDirDesc"
        :fields="tableColumns"
      >
        <!-- Column: Status -->
        <template #cell(status)="data">
          <b-badge
            :variant="resolveMailStatusVariant(data.item.status)"
          >
            {{ data.item.status.toUpperCase() }}
          </b-badge>
        </template>

        <!-- Column: Date -->
        <template #cell(updated_at)="data">
          <div class="date-col">{{ data.item.updated_at | date }}</div>
        </template>

        <!-- Column: Sender -->
        <template #cell(sender)="data">
          <div v-if="data.item.sender_name">
            {{ data.item.sender_name }}
          </div>
          <div class="text-muted" v-if="data.item.sender_name">
            <small>{{ data.item.sender_email }}</small>
          </div>
          <div v-else>
            {{ data.item.sender_email }}
          </div>
        </template>

        <!-- Column: Recipient(s) -->
        <template #cell(recipients)="data">
          <div
            v-for="(recipient, index) of data.item.recipients"
            :key="index"
          >
            <b-link v-if="recipient.name" >
              {{ recipient.name }}
            </b-link>
            <div class="text-muted" v-if="recipient.name">
              <small>{{ recipient.email }}</small>
            </div>
            <b-link v-else>
              {{ recipient.email }}
            </b-link>
          </div>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <div class="text-nowrap">
            <b-link @click="viewMail(data.item)">
              <feather-icon
                icon="EyeIcon"
                size="16"
                class="mx-1"
              />
            </b-link>
          </div>
        </template>
      </b-table>

      <list-foot
        :items-per-page="itemsPerPage"
        :data-meta="dataMeta"
        :resourcesMeta="resourcesMeta"
        :current-page.sync="currentPage"
      />
    </b-card>

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
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import useResourcesList from '@/components/useResourcesList'
import { ref, useContext, useFetch, watch, computed } from '@nuxtjs/composition-api'

export default {
  layout: 'Main',
  middleware: 'auth',
  setup() {
    const { $config, $api, $auth, store } = useContext();
    const {
      currentPage,
      perPageOptions,
      itemsPerPage,
      dataMeta,
      orderBy,
      isOrderDirDesc,
      isLoading,
      searchQuery,
      resourcesMeta,
      addGlobalsToSearchParams
    } = useResourcesList( $config );

    const emails              = ref([]);
    const tableColumns        = [
      { key: 'status', label: 'Status', sortable: true },
      { key: 'sender', label: 'Sender', sortable: false },
      { key: 'recipients', label: 'Recipient(s)', sortable: false },
      { key: 'subject', label: 'Subject', sortable: true },
      { key: 'updated_at', label: 'Date', sortable: true },
      { key: 'actions', label: '' }
    ];
    const statusFilter = ref({
        label: 'All',
        value: 'all'
      });
    const statusOptions = ref([
      {
        label: 'All',
        value: 'all'
      },
      {
        label: 'Posted',
        value: 'posted'
      },
      {
        label: 'Sent',
        value: 'sent'
      },
      {
        label: 'Failed',
        value: 'failed'
      }
    ]);

    const buildSearchEmails = ( page ) => {
      let searchParams  = {};
      searchParams = addGlobalsToSearchParams( searchParams, page );

      if( statusFilter.value && statusFilter.value.value ) {
        searchParams.status = ( statusFilter.value.value !== 'all' ) ? statusFilter.value.value : null;
      }

      return searchParams;
    }

    const fetchEmails = async function( page = 1 ) {
      isLoading.value         = true;

      const searchParams      = buildSearchEmails( page );
      const { data, meta }    = await $api.emails.index( searchParams );
      emails.value          = data;
      resourcesMeta.value     = meta;

      isLoading.value         = false;
    }

    useFetch( async function() {
      fetchEmails();
    }.bind( fetchEmails ) );


    watch( currentPage,   () => { fetchEmails( currentPage.value ) })
    watch( orderBy,       () => { fetchEmails() })
    watch( isOrderDirDesc,() => { fetchEmails() })
    watch( searchQuery,   () => { fetchEmails() })
    watch( itemsPerPage,  () => { fetchEmails() })
    watch( statusFilter,  () => { fetchEmails() })

    // UI
    const resolveMailStatusVariant = status => {
      if (status === 'posted') return 'primary'
      if (status === 'sent') return 'success'
      if (status === 'failed') return 'danger'
      return 'dark'
    }

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
    };

    const handleOk = bvModalEvt => {
      bvModalEvt.preventDefault();

      handleSubmitTokenForm()
    };

    const resetModal = () => {
      nameState.value = null;
      tokenForm.value.token_name = ''
    }

    // Single mail
    const currentMail = ref({});
    const isMailModalOpen = ref(false);

    const viewMail = mail => {
      currentMail.value = mail;
      isMailModalOpen.value = true;
    }

    return {
      // View mail
      viewMail,
      isMailModalOpen,
      currentMail,

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

      // UI
      resolveMailStatusVariant,

      currentPage,
      perPageOptions,
      itemsPerPage,
      dataMeta,
      orderBy,
      isOrderDirDesc,
      isLoading,
      searchQuery,
      tableColumns,
      resourcesMeta,
      emails,
      fetchEmails,
      statusFilter,
      statusOptions,
    }
  }
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
.date-col { min-width: 5em;}
.label-selector { width: 200px; }
</style>