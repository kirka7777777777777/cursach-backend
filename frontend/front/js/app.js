const app = Vue.createApp({
    data() {
        return {
            previewImage: 'img/your-image.jpg', // Укажите путь к изображению
            features: [
                'Маркетинг',
                'Управление проектами',
                'Самоорганизация',
                'Обучение'
            ]
        }
    },
    methods: {
        tryIt() {
            alert('Попробуйте наш сервис!');
        }
    }
});

app.mount('#app');
