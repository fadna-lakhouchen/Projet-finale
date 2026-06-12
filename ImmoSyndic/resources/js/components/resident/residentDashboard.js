export default () => ({
    search: '',
    paid: false,
    processing: false,
    done: false,
    
    processPayment() {
        this.processing = true;
        setTimeout(() => {
            this.processing = false;
            this.done = true;
        }, 2000);
    }
});