+un usuario tiene varios ticket
+un usuario tiene informacion pesonal incluye ip address telefono email direccion
+un usuario tiene informacion tècnica: antena, router, clave, tipo de intalaciòn,
+los soportes q info afectan?
afectan info tecnica por q le hace cambos y deben quedar registrados
osea q el ticket afcta la informacion tècnica pero queda guardado en la tabla de informacion tecnica
y en la talba tickets que constacncia de lo q habìa
se guarda la informaciòn tècnica y luego se coje la info del ticket cerradoo abierto y la actualiza

CREAR NUEVO TICKET:
Parte de la info es personal -Parte de la info es tecnica
informacion para backup:
telefono
email
ipaddress
antena :agregar
velocidad
precio-plan
router :agregar
acceso-remoto :agregar
tipo-instalacion :agregar
direccion
mac-address-router :agregar
mac-address-antena :agregar
inyector-poe :agregar
apuntamiento :agregar

informacion propia del ticket:
id
backup-telefono
backup-email
backup-ipaddress
backup-antena
backup-velocidad
backup-precio-plan
backup-router
backup-acceso-remoto
backup-tipo-instalacion
backup-direccion
telefono-contacto
solicitud-cliente
sugerencia-solucion
fecha-now
fecha-sugerida
hora
administrador
solucion
recomendaciones
status
precio-soporte




CERRAR TICKET:
Se selecciona el ticket abierto y luego se le modifica la info agregando la solucion

ipAddressCerrarTicket


<div class="footer-modal">
    <input type="submit" value="Enviar"><button class="icon-cancel"
        @click="continueToClosedTicketsModal(false)"></button>
</div>





