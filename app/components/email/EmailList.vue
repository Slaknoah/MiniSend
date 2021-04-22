<template>
  <div>
    <b-modal
      centered
      v-model="isMailModalOpen"
      hide-footer
      v-if="currentMail.id"
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
        <b-media
          vertical-align="center"
          class="mb-50"
          v-for="(recipient, index) of currentMail.recipients"
          :key="index"
        >
          <template #aside>
            <b-avatar
              size="32"
              :src="recipient.avatar"
              :to="`/recipients/${recipient.id}`"
            />
          </template>
          <b-link
            :to="`/recipients/${recipient.id}`"
            class="font-weight-bold d-block text-nowrap"
          >
            <span v-if="recipient.name">{{ recipient.name }}</span>
            <span v-else>{{ recipient.email }}</span>
          </b-link>
          <small class="text-muted" v-if="recipient.name">{{ recipient.email }}</small>
        </b-media>
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

    <b-row>
      <b-col sm="4" cols="12">
        <b-card>
          <b-media
            vertical-align="center"
          >
            <template #aside>
              <b-avatar
                rounded
                size="64"
                variant="primary"
              >
                <span class="d-flex align-items-center">
                  <feather-icon
                    icon="SendIcon"
                    size="32"
                  />
                </span>
              </b-avatar>
            </template>
            <div>
              <h3 class="h1">{{ resourcesMeta.total }}</h3>
              <p class="text-muted mb-0">Posted</p>
            </div>
          </b-media>
        </b-card>
      </b-col>

      <b-col sm="4" cols="12">
        <b-card>
          <b-media
            vertical-align="center"
          >
            <template #aside>
              <b-avatar
                rounded
                size="64"
                variant="success"
              >
                <span class="d-flex align-items-center">
                  <feather-icon
                    icon="InboxIcon"
                    size="32"
                  />
                </span>
              </b-avatar>
            </template>
            <div>
              <h3 class="h1">{{ resourcesMeta.total_sent }}</h3>
              <p class="text-muted mb-0">Sent</p>
            </div>
          </b-media>
        </b-card>
      </b-col>

      <b-col sm="4" cols="12">
        <b-card>
          <b-media
            vertical-align="center"
          >
            <template #aside>
              <b-avatar
                rounded
                size="64"
                variant="danger"
              >
                <span class="d-flex align-items-center">
                  <feather-icon
                    icon="ShieldIcon"
                    size="32"
                  />
                </span>
              </b-avatar>
            </template>
            <div>
              <h3 class="h1">{{ resourcesMeta.total_failed }}</h3>
              <p class="text-muted mb-0">Failed</p>
            </div>
          </b-media>
        </b-card>
      </b-col>
    </b-row>

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
          <b-media
            vertical-align="center"
            class="mb-50"
            v-for="(recipient, index) of data.item.recipients"
            :key="index"
          >
            <template #aside>
              <b-avatar
                size="32"
                :src="recipient.avatar"
                :to="`/recipients/${recipient.id}`"
              />
            </template>
            <b-link
              :to="`/recipients/${recipient.id}`"
              class="font-weight-bold d-block text-nowrap"
            >
              <span v-if="recipient.name">{{ recipient.name }}</span>
              <span v-else>{{ recipient.email }}</span>
            </b-link>
            <small class="text-muted" v-if="recipient.name">{{ recipient.email }}</small>
          </b-media>
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
  </div>
</template>

<script>
import useResourcesList from '@/components/useResourcesList'
import { ref, useContext, useFetch, watch, computed } from '@nuxtjs/composition-api'

export default {
  props: {
    recipient: {
      type: Object,
      default: () => {}
    }
  },
  setup(props) {
    const { $config, $api } = useContext();
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

      console.log( props.recipient );
      if( props.recipient && props.recipient.id ) {
        searchParams.recipient = props.recipient.id;
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
    fetchEmails();

    watch( () => props.recipient, () => { fetchEmails() })
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