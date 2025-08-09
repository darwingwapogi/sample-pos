/* 
    Author: Jezelle Carino
    Date: March 29, 2025
    Time: 4:30 PM
    Description: CRUD for Unit
*/
import axios from "axios";

class UnitService {
    async getList(params) {
        return axios.get('unit/list', { params });
    }
    
    async save(data) {
        return axios.post('unit/save', data);
    }

    async update(id, params) {
        return axios.put(`unit/update/${id}`, params);
    }

    async show(id) {
        return axios.get(`unit/show/${id}`);
    }

    async delete(id) {
        return axios.delete(`unit/delete/${id}`);
    }
}

export default new UnitService();