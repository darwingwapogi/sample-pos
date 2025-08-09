/* 
    Author: Darwin Casanova
    Date: May 3, 2025
    Time: 9:42 PM
    Description: CRUD for Item Type
*/
import axios from "axios";

class ItemTypeService {
    async getList(params) {
        return axios.get('item-type/list', { params });
    }
    
    async save(data) {
        return axios.post('item-type/save', data);
    }

    async update(id, params) {
        return axios.put(`item-type/update/${id}`, params);
    }

    async show(id) {
        return axios.get(`item-type/show/${id}`);
    }

    async delete(id) {
        return axios.delete(`item-type/delete/${id}`);
    }
}

export default new ItemTypeService();