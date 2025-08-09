import axios from 'axios';

class ItemService {
  async getList(params) {
    return axios.get('item/list', { params });
  }

  async save(data) {
    return axios.post('item/save', data);
  }

  async computeFinalSellingPrice(item) {
    return axios.post('item/compute-final-selling-price', item);
  }
}

export default new ItemService();
