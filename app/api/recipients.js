export default $axios => ({
  async show(id) {
    try {
      const response = await $axios.get( `/api/v1/recipients/${id}` );
      return response.data;
    } catch(err) {
      throw err;
    }
  },
})