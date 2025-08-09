/* 
    Author: Darwin Casanova
    Date: April 19, 2025
    Time: 6:00 PM
    Description: DB Config Service
                 This service handles data for dropdown lists.
*/
import axios from "axios";

class DbConfigService {
    getItemTypeList() {
        return axios.get('db-config/item-type-list');
    }

    getUnitList() {
        return axios.get('db-config/unit-list');
    }

    getCategoryList() {
        return axios.get('db-config/category-list');
    }
    
    getSupplierList() {
        return axios.get('db-config/supplier-list');
    }

    getItemStatusList() {
        return axios.get('db-config/item-status-list');
    }
}

export default new DbConfigService();