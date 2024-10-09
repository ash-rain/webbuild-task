const { createApp, ref } = Vue

createApp({
    data() {
        return {
            loading: true,
            drinks: [],
            balance: 0,
        }
    },
    mounted() {
        this.apiRequest('/api/viewDrinks', response => {
            this.drinks = response.data;
        });
        this.apiRequest('/api/getBalance', response => {
            this.balance = response.data;
        });
    },
    methods: {
        putCoin(amount) {
            this.apiRequest(`/api/putCoin?amount=${amount}`, response => {
                this.balance = response.data;
            });
        },
        returnChange() {
            this.apiRequest(`/api/getCoins`, response => {
                alert(response.data.before);
                this.balance = response.data.after;
            });
        },
        buyDrink(drink) {
            this.apiRequest(`/api/buyDrink?drink=${drink}`, response => {
                if(response.data.error) {
                    alert(response.data.error);
                    return;
                }
                alert(`Enjoy your ${drink}!`);
                this.balance = response.data.balance;
            });
        },
        apiRequest(url, thenCallback) {
            this.loading = true;
            axios.get(url)
                .then(thenCallback)
                .catch(error => {
                    alert('Error fetching data: ', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }
}).mount('#app')