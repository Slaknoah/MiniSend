<template>
  <b-sidebar
    sidebar-class="sidebar-lg"
    :visible="isMailSidebarActive"
    bg-variant="white"
    shadow
    backdrop
    no-header
    right
    @change="(val) => $emit('update:is-mail-sidebar-active', val)"
    @hidden="resetMailLocal"
  >
    <template #default="{ hide }">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
        <h5 class="mb-0">
          Send test email
        </h5>

        <feather-icon
          class="ml-1 cursor-pointer"
          icon="XIcon"
          size="16"
          @click="hide"
        />
      </div>

      <validation-observer
        #default="{ handleSubmit }"
        ref="formDOM"
      >
        <!-- Form -->
        <b-form
          class="p-2"
          @submit.prevent="handleSubmit(onSubmit)"
        >
          <validation-provider
            vid="from"
            name="From"
            rules="required"
            #default="{ errors }"
          >
            <b-form-group
              label="From"
              :state="errors.length > 0 ? false:null"
            >
              <b-form-input
                v-model="mailLocal.from"
                :state="errors.length > 0 ? false:null"
                placeholder="john@example.com"
              />
              <b-form-invalid-feedback >{{ errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <validation-provider
            vid="to"
            name="To"
            rules="required"
            #default="{ errors }"
          >
            <b-form-group
              label="To"
              :state="errors.length > 0 ? false:null"
            >
              <b-form-input
                v-model="mailLocal.to"
                :state="errors.length > 0 ? false:null"
                placeholder="john@example.com"
              />
              <b-form-invalid-feedback >{{ errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <validation-provider
            vid="subject"
            name="Subject"
            rules="required"
            #default="{ errors }"
          >
            <b-form-group
              label="Subject"
              :state="errors.length > 0 ? false:null"
            >
              <b-form-input
                v-model="mailLocal.subject"
                :state="errors.length > 0 ? false:null"
                placeholder=""
              />
              <b-form-invalid-feedback >{{ errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <validation-provider
            vid="description"
            name="Content"
            class="col px-0"
            rules=""
            v-slot="{ errors }">
            <b-form-group
              label="Content"
              :state="errors.length > 0 ? false:null"
            >
              <quill-editor
                v-model="mailLocal.html"
                :options="editorOption" />
              <b-form-invalid-feedback>{{ errors[0] }}</b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
        </b-form>
      </validation-observer>
    </template>
  </b-sidebar>
</template>

<script>
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import { ref, toRefs, watch, useContext, onMounted } from '@nuxtjs/composition-api'
import { quillEditor } from 'vue-quill-editor';

export default {
  components: {
    // Form Validation
    ValidationProvider,
    ValidationObserver,
    quillEditor
  },
  model: {
    prop: 'isMailSidebarActive',
    event: 'update:is-mail-sidebar-active',
  },
  props: {
    isMailSidebarActive: {
      type: Boolean,
      required: true,
    }
  },
  setup( props ) {
    const blankMail = {
      from: $nuxt.$auth.user.email,
      to: '',
      subject: '',
      content: '',
      attachments: []
    }

    const mailLocal  = ref( JSON.parse( JSON.stringify( blankMail ) ) );

    const resetMailLocal = () => {
      mailLocal.value = JSON.parse( JSON.stringify( blankMail ) );
    }

    const onSubmit = () => {
      // Close sidebar
      emit('update:is-mail-sidebar-active', false)
    }

    return {
      resetMailLocal,
      onSubmit,
      mailLocal
    }
  }
}
</script>
<style lang="scss">
  @import '@core/scss/vue/libs/quill.scss';
</style>
