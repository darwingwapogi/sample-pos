import axios from 'axios';

class SupplierService {
    async getList(params) {
        return axios.get('supplier/list', { params });
    }

    async save(data) {
        return axios.post('supplier/save', data);
    }

    async delete(id) {
        return axios.delete(`supplier/${id}`);
    }

    getSupplierStatusList() {
        return axios.get('enum/supplier-status-list');
    }
}

export default new SupplierService();