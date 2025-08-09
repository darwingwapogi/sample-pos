/*
    Author: Darwin Casanova
    Date: March 8, 2025
    Time: 4:30 PM
    Description: This method will responsible for sending and getting requests.
 */

import axios from "axios";

const appUrl = import.meta.env.VITE_APP_URL;
const appEnv = import.meta.env.VITE_APP_ENV;

axios.defaults.baseURL = `${appUrl}/api`;

if (!appEnv.includes('prod')) {
  axios.defaults.baseURL = '/api';
}

/*TODO: use to handle token cache
let refresh = false;

const tokenInterceptor = axios.interceptors.response.use(resp => resp, async error => {
    if (error.response.status === 401 && !refresh) {
        refresh = true;
        const {status, data} = await axios.post('refresh', {}, {
            withCredentials: true
        });

        if (status === 200) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;

            return axios(error.config);
        }
    }
    refresh = false;

    return error;
})*/

function getCookie(name) {
    const cookieArr = document.cookie.split(';');
    for (let cookie of cookieArr) {
        const [key, value] = cookie.trim().split('=');
        if (key === name) return value;
    }
    return null;
}

const token = getCookie('jwt-user');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

