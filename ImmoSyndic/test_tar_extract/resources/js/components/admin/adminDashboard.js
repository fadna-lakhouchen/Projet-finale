export default function adminDashboard() {
    return {
        countdown: 30,
        timer: null,

        init() {
            this.timer = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    clearInterval(this.timer);
                    window.location.reload();
                }
            }, 1000);
        },

        destroy() {
            clearInterval(this.timer);
        }
    };
}
