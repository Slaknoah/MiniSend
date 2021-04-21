export default $axios => ({
  async index( params ) {
    try {
      const response = await $axios.get( '/api/v1/emails', {
        params: params
      });

      return response.data;
    } catch(err) {
      throw err;
    }
  },
});