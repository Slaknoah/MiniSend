import { ref, computed } from '@nuxtjs/composition-api'

export default function useResourcesList( config ) {
  const currentPage = ref( 1 );
  const perPageOptions = [ 10, 25, 50, 100 ];
  const searchQuery = ref('');
  const isLoading = ref(false);
  const orderBy = ref('');
  const isOrderDirDesc = ref(false)
  const itemsPerPage = ref( config.resourcesPerPage );
  const resourcesMeta = ref({})

  const dataMeta = computed(() => {
    return {
      from: currentPage.value * itemsPerPage.value - (itemsPerPage.value - 1),
      to: ( ( currentPage.value * itemsPerPage.value ) <= resourcesMeta.value.total ) ? currentPage.value * itemsPerPage.value : resourcesMeta.value.total
    }
  })

  const addGlobalsToSearchParams = ( searchParams, page = 1 ) => {
    searchParams.page = page;
    searchParams.take = itemsPerPage.value;

    if ( orderBy.value != '' ) {
      searchParams.order_by = encodeURIComponent( orderBy.value );

      if ( isOrderDirDesc.value ) {
        searchParams.order_direction = encodeURIComponent( 'desc' );
      } else {
        searchParams.order_direction = encodeURIComponent( 'asc' );
      }
    }

    if ( searchQuery.value != '' ) {
      searchParams.search = searchQuery.value;
    }

    return searchParams;
  };

  return {
    currentPage,
    perPageOptions,
    searchQuery,
    orderBy,
    isOrderDirDesc,
    itemsPerPage,
    resourcesMeta,
    dataMeta,
    isLoading,
    addGlobalsToSearchParams
  }
}