import axios from "axios";

class PosService {
    processSale(params) {
        return axios.post('pos/sale', params);
    }

    recomputeFinalSellingPrice(params) {
        return axios.post('pos/recompute-final-selling-price', params)
    }
}
export default new PosService()