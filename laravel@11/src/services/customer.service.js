import axios from 'axios';

class CustomerService {
  async getList(params) {
    return axios.get('customer/list', { params });
  }

  save(params) {
    return axios.post('customer/save', params);
  }

  update(id, params) {
      return axios.put(`customer/update/${id}`, params);
  }

  show(id) {
      return axios.get(`customer/show/${id}`);
  }

  delete(id) {
      return axios.delete(`customer/delete/${id}`);
  }
}

export default new CustomerService();