import _ from "underscore";
import axios from "axios";

class IcePosService {
    createInvoice(data) {
        return axios.post('invoice/create', data)
    }
}
export default new IcePosService();