import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { ref } from '@nuxtjs/composition-api'

export default function useValidationErrorHandler() {
  const formDOM = ref(null);

  const showValidationErrors = function( err ) {

    if ( err.response ) {
      if( err.response.status == 403 ) {
        $nuxt.error( {
          statusCode: err.response.status,
          message: 'Действие запрещено'
        } );
      } else {
        $nuxt.$toast({
          component: ToastificationContent,
          props: {
            title: 'Ошибка!',
            text: err.response.data.message,
            icon: 'icon-alert-circle',
            variant: 'warning',
          },
        })
      }

      if(err.response.status == 422) {
        if( formDOM.value ) {
          formDOM.value.setErrors(err.response.data.errors)
        }
      }
    }
  }

  return {
    formDOM,
    showValidationErrors
  }
}