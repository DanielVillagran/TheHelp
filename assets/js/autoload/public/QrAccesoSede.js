var qrScanner = null;
var qrScannerActivo = false;
var ultimoQrLeido = null;
var qrBadgeTimeout = null;
var ubicacionGlobal = null;
var registroCompletado = false;

$(document).ready(function () {
    validarUbicacionInicial();
});

function obtenerUbicacionActual() {
    return new Promise(function (resolve, reject) {
        if (!navigator.geolocation) {
            reject(new Error("Tu dispositivo no soporta geolocalizacion."));
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
                resolve({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
            },
            function () {
                reject(new Error("No fue posible obtener la ubicacion del dispositivo."));
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
}

function setEstadoGeneral(texto) {
    var $texto = $("#qr_public_status_text");
    var $box = $texto.closest(".qr-status-box");
    $texto.text(texto || "");

    if (texto && $.trim(texto) !== "") {
        $box.show();
    } else {
        $box.hide();
    }
}

function mostrarLectorQr() {
    $("#qr_reader_shell").show();
    $("#qr_scanner_status").text("Escanea el QR del colaborador.");
    iniciarLectorQr();
}

function ocultarLectorQr() {
    $("#qr_reader_shell").hide();
    $("#qr_scanner_status").text("El lector esta deshabilitado.");
    ocultarBadgeColaboradorQr();
    detenerLectorQr();
}

function mostrarBadgeColaboradorQr(data, type) {
    if (qrBadgeTimeout) {
        clearTimeout(qrBadgeTimeout);
    }

    var badge = $("#qr_colaborador_badge");
    badge.removeClass("error");
    if (type === "error") {
        badge.addClass("error");
    }

    $("#qr_colaborador_badge_nombre").text(data.nombre || "Colaborador detectado");
    $("#qr_colaborador_badge_codigo").text("Codigo: " + (data.codigo || "-"));
    $("#qr_colaborador_badge_id").text("ID: " + (data.colaborador_id || "-"));
    badge.fadeIn(200);

    qrBadgeTimeout = setTimeout(function () {
        ocultarBadgeColaboradorQr();
    }, 10000);
}

function ocultarBadgeColaboradorQr() {
    if (qrBadgeTimeout) {
        clearTimeout(qrBadgeTimeout);
        qrBadgeTimeout = null;
    }

    $("#qr_colaborador_badge").hide().removeClass("error");
    $("#qr_colaborador_badge_nombre").text("");
    $("#qr_colaborador_badge_codigo").text("");
    $("#qr_colaborador_badge_id").text("");
}

function iniciarLectorQr() {
    if (registroCompletado || qrScannerActivo || typeof Html5Qrcode === "undefined") {
        return;
    }

    qrScanner = new Html5Qrcode("qr-reader");
    qrScanner.start({
            facingMode: "environment"
        }, {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        },
        function (decodedText) {
            procesarQrEscaneado(decodedText);
        },
        function () {}
    ).then(function () {
        qrScannerActivo = true;
    }).catch(function () {
        $("#qr_scanner_status").text("No fue posible iniciar la camara.");
        setEstadoGeneral("No fue posible iniciar la camara del dispositivo.");
    });
}

function detenerLectorQr() {
    if (!qrScanner || !qrScannerActivo) {
        qrScanner = null;
        qrScannerActivo = false;
        return;
    }

    qrScanner.stop().then(function () {
        qrScanner.clear();
        qrScanner = null;
        qrScannerActivo = false;
    }).catch(function () {
        qrScanner = null;
        qrScannerActivo = false;
    });
}

async function validarUbicacionInicial() {
    try {
        setEstadoGeneral("Obteniendo ubicacion del dispositivo...");
        ubicacionGlobal = await obtenerUbicacionActual();

        $.ajax({
            url: "/QrAcceso/validar_posicion",
            type: "POST",
            data: {
                sede_id: $("#sede_id").val(),
                lat: ubicacionGlobal.lat,
                lng: ubicacionGlobal.lng
            },
            dataType: "json",
            success: function (data) {
                if (data && data.status && data.en_rango) {
                    setEstadoGeneral("");
                    mostrarLectorQr();
                    return;
                }

                ocultarLectorQr();
                setEstadoGeneral((data && data.mensaje) ? data.mensaje : "No fue posible validar la ubicacion.");
                swal("Ubicacion", (data && data.mensaje) ? data.mensaje : "No fue posible validar la ubicacion.", "warning");
            },
            error: function () {
                ocultarLectorQr();
                setEstadoGeneral("No fue posible validar la ubicacion.");
                swal("Error", "No fue posible validar la ubicacion.", "error");
            }
        });
    } catch (error) {
        ocultarLectorQr();
        setEstadoGeneral(error.message);
        swal("Ubicacion", error.message, "warning");
    }
}

function procesarQrEscaneado(token) {
    if (registroCompletado || !token || token === ultimoQrLeido) {
        return;
    }

    ultimoQrLeido = token;
    $("#qr_scanner_status").text("Validando QR...");

    $.ajax({
        url: "/QrAcceso/decode_qr_colaborador",
        type: "POST",
        data: {
            token: token,
            lat: ubicacionGlobal.lat,
            lng: ubicacionGlobal.lng,
            sede_id: $("#sede_id").val(),
            empresa_id: $("#empresa_id").val()
        },
        dataType: "json",
        success: function (data) {
            if (data && data.status) {
                registroCompletado = true;
                $("#qr_scanner_status").text("Colaborador detectado con ID " + data.colaborador_id + ".");
                setEstadoGeneral("Registro exitoso. Cerrando pantalla...");
                mostrarBadgeColaboradorQr(data);
                detenerLectorQr();
                $("#qr_closing_box").show();
                intentarCerrarPantalla();
                return;
            }

            $("#qr_scanner_status").text((data && data.mensaje) ? data.mensaje : "No fue posible leer el QR.");
            mostrarBadgeColaboradorQr({
                nombre: "QR invalido",
                codigo: (data && data.mensaje) ? data.mensaje : "No fue posible leer el QR.",
                colaborador_id: "-"
            }, "error");
            resetUltimoQr();
        },
        error: function () {
            $("#qr_scanner_status").text("No fue posible validar el QR.");
            mostrarBadgeColaboradorQr({
                nombre: "Error de lectura",
                codigo: "No fue posible validar el QR.",
                colaborador_id: "-"
            }, "error");
            resetUltimoQr();
        }
    });
}

function resetUltimoQr() {
    setTimeout(function () {
        ultimoQrLeido = null;
    }, 2000);
}

function intentarCerrarPantalla() {
    setTimeout(function () {
        window.open("", "_self");
        window.close();

        setTimeout(function () {
            if (!window.closed) {
                $("#qr_closing_box").text("Registro completado. Si tu navegador no permite cerrar esta pestaña automaticamente, puedes cerrarla manualmente.");
            }
        }, 1200);
    }, 900);
}
