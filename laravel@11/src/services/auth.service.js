/* 
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 4:30 PM
    Description: Authentication
*/
import axios from "axios";
class AuthenticationService {
    getCurrentUser() {
        const token = window.localStorage.getItem('jwt-token') || '';
        const header = { Authorization: `Bearer ${token}` };
        
        return axios.get('user', { headers: header })
    }

    postLogin(params) {
        return axios.post('login', params)
    }

    postLogout() {
        return axios.post('logout', {}, {withCredentials: true})
    }
}
export default new AuthenticationService();