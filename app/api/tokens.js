export default $axios => ({
  async index() {
    try {
      const response = await $axios.get( '/api/v1/tokens');
      return response.data;
    } catch(err) {
      throw err;
    }
  },
  async create( form ) {
    try {
      const response = await $axios.post( '/api/v1/tokens', form );
      return response.data;
    } catch(err) {
      throw err;
    }
  },
});