<script>
    const getGreetingMessage = () => {
        const currentHour = new Date().getHours();

        if (currentHour >= 5 && currentHour < 12) {
            return 'Good Morning';
        } else if (currentHour >= 12 && currentHour < 15) {
            return 'Good Afternoon';
        } else if (currentHour >= 15 && currentHour < 18) {
            return 'Good Evening';
        } else {
            return 'Good Night';
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
