import Swal from 'sweetalert2';

const posSwal = Swal.mixin({
    customClass: {
        container: 'swal2-custom-zindex'
    },
    buttonsStyling: true,
    confirmButtonColor: '#337daf',
});

export default posSwal;