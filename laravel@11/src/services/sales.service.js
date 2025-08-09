/* 
    Author: Darwin Casanova
    Date: March 27, 2025
    Time: 7:56 PM
    Description: Sales related data.
*/
import axios from 'axios';

class SalesService {
  async getList(params) {
    return axios.get('sales/list', { params });
  }

  async getStatusList() {
    return axios.get('enum/sale-status-list');
  }
}

export default new SalesService();