<div class="container pb-5 mt-5" style="padding-top: 104px;">
    <div class="row row-gap-5">
        <!-- Primera sección - Datos del Usuario -->
        <div class="col-md-12">
            <h2>Datos del Usuario</h2>
            <div class="row">
                <div class="col-md-4 container-img d-flex justify-content-center align-items-center">
                    <img src="https://cdn.dribbble.com/users/4307805/screenshots/15598347/media/a6be63d86327c045f08e58e6b26084c7.png?resize=600x600&vertical=center" alt="Foto de Perfil" class="img-fluid rounded-circle" style="max-height: 300px; overflow: hidden;">
                </div>
                <div class="col-md-8">
                    <!-- Formulario para los datos del usuario -->
                    <form class="row align-content-between h-100">

                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text">Nombre y Apellido</span>
                                <label class="visually-hidden" for="name">Nombre</label>
                                <input id="name" name="name" type="text" class="form-control" class="form-control">
                                <label class="visually-hidden" for="surname">Apellido</label>
                                <input id="surname" name="surname" type="text" class="form-control" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">+54</span>
                                <label class="visually-hidden" for="phone"></label>
                                <input id="phone" name="phone" type="number" class="form-control" placeholder="N° Celular">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="input-group">
                                <label class="visually-hidden" for="email">Correo Electrónico</label>
                                <input id="email" name="email" type="text" class="form-control" placeholder="Correo Electrónico">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <label class="visually-hidden" for="gender">Género</label>
                                <select name="gender" id="gender" class="form-select">
                                    <option value="0" disabled selected>
                                        Selecciona tu género
                                    </option>
                                    <option value="1">
                                        Masculino
                                    </option>
                                    <option value="2">
                                        Femenino
                                    </option>
                                    <option value="3">
                                        Otro
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="input-group">
                                <label class="visually-hidden" for="address">Dirección</label>
                                <input id="address" name="address" type="text" class="form-control" placeholder="Dirección">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <label class="visually-hidden" for="city">Barrio</label>
                                <input id="city" name="city" type="text" class="form-control" placeholder="Ciudad">
                            </div>

                        </div>

                        <div class="col-md-8">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                                <label class="form-check-label" for="flexSwitchCheckChecked">Recibir novedades por email</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn w-100">Guardar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Segunda sección - Compras del Usuario -->
        <div class="col-md-12 mt-4">
            <h2>Compras Realizadas</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Imagen</th>
                        <th>Fecha de Compra</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Método de Pago</th>
                        <th>Estado de la Compra</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Producto 1</td>
                        <td>Categoría A</td>
                        <td>$50.00</td>
                        <td><img src="ruta-a-la-imagen.jpg" alt="Imagen de Producto" class="img-fluid"></td>
                        <td>01/09/2023</td>
                        <td>2</td>
                        <td>$100.00</td>
                        <td>Tarjeta de Crédito</td>
                        <td>Entregado</td>
                    </tr>
                    <!-- Agrega más filas para otras compras aquí -->
                </tbody>
            </table>
        </div>

    </div>
</div>