/* 
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 4:30 PM
    Description: CRUD for Category
*/
import axios from "axios";

class CategoryService {
    async getList(params) {
        return axios.get('category/list', { params });
    }
    
    async save(data) {
        return axios.post('category/save', data);
    }

    async update(id, params) {
        return axios.put(`category/update/${id}`, params);
    }

    async show(id) {
        return axios.get(`category/show/${id}`);
    }

    async delete(id) {
        return axios.delete(`category/delete/${id}`);
    }
}

export default new CategoryService();