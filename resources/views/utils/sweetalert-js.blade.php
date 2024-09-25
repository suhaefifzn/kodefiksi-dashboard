<script src="/assets/js/sweetalert2.js"></script>
<script>

    /**
     * @param {string} type - success, error, warning, or info
     * @param {string} message - default value is 'success'
     * @param {number} duration - duration for toast. Default is 5000ms
     * @param {string} position - defaul position is 'top-right'
     **/
    const toast = (type = 'success', message = 'Success', duration = 5000, position = 'top-right') => {
        return Swal.fire({
            text: message,
            icon: type,
            toast: true,
            timer: duration,
            timerProgressBar: true,
            showConfirmButton: false,
            position
        });
    };

    /**
     * @param {string} type - warning or info
     * @param {string} message - defaul value is 'Alert'
     * @param {string} cancelButtonText - default value is 'Cancel'
     * @param {string} confirmButtonText - default value is 'Yes!'
     **/
    const alertConfirm = (type = 'warning', message = 'Alert', cancelButtonText = 'Cancel', confirmButtonText = 'Yes!') => {
        return Swal.fire({
            text: message,
            icon: type,
            showCancelButton: true,
            cancelButtonText,
            confirmButtonText
        });
    };
</script>
