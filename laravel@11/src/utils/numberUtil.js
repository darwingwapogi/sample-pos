class NumberUtil {
    toPhpCurrency(value) {
        return `${new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value)}`;
    }
}

export default new NumberUtil()