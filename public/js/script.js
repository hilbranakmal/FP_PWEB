document.addEventListener('DOMContentLoaded', function() {

    const routineCheck = document.getElementById('isRoutineCheck');
    const routineOptions = document.getElementById('routineOptions');

    if (routineCheck && routineOptions) {
        routineCheck.addEventListener('change', function() {
            if (this.checked) {
                routineOptions.classList.remove('d-none');

                routineOptions.querySelector('input').focus();
            } else {
                routineOptions.classList.add('d-none');
            }
        });
    }


    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500); 
        }, 4000);
    });

    const deleteButtons = document.querySelectorAll('a[href*="delete_id"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); 
            const href = this.getAttribute('href');

            Swal.fire({
                title: 'Yakin mau hapus?',
                text: "Tugas yang dihapus tidak bisa dikembalikan loh!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E57373', 
                cancelButtonColor: '#8D6E63', 
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#FAF9F6', 
                color: '#4E342E' 
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href; 
                }
            });
        });
    });


    const doneButtons = document.querySelectorAll('a[href*="toggle_id"]');
    doneButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {

        });
    });

});

const editModal = document.getElementById('editTaskModal');
if (editModal) {
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget; 

        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const urgency = button.getAttribute('data-urgency');
        const deadline = button.getAttribute('data-deadline');
        const isRoutine = button.getAttribute('data-routine');
        const interval = button.getAttribute('data-interval');

        document.getElementById('edit_task_id').value = id;
        document.getElementById('edit_task_name').value = name;
        document.getElementById('edit_urgency').value = urgency;
        document.getElementById('edit_deadline').value = deadline;
        document.getElementById('edit_isRoutineCheck').checked = (isRoutine == '1');
        document.getElementById('edit_routine_interval').value = interval || '';

        if (isRoutine == '1') {
            document.getElementById('edit_routineOptions').classList.remove('d-none');
        } else {
            document.getElementById('edit_routineOptions').classList.add('d-none');
        }
    });

    const editRoutineCheck = document.getElementById('edit_isRoutineCheck');
    const editRoutineOptions = document.getElementById('edit_routineOptions');
    
    if (editRoutineCheck) {
        editRoutineCheck.addEventListener('change', function() {
            if (this.checked) {
                editRoutineOptions.classList.remove('d-none');
            } else {
                editRoutineOptions.classList.add('d-none');
            }
        });
    }
}