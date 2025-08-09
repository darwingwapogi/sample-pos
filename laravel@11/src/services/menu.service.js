/* 
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 4:30 PM
    Description: Menu Service
    Note: Specify the Menu Items in MenuController and fetch it here.
 */
import axios from "axios";

class MenuService {
    getList() {
        return axios.get('menu/list')
    }
}
export default new MenuService();