<form v-on:submit.prevent="true">
    <div class="form-close-ticket">
        <div class="form-group">
            <p>Fecha: <span>19/07/2020</span></p>
            <p>Tècnico: <span>Juan Pablo</span></p>
        </div>
        <div class="form-group">
            <label for="cliente">Cliente</label>
            <input v-model="clientCerradoTicketSelected.cliente" type="text" id="cliente">
        </div>
        <div class="form-group">
            <label for="telefonoCliente">Telèfono Cliente</label>
            <input required v-model="clientCerradoTicketSelected.telefono" type="number" id="telefonoCliente">
        </div>
        <div class="form-group">
            <label for="telefono">Telèfono Contacto</label>
            <input v-model="clientCerradoTicketSelected.telefonoContacto" type="number" name="telefono" id="telefono">
        </div>
        <div class="form-group">
            <label for="clientAdd">Direcciòn</label>
            <input required v-model="clientCerradoTicketSelected.direccion" type="text" name="clientAdd" id="clientAdd">
        </div>
        <div class="form-group">
            <label for="mail">Email de cliente</label>
            <input required v-model="clientCerradoTicketSelected.email" type="email" name="mail" id="mail">
        </div>
        <div class="form-group">
            <div class="radio-button">
                <label for="ipAddress">Ip Address,cambiar ip:</label>
                <input type="radio" name="changeIp" @click="radioButtonDisabled=false"
                    :checked="clientCerradoTicketSelected.ip"> SI
                <input type="radio" name="changeIp" @click="radioButtonDisabled=true"
                    :checked="!clientCerradoTicketSelected.ip">NO
            </div>
            <input required placeholder="Ingrese Direcciòn Ip" v-model="clientCerradoTicketSelected.ip" type="text"
                name="ipAddress" id="ipAddress" :disabled="radioButtonDisabled">
        </div>
        <div class="form-group">
            <label for="routerModel">Marca de Router</label>
            <input required v-model="clientCerradoTicketSelected.marcaRouter" type="text" name="routerModel"
                id="routerModel">
        </div>
        <div class="form-group">
            <label for="routerMacAddress">MAC Address Router</label>
            <input v-model="clientCerradoTicketSelected.macRouter" type="text" name="routerMacAddress"
                id="routerMacAddress" placeholder="0-9 & A-F">
        </div>
        <div class="form-group">
            <label for="antenaMacAddress">MAC Address Antena</label>
            <input required v-model="clientCerradoTicketSelected.macAntena" type="text" name="antenaMacAddress"
                id="antenaMacAddress" placeholder="0-9 & A-F">
        </div>
        <div class="form-group">
            <label for="inyectorPoe">Inyector POE</label>
            <select required v-model="clientCerradoTicketSelected.inyectorPoe" id="inyectorPoe">
                <option value="inyectorUbiquiti">Ubiquiti</option>
                <option value="inyectorMikrotik">Mikrotik</option>
                <option value="inyectorOtro">Otro</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Apuntamiento">Apuntamiento</label>
            <select required v-model="clientCerradoTicketSelected.apuntamiento" id="Apuntamiento">
                <option value="montecristo">Montecristo</option>
                <option value="retiro">Retiro</option>
                <option value="calizas">Calizas</option>
                <option value="alcaravan">Alcaravan</option>
                <option value="sapitos">Sapitos</option>
                <option value="santa-ana-1">Santa Ana 1</option>
                <option value="santa-ana-2">Santa Ana 2</option>
                <option value="vereda-centro">Vereda Centro</option>
                <option value="barrio-costeños">Brr Costeños</option>
            </select>
        </div>
        <div class="select-group">
            <div class="form-group">
                <label for="router-remote-admin">Acceso Remoto habilitado</label>
                <select required v-model="clientCerradoTicketSelected.accesoRemoto" id="router-remote-admin">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo-antena">Tipo de Antena</label>
                <select required v-model="clientCerradoTicketSelected.tipoAntena" id="tipo-antena">
                    <option value="none">Ninguna</option>
                    <option value="mikrotik-lhg5">Mikrotik, LGH 5</option>
                    <option value="mikrotik-lhg5ac">Mikrotik, LHG5 AC</option>
                    <option value="mikrotik-metal">Mikrotik, METAL</option>
                    <option value="mikrotik-sxtlite2">Mikrotik, SXT LITE 2</option>
                    <option value="mikrotik-sxtlite5">Mikrotik, SXT LITE 5</option>
                    <option value="mikrotik-disclite5">Mikrotik, DISC LITE 5</option>
                    <option value="mikrotik-sqlite5">Mikrotik, SQ LITE 5</option>
                    <option value="mikrotik-otro">Mikrotik, OTRO</option>
                    <option value="ubiquiti-liteBeam">Ubiquiti, LITE BEAM</option>
                    <option value="ubiquiti-nanobride">Ubiquiti, NANOBRIDGE</option>
                    <option value="ubiquiti-powerbeam">Ubiquiti, POWERBEAM</option>
                    <option value="ubiquiti-nanostation">Ubiquiti, NANOSTATION</option>
                    <option value="ubiquiti-locom2">Ubiquiti, NANOSTATION LOCO M2</option>
                    <option value="ubiquiti-locom5">Ubiquiti, NANOSTATION LOCO M5</option>
                    <option value="ubiquiti-otro">Ubiquiti, OTRO</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo-instalacion">Tipo de Instalaciòn</label>
                <select required v-model="clientCerradoTicketSelected.tipoInstalacion" id="tipo-instalacion">
                    <option value="repetidor">X Repetidor</option>
                    <option value="antena">X Antena</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipo-soporte">Tipo de Soporte</label>
                <select required v-model="clientCerradoTicketSelected.tipoSoporte" id="tipo-soporte">
                    <option value="varios">Varios. Cuàles?</option>
                    <option value="ampliar-velocidad">Ampliaciòn de velocidad</option>
                    <option value="traslado">Traslado</option>
                    <option value="dano-antena">Daño de Antena</option>
                    <option value="clave">Cambio de clave</option>
                    <option value="dano-router">Daño de router</option>
                    <option value="dano-router">Daño de switch</option>
                    <option value="dano-cable">Daño de cable</option>
                    <option value="instalacionServicio">Instalacion de servicio</option>
                    <option value="direccionamiento">Direccionamiento de antena. Por què?</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="diagnostico">Solicitud de Cliente</label>
            <textarea v-model="clientCerradoTicketSelected.solicitudCliente" cols="" id="diagnostico"
                required></textarea>
        </div>
        <div class="form-group">
            <label for="solucion">Describir soluciòn</label>
            <textarea v-model="clientCerradoTicketSelected.solucion" cols="" id="solucion" required minlength="30"
                placeholder="Si recibe dinero, especificar"></textarea>
        </div>
        <div class="form-group">
            <label for="sugerencias">Sugerencias</label>
            <textarea v-model="clientCerradoTicketSelected.sugerencia" cols="" id="sugerencias"></textarea>
        </div>
        <div class="form-group">
            <label for="cargar-a-factura-valor">Cargar a factura valor</label>
            <input v-model="clientCerradoTicketSelected.cargarAfacturaVaĺor" type="number" name="cargar-a-factura-valor"
                id="cargar-a-factura-valor" placeholder="Queda Pendiente Pagar?">
        </div>
        <div class="form-group">
            <label for="cargar-a-factura-descripcion">Cargar a factura descripciòn</label>
            <textarea v-model="clientCerradoTicketSelected.cargarAfacturaDescripcion" cols=""
                id="cargar-a-factura-descripcion" placeholder="Què es lo que queda pdte pagar?"></textarea>
        </div>
        <div class="form-group">
            <label for="resuelto">El problema fue resuelto?</label>
            <select required v-model="clientCerradoTicketSelected.problemaResuelto" id="resuelto">
                <option value="si">Si, ya se puede cerrar este ticket</option>
                <option value="no">No, queda pendiente.</option>
            </select>
        </div>

    </div>
    <div class="footer-modal">
        <input type="submit" value="Enviar"><span class="icon-cancel"
            @click="continueToClosedTicketsModal(false)"></span>
    </div>
</form>