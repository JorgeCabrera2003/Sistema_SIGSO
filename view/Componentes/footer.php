<!-- ======= Footer ======= -->
<script>$(document).ready(function() {

    $('.notification-btn').click(function(e) {
        e.stopPropagation();
        cargarNotificacionesMenu();
    });


    actualizarContadorMenu();
    setInterval(actualizarContadorMenu, 30000000); // Cada 30 segundos
});

function cargarNotificacionesMenu() {
    $.ajax({
        url: '?page=notificacion',
        type: 'POST',
        data: { consultar: true, limit: 5 },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if(data.resultado == 'consultar') {
                    renderNotificacionesMenu(data.datos);
                }
            } catch(e) {
                console.error('Error al parsear notificaciones:', e);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar notificaciones:', error);
        }
    });
}

function renderNotificacionesMenu(notificaciones) {
    var container = $('#notificaciones-container');
    container.empty();

    if(notificaciones.length === 0) {
        container.append('<div class="notification-empty">No hay notificaciones nuevas</div>');
        return;
    }

    notificaciones.forEach(function(notif) {
        var item = $('<div class="notification-item"></div>');
        if(notif.estado == 'Nuevo') {
            item.addClass('nueva');
        }

        item.append(`
            <div class="notification-icon ${getIconClass(notif.modulo)}">
                <i class="${getIcon(notif.modulo)}"></i>
            </div>
            <div class="notification-content">
                <p class="notification-title">${notif.modulo}</p>
                <p class="notification-text">${notif.mensaje}</p>
                <p class="notification-time">${notif.fecha} ${notif.hora}</p>
            </div>
        `);

        item.click(function() {
            $.ajax({
                url: '?page=notificacion',
                type: 'POST',
                data: { marcar_leido: true, id: notif.id }
            });


            window.location.href = getModuleLink(notif.modulo);
        });

        container.append(item);
    });
}

function actualizarContadorMenu() {
    $.ajax({
        url: '?page=notificacion',
        type: 'POST',
        data: { contar_nuevas: true },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if(data.resultado == 'contar') {
                    $('.notification-badge').text(data.total);
                    if(data.total > 0) {
                        $('.notification-badge').show();
                    } else {
                        $('.notification-badge').hide();
                    }
                }
            } catch(e) {
                console.error('Error al parsear contador:', e);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al actualizar contador:', error);
        }
    });
}

function getIconClass(modulo) {
    switch(modulo) {
        case 'Solicitudes': return 'info';
        case 'Bitácora': return 'warning';
        case 'Usuarios': return 'danger';
        default: return 'primary';
    }
}

function getIcon(modulo) {
    switch(modulo) {
        case 'Solicitudes': return 'fas fa-clipboard-list';
        case 'Bitácora': return 'fas fa-book';
        case 'Usuarios': return 'fas fa-users';
        default: return 'fas fa-bell';
    }
}

function getModuleLink(modulo) {
    switch(modulo) {
        case 'Solicitudes': return '?page=solicitud';
        case 'Bitácora': return '?page=bitacora';
        case 'Usuarios': return '?page=usuario';
        case 'Materiales': return '?page=material';
        default: return '?page=notificacion';
    }
}</script>
<footer id="footer" class="footer bottom">
    <div class="copyright">
      &copy; Copyright <strong><span>OFITIC</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Designed by <a href="#">J. Cabrera, L. Torrealba & M. Bokor</a>
    </div>

</footer>