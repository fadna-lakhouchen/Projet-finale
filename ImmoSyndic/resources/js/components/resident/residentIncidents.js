export default () => ({
    success: false,
    
    submit() {
        this.success = true;
        this.$refs.form.reset();
        
        setTimeout(() => {
            this.success = false;
        }, 5000);
    }
});