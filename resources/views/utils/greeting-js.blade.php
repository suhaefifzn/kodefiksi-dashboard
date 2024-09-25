<script>
    const getGreetingMessage = () => {
        const currentHour = new Date().getHours();

        if (currentHour >= 5 && currentHour < 12) {
            return 'Selamat Pagi';
        } else if (currentHour >= 12 && currentHour < 15) {
            return 'Selamat Siang';
        } else if (currentHour >= 15 && currentHour < 18) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    };

    const updateGreeting = () => {
        $('#greeting').text(getGreetingMessage());
    };

    // initial
    updateGreeting();

    // update every 1 minute
    setInterval(updateGreeting, 6000);
</script>
