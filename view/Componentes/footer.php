<!-- ======= Footer ======= -->
<script>
    // Quitar la pantalla de carga de forma robusta:
    // - en load, DOMContentLoaded y pageshow
    // - y con un fallback a los 5s por si algo impide que los eventos se disparen (p. ej. redirecciones)
    (function(){
        function setPageReady(){
            try{
                document.documentElement.classList.add('page-ready');
            }catch(e){}
        }

        if (document.readyState === 'complete'){
            setPageReady();
        } else {
            window.addEventListener('load', setPageReady);
            document.addEventListener('DOMContentLoaded', setPageReady);
            window.addEventListener('pageshow', setPageReady);
        }

        // Fallback: si pasados 5s la clase no se añadió, forzarlo para evitar loader infinito
        setTimeout(function(){
            if (!document.documentElement.classList.contains('page-ready')){
                setPageReady();
            }
        }, 5000);
    })();
</script>
<script>$(document).ready(function() {

    $('.notification-btn').click(function(e) {
        e.stopPropagation();
        cargarNotificacionesMenu();
    });

    // (Se eliminó el botón 'Marcar todas' del dropdown por decisión de UX)
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
                ${notif.estado == 'Nuevo' ? `
                    <p class="notification-title"><strong>${notif.modulo}</strong></p>
                    <p class="notification-text"><strong>${notif.mensaje}</strong></p>
                ` : `
                    <p class="notification-title">${notif.modulo}</p>
                    <p class="notification-text">${notif.mensaje}</p>
                `}
                <p class="notification-time">${notif.fecha} ${notif.hora}</p>
            </div>
                <div class="notification-actions">
                ${notif.estado == 'Nuevo' ? `<button class="btn btn-sm btn-light mark-read-btn" data-id="${notif.id}" title="Leer"><i class="fas fa-eye text-primary"></i></button>` : ''}
            </div>
        `);


        // Click en el item: marcar leído y navegar (comportamiento existente)
        item.click(function() {
            $.ajax({
                url: '?page=notificacion',
                type: 'POST',
                data: { marcar_leido: true, id: notif.id }
            });

            window.location.href = getModuleLink(notif.modulo);
        });

        // Handler para el botón de marcar como leído (sin navegar)
        item.find('.mark-read-btn').click(function(e) {
            e.stopPropagation();
            var id = $(this).data('id');
            var boton = $(this);
            $.ajax({
                url: '?page=notificacion',
                type: 'POST',
                data: { marcar_leido: true, id: id },
                success: function(response) {
                    try {
                        var res = JSON.parse(response);
                        if(res.resultado == 'actualizar') {
                            // actualizar UI local
                            item.removeClass('nueva');
                            boton.remove();
                            // quitar negrita (strong) del título y mensaje en el dropdown
                            item.find('.notification-title, .notification-text').each(function() {
                                $(this).html($(this).text());
                            });
                            actualizarContadorMenu();
                        }
                    } catch(err) {
                        console.error('Error al parsear respuesta marcar_leido:', err);
                    }
                },
                error: function(xhr, status, err) {
                    console.error('Error al marcar notificación:', err);
                }
            });
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
                    $('#badge-notificacion').text(data.total);
                    if(data.total > 0) {
                        $('#badge-notificacion').show();
                    } else {
                        $('#badge-notificacion').hide();
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