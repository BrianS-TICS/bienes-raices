<fieldset>
    <legend>Informacion general</legend>
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="vendedor[nombre]" placeholder="Nombre del vendedor" value="<?php echo sanitizar($vendedor->nombre); ?>">

    <label for="apellido">Nombre:</label>
    <input type="text" id="nombre" name="vendedor[apellido]" placeholder="Apellido del vendedor" value="<?php echo sanitizar($vendedor->nombre); ?>">
</fieldset>

<fieldset>
    <legend>Informacion extra</legend>
    <label for="telefono">Telefono:</label>
    <input type="number" id="nombre" name="vendedor[telefono]" placeholder="Telefono del vendedor" value="<?php echo sanitizar($vendedor->nombre); ?>">
</fieldset>